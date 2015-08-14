<?php
session_start();
ob_start();
require_once('config.php');


//Uncomment to report PHP errors.
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$hash = hash("sha256", $_POST['password']);
	
if ($hash == $APPROVED_HASH) {
	$_SESSION["adminvalido"] = 001;
	header("Refresh: 0; URL=menu.php");
}
else {
	header("Refresh: 0; URL=index.php");
}
?>
