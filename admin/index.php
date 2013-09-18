<?php
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
	include $_SERVER['DOCUMENT_ROOT'].'/includes/magicquotes.inc.php';
	include $_SERVER['DOCUMENT_ROOT'].'/includes/util.inc.php';
	include 'login.inc.php';
	
	if( isset( $_POST['action'] ) && $_POST['action'] == 'login' ){
		isLogged();
		header( 'Location: .' );
		exit();
	}
	
	if( isset( $_POST['action'] ) && $_POST['action'] == 'logout' ){
		logout( );
		header( 'Location: .' );
		exit();
	}
	
	if( isLoggedIn() )
		include 'logout.html.php';
	include 'admin.html.php';
?>
