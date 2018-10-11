	</div><!-- /.container -->


	<!-- Footer -->
	<footer class="text-center" id="footer">
		&copy; Copyright 2016 Shaunta's Boutique
	</footer>

	<script src="js/jquery-1.12.3.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
	<script>
		function updateSizes() {
			var sizeString = '';
			for(var i=1;i<=12;i++){
				sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';
			}
			jQuery('#sizes').val(sizeString);
		}

		function get_child_options(selected){
			if(typeof selected === 'undefined'){
				var selected = '';
			}
			var parentID = jQuery('#parent').val();
			jQuery.ajax({
				url: '/my-php-shop/admin/parsers/child_categories.php',
				type: 'post',
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
		})

		$(window).scroll( function() {
			var vscroll = $(this).scrollTop();
			//console.log(vscroll);
			$('#logotext').css({
				"transform" : "translate(0px, "+vscroll/2+"px)"
			});
			$('#back-flower').css({
				"transform" : "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
			});
			$('#fore-flower').css({
				"transform" : "translate(0px, -"+vscroll/2+"px)"
			});
		});


		function update_cart(mode, edit_id, edit_size){
			var data = {"mode": mode, "edit_id": edit_id, "edit_size": edit_size };
			jQuery.ajax({
				url : '/my-php-shop/admin/parsers/update_cart.php',
				method : 'post',
				data : data,
				success: function(){
					location.reload();
				},
				error: function(){ alert("Something went wrong with the Cart.")}
			});
		}

		function detailsmodal(id) {
			var data = { "id" : id};
			// send data to detailsmodal.php
			jQuery.ajax({
				url		: '/my-php-shop/includes/detailsmodal.php',
				method	: "post",
				data	: data,
				success	: function(data){
					jQuery('body').append(data);
					jQuery('#details-modal').modal('toggle');
				},
				error	: function(){
					alert("Something went wrong!");
				}
			});
		}
	</script>
</body>
</html>
