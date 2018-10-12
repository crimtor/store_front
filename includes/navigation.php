<?php
	$sql = "SELECT * FROM categories WHERE parent = 0";
	$pquery = $db->query($sql);
	$cat_id = ((isset($_REQUEST['cat']))?sanitize($_REQUEST['cat']): '');
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="index.php">Your Business Here</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
			<?php while($parent = mysqli_fetch_assoc($pquery)) : ?>
			<?php
				$parent_id = $parent['id'];
				$sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
				$cquery = $db->query($sql2);
			?>
			<li class="nav-item dropdown">
				<a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $parent['category']; ?> <span class="caret"></span></a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<?php while($child = mysqli_fetch_assoc($cquery)) : ?>
					<a class="dropdown-item" href="category.php?cat=<?=$child['id'];?>"><?=$child['category']; ?></a>
					<?php endwhile; ?>
				</div>
			</li>
			<?php endwhile; ?>
			<li style="margin-top:.5em; padding-right: 1em;"><a href="cart.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> My Cart </a></li>
		</ul>
    <form action="search.php" method="post" class="form-inline my-2 my-lg-0">
			<input type="hidden" name="cat_id" value="<?=$cat_id;?>">
      <input name="search_name" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
<!-- Navbar -->
