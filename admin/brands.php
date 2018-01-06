<?php  
	require_once '../core/init.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	include_once 'includes/head.php';
	include_once 'includes/navigation.php';

	$sql = "SELECT * FROM brand ORDER BY brand";
	$result = $db->query($sql);
	$errors = array();

	//edit brand
	if (isset($_GET['edit']) && !empty($_GET['edit'])) {
		$edit_id = (int)$_GET['edit'];
		$edit_id = sanitize($edit_id);
		$sql_4 = "SELECT * FROM brand WHERE id = '$edit_id'";
		$edit_result = $db->query($sql_4);
		$e_brand = mysqli_fetch_assoc($edit_result);
	}


	//delete brand
	if (isset($_GET['delete']) && !empty($_GET['delete'])) {
		$delete_id = (int)$_GET['delete'];
		$delete_id = sanitize($delete_id);
		$sql_3 = "DELETE FROM brand WHERE id = '$delete_id'";
		$db->query($sql_3);
		header('location:brands.php');
	}

	//form query
	if (isset($_POST['add_submit'])) {
		//$brand = sanitize($_POST['brand']);
		$brand = $_POST['brand'];

		if ($_POST['brand'] == '') {
			$errors[] .= 'You must enter a brand!';
		}
		//check if brand already exits

		$sql = "SELECT * FROM brand WHERE brand = '$brand' ";
		if (isset($_GET['edit'])) {
			$sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";
		}
		$result = $db->query($sql);
		$count = mysqli_num_rows($result);
		if ($count > 0 ) {
			$errors[] .= $brand. ' already exist. please choose another brand name!';
		}

		//display errors
		if (!empty($errors)) {
			echo display_errors($errors);
		}else {
		$sql_2 = "INSERT INTO brand (brand) VALUES ('$brand')";
		if (isset($_GET['edit'])) {
			$sql_2 = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
		}
		$db->query($sql_2);
		header('location: brands.php');
	}

	} 
	
?>

<h2 class="text-center">Brands</h2>

<hr>
<!--Brand form-->
<div class="text-center">
	<form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'')?>" method="post">
		  <div class="form-group">
		  		<label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add')?> Brand</label>

		  		<?php  
		  			$brand_value = "";

		  			if (isset($_GET['edit'])) {
		  				$brand_value = $e_brand['brand'];
		  			} else {
		  				if (isset($_POST['brand'])) {
		  					$brand_value = sanitize($_POST['brand']);
		  				}
		  			}
		  			
		  		?>

				<input type="text" name="brand" class="form-control" id="brand" placeholder="Brand" value="<?php echo $brand_value; ?>">
		  </div>

		  	<?php  if (isset($_GET['edit'])) : ?>

		  		<a href="brands.php" class="btn btn-default">Cancel</a>

		  	<?php endif; ?>
		  <button type="submit" name="add_submit" class="btn btn-success "><?=((isset($_GET['edit']))?'Edit':'Add')?> Brand</button>
	</form>
</div>

<hr>

<table class="table table-bordered table-stripped table-auto table-condensed">
	<thead>
		<th></th>
		<th>Brand</th>
		<th></th>
	</thead>
	<tbody>
		<?php while ($brand = mysqli_fetch_assoc($result)) : ?>
		<tr>
			<td><a href="brands.php?edit=<?php echo $brand ['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
			<td><?php echo $brand ['brand']; ?></td>
			<td><a href="brands.php?delete=<?php echo $brand ['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
		</tr>
	<?php endwhile; ?>
	</tbody>
</table>

<?php  
	include_once 'includes/footer.php';
?>