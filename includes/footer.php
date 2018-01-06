<footer class="text-center" id="footer">&copy; 2017 Ecommerce OOP</footer>

	<script type="text/javascript">
		jQuery(window).scroll(function () {
			var vscroll = jQuery(this).scrollTop();
			jQuery('#logo-text').css({
				"transform" : "translate(30px, "+vscroll/2+"px)"
			});

			var vscroll = jQuery(this).scrollTop();
			jQuery('#back-flower').css({
				"transform" : "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
			});

			var vscroll = jQuery(this).scrollTop();
			jQuery('#fore-flower').css({
				"transform" : "translate(0px, -"+vscroll/2+"px)"
			});
		});

		function details_modal(id) {
			var data = {'id' : id};

			jQuery.ajax({
				url : '/ecommerce_oop/includes/details-modal.php',
				method : "post",
				data : data,
				success: function (data) {
					jQuery('body').append(data);
					jQuery('#details-modal').modal('toggle');
				},
				error: function () {
					alert("Something went wrong");
				}
			});
		}

		function add_to_cart() {
			jQuery('#modal-errors').html('');
			var size = jQuery('#size').val();
			var quantity = jQuery('#quantity').val();
			var available = jQuery('#available').val();
			var error = '';
			var data = jQuery('#add-product-form').serialize();

			if (size == '' || quantity == '' || quantity == 0) {
				error += '<p class="text-center text-danger">You must choose a size and quanyity!</p>'
				jQuery('#modal-errors').html(error);
				return;
			}else if(quantity > available){
				error += '<p class="text-center text-danger">There are only '+ available +' available</p>'
				jQuery('#modal-errors').html(error);
				return;
			}else{
				jQuery.ajax({
					url : '/ecommerce_oop/admin/parsers/add_cart.php',
					method : "post",
					data : data,
					success : function () {
						location.reload();
					},
					error : function () {
						alert('Something went wrong');
					}
				});
			}
		}
	</script>
</body>
</html>