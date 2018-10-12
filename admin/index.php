<?php
	require_once '../core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	check_logged_in_status();
?>
<!--- ORDERS TO FILL ---->
<?php
$trnQuery = "SELECT t.id, t.cart_id, t.full_name, t.trn_date, t.grand_total, c.items, c.paid, c.shipped
 FROM transactions t
 LEFT JOIN cart c ON t.cart_id = c.id
 WHERE c.paid = 1 AND c.shipped =0
 ORDER BY t.trn_date";
 $trn_results = $db->query($trnQuery);
 ?>
 <br />
 <h1 class="text-center"> My Dashboard </h1><hr><br />
<div class="col-md-12">
	<h3 class="text-center">Orders to Ship</h3>
	<table class="table table-condensed table-bordered table-striped">
		<thead>
			<th></th><th>Name</th><th>Description</th><th>Total</th><th>Date</th>
		</thead>
		<tbody>
		<?php while($order = mysqli_fetch_assoc($trn_results)): ?>
			<tr>
				<td><a href="orders.php?trn_id=<?=$order['id'];?>" class="btn btn-xs btn-info">Details</a></td>
				<td><?=$order['full_name'];?></td>
				<td><?=$order['items'];?></td>
				<td><?=money($order['grand_total']);?></td>
				<td><?=format_date($order['trn_date']);?></td>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
</div>

<div class="row">
	<!--- Sales By Month --->
	<?php
	$thisYr = date("Y");
	$lastYr = $thisYr - 1;
	$thisYrQuery = $db->query("SELECT grand_total, trn_date FROM transactions WHERE YEAR(trn_date) = '{$thisYr}'");
	$lastYrQuery = $db->query("SELECT grand_total, trn_date FROM transactions WHERE YEAR(trn_date) = '{$lastYr}'");
	$current = array();
	$last = array();
	$current_total = 0;
	$last_total = 0;

	while($x = mysqli_fetch_assoc($thisYrQuery)){
		$month = date("m", strtotime($x['trn_date']));
		if(!array_key_exists($month, $current)) {
			$current[(int)$month] = $x['grand_total'];
		}else{
			$current[(int)$month] += $x['grand_total'];
		}
		$current_total += $x['grand_total'];
	}

	while($y = mysqli_fetch_assoc($lastYrQuery)){
		$month = date("m", strtotime($y['trn_date']));
		if(!array_key_exists($month, $last)) {
			$last[(int)$month] = $y['grand_total'];
		}else{
			$last[(int)$month] += $y['grand_total'];
		}
		$last_total += $y['grand_total'];
	}
	?>

	<div class="col-md-4">
		<h3 class="text-center">Sales By Month</h3>
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<th></th><th><?=$lastYr;?></th><th><?=$thisYr;?></th>
			</thead>
			<tbody>
			<?php for($i=1; $i<=12;$i++):
				$dt = DateTime::createFromFormat('!m',$i); ?>
				<tr <?=(date("m") == $i)?' class="info"':'';?>>
					<td><?=$dt->format("F");?></td>
					<td><?=(array_key_exists($i,$last))?money($last[$i]):money(0);?></td>
					<td><?=(array_key_exists($i,$current))?money($current[$i]):money(0);?></td>
				</tr>
			<?php endfor; ?>
			<tr>
				<td></td>
				<td><?=money($last_total);?></td>
				<td><?=money($current_total);?></td>
			</tr>
			</tbody>
		</table>
	</div>

<!--- INVENTORY SECTION --->
<?php
	$items_query = $db->query("SELECT * FROM products WHERE deleted = 0");
	$low_inv_items = array();
	while($product = mysqli_fetch_assoc($items_query)){
		$item = array();
		$sizes = sizesToArray($product['sizes']);
		foreach ($sizes as $size) {
			if($size['quantity'] <= $size['threshold']){
				$cat = get_catergories($product['categories']);
				$item = array(
					'title' => $product['title'],
					'size' => $size['size'],
					'quantity' => $size['quantity'],
					'threshold' => $size['threshold'],
					'category' => $cat['parent']. ' ~ '. $cat['child'],
				);
				$low_inv_items[] = $item;
			}
		}
	}
	?>
	<div class="col-md-8">
		<h3 class="text-center">Low Inventory</h3>
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<th>Product</th><th>Category</th><th>Size</th><th>Quantity</th><th>Threshold</th>
			</thead>
			<tbody>
				<?php foreach ($low_inv_items as $item): ?>
				<tr <?=($item['quantity'] == 0)?' class="danger"':'';?>>
					<td><?=$item['title'];?></td>
					<td><?=$item['category'];?></td>
					<td><?=$item['size'];?></td>
					<td><?=$item['quantity'];?></td>
					<td><?=$item['threshold'];?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>

</div>
<?php
	include 'includes/footer.php';
?>
