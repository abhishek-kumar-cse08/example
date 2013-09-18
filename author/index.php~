<?php
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
	include $_SERVER['DOCUMENT_ROOT'].'/includes/magicquotes.inc.php';
	include $_SERVER['DOCUMENT_ROOT'].'/includes/util.inc.php';
	include '../admin/login.inc.php';
	
	if( !isLoggedIn() ){
		include '../admin/login.html.php';
		exit();
	}
	
	if( !hasAccess( 'Account' ) ){
		$error = 'You do not have the Account Administration access rights required to access this page';
		include '../admin/accessdenied.html.php';
		exit();
	}
	
	include '../admin/logout.html.php';
//Add Author Start
	
	if( isset( $_GET['addauthor'] ) ){
		$id = "";
		$name = "";
		$email = "";
		$button = "Add";
		$submit = "addauthor";
		
		$sql = "SELECT id FROM role";
		$s = $pdo->query( $sql );
		
		$roles = $s->fetchAll();
		
		for( $i=0; $i<count($roles); $i++ ){
			$roles[$i]['selected'] = false;
		}
		
		include 'add_edit_author.html.php';
		exit();
	}
	
	if( isset( $_POST[ 'addauthor' ] ) ){
		if(!$_POST['name'] || !$_POST['email'] || !$_POST['password']){
			$error = 'One or more of the fields are empty. Go back and ensure they are filled';
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		$place = array( 'name' => $_POST[ 'name' ], 'email' => $_POST[ 'email' ], 'password' => md5( $_POST['password'] . 'ijdb' ) );
		
		try{
			$s = $pdo->prepare( 'INSERT INTO author SET name = :name, email = :email, password = :password' );
			$s->execute( $place );
		} catch( PDOException $e ){
			$error = 'Error adding author : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		$roles = $_POST['roles'];
		$authorid = $pdo->lastInsertId(); 
		$sql = 'INSERT INTO authorrole SET authorid = :authorid, roleid = :roleid';
		$s = $pdo->prepare( $sql );
		foreach( $roles as $role ){
			$pass = array( ':authorid'=>$authorid, ':roleid'=>$role );
			$s->execute( $pass );
		}
		
		header( 'Location: .' );
		exit(); 
	}

//Add Author Finish

//Edit Author Start
	
	if( isset( $_POST['action'] ) and $_POST['action'] == 'edit' ){
		$id = intval( $_POST['id'] );
		try{
			$s = $pdo->prepare( 'SELECT * FROM author WHERE id = :id' );
			$s->bindValue( ':id', $id );
			$s->execute();
		} catch ( PDOException $e ) {
			$error = 'Error getting author : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		$row = $s->fetch();
		
		$name = $row['name'];
		$email = $row['email'];
		$button = "Edit";
		$submit = "editauthor";
		
		$sql = 'SELECT id FROM role';
		$s = $pdo->query( $sql );
		
		$roles = $s->fetchAll();
		
		$sql = "SELECT roleid FROM authorrole WHERE authorid = $id";
		$s = $pdo->query( $sql );
		
		$authorrole = array();
		while( $row = $s->fetch() )
			$authorrole[] = $row['roleid']; 
		
		for( $i=0; $i<count($roles); $i++ ){
			$roles[$i]['selected'] = in_array( $roles[$i]['id'], $authorrole );
		}
		
		include 'add_edit_author.html.php';
		exit();
	}
	
	if( isset( $_POST[ 'editauthor' ] ) ){
		if(!$_POST['name'] || !$_POST['email'] || !$_POST['password']){
			$error = 'One or more of the fields are empty. Go back and ensure they are filled';
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
	
		$place = array( 'id' => $_POST[ 'id' ], 'name' => $_POST[ 'name' ], 'email' => $_POST[ 'email' ], 'password' => md5( $_POST['password'] . 'ijdb' ) );
		
		try{
			$s = $pdo->prepare( 'UPDATE author SET name = :name, email = :email, password = :password WHERE id = :id');
			$s->execute( $place );
		} catch( PDOException $e ){
			$error = 'Error editing author : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		$authorid = intval( $_POST['id'] );
		$pdo->exec("DELETE FROM authorrole WHERE authorid = $authorid");
		
		$roles = $_POST['roles'];
		$sql = 'INSERT INTO authorrole SET authorid = :authorid, roleid = :roleid';
		$s = $pdo->prepare( $sql );
		foreach( $roles as $role ){
			$pass = array( ':authorid'=>$authorid, ':roleid'=>$role );
			$s->execute( $pass );
		}
		
		header( 'Location: .' );
		exit(); 
	}

	
//Edit Author Finish

//Delete Author Start

if( isset( $_POST['action'] ) and $_POST['action'] == 'delete' ){
		$id = intval( $_POST['id'] );
		try{
			$s = $pdo->prepare( 'SELECT id FROM joke WHERE authorid = :id' );
			$s->bindValue( ':id', $id );
			$s->execute();
		} catch ( PDOException $e ) {
			$error = 'Error getting jokes by author : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		$jokes = $s->fetchAll();
		$s = $pdo->prepare( 'DELETE FROM jokecategory WHERE jokeid = :id' );
		foreach( $jokes as $jokeid ){
			try{
				$s->bindValue( ':id', $jokeid['id'] );
				$s->execute();
			} catch( PDOException $e ){
				$error = 'Error delting entries from jokecategory : '.$e->getMessage();
		include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
		exit();
			}
		}
		
		try{
			$s = $pdo->prepare( 'DELETE FROM joke WHERE authorid = :id' );
			$s->bindValue( ':id', $id );
			$s->execute();
		} catch ( PDOException $e ) {
			$error = 'Error deleting jokes by author : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		try{
			$s = $pdo->prepare( 'DELETE FROM authorrole WHERE authorid = :id' );
			$s->bindValue( ':id', $id );
			$s->execute();
		} catch ( PDOException $e ) {
			$error = 'Error deleting roles by author : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		try{
			$s = $pdo->prepare( 'DELETE FROM author WHERE id = :id' );
			$s->bindValue( ':id', $id );
			$s->execute();
		} catch ( PDOException $e ) {
			$error = 'Error deleting author : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		header( 'Location: .' );
		exit();
	}
//Delete Author Finish

//Display Authors
	try{
		$result = $pdo->query( 'SELECT id, name FROM author' );
	} catch( PDOException $e ){
		$error = 'Error obtaining list of authors : '.$e->getMessage();
		include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
		exit();
	}
	
	foreach( $result as $row ){
		$authors[] = array( 'id'=>$row['id'], 'name'=>$row['name'] );
	}
	
	include 'authors.html.php';

