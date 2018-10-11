<!-- Navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/my-php-shop/index.php">Shawn's Business Website</a>
		</div>

		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
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
				<!-- <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $parent['category']; ?> <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#"><?php echo $child['category']; ?></a></li>
					</ul>
				</li> -->

			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container -->
</nav>
