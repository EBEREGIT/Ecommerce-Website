<?php 
	require_once 'core/init.php';
	include_once 'includes/head.php';
	include_once 'includes/navigation.php'; 
	include_once 'includes/header-full.php';
	include_once 'includes/left-bar.php';

	$sql = "SELECT * FROM products WHERE featured = 1 ORDER BY RAND()";
	$featured = $db->query($sql);

?>
<!--main content-->
<div class="col-md-8">
	<div class="row">
		<h2 class="text-center">Featured Products</h2>

		<?php while ($product = mysqli_fetch_assoc($featured)) : ?>
			
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