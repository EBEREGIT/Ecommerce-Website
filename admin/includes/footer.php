<footer class="text-center" id="footer">&copy; 2017 Ecommerce OOP</footer>

<script type="text/javascript">
	function update_sizes() {
		var size_string = '';
		for (var i = 1; i <= 12; i++) {
			if (jQuery('#size'+i).val() != '') {
				size_string += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';
			}
		}
		jQuery('#sizes').val(size_string);
	}

	function get_child_options(selected) {
		if (typeof selected === 'undefined') {
			var selected = '';
		}
		var parent_id = jQuery('#parent').val();
		jQuery.ajax({
			url: '/ecommerce_oop/admin/parsers/child_categories.php',
			type: 'POST',
			data: {parent_id : parent_id, selected: selected},
			success: function (data) {
				jQuery('#child').html(data);
			},
			error: function () {alert("Something Went Wrong While loading child option!")},
		});
	}
	jQuery('select[name="parent"]').change(function(){
		get_child_options();
	});
</script>

</body>
</html>