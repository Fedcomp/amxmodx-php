<?php php_sapi_name() == 'cli' or die('This script is supposed to run from console.');
require __DIR__.'/../../vendor/autoload.php';

use Amxmodx\Nvault;

$nvault_path = realpath(__DIR__.'/../../files') .  '/nvault';
if(!is_dir($nvault_path)) mkdir($nvault_path, 0775);

$nvault = new Nvault();
$faker = Faker\Factory::create();

for($i=0;$i < 10;$i++){
    $nick = $faker->userName;
    $vault_data = [
        'nick' => $nick,
        'frags' => $faker->numberBetween(0, $max = 200),
        'clan' => $faker->userName,
        'ip' => $faker->ipv4,
    ];

    $nvault->keydata = $vault_data;
    $file_path = $nvault_path .'/'. strtolower($nick) . ".vault";

    file_put_contents($file_path, $nvault->pack());
    echo "generating: ", $file_path, "\n";
}
