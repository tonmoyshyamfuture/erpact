
var full_path = window.location.protocol + "//" + window.location.host + '/';


$(document).ready(function() {

    var entry_no = $('.entry_no');
    var date = $('#tr_date');
    var entry_debtors = $('.entry_debtors');
    




    setTimeout(function() {

        if (!entry_no.prop('readonly')) {
            $('.entry_no').focus();
        } else if (!date.prop('readonly')) {
            $('#tr_date').focus();
        } else if (!entry_debtors.prop('readonly')) {
            $('.entry_debtors').focus();
        }

    }, 2000);



});

$(".myButton").click(function() {

    // Set the effect type
    var effect = 'slide';

    // Set the options for the effect type chosen
    var options = {direction: $('.mySelect').val(0)};

    // Set the duration (default: 400 milliseconds)
    var duration = 500;

    $('#myDiv').toggle(effect, options, duration);
});

$('body').delegate('.new-ledger-btn', 'click', function() {
    // Set the effect type
    var effect = 'slide';

    // Set the options for the effect type chosen
    var options = {direction: $('.mySelect').val(0)};

    // Set the duration (default: 400 milliseconds)
    var duration = 300;

    $('#myDiv').toggle(effect, options, duration);
    
    //get contacts
// $('#contact_id').select2({
//    placeholder: "Select contacts",
//   ajax: {
//     url: full_path+'transaction_inventory/inventory/getAllContacts',
//     dataType: 'json',
//     processResults: function (data) {
//       // Tranforms the top-level key of the response object from 'items' to 'results'
//       return {
//         results: data
//       };
//     }
//   }
// });

$.ajax({
    url: full_path+'transaction_inventory/inventory/getAllGroupsByAjax',
    success: function(response) {
        $("#addLedger #group_id").html(response);
    }
});
    $("#addLedger").modal('show');

});

$('body').delegate('.add-group-btn', 'click', function() {
    // Set the effect type
    var effect = 'slide';

    // Set the options for the effect type chosen
    var options = {direction: $('.mySelect').val(0)};

    // Set the duration (default: 400 milliseconds)
    var duration = 300;
    $('#myDiv').toggle(effect, options, duration);

    $.ajax({
        url: full_path+'transaction_inventory/inventory/getAllGroupsByAjax',
        success: function(response) {
            $("#addGroup #parent_id").html(response);
        }
    });
    $("#addGroup").modal('show');
});

$("input[name='bill_details_status']").click(function() {
    var val = $(this).val();
    if (parseInt(val) == 1) {
        $(".credit_limit_div").show();
    } else {
        $(".credit_limit_div").hide();
    }
});
//add group

$("#add_group_form_te").submit(function(event) {
    event.preventDefault();
    var l = Ladda.create(document.querySelector('.group-add-btn'));
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
            $('.errorMessage').html('');
            $('.form-group').removeClass('has-error');
            if (data.res == 'error') {
                $.each(data.message, function(index, value) {
                    $('#' + index).closest('.form-group').addClass('has-error');
                    $('#' + index).closest('.form-group').find('.errorMessage').html(value);
                });
            } else if (data.res == 'save_err') {
                Command: toastr["error"](data.message);
            } else {
                Command: toastr["success"](data.message);
                $('#group_id').val("");
                $('#addGroup').modal('toggle');

            }
        }
    });


});

//add ledger
$("#add_ledger_form_te").submit(function(event) {
    event.preventDefault();
    var l = Ladda.create(document.querySelector('.add-ledger-btn'));
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
            $('.errorMessage').html('');
            $('.form-group').removeClass('has-error');
            if (data.res == 'error') {
                $.each(data.message, function(index, value) {
                    $('#' + index).closest('.form-group').addClass('has-error');
                    $('#' + index).closest('.input-block').find('.errorMessage').html(value);
                });
            } else if (data.res == 'save_err') {
                Command: toastr["error"](data.message);
            } else {
                Command: toastr["success"](data.message);
                $('#ladger_name, #opening_balance, #credit_date, #credit_limit').val("");
                $('#addLedger').modal('toggle');
            }
        }
    });

});

//recurring
$('.formSubmitAll').delegate('.recurring', 'focusin', function() {
    var availableType = [
        "No",
        "Daily",
        "Weekly",
        "Monthly",
        "Yearly"
    ];
    $(this).autocomplete({
        source: availableType,
        minLength: 0,
        select: function(e, ui) {
            var self = $(this);
            e.preventDefault(); // <--- Prevent the value from being inserted.
            $(this).val(ui.item.label); // display the selected text


        }
    }).focus(function() {
        $(this).autocomplete("search", "");
    });

});

var LS_tracking = [];
var LS_tracking_get = [];
var LS_batch_get = [];
var LS_godown_batch_get = [];
var transaction_type_id = $("#entry_type_id").val()

function generateNewRowTrackingModal(diff, callback) {

    var $trNum = $('.tr_tracking_row:last').attr('id');
    var num = parseInt($trNum) + 1;
LS_godown_batch_get
    var $tr = $('.tr_tracking_row:last').closest('.form-group');
    var $klon = $tr.clone();

    // Finally insert $klon wherever you want


    $tr.after($klon);


    setTimeout(function() {
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


    setTimeout(function() {

        $('.discounts-tr:last .discount_allowed_ledger').val("");
        $('.discounts-tr:last .discount_allowed_input').val("");
        $('.discounts-tr:last .discount_acc_type_hidden').val("");
        $('.discounts-tr:last .discount_allowed_ledger_hidden').val("");
        $('.discounts-tr:last .discount_value_hidden').val("");
        $('.discounts-tr:last .discount_percent_hidden').val("");


        $('.discounts-tr:last .discount_allowed_ledger').focus();
        callback();
    }, 500);

}


/* Generate new product row */

function generateNewProductRow(pId, stockId, productName, price, productSalesPrice, taxPerc, productUnitName, productUnitHiddenId, stateStatus, cessStatus, cessValue, productBatchStatus, shortDescription, entryType, callback) {

    // get the last DIV which ID starts with ^= "klon"
    // check if tax is out of state or inside state
    var regulerCom = true;
    var displayFlag = '';
    if(regulerCom){
        displayFlag = '';
    }else{
        displayFlag = 'display:none';
    }
    
    if (parseInt(cessStatus) == 1) {
        var cessCol = '<div style="font-size: 11px; margin-top:-6px; float:left"><span class="pull-left" style="width: 50%;">CESS:</span>' +
                '<input type="text" class="cess-percent form-control pull-left" name="cess_percent[]" readonly="readonly" style="width: 50%;margin-top:-8px; padding: 0; font-size:11px;">' +
                '</div>';

        var cessValCol = '<div style="font-size: 11px;"><input type="text" readonly="readonly" class="cess-value form-control text-right" name="cess_value[]" style="margin-top:-12px; font-size:11px;"/></div>';
    } else {
        var cessCol = '<div style="font-size: 11px; margin-top:-6px; float:left; display:none;"><span class="pull-left" style="width: 50%;">CESS:</span>' +
                '<input type="text" value="0.00" class="cess-percent form-control pull-left" name="cess_percent[]" readonly="readonly" style="width: 50%;margin-top:-8px; padding: 0; font-size:11px;">' +
                '</div>';

        var cessValCol = '<div style="font-size: 11px; display:none;"><input type="text" readonly="readonly" class="cess-value form-control text-right" value="0.00" name="cess_value[]" style="margin-top:-12px; font-size:11px;"/></div>';
    }



    if (stateStatus == 1) {
        var taxCol = '<td style="'+displayFlag+'"><div style="font-size: 11px;"><span class="pull-left" style="width: 50%;">' +
                'CGST:</span> <input type="text" class="tax-percent" name="cgst_tax_percent[]" readonly="readonly" ><span class="pull-left" style="width: 50%;">SGST:</span>' +
                '<input type="text" class="tax-percent" name="sgst_tax_percent[]" readonly="readonly">' +
                '</div>' + cessCol + '</td>';


        var taxValCol = '<td style="'+displayFlag+'"><div style="font-size: 11px;"><input type="text" readonly="readonly" class="tax-value form-control text-right" name="cgst_tax_value[]" style="margin-top:-11px; font-size:11px;"/></div>' +
                '<div style="font-size: 11px;"><input type="text" readonly="readonly" class="tax-value form-control text-right" name="sgst_tax_value[]" style="margin-top:-12px; font-size:11px;"/></div>'
                + cessValCol + '</td>';

    } else if (stateStatus == 2) {
        var taxCol = '<td style="'+displayFlag+'"><div style="font-size: 11px;"><span class="pull-left" style="width: 50%; margin-top: 8px;">IGST:</span><input type="text" class="tax-percent form-control" name="igst_tax_percent[]" readonly="readonly" style="width: 50%;margin-top: -10px;padding: 0; font-size: 11px;"/></div>' + cessCol + '</td>';

        var taxValCol = '<td style="'+displayFlag+'"><div style="font-size: 11px;"><input type="text" readonly="readonly" class="tax-value form-control text-right" name="igst_tax_value[]" style="margin-top:-11px; font-size:11px;"/></div>' + cessValCol + '</td>';
    }


    $productDiv = '<tr product-batch-status="'+productBatchStatus+'" class="each-product '+entryType+'">' +
            '<td><button class="btn btn-xs btn-danger removeitem"><i class="fa fa-trash-o"></i></button>' +
            '<input type="hidden" class="product_id_hidden" name="product_id[]"><input type="hidden" class="stock_id_hidden" name="stock_id[]"><input type="hidden" class="cess-status-col" name="cess_status_col[]"></td>' +
            '</td>' +
            '<td><input type="text" class="product-name text-left form-control" name="product_name[]"/></td>' +
            '<td class="product-description-td"><input placeholder="Description" type="text" class="product-description text-left form-control" name="product_description[]"/></td>' +
            '<td style="width: 100px;"><input type="text" class="form-control product-unit text-center"  name="product_unit[]"/><input type="hidden" class="product-unit-hidden-id" name="product_unit_hidden_id[]"><input type="hidden" class="product-complex-unit-hidden-id" name="product_complex_unit_hidden_id[]"></td>' +
            '<td><input type="text" class="form-control text-right product-quantity" value="1" name="product_quantity[]"/></td>' +
            '<td><input type="text" class="form-control text-right product-price" name="product_price[]"/></td>' +
            '<td><input type="text" class="form-control text-right product-discount" name="product_discount[]"/></td>' +
            '<td style="display:none"><input type="text" readonly="readonly" class="total-price-per-prod form-control text-right" name="total_price_per_prod[]"/></td>' +
            '<td style="'+displayFlag+'"><input type="text" readonly="readonly" class="gross-total-price-per-prod form-control text-right" name="gross_total_price_per_prod[]"/></td>'
            + taxCol + taxValCol +
            '<td><input type="text" readonly="readonly" class="total-price-per-prod-with-tax form-control text-right" name="total_price_per_prod_with_tax[]"/></td>' +
            '</tr>';



    var target = $('.product-listing-table .product-search-row');

    target.before($productDiv);

    setTimeout(function() {

        var $lastProduct = $('.product-listing-table tr.each-product:last');
        // $lastProduct.prop('id', 1);
        $lastProduct.find('.product_id_hidden').val(pId);
        $lastProduct.find('.stock_id_hidden').val(stockId);
        $lastProduct.find('.product-name').val(productName);
        $lastProduct.find('.product-description').val(shortDescription);
        $lastProduct.find('.product-price').val(price);
        $lastProduct.find('.product-unit').val(productUnitName);
        $lastProduct.find('.product-unit-hidden-id').val(productUnitHiddenId);
        $lastProduct.find('.product-complex-unit-hidden-id').val(productUnitHiddenId);
        
        $('.product-listing-table tr.sale-out:last').find('.product-price').val(productSalesPrice);


        if (stateStatus == 2) {
            $lastProduct.find('.tax-percent').val(taxPerc);



            var taxVal = parseFloat((taxPerc / 100) * price);

            $lastProduct.find('.tax-value').val(parseFloat(taxVal).toFixed(2));

            if (parseInt(cessStatus) == 1) {
                var cessVal = parseFloat((cessValue / 100) * taxVal);
                $lastProduct.find('.cess-percent').val(parseFloat(cessValue).toFixed(2));
                $lastProduct.find('.cess-value').val(parseFloat(cessVal).toFixed(2));
                $lastProduct.find('.cess-status-col').val(1);
            } else {
                var cessVal = 0;
                $lastProduct.find('.cess-status-col').val(0);
            }



            var totalProductPrice = parseFloat(taxVal + price + cessValue);




        } else if (stateStatus == 1) {
            $lastProduct.find('.tax-percent').val(parseFloat(taxPerc / 2).toFixed(2));

            var taxVal = parseFloat((taxPerc / 100) * price);
            $lastProduct.find('.tax-value').val(parseFloat(taxVal / 2).toFixed(2));

            if (parseInt(cessStatus) == 1) {
                var cessVal = parseFloat((cessValue / 100) * taxVal);
                $lastProduct.find('.cess-percent').val(parseFloat(cessValue).toFixed(2));
                $lastProduct.find('.cess-value').val(parseFloat(cessVal).toFixed(2));
                $lastProduct.find('.cess-status-col').val(1);
            } else {
                var cessVal = 0;
                $lastProduct.find('.cess-status-col').val(0);
            }

            var totalProductPrice = parseFloat(taxVal + price + cessVal);

        }








        $lastProduct.find('.total-price-per-prod').val(price.toFixed(2));
        $lastProduct.find('.gross-total-price-per-prod').val(price.toFixed(2));
        $lastProduct.find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));
        callback();
    }, 500);




}

var track_index = 0;
function generateNewProductRowBOM(pId, stockId, productName, price, taxPerc, productUnitName, productUnitHiddenId, bom_type, callback) {


    if (bom_type == 'out') {
        $productDiv = '<tr class="each-product">' +
                '<td><button class="btn btn-xs btn-danger removeitem"><i class="fa fa-trash-o"></i></button>' +
                '<input type="hidden" class="product_id_hidden" name="out_product_id[]"><input type="hidden" class="stock_id_hidden" name="out_stock_id[]">' +
                '</td>' +
                '<td><input type="text" class="bom-product-name text-left form-control" name="out_product_name[]"/></td>' +
                '<td><input type="text" class="bom-product-unit text-left form-control" value="'+productUnitName+'" readonly="readonly"/></td>' +
                '<td><input type="text" class="form-control text-right product-quantity" value="1" name="out_product_quantity[]" id="out_product_quantity_id_'+track_index+'"/></td>' +
                '<td><input type="text" class="form-control text-right product-quantity" value="0" name="out_product_price[]" id="out_product_price_id_'+track_index+'" data-index='+track_index+' readonly="readonly"/></td>' +
                '<td><input type="text" class="form-control text-right product-quantity" value="0" name="out_product_value[]" id="out_product_value_id_'+track_index+'" data-index='+track_index+' readonly="readonly"/></td>' +
                '</tr>';
        var target = $('.out-product-listing-table .product-search-row');
    } else {
        $productDiv = '<tr class="each-product">' +
                '<td><button class="btn btn-xs btn-danger removeitem"><i class="fa fa-trash-o"></i></button>' +
                '<input type="hidden" class="product_id_hidden" name="in_product_id[]"><input type="hidden" class="stock_id_hidden" name="in_stock_id[]">' +
                '</td>' +
                '<td><input type="text" class="bom-product-name text-left form-control" name="in_product_name[]"/></td>' +
                '<td><input type="text" class="bom-product-unit text-left form-control" value="'+productUnitName+'" readonly="readonly"/></td>' +
                '<td><input type="text" class="form-control text-right product-quantity" value="1" name="in_product_quantity[]" id="in_product_quantity_id_'+track_index+'" data-index='+track_index+' /></td>' +
                '<td><input type="text" class="form-control text-right product-quantity" value="0" name="in_product_price[]" id="in_product_price_id_'+track_index+'" data-index='+track_index+' /></td>' +
                '<td><input type="text" class="form-control text-right product-quantity" value="0" name="in_product_value[]" id="in_product_value_id_'+track_index+'" data-index='+track_index+' readonly="readonly"/></td>' +
                '</tr>';
        var target = $('.in-product-listing-table .product-search-row');
    }

    track_index += 1;

    target.before($productDiv);

    setTimeout(function() {
        if (bom_type == 'out') {
            var $lastProduct = $('.out-product-listing-table tr.each-product:last');
        } else {
            var $lastProduct = $('.in-product-listing-table tr.each-product:last');
        }
        // $lastProduct.prop('id', 1);
        $lastProduct.find('.product_id_hidden').val(pId);
        $lastProduct.find('.stock_id_hidden').val(stockId);
        $lastProduct.find('.bom-product-name').val(productName);
        callback();
    }, 500);




}

function calculateTotalWithoutDiscount(calculatedRounding, callback) {

    var subTotal = 0.00;
    var subTotalOnlyProduct = 0.00;
    var subTotalOnlyProductGross = 0.00;
    var GST = 0.00;
    var netTotal = 0.00;
    var totalQ = 0;
    var discountAllowed = $('.discount_allowed_input').val();


    // var taxValue = 0;


    $('.each-product').each(function() {


        var self = $(this);
        var taxValue = 0.00;


        if ($(this).find('.tax-value').length == 2) {
            self.find('.tax-value').each(function() {
                taxValue = parseFloat(taxValue) + parseFloat($(this).val());
            });

        } else if ($(this).find('.tax-value').length == 1) {
            taxValue = $(this).find('.tax-value').val();
        }


        if ($(this).find('.cess-value').length == 1) {
            var cessValue = $(this).find('.cess-value').val();
            taxValue = parseFloat(taxValue) + parseFloat(cessValue);
        }



        //var taxValue = $(this).find('.tax-value').val();



        var productTotal = $(this).find('.total-price-per-prod').val();
        var productTotalWithTax = $(this).find('.total-price-per-prod-with-tax').val();
        var productTotalGross = $(this).find('.gross-total-price-per-prod').val();

        var quantity = $(this).find('.product-quantity').val();



        subTotalOnlyProduct = parseFloat(subTotalOnlyProduct) + parseFloat(productTotal);
        subTotalOnlyProductGross = parseFloat(subTotalOnlyProductGross) + parseFloat(productTotalGross);


        subTotal = parseFloat(subTotal) + parseFloat(productTotalWithTax);
        GST = parseFloat(GST) + parseFloat(taxValue);
//        totalQ = parseInt(totalQ) + parseInt(quantity);
        totalQ = parseFloat(totalQ) + parseFloat(quantity);


        netTotal = subTotal;


        /* If there's a discount then negate that */




    });

    $('#netTotal').val(subTotal);
    $("#product_grand_total").val(subTotal);


    setTimeout(function() {

        callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2), subTotalOnlyProductGross.toFixed(2), GST.toFixed(2), totalQ, subTotal);

    }, 1000);

}


function calculateTotal(callback) {

    var subTotal = 0.00;
    var subTotalOnlyProduct = 0.00;
    var subTotalOnlyProductGross = 0.00;
    var GST = 0.00;
    var netTotal = 0.00;
    var totalQ = 0;
    var discountAllowed = $('.discount_allowed_input').val();



    $('.each-product').each(function() {

        var self = $(this);
        var taxValue = 0.00;

        if ($(this).find('.tax-value').length == 2) {
            self.find('.tax-value').each(function() {
                taxValue = parseFloat(taxValue) + parseFloat($(this).val());
            });

        } else if ($(this).find('.tax-value').length == 1) {
            taxValue = $(this).find('.tax-value').val();
        }



        if ($(this).find('.cess-value').length == 1) {

            var cessValue = $(this).find('.cess-value').val();
            taxValue = parseFloat(taxValue) + parseFloat(cessValue);
        }

        // var taxValue = $(this).find('.tax-value').val();



        var productTotal = $(this).find('.total-price-per-prod').val();
        var productTotalGross = $(this).find('.gross-total-price-per-prod').val();
        var productTotalWithTax = $(this).find('.total-price-per-prod-with-tax').val();

        var quantity = $(this).find('.product-quantity').val();



        subTotalOnlyProduct = parseFloat(subTotalOnlyProduct) + parseFloat(productTotal);
        subTotalOnlyProductGross = parseFloat(subTotalOnlyProductGross) + parseFloat(productTotalGross);
        subTotal = parseFloat(subTotal) + parseFloat(productTotalWithTax);
        GST = parseFloat(GST) + parseFloat(taxValue);
//        totalQ = parseInt(totalQ) + parseInt(quantity);
        totalQ = parseFloat(totalQ) + parseFloat(quantity);
//        totalQ = totalQ + quantity;
        // console.log(totalQ);


        netTotal = subTotal;



        /* If there's a discount then negate that */




    });

    $('#netTotal').val(netTotal);
    $("#product_grand_total").val(subTotal);
    $('#product_total').val(subTotalOnlyProduct);
    $('#product_total_gross').val(subTotalOnlyProductGross);


    setTimeout(function() {

        var productT = $('#product_total').val();

        callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2), subTotalOnlyProductGross.toFixed(2), GST.toFixed(2), totalQ, productT);


        calculateDiscount(function(calculatedRounding) {

            /* Show subtotal, GST and total amount */

            callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2), subTotalOnlyProductGross.toFixed(2), GST.toFixed(2), totalQ, calculatedRounding);


        });

    }, 1000);


}




function calculateDiscount(callback) {


    var subTotalGross = $('#product_total').text();
//    var calculatedRounding = subTotalGross;

    var netTotal = $('#netTotal').val();
    var percentageOfProduct = 0.00;
    var product_grand_total = $('#product_grand_total').val();
    var calculatedRounding = product_grand_total;

    // var productTotalGross = 0;




    /* check if it's a percentage or not  */

    $('.discounts-tr').each(function() {


        /*  Check for a/c type and value in textbox */
        var self = $(this);
        var acc_type = $(this).find('.discount_acc_type_hidden').val();
        var valDis = $(this).find('.discount_allowed_input').val();

        if (tran_type == 5 || tran_type == 7 || tran_type == 10 || tran_type == 12 || parent_id == 5 || parent_id == 7 || parent_id == 10 || parent_id == 12) {
            self.find('.discount_acc_type_hidden').val('Dr');
        } else if (tran_type == 6 || tran_type == 8 || tran_type == 9 || tran_type == 14 || parent_id == 6 || parent_id == 8 || parent_id == 9 || parent_id == 14) {
            self.find('.discount_acc_type_hidden').val('Cr');
        }


        if (valDis != "" && valDis != "0.00") {

            /* first check for percentage */

            var checkLastChar = valDis.charAt(valDis.length - 1);

            if ($.trim(checkLastChar) == "%") {

                var percentageOf = valDis.substr(0, valDis.indexOf('%'));
                var recentCalculations = parseFloat(percentageOf / 100) * parseFloat(calculatedRounding);

                self.find('.discount_percent_hidden').val(parseFloat(percentageOf).toFixed(2));


                setTimeout(function() {
                    calculatedRounding = parseFloat(calculatedRounding) - parseFloat(recentCalculations);

                    if (tran_type == 5 || tran_type == 7 || tran_type == 10 || tran_type == 12 || parent_id == 5 || parent_id == 7 || parent_id == 10 || parent_id == 12) {

                        self.find('.discount_acc_type_hidden').val('Dr');
                    } else if (tran_type == 6 || tran_type == 8 || tran_type == 9 || tran_type == 14 || parent_id == 6 || parent_id == 8 || parent_id == 9 || parent_id == 14) {

                        self.find('.discount_acc_type_hidden').val('Cr');
                    }

                    self.find('.discount_value_hidden').val(recentCalculations);


                    /* discount for each product total */

                    //var productTotalGross = 0.00;
                    percentageOfProduct = parseFloat(percentageOfProduct) + parseFloat(percentageOf);


                    $('.each-product').each(function() {

                        var self = $(this);
                        var taxPerc = 0.00;
                        // var cessPerc = 0.00;
                        var productTotal = $(this).find('.total-price-per-prod').val();



                        var productTotalGross = $(this).find('.gross-total-price-per-prod').val();

                        var percentOf = parseFloat(percentageOfProduct / 100) * parseFloat(productTotal);

                        productTotalGross = parseFloat(productTotal) - parseFloat(percentOf);
                        
                        $(this).find('.gross-total-price-per-prod').val(productTotalGross.toFixed(2));


                        if ($(this).find('.tax-percent').length == 2) {
                            self.find('.tax-percent').each(function() {
                                taxPerc = parseFloat(taxPerc) + parseFloat($(this).val());
                            });
                            
                        } else if ($(this).find('.tax-value').length == 1) {
                            taxPerc = $(this).find('.tax-percent').val();
                        }


                        if ($(this).find('.cess-value').length == 1) {
                            var cessPerc = $(this).find('.cess-percent').val();
                        } else {
                            var cessPerc = 0.00;
                        }


                        var taxVal = parseFloat((taxPerc / 100) * productTotalGross.toFixed(2));
                        var cessVal = parseFloat((cessPerc / 100) * taxVal.toFixed(2));

                        if ($(this).find('.tax-value').length == 2) {
                            $(this).find('.tax-value').val(parseFloat(taxVal / 2).toFixed(2));
                        } else {
                            $(this).find('.tax-value').val(taxVal.toFixed(2));
                        }

                        if ($(this).find('.cess-value').length == 1) {
                            $(this).find('.cess-value').val(cessVal.toFixed(2));
                        }



                        var totalProductPrice = parseFloat(taxVal + cessVal + productTotalGross);

                        $(this).find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));


                    });

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
                        if (tran_type == 5 || tran_type == 7 || tran_type == 10 || tran_type == 12 || parent_id == 5 || parent_id == 7 || parent_id == 10 || parent_id == 12) {
                            self.find('.discount_acc_type_hidden').val('Dr');
                        } else if (tran_type == 6 || tran_type == 8 || tran_type == 9 || tran_type == 14 || parent_id == 6 || parent_id == 8 || parent_id == 9 || parent_id == 14) {
                            self.find('.discount_acc_type_hidden').val('Cr');
                        }
                        self.find('.discount_value_hidden').val(valDis);

                    } else if ($.trim(firstChar) == "-") {
                        /* credit */
                        //alert("pos 1");

                        var exactVal = Math.abs(parseFloat(valDis));
                        calculatedRounding = parseFloat(calculatedRounding) + parseFloat(exactVal);
                        if (tran_type == 5 || tran_type == 7 || tran_type == 10 || tran_type == 12 || parent_id == 5 || parent_id == 7 || parent_id == 10 || parent_id == 12) {
                            self.find('.discount_acc_type_hidden').val('Cr');
                        } else if (tran_type == 6 || tran_type == 8 || tran_type == 9 || tran_type == 14 || parent_id == 6 || parent_id == 8 || parent_id == 9 || parent_id == 14) {
                            self.find('.discount_acc_type_hidden').val('Dr');
                        }
                        self.find('.discount_value_hidden').val(exactVal);
                    }
                    
                    
                } else if (acc_type == "Cr") {
                    // calculatedRounding = parseFloat(calculatedRounding) + parseFloat(valDis);
                    // self.find('.discount_value_hidden').val(valDis);

                    var firstChar = valDis.charAt(0);

                    if ($.trim(firstChar) != "-") {
                        /* debit */
                        calculatedRounding = parseFloat(calculatedRounding) - parseFloat(valDis);
                        if (tran_type == 5 || tran_type == 7 || tran_type == 10 || tran_type == 12 || parent_id == 5 || parent_id == 7 || parent_id == 10 || parent_id == 12) {
                            self.find('.discount_acc_type_hidden').val('Dr');
                        } else if (tran_type == 6 || tran_type == 8 || tran_type == 9 || tran_type == 14 || parent_id == 6 || parent_id == 8 || parent_id == 9 || parent_id == 14) {
                            self.find('.discount_acc_type_hidden').val('Cr');
                        }
                        self.find('.discount_value_hidden').val(valDis);

                    } else if ($.trim(firstChar) == "-") {
                        /* credit */
                        //alert("pos 2");

                        var exactVal = Math.abs(parseFloat(valDis));
                        calculatedRounding = parseFloat(calculatedRounding) + parseFloat(exactVal);
                        if (tran_type == 5 || tran_type == 7 || tran_type == 10 || tran_type == 12 || parent_id == 5 || parent_id == 7 || parent_id == 10 || parent_id == 12) {
                            self.find('.discount_acc_type_hidden').val('Cr');
                        } else if (tran_type == 6 || tran_type == 8 || tran_type == 9 || tran_type == 14 || parent_id == 6 || parent_id == 8 || parent_id == 9 || parent_id == 14) {
                            self.find('.discount_acc_type_hidden').val('Dr');
                        }
                        self.find('.discount_value_hidden').val(exactVal);

                    }

                } else if (acc_type != "Dr" && acc_type != "Cr") {
                    /* check for negative */

                    var firstChar = valDis.charAt(0);

                    if ($.trim(firstChar) != "-") {
                        /* debit */
                        calculatedRounding = parseFloat(calculatedRounding) - parseFloat(valDis);
                        if (tran_type == 5 || tran_type == 7 || tran_type == 10 || tran_type == 12 || parent_id == 5 || parent_id == 7 || parent_id == 10 || parent_id == 12) {
                            self.find('.discount_acc_type_hidden').val('Dr');
                        } else if (tran_type == 6 || tran_type == 8 || tran_type == 9 || tran_type == 14 || parent_id == 6 || parent_id == 8 || parent_id == 9 || parent_id == 14) {
                            self.find('.discount_acc_type_hidden').val('Cr');
                        }
                        self.find('.discount_value_hidden').val(valDis);

                    } else if ($.trim(firstChar) == "-") {
                        /* credit */

                        //alert("pos 5"); 

                        var exactVal = Math.abs(parseFloat(valDis));
                        calculatedRounding = parseFloat(calculatedRounding) + parseFloat(exactVal);
                        if (tran_type == 5 || tran_type == 7 || tran_type == 10 || tran_type == 12 || parent_id == 5 || parent_id == 7 || parent_id == 10 || parent_id == 12) {
                            self.find('.discount_acc_type_hidden').val('Cr');
                        } else if (tran_type == 6 || tran_type == 8 || tran_type == 9 || tran_type == 14 || parent_id == 6 || parent_id == 8 || parent_id == 9 || parent_id == 14) {
                            self.find('.discount_acc_type_hidden').val('Dr');
                        }
                        self.find('.discount_value_hidden').val(exactVal);
                    }

                }




            }

        } else {

            $('.each-product').each(function() {
                var productTotalGross = $(this).find('.gross-total-price-per-prod').val();
                var productTotal = $(this).find('.total-price-per-prod').val();
                calculatedRounding = parseFloat(calculatedRounding) - parseFloat(0.00);
                productTotalGross = parseFloat(productTotal) - parseFloat(0.00);
                $(this).find('.gross-total-price-per-prod').val(productTotalGross.toFixed(2));
                // calculatedRounding = productTotalGross;
            });


            //  $('.each-product').each(function() {

            //     var self = $(this);
            //     var recentCalculations = 0.00;


            //     var productTotal = $(this).find('.total-price-per-prod').val();
            //     var percentOf = parseFloat(percentageOf / 100) * parseFloat(productTotal);
            //     var taxPerc = 0.00;

            //     console.log("CUrrent price " + productTotal);
            //     console.log("percentage " + percentOf);

            //     var productTotalGross = parseFloat(productTotal);

            //     console.log("new price gross " + productTotalGross);

            //     $(this).find('.gross-total-price-per-prod').val(productTotalGross.toFixed(2));

            //     if( $(this).find('.tax-percent').length == 2){
            //         self.find('.tax-percent').each(function(){
            //              taxPerc = parseFloat(taxPerc) + parseFloat( $(this).val());
            //         });

            //         console.log("TAX Perc ---- " + taxPerc);
            //     }else if( $(this).find('.tax-value').length == 1 ){
            //         taxPerc = $(this).find('.tax-percent').val();
            //         console.log("TAX Perc ---- " + taxPerc);
            //     }    


            //     if( $(this).find('.cess-value').length == 1 ){
            //         var cessPerc = $(this).find('.cess-percent').val();
            //       }else{
            //         var cessPerc = 0.00;
            //       }


            //     console.log("tax perc " + taxPerc);


            //     var taxVal = parseFloat((taxPerc / 100) * productTotalGross.toFixed(2));
            //     var cessVal = parseFloat((cessPerc / 100) * taxVal.toFixed(2));

            //     console.log("TAXVALTAXVAL " + taxVal);
            //     console.log("CESSVALCESSVAL " + cessVal);

            //     if( $(this).find('.tax-value').length == 2){
            //         $(this).find('.tax-value').val( parseFloat(taxVal/2).toFixed(2) );
            //     }else{
            //         $(this).find('.tax-value').val(taxVal.toFixed(2));
            //     }

            //      if( $(this).find('.cess-value').length == 1){
            //         $(this).find('.cess-value').val(cessVal.toFixed(2));
            //      }

            //     var totalProductPrice = parseFloat(taxVal + cessVal + productTotalGross);

            //     console.log("total price now " + totalProductPrice);
            //     $(this).find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));


            //     calculatedRounding = parseFloat(calculatedRounding) - parseFloat(recentCalculations);



            // });





        }




    });


    setTimeout(function() {
        callback(calculatedRounding);

        // setTimeout(function(){

//        calculateTotalWithoutDiscount(calculatedRounding, function(subTotal, subTotalOnlyProduct, subTotalOnlyProductGross, GST, q, netTotal) {
//            $('#product_grand_total').val(subTotal);
//            $('#tax_value').val(GST);
//            $('#product_total').text(subTotalOnlyProduct);
//            $('#product_total_gross').text(subTotalOnlyProductGross);
//            $('#quantity').text(q);
//            $('#netTotal').val(parseFloat(netTotal).toFixed(2));
//
//        });

        // }, 3000);



    }, 1000);




}




$(function() {
    
    // product price calculation clicking product-discount
    
    $('.product-listing-table').delegate('.product-discount', 'keydown', function(e) {
        var self = $(this);

        var quantity = self.closest('tr').find('.product-quantity').val();
        var value = self.closest('tr').find('.product-price').val();

        var tax = 0.00;

        if (self.closest('tr').find('.tax-percent').length == 2) {
            self.closest('tr').find('.tax-percent').each(function() {
                tax = parseFloat(tax) + parseFloat($(this).val());

            });
            // console.log("TAX val " + taxVal);
        } else if (self.closest('tr').find('.tax-percent').length == 1) {
            tax = self.closest('tr').find('.tax-percent').val();
            // taxVal = self.closest('tr').find('.tax-value').val();
        }

        if (e.which == 13) {

            setTimeout(function() {

                var discountPerProduct = self.val();
                
                var totalPrPriceQuantity = parseFloat(value * quantity);
                if(discountPerProduct != undefined && discountPerProduct > 0){
                     totalPrPriceQuantity = totalPrPriceQuantity * ((100-discountPerProduct)/100);
                }
                var taxVal = parseFloat((tax / 100) * totalPrPriceQuantity);
                var totalProductPrice = parseFloat(taxVal + totalPrPriceQuantity);



                if (self.closest('tr').find('.tax-percent').length == 2) {
                    self.closest('tr').find('.tax-value').val(parseFloat(taxVal / 2).toFixed(2));
                } else {
                    self.closest('tr').find('.tax-value').val(taxVal.toFixed(2));
                }

                self.closest('tr').find('.total-price-per-prod').val(totalPrPriceQuantity.toFixed(2));
                self.closest('tr').find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));

                calculateTotal(function(subTotal, subTotalOnlyProduct, subTotalOnlyProductGross, GST, q, netTotal) {
                    $('#product_grand_total').val(subTotal);
                    $('#tax_value').val(GST);
                    $('#product_total').text(subTotalOnlyProduct);
                    $('#product_total_gross').text(subTotalOnlyProductGross);

                    $('#quantity').text(q);
                    $('#netTotal').val(parseFloat(netTotal).toFixed(2));
                    if(parseFloat(netTotal).toFixed(2) > 0){
                        $('.footer-button').show();
                    }else{
                        $('.footer-button').hide();
                    }

                    //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                });


            }, 500);



        }

    });

    // product price calculation clicking product-price

    $('.product-listing-table').delegate('.product-price', 'keydown', function(e) {
        var self = $(this);

        var quantity = self.closest('tr').find('.product-quantity').val();
        var discountPerProduct = self.closest('tr').find('.product-discount').val();
        // var tax = self.closest('tr').find('.tax-percent').val();

        // if (e.keyCode > 31 && (e.keyCode < 48 || e.keyCode > 57) && (e.keyCode != 39 && e.keyCode != 37)) {
        //     return false;
        // }else{

        var tax = 0.00;
        // var taxVal = 0.00;

        if (self.closest('tr').find('.tax-percent').length == 2) {
            self.closest('tr').find('.tax-percent').each(function() {
                tax = parseFloat(tax) + parseFloat($(this).val());

            });
            // console.log("TAX val " + taxVal);
        } else if (self.closest('tr').find('.tax-percent').length == 1) {
            tax = self.closest('tr').find('.tax-percent').val();
            // taxVal = self.closest('tr').find('.tax-value').val();
        }

        if (e.which == 13) {

            setTimeout(function() {

                var value = self.val();
                
                var totalPrPriceQuantity = parseFloat(value * quantity);
                if(discountPerProduct != undefined && discountPerProduct > 0){
                     totalPrPriceQuantity = totalPrPriceQuantity * ((100-discountPerProduct)/100);
                }
                var taxVal = parseFloat((tax / 100) * totalPrPriceQuantity);
                var totalProductPrice = parseFloat(taxVal + totalPrPriceQuantity);


                // self.closest('tr').find('.tax-value').val(taxVal.toFixed(2));


                if (self.closest('tr').find('.tax-percent').length == 2) {
                    self.closest('tr').find('.tax-value').val(parseFloat(taxVal / 2).toFixed(2));
                } else {
                    self.closest('tr').find('.tax-value').val(taxVal.toFixed(2));
                }

                self.closest('tr').find('.total-price-per-prod').val(totalPrPriceQuantity.toFixed(2));
                self.closest('tr').find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));

                calculateTotal(function(subTotal, subTotalOnlyProduct, subTotalOnlyProductGross, GST, q, netTotal) {
                    $('#product_grand_total').val(subTotal);
                    $('#tax_value').val(GST);
                    $('#product_total').text(subTotalOnlyProduct);
                    $('#product_total_gross').text(subTotalOnlyProductGross);

                    $('#quantity').text(q);
                    $('#netTotal').val(parseFloat(netTotal).toFixed(2));
                    if(parseFloat(netTotal).toFixed(2) > 0){
                        $('.footer-button').show();
                    }else{
                        $('.footer-button').hide();
                    }
                    


                    //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                });


            }, 500);



        }

    });

    $('.product-listing-table').delegate('.removeitem', 'click', function() {
        
        var product_stock_id = $(this).closest('.each-product').find('.stock_id_hidden').val();
        if(product_stock_id !== undefined)
            localStorage.removeItem('batchGodownStockId_' + product_stock_id);
        console.log(product_stock_id)
        
        $(this).closest('.each-product').remove();
        calculateTotal(function(subTotal, subTotalOnlyProduct, subTotalOnlyProductGross, GST, q, netTotal) {
            $('#product_grand_total').val(subTotal);
            $('#tax_value').val(GST);
            $('#product_total').text(subTotalOnlyProduct);
            $('#product_total_gross').text(subTotalOnlyProductGross);

            $('#quantity').text(q);
            $('#netTotal').val(parseFloat(netTotal).toFixed(2));
            if(parseFloat(netTotal).toFixed(2) > 0){
                $('.footer-button').show();
            }else{
                $('.footer-button').hide();
            }
            

            //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
        });



    })

    $('.product-listing-table').delegate('.product-quantity', 'blur', function(e) {
        var self = $(this);

        var action = $("#action").val();
        var tran_type_id = $("#entry_type_id").val();
        var parent_id = $("#parent_id").val();
        var value = self.val();
        var max_qty = self.closest('tr').find('.max-product-qty').val();
        if (action == 't' && (parseInt(tran_type_id) == 5 || parseInt(tran_type_id) == 6 || parseInt(parent_id) == 5 || parseInt(parent_id) == 6)) {
            if (parseInt(value) > parseInt(max_qty)) {
                self.val(max_qty);
                Command: toastr["error"]("You can not increase quantity more then remaining sales order quantity.");
            }
        }
    });

    $('.product-listing-table').delegate('.product-quantity', 'keyup', function(e) {

        // $(this).val($(this).val().replace(/[^0-9]/gi, ''));
        // somnath - allow only float number
        $(this).val($(this).val().replace(/[^\d.]/gi, ''));
        // only one decimal(.) allowed
        if($(this).val().split('.').length>2) 
            $(this).val( $(this).val().replace(/\.+$/,"") );

    });

    $('.product-listing-table').delegate('.product-price', 'keyup', function(e) {

        // somnath - allow only float number
        $(this).val($(this).val().replace(/[^\d.]/gi, ''));
        // only one decimal(.) allowed
        if($(this).val().split('.').length>2) 
            $(this).val( $(this).val().replace(/\.+$/,"") );

    });
    
    $('.product-listing-table').delegate('.product-discount', 'keyup', function(e) {

        // somnath - allow only float number
        $(this).val($(this).val().replace(/[^\d.]/gi, ''));
        // only one decimal(.) allowed
        if($(this).val().split('.').length>2) 
            $(this).val( $(this).val().replace(/\.+$/,"") );

    });

    $('.product-listing-table').delegate('.product-quantity', 'keydown', function(e) {

        var self = $(this);
        // console.log('product-quantity');
        var eachProductPrice = self.closest('tr').find('.product-price').val();
        var eachProductDiscount = self.closest('tr').find('.product-discount').val();
        var tax = 0.00;
        // var taxVal = 0.00;

        if (self.closest('tr').find('.tax-percent').length == 2) {

            self.closest('tr').find('.tax-percent').each(function() {
                tax = parseFloat(tax) + parseFloat($(this).val());
            });

            // console.log("TAX val " + taxVal);
        } else if (self.closest('tr').find('.tax-percent').length == 1) {
            tax = self.closest('tr').find('.tax-percent').val();
            // taxVal = self.closest('tr').find('.tax-value').val();
        }


        //var tax = self.closest('tr').find('.tax-percent').val();



        if (e.keyCode == "38") {
            e.preventDefault();

            var value = self.val();
            var max_qty = self.closest('tr').find('.max-product-qty').val();

            var action = $("#action").val();
            var tran_type_id = $("#entry_type_id").val();
            var parent_id = $("#parent_id").val();
            if (parseInt(value) >= parseInt(max_qty)) {
                if (action == 't' && (parseInt(tran_type_id) == 5 || parseInt(tran_type_id) == 6 || parseInt(parent_id) == 5 || parseInt(parent_id) == 6)) {
                    Command: toastr["error"]("You can not increase quantity more then remaining sales order quantity.");
                }
            } else {
                value++;
                self.val(value);

                var totalPrPriceQuantity = parseFloat(eachProductPrice * value);
                if(totalPrPriceQuantity != undefined && totalPrPriceQuantity > 0){
                     totalPrPriceQuantity = totalPrPriceQuantity * ((100 - eachProductDiscount)/100);
                }
                var taxVal = parseFloat((tax / 100) * totalPrPriceQuantity);
                var totalProductPrice = parseFloat(taxVal + totalPrPriceQuantity);



                if (self.closest('tr').find('.tax-percent').length == 2) {
                    self.closest('tr').find('.tax-value').val(parseFloat(taxVal / 2).toFixed(2));
                } else {
                    self.closest('tr').find('.tax-value').val(taxVal.toFixed(2));
                }




                self.closest('tr').find('.total-price-per-prod').val(totalPrPriceQuantity.toFixed(2));
                self.closest('tr').find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));

                calculateTotal(function(subTotal, subTotalOnlyProduct, subTotalOnlyProductGross, GST, q, netTotal) {
                    $('#product_grand_total').val(subTotal);
                    $('#tax_value').val(GST);
                    $('#product_total').text(subTotalOnlyProduct);
                    $('#product_total_gross').text(subTotalOnlyProductGross);

                    $('#quantity').text(q);
                    $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                    //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                });


            }

        } else if (e.keyCode == "40") {
            e.preventDefault();
            var value = self.val();
            value--;

            if (self.val() > 1) {

                self.val(value);

                var totalPrPriceQuantity = parseFloat(eachProductPrice * value);
                var taxVal = parseFloat((tax / 100) * totalPrPriceQuantity);
                var totalProductPrice = parseFloat(taxVal + totalPrPriceQuantity);


                if (self.closest('tr').find('.tax-percent').length == 2) {
                    self.closest('tr').find('.tax-value').val(parseFloat(taxVal / 2).toFixed(2));
                } else {
                    self.closest('tr').find('.tax-value').val(taxVal.toFixed(2));
                }
                self.closest('tr').find('.total-price-per-prod').val(totalPrPriceQuantity.toFixed(2));
                self.closest('tr').find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));





                calculateTotal(function(subTotal, subTotalOnlyProduct, subTotalOnlyProductGross, GST, q, netTotal) {
                    $('#product_grand_total').val(subTotal);
                    $('#tax_value').val(GST);
                    $('#product_total').text(subTotalOnlyProduct);
                    $('#product_total_gross').text(subTotalOnlyProductGross);

                    $('#quantity').text(q);
                    $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                    //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                });

            } else {
                return;
            }


        } 
        // else if (e.keyCode > 31 && (e.keyCode < 48 || e.keyCode > 57) && (e.keyCode != 39 && e.keyCode != 37)) {
        //     return false;
        // } 
        else if ((e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 106 && e.keyCode <= 109) || (e.keyCode >= 186 && e.keyCode <= 189) || (e.keyCode >= 219 && e.keyCode <= 222) || e.keyCode == 16 || e.keyCode == 17 || e.keyCode == 111 || e.keyCode == 191 || e.keyCode == 192) { 
            return false;
        }
        else {

            setTimeout(function() {

                var value = self.val();

                var totalPrPriceQuantity = parseFloat(eachProductPrice * value);
                var taxVal = parseFloat((tax / 100) * totalPrPriceQuantity);
                var totalProductPrice = parseFloat(taxVal + totalPrPriceQuantity);


                if (self.closest('tr').find('.tax-percent').length == 2) {
                    self.closest('tr').find('.tax-value').val(parseFloat(taxVal / 2).toFixed(2));
                } else {
                    self.closest('tr').find('.tax-value').val(taxVal.toFixed(2));
                }
                self.closest('tr').find('.total-price-per-prod').val(totalPrPriceQuantity.toFixed(2));
                self.closest('tr').find('.total-price-per-prod-with-tax').val(totalProductPrice.toFixed(2));

                calculateTotal(function(subTotal, subTotalOnlyProduct, subTotalOnlyProductGross, GST, q, netTotal) {
                    $('#product_grand_total').val(subTotal);
                    $('#tax_value').val(GST);
                    $('#product_total').text(subTotalOnlyProduct);
                    $('#product_total_gross').text(subTotalOnlyProductGross);

                    $('#quantity').text(q);
                    $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                    //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                });


            }, 500);



        }



    });



    $('body').delegate('.product-name', 'focusin', function() {
        var self = $(this);

        var shippingCountry = $('.in_ledger_country').val();
        var shippingState = $('.in_ledger_state').val();

        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/accounts_inventory/getProducts',
                    data: "term=" + request.term,
                    dataType: "json",
                    success: function(data) {
                        response(data);
                        //console.log(data);
                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label); // display the selected text

//                var pId = ui.item.value;
                var stockId = ui.item.value;
                var productName = ui.item.label;
                $(this).next(".search_product_id").val(ui.item.value); // save selected id to hidden input

                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/accounts_inventory/getProductDetails',
                    data: "sId=" + stockId + "&shippingcountry=" + shippingCountry + "&shippingstate=" + shippingState,
                    dataType: "json",
                    success: function(data) {
                        // response(data);

                        var price = parseFloat(data['productPrice']);
                        var taxPerc = parseFloat(data['productTax']);
                        var productUnitName = $.trim(data['productUnitName']);
                        var productUnitHiddenId = data['productUnitId'];
                        var pId = data['pId'];


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


                        calculateTotal(function(subTotal, subTotalOnlyProduct, subTotalOnlyProductGross, GST, q, netTotal) {
                            $('#product_grand_total').val(subTotal);
                            $('#tax_value').val(GST);
                            $('#product_total').text(subTotalOnlyProduct);
                            $('#product_total_gross').text(subTotalOnlyProductGross);

                            $('#quantity').text(q);
                            $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                            //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                        });

                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });

            }

        });

    });


//BOM

    $('body').delegate('.bom-product-name', 'focusin', function() {

        var self = $(this);

        var shippingCountry = $('.in_ledger_country').val();
        var shippingState = $('.in_ledger_state').val();

        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/accounts_inventory/getProducts',
                    data: "term=" + request.term,
                    dataType: "json",
                    success: function(data) {
                        response(data);
                        //console.log(data);
                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label); // display the selected text

                var pId = ui.item.value;
                var productName = ui.item.label;

                $(this).next(".search_product_id").val(ui.item.value); // save selected id to hidden input

                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/accounts_inventory/getProductDetailsBOM',
                    data: "pId=" + pId,
                    dataType: "json",
                    success: function(data) {
                        // response(data);


                        var price = parseFloat(data['productPrice']);
                        var taxPerc = parseFloat(data['productTax']);
                        var productUnitName = $.trim(data['productUnitName']);
                        var productUnitHiddenId = data['productUnitId'];
                        var stockId = data['stockId'];



                        self.closest('.each-product').find('.product_id_hidden').val(pId);
                        self.closest('.each-product').find('.stock_id_hidden').val(stockId);
                        self.closest('.each-product').find('.product-name').val(productName);
                        self.closest('.each-product').find('.product-quantity').val(1);


                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });

            }

        });

    });


    $('body').delegate('.search_product', 'focusin', function() {

        var self = $(this);

        var shippingCountry = $('.in_ledger_country').val();
        var shippingState = $('.in_ledger_state').val();
        var type_service_product = $('input[name="service_product"]:checked').val();

        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/accounts_inventory/getProducts',
                    data: {term: request.term, type_service_product: type_service_product},
                    dataType: "json",
                    success: function(data) {
                        response(data);
                        //console.log(data);
                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label); // display the selected text
                
                
//                var pId = ui.item.value;
                var stockId = ui.item.value;
                var productName = ui.item.label;
                //$(this).next(".search_product_id").val(ui.item.value); // save selected id to hidden input

                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/accounts_inventory/getProductDetails',
                    data: "sId=" + stockId + "&shippingcountry=" + shippingCountry + "&shippingstate=" + shippingState + "&type_service_product=" + type_service_product,
                    dataType: "json",
                    success: function(data) {
                        // response(data);

                        var stateStatus;


                        var price = parseFloat(data['productPrice']);
                        var productSalesPrice = parseFloat(data['productSalesPrice']);
                        var taxPerc = parseFloat(data['productTax']);
                        var productUnitName = $.trim(data['productUnitName']);
                        var productUnitHiddenId = data['productUnitId'];
                        var pId = data['pId'];
                        var companyState = data['billingState'];
                        var companyCountry = data['billingCountry'];
                        var cessStatus = data['cess_present'];
                        var cessValue = data['cess_value'];
                        
                        var productBatchStatus = data['productBatchStatus'];
                        var shortDescription = data['shortDescription'];

//                        var shippingState = $('.shipping_addr .state').text();
//                        var shippingCountry = $('.shipping_addr .country').text();
                        var shippingState = $('.billing_addr .state').text();
                        var shippingCountry = $('.billing_addr .country').text();
//                        
//                        console.log(shippingState+'state');
//                        console.log(shippingCountry+'shippingCountry');
                        if ($.trim(companyState) == $.trim(shippingState)) {
                            stateStatus = 1;
                            $('#igst_status').val(0);
                        } else {
                            stateStatus = 2;
                            $('#igst_status').val(1);
                        }
//                        console.log(shippingState+'state');
//                        console.log(shippingCountry+'shippingCountry');
                        var companyTypeStatus = $('.company-type-status').val();
                        
                        var taxStatusMenuOption = $('.tax-status-country:checked').val();

                        if (parseInt(taxStatusMenuOption) == 1) {

                            if ($.trim(companyCountry) != $.trim(shippingCountry)) {
                                taxPerc = 0.00;
                            }
                        }else if(parseInt(companyTypeStatus) == 0){
                            taxPerc = 0.00;
                        }
                        
                        
                        
                        //session store is used for sales price set in batch modal
                        sessionStorage.setItem("productSalesPrice_"+stockId, productSalesPrice);
                        
                        //check entry typeid for sale or purchase
                        var typeOfEntry = $('#entry_type_id').val();
                        var typeOfParentEntry = $('#parent_id').val();
                        var entryType = '';
                        if(typeOfEntry == '5' || typeOfEntry == '7' || typeOfEntry == '10' || typeOfParentEntry == '5' || typeOfParentEntry == '7' || typeOfParentEntry == '10'){
                            entryType = 'sale-out';
                        }else{
                            entryType = 'purchase-in';
                        }
                        
                        generateNewProductRow(pId, stockId, productName, price, productSalesPrice, taxPerc, productUnitName, productUnitHiddenId, stateStatus, cessStatus, cessValue, productBatchStatus, shortDescription, entryType, function() {
                            
                            var $lastProduct = $('.product-listing-table tr.each-product:last');

                            $lastProduct.find('.product-description').focus().select();


                            calculateTotal(function(subTotal, subTotalOnlyProduct, subTotalOnlyProductGross, GST, q, netTotal) {
                                $('#product_grand_total').val(subTotal);
                                $('#tax_value').val(GST);
                                $('#product_total').text(subTotalOnlyProduct);
                                $('#product_total_gross').text(subTotalOnlyProductGross);

                                $('#quantity').text(q);
                                $('#netTotal').val(parseFloat(netTotal).toFixed(2));



                                //callback(subTotal.toFixed(2), subTotalOnlyProduct.toFixed(2); GST.toFixed(2));
                            });

                            self.val("");

                            // somnath - move curson at the end of the quantity
                            // var product_quantity = $lastProduct.find('.product-quantity').val();
                            // $lastProduct.find('.product-quantity').focus().val("").val(product_quantity);
                            

                        });


                        /* Generate new product row with values  */



                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });

            }

        }).focus(function() {
            $(this).autocomplete("search", "");
        });

    });   // autocomplete 




    $('body').delegate('.entry_debtors', 'focusin', function() {

        // $('.entry_debtors').on('focus', function(){


        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction_inventory/inventory/getLedgerDebtors',
                    data: {ledger: request.term, tran_type: tran_type},
                    dataType: "json",
                    success: function(data) {
                        response(data);
                        // console.log(data);
                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label); // display the selected text
                $(this).next(".tr_ledger_id_debtors").val(ui.item.value); // save selected id to hidden input

                var ledgerId = ui.item.value;

                /* Get shipping country and state  */

                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction_inventory/inventory/getShippingDetails',
                    data: "ledger=" + ledgerId,
                    dataType: "json",
                    success: function(data) {
                        // response(data);
                        if (data.shipping == true) {
                            $('.tr_branch_id').val(data.tr_branch_id);
                            $('.in_ledger_state').val(data.state);
                            $('.in_ledger_country').val(data.country);
                            if (tran_type == 5 || parent_id == 5 || tran_type == 7 || parent_id == 7 || tran_type == 10 || parent_id == 10) {
                                $('.tr_type_debtors').val('Dr');
                            } else if (tran_type == 6 || parent_id == 6 || tran_type == 8 || parent_id == 8 || tran_type == 9 || parent_id == 9) {
                                $('.tr_type_debtors').val('Cr');
                            } else if (tran_type == 14 || parent_id == 14) {
                                $('.tr_type_debtors').val('Cr');
                            } else if (tran_type == 12 || parent_id == 12) {
                                $('.tr_type_debtors').val('Dr');
                            } else {
                                $('.tr_type_debtors').val(data.transactionType);
                            }


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
                            $('.shipping_addr .shipping_id').val(data.Sh_id);


                            $('.credit_days').val(data.LL_creditDays);
                            $('.credit_limit').val(data.LL_creditLimit);
                            
                            
                            if(data.companyType ==1){
                                $('.company-type-status').val(1);
                                $('.registerType span').html('');
                                $('.registerType').html('<i style="color:#4caf50"> (Regular)</i>');
                            }else{
                                $('.company-type-status').val(0);
                                 $('.registerType span').html('');
                                if(data.companyType == 2){
                                    $('.registerType').html('<i style="color:#ffad33"> (Composite)</i>');
                                }
                                if(data.companyType == 3){
                                    $('.registerType').html('<i style="color:#e68a00"> (Unregister)</i>');
                                }
                            }
                            

                            $('.debtors_details').show();
                            $(".billing_addr").css("display", "block");//23032018
                            $(".shipping_addr").css("display", "block");//23032018
                            $(".credit_days_div").css("display", "block");//23032018
                            $(".credit_limit_div").css("display", "block");//23032018
                            
                            //multiple shiping address part start
//                            var multiple_ship = '';
//                            var multiple_ship = '<div class="form-group"><input type="text" class="form-control" id="multiple_ship_contact" placeholder="Select Contact"/></div>';
//                            multiple_ship += '<div class="list-group">';
                            var multiple_ship = '';
                            for (var i = data.multiple_ship.length - 1; i >= 0; i--) {                                
                                multiple_ship += '<a ship-id="'+data.multiple_ship[i].id+'" href="javascript:void(0);" class="list-group-item shippingId"><strong class="c_name">'+data.Sh_companyName+'</strong><br><span class="addr">'+data.multiple_ship[i].address+'</span><br><span class="city">'+data.multiple_ship[i].city+'</span> - <span class="zip">' + data.multiple_ship[i].zip + '</span>, <span class="state">' + data.multiple_ship[i].state_name + '</span>, <span class="country">' + data.multiple_ship[i].country_name + '</span></a>';
                            }
//                            multiple_ship += '</div>';

                            // $("#multipleShipModal").modal('show');
                            $("#multipleShipModal .list-group").html(multiple_ship);
                            //multiple shiping address part end

                        } else {
                            if(data.sale_type == 'cash'){
                                $(".debtors_details").show();//23032018
                                $(".shipping_addr").css("display", "none");//17022018
                                $(".billing_addr").css("display", "none");//23032018
                                $(".credit_days_div").css("display", "none");//23032018
                                $(".credit_limit_div").css("display", "none");//23032018
                                $(".shipping_id").val('');//23032018
                                if (tran_type == 5 || parent_id == 5 || tran_type == 7 || parent_id == 7 || tran_type == 10 || parent_id == 10) {
                                    $('.tr_type_debtors').val('Dr');
                                } else if (tran_type == 6 || parent_id == 6 || tran_type == 8 || parent_id == 8 || tran_type == 9 || parent_id == 9) {
                                    $('.tr_type_debtors').val('Cr');
                                } else if (tran_type == 14 || parent_id == 14) {
                                    $('.tr_type_debtors').val('Cr');
                                } else if (tran_type == 12 || parent_id == 12) {
                                    $('.tr_type_debtors').val('Dr');
                                } else {
                                    $('.tr_type_debtors').val(data.transactionType);
                                }
                                $('.in_ledger_state').val(data.state);
                                $('.in_ledger_country').val(data.country);
                                $('.tr_branch_id').val(data.tr_branch_id);
//                                $('.shipping_addr .state').text(data.state_name);
                                $('.billing_addr .state').text(data.state_name);
                            }else{
                                 Command: toastr["error"](data.message);
                            }
                           
                        }



                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });


            }

        }).focus(function() {
            $(this).autocomplete("search", "");
        }); // autocomplete 

    });



    $('body').delegate('.entry_sales', 'focusin', function() {

        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction_inventory/inventory/getLedgerSales',
                    data: {ledger: request.term, tran_type: tran_type},
                    dataType: "json",
                    success: function(data) {

                        response(data);
                    },
                    error: function(request, error) {
                        console.log(error);
                        //alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label); // display the selected text
                // save selected id to hidden input
                $(this).next(".tr_ledger_id_sales").val(ui.item.value);


                var dataLType = {'action': 'get-ledger-extra-details', ledgerId: ui.item.value};
                // console.log("dataLtype ========= ", dataLType);

                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/admin/getLedgerExtraDetails',
                    data: dataLType,
                    dataType: "json",
                    success: function(data) {
                        if (tran_type == 14 || parent_id == 14) {
                            $('.tr_type_creditors').val('Dr');
                        } else if (tran_type == 12 || parent_id == 12) {
                            $('.tr_type_creditors').val('Cr');
                        } else {
                            $('.tr_type_creditors').val(data.transactionType);
                        }

                    }})


            }

        }).focus(function() {
            $(this).autocomplete("search", "");
        }); // autocomplete 

    });




    /** Discount Allowed Ledger **/

    $('body').delegate('.discount_allowed_ledger', 'focusin', function() {

        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/accounts_inventory/getLedgerRounding',
                    data: "ledger=" + request.term,
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label); // display the selected text
                // save selected id to hidden input
                $(this).next(".discount_allowed_ledger_hidden").val(ui.item.value);

                $(this).closest('tr').find('.discount_allowed_input').attr('disabled', false).val("");

                setTimeout(function() {
                    self.closest('tr').find('.discount_allowed_input').focus();
                }, 500);

            }

        }).focus(function() {
            $(this).autocomplete("search", "");
        }); // autocomplete 

    });



}); // onready

// $(document).on('keypress', 'input,select', function(e){
//     if(e.which == 13){

//     }
// })



$(document).on('keypress', 'input,select', function(e) {
    if (e.which == 13) {
        e.preventDefault();

        var selfInput = $(this);

        if ($(this).hasClass('discount_allowed_input')) {


            calculateDiscount(function(calculatedRounding) {


                setTimeout(function() {
                    $('#netTotal').val(parseFloat(calculatedRounding).toFixed(2));

                    checkTrackingForDiscount(selfInput, function() {

                    });

                    if ($.trim($('.discounts-tr:last .discount_allowed_ledger_hidden').val()) != "" && $.trim($('.discounts-tr:last .discount_value_hidden').val()) != "") {

                        generateNewDiscountRow(function() {
                        });



                    } else {

                        $('.footer-button').show();
                    }


                }, 500);


            });



        } else if ($(this).hasClass('discount_allowed_ledger')) {
            $('.footer-button').show();
        }


        var $canfocus = $(':focusable:not([readonly])');
        var index = $canfocus.index(document.activeElement) + 1;
        if (index >= $canfocus.length)
            index = 0;
        $canfocus.eq(index).focus();


    }


});


$('#trackingModal').on('keypress', '.tr_tracking_amount_modal', function(e) {
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

            $('#trackingModal .tr_tracking_row').each(function() {

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


                    $('#trackingModal .tr_tracking_row').each(function() {

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

                    setTimeout(function() {

                        $('.discounts-tr:last .discount_allowed_ledger').focus();


                    }, 1500);

                    /* remove modal name + hidden id */

                    $('#trackingModal #tr_tracking_ledger_name').html("");
                    $('#trackingModal #tr_tracking_ledger_id_hidden').val("");

                    /* remove all modal fields except 1st */

                    $('#trackingModal .tr_tracking_row').each(function() {
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
                generateNewRowTrackingModal(diff, function() {
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
        url: full_path + 'transaction/admin/getLedgerExtraDetails',
        data: dataLType,
        dataType: "json",
        success: function(data) {

            var ledgerAmt = self.closest('.discounts-tr').find('.discount_value_hidden').val();

            if (data.hasTracking == "1") {
                $('#trackingModal').modal('show');


                setTimeout(function() {
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

                    $('#trackingModal .tr_tracking_row').each(function() {
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





                }


                callback();


            }
        }});



}



// $("#tr_date").inputmask("d/m/y", {placeholder: "dd/mm/yyyy", "oncomplete": function() {
//         $("#Text1").focus();
//     }
// });

//init voucher date
// $("#voucher_date").inputmask("d/m/y", {placeholder: "dd/mm/yyyy", "oncomplete": function() {

//     }});

// $('#tr_date').bind('keyup', 'keydown', function(e) {
//     $.post(full_path + "transaction/accounts_inventory/checkDate", {date: e.target.value}, function(data) {
//         if (data.res) {
//             $("#tr_date").val(data.date);
//         }
//     }, "json");
// });
//voucher date input
// $('#voucher_date').bind('keyup', 'keydown', function(e) {
//     $.post(full_path + "api-get-date-by-finance-year", {date: e.target.value}, function(data) {
//         if (data.res) {
//             $("#voucher_date").val(data.date);
//         }
//     }, "json");
// });


// somnath - new daterange for sales/purchase order date
var monthDate = [0,31,28,31,30,31,30,31,31,30,31,30,31];
var delimeter;

$("#tr_date").keyup(function() {
    
            
    var financial_year;
    
    var lastChar = $(this).val().substr($(this).val().length - 1);
    
    if ( ($(this).val().length ==1 || $(this).val().length == 4) && isNaN(lastChar)) {
        $(this).val( $(this).val().slice(0, -1) );
    }

    if( $(this).val().length == 2 && isNaN(lastChar)) {
        if(lastChar == "." || lastChar == "-" || lastChar == "/"){
            delimeter = lastChar;                
            var arrDate = $("#tr_date").val().split(delimeter);
            $(this).val('0'+arrDate[0]+delimeter);
            
        }else{
           $(this).val( $(this).val().slice(0, -1) ); 
        }
    }
    
    if( $(this).val().length == 5 && isNaN(lastChar)) {
        if(lastChar == "." || lastChar == "-" || lastChar == "/"){ 
            var arrDate = $("#tr_date").val().split(delimeter);
            $(this).val(arrDate[0]+delimeter+'0'+arrDate[1]);
            
        }else{
           $(this).val( $(this).val().slice(0, -1) ); 
        }
    }
    
    // separator should be (.),(/),(-)
    if( $(this).val().length == 3 || $(this).val().length == 6 ) {
        if(lastChar != "." && lastChar != "-" && lastChar != "/"){
            $(this).val( $(this).val().slice(0, -1) );
        }
    }

  // set the user choosen delimeter
  if($(this).val().length == 3){
    delimeter = $(this).val().substr(2);
  }

  if($(this).val().length == 2 && $(this).val() > 31){
    $(this).val(31);
    // $(this).val($(this).val() + '/');
  }else if($(this).val().length == 5){
    var arrStartDate = $("#tr_date").val().split(delimeter);
    
    
    // month cannot be greater than 12
    if(arrStartDate[1] > 12){
      $(this).val( $(this).val().slice(0, -1) );
    }else{

      var month = arrStartDate[1];
      if( arrStartDate[1] < 10 ){
        arrStartDate[1] = month[month.length -1];
      }

      // you can not enter more days than a month can have,
      // like if you enter 31/11 then it automatically changes to 30/11
      // because last day of November is 30 
      if(arrStartDate[0] > monthDate[arrStartDate[1]] && arrStartDate[1] > 9){
        $(this).val( monthDate[arrStartDate[1]] + delimeter + arrStartDate[1] ); // if month is greater than 9 it will show as it is
      }else if(arrStartDate[0] > monthDate[arrStartDate[1]] && arrStartDate[1] < 9){
        $(this).val( monthDate[arrStartDate[1]] + delimeter +'0' + arrStartDate[1] ); // otherwise it will append to 0
      }
      
        var current_url = window.location.protocol + "//" + window.location.hostname;
        var target_url = current_url+"/admin/getCurrentFinancialYearForDateRange";
        // console.log(target_url);
        $.ajax({
            url: target_url,
            type:"POST",
            data:{month:arrStartDate[1]},
            async: false,
            success: function(response){
                financial_year = $.trim(response);
            }              
        });

      $(this).val($(this).val() + delimeter + financial_year);
      // $( ".entry_debtors" ).focus();
    }
    


  }
});

// somnath - new daterange for voucher date
// $("#voucher_date,#modal_bill_lr_rr_date").keyup(function() {
$("body").on('keyup',"#voucher_date,#modal_bill_lr_rr_date",function() {
    
            
    var financial_year;
    
    var lastChar = $(this).val().substr($(this).val().length - 1);
    
    if ( ($(this).val().length ==1 || $(this).val().length == 4) && isNaN(lastChar)) {
        $(this).val( $(this).val().slice(0, -1) );
    }

    if( $(this).val().length == 2 && isNaN(lastChar)) {
        if(lastChar == "." || lastChar == "-" || lastChar == "/"){
            delimeter = lastChar;                
            var arrDate = $(this).val().split(delimeter);
            // var arrDate = $("#voucher_date").val().split(delimeter);
            $(this).val('0'+arrDate[0]+delimeter);
            
        }else{
           $(this).val( $(this).val().slice(0, -1) ); 
        }
    }
    
    if( $(this).val().length == 5 && isNaN(lastChar)) {
        if(lastChar == "." || lastChar == "-" || lastChar == "/"){ 
            // var arrDate = $("#voucher_date").val().split(delimeter);
            var arrDate = $(this).val().split(delimeter);
            $(this).val(arrDate[0]+delimeter+'0'+arrDate[1]);
            
        }else{
           $(this).val( $(this).val().slice(0, -1) ); 
        }
    }
    
    // separator should be (.),(/),(-)
    if( $(this).val().length == 3 || $(this).val().length == 6 ) {
        if(lastChar != "." && lastChar != "-" && lastChar != "/"){
            $(this).val( $(this).val().slice(0, -1) );
        }
    }

  // set the user choosen delimeter
  if($(this).val().length == 3){
    delimeter = $(this).val().substr(2);
  }

  if($(this).val().length == 2 && $(this).val() > 31){
    $(this).val(31);
    // $(this).val($(this).val() + '/');
  }else if($(this).val().length == 5){
    // var arrStartDate = $("#voucher_date").val().split(delimeter);
    var arrStartDate = $(this).val().split(delimeter);
    
    
    // month cannot be greater than 12
    if(arrStartDate[1] > 12){
      $(this).val( $(this).val().slice(0, -1) );
    }else{

      var month = arrStartDate[1];
      if( arrStartDate[1] < 10 ){
        arrStartDate[1] = month[month.length -1];
      }

      // you can not enter more days than a month can have,
      // like if you enter 31/11 then it automatically changes to 30/11
      // because last day of November is 30 
      if(arrStartDate[0] > monthDate[arrStartDate[1]] && arrStartDate[1] > 9){
        $(this).val( monthDate[arrStartDate[1]] + delimeter + arrStartDate[1] ); // if month is greater than 9 it will show as it is
      }else if(arrStartDate[0] > monthDate[arrStartDate[1]] && arrStartDate[1] < 9){
        $(this).val( monthDate[arrStartDate[1]] + delimeter +'0' + arrStartDate[1] ); // otherwise it will append to 0
      }
      
        var current_url = window.location.protocol + "//" + window.location.hostname;
        var target_url = current_url+"/admin/getCurrentFinancialYearForDateRange";
        // console.log(target_url);
        $.ajax({
            url: target_url,
            type:"POST",
            data:{month:arrStartDate[1]},
            async: false,
            success: function(response){
                financial_year = $.trim(response);
            }              
        });

      $(this).val($(this).val() + delimeter + financial_year);
      // $( ".search_product" ).focus();
      // $(this).nextAll('input.form-control').first().focus();
      // $(this).nextAll('input:first').focus();
      $(this).parent('.form-group').siblings('.form-group').closest('input').focus();
    }
    


  }
});





$(document).ready(function() {
    $('#totalFormSubmitBtn').on('keypress', function(e) {
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



    $('.discounts-tr').each(function() {
        var getLedgeId = $(this).find('.discount_allowed_ledger_hidden').val();

        // check if local storage exists
        LS_data_tracking = localStorage.getItem('ledgerTrackingId' + getLedgeId);

        if (LS_data_tracking !== null) {

            var tracking_data = {'ledgeId': getLedgeId, value: LS_data_tracking};
            LS_tracking_get.push(tracking_data);
        }

    });
    
    $('.each-product').each(function() {
         var getProductId = $(this).find('.stock_id_hidden').val();
        LS_data_batching = localStorage.getItem('batchGodownStockId_' + getProductId);
        if (LS_data_batching !== null) {
            var batch_data = {'product_id': getProductId, value: LS_data_batching};
            LS_godown_batch_get.push(batch_data);
        }
    })


    callback(LS_tracking_get,LS_godown_batch_get);


}

function formToJSON(selector) {
    var form = {};

    $(selector).find(':input[name]:enabled').each(function() {
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



$(".formSubmitAll").submit(function(event) {

    event.preventDefault();
    var self = $(this);

    var newReferenceLedgerArray = [];



    var extraFuncFlag = 0;

    var newRefCall = 0;

    if (extraFuncFlag >= 2) {
        newRefCall = 1;
    } else {
        newRefCall = 0;
    }

    var currency = $("#selected_currency").val();
    var postdated = $('input[name="postdated"]:checked').val();
    var type_service_product = $('input[name="service_product"]:checked').val();
    var reverse_entry = $('input[name="reverse_entry"]:checked').val();
    getAllStorageItems(function(trackingArr,batchGodownArr) {

        var extra = {tracking: trackingArr,batchGodown: batchGodownArr, newRefCall: newRefCall, newReferenceLedgerArray: newReferenceLedgerArray, entry_type: transaction_type_id, parent_id: parent_id, currency: currency, postdated: postdated, type_service_product: type_service_product, reverse_entry: reverse_entry};



        var l = Ladda.create(document.querySelector('.ladda-button'));
        l.start();

        // entry date is stored in session storage for further use
        var cur_entry_date = $("#tr_date").val();
        sessionStorage.setItem('entry_date', cur_entry_date);

        var form = self,
                url = form.attr('action'),
                formData = form.serialize() + '&' + $.param(extra);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(data) {
                // console.log(data);return false;
                l.stop();

                $('.form-group').removeClass('has-error');

                if (data.res == 'error') {

                    Command: toastr["error"](data.message);
                } else if (data.res == 'save_err') {
                    Command: toastr["error"](data.message);
                } else {
                    var activity = $('input[name="activity_submit"]:checked').val();
                    Command: toastr["success"](data.message);

                    localStorage.clear();
                    if (parseInt(activity) == 1 || data.type == 'undefined') {
                        location.reload();
                    } else if (parseInt(activity) == 2) {
                        if (data.redirect_url != 'undefined') {
                            window.location.href = data.redirect_url;
                        }
                    } else if (parseInt(activity) == 3) {
                        window.location.href = data.print_url;
                    } else if (data.type != 'undefined') {
                        window.location.href = data.redirect_url;
                    }
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


            },
            error: function(response){
                // console.log(response);
            }
        });


        //console.log("2nd ============================= ", formData);

    });


});

//for BOM 

$('body').delegate('.out_search_product', 'focusin', function() {

    var self = $(this);

    $(this).autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: full_path + 'transaction/accounts_inventory/getProducts',
                data: "term=" + request.term,
                dataType: "json",
                success: function(data) {
                    response(data);
                    //console.log(data);
                },
                error: function(request, error) {
                    //alert('connection error. please try again.');
                }
            });
        },
        minLength: 0,
        select: function(e, ui) {
            var self = $(this);
            e.preventDefault(); // <--- Prevent the value from being inserted.
            $(this).val(ui.item.label); // display the selected text

            var pId = ui.item.value;
            var productName = ui.item.label;

            //$(this).next(".search_product_id").val(ui.item.value); // save selected id to hidden input

            $.ajax({
                type: "POST",
                url: full_path + 'transaction/accounts_inventory/getProductDetailsBOM',
                data: "pId=" + pId,
                dataType: "json",
                success: function(data) {

                    var price = parseFloat(data['productPrice']);
                    var taxPerc = parseFloat(data['productTax']);
                    var productUnitName = $.trim(data['productUnitName']);
                    var productUnitHiddenId = data['productUnitId'];
                    var stockId = data['stockId'];
                    var bom_type = 'out';

                    
                    generateNewProductRowBOM(pId, stockId, productName, price, taxPerc, productUnitName, productUnitHiddenId, bom_type, function() {

                        var $lastProduct = $('.out-product-listing-table tr.each-product:last');

                        $lastProduct.find('.product-quantity').focus();


                        self.val("");

                    });


                    /* Generate new product row with values  */



                },
                error: function(request, error) {
                    //alert('connection error. please try again.');
                }
            });

        }

    }).focus(function() {
        $(this).autocomplete("search", "");
    });

});   // autocomplete 


$('body').delegate('.in_search_product', 'focusin', function() {

    var self = $(this);

    $(this).autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: full_path + 'transaction/accounts_inventory/getProducts',
                data: "term=" + request.term,
                dataType: "json",
                success: function(data) {
                    response(data);
                    //console.log(data);
                },
                error: function(request, error) {
                    //alert('connection error. please try again.');
                }
            });
        },
        minLength: 0,
        select: function(e, ui) {
            var self = $(this);
            e.preventDefault(); // <--- Prevent the value from being inserted.
            $(this).val(ui.item.label); // display the selected text

            var pId = ui.item.value;
            var productName = ui.item.label;

            //$(this).next(".search_product_id").val(ui.item.value); // save selected id to hidden input

            $.ajax({
                type: "POST",
                url: full_path + 'transaction/accounts_inventory/getProductDetailsBOM',
                data: "pId=" + pId,
                dataType: "json",
                success: function(data) {
                    // response(data);


                    var price = parseFloat(data['productPrice']);
                    var taxPerc = parseFloat(data['productTax']);
                    var productUnitName = $.trim(data['productUnitName']);
                    var productUnitHiddenId = data['productUnitId'];
                    var stockId = data['stockId'];
                    var bom_type = 'in';
                    
                    generateNewProductRowBOM(pId, stockId, productName, price, taxPerc, productUnitName, productUnitHiddenId, bom_type, function() {

                        var $lastProduct = $('.in-product-listing-table tr.each-product:last');

                        $lastProduct.find('.product-quantity').focus();


                        self.val("");

                    });


                    /* Generate new product row with values  */



                },
                error: function(request, error) {
                    //alert('connection error. please try again.');
                }
            });

        }

    }).focus(function() {
        $(this).autocomplete("search", "");
    });

});   // autocomplete 


//advance bill
$('body').delegate('.advance_bill_name', 'focusin', function() {
    var ledger_id = $(".tr_ledger_id_debtors").val();
    if (!ledger_id) {
        Command: toastr["error"]("Please select ledger first.");
    } else {
        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction_inventory/inventory/getAdvanceBill',
                    data: "bill_name=" + request.term + "&ledger_id=" + ledger_id,
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.value); // display the selected text

            }

        }).focus(function() {
            $(this).autocomplete("search", "");
        }); // autocomplete 
    }
});

function checkGroup(obj) {
    var group_id = $(obj).val();
    $.ajax({
        type: "POST",
        url: full_path + 'accounts/accounts/checkGroup',
        data: "group_id=" + group_id,
        dataType: "json",
        success: function(data) {
            if (data.res) {
                $("#contact_required").val(1);
            } else {
                $("#contact_required").val(0);
            }
        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });
}


$(document).on("change", "input[type=radio]", function() {
    var branch_entry_no = $('[name="select_branch_entry_no"]:checked').val();
    if (parseInt(branch_entry_no) == 1) {
        $("#branch-div").css("display", "block");
    } else {
       $('input[name="branch_entry_no"]').val('');
       $("#branch-div").css("display", "none");
    }
});



$("body").delegate(".product-price, .product-quantity", 'focus', function () {
   $(this).select();
});


//multiple shiping address part start

$('body').delegate('#multiple_ship_contact', 'focusin', function() {
    $(this).autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "GET",
                url: full_path + 'transaction_inventory/inventory/getAllContacts',
                data: {q: request.term},
                dataType: "json",
                success: function(data) {
                    response(data);
                    // console.log(data);
                },
                error: function(request, error) {
                    //alert('connection error. please try again.');
                }
            });
        },
        minLength: 0,
        select: function(e, ui) {
            var self = $(this);
            e.preventDefault(); // <--- Prevent the value from being inserted.
            $(this).val(ui.item.text); // display the selected text

            var contact_id = ui.item.id;
            $.ajax({
                type: "POST",
                url: full_path + 'transaction_inventory/inventory/getShippingAddress',
                data: {contact_id: contact_id},
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    // var multiple_ship = '<div class="form-group"><input type="text" class="form-control" id="multiple_ship_contact" placeholder="Select Contact"/></div>';
//                     var multiple_ship = '<div class="list-group">';
                    var tr_ledger_id_debtors = $('.tr_ledger_id_debtors').val();
                    if(tr_ledger_id_debtors != ''){
                        var multiple_ship = '';
                        for (var i = data.length - 1; i >= 0; i--) {                                
    //                        multiple_ship += '<a ship-id="'+data[i].id+'" href="javascript:void(0);" class="list-group-item">'+data[i].address+'<br>'+data[i].city+' - ' + data[i].zip + ', ' + data[i].state_name + ', ' + data[i].country_name + '</a>';
                             multiple_ship += '<a ship-id="'+data[i].id+'" href="javascript:void(0);" class="list-group-item shippingId"><strong class="c_name">'+data[i].company_name+'</strong><br><span class="addr">'+data[i].address+'</span><br><span class="city">'+data[i].city+'</span> - <span class="zip">' + data[i].zip + '</span>, <span class="state">' + data[i].state_name + '</span>, <span class="country">' + data[i].country_name + '</span></a>';
                        }
                    }
                    
//                     multiple_ship += '</div>';

                    $("#multipleShipModal").modal('show');
                    $("#multipleShipModal .list-group").html(multiple_ship);
                }
            });

        }

    }).focus(function() {
        $(this).autocomplete("search", "");
    }); // autocomplete 
});

//multiple shiping address part end

$("body").delegate(".list-group, .shippingId", 'click', function (e) {
   e.stopPropagation();
   $(".shipping_addr").css("display", "block");//17022018
   var self = $(this);
    $('.shipping_addr .c_name').text(self.find('.c_name').text());
    $('.shipping_addr .addr').text(self.find('.addr').text());
    $('.shipping_addr .city').text(self.find('.city').text());
    $('.shipping_addr .zip').text(self.find('.zip').text());
    $('.shipping_addr .state').text(self.find('.state').text());
    $('.shipping_addr .country').text(self.find('.country').text());
    $('.shipping_addr .shipping_id').val(self.attr('ship-id'));
    $("#multipleShipModal").modal('hide');
});


$("body").delegate("#bankForInvoiceForm, #bank_id", 'change', function (e) {
   e.stopPropagation();
   var self = $(this);
   $('.invice_number #bank_id').val(self.val());
});

$("body").delegate("#courierForInvoiceSave", 'click', function (e) {
   e.stopPropagation();
   $('#despatch_doc_no').val($('#modal_despatch_doc_no').val());
   $('#despatch_through').val($('#modal_despatch_through').val());
   $('#courier_gstn').val($('#modal_courier_gstn').val());
   $('#destination').val($('#modal_destination').val());
   $('#bill_lr_rr').val($('#modal_bill_lr_rr').val());
   $('#bill_lr_rr_date').val($('#modal_bill_lr_rr_date').val());
   $('#motor_vehicle_no').val($('#modal_motor_vehicle_no').val());
});

    
    $("body").delegate("#modal_despatch_through", 'focusin', function (e) {
        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction_inventory/inventory/showCourierModalInvoice',
                    data: {despatch_through: request.term},
                    dataType: "json",
                    success: function(data) {

                        response(data);
                    },
                    error: function(request, error) {
                        //alert('connection error. please try again.');
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label); // display the selected text
                // save selected id to hidden input          
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction_inventory/inventory/despatchDetailsById',
                    data: {courier_id: ui.item.value},
                    dataType: "json",
                    success: function(DATA) {
                        if(DATA.FLAG){
                            $('#modal_despatch_doc_no').val(DATA.RESULT.despatch_doc_no);
                            $('#modal_despatch_through').val(DATA.RESULT.despatch_through);
                            $('#modal_courier_gstn').val(DATA.RESULT.courier_gstn);
                            $('#modal_destination').val(DATA.RESULT.destination);
                            $('#modal_bill_lr_rr').val(DATA.RESULT.bill_lr_rr);
                            $('#modal_bill_lr_rr_date').val(DATA.RESULT.bill_lr_rr_date);
                            $('#modal_motor_vehicle_no').val(DATA.RESULT.motor_vehicle_no);
                        }
                    }
                });
                
            }

        }).focus(function() {
            $(this).autocomplete("search", "");
        }); // autocomplete 
    });
    
//    $("body").delegate('#eway-bill-download-pdf','click',function(e){
    $('#eway-bill-download-pdf').click(function() {
        alert('sdfs');
    })
    
    $("body").delegate(".product-unit", "focusin", function() {
        var unit_id = $(this).closest('.each-product').find('.product-unit-hidden-id').val();
        $(this).autocomplete({
            source:function(request, response) {
                $.ajax({
                    url: full_path + "transaction_inventory/inventory/getComplexUnitById",
                    type: "POST",
                    cache: false,
                    data: { unit_id: unit_id },
                    dataType: 'JSON',
                    success: function(res) {
                        response(res);
                    },
                    error: function(res) {
                        // console.log(res);
                    }
                });
            },
            minLength:0,
            select: function(e, ui) {
                e.preventDefault();
                $(this).closest('.each-product').find('.product-unit').val(ui.item.value);
                $(this).closest('.each-product').find('.product-complex-unit-hidden-id').val(ui.item.id);
                $(this).closest('.each-product').find('.product-price').val('');
            }
        }).focus(function() {
            $(this).autocomplete("search", "");
        });
    });
