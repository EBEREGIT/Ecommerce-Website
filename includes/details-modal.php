<?php 
	require_once '../core/init.php';

	$id = $_POST['id'];
	$id = (int)$id;
	$sql = "SELECT * FROM products WHERE id = '$id'";
	$result = $db->query($sql);
	$product = mysqli_fetch_assoc($result);

	$brand_id = $product ['brand'];
	$sql_2 = "SELECT brand FROM brand WHERE id = '$brand_id'";
	$brand_query = $db->query($sql_2);
	$brand = mysqli_fetch_assoc($brand_query);

	$size_string = $product['sizes'];
	$size_string = rtrim($size_string, ',');
	$size_array = explode(',', $size_string);
?>
<!--Details Modal-->
<?php ob_start(); ?>
	<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" onclick="close_modal()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title text-center"><?php echo $product ['title']; ?></h4>
				</div>

				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<span class="bg-danger" id="modal-errors"></span>
							<div class="col-sm-6">
								<div class="center-block">
									<img src=<?php echo $product ['image']; ?> alt=<?php echo $product ['title']; ?> class="details img-responsive" />
								</div>
							</div>
							<div class="col-sm-6">
								<h4>Details</h4>
								<p><?php echo nl2br($product ['description']); ?></p>
								<hr>
								<p>Price: N<?php echo $product ['price']; ?></p>
								<p>Brand: <?php echo $brand ['brand']; ?></p>
									<form action="add-cart.php" method="post" id="add-product-form">
										<div class="row">
											<div class="form-group">
												<div class="col-md-12 ">
													<input type="hidden" name="product_id" id="product_id" value="<?php echo $id; ?>p">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="form-group">
												<div class="col-md-12 ">
													<input type="hidden" name="available" id="available" value="">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="form-group">
												<div class="col-md-12 ">
													<label for="quantity">Quantity: </label>
													<input type="number" class="form-control" name="quantity" min="0" id="quantity">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="form-group">
												<div class="col-md-12 ">
													<label for="size">size: </label>
													<select class="form-control" name="size" id="size">

														<?php  
															foreach ($size_array as $sizes) {
																$size_array_2 = explode(':', $sizes);
																$size = $size_array_2[0];
																$available = $size_array_2[1];
																echo '<option value="'.$size.'" data-available="'.$available.'">'.$size.' ('.$available.' Available)</option>';
															}
														?>
														
														
													</select> 
												</div>
											</div>
										</div>

									</form>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button class="btn btn-default" onclick="close_modal()">Close</button>
					<button class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Add to Cart</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		jQuery('#size').change(function () {
			var available = jQuery('#size option:selected').data('available');
			jQuery('#available').val(available);
		});
		
		function close_modal() {
			jQuery('#details-modal').modal('hide');
			setTimeout(function() {
				jQuery('details-modal').remove();
				jQuery('modal-backdrop').remove();
			},500);
		}
	</script>
<?php echo ob_get_clean(); ?>