<?php 
	require_once 'core/init.php';
	include_once 'includes/head.php';
	include_once 'includes/navigation.php'; 
	include_once 'includes/header-partial.php';
	include_once 'includes/left-bar.php';

	if (isset($_GET['cat'])) {
		$cat_id = sanitize($_GET['cat']);
	}else{
		$cat_id = '';
	}

	$cat_sql = "SELECT * FROM products WHERE categories = '$cat_id' ORDER BY RAND()";
	$cat_query = $db->query($cat_sql);
	$category = get_categories($cat_id);
	
?>
<!--main content-->
<div class="col-md-8">
	<div class="row">
		<h2 class="text-center"><?php echo $category['parent'].' '.$category['child']; ?></h2>

		<?php while ($product = mysqli_fetch_assoc($cat_query)) : ?>
			
			<div class="col-md-3">
				<h4><?php echo $product ['title']; ?></h4>
				<img src=<?php echo $product ['image']; ?> alt=<?php echo $product ['title']; ?> class="img-thumb" />
				<p class="list-price text-danger">List Price <s>N<?php echo $product ['list_price']; ?></s></p>
				<p class="price">Our Price: N<?php echo $product ['price']; ?></p>
				<button type="button" class="btn btn-sm btn-success" onclick="details_modal(<?php echo $product['id']; ?>)">Details</button>
			</div>
		<?php endwhile; ?>

	</div>
</div>

<?php
	include_once 'includes/right-bar.php';
	include_once 'includes/footer.php';
?>