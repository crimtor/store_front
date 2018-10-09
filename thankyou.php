<?php
	require_once 'core/init.php';
	include 'includes/head.php';

  // Set your secret key: remember to change this to your live secret key in production
  // See your keys here: https://dashboard.stripe.com/account/apikeys
  \Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

  // Token is created using Checkout or Elements!
  // Get the payment token ID submitted by the form:
  $token = $_POST['stripeToken'];
  $name = sanitize($_POST['full_name']);
  $email = sanitize($_POST['email']);
  $street = sanitize($_POST['street']);
  $street2 = sanitize($_POST['street2']);
  $city = sanitize($_POST['city']);
  $state = sanitize($_POST['state']);
  $country = sanitize($_POST['country']);
  $zip = sanitize($_POST['zip']);
  $tax = sanitize($_POST['tax']);
  $sub_total = sanitize($_POST['sub_total']);
  $grand_total = sanitize($_POST['grand_total']);
  $cart_id = sanitize($_POST['cart_id']);
  $description = sanitize($_POST['description']);
  $charge_amount = number_format($grand_total, 2) * 100;
  $meta_data = array(
    "cart_id" => $cart_id,
    "tax"   => $tax,
    "sub_total" => $sub_total,
  );

  try {
    // Use Stripe's library to make requests...
    $charge = \Stripe\Charge::create([
        'amount' => $charge_amount,
        'currency' => CURRENCY,
        'description' => $description,
        'source' => $token,
        'receipt_email' => $email,
        'metadata' => $meta_data,
    ]);

    $db->query("UPDATE cart SET paid = 1 WHERE id = '{$cart_id}'");
    $db->query("INSERT INTO transactions (charge_id, cart_id, full_name, email, street, street2, city, state, zip, country, sub_total, tax, grand_total, description, trn_type)
    VALUES ('{$charge->id}', '{$cart_id}', '{$name}', '{$email}', '{$street}', '{$street2}', '{$city}', '{$state}', '{$zip}', '{$country}', '{$sub_total}', '{$tax}',
    '{$grand_total}', '{$description}', '{$charge->object}')");
    $domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);
    setcookie(CART_COOKIE, '', 1, "/", $domain, false);
    include 'includes/navigation.php';
  	include 'includes/header-partial.php';
    ?>
    <h1  class="text-center text-success"> Thank You!</h1>
    <p> Your card has been successfully charged <?=money($grand_total);?>, You have been emailed a receipt.
      Please check your spam folder if you do not see it in your inbox. Additionally you can print this page as a receipt.</p>
      <p>Your receipt number is: <strong><?=$cart_id;?></strong></p>
      <p>Your order will be shipped to the address below.</p>
      <address>
        <?=$name;?><br />
        <?=$street;?><br />
  } catch(\Stripe\Error\Card $e) {
    // Since it's a decline, \Stripe\Error\Card will be caught
    $body = $e->getJsonBody();
    $err  = $body['error'];

    print('Status is:' . $e->getHttpStatus() . "\n");
    print('Type is:' . $err['type'] . "\n");
    print('Code is:' . $err['code'] . "\n");
    // param is '' in this case
    print('Param is:' . $err['param'] . "\n");
    print('Message is:' . $err['message'] . "\n");
  } catch (\Stripe\Error\RateLimit $e) {
    // Too many requests made to the API too quickly
  } catch (\Stripe\Error\InvalidRequest $e) {
    // Invalid parameters were supplied to Stripe's API
  } catch (\Stripe\Error\Authentication $e) {
    // Authentication with Stripe's API failed
    // (maybe you changed API keys recently)
  } catch (\Stripe\Error\ApiConnection $e) {
    // Network communication with Stripe failed
  } catch (\Stripe\Error\Base $e) {
    // Display a very generic error to the user, and maybe send
    // yourself an email
  } catch (Exception $e) {
    // Something else happened, completely unrelated to Stripe
  }
?>
