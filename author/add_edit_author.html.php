<!DOCTYPE html>		
<head>
	<title><?php htmlout( $button )?> Author</title>
	<meta charset="utf-8">
	<style type="text/css">
		label{
			vertical-align : text-top;
		}
	</style>
</head>
<body>
	<form action="?" method="post">
		<input type="hidden" name="id" value="<?php htmlout( $id );?>">
		<label for="name">Name
			<input type="text" name="name" value="<?php htmlout( $name )?>">
		</label>
		<br>
		<label for="email">Email
			<input type="text" name="email" value="<?php htmlout( $email )?>">
		</label>
		<br>
		<label for="password">Password
			<input type="password" name="password" value="">
		</label>
		<br>
		<?php foreach( $roles as $role ): ?>
			<?php htmlout( $role['id'].' Administrator' ); ?>
			<input type="checkbox" name="roles[]" value="<?php htmlout( $role['id'] ); ?>" <?php if( $role['selected'] ) echo ' checked'; ?>>
			<br>
		<?php endforeach; ?>
		<input type=submit name="<?php htmlout( $submit ); ?>" value="<?php htmlout( $button )?>">
	</form>
</body>
