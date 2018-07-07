<?php

use PHPUnit\Framework\TestCase;
use Amxx\Nvault;

class NvaultTest extends TestCase
{
    protected function setUp()
    {
        $this->nvault_path = __DIR__ . '/../files/unknown.vault';
        $this->nvault = new Nvault();
    }

    public function testEqualGeneration()
    {
        $nvault_file = file_get_contents($this->nvault_path);
        $result = $this->nvault->initFromFile($this->nvault_path)->parse()->pack();
        $this->assertEquals($nvault_file, $result);
    }
}
