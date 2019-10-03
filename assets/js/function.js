
function split(val) {
    return val.split(/,\s*/);
}

function extractLast(term) {
    return split(term).pop();
}

function removeerrorclass()
{
    //add Customer
    $('#customerfirstname').removeClass('errorclass');
    $('#customerlastname').removeClass('errorclass');
    $('#customeremail').removeClass('errorclass');
    $('#customerphone').removeClass('errorclass');
    $('#customeraddress').removeClass('errorclass');
    $('#addcustomercountry').removeClass('errorclass');
    $('#addcustomerstate').removeClass('errorclass');
    $('#addcustomercity').removeClass('errorclass');
    $('#customerzip').removeClass('errorclass');
    $('#customerpassword').removeClass('errorclass');
    $('#customerdateofbirth').removeClass('errorclass');
    $('#customerusername').removeClass('errorclass');

    //add Shipping Address

    $('#addshippingaddress').removeClass('errorclass');
    $('#addshippingcountry').removeClass('errorclass');
    $('#addshippingstate').removeClass('errorclass');
    $('#addshippingcity').removeClass('errorclass');
    $('#addshippingzip').removeClass('errorclass');

    //add Billing Address

    $('#addbillingaddress').removeClass('errorclass');
    $('#addbillingcountry').removeClass('errorclass');
    $('#addbillingstate').removeClass('errorclass');
    $('#addbillingcity').removeClass('errorclass');
    $('#addbillingzip').removeClass('errorclass');
}

function fetchstate(countryid, containerid)
{
    $.ajaxSetup({cache: false});

    var loadUrl = fetchstate_url + countryid;
    $.ajax({
        type: "POST",
        url: loadUrl,
        dataType: "html",
        data: {countryid: countryid},
        success: function(responseText)
        {
            if (containerid == 'customer')
            {
                $("#addcustomerstate").html(responseText);
                $("#addcustomercity").val('');
            } else if (containerid == 'shipping')
            {
                $("#adddefaultshippingstate").html(responseText);
                $("#adddefaultshippingcity").val('');
            } else if (containerid == 'billing')
            {
                $("#adddefaultbillingstate").html(responseText);
                $("#adddefaultbillingcity").val('');
            } else if (containerid == 'addshipping')
            {
                $("#addshippingstate").html(responseText);
                $("#addshippingcity").val('');
            } else if (containerid == 'addbilling')
            {
                $("#addbillingstate").html(responseText);
                $("#addbillingcity").val('');
            }
        },
        error: function(jqXHR, exception) {
            return false;
        }
    });
    return false;
}

function fetchcities(stateid, containerid)
{
    $.ajaxSetup({cache: false});
    var loadUrl = fetchcities_url + stateid;
    $.ajax({
        type: "POST",
        url: loadUrl,
        dataType: "html",
        data: {stateid: stateid},
        success: function(responseText)
        {
            if (containerid == 'customer')
                $("#addcustomercity").html(responseText);
            else if (containerid == 'shipping')
                $("#adddefaultshippingcity").html(responseText);
            else if (containerid == 'billing')
                $("#adddefaultbillingcity").html(responseText);
            else if (containerid == 'addshipping')
                $("#addshippingcity").html(responseText);
            else if (containerid == 'addbilling')
                $("#addbillingcity").html(responseText);
        },
        error: function(jqXHR, exception) {
            return false;
        }
    });
    return false;
}

function getAge(birth)
{
    var today = new Date();
    var nowyear = today.getFullYear();
    var nowmonth = today.getMonth();
    var nowday = today.getDate();
    var birthdayDate = new Date(birth);
    var birthyear = birthdayDate.getFullYear();
    var birthmonth = birthdayDate.getMonth();
    var birthday = birthdayDate.getDate();
    var age = nowyear - birthyear;
    var age_month = nowmonth - birthmonth;
    var age_day = nowday - birthday;
    if (age_month < 0 || (age_month == 0 && age_day < 0)) {
        age = parseInt(age) - 1;
    }
    return age;
}

function checkPhoto(elvobj)
{
    var fileUpload = $("#customerimage")[0];
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.png|.gif|.bmp|.tiff|.exif)$");
    if (regex.test(fileUpload.value.toLowerCase())) {
        if (typeof (fileUpload.files) != "undefined") {
            var reader = new FileReader();
            reader.readAsDataURL(fileUpload.files[0]);
            reader.onload = function(e) {
                var image = new Image();
                image.src = e.target.result;
                image.onload = function() {
                    var height = this.height;
                    var width = this.width;
                    if (height < 200 || width < 200)
                    {
                        alert("image height or width cannot be less then 200px.");
                        var $el = $('#customerimage');
                        $el.wrap('<form>').closest('form').get(0).reset();
                        $el.unwrap();
                        return false;
                    }
                    return true;
                };
            }
        } else {
            alert("This browser does not support HTML5.");
            var $el = $('#customerimage');
            $el.wrap('<form>').closest('form').get(0).reset();
            $el.unwrap();
            return false;
        }
    } else {
        alert("Please select a valid Image file.");
        var $el = $('#customerimage');
        $el.wrap('<form>').closest('form').get(0).reset();
        $el.unwrap();
        return false;
    }
}

function check_dup_email(email)
{
    if (email == "")
        $("#is_dup_email").val(0);
    else
    {
        $.ajax({
            type: "POST",
            data: "&email=" + email,
            url: checkemail_url,
            cache: false,
            success: function(msg) {
                if (msg == 'false')
                    $("#is_dup_email").val(1);
                else
                    $("#is_dup_email").val('');
            }
        });
    }
}


function saveshippingaddress()
{
    var flag = 0;
    var addshippingaddress = $("#addshippingaddress").val();
    var addshippingcountry = $("#addshippingcountry").val();
    var addshippingstate = $("#addshippingstate").val();
    var addshippingcity = $("#addshippingcity").val();
    var addshippingzip = $("#addshippingzip").val();
    var validRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if (addshippingaddress.search(/\S/) == -1) {
        $('#addshippingaddress').val('');
        $('#addshippingaddress').attr('placeholder', 'Address...');
        $('#addshippingaddress').addClass('errorclass');
        flag = 1;
    }

    if (addshippingcountry.search(/\S/) == -1) {
        $('#addshippingcountry').val('');
        $('#addshippingcountry').addClass('errorclass');
        flag = 1;
    }

    if (addshippingstate.search(/\S/) == -1) {
        $('#addshippingstate').val('');
        $('#addshippingstate').addClass('errorclass');
        flag = 1;
    }

    if (addshippingcity.search(/\S/) == -1) {
        $('#addshippingcity').val('');
        $('#addshippingcity').addClass('errorclass');
        flag = 1;
    }

    if (addshippingzip.search(/\S/) == -1) {
        $('#addshippingzip').val('');
        $('#addshippingzip').addClass('errorclass');
        flag = 1;
    }

    if (flag != 0)
        return false;

    $("#customerdatacontainer").hide();
    $("#customerdatacontainerloader").show();
    $.ajaxSetup({cache: false});
    var loadUrl = saveshippingaddress_url;
    var formdata = $("#add_order_form").serialize();
    $.ajax({
        type: "POST",
        url: loadUrl,
        dataType: "html",
        data: formdata,
        success: function(responseText)
        {
            $("#shippingclosebtn").click();
            $("#shippingaddbtn").show();
            $("#billingaddbtn").show();
            $('#addshippingaddress').val('');
            $('#addshippingcountry').val('');
            $('#addshippingstate').val('');
            $('#addshippingcity').val('');
            $('#addshippingzip').val('');
            $("#customershippingaddress").html(responseText);
            $("#hidcustomershippingaddress").val(responseText);
            $("#customerdatacontainerloader").hide();
            $("#customerdatacontainer").show();
        },
        error: function(jqXHR, exception) {
            return false;
        }
    });
    return false;
}

function savebillingaddress()
{
    var flag = 0;
    var addbillingaddress = $("#addbillingaddress").val();
    var addbillingcountry = $("#addbillingcountry").val();
    var addbillingstate = $("#addbillingstate").val();
    var addbillingcity = $("#addbillingcity").val();
    var addbillingzip = $("#addbillingzip").val();
    var validRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if (addbillingaddress.search(/\S/) == -1) {
        $('#addbillingaddress').val('');
        $('#addbillingaddress').attr('placeholder', 'Address...');
        $('#addbillingaddress').addClass('errorclass');
        flag = 1;
    }

    if (addbillingcountry.search(/\S/) == -1) {
        $('#addbillingcountry').val('');
        $('#addbillingcountry').addClass('errorclass');
        flag = 1;
    }

    if (addbillingstate.search(/\S/) == -1) {
        $('#addbillingstate').val('');
        $('#addbillingstate').addClass('errorclass');
        flag = 1;
    }

    if (addbillingcity.search(/\S/) == -1) {
        $('#addbillingcity').val('');
        $('#addbillingcity').addClass('errorclass');
        flag = 1;
    }

    if (addbillingzip.search(/\S/) == -1) {
        $('#addbillingzip').val('');
        $('#addbillingzip').addClass('errorclass');
        flag = 1;
    }

    if (flag != 0)
        return false;

    $("#customerdatacontainer").hide();
    $("#customerdatacontainerloader").show();
    $.ajaxSetup({cache: false});
    var loadUrl = savebillingaddress_url;
    var formdata = $("#add_order_form").serialize();
    $.ajax({
        type: "POST",
        url: loadUrl,
        dataType: "html",
        data: formdata,
        success: function(responseText)
        {
            $("#billingclosebtn").click();
            $("#shippingaddbtn").show();
            $("#billingaddbtn").show();
            $('#addbillingaddress').val('');
            $('#addbillingcountry').val('');
            $('#addbillingstate').val('');
            $('#addbillingcity').val('');
            $('#addbillingzip').val('');
            $("#customerbillingaddress").html(responseText);
            $("#hidcustomerbillingaddress").val(responseText);
            $("#customerdatacontainerloader").hide();
            $("#customerdatacontainer").show();
        },
        error: function(jqXHR, exception) {
            return false;
        }
    });
    return false;
}

function getCalculateShippingRate(weight)
{
    if (is_shipping == 'Y')
    {
        var ary_temp_data = shippingrate['weight']['shipname'];
        var shipcost = 0;

        for (var i = 0; i < ary_temp_data.length; i++)
        {
            var minval = shippingrate['weight']['minval'][i];
            var maxval = shippingrate['weight']['maxval'][i];
            if (weight >= minval && weight <= maxval)
            {
                shipcost = parseFloat(shippingrate['weight']['cost'][i]);
            }
        }

        return shipcost.toFixed(2);
    } else
    {
        return 0;
    }
}

function updateQtn(action, id)
{
    var newSubTotal = 0;
    var special_discountAmt = 0;
    var current_qtn = parseInt($('.mainTr' + id).find('.product_quantity').val());
    var base_price = parseFloat($('.mainTr' + id).find('.product-price').val());
    var base_discount_amt = 0;
    if ($('.discountTr' + id).find('.discount_amt').length > 0)
    {
        base_discount_amt = parseFloat($('.discountTr' + id).find('.discount_amt').html()) / current_qtn;
    }
    var base_tax_amt = parseFloat($('.taxTr' + id).find('.tax_amt').html()) / current_qtn;
    var newqtn = 0;
    var weight = parseFloat($('.hiddenTr' + id).attr('data-weight'));
    var total_weight = 0;
    //alert(base_price+'--'+base_discount_amt+'--'+base_tax_amt);
    if (action == 'add')
    {
        newqtn = current_qtn + 1;
    } else if (action == 'none') {
        newqtn = current_qtn;
    } else
    {
        newqtn = current_qtn - 1;
    }
    $('.mainTr' + id).find('.product_quantity').val(newqtn);
    $('.mainTr' + id).find('.product_quantity').attr('value',newqtn);
    $('.hiddenTr' + id).find('input[name="product_qtn[]"]').val(newqtn);
     $('.hiddenTr' + id).find('input[name="product_qtn[]"]').attr('value',newqtn);
    var new_total_amt = (base_price + base_tax_amt - base_discount_amt) * newqtn;
    var new_discount = base_discount_amt * newqtn;
    var new_tax = base_tax_amt * newqtn;
    $('.discountTr' + id).find('.discount_amt').html(new_discount.toFixed(2));
    $('.taxTr' + id).find('.tax_amt').html(new_tax.toFixed(2));
    $('.finalTr' + id).html(new_total_amt.toFixed(2));
    $('.finalPrice').each(function() {
        newSubTotal = newSubTotal + parseFloat($(this).html());
    });

    $('#subtotaldisplay').html(newSubTotal.toFixed(2));
    $('#subtotal').val(newSubTotal.toFixed(2));

    if (action == 'add')
    {
        total_weight = parseFloat($('#total_weight').val()) + weight;
    } else if (action == 'none') {
        total_weight = parseFloat($('#total_weight').val());
    } else
    {
        total_weight = parseFloat($('#total_weight').val()) - weight;
    }
    $('#total_weight').val(total_weight);
    var shipping_rate = getCalculateShippingRate(total_weight);
    $('#ed_inv_ship').text(shipping_rate);
    $('#added_shipping_amount').val(shipping_rate);

    if ($('#spl_disc_type').val() != '')
    {
        var discount_type = $('#spl_disc_type').val();
        var discount_percent = $('#spl_discount_amount').val();
        if (discount_type == 'A')
        {
            special_discountAmt = parseFloat($('#spl_discount_val').val());
        } else
        {
            special_discountAmt = ((newSubTotal * discount_percent) / 100);
        }

        $('#spl_discount_val').val(special_discountAmt);
        $('#spl_discount_val_display').html(special_discountAmt.toFixed(2));
    }
    newGrandTotal = parseFloat(newSubTotal) + parseFloat(shipping_rate) - special_discountAmt;
    $('#grandtotaldisplay').html(newGrandTotal.toFixed(2));
    $('#grandtotal').val(newGrandTotal.toFixed(2));
    console.log(newGrandTotal.toFixed(2))
    $('#amount_0').attr('value', newGrandTotal.toFixed(2));
    $('#amount_0').val(newGrandTotal.toFixed(2));
    //add tax row
    var _tax = 0;
    var tax_arr = $(".tax_amt");
    $.each(tax_arr, function(index, element) {
        _tax = _tax + parseFloat($(element).html());
    });
    $("#taxdisplay").html(_tax);
    var final_subtotal = parseFloat(newGrandTotal) - parseFloat(_tax);
    $('#subtotaldisplay').html(final_subtotal);
    var order_type = $("#order_type").val();
    console.log(order_type);
    console.log(_tax);
    if (_tax != 0) {
        var tax_html = '';
        tax_html += '<br>';
        tax_html += '<div class="row">';
        if (order_type == 'sales' || order_type == 'delivery-notes') {
            tax_html += '<input type="hidden" name="account[]" id="account_type_3" class="form-control account-type" value="Cr" readonly="true">';
            tax_html += '<div class="col-md-3"><input type="text" name="ledger[]" id="ledger_3" class="form-control sales-ledger" value="VAT Input 5"><input type="hidden" name="ledger_id[]" value="23"></div>';
            tax_html += '<div class="col-md-3"><input type="text" name="amount[]" id="amount_3" class="form-control" value="' + _tax + '" readonly="true"></div>';
            tax_html += ' <div class="col-md-3"></div>';
        } else if (order_type == 'purchase' || order_type == 'receive-notes') {
            tax_html += '<input type="hidden" name="account[]" id="account_type_3" class="form-control account-type" value="Dr" readonly="true">';
            tax_html += '<div class="col-md-3"><input type="text" name="ledger[]" id="ledger_3" class="form-control sales-ledger" value="VAT Output 5"><input type="hidden" name="ledger_id[]" value="24"></div>';
            tax_html += '<div class="col-md-3"><input type="text" name="amount[]" id="amount_3" class="form-control" value="' + _tax + '" readonly="true"></div>';
            tax_html += ' <div class="col-md-3"></div>';
        }
        tax_html += '</div>';
        $("#tax_box").html(tax_html);
        console.log(_tax);
    }
    //end add tax row
    //add discount row
    var _discount = 0;
    var dis_arr = $(".discount_amt");
    $.each(dis_arr, function(index, element) {
        _discount = _discount + parseFloat($(element).html());
    });
    $("#total_discount").html(_discount);
    var order_type = $("#order_type").val();
    if (_discount != 0) {
        var dis_html = '';
        dis_html += '<br>';
        dis_html += '<div class="row">';
        if (order_type == 'sales') {
            dis_html += '<input type="hidden" name="account[]" id="account_type_4" class="form-control account-type" value="Dr" readonly="true">';
            dis_html += '<div class="col-md-3"><input type="text" name="ledger[]" id="ledger_4" class="form-control sales-ledger" value="Discount Allowed"><input type="hidden" name="ledger_id[]" value="38"></div>';
            dis_html += '<div class="col-md-3"><input type="text" name="amount[]" id="amount_4" class="form-control" value="' + _discount + '" readonly="true"></div>';
            dis_html += ' <div class="col-md-3"></div>';
        } else if (order_type == 'purchase') {
            dis_html += '<input type="hidden" name="account[]" id="account_type_4" class="form-control account-type" value="Cr" readonly="true">';
            dis_html += '<div class="col-md-3"><input type="text" name="ledger[]" id="ledger_4" class="form-control sales-ledger" value="Discount Receivable"><input type="hidden" name="ledger_id[]" value="37"></div>';
            dis_html += '<div class="col-md-3"><input type="text" name="amount[]" id="amount_4" class="form-control" value="' + _discount + '" readonly="true"></div>';
            dis_html += ' <div class="col-md-3"></div>';
        }
        dis_html += '</div>';
        $("#discount_box").html(dis_html);
    }
    //end add discount row
    var sales_amount = parseFloat(newGrandTotal.toFixed(2)) - parseFloat(_tax) - parseFloat(_discount);
    $('#amount_1').attr('value', sales_amount);
    $('#amount_1').val(sales_amount);
    var sum_dr = parseFloat(newGrandTotal.toFixed(2)) + parseFloat(_discount);
    var sum_cr = parseFloat(sales_amount) + parseFloat(_tax);
    $("#sum_dr").val(sum_dr);
    $("#sum_cr").val(sum_cr);
}

function paid_unpaid_order(elmval)
{
    $('.zunpaidz').addClass("notActive");
    $('.zpaidz').addClass("notActive");
    if (elmval == 'P')
    {
        $('#is_paid').val("1");
        $('.zpaidz').removeClass("notActive");
        $('.zunpaidz').addClass("Active");
    } else
    {
        $('#is_paid').val("0");
        $('.zunpaidz').removeClass("notActive");
        $('.zpaidz').addClass("Active");
    }
}

function closeshippingclosebtn()
{
    $("#shippingclosebtn").click();
}

function closebillingclosebtn()
{
    $("#billingclosebtn").click();
}

$(document).on("click", ".btn-spl-disc", function() {
    var spl_distype = $(this).data('disc-type');
    $('.btn-spl-disc').removeClass('active');
    $(this).addClass('active');
    $('#spl_disc_type').val(spl_distype);
});

$(document).on("click", "#discountpopaddbtn", function() {
    var disamt = parseFloat($("#disctxtbox").val());
    if (disamt > 0)
    {
        var previousdiscount = parseFloat($('#spl_discount_val').val());
        var subtotal = parseFloat($('#subtotal').val());
        var discount_type = $('#spl_disc_type').val();
        var spl_disc_reason = $('#discresntxtbox').val();
        var discount_minus_amt = 0;
        if (discount_type == 'P')
        {
            discount_minus_amt = (subtotal * disamt) / 100;
        } else
        {
            discount_minus_amt = disamt;
        }

        $('#spl_discount_val').val(discount_minus_amt.toFixed(2));
        $('#spl_discount_val_display').html(discount_minus_amt.toFixed(2));
        $('#spl_discount_amount').val(disamt);
        $('#spl_disc_reason').val(spl_disc_reason);

        var grandtotal = parseFloat($('#grandtotal').val());
        if (grandtotal > 0)
        {
            var newGrand = (grandtotal + previousdiscount - discount_minus_amt).toFixed(2);
            $('#grandtotal').val(newGrand);
            $('#grandtotaldisplay').html(newGrand);
        }
        $("#discountpopclosebtn").click();
    }
});
