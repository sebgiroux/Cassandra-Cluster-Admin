<?php echo $login_error; ?>
<?php echo $you_must_be_logged; ?>

<form method="post" action="login.php">
	<div>
		<label for="username">Username:</label>
		<input type="text" id="username" name="username" />
	</div>
	
	<div>
		<label for="password">Password:</label>
		<input type="password" id="password" name="password" />
	</div>
	
	<div>
		<input type="submit" name="btn_login" value="Login" />
	</div>	
</form>