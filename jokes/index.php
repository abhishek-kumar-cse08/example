<?php
	include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
	include $_SERVER['DOCUMENT_ROOT'].'/includes/magicquotes.inc.php';
	include $_SERVER['DOCUMENT_ROOT'].'/includes/util.inc.php';
	include '../admin/login.inc.php';
	
	if( !isLoggedIn() ){
		include '../admin/login.html.php';
		exit();
	}
	
	if( !hasAccess( 'Content' ) ){
		$error = 'You do not have the Content Administration access rights required to access this page';
		include '../admin/accessdenied.html.php';
		exit();
	}
	
	include '../admin/logout.html.php';
	
//Add joke starts

	if( isset( $_GET['addjoke'] ) ){
		$action = 'add';
		$button = 'Add';
			
		$joketext = "";
		$authorid = "";
				
		$selectCat = NULL;
		
		try{
		$s = $pdo->query( "SELECT id, name FROM author" );	
		} catch ( PDOException $e ){
			$error = "Error retrieving authors : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
	
		$authors = $s->fetchAll();
	
		try{
			$s = $pdo->query( "SELECT id, name FROM category" );	
		} catch ( PDOException $e ){
			$error = "Error retrieving categories : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
	
		while( $row = $s->fetch() )
			$categories[] = array( 'id'=> $row['id'], 'name' => $row['name'], 'selected' => False );
			
		include 'add_edit_jokes.html.php';
		exit();
	}

//Add joke ends

//Service add joke starts
	if( isset( $_GET['add'] ) ){
		$place['joketext'] = $_POST['text'];
		if( !$_POST['author'] ){
			echo '<B>Go back and select a valid author!<B>';
			exit();
		}
		$place['authorid'] = $_POST['author'];
		
		try{
			$s = $pdo->prepare( 'INSERT INTO joke SET joketext = :joketext, authorid = :authorid, jokedate = NOW()');
			$s->execute( $place );
		} catch( PDOException $e ){
			$error = "Error adding joke : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		$id = $pdo->lastInsertId();
				
		$categories = $_POST['categories'];
		try{
			$s = $pdo->prepare( 'INSERT INTO jokecategory SET jokeid = :jokeid, categoryid = :categoryid');
			foreach( $categories as $category ){ 
				$s->bindValue( ':jokeid', $id );
				$s->bindValue( ':categoryid', $category );
				$s->execute();
			}
		} catch( PDOException $e ){
			$error = "Error inserting into joke-categories : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		header( 'Location: .' );
		exit();
	}
//Service add joke ends

//Edit joke starts
	if( isset( $_POST['edit'] ) ){
		$action = 'edit';
		$button = 'Update';
	
		$id = intval( $_POST['id'] );
		try{
			$sql = "SELECT id, joketext, authorid FROM joke WHERE id = $id";
			$s = $pdo->query( $sql );
		} catch( PDOException $e ){
			$error = "Error retrieving joke to edit : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		$joke = $s->fetch();
		$joketext = $joke['joketext'];
		$authorid = $joke['authorid'];
		
		try{
			$s = $pdo->query( "SELECT categoryid FROM jokecategory WHERE jokeid = $id" );	
		} catch ( PDOException $e ){
			$error = "Error retrieving selected categories : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		$selectCat = NULL;
		while( $row = $s->fetch() )
			$selectCat[] = $row['categoryid']; 
		
		try{
		$s = $pdo->query( "SELECT id, name FROM author" );	
		} catch ( PDOException $e ){
			$error = "Error retrieving authors : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
	
		$authors = $s->fetchAll();
	
		try{
			$s = $pdo->query( "SELECT id, name FROM category" );	
		} catch ( PDOException $e ){
			$error = "Error retrieving categories : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
	
		while( $row = $s->fetch() )
			$categories[] = array( 'id'=> $row['id'], 'name' => $row['name'], 'selected' => ( $selectCat != NULL and in_array( $row['id'], $selectCat ) ) );
			
		include 'add_edit_jokes.html.php';
		exit();
	}
//Edit joke ends

//Update joke starts
	if( isset( $_GET['edit'] ) ){
		$place['id'] = $_POST['id'];
		$place['joketext'] = $_POST['text'];
		if( !$_POST['author'] ){
			echo '<B>Go back and select a valid author!<B>';
			exit();
		}
		$place['authorid'] = $_POST['author'];
		
		try{
			$s = $pdo->prepare( 'UPDATE joke SET joketext = :joketext, authorid = :authorid WHERE id = :id');
			$s->execute( $place );
		} catch( PDOException $e ){
			$error = "Error updating joke : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		try{
			$s = $pdo->prepare( 'DELETE FROM jokecategory WHERE jokeid = :id');
			$s->bindValue( ':id', $_POST['id'] );
			$s->execute();
		} catch( PDOException $e ){
			$error = "Error deleting joke-categories : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		$categories = $_POST['categories'];
		try{
			$s = $pdo->prepare( 'INSERT INTO jokecategory SET jokeid = :jokeid, categoryid = :categoryid');
			foreach( $categories as $category ){ 
				$s->bindValue( ':jokeid', $_POST['id'] );
				$s->bindValue( ':categoryid', $category );
				$s->execute();
			}
		} catch( PDOException $e ){
			$error = "Error inserting into joke-categories : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		header( 'Location: .' );
		exit();
	}
//Update joke ends

//Delete joke starts
	if( isset( $_POST['delete'] ) ){
		$id = intval( $_POST['id'] );
		try{
			$sql = "DELETE FROM joke WHERE id = $id";
			$pdo->exec( $sql );
		} catch( PDOException $e ){
			$error = "Error deleting joke : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
				
		try{
			$pdo->exec( "DELETE FROM jokecategory WHERE jokeid=$id" );	
		} catch ( PDOException $e ){
			$error = "Error deleting categories : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		header( 'Location: .' );
		exit();
	}
//Delete joke ends

//Display Search Form Start

	try{
	$s = $pdo->query( "SELECT id, name FROM author" );	
	} catch ( PDOException $e ){
		$error = "Error retrieving authors : " . $e;
		include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
		exit();
	}
	
	$authors = $s->fetchAll();
	
	try{
	$s = $pdo->query( "SELECT id, name FROM category" );	
	} catch ( PDOException $e ){
		$error = "Error retrieving categories : " . $e;
		include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
		exit();
	}
	
	$categories = $s->fetchAll();
	$jokes = NULL;
	
	if( isset( $_GET['author'] ) and isset( $_GET['category'] ) ){
		$select = 'SELECT id, joketext ';
		$from = 'FROM joke ';
		$where = 'WHERE TRUE ';
		
		$place = NULL;
		
		if( $_GET['author'] ){
			$where .= 'AND authorid = :author ';
			$place[':author'] = $_GET['author'];  
		}
		
		if( $_GET['category'] ){
			$from .= 'INNER JOIN jokecategory ON id = jokeid ';
			$where .= 'AND categoryid = :category ';
			$place[':category'] = $_GET['category'];
		}
		
		if( $_GET['text'] ){
			$where .= 'AND joketext LIKE :text ';
			$place[':text'] = '%'.$_GET['text'].'%';
		}
		
		try{
			$sql = $select.$from.$where;
			$s = $pdo->prepare( $sql );
			$s->execute( $place );
		} catch( PDPException $e ){
			$error = "Error retrieving authors : " . $e;
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		$jokes = $s->fetchAll();
	}
	
	include 'jokes.html.php';
	exit();	
//Display Search Form Ends


