<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/core/init.php';
	check_logged_in_status();
  include 'includes/head.php';
	include 'includes/navigation.php';

//complete the order
if(isset($_GET['complete']) && $_GET['complete'] == 1){
	$cart_id = sanitize((int)$_GET['cart_id']);
	$db->query("UPDATE cart SET shipped = 1 WHERE id = '{$cart_id}'");
	$_SESSION['success_flash'] = "The Order has been marked as shipped!";
	page_redirect("index.php");
}
  $trn_id = ((isset($_GET['trn_id']))?sanitize((int)$_GET['trn_id']) : 1);
  $trn_query = $db->query("SELECT * FROM transactions WHERE id = '{$trn_id}'");
  $trn = mysqli_fetch_assoc($trn_query);
  $cart_id = $trn['cart_id'];
  $cart_query = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
  $cart = mysqli_fetch_assoc($cart_query);
  $items = json_decode($cart['items'], true);
  $id_array = array();
  $products = array();
  $x = array();
  foreach ($items as $item) {
    $id_array[] = $item['id'];
  }
  $ids = implode(',', $id_array);
  $product_query = $db->query(
    "SELECT i.id as 'id', i.title as 'title', c.id as 'cid', c.category as 'child', p.category as 'parent'
    FROM products i
    LEFT JOIN categories c on i.categories = c.id
    LEFT JOIN categories p ON c.parent = p.id
    WHERE i.id IN({$ids})");

while($p = mysqli_fetch_assoc($product_query)){

  foreach ($items as $item) {
    if($item['id'] == $p['id']){
      $x = $item;
      continue;
    }
  }
  $products[] = array_merge($x, $p);
}
  ?>
  <h2 class="text-center">Items Ordered</h2>
  <table class="table table-condensed table-bordered table-striped">
    <thead>
      <th>Quantity</th>
      <th>Title</th>
      <th>Category</th>
      <th>Size</th>
    </thead>
    <tbody>
      <?php foreach ($products as $product): ?>
      <tr>
        <td><?=$product['quantity'];?></td>
        <td><?=$product['title'];?></td>
        <td><?=$product['parent'].' ~ '.$product['child'];?></td>
        <td><?=$product['size'];?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

	<div class="row">
		<div class="col-md-6">
			<h3 class="text-center">Order Details</h3>
			<table class="table table-condensed table-bordered table-striped">
				<tbody>
					<tr>
						<td>Sub Total</td>
						<td><?=money($trn['sub_total']);?></td>
					</tr>
					<tr>
						<td>Tax</td>
						<td><?=money($trn['tax']);?></td>
					</tr>
					<tr>
						<td>Grand Total</td>
						<td><?=money($trn['grand_total']);?></td>
					</tr>
					<tr>
						<td>Transaction Date</td>
						<td><?=format_date($trn['trn_date']);?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="col-md-6">
			<h3 class="text-center">Shipping Address Details</h3>
			<address>
        <?=$trn['full_name'];?><br />
        <?=$trn['street'];?><br />
				<?=(($trn['street2'] != '')?$trn['street2'].'<br />':'');?>
				<?=$trn['city']. ', '.$trn['state']. ' '.$trn['zip'];?>,<br />
				<?=$trn['country'];?><br />
			</address>
		</div>

	</div>

	<div class="pull-right">
		<a href="index.php" class="btn btn-large btn-default">Cancel</a>
		<a href="orders.php?complete=1&cart_id=<?=$cart_id;?>" class="btn btn-large btn-primary">Mark Order Complete</a>
	</div>






  <?php
  	include 'includes/footer.php';
    ?>
