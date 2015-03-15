<?php

namespace Amxx;
use PhpBinaryReader\BinaryReader;

class InvalidNvault extends \Exception {}

class nvault extends BinaryReader{
    public $keydata = NULL;
    public $extkeydata = NULL;

    function __construct(){}

    public function initFromString($str){
        parent::__construct($str);
        return $this;
    }

    public function initFromFile($file_path){
        if(!file_exists($file_path))
            throw new \Exception("file '$file_path' is not found");

        $file = file_get_contents($file_path);
        parent::__construct($file);
        return $this;
    }

    public function parse(){
        $this->keydata = NULL;
        $this->extkeydata = NULL;

        if( strrev($this->readString(4)) !== 'nVLT' )
            throw new InvalidNvault('invalid magic');

        $this->readUInt16();    // NVault version

        $entries = $this->readInt32();

        for($i=0;$i < $entries; $i++){
            $timestamp = $this->readInt32();
            $keyLen = $this->readUInt8();
            $valLen = $this->readUInt16();

            if($keyLen == 0) throw new InvalidNvault(sprintf('Found empty key length'));
            $key = $this->readString($keyLen);

            if($valLen > 0) // FIXME: Check if NVault supports empty keys/values
                $value = $this->readString($valLen);
            else
                $value = '';

            $this->keydata[$key] = $value;
            $this->extkeydata[$key] = array(
                'timestamp' => $timestamp,
                'value'     => $value
            );
        }

        return $this;
    }

    public function pack(){
        $packed = '';

        $packed .=  strrev('nVLT').
                    pack('S', 512).
                    (count($this->keydata) ?
                        pack('l', count($this->keydata))
                    :
                        pack('l', -1));

        foreach($this->keydata as $k=>$v){
            $packed .=  pack('l', isset($this->extkeydata[$k]['timestamp']) ? $this->extkeydata[$k]['timestamp'] : time()).
                        pack('C', strlen($k)). // keyLen UInt8
                        pack('S', strlen($v)).  // valLen UInt16
                        $k.$v; // Dump Key/Value
        }

        return $packed;
    }
}