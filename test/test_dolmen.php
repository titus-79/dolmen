<?php
namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Titus\Dolmen\Models\Group;

$admin = new \Model\Group();
$admin->setName("Admin");
$admin->save();

$member = new \Model\Group();
$member->setName("Member");
$member->save();

//$datenow = new \DateTime();
//var_dump($datenow);
//$datenow = new DateTime();
//$datenow= $datenow->format('Y-m-d H:i:s');
//
//var_dump($datenow);
