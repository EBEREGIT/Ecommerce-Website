<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce_oop/core/init.php';
	include_once 'includes/head.php';

	$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
	$email = trim($email);
	$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
	$password = trim($password);
	//$hashed = password_hash($password, PASSWORD_DEFAULT);
	$errors = array();
?>
<style type="text/css">
	body{
		background-image: url('/ecommerce_oop/images/sky.jpg');
		background-size: 100vw 100vh;
		background-attachment: fixed;
	}
</style>

<div id="login-form">
	<div>
		<?php 
			if ($_POST) {
				//form validation
				if (empty($_POST['email']) || empty($_POST['password'])) {
					$errors[] = 'You must provide email and password!';
				}

				//validate email
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$errors[] = 'Enter a valid email address!';
				}

				//validate password
				if (strlen($password) < 6) {
					$errors[] = 'Password too short!';
				}
				//check if email exists
				$user_query = $db->query("SELECT * FROM users WHERE email = '$email'");
				$user = mysqli_fetch_assoc($user_query);
				$user_count = mysqli_num_rows($user_query);
				if ($user_count != 1) {
					$errors[] = 'invalid user credentials!';
				}

				if (!password_verify($password, $user['password'])) {
					$errors[] = "The password does not match our records!";
				}

				//display errors
				if (!empty($errors)) {
					echo display_errors($errors);
				}else{
					//log user in
					$user_id = $user['id'];
					login($user_id);
				}
			}
		?>
	</div>
	<h2 class="text-center">Login</h2><hr>
	<form action="login.php" method="post">
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>">
		</div>

		<div class="form-group">
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" class="form-control" value="<?php echo $password; ?>">
		</div>

		<div class="form-group">
			<input type="submit" value="Login" class="btn btn-primary">
		</div>
	</form>
	<p class="text-right"><a href="/ecommerce_oop/index.php" alt="home">Visit Site</a></p>
</div>	
<?php include_once 'includes/footer.php'; ?>