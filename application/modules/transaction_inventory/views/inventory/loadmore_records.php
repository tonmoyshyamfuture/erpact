<?php
// foreach ($gst as $key => $value) {
// 	echo "<h1>" . $value->name . "</h1>";
// }

?>

<?php foreach ($all_entries as $entry) { ?> 
<tr id="current_row_<?php echo $entry['id']; ?>">
	<td><?php echo date('d-m-Y', strtotime($entry['create_date'])); ?></td>
	<td><?php echo $entry['entry_no']; ?>&nbsp;&nbsp;<span data-toggle="tooltip" title="<?php echo 'Ref. No. ' . $entry['voucher_no'] ?>"><?php echo (strlen($entry['voucher_no']) > 0) ? ((strlen($entry['voucher_no']) > 6) ? '(Ref. No. ' . substr($entry['voucher_no'], 0, 6) . '...' . ')' : '(Ref. No. ' . $entry['voucher_no'] . ')') : ''; ?></span></td>
	<td>
		<a href="<?php echo base_url('transaction/invoice') . '.aspx/' . $entry['id'] . '/' . $entry['entry_type_id']; ?>">
			<?php
			$led = array();
			$devit = json_decode($entry['ledger_ids_by_accounts']);

			echo "<strong>Dr </strong>";
			if (isset($devit->Dr)) {
				for ($i = 0; $i < count($devit->Dr); $i++) {
					echo $devit->Dr[$i];
					if (isset($devit->Dr) && count($devit->Dr) > 1) {
						echo ' + ';
					}
					break;
				}
			}
			?>
			/
			<?php
			echo "<strong>Cr </strong>";
			for ($i = 0; $i < count($devit->Cr); $i++) {
				echo $devit->Cr[$i];
				if (count($devit->Cr) > 1) {
					echo ' + ';
				}
				break;
			}
			?>
		</a>
	</td>
	<td><?php echo $entry['type']; ?></td>
	<?php
	if ($entry_type_id == 7 || $entry_type_id == 8 || $parent_id == 7 || $parent_id == 8):
		?>
		<td><span class="label <?php echo (isset($entry['order_status']) && $entry['order_status'] == 'Open') ? 'label-success' : ((isset($entry['order_status']) && $entry['order_status'] == 'Partial') ? 'label-primary' : 'label-danger') ?>"><?php echo $entry['order_status']; ?></span></td>
	<?php endif; ?>
	<td class="text-right"><?php echo $this->price_format($entry['cr_amount']); ?></td>
	<td>
		<div class="dropdown circle">
			<a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-ellipsis-v"></i></a>
				<ul class="dropdown-menu tablemenu">

					<?php
					$permission = ua($module, 'edit');
					if ($permission):
						if ($entry_type_id == 5 || $parent_id == 5):
							?>
							<li>
								<a href="<?php echo site_url('transaction/sales-update') . "/" . $entry['id'] . "/" . $entry_type_id . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
							</li>
						<?php elseif ($entry_type_id == 6 || $parent_id == 6): ?>
							<li>
								<a href="<?php echo site_url('transaction/purchase-update') . "/" . $entry['id'] . "/" . $entry_type_id . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>                                                          
							</li>
						<?php elseif ($entry_type_id == 14 || $parent_id == 14): ?>
							<li>
								<a href="<?php echo site_url('transaction/credit-note-update') . "/" . $entry['id'] . "/" . $entry_type_id . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>                                                          
							</li>
						<?php elseif ($entry_type_id == 12 || $parent_id == 12): ?>
							<li>
								<a href="<?php echo site_url('transaction/debit-note-update') . "/" . $entry['id'] . "/" . $$entry_type_id . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>                                                          
							</li>
						<?php elseif ($entry_type_id == 7 || $parent_id == 7): ?>
							<li>
								<a href="<?php echo site_url('transaction/sales-order-update') . "/" . $entry['id'] . "/" . $entry_type_id . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>  
							</li>
							<?php if (isset($entry['order_status']) && ($entry['order_status'] == 'Open' || $entry['order_status'] == 'Partial')): ?>
								<li>
									<a href="<?php echo site_url('transaction/sales-order-sales') . "/" . $entry['id'] . "/" . $entry_type_id . "/t"; ?>" data-toggle="tooltip" title="Sales" > <i class="fa fa-shopping-basket" aria-hidden="true"></i></a>  
								</li>
							<?php endif; ?>
						<?php elseif ($entry_type_id == 8 || $parent_id == 8): ?>
							<li>
								<a href="<?php echo site_url('transaction/purchase-order-update') . "/" . $entry['id'] . "/" . $entry_type_id . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>  
							</li>
							<?php if (isset($entry['order_status']) && ($entry['order_status'] == 'Open' || $entry['order_status'] == 'Partial')): ?>
								<li>
									<a href="<?php echo site_url('transaction/purchase-order-purchase') . "/" . $entry['id'] . "/" . $entry_type_id . "/t"; ?>" data-toggle="tooltip" title="Purchase" > <i class="fa fa-shopping-basket" aria-hidden="true"></i></a>  
								</li>
							<?php endif; ?>
						<?php elseif ($entry_type_id == 9 || $parent_id == 9): ?>
							<li>
								<a href="<?php echo site_url('transaction/receive-note-update') . "/" . $entry['id'] . "/" . $entry_type_id . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>  
							</li>
							<li>
								<a href="<?php echo site_url('transaction/receive-note-purchase') . "/" . $entry['id'] . "/" . $entry_type_id . "/t"; ?>" data-toggle="tooltip" title="Purchase" > <i class="fa fa-shopping-basket" aria-hidden="true"></i></a>  
							</li>
						<?php elseif ($entry_type_id == 10 || $parent_id == 10): ?>
							<li>
								<a href="<?php echo site_url('transaction/delivery-note-update') . "/" . $entry['id'] . "/" . $entry_type_id . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>  
							</li>
							<li>
								<a href="<?php echo site_url('transaction/delivery-note-sales') . "/" . $entry['id'] . "/" . $entry_type_id . "/t"; ?>" data-toggle="tooltip" title="Sales" > <i class="fa fa-shopping-basket" aria-hidden="true"></i></a>  
							</li>
							<?php
						endif;
					endif;
					?>

					<li>
						<?php
						$permission = ua($module, 'delete');
						if ($permission):
							if ($entry['entry_type_id'] == 5 || $entry['entry_type_id'] == 6 || $entry['entry_type_id'] == 12 || $entry['entry_type_id'] == 14):
								?>
								<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" delete-type="current"  data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
								<?php
							elseif ($entry['entry_type_id'] == 7 || $entry['entry_type_id'] == 8):
								?>
								<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" delete-type="current" data-type="request" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
								<?php
							elseif ($entry['entry_type_id'] == 9 || $entry['entry_type_id'] == 10):
								?>
								<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" delete-type="current" data-type="temp-note" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
								<?php
							endif;
						endif;
						?>
					</li>
				</ul>


			</div>

		</td>
	</tr>
	<?php } ?>
