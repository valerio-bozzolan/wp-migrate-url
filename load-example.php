<?php
// boz-php configuration file
// https://gitpull.it/w/suckless-php/

$username = 'user';
$password = 'pwd';
$database = 'database-name';
$location = 'localhost';

// Table prefix, if any!
$prefix = 'wp_';

// We can wait for this feature..
define('REQUIRE_LOAD_POST', false);

define('ABSPATH', __DIR__ );

// That's it! This will load the framework with the above configurations
// 	git clone https://gitpull.it/source/suckless-php/
require '/usr/share/php/suckless-php/load.php';
