<?php
  $cat_id = ((isset($_REQUEST['cat']))?sanitize($_REQUEST['cat']): '');
	require_once 'core/init.php';
  $price_sort = ((isset($_REQUEST['price_sort']))?sanitize($_REQUEST['price_sort']): '');
  $min_price = ((isset($_REQUEST['min_price']))?sanitize($_REQUEST['min_price']): '');
  $max_price = ((isset($_REQUEST['max_price']))?sanitize($_REQUEST['max_price']): '');
  $brand = ((isset($_REQUEST['brand']))?sanitize($_REQUEST['brand']): '');
  $brand_query = $db->query("SELECT * FROM brand ORDER BY brand");

  ?>
  <br />
  <h3 class="text-center">Search By:</h3>
  <h4 class="text-center">Name</h4>
  <form action="search.php" method="post">
    <input type="hidden" name="cat_id" value="<?=$cat_id;?>">
    <input type="hidden" name="price_sort" value="0">
    <input class="form-control text-center" type="text" name="search_name" placeholder="Search Here">
    <h4 class="text-center">Price</h4>
    <input type="radio" name="price_sort" value="low"
    <?=(($price_sort == 'low')?' checked':'');?>>Low to High<br />
    <input type="radio" name="price_sort" value="high"
    <?=(($price_sort == 'high')?' checked':'');?>>High to Low<br />
    <br />
    <input type="text" name="min_price" class="price-range" placeholder="Min $" value="<?=$min_price;?>">To
    <input type="text" name="max_price" class="price-range" placeholder="Max $" value="<?=$max_price;?>"><br /><br />
    <h4 class="text-center">Brand</h4>
    <input type="radio" name="brand" value=""<?=(($brand == '')?' checked':'');?>>All<br />
    <?php while($brand_name = mysqli_fetch_assoc($brand_query)): ?>
      <input type="radio" name="brand" value="<?=$brand_name['id']?>"<?=(($brand == $brand_name['id'])?' checked':'')?>><?=$brand_name['brand'];?><br />
    <?php endwhile; ?>
    <input type="submit" value="Search" class="btn btn-xs btn-primary">
  </form>
