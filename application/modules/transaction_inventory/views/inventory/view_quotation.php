
<div class="wrapper2">
    <div class="side_toggle">
        <div  id="myDiv"><button class="btn btn-sm btn-danger myButton  btn-closePanel"><i class="fa fa-times"></i></button>
            <form class="hidden" style="padding:20px;">
                <div class="form-group">
                    <h5>Invoice Template</h5>
                </div>
                <div class="form-group">
                    <label>Page Orientation</label>
                    <div class="radio">
                        <label>
                            <input name="optionsRadios" id="setInvoiceTaxInCol" value="option1" checked="" type="radio">
                            Tax & Discount in Column
                        </label>
                        <a href="#" data-toggle="modal" data-target="#modalTaxInCol" class="pull-right" href="" ><i class="fa fa-eye"></i></a>
                    </div>
                    <div class="radio">
                        <label>
                            <input name="optionsRadios" id="setInvoiceTaxInRow" value="option2" type="radio">
                            Tax & Discount in Row
                        </label>
                        <a href="#" data-toggle="modal" data-target="#modalTaxInRow" class="pull-right" href="" ><i class="fa fa-eye"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1><i class="fa fa-shopping-cart" aria-hidden="true"></i>Quotation</h1>
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <div class="btn-group btn-svg">
                        <?php
                        //sales related = 1, Purchase related = 2
                        $module = 196;
                        $permission = ua($module, 'edit');
                        if ($permission):
                            ?>
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#deleteEntryConfirm"><i data-feather="trash-2"></i></button>
                            <a class="btn btn-default" href="<?php echo base_url('admin/edit-quotation') . "/" . $entry->id . "/" . $entry_type_id . "/e"; ?>" data-toggle="tooltip" data-placement="bottom" title="Edit" > <i data-feather="edit"></i></a>
                            <!-- <a class="btn btn-default" href="javascript:void(0);" data-toggle="tooltip" data-placement="bottom" title="Edit" > <i data-feather="edit"></i></a> -->
                        <?php endif ?>
                        <button class="btn btn-default save-pdf" data-pdf="dpf_content"><i data-feather="mail"></i></button>
                        <button class="btn btn-default download-pdf"><i data-feather="printer"></i></button>
                    </div>
                    <button id="button" class="myButton btn btn-primary btn-sm hidden"><i class="fa fa-gear"></i></button>
                </div>
            </div>
        </div>
    </section>
    <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Quotations', '/admin/quotation-list');
        $this->breadcrumbs->push('View Quotation', '/admin/view-quotation');
        $this->breadcrumbs->show();
        ?>
    </section>

    <section class="content" id="invoiceTaxInCol">
        <div class="clearfix bill-wrapper">
            <?php
            $total_data = count($order_details);
            $rownumber = 26;
            //IF is not given titel : $rownumber + 1
            isset($voucher['title']) ? $rownumber : $rownumber++;
            //IF is not given sub_title : $rownumber + 1
            isset($voucher['sub_title']) ? $rownumber : $rownumber++;
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
                    <div class="box-body pdf_type_1 pdf_class" id="printreport" data-pdf="Quotation">
                        <div class="table-responsive">
                        <table class="table table-invoice-main">
                            <thead>
                                <tr>
                                    <th>
                            <table class="table table-invoice-header">
                                <!-- Report Header Start -->
                                <tr id="report-header">
                                    <td colspan="3" class="invoice-title text-center">
                                        <h1><?php echo ($voucher['title']) ? $voucher['title'] : $voucher['type']; ?></h1>
                                        <h3><?php echo isset($voucher['sub_title']) ? $voucher['sub_title'] : '' ?></h3>
                                    </td>
                                </tr>
                                <!-- Report Header End -->

                                <!-- Billing Header Start -->
                                <tr>
                                    <td colspan="2">
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
                                    <td rowspan="2" class="table-invoice-header-bill-dtls">
                                        <table class="table">
                                            <tr>
                                                <td>
                                                    <strong><?php echo isset($voucher['type']) ? $voucher['type'] : '' ?> Invoice No.</strong><br><?php echo $entry->entry_no; ?>&nbsp;
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
                                                    <strong>Delivery Doc. No.</strong><br>
                                                    <?php echo isset($despatchDetails->despatch_doc_no) ? $despatchDetails->despatch_doc_no : ''; ?>&nbsp;
                                                </td>
                                                <td>
                                                    <strong>Dated</strong><br>
                                                    <?php echo isset($despatchDetails->bill_lr_rr_date) ? date('d/m/Y', strtotime($despatchDetails->bill_lr_rr_date)) : ''; ?>&nbsp;
                                                </td>
                                            </tr>

                                            <tr class="<?php echo ((isset($voucher['id']) && $voucher['id'] == 5) && (isset($ewaybillDetails->cancel_status) && $ewaybillDetails->cancel_status != 1)) ? '' :'hidden';?>">
                                                <td>
                                                    <strong>Eway bill No.</strong><br>
                                                    <?php echo (isset($ewaybillDetails->motor_vehicle_no))? $ewaybillDetails->motor_vehicle_no:''; ?>
                                                </td>
                                                <td>
                                                    <strong>Valid upto</strong><br>
                                                    <?php echo (isset($ewaybillDetails->valid_upto))? $ewaybillDetails->valid_upto:''; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Mode/Terms of Payment</strong><br><?php echo ($entry_details[0]->credit_date) ? $entry_details[0]->credit_date . ' Days' : ''; ?>
                                                </td>
                                                <td>
                                                    <strong>Mode/Terms of Delivery</strong><br>
                                                    <?php 
                                                        if(isset($despatchDetails->despatch_through) && $despatchDetails->despatch_through != ''){
                                                            echo 'By Courier';
                                                        }elseif(isset($despatchDetails->motor_vehicle_no) && $despatchDetails->motor_vehicle_no != ''){
                                                            echo 'By Car'; 
                                                        }else{
                                                            echo 'By Hand'; 
                                                        }
                                                    ?>&nbsp;
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Despatched through</strong><br>
                                                    <?php echo isset($despatchDetails->despatch_through) ?  $despatchDetails->despatch_through : ''; ?>&nbsp;
                                                </td>
                                                <td >
                                                    <strong>Place of Supply</strong><br><?php echo ($order->shipping_state) ? $order->shipping_state_code . '-' . $order->shipping_state_name : ''; ?>&nbsp;
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Bill of Lading/LR-RR No.</strong><br><?php echo isset($despatchDetails->bill_lr_rr) ? $despatchDetails->bill_lr_rr : ''; ?>&nbsp;
                                                </td>
                                                <td>
                                                    <strong>Vehicle No.</strong><br>
                                                    <?php echo isset($despatchDetails->motor_vehicle_no) ? $despatchDetails->motor_vehicle_no : ''; ?>&nbsp;
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- row 2-->
                                <tr>
                                    <td>
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
                                    <td>
                                        <div class="invoice-header-address">
                                            <?php echo (isset($voucher['id']) && ($voucher['id'] == 6 || $voucher['id'] == 8 || $voucher['id'] == 9 || $voucher['id'] == 14)) ? 'From' : 'To' ?>
                                            <div class="invoice-address-indent clearfix">
                                                <i>Shipping Address</i><br>
                                                <span style="font-size:110%; text-transform: uppercase"><strong><?php echo ($order->shipping_first_name) ? $order->shipping_first_name . "<br>" : ''; ?></strong></span>
                                                <?php echo ($order->shipping_address) ? $order->shipping_address : '' ?>
                                                <?php echo ($order->shipping_city) ? $order->shipping_city . ',<br>' : ''; ?>
                                                <?php echo ($order->shipping_state) ? $order->shipping_state_code . '-' . $order->shipping_state_name . ',' : ''; ?>
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
                                        $discount_flag = 0;
                                        if($order->spl_discount_json != ''){
                                            $discount_flag = 1;
                                        }
                                    ?>
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
                                                    <?php if($discount_flag > 0){ ?>
                                                        <th class="inv-tax text-center" colspan="2">Discount</th>
                                                    <?php } ?>

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
                                                    <!-- discount-->
                                                    <?php if($discount_flag > 0){ ?>
                                                        <th class="inv-tax-rate text-right"> (%)</th>
                                                        <th class="inv-tax-value text-right">Value</th>
                                                    <?php } ?>
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
                                               

                                                if (count($order_details) > 0):
                                                    // foreach ($order_details as $key => $row) {
                                                    $k = $j * $rownumber;
                                                    $kk = $k + $rownumber;
                                                    for ($k; $k < $kk; $k++) {
                                                        if ($k >= $total_data) {
                                                            ?>
                                                            <tr class="row-height">
                                                                <td class="inv-slno">&nbsp</td>
                                                                <td class="inv-itemdtls"><?php //  echo $k;  ?></td>
                                                                <td class="inv-hsn"></td>
                                                                <td class="inv-qty"></td>
                                                                <td class="inv-rate"></td>
                                                                <td class="inv-unit"></td>
                                                                <td class="inv-value"></td>
                                                                <?php if($discount_flag > 0){ ?>
                                                                    <td class="inv-tax-rate"></td>
                                                                    <td class="inv-tax-value"></td>
                                                                <?php } ?>
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
                                                                <td class="inv-itemdtls"><?php echo isset($row->name) ? $row->name : ''; ?><span class="product-description"><?php echo isset($row->product_description) ? $row->product_description : ''; ?></span></td>
                                                                <td class="inv-hsn"><?php echo isset($row->hsn_number) ? $row->hsn_number : '' ?></td>
                                                                <td class="text-right inv-qty"><?php echo isset($row->quantity) ? $row->quantity+0 : '1' ?></td>
                                                                <td class="text-right inv-rate"><?php echo isset($row->base_price) ? $this->price_format($row->base_price) : '0.00' ?></td>
                                                                <td class="inv-unit"><?php echo isset($row->unit_name) ? $row->unit_name : 'No' ?></td>
                                                                <td class="text-right inv-value"><?php echo isset($row->base_price) ? $this->price_format($row->base_price * $row->quantity) : '0.00' ?></td>
                                                                <?php if($discount_flag > 0){ ?>
                                                                <td class="text-right inv-tax-rate"><?php echo $row->discount_percentage; ?></td>
                                                                <td class="text-right inv-tax-value"><?php echo $this->price_format($discount); ?></td>
                                                               
                                                                <?php } ?>
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
                                            <th class="inv-qty text-right"><?php echo $total_qty + 0; ?></th>
                                            <th class="inv-rate text-center">-</th>
                                            <th class="inv-unit text-center">-</th>
                                            <th class="inv-value text-right"><?php echo $this->price_format($total_product_val); ?></th>
                                            <?php if($discount_flag > 0){ ?>
                                                <th class="inv-tax-tot text-right" ><?php echo $this->price_format($total_discount); ?></th>
                                            <?php }?>
                                            
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
                                                <?php
                                                }
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

        <?php if ($discount_flag != 0) { ?>
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
                                                    echo ucwords(number_to_words($total_amount)) . ' Only';
                                                    ?>
                                                </strong> </td>
                                        </tr>
    <?php } ?>
                                    <tr class="row-naration">
                                        <td ><strong>Narration :</strong><br>
    <?php echo isset($entry->narration) ? $entry->narration : '' ?></td>
                                        <td >
                                            Bank Name: <strong><?php echo isset($entry->bank_name) ? $entry->bank_name : '' ?></strong> <br>
                                            Account No: <strong><?php echo isset($entry->acc_no) ? $entry->acc_no : '' ?></strong> <br>
                                            IFSC Code: <strong><?php echo isset($entry->ifsc) ? $entry->ifsc : '' ?></strong> <br>
                                            Branch Name: <strong><?php echo isset($entry->branch_name) ? $entry->branch_name : '' ?></strong> <br>
                                        </td>
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
                            </div>
                        <?php if (($ceil_data - 1) != $j) { ?>
                            <div class="invoice-continued">Continued...</div>
    <?php } ?>
                    </div>
                </div>
<?php } ?>
        </div>
    </section><!-- /.content -->

    <section class="content hidden" id="invoiceTaxInRow">
        <div class="clearfix bill-wrapper">
            <?php
            $total_data = count($order_details);
            $rownumber = 6;
            //IF is not given titel : $rownumber + 1
            isset($voucher['title']) ? $rownumber : $rownumber++;
            //IF is not given sub_title : $rownumber + 1
            isset($voucher['sub_title']) ? $rownumber : $rownumber++;
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
                    <div class="box-body pdf_type_2" id="printreport" data-pdf="Invoice">

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
                                    <td colspan="2">
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
                                    <td rowspan="2" class="table-invoice-header-bill-dtls">
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
                                    <td>
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
                                    <td>
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
                                        <table class="table table-invoice-body lessheight">
                                            <thead>
                                                <!-- billing title row -->
                                                <tr>
                                                    <th class="inv-slno2">#</th>
                                                    <th class="inv-itemdtls2">Item Details</th>
                                                    <th class="inv-hsn2" >HSN/SAC</th>
                                                    <th class="inv-qty2 text-right">Qty.</th>
                                                    <th class="inv-rate2 text-right">Rate</th>
                                                    <th class="inv-unit2">Unit</th>
                                                    <th class="inv-value2 text-right">Value</th>
                                                </tr>
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
                                                                <td class="inv-slno2">&nbsp</td>
                                                                <td class="inv-itemdtls2"><?php //  echo $k;  ?></td>
                                                                <td class="inv-hsn2"></td>
                                                                <td class="inv-qty2"></td>
                                                                <td class="inv-rate2"></td>
                                                                <td class="inv-unit2"></td>
                                                                <td class="inv-value2"></td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            $row = $order_details[$k];
                                                            $key = $k;
                                                            $discount = $row->base_price * $row->quantity - $row->price;
                                                            ?>
                                                            <tr class="row-height2">
                                                                <td class="inv-slno2"><?php echo $key + 1; ?></td>
                                                                <td class="inv-itemdtls2"><?php echo isset($row->name) ? $row->name : '' ?></td>
                                                                <td class="inv-hsn2"><?php echo isset($row->hsn_number) ? $row->hsn_number : '' ?></td>
                                                                <td class="text-right inv-qty2"><?php echo isset($row->quantity) ? $this->price_format($row->quantity) : '1' ?></td>
                                                                <td class="text-right inv-rate2"><?php echo isset($row->base_price) ? $this->price_format($row->base_price) : '0.00' ?></td>
                                                                <td class="inv-unit2"><?php echo isset($row->unit_name) ? $row->unit_name : 'No' ?></td>
                                                                <td class="text-right inv-value2"><?php echo isset($row->base_price) ? $this->price_format($row->base_price * $row->quantity) : '0.00' ?></td>
                                                            </tr>

                                                            <tr class="row-taxx disc">
                                                                <td></td>
                                                                <td>DISCOUNT</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="text-right">-</td>
                                                                <td></td>
                                                                <td class="text-right">- <?php echo $discount; ?></td>
                                                            </tr>
                                                            <?php
                                                            if (!$order->is_igst_included) {
                                                                ?>
                                                                <tr class="row-taxx cgst">
                                                                    <td></td>
                                                                    <td>CGST</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-right"><?php echo intval($row->cgst_tax_percent); ?></td>
                                                                    <td></td>
                                                                    <td class="text-right">- <?php echo $this->price_format($row->cgst_tax); ?></td>
                                                                </tr>
                                                                <tr class="row-taxx sgst">
                                                                    <td></td>
                                                                    <td>SGST</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-right"><?php echo intval($row->sgst_tax_percent); ?></td>
                                                                    <td></td>
                                                                    <td class="text-right">- <?php echo $this->price_format($row->sgst_tax); ?></td>
                                                                </tr>
                <?php } else { ?>
                                                                <tr class="row-taxx igst">
                                                                    <td></td>
                                                                    <td>IGST</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-right"><?php echo intval($row->igst_tax_percent); ?></td>
                                                                    <td></td>
                                                                    <td class="text-right">- <?php echo $this->price_format($row->igst_tax); ?></td>
                                                                </tr>
                                                            <?php } ?>

                                                            <?php
                                                            if ($order->is_cess_included) {
                                                                ?>
                                                                <tr class="row-taxx cess">
                                                                    <td></td>
                                                                    <td>CESS</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-right"><?php echo intval($row->cess_tax_percent); ?></td>
                                                                    <td></td>
                                                                    <td class="text-right">- <?php echo $row->cess_tax; ?></td>
                                                                </tr>
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
                                                            <tr class="row-taxx itemtotal">
                                                                <td></td>
                                                                <td>ITEM TOTAL</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="text-right"><?php echo $this->price_format($row->total) ?></td>
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
                                <table class="table table-invoice-total2">
                                    <thead>
                                        <tr class="">
                                            <th class="inv-slno2"></th>
                                            <th class="inv-itemdtls2">Total Quantity</th>
                                            <th class="inv-hsn2"></th>
                                            <th class="text-right inv-qty2"><?php echo $this->price_format($total_qty); ?></th>
                                            <th class="text-right inv-rate2"></th>
                                            <th class="inv-unit2"></th>
                                            <th  class="text-right inv-value2"></th>
                                        </tr>

                                        <tr class="row-taxx disc">
                                            <th></th>
                                            <th>Total Discount </th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">-</th>
                                            <th></th>
                                            <th class="text-right"><?php echo $this->price_format($total_discount); ?></th>
                                        </tr>
                                        <?php
                                        if (!$order->is_igst_included) {
                                            ?>
                                            <tr class="row-taxx cgst">
                                                <th></th>
                                                <th>Total CGST</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-right"><?php echo $this->price_format($cgst_total); ?></th>
                                            </tr>
                                            <tr class="row-taxx sgst">
                                                <th></th>
                                                <th>Total SGST</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-right"><?php echo $this->price_format($sgst_total); ?></th>
                                            </tr>
        <?php } else { ?>
                                            <tr class="row-taxx igst">
                                                <th></th>
                                                <th>Total IGST</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-right"><?php echo $this->price_format($igst_total); ?></th>
                                            </tr>
                                        <?php } ?>

                                        <?php
                                        if ($order->is_cess_included) {
                                            ?>
                                            <tr class="row-taxx cess">
                                                <th></th>
                                                <th>Total CESS</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-right"><?php echo $this->price_format($cess_total); ?></th>
                                            </tr>
        <?php } ?>
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
                                                <?php
                                                }
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
                                                    echo ucwords(numberTowords($total_amount)) . ' Only';
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
    
    <!--Eway bill start-->
    <section style="display:none" class="content" id="eway_bill_section">
        <div class="clearfix bill-wrapper">
            <div class="box">   
                <div class="box-body eway_bill_pdf_class eway_bill_section" id="printreport" data-pdf="Invoice">
                    
                </div>
        </div>    
        </div>
    </section><!-- /.content -->
    
    <!--Eway bill end-->

    
<!--Eway bill canceled modal start-->
<div id="cancel_eway_bill" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
                <div class="modal-header" style="background: #367fa9;color: #fff">
                    <h4 class="modal-title">Do you want to cancel e-way bill ?</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="<?php echo $ewaybillDetails->entry_id; ?>"  id="ewaybill_entry_id" name="ewaybill_entry_id">
                    <div class="form-group" >
                        <label>Reason</label>
                        <input type="text" value="" class="form-control" placeholder="Reason" id="ewaybill_reason" name="ewaybill_reason">
                        <span class="errorMessage"></span>
                    </div> 
                    <div class="form-group" >
                        <label>Remarks</label>
                        <input type="text" value="" class="form-control" placeholder="Remarks" id="ewaybill_remark" name="ewaybill_remark">
                        <span class="errorMessage"></span>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="button" id="eway-bill-canceled-confrom" class="btn ladda-button delete_eway_bill" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
        </div>
    </div>
</div>
<!--Eway bill canceled modal end-->


<!--Eway bill update modal start-->
<div  id="update_eway_bill" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
                <div class="modal-header" style="background: #367fa9;color: #fff">
                    <h4 class="modal-title">Update Eway bill</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="<?php echo $ewaybillDetails->entry_id; ?>"  id="ewaybill_entry_id" name="ewaybill_entry_id">
                    <div class="form-group" >
                        <label>Reason Code</label>
                        <input type="text" value="" class="form-control" placeholder="Reason Code" id="ewaybill_reason_updaete" name="ewaybill_reason_updaete">
                        <span class="errorMessage"></span>
                    </div> 
                    <div class="form-group" >
                        <label>Reason</label>
                        <input type="text" value="" class="form-control" placeholder="Remarks" id="ewaybill_remark_updaete" name="ewaybill_remark_updaete">
                        <span class="errorMessage"></span>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="button" id="eway-bill-update-confrom" class="btn ladda-button update_eway_bill" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
        </div>
    </div>
</div>
<!--Eway bill update modal end-->

<div id="deleteEntryConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('transaction_inventory/inventory/ajax_delete_quotation'); ?>" method="post" id="delete-transaction-form">
                <div class="modal-header" style="background: #4b83ee;color: #fff">
                    <h4 class="modal-title">Confirmation</h4>
                    <input type="hidden" value="<?php echo $entry->id; ?>" id="delete-entry-id" name="delete_entry_id">
                    <input type="hidden" value="request" id="delete-entry-type" name="delete_entry_type">
                    <input type="hidden" value="current" id="delete-type" name="delete_type">
                    <input type="hidden" name="delete_entry_type_id" id="delete-entry-type-id" value="<?php echo $entry_type_id; ?>">
                </div>
                <div class="modal-body">
                    <!-- <p style="font-size:16px;" id="group-confirm-msg">Are you sure want to <label><input type="radio" value="0" id="delete_or_cancel" checked="checked" name="cancel"> delete</label> / <label><input type="radio" value="1" id="delete_or_cancel" name="cancel"> Cancel</label> this entry?</p> -->
                    <p style="font-size:16px;" id="group-confirm-msg">Are you sure want to <label><input type="hidden" value="0" id="delete_or_cancel" checked="checked" name="cancel"> delete</label> this entry?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> No</button>
                    <button type="submit" class="btn btn-primary ladda-button delete-entry-btn" data-color="blue" data-style="expand-right" data-size="s"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>



</div>
<script>
    $(".myButton").click(function() {
        var effect = 'slide';
        var options = {direction: $('.mySelect').val(0)};
        var duration = 500;
        $('#myDiv').toggle(effect, options, duration);
    });

    // T O G G L E

    $(".toggleReportHeader").click(function() {
        $("#report-header").toggleClass('hidden');
    });
    $(".toggleBillingHeader").click(function() {
        $("#billing-header").toggleClass('hidden');
        $(".table-invoice-gst-body").toggleClass('border-t-ddd');
    });
    $(".toggleBillingFooter").click(function() {
        $("#billing-footer").toggleClass('hidden');
    });

    $("#landscape").click(function() {
        $("#switch_page_orientation").attr("href", "<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/print-landscape.css");
    });
    $("#portrait").click(function() {
        $("#switch_page_orientation").attr("href", "<?php echo $this->config->item('base_url'); ?>assets/admin/sketch_custom/css/print-portrait.css");
    });

    setTimeout(function() {
        $('.base_currency').css('display', 'none');
    }, 10000);


    $("#setInvoiceTaxInRow").click(function() {
        $("#invoiceTaxInRow").removeClass('hidden');
        $("#invoiceTaxInCol").addClass('hidden');
    });
    $("#setInvoiceTaxInCol").click(function() {
        $("#invoiceTaxInCol").removeClass('hidden');
        $("#invoiceTaxInRow").addClass('hidden');
    });


    $("#delete-transaction-form").submit(function(event) {
        event.preventDefault();
        var l = Ladda.create(document.querySelector('.delete-entry-btn'));
        l.start();
        var form = $(this),
                url = form.attr('action'),
                data = form.serialize();
        var entry_type_id = $("#delete-entry-type-id").val();
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(data) {
                l.stop();
                if (data.res == 'success') {
                    if (data.type == 'current') {
                        // $('#current_row_' + data.entry_id).remove();
                    } else if (data.type == 'postdated') {
                        // $('#post_row_' + data.entry_id).remove();
                    } else if (data.type == 'recurring') {
                        // $('#recurring_row_' + data.entry_id).remove();
                    }

                    $("#deleteEntryConfirm").modal('hide');
                    Command: toastr["success"](data.message);
                } else {
                    Command: toastr["error"]('Error Occured please try again.');
                }

                if (entry_type_id == 5) {
                    window.location.href = "<?php echo base_url('admin/inventory-transaction-list/sales/5/all'); ?>";
                } else if(entry_type_id == 6) {
                    window.location.href = "<?php echo base_url('admin/inventory-transaction-list/purchase/6/all'); ?>";
                }
            }
        });

    });
    
    

</script>
<style>
    .base_currency{display: none;}
</style>

<!-- pdf download for invoice -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-ajax-downloader@1.1.0/src/ajaxdownloader.min.js"></script>
<script>
    $(function() {
        $('.download-pdf').click(function() {
            var baseUrl = "<?php echo base_url(); ?>";
            var dompdf_css = '<link type="text/css" href="' + baseUrl + 'assets/admin/sketch_custom/css/dompdf.css" id="dompdf_css" rel="stylesheet">';
            $(".pdf_class").prepend(dompdf_css);
            var htmlContent = $('.pdf_class').html();
            $.AjaxDownloader({
                url : "<?php echo base_url(); ?>transaction_inventory/inventory/savePdf",
                data : {
                    content: htmlContent
                }
            });
            $('.pdf_class').find('link[id="dompdf_css"]').remove();
        });
    });
</script> -->

<div class="modal fade" id="modalTaxInCol" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invoice Preview <small>Tax in Column</small></h4>
            </div>
            <div class="modal-body">
                <img class="img-responsive" src="<?php echo site_url(); ?>assets/images/invoice-taxin-row.jpg" style="max-width: 870px; width:100%">
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalTaxInRow" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invoice Preview <small>Tax in Row</small></h4>
            </div>
            <div class="modal-body">
                <img class="img-responsive" src="<?php echo site_url(); ?>assets/images/invoice-taxin-col.jpg" style="max-width: 870px; width:100%">
            </div>
        </div>

    </div>
</div>
