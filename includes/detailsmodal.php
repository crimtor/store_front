<?php
	require_once '../core/init.php';
	$id = $_POST['id'];
	$id = (int)$id;
	$result = $db->query("SELECT * FROM products WHERE id = '$id'");
	$product = mysqli_fetch_assoc($result);
	$brand_id = $product['brand'];
	$brand_query = $db->query("SELECT brand FROM brand WHERE id = '$brand_id'");
	$brand = mysqli_fetch_assoc($brand_query);
	$sizestring = $product['sizes'];
	$sizestring = rtrim($sizestring, ',');
	$size_array = explode(',', $sizestring);
?>

<!-- Details Modal -->
<?php ob_start(); ?>
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onclick="closeModal()" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title text-center" id="myModalLabel"><?php echo $product['title']; ?></h4>
			</div>

			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<span id="modal-errors" class="bd-danger"></span>
						<div class="col-sm-6 fotorama">
							<?php $photos = explode(',', $product['image']);
							foreach($photos as $photo): ?>
								<img class="details img-responsive" src="<?=$photo?>" alt="<?=$product['title']; ?>">
							<?php endforeach; ?>
						</div>

						<div class="col-sm-6">
							<h4>Details</h4>
							<p><?php echo nl2br($product['description']); ?></p>
							<hr>
							<p>Price: $<?php echo $product['price']; ?></p>
							<p>Brand: <?php echo $brand['brand']; ?></p>

							<hr>

							<form action="add_cart.php" method="post" id="add-item-form">
								<input type="hidden" name="product-id" id="product-id" value="<?=$id;?>">
								<input type="hidden" name="available" id="available" value="">
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label for="quantity">Quantity:</label>
											<input class="form-control" id="quantity" type="number" name="quantity" min="1" value="1">
										</div>
									</div>

									<div class="col-sm-9">
										<div class="form-group">
											<label for="size">Size:</label>
											<select name="size" class="form-control" id="size">
												<option value=""></option>
												<?php foreach($size_array as $string) {
													$string_array = explode(':', $string);
													$size = $string_array[0];
													$quantity = $string_array[1];
													echo '<option value="'.$size.'" data-available="'.$quantity.'">'.$size.' ('.$quantity.' Available)</option>';
												} ?>
											</select>
										</div>
									</div>

								</div>
							</form>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</div><!-- /.modal-body -->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" onclick="closeModal()">Close</button>
				<button type="submit" class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span> Add To Cart</button>
			</div>

		</div>
	</div>
</div><!-- /.modal -->
<script>
jQuery('#size').change(function(){
	var available = jQuery('#size option:selected').data('available');
	jQuery('#available').val(available);
});

$(function () {
  $('.fotorama').fotorama({'loop':true, 'autoplay':true});
});

	function closeModal() {
		jQuery('#details-modal').modal('hide');
		setTimeout(function(){
			jQuery('#details-modal').remove();
			jQuery('.modal-backdrop').remove();
		},500);
	}


	function add_to_cart() {
		jQuery('#modal-errors').html("");
		var size = jQuery('#size').val();
		var quantity = jQuery('#quantity').val();
		var available = jQuery('#available').val();
		var error = ''
		var data = jQuery('#add-item-form').serialize();
		if(size == '' || quantity == '' || quantity == 0){
			error += '<p class="text-danger text-center">You must choose a size and quantity.</p>';
			jQuery('#modal-errors').html(error);
			return;
		}else if(quantity > available){
			error += '<p class="text-danger text-center">Sorry there are only '+available+' available. So get '+available+' instead.</p>';
			jQuery('#modal-errors').html(error);
			return;
		}else{
			jQuery.ajax({
				url : '/my-php-shop/admin/parsers/add_cart.php',
				method : 'post',
				data : data,
				success: function(){
					location.reload();
				},
				error: function(){ alert("Something went wrong with the Cart.")}
			});
		}
	}
</script>
<?php echo ob_get_clean();
