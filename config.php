<?php
	define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/');
	define('CART_COOKIE', '4rfsFFGHEF6666BSdfg');
	define('CART_COOKIE_EXPIRE', time() + (86400 * 30));
	define('TAXRATE', 0.087);

	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', 'fixoton_wrdp1');

	/** MySQL database username */
	define('DB_USER', 'fixoton_wrdp1');

	/** MySQL database password */
	define('DB_PASSWORD', 'LLQajcLsYZQxN0X5');

	/** MySQL hostname */
	define('DB_HOST', 'localhost');

	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', 'utf8mb4');

	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');

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
