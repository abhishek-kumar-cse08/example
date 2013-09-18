<?php
try{
	$pdo = new PDO( 'mysql:host=localhost;dbname=ijdb', 'ijdbuser', 'suresh');
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$pdo->exec( 'SET NAMES "utf8"' );
} catch( PDOException $e ) {
	$error = "Unable to connect to database : " . $e.getMessage();
	include 'error.inc.html.php';
	exit();
}
?>
