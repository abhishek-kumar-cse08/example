<!DOCTYPE html>
<head>
	<title>Categories</title>
	<meta charset="utf-8">
</head>
<body>
	<B><a href="?addcategory">Add a new category</a></B>
	<?php foreach( $categories as $category ): ?>
		<hr>
		<form action="" method="post">
			<p>
				<input type="hidden" name="id" value="<?php htmlout( $category['id'] ); ?>">
				</input>
				<?php htmlout( $category['name'] ); ?>
			</p>
			<input type="submit" name="action" value="delete">
			<input type="submit" name="action" value="edit">
		</form>
	<?php endforeach; ?>
</body>
