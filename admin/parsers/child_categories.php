<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce_oop/core/init.php';
	$parent_id = (int)$_POST['parent_id'];
	$selected = sanitize($_POST['selected']);
	$child_query = $db->query("SELECT * FROM categories WHERE parent ='$parent_id' ORDER BY category");

	ob_start();
?>

	<option value=""></option>

	<?php while($child = mysqli_fetch_assoc($child_query)): ?>

		<option value="<?php echo $child['id']; ?>" <?php echo (($selected == $child['id'])?' selected':''); ?>><?php echo $child['category']; ?></option>

	<?php endwhile; ?>

<?php 
	echo ob_get_clean() ;
?>