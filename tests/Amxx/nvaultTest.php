<?php
declare(strict_types=1);

use Amxx\Nvault;
use PHPUnit\Framework\TestCase;

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
        $result = $this->nvault->initFromFile($this->nvault_path)->parse();
        $this->assertEquals($nvault_file, $result);
    }
}
