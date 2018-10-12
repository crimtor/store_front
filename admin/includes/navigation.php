
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="/my-php-shop/index.php">Shawn's Business Website</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
			<!--- Menu Items --->
			<li><a href="index.php">My Dashboard</a></li>
			<li><a href="brands.php">Brands</a></li>
			<li><a href="categories.php">Categories</a></li>
			<li><a href="products.php">Products</a></li>
			<li><a href="archived.php">Archived</a></li>
				<?php if(has_permission()) : ?>
					<li><a href="users.php">Users</a></li>
				<?php endif; ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data['full_name'];?>!
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="change_password.php">Change Password</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</li>
		</ul>
  </div>
</nav>
<!-- Navbar -->
