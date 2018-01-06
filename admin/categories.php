<?php  
	require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce_oop/core/init.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	include_once 'includes/head.php';
	include_once 'includes/navigation.php';

	$sql = "SELECT * FROM categories WHERE parent = 0";
	$result = $db->query($sql);
	$errors = array();
	$category = "";
	$post_parent = "";

	//edit category
	if (isset($_GET['edit']) && !empty($_GET['edit'])) {
		$edit_id = (int)$_GET['edit'];
		$edit_id = sanitize($edit_id);
		$edit_sql = "SELECT * FROM categories WHERE id = '$edit_id'";
		$edit_result = $db->query($edit_sql);
		$edit_cat = mysqli_fetch_assoc($edit_result);
	}

	//Delete categories
	if (isset($_GET['delete']) && !empty($_GET['delete'])) {
		$delete_id = (int)$_GET['delete'];
		$delete_id = sanitize($delete_id);
		$p_sql = "SELECT * FROM categories WHERE id = '$delete_id'";
		$p_result = $db->query($p_sql);
		$cat = mysqli_fetch_assoc($p_result);
		if ($cat['parent'] == 0) {
			$pd_sql = "DELETE FROM categories WHERE parent = '$delete_id'";
			$db->query($pd_sql);
		}

		$delete_sql = "DELETE FROM categories WHERE id = '$delete_id'";
		$db->query($delete_sql);
		header("Location:categories.php");
	}

	//process from
	if (isset($_POST) && !empty($_POST)) {
		$post_parent = sanitize($_POST['parent']);
		$category = sanitize($_POST['category']);
		$sql_form = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";

		if (isset($_GET['edit'])) {
			$id = $edit_cat['id'];
			$sql_form = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent' AND id != '$id'";
		}
		$f_result = $db->query($sql_form);
		$count = mysqli_num_rows($f_result);

		//if category empty
		if ($category == '') {
			$errors[] .= 'The category cannot be left blank';
		}

		//if category exits already
		if ($count > 0) {
			$errors[] .= $category . ' already exit!';
		}

		//display errors or update db
		if (!empty($errors)) {
			//display errors
			$display = display_errors($errors); ?>

			<script type="text/javascript">
				jQuery('document').ready(function () {
					jQuery('#errors').html('<?php echo $display; ?>')
				});
			</script>

			<?php
		} else {
			$sql_update = "INSERT INTO categories (category, parent) VALUES ('$category', '$post_parent')";

			if (isset($_GET['edit'])) {
				$sql_update = "UPDATE categories SET  category = '$category', parent = '$post_parent' WHERE id = '$edit_id'";
			}
			$db->query($sql_update);
			header('Location:categories.php');
		}
		
	}

	$cat_val = "";
	$parent_val = 0;
	if (isset($_GET['edit'])) {
		$cat_val = $edit_cat['category'];
		$parent_val = $edit_cat['parent'];
	}else{
		if (isset($_POST)) {
			$cat_val = $category;
			$parent_val = $post_parent;
		}
	}
?>

<h2 class="text-center">CATEGORIES</h2><hr>

<div class="row">
	<!--category form-->
	<div class="col-md-6">
		<form class="form" action="categories.php<?php echo((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">
			<legend><?=((isset($_GET['edit']))?'Edit':'Add')?> Category</legend>
			<div id="errors"></div>
			<div class="form-group">
				<label for="parent">Parent</label>
				<select class="form-control" name="parent" id="parent">
					<option value="0" <?php echo (($parent_val == 0)?'selected="selected"':''); ?>>Parent</option>

					<?php while($parent = mysqli_fetch_assoc($result)): ?>

						<option value="<?php echo $parent['id']; ?>" <?php echo (($parent_val == $parent['id'])?'selected="selected"':''); ?>><?php echo $parent['category']; ?></option>

					<?php endwhile; ?>
				</select>
			</div>

			<div class="form-group">
				<label for="category">Category</label>
				<input class="form-control" type="text" name="category" id="category" value="<?php echo $cat_val; ?>"></input>	
			</div>

			<div class="form-group">
				<button class="btn btn-success" ><?=((isset($_GET['edit']))?'Edit':'Add')?> Category</button>	
			</div>
		</form>		
	</div>

	<!--category table-->
	<div class="col-md-6">
		<table class="table table-bordered">
			<thead>
				<th>Categories</th>
				<th>Parent</th>
				<th></th>
			</thead>
			<tbody>
				<?php 
					$sql = "SELECT * FROM categories WHERE parent = 0";
					$result = $db->query($sql);

					while($parent = mysqli_fetch_assoc($result)):

						$parent_id = (int)$parent['id'];
						$sql_2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
						$c_result = $db->query($sql_2);

				?>
					<tr class="bg-primary">
						<td><?php echo $parent['category']; ?></td>
						<td><?php echo $parent['category']; ?></td>
						<td>
							<a href="categories.php?edit=<?php echo $parent['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="categories.php?delete=<?php echo $parent['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
						</td>
					</tr>

					<?php while($child = mysqli_fetch_assoc($c_result)): ?>

						<tr class="bg-info">
							<td><?php echo $child['category']; ?></td>
							<td><?php echo $parent['category']; ?></td>
							<td>
								<a href="categories.php?edit=<?php echo $child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="categories.php?delete=<?php echo $child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
							</td>
						</tr>


					<?php endwhile; ?>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<?php  
	include_once 'includes/footer.php';
?>