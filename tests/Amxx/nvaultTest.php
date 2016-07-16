<?php

use Amxx\Nvault;

class NvaultTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->nvault_path = __DIR__ . '/../files/unknown.vault';
        $this->nvault = new Nvault();
    }

    public function testEqualGeneration()
    {
        $nvault_file = file_get_contents($this->nvault_path);
        $result = $this->nvault->initFromFile($this->nvault_path)->parse();
        $this->assertEquals($nvault_file, $result);
    }
}
