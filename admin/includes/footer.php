</div><!-- .container-fluid -->

<!-- Footer -->
<footer class="text-center" id="footer">
	&copy; Copyright 2016 Shaunta's Boutique
</footer>

<script>
function get_child_options(selected){
	if(typeof selected === 'undefined'){
		var selected = '';
	}
	var parentID = jQuery('#parent').val();
	jQuery.ajax({
		url: '/my-php-shop/admin/parsers/child_categories.php',
		type: 'POST',
		data: { parentID : parentID, selected: selected },
		success: function(data){
			jQuery('#child').html(data);
		},
		error: function(){
			alert("Something went wrong with your Child Options.")
		}
	});
}

jQuery('select[name="parent"]').change(function(){
	get_child_options();
});

	function updateSizes() {
		var sizeString = '';
		for(var i = 1; i <= 12; i++) {
			if(jQuery('#size'+i).val() != '') {
				sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+','; // example output: small:7,medium:8,
			}
		}
		jQuery('#sizes').val(sizeString);
	}

</script>

</body>
</html>
