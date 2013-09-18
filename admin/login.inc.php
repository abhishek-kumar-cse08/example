<?php
	function isLoggedIn(){
		include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
		session_start();
		
		if( isset( $_POST['email'] ) && isset( $_POST['password'] ) ){
			$email = $_POST['email'];
			$password = md5( $_POST['password'] . 'ijdb' );
			
			try{ 
				$sql = 'SELECT COUNT(*) FROM author WHERE email = :email AND password = :password';
				$s = $pdo->prepare( $sql );
				$place = array( ':email'=>$email, 'password'=>$password );
				$s->execute( $place );
			} catch( PDOException $e ) {
				$error = 'Error cheking login credentials : ' . $e->getMessage();
				include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
				exit();
			}
			
			$row = $s->fetch();
			if( $row[0] > 0 ){
				$_SESSION['email'] = $email;
				$_SESSION['password'] = $password;
				return True;
			}
		}
		
		if( isset( $_SESSION['email'] ) && isset( $_SESSION['password'] ) ){
			$email = $_SESSION['email'];
			$password = $_SESSION['password'];
			
			try{ 
				$sql = 'SELECT COUNT(*) FROM author WHERE email = :email AND password = :password';
				$s = $pdo->prepare( $sql );
				$place = array( ':email'=>$email, 'password'=>$password );
				$s->execute( $place );
			} catch( PDOException $e ) {
				$error = 'Error cheking login credentials : ' . $e->getMessage();
				include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
				exit();
			}
			
			$row = $s->fetch();
			if( $row[0] > 0 ){
				return True;
			}
		}
		
		return False;
	}
	
	function hasAccess( $access ){
		include $_SERVER['DOCUMENT_ROOT'].'/includes/db.inc.php';
		session_start();
		$email = $_SESSION['email'];
		
		try{ 
			$sql = 'SELECT COUNT(*) FROM author INNER JOIN authorrole ON id = authorid WHERE email = :email AND roleid = :roleid';
			$s = $pdo->prepare( $sql );
			$place = array( ':email'=>$email, ':roleid'=>$access );
			$s->execute( $place );
		} catch( PDOException $e ) {
			$error = 'Error cheking access : ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'].'/includes/error.inc.html.php';
			exit();
		}
		
		$row = $s->fetch();
		if( $row[0] > 0 ){
			return True;
		}
		
		return False;
	}
	
	function logout(){
		session_start();
		
		$_SESSION = array();
		session_destroy();
	}
?>
