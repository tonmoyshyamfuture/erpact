<form action="" method="" id="userDetailsForEwayForm">
	<div class="form-group">		
		<input type="text" name="eway_username" id="eway_username" class="form-control" value="<?php echo $account_settings->eway_username; ?>" placeholder="Enter Username">
	</div>
	<div class="form-group">
		<input type="password" name="eway_password" id="eway_password" class="form-control" value="" placeholder="Enter Password">
	</div>
	<p class="text-dange" id="eway_user_error"></p>
</form>