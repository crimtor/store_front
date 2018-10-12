<?php
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/headerfull.php';

	$sql = "SELECT * FROM products WHERE featured = 1";
	$featured = $db->query($sql);
?>

<div class="row">
<?php include 'includes/leftbar.php'; ?>
	<!-- Main Content -->
	<div class="col-md-8">
		<h2 class="text-center">Featured Products</h2><br />
		<div class="row">

			<?php while($product = mysqli_fetch_assoc($featured)) : ?>
			<div class="col-lg-3 col-md-4 col-sm-6">
				<h4><?php echo $product['title']; ?></h4>
				<?php $photos = explode(',', $product['image']); ?>
				<img class="img-thumb" src="<?= $photos[0] ?>" alt="<?= $product['title']; ?>">
				<p class="list-price text-danger">List Price: <s><?=$product['list_price']; ?></s></p>
				<p class="price">Our Price: <?php echo $product['price']; ?></p>
				<button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $product['id']; ?>)">Details</button>
			</div>
			<?php endwhile; ?>

		</div>
	</div>

<?php
	include 'includes/rightbar.php';
	?>
</div>
	<?php include 'includes/footer.php';
?>
