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

<?php
	include 'includes/footer.php';
?>
