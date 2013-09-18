<?php
if ( get_magic_quotes_gpc() ) {
	$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	while (list($key, $val) = each($process)) {
		foreach ($val as $k => $v) {
			unset($process[$key][$k]);
			if (is_array($v)) {
				$process[$key][stripslashes($k)] = $v;
				$process[] = &$process[$key][stripslashes($k)];
			} else {
				$process[$key][stripslashes($k)] = stripslashes($v);
			}
		}
	}
	unset( $process );
}

if( isset( $_GET['addjoke'] ) ){
	include 'addjoke.html.php';
	exit();
}

try{
	$pdo = new PDO( 'mysql:host=localhost;dbname=ijdb', 'ijdbuser', 'suresh' );
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$pdo->exec( 'SET NAMES "utf8"' );
}catch( PDOException $e ){
	$error = "Unable to connect to database : " . $e->getMessage();
	include 'error.html.php';
	exit();
}

if( isset( $_POST['joketext'] ) ){
	try{
		$sql = 'INSERT INTO joke SET joketext = :joketext, jokedate = NOW()';
		$s = $pdo->prepare( $sql );
		$s->bindValue( ':joketext', $_POST['joketext'] );
		$s->execute();
	} catch( PDOException $e ){
		$error = "Error adding joke : " . $e->getMessage();
		include 'error.html.php';
		exit();
	}
	
	header( 'Location: .' );
	exit();
}

if( isset( $_GET['deletejoke'] ) ){
	try{
		$sql = "DELETE FROM joke WHERE id = :id";
		$s = $pdo->prepare( $sql );
		$s->bindValue( ':id', $_POST['id'] );
		$s->execute();
	} catch ( PDPException $e ){
		$error = "Error deleting joke : " . $e->getMessage();
		include 'error.html.php';
		exit();
	}
	
	header( 'Location: .' );
	exit();
}

try{
	$sql = "SELECT joke.id, joketext, name, email FROM joke INNER JOIN author ON joke.authorid = author.id";
	$result = $pdo->query( $sql );
} catch( PDOException $e ){
	$error = "Error retrieving joke : " . $e->getMessage();
	include 'error.html.php';
	exit();
}

while( $row = $result->fetch() ){
	$jokes[] = array( 'id'=>$row['id'], 'text'=>$row['joketext'], 'name'=>$row['name'], 'email'=>$row['email'] );
}

include 'jokes.html.php';
