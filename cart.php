<?php
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/header-partial.php';

  if($cart_id != ''){
    $cart_query = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $result = mysqli_fetch_assoc($cart_query);
    $items = json_decode($result['items'], true);
    $i = 1;
    $sub_total = 0;
    $item_count = 0;
  }
?>

<div class="col-md-12">
  <div class="row">
    <h2 class="text-center">My Shopping Cart</h2> <hr>
    <?php if($cart_id == ''): ?>
      <div class="bg-danger">
        <p class="text-center text-danger">
          Your Shopping Cart is Empty!
        </p>
      </div>
    <?php else: ?>
      <table class="table table-bordered table-condensed table-striped">
        <thead><th>Item #</th><th>Item</th><th>Price</th><th>Quantity</th><th>Size</th><th>Sub Total</th></thead>
        <tbody>
          <?php foreach ($items as $item) {
            $product_id = $item['id'];
            $product_query = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
            $product = mysqli_fetch_assoc($product_query);
            $size_array = explode(',', $product['sizes']);
            foreach ($size_array as $size_string) {
              $size = explode(':', $size_string);
              if($size[0] == $item['size']){
                $available = $size[1];
              }
            }
            ?>
            <tr>
              <td><?=$i;?></td>
              <td><?=$product['title'];?></td>
              <td><?=money($product['price']);?></td>
              <td>
                <button class="btn btn-xs" onclick="update_cart('removeone', '<?=$product['id'];?>', '<?=$item['size'];?>');">-</button>
                <?=$item['quantity'];?>
                <?php if($item['quantity'] < $available): ?>
                <button class="btn btn-xs" onclick="update_cart('addone', '<?=$product['id'];?>', '<?=$item['size'];?>');">+</button>
              <?php else: ?>
                <span class="text-danger"> Max Reached </span>
              <?php endif; ?>
              </td>
              <td><?=$item['size'];?></td>
              <td><?=money($product['price'] * $item['quantity']);?></td>
          <?php
          $i++;
          $item_count += $item['quantity'];
          $sub_total += ($product['price'] * $item['quantity']);
          }
          $tax = TAXRATE * $sub_total;
          $tax = number_format($tax, 2);
          $grand_total = $tax + $sub_total;
        ?>
        </tbody>
      </table>
      <table class="table table-bordered table-condensed text-right">
        <thead class="total-table-header"><th>Total Items</th><th>Sub Total</th><th>Tax</th><th>Grand Total</th></thead>
        <tbody>
          <tr>
            <td><?=$item_count;?></td>
            <td><?=money($sub_total);?></td>
            <td><?=money($tax);?></td>
            <td class="bg-success"><?=money($grand_total);?></td>
          </tr>
        </tbody>
      </table>

<!-- Checkout button -->
<button type="button" class="btn btn-primary btn-large pull-right" data-toggle="modal" data-target="#checkoutModal">
  <span class="glyphicon glyphicon-shopping-cart"></span> Check Out
</button>

<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Shipping Address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="row">
          <form action="thankyou.php" method="post" id="payment-form">
						<span class="bg-danger" id="payment-errors"></span>
						<input type="hidden" name="tax" value="<?=$tax;?>">
						<input type="hidden" name="sub_total" value="<?=$sub_total;?>">
						<input type="hidden" name="grand_total" value="<?=$grand_total;?>">
						<input type="hidden" name="cart_id" value="<?=$cart_id;?>">
						<input type="hidden" name="description" value="<?=$item_count.' item'.(($item_count > 1)?'s':'').' from Shawns Place.';?>">
						<div id="step1" style="display:block;">
              <div class="form-group col-md-6">
                <label for="full-name">Full Name: </label>
                <input type="text" class="form-control" id="full-name" name="full-name">
              </div>
              <div class="form-group col-md-6">
                <label for="email">Email: </label>
                <input type="email" class="form-control" id="email" name="email">
              </div>
              <div class="form-group col-md-6">
                <label for="street">Street Address: </label>
                <input type="text" class="form-control" id="street" name="street">
              </div>
              <div class="form-group col-md-6">
                <label for="street2">Street Address 2: </label>
                <input type="text" class="form-control" id="street2" name="street2">
              </div>
              <div class="form-group col-md-6">
                <label for="city">City: </label>
                <input type="text" class="form-control" id="city" name="city">
              </div>
              <div class="form-group col-md-6">
                <label for="state">State: </label>
                <input type="text" class="form-control" id="state" name="state">
              </div>
              <div class="form-group col-md-6">
                <label for="zip">Zip Code: </label>
                <input type="text" class="form-control" id="zip" name="zip">
              </div>
              <div class="form-group col-md-6">
                <label for="country">Country: </label>
                <input type="text" class="form-control" id="country" name="country">
              </div>
            </div>
            <div id="step2" style="display:none;">
							<div class="form-group col-md-3">
								<label for="card-element">
							      Credit or debit card
							    </label>
							    <div id="card-element">
							      <!-- A Stripe Element will be inserted here. -->
							    </div>

							    <!-- Used to display Element errors. -->
							    <div id="card-errors" role="alert"></div>
							  	</div>
								</select>
							</div>
						</div>

        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="back_button" class="btn btn-primary" style="display:none;" onclick="back_address();"> Back </button>
				<button type="button" id="next_button" class="btn btn-primary" onclick="check_address();"> Next </button>
				<button type="submit" id="checkout_button" class="btn btn-primary" style="display:none;"> Check Out </button>
      </div>
			</form>
    </div>
  </div>
</div>
<!-- End Modal -->
    <?php endif; ?>
  </div>
</div>
<script>
function back_address(){
	jQuery('#payment-errors').html('');
	jQuery('#step1').css("display","block");
	jQuery('#step2').css("display","none");
	jQuery('#next_button').css("display","inline-block");
	jQuery('#back_button').css("display","none");
	jQuery('#checkout_button').css("display","none");
	jQuery('#checkoutModalLabel').html("Shipping Address");
}

function check_address($address){
	var data = {
		'full_name': jQuery('#full-name').val(),
		'email' : jQuery('#email').val(),
		'street' : jQuery('#street').val(),
		'street2' : jQuery('#street2').val(),
		'city' : jQuery('#city').val(),
		'state' : jQuery('#state').val(),
		'zip' : jQuery('#zip').val(),
		'country' : jQuery('#country').val()
	};
	jQuery.ajax({
		url		: '/my-php-shop/admin/parsers/check_address.php',
		method	: "post",
		data	: data,
		success	: function(data){
			if(data != 'passed'){
				jQuery('#payment-errors').html(data);
			}
			if(data == 'passed'){
				jQuery('#payment-errors').html('');
				jQuery('#step1').css("display","none");
				jQuery('#step2').css("display","block");
				jQuery('#next_button').css("display","none");
				jQuery('#back_button').css("display","inline-block");
				jQuery('#checkout_button').css("display","inline-block");
				jQuery('#checkoutModalLabel').html("Enter Your Card Details");
			}
		},
		error	: function(){
			alert("Your Address Was not able to be verified Try Again!");
		}
	});

}
var stripe = Stripe('<?=STRIPE_PUBLIC;?>');

var elements = stripe.elements();
// Custom styling can be passed to options when creating an Element.
var style = {
  base: {
    // Add your base input styles here. For example:
    fontSize: '16px',
    color: "#32325d",
  }
};

// Create an instance of the card Element.
var card = elements.create('card', {style: style});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');
card.addEventListener('change', function(event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
}

// Create a token or display an error when the form is submitted.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
  event.preventDefault();

  stripe.createToken(card).then(function(result) {
    if (result.error) {
      // Inform the customer that there was an error.
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server.
      stripeTokenHandler(result.token);
    }
  });
});

</script>

<?php
	include 'includes/footer.php';
  ?>
