<?php
error_reporting (E_ALL | E_STRICT );
#ini_set( 'mysql.trace_mode', true );

require_once "site-include.php";
require_once "lib/functions.php";
require_once "lib/Repository.php";
require_once "lib/MySqli.php";

$repository = new Repository( $database_address, $database_name, $database_user, $database_password );
$repository->connect();
$repository->remove()->create();

?>
