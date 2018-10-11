<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	check_logged_in_status();
	$dbpath = '';

// Delete product
if(isset($_GET['delete'])) {
	$delete_id = (int)$_GET['delete'];
	$delete_id = sanitize($delete_id);
	$db->query("UPDATE products SET deleted = 1 WHERE id = '{$delete_id}'");
	page_redirect('products.php');
}

	if(isset($_GET['add']) || isset($_GET['edit'])) {
		$brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
		$parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

		$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']) : '');
		$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']) : '');
		$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']) : '');
		$category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']) : '');
		$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']) : '');
		$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']) : '');
		$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']) : '');
		$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']) : '');
		$sizes = rtrim($sizes, ',');
		$saved_image = '';

		if(isset($_GET['edit'])) {
			$edit_id = (int)$_GET['edit'];
			$productResults = $db->query("SELECT * FROM products WHERE id = '{$edit_id}'");
			$product = mysqli_fetch_assoc($productResults);

			if(isset($_GET['delete_image'])){
				$img_inc = $_GET['img_inc'];
				$inc = (int)$img_inc - 1;
				$images = explode(',', $product['image']);
				$image_url = $_SERVER['DOCUMENT_ROOT'].$images[$inc];
				unset($image_url);
				unset($images[$inc]);
				$imageString = implode(',', $images);
				$db->query("UPDATE products SET image = '{$imageString}' WHERE id = '{$edit_id}'");
				page_redirect('products.php?edit='.$edit_id);
			}

			$category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']) : $product['categories']);
			$title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']) : $product['title']);
			$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']) : $product['brand']);
			$parentQ = $db->query("SELECT * FROM categories WHERE id = '{$category}'");
			$parentResult = mysqli_fetch_assoc($parentQ);
			$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']) : $parentResult['parent']);
			$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']) : $product['price']);
			$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']) : $product['list_price']);
			$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']) : $product['description']);
			$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']) : $product['sizes']);
			$sizes = rtrim($sizes, ',');
			$saved_image = (($product['image'] != '')? $product['image']: '');
			$dbpath = $saved_image;
		}

		if(!empty($sizes)) {
			$sizeString = sanitize($sizes);
			$sizeString = rtrim($sizeString, ',');
			$sizesArray = explode(',', $sizeString);
			$sArray = array();
			$qArray = array();
			$tArray =array();
			foreach($sizesArray as $ss) {
				$s = explode(':', $ss);
				$sArray[] = $s[0];
				$qArray[] = $s[1];
				$tArray[] = $s[2];
			}
		} else {
			$sizesArray = array();
		}


		if($_POST) {

			$errors = array();

			$required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
			$allowed = array('png', 'jpg', 'jpeg', 'gif');
			$tmpLoc = array();
			$uploadPath = array();
			foreach($required as $field) {
				if($_POST[$field] == '') {
					$errors[] = 'All fields with an anterisk are required!';
					break;
				}
			}
			$photoCount = 0;
			if(isset($_FILES['photo'])){
			$photoCount = count($_FILES['photo']['name']);
			}
			if($photoCount > 0) {
				// var_dump($_FILES);
				for($i = 0;$i<$photoCount;$i++){
					$name = $_FILES['photo']['name'][$i];
					$nameArray = explode('.', $name);
					$fileName = $nameArray[0];
					$fileExt = $nameArray[1];
					$mime = explode('/', $_FILES['photo']['type'][$i]);
					$mimeType = $mime[0];
					$mimeExt = $mime[1];
					$tmpLoc[] = $_FILES['photo']['tmp_name'][$i];
					$fileSize = $_FILES['photo']['size'][$i];
					$uploadName = md5(microtime().$i).'.'.$fileExt;
					$uploadPath[] = BASEURL.'images/products/'.$uploadName;

					if($i != 0){
						$dbpath .= ',';
					}
					$dbpath .= '/my-php-shop/images/products/'.$uploadName;

					if($mimeType != 'image') {
						$errors[] .= 'The file must be an image.';
					}
					if(!in_array($fileExt, $allowed)) {
						$errors[] .= 'The file extension must be a png, jpg, jpeg, or gif.';
					}
					if($fileSize > 15000000) {
						$errors[] .= 'The file size must be under 15 megabytes.';
					}
					if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
						$errors[] .= 'File extension does not match the file.';
					}
				}
			}

			if(!empty($errors)) {
				echo display_errors($errors);
			} else {
				/* Upload file and insert into database. */
				if($photoCount > 0){
					for($i = 0;$i<$photoCount;$i++){
				move_uploaded_file($tmpLoc[$i], $uploadPath[$i]);
				}
			}
			$product_for_search = array(
				'title' => $title,
				'description' => $description,
				'categories' => $category,
				'brand' => $brand,
			);
			$sounds_like = make_sounds_like($product_for_search);
				$insertSql = "INSERT INTO products (title, price, list_price, brand, categories, image, description, sizes, sounds_like)
				VALUES ('{$title}', '{$price}', '{$list_price}', '{$brand}', '{$category}', '{$dbpath}', '{$description}', '{$sizes}', '{$sounds_like}')";

				if(isset($_GET['edit'])){
					$insertSql = "UPDATE products SET title = '{$title}', price = '{$price}', list_price = '{$list_price}', brand = '{$brand}',
					categories = '{$category}', image = '{$dbpath}', description = '{$description}', sizes = '{$sizes}', sounds_like = '{$sounds_like}'
					WHERE id = '{$edit_id}'";
				}
				$r = $db->query($insertSql);
				page_redirect('products.php');
			}
		}
?>


<!-- Form -->
<h2 class="text-center"><?php echo ((isset($_GET['edit']))?'Edit' : 'Add A New'); ?> Product</h2>
<hr>

<form class="form" id="product_form" name="product_form" action="products.php?<?php echo ((isset($_GET['edit']))?'edit='.$edit_id : 'add=1'); ?>" method="post" enctype="multipart/form-data">
	<div class="form-group col-md-3">
		<label for="title">Title*:</label>
		<input class="form-control" type="text" name="title" id="title" value="<?php echo $title; ?>">
	</div>
	<div class="form-group col-md-3">
		<label for="brand">Brand*:</label>
		<select class="form-control" name="brand" id="brand">
			<option value=""<?php echo (($brand == '')?' selected' : ''); ?>></option>
			<?php while($b = mysqli_fetch_assoc($brandQuery)) : ?>
			<option value="<?php echo $b['id']; ?>"<?php echo (($brand == $b['id'])?' selected' : ''); ?>><?php echo $b['brand']; ?></option>
			<?php endwhile; ?>
		</select>
	</div>
	<div class="form-group col-md-3">
		<label for="parent">Parent Category*:</label>
		<select class="form-control" name="parent" id="parent">
			<option value=""<?=(($parent == '')?' selected' : ''); ?>></option>
			<?php while($p = mysqli_fetch_assoc($parentQuery)) : ?>
			<option value="<?=$p['id']; ?>"<?=(($parent == $p['id'])?' selected' : ''); ?>><?=$p['category']; ?></option>
			<?php endwhile; ?>
		</select>
	</div>
	<div class="form-group col-md-3">
		<label for="child">Child Category*:</label>
		<select class="form-control" name="child" id="child"></select>
	</div>
	<div class="form-group col-md-3">
		<label for="price">Price*:</label>
		<input class="form-control" type="text" name="price" id="price" value="<?=$price;?>">
	</div>
	<div class="form-group col-md-3">
		<label for="list_price">List Price:</label>
		<input class="form-control" type="text" name="list_price" id="list_price" value="<?=$list_price?>">
	</div>
	<div class="form-group col-md-3">
		<label>Quantity &amp; Sizes*:</label>
		<button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity &amp; Sizes</button>
	</div>
	<div class="form-group col-md-3">
		<label for="sizes">Sizes &amp; Quantity Preview</label>
		<input class="form-control" type="text" name="sizes" id="sizes" value="<?=$sizes;?>" readonly>
	</div>
	<div class="form-group col-md-6">
		<?php if($saved_image != ''): ?>
			<?php
				$img_inc = 1;
				$photos = explode(',', $saved_image);
				foreach($photos as $photo) : ?>
			<div class="saved-image col-md-4"><img src="<?=$photo;?>" alt="Saved Image" /><br />
				<a style="text-align: center; margin-left: 4em; font-weight: bolder;"
				href="products.php?delete_image=1&edit=<?=$edit_id;?>&img_inc=<?=$img_inc;?>" class="text-danger"> Delete Image </a>
			</div>
		<?php
		$img_inc++;
		endforeach; ?>
		<?php else: ?>
		<label for="photo">Product Photo:</label>
		<input class="form-control" type="file" name="photo[]" id="photo" multiple>
		<?php endif; ?>
	</div>
	<div class="form-group col-md-6">
		<label for="description">Description</label>
		<textarea class="form-control" name="description" id="description" rows="6"><?=$description;?></textarea>
	</div>
	<div class="form-group pull-right">
		<a class="btn btn-default" href="products.php">Cancel</a>
		<button type="submit" form="product_form" value="Submit" class="btn btn-success"><?=((isset($_GET['edit']))?'Edit' : 'Add'); ?> Product</button>
	</div>
	<div class="clearfix"></div>
</form>


<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="sizesModalLabel">Size &amp; Quantity</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<?php for($i = 1; $i <= 12; $i++) : ?>
					<div class="form-group col-md-2">
						<label for="size<?=$i; ?>">Size: </label>
						<input class="form-control" type="text" name="size<?=$i; ?>" id="size<?=$i; ?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1] : ''); ?>">
					</div>
					<div class="form-group col-md-2">
						<label for="qty<?php echo $i; ?>">Quantity:</label>
						<input class="form-control" type="number" name="qty<?=$i; ?>" id="qty<?= $i; ?>" value="<?= ((!empty($qArray[$i-1]))?$qArray[$i-1] : ''); ?>" min="0">
					</div>
					<div class="form-group col-md-2">
						<label for="threshold<?php echo $i; ?>">Inv Threshold:</label>
						<input class="form-control" type="number" name="threshold<?=$i; ?>" id="threshold<?= $i; ?>" value="<?= ((!empty($tArray[$i-1]))?$tArray[$i-1] : ''); ?>" min="0">
					</div>
					<?php endfor; ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
			</div>
		</div>
	</div>
</div>


<?php
	} else {

	$presults = $db->query("SELECT * FROM products WHERE deleted = 0");
	if(isset($_GET['featured'])) {
		$id = (int)$_GET['id'];
		$featured = (int)$_GET['featured'];
		$db->query("UPDATE products SET featured = '{$featured}' WHERE id = '{$id}'");
		page_redirect('products.php');
	}
?>


<!-- Table -->
<h2 class="text-center">Products</h2>
<a class="btn btn-success pull-right" id="add-product-btn" href="products.php?add=1">Add Product</a>
<div class="clearfix"></div>
<hr>

<table class="table table-bordered table-condensed table-striped">
	<thead>
		<th></th>
		<th>Product</th>
		<th>Price</th>
		<th>Category</th>
		<th>Featured</th>
		<th>Sold</th>
	</thead>
	<tbody>
		<?php while($product = mysqli_fetch_assoc($presults)) :
			$childID = $product['categories'];
			$result = $db->query("SELECT * FROM categories WHERE id = '{$childID}'");
			$child = mysqli_fetch_assoc($result);
			$parentID = $child['parent'];
			$presult = $db->query("SELECT * FROM categories WHERE id = '$parentID'");
			$parent = mysqli_fetch_assoc($presult);
			$category = $parent['category'].' ~ '.$child['category'];
		?>
		<tr>
			<td>
				<a class="btn btn-xs btn-default" href="products.php?edit=<?php echo $product['id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
				<a class="btn btn-xs btn-default" href="products.php?delete=<?php echo $product['id']; ?>"><span class="glyphicon glyphicon-remove"></span></a>
			</td>
			<td><?php echo $product['title']; ?></td>
			<td><?php echo money($product['price']); ?></td>
			<td><?php echo $category; ?></td>
			<td>
				<a class="btn btn-xs btn-default" href="products.php?featured=<?php echo (($product['featured'] == 0)?'1' : '0'); ?>&id=<?php echo $product['id']; ?>"><span class="glyphicon glyphicon-<?php echo (($product['featured'] == 1)?'minus': 'plus'); ?>"></span></a>&nbsp; <?php echo (($product['featured'] == 1)?'Featured Product' : ''); ?>
			</td>
			<td>0</td>
		</tr>
		<?php endwhile; ?>
	</tbody>
</table>


<?php } include 'includes/footer.php'; ?>
<script>
	jQuery('document').ready(function(){
		get_child_options('<?=$category;?>');
	});
</script>
