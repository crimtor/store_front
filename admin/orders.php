<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/core/init.php';
	check_logged_in_status();
  include 'includes/head.php';
	include 'includes/navigation.php';

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







  <?php
  	include 'includes/footer.php';
    ?>
