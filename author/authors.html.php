<!DOCTYPE html>
<head>
	<title>Authors</title>
	<meta charset="utf-8">
</head>
<body>
	<B><a href="?addauthor">Add a new author</a></B>
	<?php foreach( $authors as $author ): ?>
		<hr>
		<form action="" method="post">
			<p>
				<input type="hidden" name="id" value="<?php htmlout( $author['id'] ); ?>">
				</input>
				<?php htmlout( $author['name'] ); ?>
			</p>
			<input type="submit" name="action" value="delete">
			<input type="submit" name="action" value="edit">
		</form>
	<?php endforeach; ?>
</body>
