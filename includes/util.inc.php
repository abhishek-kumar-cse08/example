<?php
	function html( $s ){
		return htmlspecialchars( $s, ENT_QUOTES, "UTF-8" );
	}
	
	function htmlout( $s ){
		echo html( $s );
	} 
?>
