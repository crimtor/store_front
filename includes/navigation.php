<?php
	$sql = "SELECT * FROM categories WHERE parent = 0";
	$pquery = $db->query($sql);
?>

<!-- Navbar -->
<div class="container-fluid" id="navagation_main">
<nav class="navbar navbar-default navbar-toggleable-sm navbar-fixed-top">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
	<a class="navbar-brand" href="index.php">Your Business Here</a>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
		<ul class="nav navbar-nav">
			<?php while($parent = mysqli_fetch_assoc($pquery)) : ?>
			<?php
				$parent_id = $parent['id'];
				$sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
				$cquery = $db->query($sql2);
			?>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $parent['category']; ?> <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<?php while($child = mysqli_fetch_assoc($cquery)) : ?>
					<li><a href="category.php?cat=<?=$child['id'];?>"><?=$child['category']; ?></a></li>
					<?php endwhile; ?>
				</ul>
			</li>
			<?php endwhile; ?>
			<li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart </a></li>
		</ul>
  </div>
</nav>
</div>
<!-- Navbar -->


</nav>
