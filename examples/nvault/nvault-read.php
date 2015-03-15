<?php php_sapi_name() == 'cli' or die('This script is supposed to run from console.');
require __DIR__.'/../../vendor/autoload.php';

use Amxx\nvault;

$nvault_path = realpath(__DIR__.'/../../files') .  '/nvault';
if(!is_dir($nvault_path))
    die('No nvault files present in "files/nvault" directory. Run nvault-generate.php from console first.');

$nvault = new nvault();
$files = scandir($nvault_path);

foreach($files as $file){
    if(in_array($file, ['.', '..']) or substr($file, -strlen('.vault')) != '.vault') continue;
    try{
        $data = $nvault->initFromFile($nvault_path .'/'. $file)->parse()->keydata;
    }catch (\Amxx\InvalidNvault $e){
        echo $file, " is corrupted or not a NVault file (".$e->getMessage()."). Skipping.","\n";
        continue;
    }

    echo sprintf("%s > %s\n", $file, json_encode($data,1));
}