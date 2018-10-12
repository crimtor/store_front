</div><!-- .container-fluid -->

<!-- Footer -->
<footer class="text-center" id="footer">
	&copy; Copyright 2018 Shawn's Business Website
</footer>

<!-- Scripts -->

<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

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
				sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+':'+jQuery('#threshold'+i).val()+','; // example output: small:7,medium:8,
			}
		}
		jQuery('#sizes').val(sizeString);
	}

</script>

</body>
</html>
