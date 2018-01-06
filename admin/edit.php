<?php  
	require_once '../core/init.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	if (!has_permission('admin')) {
		permission_error_redirect('index.php');
	}
	include_once 'includes/head.php';
	include_once 'includes/navigation.php';

	if (isset($_GET['edit'])) {
			$edit_id = (int)$_GET['edit'];
			$edit_id = sanitize($edit_id);
			$edit_query = $db->query("SELECT * FROM users WHERE id = '$edit_id'");
			$edit = mysqli_fetch_assoc($edit_query);	

			//posted values and values from db
			$name = ((isset($_POST['name']))?sanitize($_POST['name']):sanitize($edit['full_name']));
			$email = ((isset($_POST['email']))?sanitize($_POST['email']):sanitize($edit['email']));
			$permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):sanitize($edit['permissions']));
			$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
			$confirm_password = ((isset($_POST['confirm_password']))?sanitize($_POST['confirm_password']):'');
			
		if ($_POST) {
			//check if email exists
			$email_query = $db->query("SELECT * FROM users WHERE email = '$email' AND id != '$edit_id'");
			$email_count = mysqli_num_rows($email_query);
			if ($email_count != 0) {
				$errors[] = 'Email already exist!';
			}

			//ensure required fields
			$required = array('name', 'email', 'password', 'confirm_password', 'permissions');
			foreach ($required as $f) {
				if (empty($_POST[$f])) {
					$errors[] = 'You must fill out all fields!';
					break;
				}
			}

			//check password lenght
			if (strlen($password) <= 6) {
			$errors[] = 'Password is too short!';
			}

			//confirm password match
			if ($password != $confirm_password) {
				$errors[] = 'Password Mismatch!';
			}

			//check email validity
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors[] = 'You must enter a valid email!';
			}

			if (!empty($errors)) {
				//display errors
				echo display_errors($errors);
			}else{
				//create user
				$hashed = password_hash($password, PASSWORD_DEFAULT);
				$db->query("UPDATE users SET full_name = '$name', email = '$email', password = '$hashed', permissions = '$permissions' WHERE id = '$edit_id'");
				$_SESSION['success_flash'] = 'User Editted Successfully!';
				header('Location: users.php');
			}

		}

	}
	
?>

<h2 class="text-center">Edit User</h2><hr>
<form action="users.php?edit=<?php echo $edit_id; ?>" method="post">
	<div class="form-group col-md-6">
		<label for="name">Full Name</label>
		<input type="name" name="name" id="name" class="form-control" value="<?php echo $name; ?>">
	</div>

	<div class="form-group col-md-6">
		<label for="email">Email</label>
		<input type="text" name="email" id="email" class="form-control" value="<?php echo $email; ?>">
	</div>

	<div class="form-group col-md-6">
		<label for="password">Password</label>
		<input type="password" name="password" id="password" class="form-control" value="">
	</div>

	<div class="form-group col-md-6">
		<label for="confirm_password">Confirm Password</label>
		<input type="password" name="confirm_password" id="confirm_password" class="form-control" value="">
	</div>

	<div class="form-group col-md-6">
		<label for="">Permissions</label>
		<select class="form-control" name="permissions">
			<option value="" <?php echo (($permissions == '')?' selected':''); ?>></option>
			<option value="editor" <?php echo (($permissions == 'editor')?' selected':''); ?>>Editor</option>
			<option value="admin,editor" <?php echo (($permissions == 'admin,editor')?' selected':''); ?>>Admin,Editor</option>
		</select>
	</div>

	<div class="form-group col-md-6 text-right" style="margin-top: 25px;">
		<a href="users.php" class="btn btn-default">Cancel</a>
		<input type="submit" value="Edit User" class="btn btn-primary">
	</div>
</form>


<?php include_once 'includes/footer.php'; ?>