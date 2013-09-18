<?php
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
	include $_SERVER['DOCUMENT_ROOT'].'/includes/magicquotes.inc.php';
	include $_SERVER['DOCUMENT_ROOT'].'/includes/util.inc.php';
	include '../admin/login.inc.php';
	
	if( !isLoggedIn() ){
		include '../admin/login.html.php';
		exit();
	}
	
	if( !hasAccess( 'Site' ) ){
		$error = 'You do not have the Site Administration access rights required to access this page';
		include '../admin/accessdenied.html.php';
		exit();
	}
	
	include '../admin/logout.html.php';
	
//Add Category Start

	if( isset( $_GET['addcategory'] ) ){
		$id = "";
		$name = "";
		$button = "Add";
		$submit = "addcategory";
		
		include 'add_edit_category.html.php';
		exit();
	}
	
	if( isset( $_POST[ 'addcategory' ] ) ){
		$place = array( 'name' => $_POST[ 'name' ] );
		
		try{
			$s = $pdo->prepare( 'INSERT INTO category SET name = :name' );
			$s->execute( $place );
		} catch( PDOException $e ){
			$error = 'Error adding category : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		header( 'Location: .' );
		exit(); 
	}

//Add Category Finish
	
//Edit Category Start
	
	if( isset( $_POST['action'] ) and $_POST['action'] == 'edit' ){
		$id = intval( $_POST['id'] );
		try{
			$s = $pdo->prepare( 'SELECT * FROM category WHERE id = :id' );
			$s->bindValue( ':id', $id );
			$s->execute();
		} catch ( PDOException $e ) {
			$error = 'Error getting category : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		$row = $s->fetch();
		
		$name = $row['name'];
		$button = "Edit";
		$submit = "editcategory";
		
		include 'add_edit_category.html.php';
		exit();
	}
	
	if( isset( $_POST[ 'editcategory' ] ) ){
		$place = array( 'id' => $_POST[ 'id' ], 'name' => $_POST[ 'name' ] );
		
		try{
			$s = $pdo->prepare( 'UPDATE category SET name = :name WHERE id = :id');
			$s->execute( $place );
		} catch( PDOException $e ){
			$error = 'Error editing category : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		header( 'Location: .' );
		exit(); 
	}

//Edit Category Finish

//Delete Category Start

	if( isset( $_POST['action'] ) and $_POST['action'] == 'delete' ){
		$id = intval( $_POST['id'] );

		$s = $pdo->prepare( 'DELETE FROM jokecategory WHERE categoryid = :id' );
		try{
			$s->bindValue( ':id', $id['id'] );
			$s->execute();
		} catch( PDOException $e ){
			$error = 'Error delting entries from jokecategory : '.$e->getMessage();
	include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
	exit();
		}
				
		try{
			$s = $pdo->prepare( 'DELETE FROM category WHERE id = :id');
			$s->bindValue( ':id', $id );
			$s->execute();
		} catch ( PDOException $e ) {
			$error = 'Error deleting category : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		header( 'Location: .' );
		exit();
	}
	
//Delete Categories Finish

//Display Categories

	try{
		$result = $pdo->query( 'SELECT id, name FROM category' );
	} catch( PDOException $e ){
		$error = 'Error obtaining list of categories : '.$e->getMessage();
		include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
		exit();
	}
	
	foreach( $result as $row ){
		$categories[] = array( 'id'=>$row['id'], 'name'=>$row['name'] );
	}
	
	include 'categories.html.php';

