<?php
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/header-partial.php';

  if(isset($_GET['cat'])){
    $cat_id = sanitize($_GET['cat']);
  }else{
    $cat_id = '';
  }

	$sql = "SELECT * FROM products WHERE categories = '{$cat_id}'";
	$products = $db->query($sql);
  $category = get_catergories($cat_id);
?>

<div class="row">
<?php include 'includes/leftbar.php'; ?>

	<!-- Main Content -->
	<div class="col-md-8">
				<br />
				<h2 class="text-center"><?=$category['parent']. ' ' . $category['child'];?></h2>
				<br />
		<div class="row">

			<?php while($product = mysqli_fetch_assoc($products)) : ?>
			<div class="col-md-3">
				<h4><?php echo $product['title']; ?></h4>
				<?php $photos = explode(',', $product['image']); ?>
				<img class="img-thumb" src="<?= $photos[0]; ?>" alt="<?php echo $product['title']; ?>">
				<p class="list-price text-danger">List Price: <s><?php echo $product['list_price']; ?></s></p>
				<p class="price">Our Price: <?php echo $product['price']; ?></p>
				<button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $product['id']; ?>)">Details</button>
			</div>
			<?php endwhile; ?>

		</div>
	</div>

	<?php include 'includes/rightbar.php';
?>
</div>
<?php
	include 'includes/footer.php';
?>
