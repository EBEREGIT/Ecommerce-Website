<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce_oop/core/init.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	include_once 'includes/head.php';

	$hashed = $user_data['password'];
	$user_id = $user_data['id'];

	$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
	$old_password = trim($old_password);

	$new_password = ((isset($_POST['new_password']))?sanitize($_POST['new_password']):'');
	$new_password = trim($new_password);

	$confirm_password = ((isset($_POST['confirm_password']))?sanitize($_POST['confirm_password']):'');
	$confirm_password = trim($confirm_password);
	
	$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
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
				if (empty($_POST['old_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
					$errors[] = 'You must provide all details!';
				}

				//validate password
				if (strlen($new_password) < 6) {
					$errors[] = 'Password too short!';
				}
				
				//check if new password matches confirm password
				if ($new_password != $confirm_password) {
					$errors[] = 'Password Mismatch!';
				}

				if (!password_verify($old_password, $hashed)) {
					$errors[] = "The old password does not match our records!";
				}

				//display errors
				if (!empty($errors)) {
					echo display_errors($errors);
				}else{
					//change password
					$db->query("UPDATE users SET password = '$new_hashed_password' WHERE id = '$user_id'");
					$_SUCCESS['success_flash'] = 'Your Password Has Been Changed Successfully';
					header('Location: index.php');
				}
			}
		?>
	</div>
	<h2 class="text-center">Change Password</h2><hr>
	<form action="change_password.php" method="post">
		<div class="form-group">
			<label for="old_password">Old_password:</label>
			<input type="password" name="old_password" id="old_password" class="form-control" value="<?php echo $old_password; ?>">
		</div>

		<div class="form-group">
			<label for="new_password">New Password:</label>
			<input type="password" name="new_password" id="new_password" class="form-control" value="<?php echo $new_password; ?>">
		</div>

		<div class="form-group">
			<label for="confirm_password">Confirm_Password:</label>
			<input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
		</div>

		<div class="form-group">
			<a href="index.php" class="btn btn-default">Cancel</a>
			<input type="submit" value="Apply Changes" class="btn btn-primary">
		</div>
	</form>
	<p class="text-right"><a href="/ecommerce_oop/index.php" alt="home">Visit Site</a></p>
</div>	
<?php include_once 'includes/footer.php'; ?>