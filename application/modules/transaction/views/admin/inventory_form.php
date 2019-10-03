<style type="text/css">
    .ui-autocomplete{ z-index: 9999; }  
    .table-addorder > tbody > tr > td > .btn,.table-addorder > tbody > tr:focus > td > .btn,.table-addorder > tbody > tr:active > td > .btn{display:none;opacity:0; transition: all 0.3s;}
    .table-addorder > tbody > tr:hover > td > .btn{opacity:1; display: block}
    .table-addorder .form-control{min-height: 30px !important; height: 30px !important;}
    .search_product {    border-color: transparent;    height: 40px !important;}
    .form-control[readonly]{    background: none !important;    border: none !important;}
</style>
<!-- Content Wrapper. Contains page content -->
<form method="POST" action="<?php echo base_url(); ?>transaction/accounts_inventory/ajax_save_form_data" class="formSubmitAll">
    <div class="">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="row">
                <div class="col-xs-5">
                    <h1><i class="fa fa-list"></i> Add Sales Order</h1>
                </div>
                <div class="col-xs-7 text-right">
                    <div class="pull-right">
                        <div class="btn-group btn-svg">
                            <a href="trans-sales-list.php" class="btn btn-default" >Cancel</a>
                            <a href="trans-sales-list.php" class="btn btn-success" >Save</a>
                        </div>                    
                        <button class="btn btn-sm btn-default"><i class="fa fa-gear"></i></button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content"> 
            <div class="box">            
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Entry Number</label>
                                <?php
                                if ($auto_no_status == "1") {

                                    echo '<input type="text" class="form-control entry_no" value="Auto" readonly="readonly" autofocus placeholder="Enter entry no" name="entry_number" />';
                                } else {

                                    echo '<input type="text" class="form-control entry_no" autofocus placeholder="Enter entry no" name="entry_number" />';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date</label>
<?php
if ($auto_date == "1") {

    $date = date('d/m/Y');

    echo '<input class="form-control" type="text" id="tr_date" readonly="readonly" placeholder="DD/MM/YYYY" value="' . $date . '" name="date_form"/>';
} else {
    echo '<input class="form-control" type="text" id="tr_date" placeholder="DD/MM/YYYY" name="date_form"/>';
}
?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Debtors </label>
                                <input type="text" class="form-control entry_debtors" name="tr_ledger[]" placeholder="Click to select" />
                                <input type="hidden" class="tr_ledger_id_debtors" name="tr_ledger_id[]"/>
                                <input type="hidden" class="in_ledger_state" name="in_ledger_state"/>
                                <input type="hidden" class="in_ledger_country" name="in_ledger_country"/>
                                <input type="hidden" class="tr_type_debtors" name="tr_type[]"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sales</label>
                                <input type="text" class="form-control entry_sales" name="tr_ledger[]" placeholder="Click to select"/>
                                <input type="hidden" class="tr_ledger_id_sales" name="tr_ledger_id[]"/>
                                <input type="hidden" class="tr_type_creditors" name="tr_type[]"/>
                            </div>
                        </div>
                    </div>
                    <hr style="margin:10px 0 20px;">

                    <div class="row debtors_details" style="display: none;"> 
                        <div class="col-md-3 billing_addr">
                            <label style="margin-bottom: 5px;">Billing Address</label>
                            <p><strong class="c_name">ABCD Company Ltd.</strong></p>
                            <p><span class="addr">50 Diamond Harbour Road</span>
                                <br><span class="city">Kolkata</span> - <span class="zip">700104</span>, 
                                <span class="state">WB</span>. <span class="country">INDIA</span> </p>
                            <p>Tax: <span class="tax">1234567890</span></p>
                        </div>                    
                        <div class="col-md-3 shipping_addr">
                            <label style="margin-bottom: 5px;">Shipping Address</label>
                            <p><strong class="c_name">ABCD Company Ltd.</strong></p>
                            <p><span class="addr">50 Diamond Harbour Road</span><br>
                                <span class="city">Kolkata</span> - <span class="zip">700104</span>, 
                                <span class="state">WB</span>. <span class="country">INDIA</span> </p>
                            <p>Tax: <span class="tax">1234567890</span></p>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Credit Days</label>
                                <input type="text" class="form-control credit_days" name="credit_days"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Credit Limit</label>
                                <input type="text" class="form-control credit_limit" readonly="readonly"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-addorder">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Description</th>
                                        <th class="width-100 text-right">Qty.</th>
                                        <th class="text-center">Unit</th>
                                        <th class="width-100 text-right">Rate(<i class="fa fa-inr"></i>)</th>
                                        <th class="width-100">Tax(%)</th>
                                        <th class="width-120 text-right">Tax Value(<i class="fa fa-inr"></i>)</th>
                                        <th class="width-150 text-right">Total(<i class="fa fa-inr"></i>)</th>
                                        <th class="width-150 text-right">Total(<i class="fa fa-inr"></i>)</th>
                                    </tr>
                                </thead>
                                <tbody class="product-listing-table">

                                    <tr class="product-search-row">                                    
                                        <td colspan="10" style="padding: 2px !important">
                                            <input type="text" class="form-control search_product" placeholder="Search product..."/>
                                            <input type="hidden" class="search_product_id" name="search_product_id[]"/>
                                        </td>
                                    </tr> 
                                    <tr class="sub-total" style="border-bottom: 1px solid #e5e5e5; background: #f1f1f1;">
                                        <td colspan="2"></td>                                            
                                        <td class="text-right" id="quantity">0</td>
                                        <td class="text-right" id="unit"></td>
                                        <td colspan="3"><input type="text" name="tax_value" id="tax_value" class="form-control text-right" readonly="readonly" value="0.00"/></td>
                                        <td class="text-right" id="product_total">0.00</td>
                                        <td><input type="text" name="product_grand_total" id="product_grand_total" class="form-control text-right" readonly="readonly" value="0.00"/></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>

                    </div>


                    <div class="col-md-7 col-sm-6 col-xs-12"></div>

                    <div class="col-md-5 col-sm-6 col-xs-12">                                
                        <table class="table table-addorder-total discounts-table">
                            <tbody>
                                <tr class="discounts-tr">
                                    <td>
                                        <input type="text" class="form-control discount_allowed_ledger" style="height: 30px; max-width: 150px; min-height: 30px;" name="tr_ledger[]"/>
                                        <input type="hidden" class="discount_allowed_ledger_hidden" name="tr_ledger_id[]"/>
                                        <input type="hidden" class="discount_acc_type_hidden" name="tr_type[]"/>  
                                    </td>
                                    <td>
                                        <input type="text" class="form-control discount_allowed_input" style="min-height: 30px; height: 30px; text-align: right;" value="0.00" disabled="disabled"/>
                                        <input type="hidden" class="discount_value_hidden" name="discount_value_hidden[]"/>
                                    </td>
                                </tr>

                            </tbody>
                        </table>  
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-addorder-grandtotal">
                                <tbody>
                                    <tr>                                            
                                        <td>Rupees <strong>Fifty nine thousand eight hundred fifty only</strong> </td>                                            
                                        <td class="text-right width-50">3</td>                                                    
                                        <td class="text-right" style="width:480px">
                                            <input type="text" name="netTotal" id="netTotal" class="form-control text-right" readonly="readonly" value="0.00"/>
                                        </td>
                                    </tr>                                                
                                </tbody>
                            </table>
                        </div>                                    
                    </div> 



                </div>

                <div class="row">
                    <div class="col-md-10" style="margin: 0 9%;">
                        <label style="padding-top: 10px">Notes</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="notes" placeholder="Write notes, if any... " style="padding: 2%;"/>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12" style="margin: 0 9%;">
                        <div class="terms clearfix">
                            <label>Terms &amp; Conditions:</label>
                            <input type="text" class="form-control" name="terms_and_conditions" style="margin-top: 10px; width: 82%; padding: 2%" value="Goods once sold cannot be returned except manufacturing defects. Lorem ipsum dolor sit amet  Goods once sold cannot be returned except manufacturing defects.Lorem ipsum dolor sit amet"/>
                        </div>
                    </div> 

                    <div class="col-md-12 text-center">
                        <hr>
                        <p style="font-size: 90%; color: #888">This is a computer generated invoice. Hence signature is not required.</p>
                    </div> 
                </div>  

                <div class="footer-button" style="padding: 2% 9%; display: none;">
                    <input type="submit" class="btn btn-primary ladda-button" data-size="s" id="totalFormSubmitBtn" value="Save">
                </div>


            </div><!-- /.box-body -->            
    </div><!-- /.box -->

</section>
</div><!-- /.content-wrapper -->

</form>



<div id="trackingModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm" style="top: 70px;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">    
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="tr_tracking_ledger_name"></h4>
                <input type="hidden" value="" id="tr_tracking_ledger_id_hidden" name="tr_tracking_ledger_id_hidden">
                <input type="hidden" class="tr_acc_type_hidden">
            </div>
            <div class="modal-body">
                <form class="form" role="form" id="tr_tracking_form">              
                    <!-- set 1-->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-7"><label>Tracking Name</label></div>
                            <div class="col-xs-5"><label>Amount</label></div>              
                        </div> 
                    </div>  

                    <div id="tracking_container">  
                        <div class="form-group tracking-form-group">
                            <div class="row tr_tracking_row" id="1">
                                <div class="col-xs-7">
                                    <input type="text" name="tr_tracking_name_modal[]" class="form-control tr_tracking_name_modal">
                                    <input type="hidden" class="tr_tracking_id_modal" name="tr_tracking_id_modal[]">
                                </div>
                                <div class="col-xs-5">
                                    <input type="text" name="tr_tracking_amount_modal[]"  class="form-control tr_tracking_amount_modal">
                                </div>              
                            </div> 
                        </div>

                    </div>  


                </form>        
            </div> 
            <div class="modal-footer">            
                <div class="row">
                    <div class="col-xs-7 text-right"><label>Total</label></div>
                    <div class="col-xs-5"><input type="text" class="form-control" id="tr_total_sub_tracking" readonly="readonly"></div>              
                </div>            
            </div>  
        </div>

    </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/account/js/inputMaskBundle.min.js"></script>

<script type="text/javascript">


    $(document).ready(function () {

        var entry_no = $('.entry_no');
        var date = $('#tr_date');
        var entry_debtors = $('.entry_debtors');




        setTimeout(function () {

            if (!entry_no.prop('readonly')) {
                $('.entry_no').focus();
            } else if (!date.prop('readonly')) {
                $('#tr_date').focus();
            } else if (!entry_debtors.prop('readonly')) {
                $('.entry_debtors').focus();
            }

            console.log("adasdsada");

        }, 2000);



    });



    var LS_tracking = [];
    var LS_tracking_get = [];
    var transaction_type_id = <?php echo $transaction_type_id; ?>;

    function generateNewRowTrackingModal(diff, callback) {

        var $trNum = $('.tr_tracking_row:last').attr('id');
        var num = parseInt($trNum) + 1;

        var $tr = $('.tr_tracking_row:last').closest('.form-group');
        var $klon = $tr.clone();

        // Finally insert $klon wherever you want


        $tr.after($klon);


        setTimeout(function () {
            $('.tr_tracking_row:last').prop('id', num);
            $('.tr_tracking_row:last .tr_tracking_name_modal').val("");
            $('.tr_tracking_row:last .tr_tracking_name_modal').focus();
            callback();
        }, 500);




    }



    function generateNewDiscountRow(callback) {

        var $tr = $('.discounts-tr:last');


        var $klon = $tr.clone();

        // Finally insert $klon wherever you want


        $tr.after($klon);


        setTimeout(function () {

            $('.discounts-tr:last .discount_allowed_ledger').val("");
            $('.discounts-tr:last .discount_allowed_input').val("");
            $('.discounts-tr:last .discount_acc_type_hidden').val("");
            $('.discounts-tr:last .discount_allowed_ledger_hidden').val("");
            $('.discounts-tr:last .discount_value_hidden').val("");

            $('.discounts-tr:last .discount_allowed_ledger').focus();
            callback();
        }, 500);

    }


    /* Generate new product row */

    function generateNewProductRow(pId, stockId, productName, price, taxPerc, productUnitName, productUnitHiddenId, callback) {

        // get the last DIV which ID starts with ^= "klon"

        $productDiv = '<tr class="each-product">' +
                '<td><button class="btn btn-xs btn-danger removeitem"><i class="fa fa-trash-o"></i></button>' +
                '<input type="hidden" class="product_id_hidden" name="product_id[]"><input type="hidden" class="stock_id_hidden" name="stock_id[]">' +
                '</td>' +
                '<td><input type="text" class="product-name text-left form-control" name="product_name[]"/></td>' +
                '<td><input type="text" class="form-control text-right product-quantity" value="1" name="product_quantity[]"/></td>' +
                '<td><input type="text" class="form-control product-unit text-center" readonly="readonly" name="product_unit[]"/><input type="hidden" class="product-unit-hidden-id" name="product_unit_hidden_id"></td>' +
                '<td><input type="text" class="form-control text-right product-price" name="product_price[]"/></td>' +
                '<td><input type="text" class="tax-percent form-control" name="tax_percent[]" readonly="readonly"/></td>' +
                '<td><input type="text" readonly="readonly" class="tax-value form-control text-right" name="tax_value[]"/></td>' +
                '<td><input type="text" readonly="readonly" class="total-price-per-prod form-control text-right" name="total_price_per_prod[]"/></td>' +
                '<td><input type="text" readonly="readonly" class="total-price-per-prod-with-tax form-control text-right" name="total_price_per_prod_with_tax[]"/></td>' +
                '</tr>';

        var taxVal = parseFloat((taxPerc / 100) * price);
        var totalProductPrice = parseFloat(taxVal + price);

        var target = $('.product-listing-table .product-search-row');

        target.before($productDiv);

        setTimeout(function () {

            var $lastProduct = $('.product-listing-table tr.each-product:last');
            // $lastProduct.prop('id', 1);
            $lastProduct.find('.product_id_hidden').val(pId);
            $lastProduct.find('.stock_id_hidden').val(stockId);
            $lastProduct.find('.product-name').val(productName);
            $lastProduct.find('.product-price').val(price);
            $lastProduct.find('.product-unit').val(productUnitName);
            $lastProduct.find('.product-unit-hidden-id').val(productUnitHiddenId);
            $lastProduct.find('.tax-percent').val(taxPerc);
            $lastProduct.find('.tax-value').val(taxVal.toFixed(2));
            $lastProduct.find('.total-price-per-prod').val(price.toFixed(2));
            $lastProduct.find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));
            callback();
        }, 500);




    }


    function calculateTotal(callback) {

        var subTotal = 0.00;
        var subTotalOnlyProduct = 0.00;
        var GST = 0.00;
        var netTotal = 0.00;
        var totalQ = 0;
        var discountAllowed = $('.discount_allowed_input').val();

        // var taxValue = 0;


        $('.each-product').each(function () {


            var taxValue = $(this).find('.tax-value').val();
            var productTotal = $(this).find('.total-price-per-prod').val();
            var productTotalWithTax = $(this).find('.total-price-per-prod-with-tax').val();

            var quantity = $(this).find('.product-quantity').val();



            subTotalOnlyProduct = parseFloat(subTotalOnlyProduct) + parseFloat(productTotal);
            subTotal = parseFloat(subTotal) + parseFloat(productTotalWithTax);
            GST = parseFloat(GST) + parseFloat(taxValue);
            totalQ = parseInt(totalQ) + parseInt(quantity);


            netTotal = subTotal;



            /* If there's a discount then negate that */




        });

        $('#netTotal').val(netTotal);
        $("#product_grand_total").val(subTotal);


        setTimeout(function () {


            calculateDiscount(function (calculatedRounding) {




                /* Show subtotal, GST and total amount */

                console.log("subtot : ", subTotal);
                console.log("gst: ", GST);
                console.log("netTotal ", netTotal);
                console.log("rounding cal ", calculatedRounding);



                callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2), GST.toFixed(2), parseInt(totalQ), calculatedRounding);


            });

        }, 1000);


    }



    function calculateDiscount(callback) {

        var netTotal = $('#netTotal').val();
        var subTotal = $('#product_grand_total').val();
        var calculatedRounding = subTotal;




        /* check if it's a percentage or not  */

        $('.discounts-tr').each(function () {


            /*  Check for a/c type and value in textbox */
            var self = $(this);
            var acc_type = $(this).find('.discount_acc_type_hidden').val();
            var valDis = $(this).find('.discount_allowed_input').val();

            if (valDis != "" && $.trim(parseFloat(valDis)) != 0.00) {

                /* first check for percentage */

                var checkLastChar = valDis.charAt(valDis.length - 1);

                if ($.trim(checkLastChar) == "%") {

                    var percentageOf = valDis.substr(0, valDis.indexOf('%'));
                    var recentCalculations = parseFloat(percentageOf / 100) * parseFloat(calculatedRounding);

                    console.log("hello perc hit");
                    console.log("perce nos", percentageOf);
                    console.log("recentCalculation ", recentCalculations);

                    setTimeout(function () {
                        calculatedRounding = parseFloat(calculatedRounding) - parseFloat(recentCalculations);


                        self.find('.discount_acc_type_hidden').val('Dr');
                        self.find('.discount_value_hidden').val(recentCalculations);

                    }, 500);



                } else {

                    /* check for dr or cr */

                    if (acc_type == "Dr") {
                        // calculatedRounding = parseFloat(calculatedRounding) - parseFloat(valDis);
                        // self.find('.discount_value_hidden').val(valDis);

                        var firstChar = valDis.charAt(0);

                        if ($.trim(firstChar) != "-") {
                            /* debit */
                            calculatedRounding = parseFloat(calculatedRounding) - parseFloat(valDis);
                            self.find('.discount_acc_type_hidden').val('Dr');
                            self.find('.discount_value_hidden').val(valDis);

                            console.log("calround ", calculatedRounding);

                        } else if ($.trim(firstChar) == "-") {
                            /* credit */
                            //alert("pos 1");

                            var exactVal = Math.abs(parseFloat(valDis));
                            calculatedRounding = parseFloat(calculatedRounding) + parseFloat(exactVal);
                            self.find('.discount_acc_type_hidden').val('Cr');
                            self.find('.discount_value_hidden').val(exactVal);

                            console.log("calround ", calculatedRounding);

                        }



                    } else if (acc_type == "Cr") {
                        // calculatedRounding = parseFloat(calculatedRounding) + parseFloat(valDis);
                        // self.find('.discount_value_hidden').val(valDis);

                        var firstChar = valDis.charAt(0);

                        if ($.trim(firstChar) != "-") {
                            /* debit */
                            calculatedRounding = parseFloat(calculatedRounding) - parseFloat(valDis);
                            self.find('.discount_acc_type_hidden').val('Dr');
                            self.find('.discount_value_hidden').val(valDis);

                            console.log("calround ", calculatedRounding);

                        } else if ($.trim(firstChar) == "-") {
                            /* credit */
                            //alert("pos 2");

                            var exactVal = Math.abs(parseFloat(valDis));
                            calculatedRounding = parseFloat(calculatedRounding) + parseFloat(exactVal);
                            self.find('.discount_acc_type_hidden').val('Cr');
                            self.find('.discount_value_hidden').val(exactVal);

                            console.log("calround ", calculatedRounding);

                        }

                    } else if (acc_type != "Dr" && acc_type != "Cr") {

                        /* check for negative */
                        console.log("got here");

                        var firstChar = valDis.charAt(0);

                        if ($.trim(firstChar) != "-") {
                            /* debit */
                            calculatedRounding = parseFloat(calculatedRounding) - parseFloat(valDis);
                            self.find('.discount_acc_type_hidden').val('Dr');
                            self.find('.discount_value_hidden').val(valDis);

                            console.log("calround ", calculatedRounding);

                        } else if ($.trim(firstChar) == "-") {
                            /* credit */

                            //alert("pos 5"); 

                            var exactVal = Math.abs(parseFloat(valDis));
                            calculatedRounding = parseFloat(calculatedRounding) + parseFloat(exactVal);
                            self.find('.discount_acc_type_hidden').val('Cr');
                            self.find('.discount_value_hidden').val(exactVal);

                            console.log("calround ", calculatedRounding);

                        }

                    }



                }

            }




        });


        setTimeout(function () {
            callback(calculatedRounding);
        }, 1000);




    }




    $(function () {


        $('.product-listing-table').delegate('.product-price', 'keydown', function (e) {
            var self = $(this);

            var quantity = self.closest('tr').find('.product-quantity').val();
            var tax = self.closest('tr').find('.tax-percent').val();

            // if (e.keyCode > 31 && (e.keyCode < 48 || e.keyCode > 57) && (e.keyCode != 39 && e.keyCode != 37)) {
            //     return false;
            // }else{

            if (e.which == 13) {

                setTimeout(function () {

                    var value = self.val();

                    console.log("value : ", value);

                    var totalPrPriceQuantity = parseFloat(value * quantity);
                    var taxVal = parseFloat((tax / 100) * totalPrPriceQuantity);
                    var totalProductPrice = parseFloat(taxVal + totalPrPriceQuantity);


                    self.closest('tr').find('.tax-value').val(taxVal.toFixed(2));
                    self.closest('tr').find('.total-price-per-prod').val(totalPrPriceQuantity.toFixed(2));
                    self.closest('tr').find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));

                    calculateTotal(function (subTotal, subTotalOnlyProduct, GST, q, netTotal) {
                        $('#product_grand_total').val(subTotal);
                        $('#tax_value').val(GST);
                        $('#product_total').text(subTotalOnlyProduct);

                        $('#quantity').text(q);
                        $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                        //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                    });


                }, 500);



            }

        });

        $('.product-listing-table').delegate('.removeitem', 'click', function () {

            $(this).closest('.each-product').remove();
            calculateTotal(function (subTotal, subTotalOnlyProduct, GST, q, netTotal) {
                $('#product_grand_total').val(subTotal);
                $('#tax_value').val(GST);
                $('#product_total').text(subTotalOnlyProduct);

                $('#quantity').text(q);
                $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
            });



        })



        $('.product-listing-table').delegate('.product-quantity', 'keydown', function (e) {



            var self = $(this);

            var eachProductPrice = self.closest('tr').find('.product-price').val();
            var tax = self.closest('tr').find('.tax-percent').val();

            console.log("1st ", eachProductPrice);
            console.log("2nd ", tax);



            if (e.keyCode == "38") {
                e.preventDefault();

                var value = self.val();
                value++;
                self.val(value);

                var totalPrPriceQuantity = parseFloat(eachProductPrice * value);
                var taxVal = parseFloat((tax / 100) * totalPrPriceQuantity);
                var totalProductPrice = parseFloat(taxVal + totalPrPriceQuantity);

                self.closest('tr').find('.tax-value').val(taxVal.toFixed(2));
                self.closest('tr').find('.total-price-per-prod').val(totalPrPriceQuantity.toFixed(2));
                self.closest('tr').find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));

                calculateTotal(function (subTotal, subTotalOnlyProduct, GST, q, netTotal) {
                    $('#product_grand_total').val(subTotal);
                    $('#tax_value').val(GST);
                    $('#product_total').text(subTotalOnlyProduct);

                    $('#quantity').text(q);
                    $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                    //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                });




            } else if (e.keyCode == "40") {
                e.preventDefault();
                var value = self.val();
                value--;

                if (self.val() > 1) {

                    self.val(value);

                    var totalPrPriceQuantity = parseFloat(eachProductPrice * value);
                    var taxVal = parseFloat((tax / 100) * totalPrPriceQuantity);
                    var totalProductPrice = parseFloat(taxVal + totalPrPriceQuantity);


                    self.closest('tr').find('.tax-value').val(taxVal.toFixed(2));
                    self.closest('tr').find('.total-price-per-prod').val(totalPrPriceQuantity.toFixed(2));
                    self.closest('tr').find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));





                    calculateTotal(function (subTotal, subTotalOnlyProduct, GST, q, netTotal) {
                        $('#product_grand_total').val(subTotal);
                        $('#tax_value').val(GST);
                        $('#product_total').text(subTotalOnlyProduct);

                        $('#quantity').text(q);
                        $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                        //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                    });

                } else {
                    return;
                }


            } else if (e.keyCode > 31 && (e.keyCode < 48 || e.keyCode > 57) && (e.keyCode != 39 && e.keyCode != 37)) {
                return false;
            } else {

                setTimeout(function () {

                    var value = self.val();

                    console.log("  value : ", value);

                    var totalPrPriceQuantity = parseFloat(eachProductPrice * value);
                    var taxVal = parseFloat((tax / 100) * totalPrPriceQuantity);
                    var totalProductPrice = parseFloat(taxVal + totalPrPriceQuantity);


                    self.closest('tr').find('.tax-value').val(taxVal.toFixed(2));
                    self.closest('tr').find('.total-price-per-prod').val(totalPrPriceQuantity.toFixed(2));
                    self.closest('tr').find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));

                    calculateTotal(function (subTotal, subTotalOnlyProduct, GST, q, netTotal) {
                        $('#product_grand_total').val(subTotal);
                        $('#tax_value').val(GST);
                        $('#product_total').text(subTotalOnlyProduct);

                        $('#quantity').text(q);
                        $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                        //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                    });


                }, 500);



            }

        });



        $('body').delegate('.product-name', 'focusin', function () {

            var self = $(this);

            var shippingCountry = $('.in_ledger_country').val();
            var shippingState = $('.in_ledger_state').val();

            $(this).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/accounts_inventory/getProducts',
                        data: "term=" + request.term,
                        dataType: "json",
                        success: function (data) {
                            response(data);
                            //console.log(data);
                        },
                        error: function (request, error) {
                            //alert('connection error. please try again.');
                            console.log(error);
                        }
                    });
                },
                minLength: 0,
                select: function (e, ui) {
                    var self = $(this);
                    e.preventDefault(); // <--- Prevent the value from being inserted.
                    $(this).val(ui.item.label); // display the selected text

                    var pId = ui.item.value;
                    var productName = ui.item.label;

                    $(this).next(".search_product_id").val(ui.item.value); // save selected id to hidden input

                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/accounts_inventory/getProductDetails',
                        data: "pId=" + pId + "&shippingcountry=" + shippingCountry + "&shippingstate=" + shippingState,
                        dataType: "json",
                        success: function (data) {
                            // response(data);
                            console.log(data);


                            var price = parseFloat(data['productPrice']);
                            var taxPerc = parseFloat(data['productTax']);
                            var productUnitName = $.trim(data['productUnitName']);
                            var productUnitHiddenId = data['productUnitId'];
                            var stockId = data['stockId'];

                            console.log(" unit name ", productUnitName, " id ", productUnitHiddenId);

                            var taxVal = parseFloat((taxPerc / 100) * price);
                            var totalProductPrice = parseFloat(taxVal + price);


                            self.closest('.each-product').find('.product_id_hidden').val(pId);
                            self.closest('.each-product').find('.stock_id_hidden').val(stockId);
                            self.closest('.each-product').find('.product-name').val(productName);
                            self.closest('.each-product').find('.product-price').val(price);
                            self.closest('.each-product').find('.product-quantity').val(1);
                            self.closest('.each-product').find('.product-unit').val(productUnitName);
                            self.closest('.each-product').find('.product-unit-hidden-id').val(productUnitHiddenId);
                            self.closest('.each-product').find('.tax-percent').val(taxPerc);
                            self.closest('.each-product').find('.tax-value').val(taxVal.toFixed(2));
                            self.closest('.each-product').find('.total-price-per-prod').val(price.toFixed(2));
                            self.closest('.each-product').find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));


                            calculateTotal(function (subTotal, subTotalOnlyProduct, GST, q, netTotal) {
                                $('#product_grand_total').val(subTotal);
                                $('#tax_value').val(GST);
                                $('#product_total').text(subTotalOnlyProduct);

                                $('#quantity').text(q);
                                $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                                //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                            });

                        },
                        error: function (request, error) {
                            //alert('connection error. please try again.');
                            console.log(error);
                        }
                    });

                }

            });

        });





        $('body').delegate('.search_product', 'focusin', function () {

            var self = $(this);

            var shippingCountry = $('.in_ledger_country').val();
            var shippingState = $('.in_ledger_state').val();

            $(this).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/accounts_inventory/getProducts',
                        data: "term=" + request.term,
                        dataType: "json",
                        success: function (data) {
                            response(data);
                            //console.log(data);
                        },
                        error: function (request, error) {
                            //alert('connection error. please try again.');
                            console.log(error);
                        }
                    });
                },
                minLength: 0,
                select: function (e, ui) {
                    var self = $(this);
                    e.preventDefault(); // <--- Prevent the value from being inserted.
                    $(this).val(ui.item.label); // display the selected text

                    var pId = ui.item.value;
                    var productName = ui.item.label;

                    //$(this).next(".search_product_id").val(ui.item.value); // save selected id to hidden input

                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/accounts_inventory/getProductDetails',
                        data: "pId=" + pId + "&shippingcountry=" + shippingCountry + "&shippingstate=" + shippingState,
                        dataType: "json",
                        success: function (data) {
                            // response(data);
                            console.log(data);


                            var price = parseFloat(data['productPrice']);
                            var taxPerc = parseFloat(data['productTax']);
                            var productUnitName = $.trim(data['productUnitName']);
                            var productUnitHiddenId = data['productUnitId'];
                            var stockId = data['stockId'];

                            console.log(" unit name ", productUnitName, " id ", productUnitHiddenId);

                            generateNewProductRow(pId, stockId, productName, price, taxPerc, productUnitName, productUnitHiddenId, function () {

                                console.log("new row generated!");
                                var $lastProduct = $('.product-listing-table tr.each-product:last');

                                $lastProduct.find('.product-quantity').focus();

                                calculateTotal(function (subTotal, subTotalOnlyProduct, GST, q, netTotal) {
                                    $('#product_grand_total').val(subTotal);
                                    $('#tax_value').val(GST);
                                    $('#product_total').text(subTotalOnlyProduct);

                                    $('#quantity').text(q);
                                    $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                                    //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                                });

                                self.val("");

                            });


                            /* Generate new product row with values  */



                        },
                        error: function (request, error) {
                            //alert('connection error. please try again.');
                            console.log(error);
                        }
                    });

                }

            });

        });   // autocomplete 



        $('body').delegate('.entry_debtors', 'focusin', function () {

            $(this).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/accounts_inventory/getLedgerDebtors',
                        data: "ledger=" + request.term,
                        dataType: "json",
                        success: function (data) {
                            response(data);
                            //console.log(data);
                        },
                        error: function (request, error) {
                            //alert('connection error. please try again.');
                            console.log(error);
                        }
                    });
                },
                minLength: 0,
                select: function (e, ui) {
                    var self = $(this);
                    e.preventDefault(); // <--- Prevent the value from being inserted.
                    $(this).val(ui.item.label); // display the selected text
                    $(this).next(".tr_ledger_id_debtors").val(ui.item.value); // save selected id to hidden input

                    var ledgerId = ui.item.value;

                    /* Get shipping country and state  */

                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/accounts_inventory/getShippingDetails',
                        data: "ledger=" + ledgerId,
                        dataType: "json",
                        success: function (data) {
                            // response(data);
                            console.log(data);



                            $('.in_ledger_state').val(data.state);
                            $('.in_ledger_country').val(data.country);
                            $('.tr_type_debtors').val(data.transactionType);



                            $('.billing_addr .c_name').text(data.Bi_companyName);
                            $('.billing_addr .addr').text(data.Bi_address);
                            $('.billing_addr .city').text(data.Bi_city);
                            $('.billing_addr .zip').text(data.Bi_zip);
                            $('.billing_addr .state').text(data.Bi_state);
                            $('.billing_addr .country').text(data.Bi_country);
                            $('.billing_addr .tax').text(data.Bi_tax);

                            $('.shipping_addr .c_name').text(data.Sh_companyName);
                            $('.shipping_addr .addr').text(data.Sh_address);
                            $('.shipping_addr .city').text(data.Sh_city);
                            $('.shipping_addr .zip').text(data.Sh_zip);
                            $('.shipping_addr .state').text(data.Sh_state);
                            $('.shipping_addr .country').text(data.Sh_country);
                            $('.shipping_addr .tax').text(data.Sh_tax);


                            $('.credit_days').val(data.LL_creditDays);
                            $('.credit_limit').val(data.LL_creditLimit);

                            $('.debtors_details').show();





                        },
                        error: function (request, error) {
                            //alert('connection error. please try again.');
                            console.log(error);
                        }
                    });


                }

            }); // autocomplete 

        });



        $('body').delegate('.entry_sales', 'focusin', function () {

            $(this).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/accounts_inventory/getLedgerSales',
                        data: "ledger=" + request.term,
                        dataType: "json",
                        success: function (data) {

                            response(data);
                            console.log(data);
                        },
                        error: function (request, error) {
                            //alert('connection error. please try again.');
                            console.log(error);
                        }
                    });
                },
                minLength: 0,
                select: function (e, ui) {
                    var self = $(this);
                    e.preventDefault(); // <--- Prevent the value from being inserted.
                    $(this).val(ui.item.label); // display the selected text
                    // save selected id to hidden input
                    $(this).next(".tr_ledger_id_sales").val(ui.item.value);


                    var dataLType = {'action': 'get-ledger-extra-details', ledgerId: ui.item.value};
                    // console.log("dataLtype ========= ", dataLType);

                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/admin/getLedgerExtraDetails',
                        data: dataLType,
                        dataType: "json",
                        success: function (data) {

                            $('.tr_type_creditors').val(data.transactionType);


                        }})


                }

            }); // autocomplete 

        });




        /** Discount Allowed Ledger **/

        $('body').delegate('.discount_allowed_ledger', 'focusin', function () {

            $(this).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>transaction/accounts_inventory/getLedgerRounding',
                        data: "ledger=" + request.term,
                        dataType: "json",
                        success: function (data) {
                            response(data);
                            console.log(data);
                        },
                        error: function (request, error) {
                            //alert('connection error. please try again.');
                            console.log(error);
                        }
                    });
                },
                minLength: 0,
                select: function (e, ui) {
                    var self = $(this);
                    e.preventDefault(); // <--- Prevent the value from being inserted.
                    $(this).val(ui.item.label); // display the selected text
                    // save selected id to hidden input
                    $(this).next(".discount_allowed_ledger_hidden").val(ui.item.value);

                    $(this).closest('tr').find('.discount_allowed_input').attr('disabled', false).val("");

                    setTimeout(function () {
                        self.closest('tr').find('.discount_allowed_input').focus();
                    }, 500);

                }

            }); // autocomplete 

        });



    }); // onready





    $(document).on('keypress', 'input,select', function (e) {
        if (e.which == 13) {
            e.preventDefault();

            var selfInput = $(this);

            if ($(this).hasClass('discount_allowed_input')) {


                calculateDiscount(function (calculatedRounding) {


                    setTimeout(function () {
                        $('#netTotal').val(parseFloat(calculatedRounding).toFixed(2));

                        checkTrackingForDiscount(selfInput, function () {
                            console.log("tracking discount checked!");

                        });

                        if ($.trim($('.discounts-tr:last .discount_allowed_ledger_hidden').val()) != "" && $.trim($('.discounts-tr:last .discount_value_hidden').val()) != "") {

                            generateNewDiscountRow(function () {
                                console.log("new row has been created");
                            });



                        } else {
                            $('.footer-button').show();
                        }


                    }, 500);


                });

            }


            var $canfocus = $(':focusable:not([readonly])');
            var index = $canfocus.index(document.activeElement) + 1;
            if (index >= $canfocus.length)
                index = 0;
            $canfocus.eq(index).focus();


        }


    });


    $('#trackingModal').on('keypress', '.tr_tracking_amount_modal', function (e) {
        if (e.which == 13) {

            var self = $(this);
            /*  check if tracking name is blank */

            if ($.trim(self.closest('.tr_tracking_row').find('.tr_tracking_name_modal').val()) == "") {

                alert("Please select a tracking name");
                return;

            } else {

                /* check total amounts are equal  */
                var totalSum = 0;
                var actualSum = $('#trackingModal #tr_total_sub_tracking').val();

                $('#trackingModal .tr_tracking_row').each(function () {

                    if ($(this).find(".tr_tracking_amount_modal").val() != "") {
                        totalSum = totalSum + parseFloat($(this).find(".tr_tracking_amount_modal").val());
                    }


                });



                if (parseFloat(totalSum) == parseFloat(actualSum)) {



                    if (self.closest('.tracking-form-group').next('.tracking-form-group').length == 0) {

                        var trackingAccType = $('#trackingModal .tr_acc_type_hidden').val();

                        /*  Insert all the datas into local storage */

                        var getLedgerId = $("#trackingModal #tr_tracking_ledger_id_hidden").val();
                        var LedgerTracking = localStorage.getItem('ledgerTrackingId' + getLedgerId);

                        if (LedgerTracking != null) {
                            localStorage.removeItem('ledgerTrackingId' + getLedgerId);
                        }

                        LS_tracking.length = 0;


                        $('#trackingModal .tr_tracking_row').each(function () {

                            var newItem = {
                                'tracking_name': $(this).find(".tr_tracking_name_modal").val(),
                                'tracking_amount': $(this).find(".tr_tracking_amount_modal").val(),
                                'tracking_id': $(this).find(".tr_tracking_id_modal").val(),
                                'account_type': trackingAccType
                            };


                            LS_tracking.push(newItem);

                        });


                        localStorage.setItem('ledgerTrackingId' + getLedgerId, JSON.stringify(LS_tracking));


                        /* HIDE AND DELETE */

                        $('#trackingModal').modal('hide');

                        setTimeout(function () {

                            $('.discounts-tr:last .discount_allowed_ledger').focus();


                        }, 1500);

                        /* remove modal name + hidden id */

                        $('#trackingModal #tr_tracking_ledger_name').html("");
                        $('#trackingModal #tr_tracking_ledger_id_hidden').val("");

                        /* remove all modal fields except 1st */

                        $('#trackingModal .tr_tracking_row').each(function () {
                            if (parseInt($(this).attr('id')) > 1) {
                                $(this).closest('.form-group').remove();
                            } else if (parseInt($(this).attr('id')) == 1) {
                                $(this).find('.tr_tracking_name_modal').val("");
                                $(this).find('.tr_tracking_amount_modal').val("");
                            }

                        });



                    }



                } else if (parseFloat(actualSum) > parseFloat(totalSum)) {

                    var diff = parseFloat(actualSum) - parseFloat(totalSum);

                    /* generate new field */
                    generateNewRowTrackingModal(diff, function () {
                        $('.tr_tracking_row:last .tr_tracking_amount_modal').val(diff);
                    });

                } else if (parseFloat(actualSum) < parseFloat(totalSum)) {
                    alert("Please check all the values correctly!");
                    return;
                }



            }
        }
    });



    function checkTrackingForDiscount(self, callback) {

        var ledgerId = self.closest('tr').find('.discount_allowed_ledger_hidden').val();
        // var ledgerAccType = self.closest('.tr_row_ledger').find('.tr_type').val(); 

        var dataLType = {'action': 'get-ledger-extra-details', ledgerId: ledgerId};

        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>transaction/admin/getLedgerExtraDetails',
            data: dataLType,
            dataType: "json",
            success: function (data) {

                console.log(data);

                var ledgerAmt = self.closest('.discounts-tr').find('.discount_value_hidden').val();

                if (data.hasTracking == "1") {
                    $('#trackingModal').modal('show');


                    setTimeout(function () {
                        $('#trackingModal .tr_tracking_row:first .tr_tracking_name_modal').focus();
                    }, 1500);

                    $('#trackingModal #tr_tracking_ledger_name').html(data.ledgerName);
                    $('#trackingModal #tr_tracking_ledger_id_hidden').val(ledgerId);
                    // $('#trackingModal .tr_acc_type_hidden').val(ledgerAccType);




                    /** Get tracking details of this ledger from local storage **/

                    var LedgerTracking = localStorage.getItem('ledgerTrackingId' + ledgerId);

                    if (LedgerTracking == null) {
                        /* Insert the value of the ledger amount */
                        $('.tr_tracking_row:last .tr_tracking_amount_modal').val(ledgerAmt);
                        $('#trackingModal #tr_total_sub_tracking').val(ledgerAmt);
                    } else {



                        $('#trackingModal #tr_total_sub_tracking').val(ledgerAmt);

                        LS_tracking.length = 0;
                        var LS_found = JSON.parse(LedgerTracking);
                        console.log("length : ", LS_found.length);

                        $('#trackingModal .tr_tracking_row').each(function () {
                            $(this).closest('.form-group').remove();
                        });

                        for (var i = 1; i <= LS_found.length; i++) {

                            var element = '<div class="form-group">' +
                                    '<div class="row tr_tracking_row" id="' + i + '">' +
                                    '<div class="col-xs-7">' +
                                    '<input type="text" name="tr_tracking_name_modal[]" class="form-control tr_tracking_name_modal" value=' + LS_found[i - 1].tracking_name + '>' +
                                    '<input type="hidden" class="tr_tracking_id_modal" name="tr_tracking_id_modal[]" value=' + LS_found[i - 1].tracking_id + '>' +
                                    '</div>' +
                                    '<div class="col-xs-5">' +
                                    '<input type="text" name="tr_tracking_amount_modal[]"  class="form-control tr_tracking_amount_modal" value=' + LS_found[i - 1].tracking_amount + '>' +
                                    '</div></div></div>';


                            $('#tracking_container').append(element);
                            //console.log("things :", LS_found[i-1]);

                        }



                        $('#trackingModal').modal('show');
                        console.log("DISPLAY DATA OF LOCAL STORAGE ", LS_found);





                    }


                    callback();


                }
            }});



    }



    $("#tr_date").inputmask("d/m/y", {placeholder: "dd/mm/yyyy", "oncomplete": function () {
            $("#Text1").focus();
        }
    });


    $('#tr_date').bind('keyup', 'keydown', function (e) {
        $.post("<?php echo base_url(); ?>transaction/accounts_inventory/checkDate", {date: e.target.value}, function (data) {
            if (data.res) {
                $("#tr_date").val(data.date);
            }
        }, "json");
    });







    $(document).ready(function () {

        $('#totalFormSubmitBtn').on('keypress', function (e) {
            if (e.which == 13) {

                $(".formSubmitAll").submit();


            }

        });

    });


    function getAllStorageItems(callback) {

        /* first for debters */

        var LS_data_tracking;

        var ledgerIdDebit = $('.tr_ledger_id_debtors').val();
        LS_data_tracking = localStorage.getItem('ledgerTrackingId' + ledgerIdDebit);

        if (LS_data_tracking !== null) {
            var tracking_data = {'ledgeId': ledgerIdDebit, value: LS_data_tracking};
            LS_tracking_get.push(tracking_data);

        }

        var ledgerIdCredit = $('.tr_ledger_id_sales').val();
        LS_data_tracking = localStorage.getItem('ledgerTrackingId' + ledgerIdCredit);

        if (LS_data_tracking !== null) {
            var tracking_data = {'ledgeId': ledgerIdCredit, value: LS_data_tracking};
            LS_tracking_get.push(tracking_data);

        }



        $('.discounts-tr').each(function () {
            var getLedgeId = $(this).find('.discount_allowed_ledger_hidden').val();

            // check if local storage exists
            LS_data_tracking = localStorage.getItem('ledgerTrackingId' + getLedgeId);

            if (LS_data_tracking !== null) {

                var tracking_data = {'ledgeId': getLedgeId, value: LS_data_tracking};
                LS_tracking_get.push(tracking_data);
            }

        });


        callback(LS_tracking_get);


    }

    function formToJSON(selector) {
        var form = {};

        $(selector).find(':input[name]:enabled').each(function () {
            var self = $(this);
            var name = self.attr('name');
            if (form[name]) {
                form[name] = form[name] + ',' + self.val();
            }
            else {
                form[name] = self.val();
            }
        });

        return form;
    }



    $(".formSubmitAll").submit(function (event) {

        event.preventDefault();
        var self = $(this);

        var newReferenceLedgerArray = [];


        // console.log("ttt ", transaction_type_id);

        var extraFuncFlag = 0;

        setTimeout(function () {

            var newRefCall = 0;

            if (extraFuncFlag >= 2) {
                newRefCall = 1;
            } else {
                newRefCall = 0;
            }


            getAllStorageItems(function (trackingArr) {

                var extra = {tracking: trackingArr, newRefCall: newRefCall, newReferenceLedgerArray: newReferenceLedgerArray, entry_type: transaction_type_id};


                var l = Ladda.create(document.querySelector('.ladda-button'));
                l.start();


                var form = self,
                        url = form.attr('action'),
                        formData = form.serialize() + '&' + $.param(extra);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (data) {

                        l.stop();

                        console.log(data);

                        $('.form-group').removeClass('has-error');

                        if (data.res == 'error') {
                            console.log('error blah');

                            $.each(data.message, function (index, value) {
                                $('#' + index).closest('.form-group').addClass('has-error');
                            });


                        } else if (data.res == 'save_err') {
                            Command: toastr["error"](data.message);
                        } else {

                            Command: toastr["success"](data.message);

                            console.log('success blah');

                            localStorage.clear();

                            // $('.tr_ledger, .tr_type, .tr_dr_amount, .tr_cr_amount').val("");
                            // $('.tr_total_dr, .tr_total_cr').html("");

                            // if( $.trim($('.entry_no').val()) != "Auto" ){
                            //   $('.entry_no').val("");

                            // }

                            // $('.tr_transaction_main .tr_row_ledger').each(function() {
                            //    if( parseInt($(this).attr('id')) > 2){
                            //       $(this).remove();
                            //    }

                            //   });  

                        }


                    }
                });


                console.log("2nd ", formData);

            });


        }, 2000);

    });



</script>



