<?php
// suckless-php configuration file
// https://gitpull.it/w/suckless-php/

$username = 'user';
$password = 'pwd';
$database = 'database-name';
$location = 'localhost';

// Table prefix, if any!
$prefix = 'wp_';

// We can wait for this feature..
define('REQUIRE_LOAD_POST', false);

// this directory
define('ABSPATH', __DIR__ );

// This will load the framework
// To install the framework:
//      cd ..
// 	git clone https://gitpull.it/source/suckless-php/
require ABSPATH . '/../suckless-php/load.php';
