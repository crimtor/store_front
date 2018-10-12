<?php
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/header-partial.php';

  $sql = "SELECT * FROM products";
  $cat_id = (($_POST['cat_id'] != '')?sanitize($_POST['cat_id']):'');
  if($cat_id == ''){
    $sql .= " WHERE deleted = 0";
  }else{
    $sql .= " WHERE categories = '{$cat_id}' and deleted = 0";
  }
	$search_name = ((isset($_POST['search_name']) && $_POST['search_name'] != '')?sanitize($_POST['search_name']):'');
  $price_sort = ((isset($_POST['price_sort']) && $_POST['price_sort'] != '')?sanitize($_POST['price_sort']):'');
  $min_price = ((isset($_POST['min_price']) && $_POST['min_price'] != '')?sanitize($_POST['min_price']):'');
  $max_price = ((isset($_POST['max_price']) && $_POST['max_price'] != '')?sanitize($_POST['max_price']):'');
  $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):'');
	if($search_name != ''){
		$search_name = metaphone($search_name);
    $sql .= " AND sounds_like like '%{$search_name}%'";
  }
	if($min_price != ''){
    $sql .= " AND price >= '{$min_price}'";
  }
  if($max_price != ''){
    $sql .= " AND price <= '{$max_price}'";
  }
  if($brand != ''){
    $sql .= " AND brand = '{$brand}'";
  }
  if($price_sort == 'low'){
    $sql .= " ORDER BY price";
  }
  if($price_sort == 'hgh'){
    $sql .= " ORDER BY price DESC";
  }

  $products = $db->query($sql);
  $category = get_catergories($cat_id);
?>

<div class="row">
<?php include 'includes/leftbar.php'; ?>

	<!-- Main Content -->
	<div class="col-md-8">
		<?php if($cat_id != ''): ?>
		<h2 class="text-center"><?=$category['parent']. ' ' . $category['child'];?></h2><br />
	<?php else: ?>
		<h2 class="text-center">Search Results</h2><br />
	<?php endif; ?>
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
	<?php
		include 'includes/rightbar.php';
		?>
	</div>
		<?php include 'includes/footer.php';
	?>
