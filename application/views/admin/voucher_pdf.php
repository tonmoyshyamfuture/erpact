<link type="text/css" href="<?php echo base_url(); ?>assets/admin/sketch_custom/css/dompdf.css" id="dompdf_css" rel="stylesheet">
<?php
$amount_field_count = 0;
?>

<div class="wrapper2">     
    
    <!-- Main content -->
    <section class="content">
        <div class="row">            
            <div class="col-md-12">              
                <div class="box">   
                    <div class="box-body pdf_class" id="printreport" data-pdf="Transaction Details<?php echo isset($entry[0]['type'])?'-'.$entry[0]['type']:"" ?>">
                        <div class="row margin-b-10">
                            <div class="col-xs-12 text-center">
                                <div class="report-header-company-info voucher" style="display:block">
                                    <div id="voucher-header" class="clearfix">
                                    <h3><?php echo isset($company_details->company_name)?$company_details->company_name:"" ?></h3>
                                    <p><?php echo isset($company_details->street_address)?$company_details->street_address:'' ?> <?php echo isset($company_details->city_name) ? ', ' . $company_details->city_name : '' ?> <?php echo isset($company_details->zip_code) ? ' - ' . $company_details->zip_code : '' ?><?php echo isset($company_details->state_name) ? ', ' . $company_details->state_name : '' ?><?php echo isset($company_details->country) ? ', ' . $company_details->country : '' ?>.<br>
                                        Mobile: <?php echo (isset($company_details->mobile) && $company_details->mobile)?$company_details->mobile:"" ?>, Phone: <?php echo (isset($company_details->telephone) && $company_details->telephone)?$company_details->telephone:'' ?>,<br> Email: <?php echo isset($company_details->email)?$company_details->email:"" ?></p>
                                    </div>
                                    <h5><?php echo isset($voucher['title']) ? $voucher['title'] : '' ?></h5> 
                                    <h6><?php echo isset($voucher['sub_title']) ? $voucher['sub_title'] : '' ?></h6>
                                </div>
                            </div>                            
                        </div>                        
                        <div class="table-responsive"> 
                        <table class="table table-voucher-header">
                            <tbody>
                                <tr>
                                    <td>Voucher No: <span><?php echo count($entry) > 0 ? $entry[0]['entry_no'] : ""; ?></span></td>
                                    <td>Dated: <span><?php echo count($entry) > 0 ? date('d/m/Y', strtotime($entry[0]['create_date'])) : ""; ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <table class="table table-voucher-body">
                            <thead>
                                <tr> 
                                    <th>Particulars</th>
                                    <th class="width-130 text-right">Debit</th>
                                    <th class="width-130 text-right">Credit</th>
                                </tr>        
                            </thead>
                            <tbody>
                                <?php
                                if (isset($entry_details)) {
                                    $entry_row = count($entry_details);
                                    for ($i = 0; $i < count($entry_details); $i++) {
                                        ?>
                                <tr>
                                            <td style="position: relative;" class="text-left">
                                                <div class="particulars">
                                                    <div class="particulars_cr"><span><?php echo $entry_details[$i]['account']; ?>.</span> <?php echo $entry_details[$i]['ladger_name']; ?></div>
                                                    <div class="particulars_name">
                                                        <?php if (count($entry_details[$i]['against_ref']) > 0) { ?>
                                                            <span>Against Ref.:</span><br>
                                                            <?php
                                                            foreach ($entry_details[$i]['against_ref'] as $bill) {
                                                                echo $bill['bill_name'] . ' - ' . date('d-m-Y', strtotime($bill['created_date'])) . ' - ' . $bill['bill_amount'] . ' ' . $entry_details[$i]['account'];
                                                            }
                                                        }
                                                        ?>
                                                        <?php
                                                        if (count($entry_details[$i]['bank_details']) > 0) {
                                                            ?>
                                                            <span>Transaction Details:</span><br>
                                                            <?php
                                                            foreach ($entry_details[$i]['bank_details'] as $bank) {
                                                                ?>
                                                                <span><?php echo $bank->name; ?></span>
                                                                - <?php echo $bank->instrument_no; ?> - <?php echo date("d-m-Y", strtotime($bank->instrument_date)); ?> - Rs.<?php echo $this->price_format($bank->bank_amount); ?>.<br>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>                                                
                                            </td>
                                            <td class="text-right"><?php echo ($entry_details[$i]['account'] == 'Dr') ? $this->price_format($entry_details[$i]['balance']) : ""; ?></td>
                                            <td class="text-right" ><?php echo ($entry_details[$i]['account'] == 'Cr') ? $this->price_format($entry_details[$i]['balance']) : ""; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>  
                                 <?php
                                 if($entry_row < 9){
                                     $i = 0;
                                    while($i < (8 - $entry_row)){ ?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                <?php 
                                    $i++;
                                    }
                                 }
                                 ?>
                                 <tr>
                                     <td style="position: relative">
                                         <div class="voucher-naration">
                                        <?php
                                        if ($entry_details) {
                                            echo '<strong>Narration : <br></strong>';
                                            echo $entry_details[0]['narration'];                                            
                                            }
                                            ?>
                                         <br>
                                         </div>
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>                                                                                                      
                            </tbody>
                            
                            <tfoot>
                                <tr>
                                    <th><span>Amount in words :</span> <?php echo ucwords($amountInWord) . ' Only'; ?></th>
                                    <th class="text-right"><?php echo $this->price_format($entry[0]['dr_amount']); ?></th>
                                    <th class="text-right"><?php echo $this->price_format($entry[0]['cr_amount']); ?></th>
                                </tr>
                            </tfoot>
                        </table>                        
                        
                        <table class="table table-voucher-footer">
                            <tbody>
                                <tr>
                                    <td>Checked by</td>
                                    <td>Verified by</td>
                                    <td>Authorized Signatory</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                      
                    </div>
                </div>
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
    <?php //echo form_close();     ?>
</div>
<style>
    hidden{transition: all 0.5s}
</style>
<script>
    $(".myButton").click(function() {
        var effect = 'slide';
        var options = {direction: $('.mySelect').val(0)};
        var duration = 500;
        $('#myDiv').toggle(effect, options, duration);
    });

    // T O G G L E 
    $(".toggleVoucherHeader").click(function() {
        $("#voucher-header").toggleClass('hidden');
    });    
    $(".toggleVoucherFooter").click(function() {
        $("#voucher-footer").toggleClass('hidden');
    });

</script>