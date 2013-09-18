<!DOCTYPE html>		
<head>
	<title>Submit Joke</title>
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
	<form action="?" method="post">
		<label for="joketext">Your Joke
			<textarea name="joketext" rows="10" cols="40">
			</textarea>
		</label>
		<input type=submit value="Submit">
	</form>
</body>
