<!-- <table class="table borderless">
	<?php foreach ($details as $key => $value): ?>
		<tr>			
			<td><?php echo $value->ledger_name . " (". $value->account_type .")"; ?></td>
			<td><?php echo $this->price_format($value->balance); ?></td>
		</tr>
	<?php endforeach ?>
		<tr>
			<td>Narration</dt>
			<td><?php echo $details[0]->narration; ?></td>
		</tr>
</table> -->
<dl class="dl-horizontal">
	<?php foreach ($details as $key => $value): ?>
		<dt><?php echo $value->ledger_name . " (". $value->account_type .")"; ?></dt>
		<dd><?php echo $this->price_format($value->balance); ?></dd>
	<?php endforeach ?>
 	<dt>Narration</dt>
 	<dd><?php echo $details[0]->narration; ?></dd>
</dl>
 ** <em>Please click on the ledger above for more details.</em><span class="pull-right close">Close</span>
