<!-- <link href="<?php echo base_url(); ?>assets/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->
<!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/sketch_custom/css/custom.css" type="text/css" /> -->
<link type="text/css" href="<?php echo base_url(); ?>assets/admin/sketch_custom/css/dompdf.css" id="dompdf_css" rel="stylesheet">

<?php
$total_data = count($order_details);
$rownumber = 16;
$ceil_data = ceil($total_data / $rownumber);
$j = 0;
?>
<!-- <div class="box-body pdf_class" id="printreport" data-pdf="Invoice">

	<table class="table table-print-wrapper">
		<thead>
			<tr>
				<th>
					<table class="table">
						<tr class="report-header" id="report-header">
							<td colspan="2" class="text-center">
								<h1><?php echo isset($voucher['title']) ? $voucher['title'] : '' ?></h1> 
								<h3><?php echo isset($voucher['sub_title']) ? $voucher['sub_title'] : '' ?></h3>
							</td>
						</tr>   
						<tr>
							<td style="width:50%; padding-left: 8px !important;">
								<?php echo (isset($voucher['id']) && ($voucher['id'] == 5 || $voucher['id'] == 7 || $voucher['id'] == 10 || $voucher['id'] == 12)) ? 'From' : 'To' ?><br>
								<div style="padding-left: 10px;">
									<span style="font-size:110%; text-transform: uppercase"><strong><?php echo isset($company_details->company_name) ? $company_details->company_name : '' ?></strong></span><br>
									<?php echo isset($company_details->street_address) ? $company_details->street_address : '' ?>                                            
									<?php echo isset($company_details->city_name) ? $company_details->city_name : '' ?>,<br>
									<?php echo isset($company_details->state_name) ? $company_details->state_name : '' ?>,
									<?php echo isset($company_details->country) ? $company_details->country : '' ?>,
									<?php echo isset($company_details->zip_code) ? $company_details->zip_code : '' ?>,<br>                                    
									<?php echo isset($company_details->email) ? 'Email:' . $company_details->email : '' ?>, <?php echo isset($company_details->telephone) ? 'Ph.:' . $company_details->telephone : '' ?>, <?php echo isset($company_details->mobile) ? 'Mob.:' . $company_details->mobile : '' ?><br>
									State Code : <?php echo isset($company_details->state_code) ? $company_details->state_code : '' ?>,  
									GST No : <?php echo isset($company_details->gst) ? $company_details->gst : '' ?>, 
									PAN No: <?php echo isset($company_details->pan) ? $company_details->pan : '' ?>
								</div>
								<hr>                                    
								<?php echo (isset($voucher['id']) && ($voucher['id'] == 6 || $voucher['id'] == 8 || $voucher['id'] == 9 || $voucher['id'] == 14)) ? 'From' : 'To' ?><br>
								<div style="padding-left: 10px;">
                                            
                                            <div class="col-md-6">
                                            	<i>Billing Address</i><br>
                                            	<span style="font-size:110%; text-transform: uppercase"><strong><?php echo isset($order->billing_first_name) ? $order->billing_first_name."<br>" : ''; ?></strong></span>
                                            	<?php echo ($order->billing_address) ? $order->billing_address : '' ?>
                                            	<?php echo ($order->billing_city) ? $order->billing_city . ',<br>' : ''; ?>
                                            	<?php echo ($order->billing_state) ? $order->billing_state_name . ',' : ''; ?>
                                            	<?php echo ($order->billing_country) ? $order->billing_country_name . ',' : ''; ?>
                                            	<?php echo ($order->billing_zip) ? $order->billing_zip . '<br>' : ''; ?>
                                            	<?php echo ($order->billing_email) ? 'Email:' . $order->billing_email . ', ' : ''; ?><?php echo ($order->billing_phone) ? 'Ph.:' . $order->billing_phone."<br>" : ''; ?>
                                            	<?php if (!empty($ledger_contact_details)): ?>
                                            		State Code  : <?php echo ($ledger_contact_details->state_code) ? $ledger_contact_details->state_code . '<br>' : '' ?>
                                            		GST No : <?php echo ($ledger_contact_details->gstn_no) ? $ledger_contact_details->gstn_no . '<br>' : '' ?>
                                            		PAN No: <?php echo ($ledger_contact_details->pan_it_no) ? $ledger_contact_details->pan_it_no : '' ?>
                                            	<?php endif ?>

                                            </div>
                                            <div class="col-md-6">
                                            	<i>Shipping Address</i><br>
                                            	<span style="font-size:110%; text-transform: uppercase"><strong><?php echo ($order->shipping_first_name) ? $order->shipping_first_name. "<br>" : ''; ?></strong></span>
                                            	<?php echo ($order->shipping_address) ? $order->shipping_address : '' ?>
                                            	<?php echo ($order->shipping_city) ? $order->shipping_city . ',<br>' : ''; ?>
                                            	<?php echo ($order->shipping_state) ? $order->shipping_state_name . ',' : ''; ?>
                                            	<?php echo ($order->shipping_country) ? $order->shipping_country_name . ',' : ''; ?>
                                            	<?php echo ($order->shipping_zip) ? $order->shipping_zip . '<br>' : ''; ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:0 !important; border-left:1px solid #ddd">
                                    	<table class="table table-invoive-date-no">
                                    		<tr>
                                    			<td style="width:40%">
                                    				<strong><?php echo isset($voucher['type']) ? $voucher['type'] : '' ?> Invoice No</strong><br>
                                    				<?php echo $entry->entry_no; ?>&nbsp;
                                    			</td>
                                    			<td>
                                    				<strong>Dated</strong><br>
                                    				<?php echo isset($entry->create_date) ? date('d/m/Y', strtotime($entry->create_date)) : '' ?>&nbsp;
                                    			</td>
                                    		</tr>
                                    		<tr>                                        
                                    			<td>
                                    				<strong>Buyer's Order No.</strong><br>
                                    				<?php echo isset($entry->voucher_no) ? $entry->voucher_no : ''; ?>&nbsp;
                                    			</td>
                                    			<td>
                                    				<strong>Dated</strong><br>
                                    				<?php echo isset($entry->voucher_date) ? date('d/m/Y', strtotime($entry->voucher_date)) : '' ?>&nbsp;
                                    			</td>
                                    		</tr>

                                    		<tr>                                        
                                    			<td>
                                    				<strong>Delivery Doc. No.</strong><br>
                                    				&nbsp;
                                    			</td>
                                    			<td>
                                    				<strong>Dated</strong><br>
                                    				&nbsp;
                                    			</td>
                                    		</tr>

                                    		<tr class="hidden">
                                    			<td>
                                    				<strong><?php echo (isset($voucher['id']) && $voucher['id'] == 5) ? 'Credit Note' : ((isset($voucher['id']) && $voucher['id'] == 6) ? 'Debit Note' : '') ?></strong><br>
                                    				&nbsp;
                                    			</td>
                                    			<td>
                                    				<strong>Mode/Terms of Payment</strong><br>
                                    				&nbsp;
                                    			</td>
                                    		</tr>
                                    		<tr>                                        
                                    			<td>
                                    				<strong>Mode/Terms of Payment</strong><br>
                                    				&nbsp;
                                    			</td>
                                    			<td>
                                    				<strong>Mode/Terms of Delivery</strong><br>
                                    				&nbsp;
                                    			</td>
                                    		</tr>

                                    		<tr>                                        
                                    			<td>
                                    				<strong>Despatched through</strong><br>
                                    				&nbsp;
                                    			</td>
                                    			<td>
                                    				<strong> Destination</strong><br>
                                    				&nbsp;
                                    			</td>
                                    		</tr>                                            
                                    	</table>   
                                    </td>                                
                                </tr> 
                            </table>                            
                        </th>
                    </tr>
                </thead>

                <tbody>                        
                	<tr>
                		<td>
                			<table class="table table-gst-header">
                				<thead>
                					<tr>
                						<th class="inv-width-sl" rowspan="2">#</th>
                						<th rowspan="2">Item Details</th>                                     
                						<th class="inv-width-hsn" rowspan="2">HSN/SAC</th>
                						<th class="inv-width-qty"  rowspan="2">Qty.</th>
                						<th class="inv-width-rate" rowspan="2">Rate</th>                                        
                						<th class="inv-width-unit"rowspan="2">Unit</th>
                						<th class="inv-width-value"  rowspan="2">Value</th>
                						<th class="inv-width-disc"  rowspan="2">Disc.</th>

                						<?php
                						if (!$order->is_igst_included) {
                							?>
                							<th class="inv-width-tax" colspan="2">CGST</th>
                							<th class="inv-width-tax" colspan="2">SGST</th>
                							<?php } else { ?>
                							<th class="inv-width-tax" colspan="2">IGST</th>
                							<?php } ?>
                							<?php
                							if ($order->is_cess_included) {
                								?>
                								<th class="inv-width-tax" colspan="2">CESS</th>
                								<?php } ?>
                								<th class="inv-width-amount" rowspan="2">Amount</th>
                							</tr>
                							<tr>                                        
                								<?php
                								if (!$order->is_igst_included) {
                									?>
                									<th class="inv-width-tax-rate"> (%)</th>
                									<th class="inv-width-tax-value">Value</th>
                									<th class="inv-width-tax-rate"> (%)</th>
                									<th class="inv-width-tax-value">Value</th>
                									<?php } else { ?>
                									<th class="inv-width-tax-rate"> (%)</th>
                									<th class="inv-width-tax-value">Value</th>
                									<?php } ?>                  
                									<?php
                									if ($order->is_cess_included) {
                										?>
                										<th class="inv-width-tax-rate"> (%)</th>
                										<th class="inv-width-tax-value">Value</th>
                										<?php } ?>   
                									</tr>
                								</thead>


                								<tbody>

                									<?php
                									$total_qty = 0;
                									$total_tax_val = 0;
                									$total_product_val = 0;
                									$grand_product_val = 0;
                									$total_discount = 0;
                									$sgst_total = 0;
                									$cgst_total = 0;
                									$igst_total = 0;
                									$cess_total = 0;
                									$flat_discount = 0;

                									if (count($order_details) > 0):
                										$k = $j * $rownumber;
                										$kk = $k + $rownumber;
                										for ($k; $k < $kk; $k++) {
                											if ($k >= $total_data) {
                												?>
                												<tr class="row-height">
                													<td class="inv-width-sl"></td>
                													<td class="inv-width-item-dtls"></td>
                													<td class="inv-width-hsn" nowrap="nowrap"></td>
                													<td class="inv-width-qty"></td>                                        
                													<td class="inv-width-rate"></td>
                													<td class="inv-width-unit"></td>
                													<td class="inv-width-value"></td>
                													<td class="inv-width-disc"></td>
                													<?php
                													if (!$order->is_igst_included) {
                														?>

                														<td class="inv-width-tax-rate"></td>
                														<td class="inv-width-tax-value"></td>
                														<td class="inv-width-tax-rate"></td>
                														<td class="inv-width-tax-value"></td>
                														<?php } else { ?>
                														<td class="inv-width-tax-rate"></td>
                														<td class="inv-width-tax-value"></td>
                														<?php } ?>                  
                														<?php
                														if ($order->is_cess_included) {
                															?>
                															<td class="inv-width-tax-rate"></td>
                															<td class="inv-width-tax-value"></td>
                															<?php } ?>   

                															<td class="inv-width-amount"></td>
                														</tr>
                														<?php
                													} else {
                														$row = $order_details[$k];
                														$key = $k;
                														$discount = $row->base_price * $row->quantity - $row->price;
                														?>
                														<tr class="row-height">
                															<td class="inv-width-sl"><?php echo $key + 1; ?></td>
                															<td class="inv-width-item-dtls"><?php echo isset($row->name) ? $row->name : '' ?></td>
                															<td class="inv-width-hsn" nowrap="nowrap"><?php echo isset($row->hsn_number) ? $row->hsn_number : '' ?></td>
                															<td class="inv-width-qty"><?php echo isset($row->quantity) ? $this->price_format($row->quantity) : '1' ?></td>                                        
                															<td class="inv-width-rate"><?php echo isset($row->base_price) ? $this->price_format($row->base_price) : '0.00' ?></td>
                															<td class="inv-width-unit"><?php echo isset($row->unit_name) ? $row->unit_name : 'No' ?></td>
                															<td class="inv-width-value"><?php echo isset($row->base_price) ? $this->price_format($row->base_price * $row->quantity) : '0.00' ?></td>
                															<td class="inv-width-disc"><?php echo $discount; ?></td>
                															<?php
                															if (!$order->is_igst_included) {
                																?>

                																<td class="inv-width-tax-rate"><?php echo intval($row->cgst_tax_percent); ?></td>
                																<td class="inv-width-tax-value"><?php echo $this->price_format($row->cgst_tax); ?></td>
                																<td class="inv-width-tax-rate"><?php echo intval($row->sgst_tax_percent); ?></td>
                																<td class="inv-width-tax-value"><?php echo $this->price_format($row->sgst_tax); ?></td>
                																<?php } else { ?>
                																<td class="inv-width-tax-rate"><?php echo intval($row->igst_tax_percent); ?></td>
                																<td class="inv-width-tax-value"><?php echo $this->price_format($row->igst_tax); ?></td>
                																<?php } ?>                  
                																<?php
                																if ($order->is_cess_included) {
                																	?>
                																	<td class="inv-width-tax-rate"><?php echo intval($row->cess_tax_percent); ?></td>
                																	<td class="inv-width-tax-value"><?php echo $row->cess_tax; ?></td>
                																	<?php } ?>   
                																	<?php
                																	$sgst_total+=$row->sgst_tax;
                																	$cgst_total+=$row->cgst_tax;
                																	$igst_total+=$row->igst_tax;
                																	$cess_total+=$row->cess_tax;
                																	$product_val = $row->quantity * $row->base_price;

                																	$total_qty+=$row->quantity;

                																	$total_product_val+=$product_val;
                																	$grand_product_val+=$row->total;
                																	$total_discount += $discount;
                																	?>
                																	<td class="inv-width-amount"><?php echo $this->price_format($row->total) ?></td>
                																</tr>
                																<?php
                															}
                														}
                														$total_tax_val+=($sgst_total + $cgst_total + $igst_total + $cess_total);

                													endif;
                													?>
                												</tbody>
                											</table>       

                										</td>
                									</tr>                            
                								</tbody>

                								<tfoot>
                									<tr>
                										<th>
                											<?php if (($ceil_data - 1) == $j) { ?>                        
                											<table class="table table-gst-total">
                												<thead>
                													<tr>
                														<th>Total</th>                                
                														<th class="inv-width-qty" ><?php echo $this->price_format($total_qty); ?></th>
                														<th class="inv-width-rate">-</th>                                        
                														<th class="inv-width-unit" >-</th>
                														<th class="inv-width-value" ><?php echo $this->price_format($total_product_val); ?></th>
                														<th class="inv-width-disc" ><?php echo $this->price_format($total_discount); ?></th>                                
                														<?php
                														if (!$order->is_igst_included) {
                															?>                                
                															<th class="inv-width-tax-tot" ><?php echo $this->price_format($cgst_total); ?></th>                                
                															<th class="inv-width-tax-tot"><?php echo $this->price_format($sgst_total); ?></th>
                															<?php } else { ?>                                
                															<th class="inv-width-tax-tot"><?php echo $this->price_format($igst_total); ?></th>
                															<?php } ?>                                
                															<?php
                															if ($order->is_cess_included) {
                																?>                                
                																<th class="inv-width-tax-tot"><?php echo $this->price_format($cess_total); ?></th>
                																<?php } ?>                                
                																<th class="inv-width-amount"><?php echo $this->price_format($grand_product_val); ?></th>
                															</tr>                            
                														</thead>
                													</table>
                													<?php } ?> 
                													<table class="table">
                														<tbody>
                															<?php if (($ceil_data - 1) == $j) { 

                																foreach($entry_details AS $dis){
                																	if($dis->discount_type != 0){
                																		if($sales_or_purchase == 1){ ?>
                																		<tr class="bold tr-discount">
                																			<td><?php echo $dis->ladger_name; ?></td>                                        
                																			<td style="text-align: right;">
                																				<?php
                																				echo $this->price_format($dis->balance);
                																				if($dis->account == 'Dr'){
                																					$grand_product_val -= $dis->balance;
                																				}else{
                																					$grand_product_val += $dis->balance;
                																				}
                																				?>
                																			</td>
                																		</tr>
                																		<?php   }
                																		if($sales_or_purchase == 2){ ?>
                																		<tr class="bold tr-discount">
                																			<td><?php echo $dis->ladger_name; ?></td>                                        
                																			<td style="text-align: right;">
                																				<?php
                																				echo $this->price_format($dis->balance);
                																				if($dis->account == 'Cr'){
                																					$grand_product_val -= $dis->balance;
                																				}else{
                																					$grand_product_val += $dis->balance;
                																				}
                																				?>
                																			</td>
                																		</tr>
                																		<?php   }
                																	}
                																}

                																?>

                																<?php if($total_discount != 0){  ?>
                																<tr class="bold tr-discount">
                																	<td>Discount</td>                                        
                																	<td style="text-align: right;"><?php
                																	echo $this->price_format($total_discount);
                																	?></td>
                																</tr>
                																<?php } ?>

                																<tr class="tr-grand-total">
                																	<td>Grand Total</td>                                        
                																	<td style="text-align: right; width:230px; font-size: 16px;"><?php echo $this->price_format($grand_product_val) ?></td>
                																</tr>

                																<tr>
                																	<td colspan="2">Amount Chargeable (in words): 
                																		<?php $this->load->helper('numtoword'); ?>
                																		<strong>
                																			<?php
                																			$total_amount = ($grand_product_val);
                																			echo numberTowords($total_amount);
                																			?>
                																		</strong> </td>
                																	</tr>
                																	<?php } ?> 
                																	<tr class="invoice-narations">
                																		<td colspan="2"><strong>Narration :</strong><br>
                																			<?php echo isset($entry->narration) ? $entry->narration : '' ?></td>
                																		</tr>
                																		<tr class="invoice-terms">
                																			<td>
                																				<strong>Terms & Conditions:</strong><br>
                																				<span id="fitThisText"><?php echo isset($order->terms_and_conditions) ? $order->terms_and_conditions : '' ?>&nbsp;</span>
                																			</td>
                																			<td style="text-align: right;">
                																				For <strong><?php echo isset($company_details->company_name) ? $company_details->company_name : '' ?></strong>
                																				<br>
                																				<br>
                																				<br>
                																				Authorize Signatory
                																			</td>
                																		</tr>
                																	</tbody>
                																</table> 
                																<p style="margin: 10px 0 10px 10px"><u>Declaration:</u>&nbsp;<?php echo isset($voucher['declaration']) ? $voucher['declaration'] : '' ?></p>                    
                															</th>
                														</tr>

                													</tfoot>
                												</table>
                												<?php if (($ceil_data - 1) != $j) { ?>
                												<div style="width:100%; display:block; text-align: right; margin-top: 10px; font-size: 11px; font-style: italic">Continued...</div>
                												<?php } ?>
                											</div> -->

                 <section class="content">
        <div class="clearfix bill-wrapper">
            <?php
            $total_data = count($order_details);
            $rownumber = 26;
            //IF is not given titel : $rownumber + 1
            isset($voucher['title']) ? $rownumber : $rownumber++;
            //IF is not given sub_title : $rownumber + 1
            isset($voucher['sub_title']) ?$rownumber : $rownumber++;
           // No Discount
            $rownumber += 3;
            foreach ($entry_details AS $dis) {
                if ($dis->discount_type != 0) { 
                    $rownumber--;
                }
            }
            
            $ceil_data = ceil($total_data / $rownumber);
            for ($j = 0; $j < $ceil_data; $j++) {
                ?>
                <div class="box">   
                    <div class="box-body pdf_class" id="printreport" data-pdf="Invoice">

                        <table class="table table-invoice-main">
                            <thead>
                                <tr>
                                    <th>
                                <table class="table table-invoice-header">
                                <!-- Report Header Start -->    
                                <tr id="report-header">
                                    <td colspan="3" class="invoice-title text-center">
                                        <h1><?php echo isset($voucher['title']) ? $voucher['title'] : '' ?></h1> 
                                        <h3><?php echo isset($voucher['sub_title']) ? $voucher['sub_title'] : '' ?></h3>
                                    </td>
                                </tr>
                                <!-- Report Header End -->    

                                <!-- Billing Header Start -->    
                                <tr>
                                    <td colspan="2" style="border-left: 1px solid #000 !important;border-right: 1px solid #000 !important;">
                                        <div class="invoice-header-address">
    <?php echo (isset($voucher['id']) && ($voucher['id'] == 5 || $voucher['id'] == 7 || $voucher['id'] == 10 || $voucher['id'] == 12)) ? 'From' : 'To' ?><br>
                                        <div class="invoice-address-indent clearfix">
                                            <span style="font-size:110%; text-transform: uppercase"><strong><?php echo isset($company_details->company_name) ? $company_details->company_name : '' ?></strong></span><br>
                                                    <?php echo isset($company_details->street_address) ? $company_details->street_address : '' ?>                                            
                                            <?php echo isset($company_details->city_name) ? $company_details->city_name : '' ?>,<br>
                                            <?php echo isset($company_details->state_name) ? $company_details->state_name : '' ?>,
                                            <?php echo isset($company_details->country) ? $company_details->country : '' ?>,
                                            <?php echo isset($company_details->zip_code) ? $company_details->zip_code : '' ?>,<br>                                    
    <?php echo isset($company_details->email) ? 'Email:' . $company_details->email : '' ?>, <?php echo isset($company_details->telephone) ? 'Ph.:' . $company_details->telephone : '' ?>, <?php echo isset($company_details->mobile) ? 'Mob.:' . $company_details->mobile : '' ?><br>
                                            State Code : <?php echo isset($company_details->state_code) ? $company_details->state_code : '' ?>,  
                                            GST No : <?php echo isset($company_details->gst) ? $company_details->gst : '' ?>, 
                                            PAN No: <?php echo isset($company_details->pan) ? $company_details->pan : '' ?>
                                        </div>
    </div>
                                    </td>
                                    <td rowspan="2" class="table-invoice-header-bill-dtls" style="border-right: 1px solid #000 !important;border-left: 1px solid #000 !important;">
                                        <table class="table">
                                            <tr>
                                                <td>
                                                    <strong><?php echo isset($voucher['type']) ? $voucher['type'] : '' ?> Invoice No</strong><br><?php echo $entry->entry_no; ?>&nbsp;
                                                </td>
                                                <td>
                                                    <strong>Dated</strong><br><?php echo isset($entry->create_date) ? date('d/m/Y', strtotime($entry->create_date)) : '' ?>&nbsp;
                                                </td>
                                            </tr>
                                            <tr>                                        
                                                <td>
                                                    <strong>Buyer's Order No.</strong><br><?php echo isset($entry->voucher_no) ? $entry->voucher_no : ''; ?>&nbsp;
                                                </td>
                                                <td>
                                                    <strong>Dated</strong><br><?php echo ($entry->voucher_date && $entry->voucher_date != "1970-01-01") ? date('d/m/Y', strtotime($entry->voucher_date)) : '' ?>&nbsp;
                                                </td>
                                            </tr>
                                            <tr>                                        
                                                <td>
                                                    <strong>Delivery Doc. No.</strong><br>&nbsp;
                                                </td>
                                                <td>
                                                    <strong>Dated</strong><br>&nbsp;
                                                </td>
                                            </tr>

                                            <tr class="hidden">
                                                <td>
                                                    <strong><?php echo (isset($voucher['id']) && $voucher['id'] == 5) ? 'Credit Note' : ((isset($voucher['id']) && $voucher['id'] == 6) ? 'Debit Note' : '') ?></strong><br>
                                                    &nbsp;
                                                </td>
                                                <td>
                                                    <strong>Mode/Terms of Payment</strong><br>
                                                    &nbsp;
                                                </td>
                                            </tr>                                            
                                            <tr>                                        
                                                <td>
                                                    <strong>Mode/Terms of Payment</strong><br>&nbsp;
                                                </td>
                                                <td>
                                                    <strong>Mode/Terms of Delivery</strong><br>&nbsp;
                                                </td>
                                            </tr>
                                            <tr>                                        
                                                <td>
                                                    <strong>Despatched through</strong><br>&nbsp;
                                                </td>
                                                <td>
                                                    <strong> Destination</strong><br>&nbsp;
                                                </td>
                                            </tr>                                            
                                        </table>
                                    </td>
                                </tr>
                                <!-- row 2-->
                                <tr>
                                    <td style="border-left: 1px solid #000 !important;border-right: 1px solid #000 !important;">
                                        <div class="invoice-header-address">
                                        <?php echo (isset($voucher['id']) && ($voucher['id'] == 6 || $voucher['id'] == 8 || $voucher['id'] == 9 || $voucher['id'] == 14)) ? 'From' : 'To' ?><br>
                                        <div class="invoice-address-indent clearfix">
                                            <i>Billing Address</i><br>
                                            <span style="font-size:110%; text-transform: uppercase"><strong><?php echo isset($order->billing_first_name) ? $order->billing_first_name . "<br>" : ''; ?></strong></span>
                                            <?php echo ($order->billing_address) ? $order->billing_address : '' ?>
                                            <?php echo ($order->billing_city) ? $order->billing_city . ',<br>' : ''; ?>
                                            <?php echo ($order->billing_state) ? $order->billing_state_name . ',' : ''; ?>
                                            <?php echo ($order->billing_country) ? $order->billing_country_name . ',' : ''; ?>
                                            <?php echo ($order->billing_zip) ? $order->billing_zip . '<br>' : ''; ?>
                                            <?php echo ($order->billing_email) ? 'Email:' . $order->billing_email . ', ' : ''; ?><?php echo ($order->billing_phone) ? 'Ph.:' . $order->billing_phone . "<br>" : ''; ?>
                                            <?php if (!empty($ledger_contact_details)): ?>
                                                State Code  : <?php echo ($ledger_contact_details->state_code) ? $ledger_contact_details->state_code . '<br>' : '' ?>
                                                GST No : <?php echo ($ledger_contact_details->gstn_no) ? $ledger_contact_details->gstn_no . '<br>' : '' ?>
                                                PAN No: <?php echo ($ledger_contact_details->pan_it_no) ? $ledger_contact_details->pan_it_no : '' ?>
                                           <?php endif ?>                         
                                        </div>
                                        </div>
                                    </td>
                                    <td style="border-right: 1px solid #000 !important;">
                                        <div class="invoice-header-address">
                                        <?php echo (isset($voucher['id']) && ($voucher['id'] == 6 || $voucher['id'] == 8 || $voucher['id'] == 9 || $voucher['id'] == 14)) ? 'From' : 'To' ?>
                                        <div class="invoice-address-indent clearfix">
                                                <i>Shipping Address</i><br>
                                                <span style="font-size:110%; text-transform: uppercase"><strong><?php echo ($order->shipping_first_name) ? $order->shipping_first_name . "<br>" : ''; ?></strong></span>
                                                <?php echo ($order->shipping_address) ? $order->shipping_address : '' ?>
                                                <?php echo ($order->shipping_city) ? $order->shipping_city . ',<br>' : ''; ?>
                                                <?php echo ($order->shipping_state) ? $order->shipping_state_name . ',' : ''; ?>
                                                <?php echo ($order->shipping_country) ? $order->shipping_country_name . ',' : ''; ?>
                                                <?php echo ($order->shipping_zip) ? $order->shipping_zip . '<br>' : ''; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>                                
                                <!-- /Billing Header End -->    
                            </table>                            
                            </th>
                            </tr>
                            </thead>

                            <tbody>                        
                                <tr>
                                    <td>
                                        <table class="table table-invoice-body">
                                            <thead>
                                                <!-- billing title row -->
                                                <tr>
                                                    <th class="inv-slno" rowspan="2">#</th>
                                                    <th class="inv-itemdtls" rowspan="2">Item Details</th>                                     
                                                    <th class="inv-hsn" rowspan="2">HSN/SAC</th>
                                                    <th class="inv-qty text-right"  rowspan="2">Qty.</th>
                                                    <th class="inv-rate text-right" rowspan="2">Rate</th>                                        
                                                    <th class="inv-unit" rowspan="2">Unit</th>
                                                    <th class="inv-value text-right"  rowspan="2">Value</th>
                                                    <th class="inv-disc text-right"  rowspan="2">Disc.</th>

                                                    <!-- if cgst & sgst -->  
                                                    <?php
                                                    if (!$order->is_igst_included) {
                                                        ?>
                                                        <th class="inv-tax text-center" colspan="2">CGST</th>
                                                        <th class="inv-tax text-center" colspan="2">SGST</th>
                                                            <?php } else { ?>
                                                        <!-- else -->
                                                        <th class="inv-tax text-center" colspan="2">IGST</th>
                                                            <?php } ?>
                                                    <!-- end  if -->
                                                    <!--  if CESS-->
                                                    <?php
                                                    if ($order->is_cess_included) {
                                                        ?>
                                                        <th class="inv-tax text-center" colspan="2">CESS</th>
                                                            <?php } ?>
                                                    <!-- end  if -->
                                                    <th class="inv-amount text-right border-left" rowspan="2">Amount</th>
                                                </tr>
                                                <tr>                                        
                                                    <!-- if cgst & sgst -->  
                                                    <?php
                                                    if (!$order->is_igst_included) {
                                                        ?>
                                                        <th class="inv-tax-rate text-right"> (%)</th>
                                                        <th class="inv-tax-value text-right">Value</th>
                                                        <th class="inv-tax-rate text-right"> (%)</th>
                                                        <th class="inv-tax-value text-right">Value</th>
                                                            <?php } else { ?>
                                                        <!-- else-->
                                                        <th class="inv-tax-rate text-right"> (%)</th>
                                                        <th class="inv-tax-value text-right">Value</th>
                                                            <?php } ?>                  
                                                    <!-- end  if -->
                                                    <!--  if CESS-->
                                                    <?php
                                                    if ($order->is_cess_included) {
                                                        ?>
                                                        <th class="inv-tax-rate text-right"> (%)</th>
                                                        <th class="inv-tax-value text-right">Value</th>
                                                            <?php } ?>   
                                                    <!-- if endif -->                                        
                                                </tr>
                                                <!-- /Billing title row -->
                                            </thead>


                                            <!-- billing Body Start-->    
                                            <tbody>

                                                <?php
                                                $total_qty = 0;
                                                $total_tax_val = 0;
                                                $total_product_val = 0;
                                                $grand_product_val = 0;
                                                $total_discount = 0;
                                                $sgst_total = 0;
                                                $cgst_total = 0;
                                                $igst_total = 0;
                                                $cess_total = 0;
                                                $flat_discount = 0;

                                                if (count($order_details) > 0):
                                                    // foreach ($order_details as $key => $row) {
                                                    $k = $j * $rownumber;
                                                    $kk = $k + $rownumber;
                                                    for ($k; $k < $kk; $k++) {
                                                        if ($k >= $total_data) {
                                                            ?>
                                                            <tr class="row-height">
                                                                <td class="inv-slno">&nbsp;</td>
                                                                <td class="inv-itemdtls"><?php //  echo $k; ?></td>
                                                                <td class="inv-hsn"></td>
                                                                <td class="inv-qty"></td>
                                                                <td class="inv-rate"></td>
                                                                <td class="inv-unit"></td>
                                                                <td class="inv-value"></td>
                                                                <td class="inv-disc"></td>
                                                                <!-- if cgst & sgst -->   
                                                                <?php
                                                                if (!$order->is_igst_included) {
                                                                    ?>
                                                                    <td class="inv-tax-rate"></td>
                                                                    <td class="inv-tax-value"></td>
                                                                    <td class="inv-tax-rate"></td>
                                                                    <td class="inv-tax-value"></td>
                                                                    <!--else-->
                                                                        <?php } else { ?>
                                                                    <td class="inv-tax-rate"></td>
                                                                    <td class="inv-tax-value"></td>
                                                                        <?php } ?>                  
                                                                <!-- end  if -->
                                                                <!--  if CESS-->  
                                                                <?php
                                                                if ($order->is_cess_included) {
                                                                    ?>
                                                                    <td class="inv-tax-rate"></td>
                                                                    <td class="inv-tax-value"></td>
                                                                        <?php } ?>   
                                                                <!-- endif -->
                                                                <td class="inv-amount"></td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            $row = $order_details[$k];
                                                            $key = $k;
                                                            $discount = $row->base_price * $row->quantity - $row->price;
                                                            ?>
                                                            <tr class="row-height">
                                                                <td class="inv-slno"><?php echo $key + 1; ?></td>
                                                                <td class="inv-itemdtls"><?php echo isset($row->name) ? $row->name : '' ?></td>
                                                                <td class="inv-hsn"><?php echo isset($row->hsn_number) ? $row->hsn_number : '' ?></td>
                                                                <td class="text-right inv-qty"><?php echo isset($row->quantity) ? $this->price_format($row->quantity) : '1' ?></td>                                        
                                                                <td class="text-right inv-rate"><?php echo isset($row->base_price) ? $this->price_format($row->base_price) : '0.00' ?></td>
                                                                <td class="inv-unit"><?php echo isset($row->unit_name) ? $row->unit_name : 'No' ?></td>
                                                                <td class="text-right inv-value"><?php echo isset($row->base_price) ? $this->price_format($row->base_price * $row->quantity) : '0.00' ?></td>
                                                                <td class="text-right inv-disc"><?php echo $discount; ?></td>
                                                                <!-- if cgst & sgst -->   
                                                                <?php
                                                                if (!$order->is_igst_included) {
                                                                    ?>
                                                                    <td class="text-right inv-tax-rate"><?php echo intval($row->cgst_tax_percent); ?></td>
                                                                    <td class="text-right inv-tax-value"><?php echo $this->price_format($row->cgst_tax); ?></td>
                                                                    <td class="text-right inv-tax-rate"><?php echo intval($row->sgst_tax_percent); ?></td>
                                                                    <td class="text-right inv-tax-value"><?php echo $this->price_format($row->sgst_tax); ?></td>
                                                                    <!--else-->
                                                                        <?php } else { ?>
                                                                    <td class="text-right inv-tax-rate"><?php echo intval($row->igst_tax_percent); ?></td>
                                                                    <td class="text-right inv-tax-value"><?php echo $this->price_format($row->igst_tax); ?></td>
                                                                        <?php } ?>                  
                                                                <!-- end  if -->
                                                                <!--  if CESS-->  
                                                                <?php
                                                                if ($order->is_cess_included) {
                                                                    ?>
                                                                    <td class="text-right inv-tax-rate"><?php echo intval($row->cess_tax_percent); ?></td>
                                                                    <td class="text-right inv-tax-value"><?php echo $row->cess_tax; ?></td>
                                                                <?php } ?>   
                                                                <!-- endif -->
                                                                <?php
                                                                $sgst_total+=$row->sgst_tax;
                                                                $cgst_total+=$row->cgst_tax;
                                                                $igst_total+=$row->igst_tax;
                                                                $cess_total+=$row->cess_tax;
                                                                $product_val = $row->quantity * $row->base_price;

                                                                $total_qty+=$row->quantity;

                                                                $total_product_val+=$product_val;
                                                                $grand_product_val+=$row->total;
                                                                $total_discount += $discount;
                                                                ?>
                                                                <td class="text-right inv-amount"><?php echo $this->price_format($row->total) ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    $total_tax_val+=($sgst_total + $cgst_total + $igst_total + $cess_total);

                                                endif;
                                                ?>
                                            </tbody>
                                            <!-- /billing Body End-->    
                                        </table>       

                                    </td>
                                </tr>                            
                            </tbody>

                            <tfoot>
                                <!-- Page Footer Start-->                            
                                <tr>
                                    <th>
                                        <?php if (($ceil_data - 1) == $j) { ?>                        
                                <table class="table table-invoice-total">
                                    <thead>
                                        <tr>
                                            <th>Total</th>                                
                                            <th class="inv-qty text-right"><?php echo $this->price_format($total_qty); ?></th>
                                            <th class="inv-rate text-center">-</th>                                        
                                            <th class="inv-unit text-center">-</th>
                                            <th class="inv-value text-right"><?php echo $this->price_format($total_product_val); ?></th>
                                            <th class="inv-disc text-right"><?php echo $this->price_format($total_discount); ?></th>                                
                                            <?php
                                            if (!$order->is_igst_included) {
                                                ?>                                
                                                <th class="inv-tax-tot text-right" ><?php echo $this->price_format($cgst_total); ?></th>                                
                                                <th class="inv-tax-tot text-right"><?php echo $this->price_format($sgst_total); ?></th>
                                            <?php } else { ?>                                
                                                <th class="inv-tax-tot text-right"><?php echo $this->price_format($igst_total); ?></th>
                                            <?php } ?>                                
                                            <?php
                                            if ($order->is_cess_included) {
                                                ?>                                
                                            <th class="inv-tax-tot text-right"><?php echo $this->price_format($cess_total); ?></th>
                                                <?php } ?>                                
                                            <th class="inv-amount text-right"><?php echo $this->price_format($grand_product_val); ?></th>
                                        </tr>                            
                                    </thead>
                                </table>
                                    <?php } ?> 
                            
                            
                            <table class="table table-invoice-footer">
                                <tbody>
                                    <?php
                                    if (($ceil_data - 1) == $j) {

                                        //For flat discount
                                        foreach ($entry_details AS $dis) {
                                            if ($dis->discount_type != 0) {
                                                if ($sales_or_purchase == 1) {
                                                    ?>
                                                    <tr class="row-discount">
                                                        <td><?php echo $dis->ladger_name; ?></td>                                        
                                                        <td class="text-right">
                                                            <?php
                                                            echo $this->price_format($dis->balance);
                                                            if ($dis->account == 'Dr') {
                                                                $grand_product_val -= $dis->balance;
                                                            } else {
                                                                $grand_product_val += $dis->balance;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php }
                                                    if ($sales_or_purchase == 2) {
                                                        ?>
                                                    <tr class="row-discount">
                                                        <td><?php echo $dis->ladger_name; ?></td>                                        
                                                        <td class="text-right">
                                                            <?php
                                                            echo $this->price_format($dis->balance);
                                                            if ($dis->account == 'Cr') {
                                                                $grand_product_val -= $dis->balance;
                                                            } else {
                                                                $grand_product_val += $dis->balance;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            }
                                        }
                                        ?>

                                        <?php if ($total_discount != 0) { ?>
                                            <tr class="row-discount">
                                                <td>Discount</td>                                        
                                                <td class="text-right"><?php
                                        echo $this->price_format($total_discount);
                                        ?></td>
                                            </tr>
                                                <?php } ?>
                                        <tr class="row-grand-total">
                                            <td>Grand Total</td>                                        
                                            <td><?php echo $this->price_format($grand_product_val) ?></td>
                                        </tr>

                                        <tr>
                                            <td colspan="2">Amount Chargeable (in words): 
                                                    <?php $this->load->helper('numtoword'); ?>
                                                <strong>
                                                <?php
                                                $total_amount = ($grand_product_val);
                                                echo ucwords(numberTowords($total_amount)). ' Only';
                                                ?>
                                                </strong> </td>
                                        </tr>
                                            <?php } ?> 
                                    <tr class="row-naration">
                                        <td colspan="2"><strong>Narration :</strong><br>
                                        <?php echo isset($entry->narration) ? $entry->narration : '' ?></td>
                                    </tr>
                                    <tr class="row-terms">
                                        <td>
                                            <strong>Terms & Conditions:</strong><br>
                                            <span id="fitThisTextX"><?php echo isset($order->terms_and_conditions) ? $order->terms_and_conditions : '' ?>&nbsp;</span>
                                        </td>
                                        <td class="text-right">
                                            For <strong><?php echo isset($company_details->company_name) ? $company_details->company_name : '' ?></strong>
                                            <br>
                                            <br>
                                            <br>
                                            Authorize Signatory
                                        </td>
                                    </tr>
                                </tbody>
                            </table> 
                            <p class="invoice-declaration"><u>Declaration:</u>&nbsp;<?php echo isset($voucher['declaration']) ? $voucher['declaration'] : '' ?></p>
                            <!-- /Page Footer--> 
                            </th>
                            </tr>
                            <!-- /Page Footer End-->    

                            </tfoot>
                        </table>
                            <?php if (($ceil_data - 1) != $j) { ?>
                            <div class="invoice-continued">Continued...</div>
                            <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section><!-- /.content -->
                                                            