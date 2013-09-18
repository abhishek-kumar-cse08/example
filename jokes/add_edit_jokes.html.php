<!DOCTYPE html>
<head>
	<title>Jokes</title>
	<meta charset="utf-8">
	<style type="text/css">
		textarea{
			display : block;
			border-radius : 10px;
		}
		label{
			vertical-align : text-top;
		}
	</style>
</head>
<body>
	<form action="<?php htmlout( '?'.$action ) ?>" method="post">
		<input type="hidden" name="id" value="<?php echo $joke['id']; ?>">
		</input>
		<label for="text"> Joke
			<textarea name="text" rows="20" cols="40"><?php htmlout( $joketext ) ?>
			</textarea>
		</label>
		<br>
		<label for="author"> Author
			<select name="author">
				<option value="">Any Author</option>
				<?php foreach( $authors as $author): ?>
					<option value="<?php htmlout( $author['id'] ); ?>" <?php if( $author['id'] == $authorid ) echo 'selected' ?> >
					<?php htmlout( $author['name'] ); ?>
					</option>
				<?php endforeach; ?>			
			</select>
		</label>
		<br>
		<p>
		<label>Category
		<br>
		<?php foreach( $categories as $category): ?>
			<input type="checkbox" name="categories[]" value="<?php htmlout( $category['id'] ); ?>" <?php if( $category['selected'] ) echo ' checked' ?>>
			<?php htmlout( $category['name'] ); ?>
			</input>
			<br>
		<?php endforeach; ?>
		</label>
		</p>
		<input type="submit" name="add_edit" value="<?php htmlout($button) ?>">
	</form>
</body>
