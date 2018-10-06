<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/core/init.php';
	$product_id = sanitize($_POST['product-id']);
  $size = sanitize($_POST['size']);
  $available = sanitize($_POST['available']);
  $quantity = sanitize($_POST['quantity']);
  $cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
  $item = array();
  $item[] = array(
    'id'    => $product_id,
    'size'  => $size,
    'quantity' => $quantity,
  );

$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
$query = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
$product = mysqli_fetch_assoc($query);
$_SESSION['success_flash'] = $product['title'] . " was added to your cart.";

//check if cart cookie exist
if($cart_id != ''){
  $cart_query = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
  $cart = mysqli_fetch_assoc($cart_query);
  $previous_items = json_decode($cart['items'], true);
  $items_match = 0;
  foreach ($previous_items as $pitem) {
    if($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size']){
      $pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
      if($pitem['quantity'] > $available){
        $pitem['quantity'] = $available;
      }
      $items_match = 1;
    }
    $new_items[] = $pitem;
  }
  if($items_match != 1){
    $new_items = array_merge($item, $previous_items);
  }
  $items_json = json_encode($new_items);
  $db->query("UPDATE cart SET items = '{$items_json}', expire_date = '{$cart_expire}' WHERE id = {$cart_id}");
  setcookie(CART_COOKIE, '', 1, '/', $domain, false);
  setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);
}else{
  //add the cart to db and set cookie
  $items_json = json_encode($item);
  $db->query("INSERT INTO cart (items, expire_date) VALUES ('{$items_json}', '{$cart_expire}')");
  $cart_id = $db->insert_id;
  setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);
}
?>
