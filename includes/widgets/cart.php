<?php
	require_once 'core/init.php';
  ?>

<h3 class="text-center">Shopping Cart</h3>
<div>
  <?php if(empty($cart_id)): ?>
<p class="text-center">Your Shopping Cart is Empty.</p>
  <?php else:
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $results = mysqli_fetch_assoc($cartQ);
    $items = json_decode($results['items'], true);
    $sub_total = 0;
    ?>
<table class="table table-condensed" id="cart_widget">
  <tbody>
    <?php foreach ($items as $item):
      $item_id = $item['id'];
      $product_query = $db->query("SELECT * FROM products WHERE id = '{$item_id}'");
      $product = mysqli_fetch_assoc($product_query);
    ?>
      <tr>
        <td><?=$item['quantity'];?></td>
        <td><?=substr($product['title'], 0, 15);?></td>
        <td><?=money($item['quantity'] * $product['price']);?></td>
      </tr>
    <?php
    $sub_total += ($item['quantity'] * $product['price']);

  endforeach; ?>
  <tr>
    <td></td>
    <td>Sub Total</td>
    <td><?=money($sub_total);?></td>
  </tr>
  </tbody>
</table>
<a href="cart.php" class="btn btn-xs btn-primary pull-right">View Cart</a>
<div class="clearfix"></div>
<?php endif; ?>
</div>
