<?php
	define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/');
	define('CART_COOKIE', '4rfsFFGHEF6666BSdfg');
	define('CART_COOKIE_EXPIRE', time() + (86400 * 30));
	define('TAXRATE', 0.087);

	define('CURRENCY', 'usd');
	//Change Test to live when Deployed
	define('CHECKOUTMODE', 'TEST');

	if(CHECKOUTMODE == 'TEST'){
		define('STRIPE_PRIVATE', 'sk_test_uoI9CNahb2WL8KAw7Amq8stw');
		define('STRIPE_PUBLIC', 'pk_test_a60APql0GnVARNkEOqhuFaau');
	}

	if(CHECKOUTMODE == 'LIVE'){
		define('STRIPE_PRIVATE', '');
		define('STRIPE_PUBLIC', '');
	}
