<?php
use Model\Group;
use Model\User;

require_once 'app/Models/Group.php';
require_once 'app/Models/User.php';

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
