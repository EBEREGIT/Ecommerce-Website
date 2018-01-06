<?php  
	require_once '../core/init.php';
	if (!is_logged_in()) {
		header('Location: login.php');
	}
	include_once 'includes/head.php';
	include_once 'includes/navigation.php';
?>

Administrator

<?php  
	include_once 'includes/footer.php';
?>