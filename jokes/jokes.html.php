<!DOCTYPE html>
<head>
	<title>Jokes</title>
	<meta charset="utf-8">
	<style type="text/css">
		label{
			vertical-align : text-top;
		}
	</style>
</head>
<body>
	<B><a href="?addjoke">Add a new joke</a></B>
	<form action="" method="get">
		<label for="author"> Author
			<select name="author">
				<option value="">Any Author</option>
				<?php foreach( $authors as $author): ?>
					<option value="<?php htmlout( $author['id'] ); ?>">
					<?php htmlout( $author['name'] ); ?>
					</option>
				<?php endforeach; ?>			
			</select>
		</label>
		<br>
		<label for="category"> Category
			<select name="category">
				<option value="">Any Category</option>
				<?php foreach( $categories as $category): ?>
					<option value="<?php htmlout( $category['id'] ); ?>">
					<?php htmlout( $category['name'] ); ?>
					</option>
				<?php endforeach; ?>	
			</select>
		</label>
		<br>
		<label for="text">Contains text
			<input type="text" name="text"></input>
		</label>
		<br>
		<input type="submit" Value="Search">
	</form>
	<br>
	<?php if( $jokes and count( $jokes ) == 0 ): ?>
		<B>No jokes to display</B>
	<?php elseif( $jokes ): ?>
		<?php foreach( $jokes as $joke ): ?>
		<hr>
		<form action="?" method="post">
			<p>
				<input type="hidden" name="id" value="<?php echo $joke['id']; ?>">
				</input>
				<?php htmlout( $joke['joketext'] ); ?>
			</p>
			<input type="submit" name="delete" value="Delete">
			<input type="submit" name="edit" value="Edit">
		</form>
		<?php endforeach; ?>
	<?php endif; ?>
</body>
