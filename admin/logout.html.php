<html>
<head>
	<style type="text/css">
		textarea{
			display : block;
			border-radius : 10px;
		}
		label{
			vertical-align : text-top;
		}
		.submit{
			position : absolute;
			right : 0px;
		}
	</style>
</head>
<body>
	<form action="/admin/" method="post">
		<div>
			<input type="hidden" name="action" value="logout">
			<input type="hidden" name="goto" value="/admin/">
			<input type="submit" class="submit" value="Log out">
		</div>
	</form>
	<br>
<body>
</html>
