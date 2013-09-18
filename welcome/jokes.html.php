<!DOCTYPE html>
<head>
	<title>Jokes</title>
	<meta charset="utf-8">
</head>
<body>
	<B><a href="?addjoke">Add a new joke</a></B>
	<?php foreach( $jokes as $joke ): ?>
		<hr>
		<form action="?deletejoke" method="post">
			<p>
				<input type="hidden" name="id" value="<?php echo $joke['id']; ?>">
				</input>
				<?php echo htmlspecialchars( $joke['text'], ENT_QUOTES, "UTF-8" ); ?>
			</p>
			<input type="submit" value="Delete">
			<a href="mailto:<?php echo htmlspecialchars( $joke['email'], ENT_QUOTES, 'UTF-8' )?>"><?php echo htmlspecialchars( $joke['name'], ENT_QUOTES, 'UTF-8') ?></a>
		</form>
	<?php endforeach; ?>
</body>
