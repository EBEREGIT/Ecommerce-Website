<?php  
	require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce_oop/core/init.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	include_once 'includes/head.php';
	include_once 'includes/navigation.php';

	if (isset($_GET['delete'])) {
		$id = sanitize($_GET['delete']);
		$db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
		header("Location: products.php?archived");
	}

	if (isset($_GET['restore'])) {
		$restore_id = sanitize($_GET['restore']);
		$db->query("UPDATE products SET deleted = 0 WHERE id = '$restore_id'");
		header("Location: products.php");
	}

	$db_path = '';
	if (isset($_GET['add']) || isset($_GET['edit'])) {
		$brand_query = $db->query("SELECT * FROM brand ORDER BY brand");
		$parent_query = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

		$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
		$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
		$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
		$category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
		$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
		$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
		$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
		$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
		$saved_photo = "";

		if (isset($_GET['edit'])) {
			$edit_id = (int)$_GET['edit'];
			$product_result_query = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
			$product_array = mysqli_fetch_assoc($product_result_query);

				if (isset($_GET['delete_image'])) {
					$image_url = $_SERVER['DOCUMENT_ROOT'].$product_array['image'];echo $image_url;
					unlink($image_url);
					$db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
					header('Location: products.php?edit='.$edit_id);
				}

			$category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product_array['categories']);
			$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product_array['title']);
			$brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$product_array['brand']);
			$parent_query_1 = $db->query("SELECT * FROM categories WHERE id = '$category'");
			$parent_result_1 = mysqli_fetch_assoc($parent_query_1);
			$parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parent_result_1['parent']);
			$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product_array['price']);
			$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):$product_array['list_price']);
			$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$product_array['description']);
			$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):$product_array['sizes']);
			$saved_photo = (($product_array['image'] != '')?$product_array['image']:'');
			$db_path = $saved_photo;
		}

	if (!empty($sizes)) {
			$size_string = sanitize($sizes);
			$size_string = rtrim($size_string, ',');
			$sizes_array = explode(',', $size_string);
			$s_array = array();
			$q_array = array();
			foreach ($sizes_array as $size_string) {
				$s = explode(':', $size_string);
				$s_array[] = $s[0];
				$q_array[] = $s[1];
			}
		}else{
			$sizes_array = array();
		}

	if ($_POST) {
		$errors = array();
		
		$required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
		foreach ($required as $field) {
			if ($_POST[$field] == '') {
				$errors[] = "All Fields with an Asterix must be filled";
				break;
			}
		}
		if (!empty($_FILES)) {
			$photo = $_FILES['photo'];
			$name = $photo['name'];
			$name_array = explode('.', $name);
			$file_name = $name_array[0];
			$file_extension = $name_array[1];
			$mime = explode('/', $photo['type']);
			$mime_type = $mime[0];
			$mime_extension = $mime[1];
			$temp_location = $photo['tmp_name'];
			$file_size = $photo['size'];
			$allowed = array('png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG', 'gif', 'GIF');
			$upload_name = md5(microtime()).'.'.$file_extension;
			$upload_path = BASEURL.'/images/products/'.$upload_name;
			$db_path = '/ecommerce_oop/images/products/'.$upload_name;
			
			if ($mime_type != 'image') {
				$errors[] = 'File must be a photo';
			}
			if (!in_array($file_extension, $allowed)) {
				$errors[] = 'Wrong image file extension';  
			}
			if ($file_size > 1000000) {
				$errors[] = 'File should not be more than 1MB';
			}
			// if ($file_extension != $mime_extension && ($mime_extension == 'jpeg' && $file_extension != 'jpg')) {
			// 	$errors[] = 'File extension corrupted!';
			// }
		}

		if (!empty($errors)) {
			echo display_errors($errors);
		}else{
			//upload file and update db
			if (!empty($_FILES)) {
				move_uploaded_file($temp_location, $upload_path);
			}
			$insert_sql = "INSERT INTO products (`title`, `price`, `list_price`, `brand`, `categories`, `sizes`, `image`, `description`) 
			VALUES ('$title', '$price', '$list_price', '$brand', '$category', '$sizes', '$db_path', '$description')";

				if (isset($_GET['edit'])) {
					$insert_sql = "UPDATE products SET title = '$title', price = '$price', list_price = $list_price, brand = '$brand', categories = '$category', sizes = '$sizes', image = '$db_path', description = '$description' WHERE id = '$edit_id'";
				}
			$db->query($insert_sql);
			header('Location: products.php');
		}
	}
?>
	<h2 class="text-center"><?php echo((isset($_GET['edit']))?'Edit':'Add'); ?> Products</h2><hr>
	<form action="products.php?<?php echo((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="post" enctype="multipart/form-data">
		<div class="row">
			<div class="form-group col-md-3">
				<label for="title">Title*:</label>
				<input class="form-control" type="text" name="title" id="title" value="<?php echo $title; ?>">
			</div>

			<div class="form-group col-md-3">
				<label for="brand">Brand*:</label>
				<select class="form-control" name="brand" id="brand">
					
					<option value="" <?php echo(($brand == '')?' selected':''); ?>></option>

					<?php while($b = mysqli_fetch_assoc($brand_query)): ?>

						<option value="<?php echo $b['id']; ?>" <?php echo(($brand == $b['id'])?' selected':''); ?> ><?php echo $b['brand']; ?></option>

					<?php endwhile; ?>
				</select>
			</div>

			<div class="form-group col-md-3">
				<label for="parent">Parent Category*:</label>
				<select class="form-control" id="parent" name="parent">
					<option value=""<?php echo(($parent == '')?'selected':'') ?>></option>

					<?php while($p = mysqli_fetch_assoc($parent_query)): ?>
						<option value="<?php echo $p['id']; ?>"<?php echo(($parent == $p['id'])?' selected':''); ?>><?php echo $p['category']; ?></option>					
					<?php endwhile; ?>
				</select>
			</div>

			<div class="form-group col-md-3">
				<label for="child">Child Category*:</label>
				<select class="form-control" id="child" name="child">
					
				</select>
			</div>

			<div class="form-group col-md-3">
				<label for="price">Price*:</label>
				<input class="form-control" type="text" name="price" id="price" value="<?php echo $price; ?>">
				</select>
			</div>			

			<div class="form-group col-md-3">
				<label for="list_price">List Price:</label>
				<input class="form-control" type="text" name="list_price" id="list_price" value="<?php echo $list_price; ?>">
				</select>
			</div>	

			<div class="form-group col-md-3">
				<label for="">Quantity & Sizes*:</label>
				<button class="btn btn-default form-control" onclick="jQuery('#sizes_modal').modal('toggle');return false;">Quantity & Sizes</button>
			</div>	

			<div class="form-group col-md-3">
				<label for="sizes">Sizes & Quantity Preview</label>
				<input type="text" id="sizes" class="form-control" name="sizes" value="<?php echo $sizes; ?>" readonly>
			</div>	

			<div class="form-group col-md-6">
				<?php if ($saved_photo != '') : ?>
					<div class="saved_photo">
						<img src="<?php echo $saved_photo; ?>" alt="saved_photo"><br>
						<a href="products.php?delete_image=1&edit=<?php echo $edit_id; ?>" class="text-danger">Delete Image</a>
					</div>
				<?php else: ?>
					<label for="photo">Product Photo</label>
					<input type="file" name="photo" id="photo" class="form-control">
				<?php endif; ?>
			</div>

			<div class="form-group col-md-6">
				<label for="description">Product Description</label>
				<textarea id="description" name="description" class="form-control" rows="6">
					<?php echo $description; ?>
				</textarea>
			</div>

			<div class="form-group col-md-3 pull-right">
				<a href="products.php" class="btn btn-default">Cancel</a>
				<input type="submit" value="<?php echo((isset($_GET['edit']))?'Edit':'Add'); ?> Product" class="btn btn-success pull-right">
			</div>

			<div class="clearfix"></div>

		</div>
	</form>

	<!-- Modal -->
	<div class="modal fade" id="sizes_modal" tabindex="-1" role="dialog" aria-labelledby="sizes_ModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="sizes_ModalLabel">Sizes & Quantity</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="container-fluid">

		      	<?php for ($i=1; $i <= 12; $i++): ?>

		      		<div class="form-group col-md-4">
		      			<label for="size<?php echo $i; ?>">Size</label>
		      			<input type="text" class="form-control" name="size<?php echo $i; ?>" id="size<?php echo $i; ?>" value="<?php echo((!empty($s_array[$i-1]))?$s_array[$i-1]:'') ?>">
		      		</div>

		      		<div class="form-group col-md-2">
		      			<label for="qty<?php echo $i; ?>">Qty</label>
		      			<input type="number" class="form-control" name="qty<?php echo $i; ?>" id="qty<?php echo $i; ?>" value="<?php echo((!empty($q_array[$i-1]))?$q_array[$i-1]:'') ?>" min="0">
		      		</div>

		      	<?php endfor; ?>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" onclick="update_sizes();jQuery('#sizes_modal').modal('toggle');return false;">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>

<?php
		
	}else{

	$sql = "SELECT * FROM products WHERE deleted = 0";
		if (isset($_GET['archived'])) {
			$sql = "SELECT * FROM products WHERE deleted = 1";
		}
	$p_result = $db->query($sql);

	if (isset($_GET['featured'])) {
		$id = (int)$_GET['id'];
		$featured = (int)$_GET['featured'];
		$featured_sql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
		$db->query($featured_sql);
		header('Location: products.php');
	}
?>
<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a>
<div class="clearfix"></div>
<hr>

<table class="table table-bordered table-condensed table-striped">
	<thead>
		<th></th>
		<th>Products</th>
		<th>Price</th>
		<th>categories</th>
		<th>Featured</th>
		<th>Sold</th>
	</thead>

	<tbody>
		<?php 
			while($products = mysqli_fetch_assoc($p_result)): 

				$child_id = $products['categories'];
				$cat_sql = "SELECT * FROM categories WHERE id = '$child_id'";
				$result = $db->query($cat_sql);
				$child = mysqli_fetch_assoc($result);

				$parent_id = $child['parent'];

				$p_sql = "SELECT * FROM categories WHERE id = '$parent_id'";
				$product_result = $db->query($p_sql);
				$parent = mysqli_fetch_assoc($product_result);

				$category = $parent['category'].'-'.$child['category'];

		?>

			<tr>
				<td>
					<?php if (isset($_GET['archived'])) : ?>
						<a href="products.php?restore=<?php echo $products['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a>
					<?php else : ?>
						<a href="products.php?edit=<?php echo $products['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>

						<a href="products.php?delete=<?php echo $products['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
					<?php endif; ?>
				</td>
				<td><?php echo $products['title']; ?></td>
				<td><?php echo money($products['price']); ?></td>
				<td><?php echo $category; ?></td>
				<td>
					<a href="products.php?featured=<?php echo(($products['featured'] == 0)?'1':'0'); ?>&id=<?php echo $products['id']; ?>" class="btn btn-default btn-xs">
					<span class="glyphicon glyphicon-<?php echo(($products['featured'] == 1)?'minus':'plus'); ?>"></span>
					
					</a>
					&nbsp<?php echo(($products['featured'] == 1)?'Featured Product':''); ?>
				</td>
				<td>0</td>
			</tr>

		<?php endwhile; ?>
	</tbody>
</table>


<?php  
	}
	include_once 'includes/footer.php';
?>

<script type="text/javascript">
	jQuery('document').ready(function () {
		get_child_options('<?php echo $category; ?>');
	});
</script>