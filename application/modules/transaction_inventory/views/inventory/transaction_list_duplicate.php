<style>
.ui-widget-header {
	border: none;; 
	background: #fff url(images/ui-bg_gloss-wave_55_5c9ccc_500x100.png) 50% 50% repeat-x;
}
.ui-widget-content {
	border: none;
}
.ui-state-active a:link, .ui-state-active a:visited {
	color: #fff;
	background-color: #3c8dbc;
}
.transaction-footer{
	background: rgba(242,232,222,0.5);
	border-top: 1px solid #ddd;
	display: block;
	box-sizing: border-box;
	padding: 25px;
	position: absolute;
	margin-top: -58px;
	z-index: 1;
	width: 100%;
	height: 60px;
}
.transaction-footer input{
	margin-top: -15px;
}
.table{position: relative;}

.dataTables_wrapper .row:last-child {
	margin: 0 auto;    padding: 10px 10px 10px 10px;    background: #F8F3EE;    height: 60px; border-top: 1px solid #ddd;
}
div.dataTables_paginate{width:100%; float: right;}
div.dataTables_paginate ul.pagination{float: right;}

span.postdated-status{
	font-size: 18px;
	-webkit-animation: color-change 1s infinite;
	-moz-animation: color-change 1s infinite;
	-o-animation: color-change 1s infinite;
	-ms-animation: color-change 1s infinite;
	animation: color-change 1s infinite;
}

@-webkit-keyframes color-change {
	0% { color: red; }
	50% { color: blue; }
	100% { color: red; }
}
@-moz-keyframes color-change {
	0% { color: red; }
	50% { color: blue; }
	100% { color: red; }
}
@-ms-keyframes color-change {
	0% { color: red; }
	50% { color: blue; }
	100% { color: red; }
}
@-o-keyframes color-change {
	0% { color: red; }
	50% { color: blue; }
	100% { color: red; }
}
@keyframes color-change {
	0% { color: red; }
	50% { color: blue; }
	100% { color: red; }
}
.box{margin-bottom: 0}
</style>
<div class="wrapper2">  

	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

	<section class="content-header">
		<div class="row">
			<div class="col-xs-6">
				<h1><div class="dropdown"><i class="fa fa-list dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;"></i>&nbsp;<?php
				if (isset($voucher_type)) {
					echo $voucher_type['type'];
				}
				?>  <ul class="dropdown-menu"><?php getTransactionListMenu(); ?></ul></div></h1>  
			</div>
			<div class="col-xs-6">
				<div class="pull-right">  
					<?php if (isset($sub_vouchers) && !empty($sub_vouchers)) { ?>
					<div class="dropdown" style="float: left; margin-right: 8px;">
						<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Sub Voucher
							<span class="caret"></span></button>
							<ul class="dropdown-menu">
								<?php foreach ($sub_vouchers as $sub_voucher) { ?> 
								<li><a href="<?php echo site_url() . "admin/inventory-transaction-list/" . str_replace(' ', '-', strtolower(trim($sub_voucher['type']))) . '/' . $sub_voucher['id'] . '/all'; ?>"><?php echo $sub_voucher['type']; ?> (<?php echo $sub_voucher['entry_code']; ?>)</a></li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>
						<?php
						if ($entry_type_id == 5 || $parent_id == 5) {
							$module = 194;
						} elseif ($entry_type_id == 6 || $parent_id == 6) {
							$module = 195;
						} elseif ($entry_type_id == 7 || $parent_id == 7) {
							$module = 196;
						} elseif ($entry_type_id == 8 || $parent_id == 8) {
							$module = 197;
						} elseif ($entry_type_id == 10 || $parent_id == 10) {
							$module = 199;
						} elseif ($entry_type_id == 9 || $parent_id == 9) {
							$module = 200;
						} elseif ($entry_type_id == 14 || $parent_id == 14) {
							$module = 202;
						} elseif ($entry_type_id == 12 || $parent_id == 12) {
							$module = 203;
						}
						$permission = ua($module, 'add');
						if ($permission):
							?>
							<?php if ($entry_type_id == 5 || $parent_id == 5): ?>
								<input type="button" class="btn btn-primary" value="New Entry" onclick="window.location.href = '<?php echo site_url('transaction/sales-add') . "/n/" . $entry_type_id . "/a"; ?>'" />
							<?php elseif ($entry_type_id == 6 || $parent_id == 6): ?>
								<input type="button" class="btn btn-primary" value="New Entry" onclick="window.location.href = '<?php echo site_url('transaction/purchase-add') . "/n/" . $entry_type_id . "/a"; ?>'" />
							<?php elseif ($entry_type_id == 7 || $parent_id == 7): ?>
								<input type="button" class="btn btn-primary" value="New Entry" onclick="window.location.href = '<?php echo site_url('transaction/sales-order-add') . "/n/" . $entry_type_id . "/a"; ?>'" />
							<?php elseif ($entry_type_id == 8 || $parent_id == 8): ?>
								<input type="button" class="btn btn-primary" value="New Entry" onclick="window.location.href = '<?php echo site_url('transaction/purchase-order-add') . "/n/" . $entry_type_id . "/a"; ?>'" />
							<?php elseif ($entry_type_id == 9 || $parent_id == 9): ?>
								<input type="button" class="btn btn-primary" value="New Entry" onclick="window.location.href = '<?php echo site_url('transaction/receive-note-add') . "/n/" . $entry_type_id . "/a"; ?>'" />
							<?php elseif ($entry_type_id == 10 || $parent_id == 10): ?>
								<input type="button" class="btn btn-primary" value="New Entry" onclick="window.location.href = '<?php echo site_url('transaction/delivery-note-add') . "/n/" . $entry_type_id . "/a"; ?>'" />
							<?php elseif ($entry_type_id == 14 || $parent_id == 14): ?>
								<input type="button" class="btn btn-primary" value="New Entry" onclick="window.location.href = '<?php echo site_url('transaction/credit-note-add') . "/n/" . $entry_type_id . "/a"; ?>'" />
							<?php elseif ($entry_type_id == 12 || $parent_id == 12): ?>
								<input type="button" class="btn btn-primary" value="New Entry" onclick="window.location.href = '<?php echo site_url('transaction/debit-note-add') . "/n/" . $entry_type_id . "/a"; ?>'" />
							<?php endif; ?>   
						<?php endif; ?>    
					</div>
				</div> 


			</div> 
		</section>
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-sm">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Sub Voucher List </h4>
					</div>
					<div class="modal-body" style="padding:0 0 15px 0">
						<table class="table table-bordered table-striped transaction-list">                                    
							<tbody>
								<?php foreach ($sub_vouchers as $sub_voucher) { ?> 
								<tr>
									<td><a href="<?php echo site_url() . "admin/inventory-transaction-list/" . str_replace(' ', '-', strtolower(trim($sub_voucher['type']))) . '/' . $sub_voucher['id'] . '/all'; ?>"><?php echo $sub_voucher['type']; ?></a></td>
									<td><?php echo $sub_voucher['entry_code']; ?></td>

								</tr>
								<?php } ?>

							</tbody>

						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>

			</div>
		</div>
		<!-- Modal -->
		<section>
			<?php
			$arr = isset($_SERVER['HTTP_REFERER']) ? explode('/', $_SERVER['HTTP_REFERER']) : '';
			$referer = isset($arr[5]) ? $arr[5] : '';
			if ($referer == 'monthly-statistics-report.aspx') {
				$prev_breadcrumbs = $this->session->userdata('_breadcrumbs');
			} else {
				$prev_breadcrumbs = array(
					'Home' => '/admin/dashboard',
					'Transaction' => '#',
				);
			}
			if ($month == 'all') {
				$current_breadcrumbs = array($voucher_name => 'admin/inventory-transaction-list/' . $voucher_name . '/' . $entry_type_id . '/' . $month);
			} else {
				$_m = date("F", strtotime($month));
				$current_breadcrumbs = array($_m => 'admin/inventory-transaction-list/' . $voucher_name . '/' . $entry_type_id . '/' . $month);
			}

			$breadcrumbs = array_merge($prev_breadcrumbs, $current_breadcrumbs);
			$this->session->set_userdata('_breadcrumbs', $breadcrumbs);
			foreach ($breadcrumbs as $k => $b) {
				$this->breadcrumbs->push($k, $b);
				if ($k == $voucher_name) {
					break;
				}
			}
			$this->breadcrumbs->show();
			?>
		</section>
		<!-- Main content --> 
		<section class="content">
			<div class="row">
				<div class="col-xs-12">
					<div class="box">                    
						<div id="tabx" class="nav-tabs">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#Predated" data-toggle="tab" aria-expanded="false">Current</a></li>
								<li class=""><a href="#Postdated" data-toggle="tab" aria-expanded="false">Postdated</a></li>
								<li class=""><a href="#Recurring" data-toggle="tab" aria-expanded="true">Recurring</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="Predated"> 

									<div class="box">
										<div class="box-body table-fullwidth">
											<div id="scrollTable" class="table-responsive" style="height: 100px;overflow-y: auto;">
												<table class="table table-striped fcol-100 lcol-70  transaction-list">
													<thead>
														<tr>
															<th>Date</th>
															<th>Number</th>                        
															<th>Ledger</th>
															<th>Type</th>
															<?php
															if ($entry_type_id == 7 || $entry_type_id == 8 || $parent_id == 7 || $parent_id == 8):
																?>
																<th>Status</th>
															<?php endif; ?>
															<th class="text-right">Amount</th>
															<th></th>
														</tr>
													</thead>
													<tbody id="results">
														</tbody> 


													</table>
													<div id="loader_image"><img src="<?php echo base_url('assets/loading.gif') ?>"></div>
													<div id="loader_message"></div>
												</div>
											</div><!-- /.box-body -->     
										</div>
									</div>
									<div class="tab-pane" id="Postdated">                            
										<div class="box">  
											<div class="box-body table-fullwidth">
												<div class="table-responsive">
													<table id="example01" class="table table-striped fcol-100 lcol-70  transaction-list">
														<thead>
															<tr>
																<th>Date</th>
																<th>Number</th>                        
																<th>Ledger</th>
																<th>Type</th>
																<th class="text-right">Amount</th>
																<th></th>
																<th></th>
															</tr>
														</thead>
														<tbody>
															<?php if (isset($all_post_dated_entries)): ?>
																<?php foreach ($all_post_dated_entries as $entry) { ?> 
																<tr id="post_row_<?php echo $entry['id']; ?>">
																	<td><?php echo date('d-m-Y', strtotime($entry['create_date'])); ?></td>
																	<td><?php echo $entry['entry_no']; ?></td>
																	<td>
																		<a href="#">
																			<?php
																			$led = array();
																			$devit = json_decode($entry['ledger_ids_by_accounts']);

																			echo "<strong>Dr </strong>";
																			for ($i = 0; $i < count($devit->Dr); $i++) {
																				echo $devit->Dr[$i];
																				if (count($devit->Dr) > 1) {
																					echo ' + ';
																				}
																				break;
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
																	<!--<td><?php //echo sprintf('%0.2f', $entry['dr_amount']);                    ?></td>-->
																	<td class="text-right"><?php echo $this->price_format($entry['cr_amount']); ?></td>
																	<td>
																		<?php
																		if ($entry['create_date'] == date("Y-m-d")):
																			?>
																			<span class="postdated-status" data-toggle="tooltip" title="Add Now"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
																		<?php endif; ?>
																	</td>
																	<td>
																		<div class="dropdown circle">
																			<a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																				<i class="fa fa-ellipsis-v"></i></a>
																				<ul class="dropdown-menu tablemenu">
																					<?php
																					if ($entry['create_date'] == date("Y-m-d")) {
																						?>
																						<li>
																							<a href="javascript:void(0);" data-toggle="tooltip" title="Add Now"  data-id="<?php echo $entry['id']; ?>" class="approved-postdated"><span class="text-green"><i class="fa fa-check-circle" aria-hidden="true"></i></span></a>
																						</li>
																						<?php
																					}
																					?>
																					<?php
																					$permission = ua($module, 'edit');
																					if ($permission):
																						if ($entry['entry_type_id'] == 5):
																							?>
																							<li>
																								<a href="<?php echo site_url('transaction/sales-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
																							</li>
																						<?php elseif ($entry['entry_type_id'] == 6): ?>
																							<li>
																								<a href="<?php echo site_url('transaction/purchase-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>                                                          
																							</li>
																							<?php 
																						endif;
																					endif;
																					$permission = ua($module, 'delete');
																					if ($permission):
																						?>
																						<li>
																							<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" delete-type="postdated" data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
																						</li>
																					<?php endif;?>
																				</ul>


																			</div>

																		</td>
																	</tr>
																	<?php } ?>
																<?php endif; ?>
															</tbody>                    
														</table>
													</div>
												</div><!-- /.box-body -->
												<div class="box-footer transaction-footer hidden">

													<input type="button" class="btn btn-primary pull-right" value="New Entry" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-entry') . '/' ?><?php echo $entry_type_id; ?>'" />

												</div>     
											</div>
										</div>

										<div class="tab-pane" id="Recurring">
											<div class="box">  
												<div class="box-body table-fullwidth">
													<div class="table-responsive">
														<table id="example02" class="table table-striped fcol-100 lcol-70  transaction-list">
															<thead>
																<tr>
																	<th>Date</th>
																	<th>Number</th>                        
																	<th>Ledger</th>
																	<th>Type</th>
																	<th class="text-right">Amount</th>
																	<th></th>
																</tr>
															</thead>
															<tbody>
																<?php if (isset($all_recurring_entries)): ?>
																	<?php foreach ($all_recurring_entries as $entry) { ?> 
																	<tr id="recurring_row_<?php echo $entry['id']; ?>">
																		<td><?php echo date('d-m-Y', strtotime($entry['create_date'])); ?></td>
																		<td><?php echo $entry['entry_no']; ?></td>
																		<td>
																			<a href="#">
																				<?php
																				$led = array();
																				$devit = json_decode($entry['ledger_ids_by_accounts']);

																				echo "<strong>Dr </strong>";
																				for ($i = 0; $i < count($devit->Dr); $i++) {
																					echo $devit->Dr[$i];
																					if (count($devit->Dr) > 1) {
																						echo ' + ';
																					}
																					break;
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
																		<!--<td><?php //echo sprintf('%0.2f', $entry['dr_amount']);                    ?></td>-->
																		<td class="text-right"><?php echo $this->price_format($entry['cr_amount']); ?></td>
																		<td>
																			<div class="dropdown circle">
																				<a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<i class="fa fa-ellipsis-v"></i></a>
																					<ul class="dropdown-menu tablemenu">
																						<?php
																						$permission = ua($module, 'edit');
																						if ($permission):
																							if ($entry['entry_type_id'] == 5):
																								?>
																								<li>
																									<a href="<?php echo site_url('transaction/sales-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>
																								</li>
																							<?php elseif ($entry['entry_type_id'] == 6): ?>
																								<li>
																									<a href="<?php echo site_url('transaction/purchase-update') . "/" . $entry['id'] . "/" . $entry['entry_type_id'] . "/e"; ?>" data-toggle="tooltip" title="Edit" > <i class="fa fa-pencil"></i></a>                                                          
																								</li>
																								<?php 
																							endif;
																						endif;
																						$permission = ua($module, 'edit');
																						if ($permission):
																							?>
																							<li>
																								<a href="javascript:void(0);" data-toggle="tooltip" title="Delete"  data-id="<?php echo $entry['id']; ?>" delete-type="recurring" data-type="current" class="delete-entry"><span class="text-red"><i class="fa fa-trash"></i></span></a>
																							</li>
																						<?php  endif;?>
																					</ul>


																				</div>

																			</td>
																		</tr>
																		<?php } ?>
																	<?php endif; ?>
																</tbody>                    
															</table>
														</div>
													</div><!-- /.box-body -->
													<div class="box-footer transaction-footer hidden">

														<input type="button" class="btn btn-primary pull-right" value="New Entry" onclick="window.location.href = '<?php echo site_url('admin/add-accounts-entry') . '/' ?><?php echo $entry_type_id; ?>'" />

													</div>   
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- /.col -->
						</div><!-- /.row -->
					</section><!-- /.content -->

				</div>
				<div id="deleteEntryConfirm" class="modal fade" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<form action="<?php echo site_url('transaction_inventory/inventory/ajax_delete_transaction'); ?>" method="post" id="delete-transaction-form">
								<div class="modal-header" style="background: #367fa9;color: #fff">
									<h4 class="modal-title">Confirmation</h4>
									<input type="hidden" value="" id="delete-entry-id" name="delete_entry_id">
									<input type="hidden" value="" id="delete-entry-type" name="delete_entry_type">
									<input type="hidden" value="" id="delete-type" name="delete_type">
								</div>
								<div class="modal-body">
									<p style="font-size:16px;" id="group-confirm-msg">Are you sure want to delete this entry?</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
									<button type="submit" class="btn ladda-button delete-entry-btn" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<script>


    //delete entry
    $(".delete-entry").click(function() {
    	var entry_id = $(this).attr("data-id");
    	var type = $(this).attr("data-type");
    	var delete_type = $(this).attr("delete-type");
    	console.log(type)
    	$("#delete-entry-id").val(entry_id);
    	$("#delete-entry-type").val(type);
    	$("#delete-type").val(delete_type);

    	$("#deleteEntryConfirm").modal('show');
    });

    $("#delete-transaction-form").submit(function(event) {
    	event.preventDefault();
    	var l = Ladda.create(document.querySelector('.delete-entry-btn'));
    	l.start();
    	var form = $(this),
    	url = form.attr('action'),
    	data = form.serialize();
    	$.ajax({
    		url: url,
    		type: 'POST',
    		data: data,
    		dataType: 'json',
    		success: function(data) {
    			l.stop();
    			if (data.res == 'success') {
    				if (data.type == 'current') {
    					$('#current_row_' + data.entry_id).remove();
    				} else if (data.type == 'postdated') {
    					$('#post_row_' + data.entry_id).remove();
    				} else if (data.type == 'recurring') {
    					$('#recurring_row_' + data.entry_id).remove();
    				}

    				$("#deleteEntryConfirm").modal('hide');
    				Command: toastr["success"](data.message);
    			} else {
    				Command: toastr["error"]('Error Occured please try again.');
    			}
    		}
    	});

    });

//    $(document).ready(function() {
//        $(".dataTables_paginate").closest('.row').addClass('footer-table-info');
//        document.addEventListener('contextmenu', event = > event.preventDefault());
//    });

</script>


<script type="text/javascript">
	// $("#loader_image").hide();
	var busy = false;
	var limit = 2
    var offset = 0;
    var name = "<?php echo $this->uri->segment(3); ?>";
    var id = "<?php echo $this->uri->segment(4); ?>";
    var month = "<?php echo $this->uri->segment(5); ?>";
	function displayRecords(lim, off) {
		$.ajax({
			url: "<?php echo base_url('admin/loadMoreRecords'); ?>",
			type: "POST",
			data: "limit=" + lim + "&offset=" + off + "&month=" + month + "&id=" + id + "&name=" + name,
			cache: false,
			async: false,
			beforeSend: function() {
				$("#loader_message").html("").hide();
				$('#loader_image').show();
			},
			success: function(html) {
				console.log(html);
				$('#loader_image').hide();
				$("#results").append(html);
				if (html.trim() == "") {
	              $("#loader_message").html('<button data-atr="nodata" class="btn btn-default" type="button">No more records.</button>').show()
	            } else {
	              $("#loader_message").html('<button class="btn btn-default" type="button">Loading please wait...</button>').show();
	            }
				window.busy = false;
			}
		});
	}


	$(document).ready(function() {
	        // start to load the first set of data
	        displayRecords(limit, offset);
	 
	        $('#loader_message').click(function() {
	 
	          // if it has no more records no need to fire ajax request
	          var d = $('#loader_message').find("button").attr("data-atr");
	          if (d != "nodata") {
	            offset = limit + offset;
	            displayRecords(limit, offset);
	          }
	        });

	        $("#scrollTable").scroll(function() {
	            // make sure u give the container id of the data to be loaded in.
	            if ($("#scrollTable").scrollTop() + $("#scrollTable").height() > $("#results").height() && !busy) {
	                busy = true;
	                offset = limit + offset;
	         
	                displayRecords(limit, offset);
	         
	            }
	        });

    });
</script>