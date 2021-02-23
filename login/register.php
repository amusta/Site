<?php include('functions.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration system PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Register</h2>
	</div>
	
	<form method="post" action="register.php">

		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" value="<?php echo $username; ?>">
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>">
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" name="password_1">
		</div>
		<div class="input-group">
			<label>Confirm password</label>
			<input type="password" name="password_2">
		</div>
        <div class="input-group">
            <label>Name</label>
            <input type="text" name="Name" value="<?php echo $Name; ?>">
        </div>
        <div class="input-group">
            <label>Last name</label>
            <input type="text" name="Last_name" value="<?php echo $Last_name; ?>">
        </div>
        <div class="input-group">
            <label>Adress</label>
            <input type="text" name="Adress" value="<?php echo $Adress; ?>">
        </div>
        <div class="input-group">
            <label>City</label>
            <input type="text" name="City" value="<?php echo $City; ?>">
        </div>
        <div class="input-group">
            <label>Phone</label>
            <input type="tel" name="Phone" value="<?php echo $Phone; ?>">
        </div>
		<div class="input-group">
			<button type="submit" class="btn" name="register_btn">Register</button>
		</div>

		<p>
			Already a member? <a href="login.php">Sign in</a>
		</p>
	</form>
</body>
</html>