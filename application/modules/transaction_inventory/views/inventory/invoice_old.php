
<div class="wrapper2"> 

    <section class="content-header">
        <div class="row">
            <div class="col-xs-6">
                <h1><i class="fa fa-shopping-cart" aria-hidden="true"></i> <a href="#"><?php echo isset($voucher['type'])?$voucher['type']:''?></a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> Invoice </h1>  
            </div>
            <div class="col-xs-6">
                <div class="pull-right">

                </div>
            </div> 
        </div>     
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
                <!-- general form elements disabled -->

                <div class="box">   

                </div>
                <style>
                    .invoice2 .table{border-left: 1px solid #ccc !important; border-right: 1px solid #ccc !important; margin-bottom: 0 !important; }
                    .invoice2 .table tr td{ border-top: 1px solid #ccc !important; border-right: 1px solid #ccc !important; padding: 5xp; font-weight: bold;}
                    .invoice2 .table tr td:last-child{border-right: 0 !important;}
                    .invoice2 .table tbody{border-bottom: 1px solid #ccc !important;}
                    .invoice2 .table tr td table tbody{border-bottom: 0 !important;}
                    .invoice2 .table tr td tr td{height: 40px;}
                    .invoice2 .table tr td tr:first-child td{border-top: 0 !important;}
                    .invoice2 .table tr td tr:last-child td {border-bottom: 0 !important;}
                    .invoice2 .table tr td tr td:last-child{border-right: 0 !important;}
                    .invoice2 .table tr th{border-right: 1px solid #ccc !important; vertical-align: middle;}
                    .invoice2 .table tr th:last-child{border-right: 0px !important;}
                    .table-striped > tbody > tr:nth-of-type(2n+1) {
                        background-color: rgba(230,230,230,0.45);
                    }

                </style>

                <div class="box">   
                    <div class="box-body">
                        <div class="row report-header">
                            <div class="col-xs-12">
                                <h3><?php echo isset($voucher['type'])?$voucher['type']:''?></h3>                               
                            </div>



                        </div>

                        <div class="invoice2 table-responsive">                    
                            <table class="table">
                                <tbody>

                                    <tr>
                                        <td width="50%">
                                            <h6><?php echo (isset($voucher['id']) && ($voucher['id']==5 || $voucher['id']==7 || $voucher['id']==10))?'From':'To'?></h6>
                                            <?php echo isset($company_details->company_name)?$company_details->company_name:''?><br>
                                            <?php echo isset($company_details->street_address)?$company_details->street_address:''?><br>
                                            <?php echo isset($company_details->zip_code)?$company_details->zip_code:''?>,
                                            <?php echo isset($company_details->city_name)?$company_details->city_name:''?>,
                                            <?php echo isset($company_details->state_name)?$company_details->state_name:''?>,
                                            <?php echo isset($company_details->country)?$company_details->country:''?><br>
                                            Contact Details<br>
                                             <?php echo isset($company_details->email)?'Email:'.$company_details->email:''?><br>
                                             <?php echo isset($company_details->telephone)?'Phone:'.$company_details->telephone:''?><br>
                                              <?php echo isset($company_details->mobile)?'Mobile:'.$company_details->mobile:''?>
                                        </td>
                                        <td  width="50%" style="padding:0;">
                                            <table width="100%" class="table-striped">
                                                <tr>
                                                    <td width="50%">
                                                        <strong><?php echo isset($voucher['type'])?$voucher['type']:''?> No</strong><br>
                                                        <?php echo $entry->entry_no;?>                                                   
                                                    </td>
                                                    <td width="50%">
                                                        <strong>Dated</strong><br>
                                                        <?php echo isset($entry->create_date)?date('d/m/Y',  strtotime($entry->create_date)):''?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                      
                                                        <?php echo (isset($voucher['id']) && $voucher['id']==5)?'Credit Note':((isset($voucher['id']) && $voucher['id']==6)?'Debit Note':'')?><br>
                                                        <strong></strong>
                                                    </td>
                                                    <td>
                                                        Mode/Terms of Payment<br>
                                                        <strong></strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Buyer's Order No.<br>
                                                        <strong><?php echo isset($entry->voucher_no)?$entry->voucher_no:'';?></strong>
                                                    </td>
                                                    <td>
                                                        Dated<br>
                                                        <strong><?php echo isset($entry->voucher_date)?date('d/m/Y',  strtotime($entry->voucher_date)):''?></strong>
                                                    </td>
                                                </tr>
<!--                                                <tr>
                                                    <td>
                                                        Delivery Challan No.<br>
                                                        <strong></strong>
                                                    </td>
                                                    <td>
                                                        Dated<br>
                                                        <strong></strong>
                                                    </td>
                                                </tr>-->

                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">
                                            <h6><?php echo (isset($voucher['id']) && ($voucher['id']==6 || $voucher['id']==8 || $voucher['id']==9))?'From':'To'?></h6>
                                            <?php echo isset($ledger_contact_details->company_name)?$ledger_contact_details->company_name:'';?><br>
                                            <?php echo isset($ledger_contact_details->zipcode)?$ledger_contact_details->zipcode:'';?>,
                                           <?php echo isset($ledger_contact_details->city)?$ledger_contact_details->city:'';?>,
                                            <?php echo isset($ledger_contact_details->state)?$ledger_contact_details->state:'';?>,
                                            <?php echo isset($ledger_contact_details->country)?$ledger_contact_details->country:'';?><br>
                                            Contact Details<br>
                                            <?php echo isset($ledger_contact_details->email)?'Email:'.$ledger_contact_details->email:'';?><br>
                                            <?php echo isset($ledger_contact_details->phone)?'Phone:'.$ledger_contact_details->phone:'';?><br>
                                        </td>
                                        <td  width="50%" style="padding:0;">
                                            <table width="100%" class="table-striped">

                                                <tr>
                                                    <td width="50%">
                                                        Despatched through<br>
                                                        <strong></strong>
                                                    </td>
                                                    <td>
                                                        Destination<br>
                                                        <strong></strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        Terms of Delivery<br>
                                                        <strong>
                                                            <?php echo isset($order->terms_and_conditions)?$order->terms_and_conditions:''  ?>
                                                        </strong>
                                                    </td>

                                                </tr>



                                            </table>
                                        </td>
                                    </tr>




                                </tbody>


                            </table>

                        </div>
                        <div class="invoice2 table-responsive">                    
                            <table class="table table-striped table-report">
                                <tbody>                                   
                                    <tr>
                                        <th width="4%" class="text-center">Sl. No.</th>
                                        <th class="text-center">Item Details</th>
                                     
                                        <th class="text-center width-100">Quantity</th>
                                        <th class="text-center width-100">Rate</th>
                                        <th class="text-center width-10">Per</th>
                                        <th class="text-center width-70">Tax<br>(%)</th>
                                        <th class="text-center width-100">Amount</th>
                                    </tr>
                                    <?php
                                    $total_qty = 0;
                                    $total_tax_val = 0;
                                    $total_product_val = 0;
                                    $grand_product_val = 0;
                                    if (count($order_details) > 0):
                                        foreach ($order_details as $key => $row) {
                                            ?>
                                            <tr>
                                                <td class="text-center "><?php echo $key + 1; ?></td>
                                                <td class="text-center"><?php echo isset($row->name) ? $row->name : '' ?></td>
                                        
                                                <td class="text-center"><?php echo isset($row->quantity) ? $row->quantity : '1' ?></td>
                                                <td class="text-center"> <?php echo isset($row->price) ? $this->price_format($row->price) : '' ?></td>
                                                <td class="text-center"><?php echo isset($row->unit_name) ? $row->unit_name : '' ?></td>
                                                <td class="text-center"><?php echo isset($row->tax_rate) ? $row->tax_rate : '' ?></td>
                                                <?php
                                                $tax_val = ($row->tax_rate / 100) * $row->price;
                                                $product_val = $row->quantity * $row->price;
                                                $product_total = (($row->quantity * $row->price) + $tax_val);
                                                $total_qty+=$row->quantity;
                                                $total_tax_val+=$tax_val;
                                                $total_product_val+=$product_val;
                                                $grand_product_val+=$product_total;
                                                ?>
                                                <td class="text-right"><?php echo $this->price_format($product_val) ?></td>
                                            </tr>
                                            <?php
                                        }
                                    endif;
                                    ?>

                                    <tr>
                                        <td colspan="6">W.B. Output Vat Tax</td>

                                        <td><?php echo $this->price_format($total_tax_val); ?></td>
                                    </tr>

                                    <tr>
                                        <td colspan="6">Discount</td>

                                        <td>
                                            <?php
                                            $discount = 0;
                                            $total_discount = 0;
                                            if (isset($entry_details) && count($entry_details) > 3):
                                                for ($i = 2; $i < (count($entry_details) - 1); $i++) {
                                                    $total_discount = isset($entry_details[$i]->balance) ? $entry_details[$i]->balance : 0;
                                                   echo $this->price_format($total_discount);
                                                }
                                            endif;
                                            ?>
                                        </td>
                                    </tr>




                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6"><strong>Total</strong></td>

                                        <td><?php echo $this->price_format($grand_product_val - $total_discount) ?></td>
                                    </tr>  
                                </tfoot>


                            </table>

                        </div>
                        <div class="invoice2 table-responsive">                    
                            <table class="table table-report">
                                <tbody>                                   

                                    <tr>
                                        <td colspan="2" style="position:relative; height: 200px;">
                                            Amount Chargeable (in words)<br>
                                            <?php $this->load->helper('numtoword');?>
                                            <strong>
                                                <?php 
                                                $total_amount=($grand_product_val - $total_discount);
                                                echo numberTowords($total_amount);
                                                 ?>
                                            </strong>
                                            <p style="position:absolute; bottom: 0;">
                                                Narration :<br>
                                                <?php echo isset($entry->narration)?$entry->narration:''?>
                                            </p>
                                        </td>

                                    </tr>
                                    <tr>

                                        <td>
                                            Company's VAT : <?php echo isset($company_details->vat)?$company_details->vat:''?><br>

                                            Company's CST No. : <?php echo isset($company_details->cst)?$company_details->cst:''?><br>

                                            Company's Service Tax No. : <?php echo isset($company_details->service_tax)?$company_details->service_tax:''?><br>

                                            Company's TIN : <?php echo isset($company_details->tan)?$company_details->tan:''?><br>

                                            Company's PAN : <?php echo isset($company_details->pan)?$company_details->pan:''?>
                                        </td>
                                        <td style="position:relative;">
                                            For <strong><?php echo isset($company_details->company_name)?$company_details->company_name:''?></strong>
                                            
                                            <p style="position:absolute; bottom: 0; right: 10px;"> Authorise Signatory</p>
                                        </td>
                                    </tr>







                                </tbody>



                            </table>

                        </div>


                    </div>
                </div> 

                <!-- /.box -->
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>
