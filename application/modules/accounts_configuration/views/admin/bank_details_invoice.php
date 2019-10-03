<?php if (!empty($banks)): ?>
	<form action="" method="post" id="bankForInvoiceForm">
		<?php if (!$transaction): ?>			
			<label>Select default bank for invoice</label>
		<?php endif ?>
		<select id="bank_id" name="bank_id" class="form-control">
			<?php if (!$transaction): ?>				
				<option>Select your primary bank</option>
			<?php endif ?>
			<?php foreach ($banks as $key => $bank): ?>
				<option value="<?php echo $bank->id; ?>" <?php echo ($bank->default_bank) ? 'selected' : ''; ?>><?php echo $bank->bank_name; ?></option>
			<?php endforeach ?>
		</select>
	</form>	
<?php else: ?>
	<p>No bank details found</p>
<?php endif ?>