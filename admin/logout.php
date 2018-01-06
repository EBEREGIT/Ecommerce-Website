<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce_oop/core/init.php';
	unset($_SESSION['SBUser']);
	header('Location: login.php');
?>
