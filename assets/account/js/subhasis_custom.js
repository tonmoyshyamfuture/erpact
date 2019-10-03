var base_url = location.protocol + '//' + location.host+'/';

function get_states(id, url) {

    jQuery.ajax({
        url: base_url + url,
        type: 'POST',
        dataType: 'json',
        data: 'country_id=' + id,
        cache: false,
        success: function(data, textStatus, jqXHR) {
            console.log(data);
            var option_string = '<option  value="">Select..</option>';
            for (var i = 0; i < data.length; i++) {
                option_string += '<option  value="' + data[i]['id'] + '">' + data[i]['state_name'] + '</option>';
            }

            jQuery('#select2_sample5').html(option_string);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}

function get_citis(state_id, url) {

    jQuery.ajax({
        url: base_url + url,
        type: 'POST',
        dataType: 'json',
        data: 'state_id=' + state_id,
        cache: false,
        success: function(data, textStatus, jqXHR) {
            console.log(data);
            var option_string = '<option  value="">Select..</option>';
            for (var i = 0; i < data.length; i++) {
                option_string += '<option  value="' + data[i]['city_id'] + '">' + data[i]['city_name'] + '</option>';
            }

            jQuery('#select2_sample6').html(option_string);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}

//company info script
$(document).ready(function() {

    function billing_status_check() {
        var dr_total_amount = parseFloat($('#sum_dr').html());
        var cr_total_amount = parseFloat($('#sum_cr').html());
        var inc_id = 0;
        if (dr_total_amount != cr_total_amount) {
            inc_id = increment_id - 1;
        } else {
            inc_id = increment_id;
        }
        var ledger = $('#select2_sample_' + inc_id).val();
        console.log(ledger);
        var url = base_url + 'index.php/accounts/entries/get_ledger_id';
        var queryString = "ledger=" + ledger + '&ajax=1';
        $.ajax({
            type: "POST",
            url: url,
            data: queryString,
            dataType: "json",
            success: function(DATA) {
                if (DATA.SUCESS) {
                    if (DATA.MENU.bill_details_status == '1') {
                        show_bill_modal();
                    } else {
                        if (dr_total_amount != cr_total_amount) {
                            $('.debitTags').focus();
                        } else {
                            $('.narration').focus();
                        }
                    }

                }
            },
            error: function(request, error) {
                alert('connection error. please try again.');
            }
        });
    }


//auther@sudip  29-7-2016 bill wish  modal 

    $(".bill_credit_day").on('keyup', function(e) {
        var credit_days = parseInt($("#bill_body > div:last div div:nth-child(3) input").val()) || 0;
        var d = new Date(new Date().setDate(new Date().getDate() + credit_days));
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();
        if (day < 10) {
            day = "0" + day;
        }
        if (month < 10) {
            month = "0" + month;
        }
        var date = day + "-" + month + "-" + year;
        $('#billModal').find('.bill_credit_date:last').val(date);
    });

    function show_bill_modal() {
        var dr_total_amount = parseFloat($('#sum_dr').html());
        var cr_total_amount = parseFloat($('#sum_cr').html());
        var inc_id = 0;
        var ledger_value = 0;
        console.log(dr_total_amount);
        console.log(cr_total_amount);
        console.log(increment_id);
        if (dr_total_amount != cr_total_amount) {
            inc_id = increment_id - 1;
        } else {
            inc_id = increment_id;
        }
        var account_type = $('#account_' + inc_id).val();

        if (account_type == 'Dr') {
            ledger_value = parseFloat($('#amount_d_' + inc_id).val());
        }
        if (account_type == 'Cr') {
            ledger_value = parseFloat($('#amount_c_' + inc_id).val());
        }
        var ledger = $('#account_' + inc_id).closest('.form-group').find('.txt_tracking').attr('data-id');
        var current_ledger = $("#tracking_ledger_id").val();
        if (current_ledger != ledger) {
            ledger = current_ledger;
        }
        console.log(inc_id)
        console.log(ledger);
        //
        var entry_id = $('#entry_id').val();
        var url = base_url + 'index.php/accounts/entries/get_temp_bill_by_id';
        $.ajax({
            type: "POST",
            url: url,
            data: {ledger: ledger, entry_id: entry_id},
            dataType: "json",
            success: function(data) {
                console.log(data);
                if (data.res == 'success') {
                    $('#cr_dr_cal').val(account_type);
                    $('#total_bill').val(ledger_value);
                    $('.account_type_modal').val(account_type);
                    $('.close_pop_bill:last').val(account_type);
                    $("#bill_ledger_name").html(data.ledger_name);
                    $("#bill_ledger_id").val(ledger);
                    $('.bill_amount').val(ledger_value);
                    getBillModalBody(ledger);
                    $('#billModal').modal('show');
                    $("#bill_form").find('#bill_body').find('input').last().focus();
                } else {
                    $('#cr_dr_cal').val(account_type);
                    $('#bill_body > div').not('div:first').remove();
                    $('.account_type_modal').val(account_type);
                    $('.ref_bill_type').val('');
                    $('.bill_name').val('');
                    $('.bill_credit_day').val('');
                    $('.bill_amount').val(ledger_value);
                    $('.close_pop_bill:last').val(account_type);
                    $('#total_bill').val(ledger_value);
                    $('#billModal').modal('show');
                }

            },
            error: function(request, error) {
                alert('connection error. please try again.');
            }
        });
    }

// focus 2
    $('#billModal').on('shown.bs.modal', function() {
        $('#billModal').find('.current:last').focus();
        log("222", 'GREEN')
    })


    $('.close_pop_bill:last').on('keypress', function(e) {
        if (e.which == '13') {
            console.log('kkkkk');
            var dr_total_amount = parseFloat($('#sum_dr').html());
            var cr_total_amount = parseFloat($('#sum_cr').html());
            var inc_id = 0;
            if (dr_total_amount != cr_total_amount) {
                inc_id = increment_id - 1;
            } else {
                inc_id = increment_id;
            }
            console.log(increment_id);
            var account_type = $('#account_' + inc_id).val();

            if (account_type == 'Dr') {
                var ledger_value = parseFloat($('#amount_d_' + inc_id).val());
            }
            if (account_type == 'Cr') {
                var ledger_value = parseFloat($('#amount_c_' + inc_id).val());
            }
            var total_bill_amount = 0.00;

            var ledger_id = $('#ledger_id_' + inc_id).val();
            var billPostData = 'ajax=1&index=' + inc_id;
            var billJsonArray = [];


            var rows = $("#bill_body > div").length;
            for (var i = 0; i < rows; i++) {
                total_bill_amount = total_bill_amount + parseFloat($("#bill_body > div:eq(" + i + ") div div:nth-child(5) input").val());
                var jsonObj = {ledger_index: inc_id, ledger_id: ledger_id,
                    bill_amount: parseFloat($("#bill_body > div:eq(" + i + ") div div:nth-child(5) input").val()),
                    ref_type: $("#bill_body > div:eq(" + i + ") div div:nth-child(1) input").val(),
                    dr_cr: $("#bill_body > div:eq(" + i + ") div div:nth-child(6) input").val(),
                    bill_name: $("#bill_body > div:eq(" + i + ") div div:nth-child(2) input").val(),
                    credit_days: $("#bill_body > div:eq(" + i + ") div div:nth-child(3) input").val(),
                    credit_date: $("#bill_body > div:eq(" + i + ") div div:nth-child(4) input").val()};
                billJsonArray.push(jsonObj);
                var billJson = JSON.stringify(billJsonArray);
                var billPostData = billPostData + '&jsonBill=' + billJson;

            }
            //$('#total_bill').val(total_bill_amount);
            if (total_bill_amount <= ledger_value) {
                if (ledger_value == total_bill_amount) {
                    set_billwish_data(billPostData);
                    console.log('ggggg');
                    console.log(dr_total_amount);
                    console.log(cr_total_amount);
                    $('#billModal').modal('hide');
                    if (dr_total_amount != cr_total_amount) {
                        $('.debitTags').focus();
                    } else {
                        var temp_ledger_id = $("#bill_ledger_id").val();
                        if (ledger_id != temp_ledger_id) {
                            ledger_id = temp_ledger_id;
                        }
                        var all = $('input[data-id="' + ledger_id + '"]').closest('.form-group').nextAll().find('.amount');
                        if (typeof all[0] !== 'undefined') {
                            $(all[0]).closest('.form-group').find('.debitTags').focus()
                        } else {
                            $('.narration').focus();
                        }
                    }
                } else {
                    add_bill_row();
                }
            } else {
                $(this).val('');
                alert('Please Give Proper Amount..!');
            }
        }
        init_bill_tracking_forword_backword();
    });

    function add_bill_row() {
        $('#clone_div_bill')
                .clone()
                .appendTo($('#bill_body')).css('display', 'block');

        $('#bill_body').find('.current:last').focus();


        var dr_total_amount = parseFloat($('#sum_dr').html());
        var cr_total_amount = parseFloat($('#sum_cr').html());
        var inc_id = 0;
        if (dr_total_amount != cr_total_amount) {
            inc_id = increment_id - 1;
        } else {
            inc_id = increment_id;
        }
        var account_type = $('#account_' + inc_id).val();

        if (account_type == 'Dr') {
            var ledger_value = parseFloat($('#amount_d_' + inc_id).val());
        }
        if (account_type == 'Cr') {
            var ledger_value = parseFloat($('#amount_c_' + inc_id).val());
        }
        var total_bill_amount = 0.00;


        var rows = $("#bill_body > div").length;
        for (var i = 0; i < (rows - 1); i++) {
            total_bill_amount = total_bill_amount + parseFloat($("#bill_body > div:eq(" + i + ") div div:nth-child(5) input").val());
        }
        $(".bill_amount:last").val(ledger_value - total_bill_amount);



        $('.close_pop_bill:last').on('keypress', function(e) {
            if (e.which == '13') {
                console.log('ooooooo');
                var dr_total_amount = parseFloat($('#sum_dr').html());
                var cr_total_amount = parseFloat($('#sum_cr').html());
                var inc_id = 0;
                if (dr_total_amount != cr_total_amount) {
                    inc_id = increment_id - 1;
                } else {
                    inc_id = increment_id;
                }
                var account_type = $('#account_' + inc_id).val();

                if (account_type == 'Dr') {
                    var ledger_value = parseFloat($('#amount_d_' + inc_id).val());
                }
                if (account_type == 'Cr') {
                    var ledger_value = parseFloat($('#amount_c_' + inc_id).val());
                }
                var total_bill_amount = 0.00;

                var ledger_id = $('#ledger_id_' + inc_id).val();
                var billPostData = 'ajax=1&index=' + inc_id;
                var billJsonArray = [];


                var rows = $("#bill_body > div").length;
                for (var i = 0; i < rows; i++) {
                    var ledger_type = $("#bill_body > div:eq(" + i + ") div div:nth-child(6) input").val();
                    if (ledger_type == account_type) {
                        total_bill_amount = total_bill_amount + parseFloat($("#bill_body > div:eq(" + i + ") div div:nth-child(5) input").val());
                    } else {
                        total_bill_amount = total_bill_amount - parseFloat($("#bill_body > div:eq(" + i + ") div div:nth-child(5) input").val());
                    }

                    var jsonObj = {ledger_index: inc_id, ledger_id: ledger_id,
                        bill_amount: parseFloat($("#bill_body > div:eq(" + i + ") div div:nth-child(5) input").val()),
                        ref_type: $("#bill_body > div:eq(" + i + ") div div:nth-child(1) input").val(),
                        dr_cr: $("#bill_body > div:eq(" + i + ") div div:nth-child(6) input").val(),
                        bill_name: $("#bill_body > div:eq(" + i + ") div div:nth-child(2) input").val(),
                        credit_days: $("#bill_body > div:eq(" + i + ") div div:nth-child(3) input").val(),
                        credit_date: $("#bill_body > div:eq(" + i + ") div div:nth-child(4) input").val()};
                    billJsonArray.push(jsonObj);
                    var billJson = JSON.stringify(billJsonArray);
                    var billPostData = billPostData + '&jsonBill=' + billJson;

                }
                //$('#total_bill').val(total_bill_amount);
                if (total_bill_amount <= ledger_value) {
                    if (ledger_value == total_bill_amount) {
                        set_billwish_data(billPostData);
                        console.log('hhhhh');
                        $('#billModal').modal('hide');
                        if (dr_total_amount != cr_total_amount) {
                            $('.debitTags').focus();
                        } else {
                            $('.narration').focus();
                        }
                    } else {
                        add_bill_row();
                    }
                } else {
                    $(this).val('');
                    alert('Please Give Proper Amount..!');
                }
            }
        });

        //Autocomplte For bill reference Modal
        var refBillType = [
            "Advance",
            "Against Reference",
            "New Reference",
            "On Account"
        ];
        $(".ref_bill_type").autocomplete({
            source: refBillType,
            appendTo: $("#bill_form")
        }).autocomplete("widget").addClass("debit-credit-ui-autocomplete");

        var accountTypeModal = [
            "Dr",
            "Cr"
        ];
        $(".account_type_modal").autocomplete({
            source: accountTypeModal,
            appendTo: $("#bill_form")
        }).autocomplete("widget").addClass("debit-credit-ui-autocomplete");

        $(".bill_credit_day").on('keyup', function(e) {
            var credit_days = parseInt($("#bill_body > div:last div div:nth-child(3) input").val()) || 0;
            var d = new Date(new Date().setDate(new Date().getDate() + credit_days));
            var day = d.getDate();
            var month = d.getMonth() + 1;
            var year = d.getFullYear();
            if (day < 10) {
                day = "0" + day;
            }
            if (month < 10) {
                month = "0" + month;
            }
            var date = day + "-" + month + "-" + year;

            $('#billModal').find('.bill_credit_date:last').val(date);
        });

        $(".reference_bill").autocomplete({
            source: function(request, response) {
                var dr_total_amount = parseFloat($('#sum_dr').html());
                var cr_total_amount = parseFloat($('#sum_cr').html());
                var inc_id = 0;
                if (dr_total_amount != cr_total_amount) {
                    inc_id = increment_id - 1;
                } else {
                    inc_id = increment_id;
                }

                $ledger_id = $('#ledger_id_' + inc_id).val();
                $.ajax({
                    type: "POST",
                    url: base_url + 'index.php/tracking/admin/getBillByReferenceLedgerId',
                    data: "bill_name=" + request.term + '&ajax=1&ledger_id=' + $ledger_id,
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    },
                    error: function(request, error) {
                        alert('Please Enter Bill Name');
                    }
                });
            },
            minLength: 0,
            appendTo: $("#bill_form")
        }).focus(function() {
            $(this).autocomplete("search");
        });

        function reference_bill_by_type(bill_type) {
            if (bill_type == 'Against Reference') {
                $('.bill_name:last').attr('class', 'form-control bill_name reference_bill');
                reference_bill_autocomplete();
            } else {
                $('.bill_name:last').attr('class', 'form-control bill_name ');

            }

        }
        //init
        init_bill_tracking_forword_backword();
        //init
        //init remove
        $(".close_pop_bill").on('keypress', function() {
        }).on('keydown', function(e) {
            if (e.keyCode == 13) {
                var ledger_val = $(this).closest('.form-group').find('.bill_amount').val();
                var amount = $('#total_bill').val();
                var all = $(this).closest('.form-group').prevAll().find('.bill_amount');
                $.each(all, function(index, element) {
                    var value = $(element).val()
                    ledger_val = parseFloat(ledger_val) + parseFloat(value);
                });
                if (parseFloat(amount) == ledger_val) {
                    $(this).closest('.form-group').nextAll().remove();
                    $(this).focus();
                } else {
                    return true;
                }
            }
        });
        //end init remove
    }




// auther@sudip  20-7-2016 sub tracking popup for modal

// Debit Modal 
    $(".txt_tracking").keypress(function(e) {
        if (e.which == '13') {
            var ledger = $(this).attr('data-id');
            var entry_id = $("#entry_id").val();
            if (typeof ledger !== 'undefined') {
                var url = base_url + 'index.php/accounts/entries/get_temp_tracking_by_id';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {ledger: ledger, entry_id: entry_id},
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        if (data.res == 'success') {
                            $("#tracking_ledger_name").html(data.ledger_name);
                            $("#tracking_ledger_id").val(ledger);
                            getTrackingModalBody(ledger);
                            $('#DrModal').modal('show');
                        } else {
                            $("#tracking_ledger_id").val(ledger);
                            $('#sub_tracking_body > div').not('div:first').remove();
                            $('.current').val('');
                            $('.sub_tracking_amount').val('');
                            $('#total_sub_tracking').val('');
                            $('#DrModal').modal('show');
                            console.log('kkkkk')
                        }

                    },
                    error: function(request, error) {
                        alert('connection error. please try again.');
                    }
                });
            } else {
                $('#sub_tracking_body > div').not('div:first').remove();
                $('.current').val('');
                $('.sub_tracking_amount').val('');
                $('#total_sub_tracking').val('');
                $('#DrModal').modal('show');
            }
        }
    });


    // focus 2
    $('#DrModal').on('shown.bs.modal', function() {
        $('.current').focus();
    });

    // .close_pop
    $('.close_pop_dr:last').on('keypress', function(e) {
        if (e.which == '13') {
            console.log('111111111')
            //   var all = $(this).prevAll().find("input[class='sub_tracking_amount']");
            //   console.log(all);
            var dr_total_amount = parseFloat($('#sum_dr').html());
            var cr_total_amount = parseFloat($('#sum_cr').html());
            var inc_id = 0;
            console.log(increment_id);
            if (dr_total_amount != cr_total_amount) {
                inc_id = increment_id - 1;
            } else {
                inc_id = increment_id;
            }
            var account_type = $('#account_' + inc_id).val();

            if (account_type == 'Dr') {
                var ledger_value = parseFloat($('#amount_d_' + inc_id).val());
            }
            if (account_type == 'Cr') {
                var ledger_value = parseFloat($('#amount_c_' + inc_id).val());
            }
            var total_sub_tracking_amount = 0.00;

            var ledger_id = $('#ledger_id_' + inc_id).val();
            var queryString = 'ajax=1&index=' + inc_id;
            var trackingJsonArray = [];
            var trackingJsonArray = [];


            var rows = $("#sub_tracking_body > div").length;
            for (var i = 0; i < rows; i++) {
                total_sub_tracking_amount = total_sub_tracking_amount + parseFloat($("#sub_tracking_body > div:eq(" + i + ") div div:last input").val());
                var jsonObj = {account_type: account_type, ledger_index: inc_id, ledger_id: ledger_id, tracking_amount: parseFloat($("#sub_tracking_body > div:eq(" + i + ") div div:last input").val()), tracking_name: $("#sub_tracking_body > div:eq(" + i + ") div div:first input").val()};
                trackingJsonArray.push(jsonObj);
                var trackingJson = JSON.stringify(trackingJsonArray);
                var queryString = queryString + '&jsonTracking=' + trackingJson;

            }
            // $('#total_sub_tracking').val(total_sub_tracking_amount);
            var current_requir_value = parseFloat(ledger_value) - parseFloat(total_sub_tracking_amount);
            if (total_sub_tracking_amount <= ledger_value) {
                if (ledger_value == total_sub_tracking_amount) {
                    set_tracking_data(queryString);

                    $('#DrModal').modal('hide');
                    billing_status_check();
                    //$('#billModal').modal('show'); 
                    // show_bill_modal();
                    // if(dr_total_amount != cr_total_amount){
                    //     $('.debitTags').focus();
                    // }else{
                    //     $('.narration').focus();
                    // }
                } else {
                    add_tracking_row(current_requir_value);
                }
            } else {
                $(this).val('');
                alert('Please Give Proper Amount..!');
            }
            //init
            init_tracking_forword_backword();
            //init
        }
    });

    function add_tracking_row(current_requir_value) {
        $('#clone_div')
                .clone()
                .appendTo($('#sub_tracking_body')).css('display', 'block');
        $('#sub_tracking_body').find('.current:last').focus();
        $(".sub_tracking_amount:last").val(current_requir_value);
        console.log(current_requir_value);
        $('.close_pop_dr:last').on('keypress', function(e) {
            if (e.which == '13') {
                console.log('22222')
                var dr_total_amount = parseFloat($('#sum_dr').html());
                var cr_total_amount = parseFloat($('#sum_cr').html());
                var inc_id = 0;
                if (dr_total_amount != cr_total_amount) {
                    inc_id = increment_id - 1;
                } else {
                    inc_id = increment_id;
                }
                var account_type = $('#account_' + inc_id).val();
                if (account_type == 'Dr') {
                    var ledger_value = parseFloat($('#amount_d_' + inc_id).val());
                }
                if (account_type == 'Cr') {
                    var ledger_value = parseFloat($('#amount_c_' + inc_id).val());
                }

                var ledger_id = $('#ledger_id_' + inc_id).val();
                //var queryString = 'ajax=1&index='+ inc_id +'&ledger_id='+ledger_id ;
                var queryString = 'ajax=1&index=' + inc_id;
                var trackingJsonArray = [];

                var total_sub_tracking_amount = 0.00;
                var rows = $("#sub_tracking_body > div").length;
                for (var i = 0; i < rows; i++) {
                    total_sub_tracking_amount = total_sub_tracking_amount + parseFloat($("#sub_tracking_body > div:eq(" + i + ") div div:last input").val());
                    var jsonObj = {account_type: account_type, ledger_index: inc_id, ledger_id: ledger_id, tracking_amount: parseFloat($("#sub_tracking_body > div:eq(" + i + ") div div:last input").val()), tracking_name: $("#sub_tracking_body > div:eq(" + i + ") div div:first input").val()};
                    trackingJsonArray.push(jsonObj);
                    var trackingJson = JSON.stringify(trackingJsonArray);
                    var queryString = queryString + '&jsonTracking=' + trackingJson;

                }
                //$('#total_sub_tracking').val(ledger_value);
                if (total_sub_tracking_amount <= ledger_value) {
                    var current_requir_value = parseFloat(ledger_value) - parseFloat(total_sub_tracking_amount)
                    if (ledger_value == total_sub_tracking_amount) {

                        set_tracking_data(queryString);

                        $('#DrModal').modal('hide');
                        billing_status_check();
                        //$('#billModal').modal('show'); 
                        //show_bill_modal();
                        // if(dr_total_amount != cr_total_amount){
                        //     $('.debitTags').focus();
                        // }else{
                        //     $('.narration').focus();
                        // }
                    } else {
                        add_tracking_row(current_requir_value);
                    }
                } else {
                    $(this).val('');
                    alert('Please Give Proper Amount..!');
                }
                //init
                init_tracking_forword_backword();
                //init
            }
        });

        // For autocomplete SUDIP For Tracking
        $(".modal_tracking").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: base_url + 'index.php/tracking/admin/getAccessTrackingDetails',
                    data: "group=" + request.term + '&ajax=1',
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    },
                    error: function(request, error) {
                        alert('Please Enter Tracking Name');
                    }
                });
            },
            appendTo: $("#form")
        });
//init
        $(".close_pop_dr").on('keypress', function() {
        }).on('keydown', function(e) {
            var ledger_val = $(this).val();
            var amount = $('#total_sub_tracking').val();
            if (e.keyCode == 13) {
                console.log('444444')
                var all = $(this).closest('.form-group').prevAll().find('.sub_tracking_amount');
                $.each(all, function(index, element) {
                    var value = $(element).val()
                    ledger_val = parseFloat(ledger_val) + parseFloat(value);
                });
                if (parseFloat(amount) == ledger_val) {
                    $(this).closest('.form-group').nextAll().remove();
                    $(this).focus();
                } else {
                    return true;
                }
                console.log(ledger_val);
            }
        });
//init
    }



    // voucher auto generate code check
    $('#auto_generated').click(function() {
        $("#voucher_code").toggle(!this.checked);
    });

    // number formating auto generate code check
    $('#entry_no_manual').click(function() {
        $("#number_formating").css("display", "none");
    });
    $('#entry_no_auto').click(function() {
        $("#number_formating").css("display", "block");
    });

    $(".add_company").change(function(e) {
        var counter = $(this).val(); //Selected counter value
        var wrapper = $('.tab-content')//Fields wrapper
        e.preventDefault();
        $('.new_li').remove();
        $('.append_tab').remove();
        for (i = 1; i < counter; i++) {
            var num = i + 1;
            $("#company_tabs").append('<li class="new_li"><a href="#tab_15_' + num + '" data-toggle="tab">Company ' + num + '  </a></li>');
            $(wrapper).append('<div class="tab-pane append_tab" id="tab_15_' + num + '"><h3 class="block">' + num + 'nd Company Details</h3><div class="tab-1-contents"><div class="form-group"><label class="control-label col-md-3">Fullname <span class="required">*</span></label><div class="col-md-4"><input type="text" class="form-control" name="fullname"/><span class="help-block">Provide your fullname </span></div></div><div class="form-group"><label class="control-label col-md-3">Phone Number <span class="required">*</span></label><div class="col-md-4"><input type="text" class="form-control" name="phone"/><span class="help-block">Provide your phone number </span></div></div></div></div>');
        }
        var lastDiv = i;
        if (lastDiv == counter) {
            $("#submit").append('<a href="javascript:;" class="btn green button-submit">Submit <i class="m-icon-swapright m-icon-white"></i></a>');
        }
    });
    $('#group_name').keyup(function(e) {  //check group name in database
        var GroupName = $('#group_name').val();
        var url = 'index.php/accounts/groups/find_group_name';
        jQuery.ajax({
            url: base_url + url,
            type: 'POST',
            dataType: 'json',
            data: 'group_name=' + GroupName,
            cache: false,
            success: function(data, textStatus, jqXHR) {
                //console.log(data);
                if (data.length > 0) {
                    $('.inactive').prop("disabled", true);
                    $('.Existence_alert').html('This name already exist please chose other name');
                } else {
                    $('.inactive').prop("disabled", false);
                    $('.Existence_alert').empty();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });
    $('#ladger_name').keyup(function() { //check leser name in database
        var LedgerName = $("#ladger_name").val();
        var url = 'index.php/accounts/check_ledger_name';
        jQuery.ajax({
            url: base_url + url,
            type: 'POST',
            dataType: 'json',
            data: 'ledger_name=' + LedgerName,
            cache: false,
            success: function(data, textStatus, jqXHR) {
                // statement_name
                //console.log(data);
                if (data.length > 0) {
                    $('.inactive').prop("disabled", true);
                    $('.Existence_alert').html('This name already exist please chose other name');
                } else {
                    $('.inactive').prop("disabled", false);
                    $('.Existence_alert').empty();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });
    //for new entry page

    var max_fields = 100; //maximum input boxes allowed
    var wrapper = $("#input_fields_wrap"); //Fields wrapper
    var add_button = $(".add_field_button"); //Add button ID
//    var x = 1; //initlal text box count
    var x = (parseInt($('#ledger_no').val()) - 1); //initlal text box count


    $(".txt_dr").each(function() {
        $(this).keyup(function() {
            calculateSumDr();
            //console.log(this);
        });
    });
    $(".txt_cr").each(function() {
        $(this).keyup(function() {
            calculateSumCr();
        });
    });

    $(wrapper).find('.select2meajax').select2();

//    $(add_button).on('click',function(e) { //on add input button click
//        e.preventDefault();
    function addRow() {
        if (x < max_fields) { //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div><div class="form-group ledger-block">\n\
            <div class="row" style="margin: 0">\n\
            <div class="">\n\
            <div class="col-sm-2">\n\
            <input id="account_' + x + '"   name="account[]"  onblur="active_amount_box(this.value, ' + x + ');" class="account-type form-control debitTags input-mg select2me validate[required]">\n\
            </div>\n\
            <div class="col-md-2">\n\
            <input id="select2_sample_' + x + '" name="" onblur="get_ledger(this.value,' + x + ');" class="form-control ledger-account ">\n\
            <input id="ledger_id_' + x + '" name="ledger_id[]" type="hidden">\n\
            </div>\n\
            <div class="col-md-2">\n\
            <input type="text" placeholder="Amount" name="amount[]" class="form-control txt_dr amount  validate[required,custom[integer]] text-input" "placeholder"="Amount" id="amount_d_' + x + '" readonly="">\n\
            </div>\n\
            <div class="col-md-2">\n\
            <input type="text" placeholder="Amount" name="amount[]" class="form-control txt_cr amount validate[required,custom[integer]] text-input" "placeholder"="Amount"  id="amount_c_' + x + '" readonly="">\n\
            </div>\n\
            <div class="col-md-1"><label>&nbsp;</label></div>\n\
            <div class="col-md-2" id="text_box">\n\
            <lebel id="current_balance_' + x + '"></lebel>\n\
            </div>\n\
            </div>\n\
            </div>\n\
            </div>\n\
            <hr/></div>'); //add input box


            $(".txt_dr").each(function() {
                $(this).keyup(function() {
                    calculateSumDr();
                });
            });
            $(".txt_cr").each(function() {
                $(this).keyup(function() {
                    calculateSumCr();
                });
            });

            //$(wrapper).find('.select2meajax').select2();

        }


        var debitTags = [
            "Dr",
            "Cr"
        ];
        $(".debitTags").autocomplete({
            source: debitTags
        }).autocomplete("widget").addClass("debit-credit-ui-autocomplete");



        $(".ledger-account").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: base_url + 'index.php/accounts/entries/getLedgerDetails',
                    data: "ledger=" + request.term + '&ajax=1',
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    },
                    error: function(request, error) {
                        alert('Please enter Ledger Name ..');
                    }
                });
            }
        });

        $('.accounts-form :text , .accounts-form select, .accounts-form ').on('keypress', function(e) {
            if (e.keyCode == 13) {
                /* FOCUS ELEMENT */
                var inputs = $(this).parents(".accounts-form").eq(0).find(":text, :submit");
                var idx = inputs.index(this);

                if (idx == inputs.length - 1) {
                    inputs[0].select();
                } else {
                    inputs[idx + 1].focus(); //  handles submit buttons
                    inputs[idx + 1].select();
                }
                return false;
            }

        });

        $('.accounts-form :text, .accounts-form select, .accounts-form  ').on('keypress', function() {
        }).on('keydown', function(e) {
            if (e.keyCode == 37) {
                var inputs = $(this).parents(".accounts-form").eq(0).find(":text, :submit");
                var idx = inputs.index(this);

                if (idx == inputs.length - 1) {
                    inputs[0].select()
                } else {
                    inputs[idx - 1].focus(); //  handles submit buttons
                    inputs[idx - 1].select();
                }
                return false;
            }
        });

        $('#account_' + x).focus();

        //For new row Append
        $('[id^="amount_c_"]').on('keypress', function(e) {
            var id = parseInt($(this).attr('id').split("c_").pop());
            if (id == x) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    var dr_total_amount = parseFloat($('#sum_dr').html());
                    var cr_total_amount = parseFloat($('#sum_cr').html());
                    if (dr_total_amount != cr_total_amount) {
                        addRow();
                    }

                }
            }
        });
        $('[id^="amount_d_"]').on('keypress', function(e) {
            var id = parseInt($(this).attr('id').split("d_").pop());
            if (id == x) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    var dr_total_amount = parseFloat($('#sum_dr').html());
                    var cr_total_amount = parseFloat($('#sum_cr').html());
                    if (dr_total_amount != cr_total_amount) {
                        addRow();
                    }

                }
            }
        });

        //For remove Rest of row
        $('[id^="amount_c_"]').on('keypress', function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                var dr_total_amount = parseFloat($('#sum_dr').html());
                var cr_total_amount = parseFloat($('#sum_cr').html());
                if (dr_total_amount == cr_total_amount) {
                    var id = parseInt($(this).attr('id').split("c_").pop());
                    if (id != x) {
                        var y = x;
                        for (var i = (id + 1); i <= y; i++) {
                            $("#amount_c_" + i).parent('div').parent('div').parent('div').parent('div').parent('div').remove();
                            x--;
                        }
                    }
                }
            }
        });
        $('[id^="amount_d_"]').on('keypress', function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                var dr_total_amount = parseFloat($('#sum_dr').html());
                var cr_total_amount = parseFloat($('#sum_cr').html());
                if (dr_total_amount == cr_total_amount) {
                    var id = parseInt($(this).attr('id').split("d_").pop());
                    if (id != x) {
                        var y = x;
                        for (var i = (id + 1); i <= y; i++) {
                            $("#amount_d_" + i).parent('div').parent('div').parent('div').parent('div').parent('div').remove();
                            x--;
                        }
                    }
                }
            }
        });

        //for modal 26072016
        // Debit Modal 
        $(".txt_tracking").keypress(function(e) {
            if (e.which == '13') {
                var ledger = $(this).attr('data-id');
                var entry_id = $("#entry_id").val();
                if (typeof ledger !== 'undefined') {
                    var url = base_url + 'index.php/accounts/entries/get_temp_tracking_by_id';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {ledger: ledger, entry_id: entry_id},
                        dataType: "json",
                        success: function(data) {
                            // console.log(data);
                            if (data.res == 'success') {
                                $("#tracking_ledger_name").html(data.ledger_name);
                                $("#tracking_ledger_id").val(ledger);
                                getTrackingModalBody(ledger);
                                $('#DrModal').modal('show');
                            } else {
                                $("#tracking_ledger_id").val(ledger);
                                $('#sub_tracking_body > div').not('div:first').remove();
                                $('.current').val('');
                                $('.sub_tracking_amount').val('');
                                $('#total_sub_tracking').val('');
                                $('#DrModal').modal('show');
                            }

                        },
                        error: function(request, error) {
                            alert('connection error. please try again.');
                        }
                    });
                } else {
                    $('#sub_tracking_body > div').not('div:first').remove();
                    $('.current').val('');
                    $('.sub_tracking_amount').val('');
                    $('#total_sub_tracking').val('');
                    $('#DrModal').modal('show');
                }
            }
        });


        //Bill wish Modal
        function show_bill_modal() {
            var dr_total_amount = parseFloat($('#sum_dr').html());
            var cr_total_amount = parseFloat($('#sum_cr').html());
            var inc_id = 0;
            var ledger_value = 0;
            if (dr_total_amount != cr_total_amount) {
                inc_id = increment_id - 1;
            } else {
                inc_id = increment_id;
            }
            var account_type = $('#account_' + inc_id).val();

            if (account_type == 'Dr') {
                ledger_value = parseFloat($('#amount_d_' + inc_id).val());
            }
            if (account_type == 'Cr') {
                ledger_value = parseFloat($('#amount_c_' + inc_id).val());
            }
            var ledger = $('#account_' + inc_id).closest('.form-group').find('.txt_tracking').attr('data-id');
            var entry_id = $('#entry_id').val();
            var url = base_url + 'index.php/accounts/entries/get_temp_bill_by_id';
            $.ajax({
                type: "POST",
                url: url,
                data: {ledger: ledger, entry_id: entry_id},
                dataType: "json",
                success: function(data) {
                    // console.log(data);
                    if (data.res == 'success') {
                        $("#bill_ledger_name").html(data.ledger_name);
                        $("#bill_ledger_id").val(ledger);
                        getBillModalBody(ledger);
                        $('.bill_amount').val(account_type);
                        $('.close_pop_bill:last').val(account_type);
                        $('#total_bill').val(ledger_value);
                        $('#billModal').modal('show');

                        $("#bill_form").find('#bill_body').find('input').last().focus();
                    } else {
                        $("#bill_ledger_name").html(data.ledger_name);
                        $("#bill_ledger_id").val(ledger);
                        $('#bill_body > div').not('div:first').remove();
                        $('.bill_amount').val(account_type);
                        $('.ref_bill_type').val('');
                        $('.bill_name').val('');
                        $('.bill_credit_day').val('');
                        $('.close_pop_bill:last').val(account_type);
                        $('#total_bill').val(ledger_value);
                        $('#billModal').modal('show');
                    }

                },
                error: function(request, error) {
                    alert('connection error. please try again.');
                }
            });
        }

        //initialize back from account type 

        $(".account-type").on('keypress', function() {
        }).on('keydown', function(e) {

            if (e.keyCode == 37) {
                var ledger = $(this).closest(".ledger-block").prev().prev().find('.amount').attr('data-id');
                if (typeof ledger === 'undefined') {
                    var ledger = $(this).closest("#input_fields_wrap").prev().prev().find('.amount').attr('data-id');
                }
                //ledger trac status
                // console.log(ledger)
                var url = base_url + 'index.php/accounts/entries/get_ledger_by_id';
                var queryString = "ledger=" + ledger + '&ajax=1';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: queryString,
                    dataType: "json",
                    success: function(DATA) {
                        if (DATA.SUCESS) {
                            $('#tracking_ledger_id').val(ledger);
                            $('#bill_ledger_id').val(ledger);
                            $('#tracking_ledger_name').text(DATA.MENU.ladger_name);
                            $('#bill_ledger_name').text(DATA.MENU.ladger_name);
                            if (DATA.MENU.bill_details_status == '1') {
                                //end ledger trac status
                                getBillModalBody(ledger);
                                $('#billModal').modal('show');
                                $("#bill_form").find('#bill_body').find('input').last().focus();
                            } else if (DATA.MENU.tracking_status == '1') {
                                getTrackingModalBody(ledger);
                                $('#DrModal').modal('show');
                                $("#form").find('#sub_tracking_body').find('input').last().focus();
                            } else {
                                $(this).closest(".ledger-block").prev().prev().find('.amount').focus();
                            }

                        }
//                        if (DATA.SUCESS) {
//                            if (DATA.MENU.bill_details_status == '1') {
//                                //end ledger trac status
//                                $('#billModal').modal('show');
//                                $("#bill_form").find('#bill_body').last().find('input').focus();
//                            } else if (DATA.MENU.tracking_status == '1') {
//                                $('#DrModal').modal('show');
//                            } else {
//                                $(".ledger-block").last().find('.amount').focus();
//                            }
//
//                        }
                    },
                    error: function(request, error) {
                        alert('connection error. please try again.');
                    }
                });
            }
        });


    }

    //    });

    //For new row Append 
    $("#amount_c_1").on('keypress', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            var dr_total_amount = parseFloat($('#sum_dr').html());
            var cr_total_amount = parseFloat($('#sum_cr').html());
            if (dr_total_amount != cr_total_amount) {
                addRow();
            }
        }
    });
    $("#amount_d_1").on('keypress', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            var dr_total_amount = parseFloat($('#sum_dr').html());
            var cr_total_amount = parseFloat($('#sum_cr').html());
            if (dr_total_amount != cr_total_amount) {
                addRow();
            }
        }
    });
    //For remove Rest of row
    $("#amount_c_1").on('keypress', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            var dr_total_amount = parseFloat($('#sum_dr').html());
            var cr_total_amount = parseFloat($('#sum_cr').html());
            if (dr_total_amount == cr_total_amount) {
                var id = parseInt($(this).attr('id').split("c_").pop());
                if (id != x) {
                    var y = x;
                    for (var i = 2; i <= y; i++) {
                        $("#amount_c_" + i).parent('div').parent('div').parent('div').parent('div').parent('div').remove();
                        x--;
                    }
                }
            }
        }
    });
    $("#amount_d_1").on('keypress', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            var dr_total_amount = parseFloat($('#sum_dr').html());
            var cr_total_amount = parseFloat($('#sum_cr').html());
            if (dr_total_amount == cr_total_amount) {
                var id = parseInt($(this).attr('id').split("d_").pop());
                if (id != x) {
                    var y = x;
                    for (var i = 2; i <= y; i++) {
                        $("#amount_d_" + i).parent('div').parent('div').parent('div').parent('div').parent('div').remove();
                        x--;
                    }
                }
            }
        }
    });




    $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    });

    Number.prototype.format = function(n, x, s, c) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
                num = this.toFixed(Math.max(0, ~~n));
        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };

    //Start:Ledger Statement
    $('#statement_type').change(function() {
        var selectedValue = this.value;
        var ledger_name = $(this).find('option:selected').attr('name');
        $('#statement_name').html(ledger_name);
        var ledgerPath = 'index.php/accounts/reports/ajax_ledger_statements';
        jQuery.ajax({
            url: base_url + ledgerPath,
            type: 'POST',
            data: {ledger_id: selectedValue},
            cache: false,
            success: function(data, textStatus, jqXHR) {
                // console.log(data);
                var result = $.parseJSON(data);
                $('#LedgerStatement').html(result.html);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
        /**
         * Number.prototype.format(n, x)
         * 
         * @param integer n: length of decimal
         * @param integer x: length of sections
         */


    });


    ComponentsPickers.init();
    var test = jQuery('.chk_sele');
    $('.chk_sele :selected').each(function() {
        for (var i = 0; i < test.length; i++) {
            var selected_account = $('#account_' + i).val();
            if (selected_account == 'Dr') {//change textbox disability
                $('#amount_c_' + i).prop('readonly', true);
                $('#amount_c_' + i).removeAttr("name");
                $('#amount_c_' + i).attr("type", "hidden");

                $('#amount_d_' + i).prop('readonly', false);
                $('#amount_d_' + i).attr("name", "amount[]");
                $('#amount_d_' + i).attr("type", "text");
            }
            if (selected_account == 'Cr') {
                $('#amount_d_' + i).prop('readonly', true);
                $('#amount_d_' + i).attr("name", "amount[]");
                $('#amount_d_' + i).attr("type", "hidden");

                $('#amount_c_' + i).prop('readonly', false);
                $('#amount_c_' + i).removeAttr("name");
                $('#amount_c_' + i).attr("type", "text");
            }

        }
    });

    // click enter  Sudip 
    $('.accounts-form :text , .accounts-form select, .accounts-form ').on('keypress', function(e) {
        if (e.keyCode == 13) {
            /* FOCUS ELEMENT */
            var inputs = $(this).parents(".accounts-form").eq(0).find(":text, :submit");
            var idx = inputs.index(this);

            if (idx == inputs.length - 1) {
                // inputs[0].select();
            } else {
                inputs[idx + 1].focus(); //  handles submit buttons
                // inputs[idx + 1].select();
            }
            return false;
        }

    });
    // click left arrow  Sudip 

    $('.accounts-form :text, .accounts-form select, .accounts-form  ').on('keypress', function() {
    }).on('keydown', function(e) {
        if (e.keyCode == 37) {

            var inputs = $(this).parents(".accounts-form").eq(0).find(":text, :submit");
            var idx = inputs.index(this);

            if (idx == inputs.length - 1) {
                inputs[0].select();
            } else {
                inputs[idx - 1].focus(); //  handles submit buttons
                inputs[idx - 1].select();
            }
            return false;
        }
    });

    //Fade in and Fade out For Ledger contact person details
//    $("#contact_add_more").click(function(){
//        $("#contact_details_two").fadeToggle();
//        return false;
//    });

});
var increment_id = 0;
function active_amount_box(account, id) {
    increment_id = id;
    var selected_account = account;
    if (selected_account == 'Dr') {//change textbox disability

        $('#amount_c_' + increment_id).attr('readonly', true);
        $('#amount_c_' + increment_id).removeAttr("name");
        $('#amount_c_' + increment_id).attr("type", "hidden");

        $('#amount_d_' + increment_id).attr('readonly', false);
        $('#amount_d_' + increment_id).attr("name", "amount[]");
        $('#amount_d_' + increment_id).attr("type", "text");
    }
    if (selected_account == 'Cr') {
        $('#amount_d_' + increment_id).attr('readonly', true);
        $('#amount_d_' + increment_id).removeAttr("name");
        $('#amount_d_' + increment_id).attr("type", "hidden");

        $('#amount_c_' + increment_id).attr('readonly', false);
        $('#amount_c_' + increment_id).attr("name", "amount[]");
        $('#amount_c_' + increment_id).attr("type", "text");
    }

    var entry_type_id = document.getElementById("entry_type_id").value;

    if (entry_type_id == 5) {
        if (selected_account == 'Cr') {
            $('#select2_sample_' + increment_id).attr("class", "form-control sale-cr-account");
            getSaleLedgerAccountBind();
        } else {
            $('#select2_sample_' + increment_id).attr("class", "form-control ledger-account");
            getLedgerAccountBind();
        }
    } else if (entry_type_id == 6) {
        if (selected_account == 'Dr') {
            $('#select2_sample_' + increment_id).attr("class", "form-control purchase-dr-account");
            getPurchaseLedgerAccountBind();
        } else {
            $('#select2_sample_' + increment_id).attr("class", "form-control ledger-account");
            getLedgerAccountBind();
        }
    } else if (entry_type_id == 3) {
        $('#select2_sample_' + increment_id).attr("class", "form-control contra-account");
        getContraLedgerAccountBind();
    } else if (entry_type_id == 2) {
        if (selected_account == 'Cr') {
            $('#select2_sample_' + increment_id).attr("class", "form-control contra-account");
            getContraLedgerAccountBind();
        } else {
            $('#select2_sample_' + increment_id).attr("class", "form-control ledger-account");
            getLedgerAccountBind();
        }
    } else if (entry_type_id == 1) {
        if (selected_account == 'Dr') {
            $('#select2_sample_' + increment_id).attr("class", "form-control contra-account");
            getContraLedgerAccountBind();
        } else {
            $('#select2_sample_' + increment_id).attr("class", "form-control ledger-account");
            getLedgerAccountBind();
        }
    } else {
        $('#select2_sample_' + increment_id).attr("class", "form-control ledger-account");
        getLedgerAccountBind();
    }


}
//bank details
function check_for_bank_details(id, ledger_id) {
    // console.log('bank');
    var url = base_url + 'index.php/accounts/entries/check_bank_betails';
    $.ajax({
        method: "POST",
        url: url,
        data: {ledger_id: ledger_id},
        dataType: 'json'
    }).done(function(data) {
        if (data.res == 'success') {
            var entry_no = $("#entry_no").val();
            $("#bank_ledger_id").val(ledger_id);
            $("#bank_entry_no").val(entry_no);
            //get temp saved data
            var url = base_url + 'index.php/accounts/entries/get_temp_bank_betails';
            $.ajax({
                method: "POST",
                url: url,
                data: {ledger_id: ledger_id, entry_no: entry_no},
                dataType: 'json'
            }).done(function(data) {
                var tran_type_html = '';
                tran_type_html += '<option value="">select tran. type</option>';
                $.each(data.transaction_types, function(index, value) {
                    tran_type_html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                if (data.res == 'success') {
                    $(".bank_input_fields_wrap").html('');

                    $.each(data.temp_bank_data, function(index, value) {
                        var html = '';
                        html += '<div class="row">';
                        html += '<div class="col-md-2">';
                        html += '<div class="form-group">';
                        html += '<select name="transaction_type[]" class="form-control" id="tran_type_' + index + '">';
                        html += tran_type_html;
                        html += '</select>';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-1 min-padding">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control" placeholder="Instrument No" name="instrument_no[]" value="' + value.instrument_no + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-1 min-padding">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control datepicker" placeholder="Instrument Date" name="instrument_date[]" value="' + value.instrument_date + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-2">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control" placeholder="Bank Name" name="bank_name[]" value="' + value.bank_name + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-2">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control" placeholder="Branch Name" name="branch_name[]" value="' + value.branch_name + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-2">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control" placeholder="IFSC Code" name="ifsc_code[]" value="' + value.ifsc_code + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-2">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control amount" placeholder="Amount" name="bank_amount[]" value="' + value.bank_amount + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div>';
                        html += '<div class="form-group close-div">';
                        html += '<a href="javascript:void(0);" class="close-btn remove_field"><i class="fa fa-times" aria-hidden="true"></i></a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        $('.bank_input_fields_wrap').append(html);
                        $("#tran_type_" + index + " option[value='" + value.transaction_type + "']").attr("selected", "selected");
                        //init date picker
                        $('.datepicker').datepicker({
                            format: 'yyyy-mm-dd',
                            todayHighlight: true,
                            autoclose: true
                        });
                        //end init date picker
                        //remove feild
                        $('.bank_input_fields_wrap').on("click", ".remove_field", function(e) { //user click on remove text
                            e.preventDefault();
                            $(this).closest('.row').remove();
                        });
                        //end remove feild

                    });
                    $("#bankModal").modal('show');
                } else {
                    $(".bank_input_fields_wrap").html('');
                    var html = '';
                    html += '<div class="row">';
                    html += '<div class="col-md-2">';
                    html += '<div class="form-group">';
                    html += '<select name="transaction_type[]" class="form-control">';
                    html += tran_type_html;
                    html += '</select>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-1 min-padding">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control" placeholder="Instrument No" name="instrument_no[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-1 min-padding">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control datepicker" placeholder="Instrument Date" name="instrument_date[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-2">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control" placeholder="Bank Name" name="bank_name[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-2">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control" placeholder="Branch Name" name="branch_name[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-2">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control" placeholder="IFSC Code" name="ifsc_code[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-2">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control amount" placeholder="Amount" name="bank_amount[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div>';
                    html += '<div class="form-group close-div">';
                    html += '<a href="javascript:void(0);" class="close-btn remove_field"><i class="fa fa-times" aria-hidden="true"></i></a>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    $('.bank_input_fields_wrap').append(html);
                    //init date picker
                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        todayHighlight: true,
                        autoclose: true
                    });
                    //end init date picker
                }
                $("#bankModal").modal('show');
            });
            //end get temp saved data

        }
    });

}

function get_current_balance(id, ledger_id) {
    // console.log('uuuuuu');
    // console.log(id);
    // console.log(ledger_id);
    var incre_id = id;
    var text_box_id = 'current_balance_' + incre_id;
    var selected_account = $('#account_' + incre_id).val();
    if (selected_account == 'Dr') {//change textbox disability
        $('#amount_c_' + incre_id).prop('readonly', true);
        $('#amount_c_' + incre_id).removeAttr("name");
        $('#amount_c_' + incre_id).attr("type", "hidden");

        $('#amount_d_' + incre_id).prop('readonly', false);
        $('#amount_d_' + incre_id).attr("name", "amount[]");
        $('#amount_d_' + incre_id).attr("type", "text");
    }
    if (selected_account == 'Cr') {
        $('#amount_d_' + incre_id).attr('readonly', true);
        $('#amount_d_' + incre_id).removeAttr("name");
        $('#amount_d_' + incre_id).attr("type", "hidden");

        $('#amount_c_' + incre_id).attr('readonly', false);
        $('#amount_c_' + incre_id).attr("name", "amount[]");
        $('#amount_c_' + incre_id).attr("type", "text");
    }
    var current_balance_path = 'index.php/accounts/entries/current_balance';
    jQuery.ajax({
        url: base_url + current_balance_path,
        type: 'POST',
        dataType: 'json',
        data: 'ledger_id=' + ledger_id,
        cache: false,
        success: function(data) {
//            var balance = '';
//            for (var i = 0; i < data.length; i++) {
//                balance = data[i]['current_balance'];
//            }
//            document.getElementById(text_box_id).value = balance;
            if (data.status == 'success') {
                document.getElementById(text_box_id).innerHTML = data.amount;
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}

function calculateSumDr() {
    var sum = 0;
    $(".txt_dr").each(function() {
        if (!isNaN(this.value) && this.value.length != 0) {
            sum += parseFloat(this.value);
        }
    });
    // console.log(sum);
    $("#sum_dr").html(sum.toFixed(2));
    document.getElementById('hidden_dr').value = sum.toFixed(2);
    difference();
    //alert(sum);
}

function calculateSumCr() {
    var sum1 = 0;
    $(".txt_cr").each(function() {
        if (!isNaN(this.value) && this.value.length != 0) {
            sum1 += parseFloat(this.value);
        }
    });
    $("#sum_cr").html(sum1.toFixed(2));
    document.getElementById('hidden_cr').value = sum1.toFixed(2);
    difference();
    //alert(sum1);
}

function difference() {
    var dr_total_amount = parseFloat($('#sum_dr').html());
    var cr_total_amount = parseFloat($('#sum_cr').html());
    var differ = 0;
    // console.log(dr_total_amount);
    // console.log(cr_total_amount);
    if (dr_total_amount > cr_total_amount) {
        differ = dr_total_amount - cr_total_amount;
        // console.log(differ)
        $("#differ_dr").html(differ.toFixed(2));

        $("#differ_cr").empty();
        return false;
        //alert('Your debit and credit balance is not equal please check it');
    }
    if (dr_total_amount < cr_total_amount) {
        differ = cr_total_amount - dr_total_amount;
        // console.log(differ)
        $("#differ_cr").html(differ.toFixed(2));

        $("#differ_dr").empty();
        return false;
        //alert('Your debit and credit balance is not equal please check it');
    }
    if (dr_total_amount == cr_total_amount) {

        $("#differ_dr").empty();
        $("#differ_cr").empty();
    }

}



function searchBalanceSheet() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    $('#date_range').html(from_date + '&nbsp to &nbsp' + to_date);
    var path = 'index.php/reports/balance_sheet_bydate';
    jQuery.ajax({
        url: base_url + path,
        type: 'POST',
        //dataType: 'json',
        data: {'from_date': from_date, 'to_date': to_date},
        cache: false,
        success: function(data, textStatus, jqXHR) {
            // console.log(data);
            jQuery('#balance_sheet').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}

function dateByProfitAndLoss() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    $('#profitloss_date_range').html(from_date + '&nbsp to &nbsp' + to_date);
    var path1 = 'index.php/reports/profit_loss_search_by_date';
    jQuery.ajax({
        url: base_url + path1,
        type: 'POST',
        //dataType: 'json',
        data: {'from_date': from_date, 'to_date': to_date},
        cache: false,
        success: function(data, textStatus, jqXHR) {
            // console.log(data);
            jQuery('#profit_and_loss').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}

function searchStatementByDate() {
    //alert('hello');
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var ledger_name = $('#statement_type').find('option:selected').attr('name');
    var selectedValue = $('#statement_type').find('option:selected').attr('value');
    var ledgerDateSearchPath = 'index.php/reports/ajax_ledger_statements_by_date';
    jQuery.ajax({
        url: base_url + ledgerDateSearchPath,
        type: 'POST',
        //dataType: 'json',
        data: {'ledger_id': selectedValue, 'from_date': from_date, 'to_date': to_date},
        cache: false,
        success: function(data, textStatus, jqXHR) {
            // console.log(data);
            //jQuery('#openong_balance_table').html(tableOpeningString);
            jQuery('#LedgerStatement').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}

function searchByDate() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var trialBalanceDateSearchPath = 'index.php/reports/trial_balance_by_date';
    //var trialBalanceDateSearchPath = 'index.php/reports';
    jQuery.ajax({
        url: base_url + trialBalanceDateSearchPath,
        type: 'POST',
        //dataType: 'json',
        data: {'from_date': from_date, 'to_date': to_date},
        cache: false,
        success: function(data, textStatus, jqXHR) {
            // console.log(data);
            //jQuery('#openong_balance_table').html(tableOpeningString);
            jQuery('#trialBalance').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}


// For autocomplete SUDIP
$(function() {
    $(".ledger-account").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/entries/getLedgerDetails',
                data: "ledger=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('Please enter Ledger Name .');
                }
            });
        },
    });
});

function getLedgerAccountBind() {
    $(".ledger-account").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/entries/getLedgerDetails',
                data: "ledger=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('Please enter Ledger Name .');
                }
            });
        },
        minLength: 0
    }).focus(function() {
        $(this).autocomplete("search");
    });
}
function getSaleLedgerAccountBind() {
    $(".sale-cr-account").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/entries/getSaleLedgerDetails',
                data: "ledger=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('Please Enter Sales Ledger Name .');
                }
            });
        },
        minLength: 0
    }).focus(function() {
        $(this).autocomplete("search");
    });
}

function getPurchaseLedgerAccountBind() {
    $(".purchase-dr-account").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/entries/getPurchaseLedgerDetails',
                data: "ledger=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('Please Enter Purchase Ledger Name .');
                }
            });
        },
        minLength: 0
    }).focus(function() {
        $(this).autocomplete("search");
    });
}

function getContraLedgerAccountBind() {
    $(".contra-account").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/entries/getContraLedgerDetails',
                data: "ledger=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('Please Enter Bank Or Cash Ledger Name .');
                }
            });
        },
        minLength: 0
    }).focus(function() {
        $(this).autocomplete("search");
    });
}

// For autocomplete SUDIP For Tracking
$(function() {
    $(".parent_tracking").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/tracking/admin/getParentTrackingDetails',
                data: "group=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('connection error. please try again.');
                }
            });
        },
        minLength: 0,
    }).focus(function() {
        $(this).autocomplete("search");
    });
});

// For autocomplete SUDIP For Tracking
$(function() {
    $(".modal_tracking").autocomplete({
        source: function(request, response) {

            $.ajax({
                type: "POST",
                url: base_url + 'index.php/tracking/admin/getAccessTrackingDetails',
                data: "group=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('Please Enter Tracking Name');
                }
            });
        },
        appendTo: $("#form")
    });
});
// For autocomplete SUDIP For Group
$(function() {
    $(".group").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/getGroupDetails',
                data: "group=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('connection error. please try again.');
                }
            });
        },
        minLength: 0,
    }).focus(function() {
        $(this).autocomplete("search");
    });
});

//get account type
$(function() {
    $(".account_type").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/getAccountType',
                data: "account=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('connection error. please try again.');
                }
            });
        },
        minLength: 0,
    }).focus(function() {
        $(this).autocomplete("search");
    });
});

// For autocomplete SUDIP For Group
$(function() {
    $(".group").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/getGroupDetails',
                data: "group=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('connection error. please try again.');
                }
            });
        },
         select: function(event, ui) {
          //   window.location.href = base_url + 'admin/groups-report-details?group_name='+ui.item.label;
            },
        minLength: 0,
    }).focus(function() {
        $(this).autocomplete("search");
    });
});

// For autocomplete SUDIP For Ledger
$(function() {
    $(".ledger").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/getLedgerDetails',
                data: "ledger=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('connection error. please try again.');
                }
            });
        },
         select: function(event, ui) {
           //  window.location.href = base_url + 'admin/ledger-statement?ledger_name='+ui.item.label;
            },
        minLength: 0,
    }).focus(function() {
        $(this).autocomplete("search");
    });
});

// For autocomplete SUDIP For Cash Bank
$(function() {
    $(".cash_bank").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/getCashBank',
                data: "ledger=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('connection error. please try again.');
                }
            });
        },
        minLength: 0,
    }).focus(function() {
        $(this).autocomplete("search");
    });
});

// For autocomplete SUDIP For Entry Type
$(function() {
    $(".entry_type_auto").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/accounts/entries/getEntryType',
                data: "entry_type=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    alert('connection error. please try again.');
                }
            });
        }
    });
});

//Autocomplte For bill name reference Modal
// $(function(){ 

//       $(".reference_bill").autocomplete({
//           source: function(request, response){
//               var dr_total_amount = parseFloat($('#sum_dr').html());
//               var cr_total_amount = parseFloat($('#sum_cr').html());
//               var inc_id = 0;
//                   if(dr_total_amount != cr_total_amount){
//                        inc_id = increment_id -1 ;
//                   }else{
//                        inc_id = increment_id;
//                   }

//               $ledger_id = $('#ledger_id_'+inc_id).val();
//                 $.ajax({
//                           type: "POST",
//                           url: base_url + 'index.php/tracking/admin/getBillByReferenceLedgerId',
//                           data: "bill_name=" + request.term  + '&ajax=1&ledger_id='+$ledger_id,
//                           dataType: "json",
//                           success: function (data) {
//                                  response(data);
//                           },
//                           error: function (request, error) {
//                               alert('Please Enter Bill Name');
//                           }
//                       });
//             },
//           minLength: 0,
//           appendTo: $("#bill_form")
//       }).focus(function () {
//           $(this).autocomplete("search");
//       });


//   });

//Autocomplte For bill reference Modal
$(function() {
    var refBillType = [
        "Advance",
        "Against Reference",
        "New Reference",
        "On Account"
    ];
    $(".ref_bill_type").autocomplete({
        source: refBillType,
        appendTo: $("#bill_form")
    }).autocomplete("widget").addClass("debit-credit-ui-autocomplete");
});

//for modal
$(function() {
    var accountTypeModal = [
        "Dr",
        "Cr"
    ];
    $(".account_type_modal").autocomplete({
        source: accountTypeModal,
        appendTo: $("#bill_form")
    }).autocomplete("widget").addClass("debit-credit-ui-autocomplete");
});

$(function() {
    var debitTags = [
        "Dr",
        "Cr"
    ];
    $(".debitTags").autocomplete({
        source: debitTags
    }).autocomplete("widget").addClass("debit-credit-ui-autocomplete");
});

$(function() {
    $('.entry_no, .group, .date-focus').focus();
});

function get_ledger(ledger, index) {
    // console.log('ttttttttttt');
    // console.log(ledger);
    // console.log(index);
    $('#tracking_ledger_name').text(ledger);
    $('#bill_ledger_name').text(ledger);
    var url = base_url + 'index.php/accounts/entries/get_ledger_id';
    var queryString = "ledger=" + ledger + '&ajax=1';
    $.ajax({
        type: "POST",
        url: url,
        data: queryString,
        dataType: "json",
        success: function(DATA) {
            if (DATA.SUCESS) {
                $('#tracking_ledger_id').val(DATA.MENU.id);
                $('#bill_ledger_id').val(DATA.MENU.id);
                $('#ledger_id_' + index).val(DATA.MENU.id);
                $('#ledger_id_' + index).closest('.ledger-block').find('.amount').attr('data-id', DATA.MENU.id);
                if (DATA.MENU.tracking_status == '1') {
                    $('#amount_d_' + index).attr('class', 'form-control amount txt_dr txt_tracking text-input');
                    $('#amount_c_' + index).attr('class', 'form-control txt_cr amount txt_tracking validate[required,custom[integer]] text-input');
                    modal_tracking_bind();
                } else if (DATA.MENU.bill_details_status == '1') {
                    $('#amount_d_' + index).attr('class', 'form-control txt_dr amount txt_billing text-input');
                    $('#amount_c_' + index).attr('class', 'form-control txt_cr amount txt_billing validate[required,custom[integer]] text-input');

                    modal_billing_bind();
                }
                check_for_bank_details(index, DATA.MENU.id)
                get_current_balance(index, DATA.MENU.id);
                //modal_tracking_bind();
            }
        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });

}

function modal_billing_bind() {
    $(".txt_billing").keypress(function(e) {
        if (e.which == '13') {
            var inc_id = increment_id;
            var ledger_value = 0;
            var account_type = $('#account_' + inc_id).val();
            if (account_type == 'Dr') {
                ledger_value = parseFloat($('#amount_d_' + inc_id).val());
            }
            if (account_type == 'Cr') {
                ledger_value = parseFloat($('#amount_c_' + inc_id).val());
            }
            $('#bill_body > div').not('div:first').remove();
            $('.account_type_modal').val(account_type);
            $('.ref_bill_type').val('');
            $('.bill_name').val('');
            $('.bill_credit_day').val('');
            $('.bill_amount:last').val(ledger_value);
            $('#cr_dr_cal').val(account_type);
            $('#total_bill').val(ledger_value);
            $('#billModal').modal('show');
        }
    });
}

function modal_tracking_bind() {
    //init tracking row
    //end init tracking row
    $(".txt_tracking").keypress(function(e) {
        if (e.which == '13') {
            var inc_id = increment_id;
            var ledger_value = 0;
            var account_type = $('#account_' + inc_id).val();
            if (account_type == 'Dr') {
                ledger_value = parseFloat($('#amount_d_' + inc_id).val());
            }
            if (account_type == 'Cr') {
                ledger_value = parseFloat($('#amount_c_' + inc_id).val());
            }
            var ledger = $(this).attr('data-id');
            var entry_id = $("#entry_id").val();
            // console.log(ledger);
            if (typeof ledger !== 'undefined') {
                var url = base_url + 'index.php/accounts/entries/get_temp_tracking_by_id';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {ledger: ledger, entry_id: entry_id},
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        if (data.res == 'success') {
                            $("#tracking_ledger_name").html(data.ledger_name);
                            $("#tracking_ledger_id").val(ledger);
                            getTrackingModalBody(ledger);
                            $('#DrModal').modal('show');
                            $("#form").find('#sub_tracking_body').find('input').last().focus();
                            //  $('#DrModal').modal('show');
                        } else {
                            // console.log('lllll')
                            $("#tracking_ledger_id").val(ledger);
                            $('#sub_tracking_body > div').not('div:first').remove();
                            $('.current').val('');
                            $('.sub_tracking_amount').val(ledger_value);
                            $('#total_sub_tracking').val(ledger_value);
                            $('#DrModal').modal('show');
                        }

                    },
                    error: function(request, error) {
                        alert('connection error. please try again.');
                    }
                });
            } else {
                $("#tracking_ledger_id").val(ledger);
                $('#sub_tracking_body > div').not('div:first').remove();
                $('.current').val('');
                $('.sub_tracking_amount').val(ledger_value);
                $('#total_sub_tracking').val(ledger_value);
                $('#DrModal').modal('show');
            }

        }
    });
}

function get_current_balance_____(id, ledger_id) {
    var incre_id = id;
    var text_box_id = 'current_balance_' + incre_id;
    //alert(text_box_id);
    var selected_account = $('#account_' + incre_id).val();
    if (selected_account == 'Dr') {//change textbox disability
        $('#amount_c_' + incre_id).prop('readonly', true);
        $('#amount_c_' + incre_id).removeAttr("name");
        $('#amount_c_' + incre_id).attr("type", "hidden");

        $('#amount_d_' + incre_id).prop('readonly', false);
        $('#amount_d_' + incre_id).attr("name", "amount[]");
        $('#amount_d_' + incre_id).attr("type", "text");
    }
    if (selected_account == 'Cr') {
        $('#amount_d_' + incre_id).attr('readonly', true);
        $('#amount_d_' + incre_id).removeAttr("name");
        $('#amount_d_' + incre_id).attr("type", "hidden");

        $('#amount_c_' + incre_id).attr('readonly', false);
        $('#amount_c_' + incre_id).attr("name", "amount[]");
        $('#amount_c_' + incre_id).attr("type", "text");

    }
    var current_balance_path = 'index.php/accounts/entries/current_balance';
    jQuery.ajax({
        url: base_url + current_balance_path,
        type: 'POST',
        dataType: 'json',
        data: 'ledger_id=' + ledger_id,
        cache: false,
        success: function(data, textStatus, jqXHR) {
            var balance = '';
            for (var i = 0; i < data.length; i++) {
                balance = data[i]['current_balance'];
            }
            //document.getElementById(text_box_id).value = balance;
            document.getElementById(text_box_id).innerHTML = balance;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}

function get_group(group) {
    var url = base_url + 'index.php/accounts/get_group_id';
    var queryString = "group=" + group + '&ajax=1';
    $.ajax({
        type: "POST",
        url: url,
        data: queryString,
        dataType: "json",
        success: function(DATA) {
            if (DATA.SUCESS) {
                $('#group_id').val(DATA.MENU.id);
            }
        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });


}

function get_tracking(tracking) {
    var url = base_url + 'index.php/tracking/admin/get_tracking_id';
    var queryString = "tracking=" + tracking + '&ajax=1';
    $.ajax({
        type: "POST",
        url: url,
        data: queryString,
        dataType: "json",
        success: function(DATA) {
            if (DATA.SUCESS) {
                $('#parent_id').val(DATA.MENU.id);
            }
        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });


}

function get_entry_type_id(entry_type) {
    var url = base_url + 'index.php/accounts/entries/get_entry_type_id';
    var queryString = "entry_type=" + entry_type + '&ajax=1';
    $.ajax({
        type: "POST",
        url: url,
        data: queryString,
        dataType: "json",
        success: function(DATA) {
            if (DATA.SUCESS) {
                $('#entry_type_id').val(DATA.MENU.id);
            }
        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });
}

function getCurrencyValule() {
    var currency_from = $("#currency_from option:selected").val();
    var currency_to = $("#currency_to option:selected").val();
    var url = base_url + 'index.php/currency/admin/get_currency_value';
    var queryString = "currency_from=" + currency_from + "&currency_to=" + currency_to + '&ajax=1';
    $.ajax({
        type: "POST",
        url: url,
        data: queryString,
        dataType: "json",
        success: function(DATA) {
            if (DATA.SUCESS) {
                $('#base_currency_value').val(DATA.MENU);
            }
        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });
}

function getOptionName() {
    var currency_name = $("#currency_from option:selected").text();
    $('#currency_name').val(currency_name);

    var currency_from = $("#currency_from option:selected").val();
    var currency_to = $("#currency_to option:selected").val();

    var url = base_url + 'index.php/currency/admin/get_currency_value';
    var queryString = "currency_from=" + currency_from + "&currency_to=" + currency_to + '&ajax=1';
    $.ajax({
        type: "POST",
        url: url,
        data: queryString,
        dataType: "json",
        success: function(DATA) {
            if (DATA.SUCESS) {
                $('#base_currency_value').val(DATA.MENU);
            }
        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });
}

function set_tracking_data(queryString) {
    var url = base_url + 'index.php/accounts/entries/set_temp_tracking_data';
    $.ajax({
        type: "POST",
        url: url,
        data: queryString,
        dataType: "json",
        success: function(DATA) {
            //alert(DATA);
        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });

}

function set_billwish_data(queryString) {
    var url = base_url + 'index.php/accounts/entries/set_temp_billwish_data';
    $.ajax({
        type: "POST",
        url: url,
        data: queryString,
        dataType: "json",
        success: function(DATA) {
            //alert(DATA);
        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });

}

function reference_bill_by_type(bill_type) {
    if (bill_type == 'Against Reference') {
        $('.bill_name:last').attr('class', 'form-control bill_name reference_bill');
        reference_bill_autocomplete();
    } else {
        $('.bill_name:last').attr('class', 'form-control bill_name ');
    }

}

function reference_bill_autocomplete() {
    $(".reference_bill").autocomplete({
        source: function(request, response) {
            var dr_total_amount = parseFloat($('#sum_dr').html());
            var cr_total_amount = parseFloat($('#sum_cr').html());
            var inc_id = 0;
            if (dr_total_amount != cr_total_amount) {
                inc_id = increment_id - 1;
            } else {
                inc_id = increment_id;
            }

            var ledger_id = $('#ledger_id_' + inc_id).val();

            var rows = $("#bill_body > div").length;
            var total_bill_name = '';
            for (var i = 0; i < rows; i++) {
                var billtype = $("#bill_body > div:eq(" + i + ") div div:nth-child(1) input").val();

                if (billtype == 'Against Reference') {
                    var ref_bill_name = $("#bill_body > div:eq(" + i + ") div div:nth-child(2) input").val();
                    if (ref_bill_name != '') {
                        total_bill_name += ref_bill_name + ',';
                        // console.log(total_bill_name);
                    }
                }
            }
            $.ajax({
                type: "POST",
                url: base_url + 'index.php/tracking/admin/getBillByReferenceLedgerId',
                data: "bill_name=" + request.term + '&ajax=1&ledger_id=' + ledger_id + '&total_bill_name=' + total_bill_name,
                dataType: "json",
                success: function(data) {
                    response(data);


                },
                error: function(request, error) {
                    alert('Please Enter Bill Name');
                }
            });
        },
        minLength: 0,
        appendTo: $("#bill_form")
    }).focus(function() {
        $(this).autocomplete("search");
    });

    $('.reference_bill').on('blur', function() {
        var dr_total_amount = parseFloat($('#sum_dr').html());
        var cr_total_amount = parseFloat($('#sum_cr').html());
        var inc_id = 0;
        if (dr_total_amount != cr_total_amount) {
            inc_id = increment_id - 1;
        } else {
            inc_id = increment_id;
        }

        var ledger_id = $('#ledger_id_' + inc_id).val();
        var str = $("#bill_body > div:last div div:nth-child(2) input").val();
//            var bill_name = str.substr(0,str.indexOf('('));
        var bill_name = str.substr(0, str.indexOf(':'));

        var url = base_url + 'index.php/tracking/admin/getBillByBillnameLedgerId';
        var queryString = "bill_name=" + bill_name + "&ledger_id=" + ledger_id + '&ajax=1';
        $.ajax({
            type: "POST",
            url: url,
            data: queryString,
            dataType: "json",
            success: function(DATA) {
                if (DATA.SUCESS) {
                    var dateAr = DATA.MENU.credit_date.split('-');
                    var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
                    $("#bill_body > div:last div div:nth-child(6) input").val(DATA.MENU.dr_cr);
                    $("#bill_body > div:last div div:nth-child(2) input").val(DATA.MENU.bill_name).attr('readonly', 'readonly');
                    $("#bill_body > div:last div div:nth-child(3) input").val(DATA.MENU.credit_days).attr('readonly', 'readonly');
                    $("#bill_body > div:last div div:nth-child(4) input").val(newDate).attr('readonly', 'readonly');
                    //$( "#bill_body > div:last div div:nth-child(5) input" ).val(DATA.MENU.bill_amount).attr('readonly','readonly');
                    $("#bill_body > div:last div div:nth-child(5) input").val(DATA.MENU.bill_amount);
                }
            },
            error: function(request, error) {
                alert('connection error. please try again.');
            }
        });
    });
}

//---------asit---------

//price validation
$(".amount").on("keyup", function() {
    var valid = /^\d{0,12}(\.\d{0,2})?$/.test(this.value),
            val = this.value;

    if (!valid) {
        // console.log("Invalid Amount!");
        this.value = val.substring(0, val.length - 1);
    }
});

//set bill body when back

function getBillModalBody(ledger) {
    function add_bill_row() {
        $('#clone_div_bill')
                .clone()
                .appendTo($('#bill_body')).css('display', 'block');

        $('#bill_body').find('.current:last').focus();


        var dr_total_amount = parseFloat($('#sum_dr').html());
        var cr_total_amount = parseFloat($('#sum_cr').html());
        var inc_id = 0;
        if (dr_total_amount != cr_total_amount) {
            inc_id = increment_id - 1;
        } else {
            inc_id = increment_id;
        }
        var account_type = $('#account_' + inc_id).val();

        if (account_type == 'Dr') {
            var ledger_value = parseFloat($('#amount_d_' + inc_id).val());
        }
        if (account_type == 'Cr') {
            var ledger_value = parseFloat($('#amount_c_' + inc_id).val());
        }
        var total_bill_amount = 0.00;


        var rows = $("#bill_body > div").length;
        for (var i = 0; i < (rows - 1); i++) {
            total_bill_amount = total_bill_amount + parseFloat($("#bill_body > div:eq(" + i + ") div div:nth-child(5) input").val());
        }
        $(".bill_amount:last").val(ledger_value - total_bill_amount);



        $('.close_pop_bill:last').on('keypress', function(e) {
            if (e.which == '13') {
                // console.log('zzzzzz');
                var dr_total_amount = parseFloat($('#sum_dr').html());
                var cr_total_amount = parseFloat($('#sum_cr').html());
                var inc_id = 0;
                if (dr_total_amount != cr_total_amount) {
                    inc_id = increment_id - 1;
                } else {
                    inc_id = increment_id;
                }

                var account_type = $('#account_' + inc_id).val();

                if (account_type == 'Dr') {
                    var ledger_value = parseFloat($('#amount_d_' + inc_id).val());
                }
                if (account_type == 'Cr') {
                    var ledger_value = parseFloat($('#amount_c_' + inc_id).val());
                }
                var total_bill_amount = 0.00;

                var ledger_id = $('#ledger_id_' + inc_id).val();
                var billPostData = 'ajax=1&index=' + inc_id;
                var billJsonArray = [];

                // console.log('bbbbbbbb')
                var rows = $("#bill_body > div").length;
                for (var i = 0; i < rows; i++) {
                    var ledger_type = $("#bill_body > div:eq(" + i + ") div div:nth-child(6) input").val();
                    if (ledger_type == account_type) {
                        total_bill_amount = total_bill_amount + parseFloat($("#bill_body > div:eq(" + i + ") div div:nth-child(5) input").val());
                    } else {
                        total_bill_amount = total_bill_amount - parseFloat($("#bill_body > div:eq(" + i + ") div div:nth-child(5) input").val());
                    }
                    if (total_bill_amount < 0) {
                        total_bill_amount = 0 - total_bill_amount;
                    }
                    var jsonObj = {ledger_index: inc_id, ledger_id: ledger_id,
                        bill_amount: parseFloat($("#bill_body > div:eq(" + i + ") div div:nth-child(5) input").val()),
                        ref_type: $("#bill_body > div:eq(" + i + ") div div:nth-child(1) input").val(),
                        dr_cr: $("#bill_body > div:eq(" + i + ") div div:nth-child(6) input").val(),
                        bill_name: $("#bill_body > div:eq(" + i + ") div div:nth-child(2) input").val(),
                        credit_days: $("#bill_body > div:eq(" + i + ") div div:nth-child(3) input").val(),
                        credit_date: $("#bill_body > div:eq(" + i + ") div div:nth-child(4) input").val()};
                    billJsonArray.push(jsonObj);
                    var billJson = JSON.stringify(billJsonArray);
                    var billPostData = billPostData + '&jsonBill=' + billJson;

                }
                // console.log('fffff')
                // console.log(ledger_value);
                // console.log(total_bill_amount);
                //$('#total_bill').val(total_bill_amount);
                if (total_bill_amount <= ledger_value) {
                    // console.log(ledger_value);
                    // console.log(total_bill_amount);
                    if (ledger_value == total_bill_amount) {
                        set_billwish_data(billPostData);

                        $('#billModal').modal('hide');
                        if (dr_total_amount != cr_total_amount) {
                            $('.debitTags').focus();
                        } else {
                            var temp_ledger_id = $("#bill_ledger_id").val();
                            if (temp_ledger_id != ledger_id) {
                                ledger_id = temp_ledger_id
                            }
                            var all = $('input[data-id="' + ledger_id + '"]').closest('.form-group').nextAll().find('.amount');
                            // console.log('mmmmmmmm');
                            if (typeof all[0] !== 'undefined') {
                                $(all[0]).closest('.form-group').find('.debitTags').focus()
                            } else {
                                $('.narration').focus();
                            }
                        }
                    } else {
                        add_bill_row();
                    }
                } else {
                    $(this).val('');
                    alert('Please Give Proper Amount..!');
                }
            }
        });

        //Autocomplte For bill reference Modal
        var refBillType = [
            "Advance",
            "Against Reference",
            "New Reference",
            "On Account"
        ];
        $(".ref_bill_type").autocomplete({
            source: refBillType,
            appendTo: $("#bill_form")
        }).autocomplete("widget").addClass("debit-credit-ui-autocomplete");

        var accountTypeModal = [
            "Dr",
            "Cr"
        ];
        $(".account_type_modal").autocomplete({
            source: accountTypeModal,
            appendTo: $("#bill_form")
        }).autocomplete("widget").addClass("debit-credit-ui-autocomplete");

        $(".bill_credit_day").on('keyup', function(e) {
            var credit_days = parseInt($("#bill_body > div:last div div:nth-child(3) input").val()) || 0;
            var d = new Date(new Date().setDate(new Date().getDate() + credit_days));
            var day = d.getDate();
            var month = d.getMonth() + 1;
            var year = d.getFullYear();
            if (day < 10) {
                day = "0" + day;
            }
            if (month < 10) {
                month = "0" + month;
            }
            var date = day + "-" + month + "-" + year;

            $('#billModal').find('.bill_credit_date:last').val(date);
        });

        $(".reference_bill").autocomplete({
            source: function(request, response) {
                var dr_total_amount = parseFloat($('#sum_dr').html());
                var cr_total_amount = parseFloat($('#sum_cr').html());
                var inc_id = 0;
                if (dr_total_amount != cr_total_amount) {
                    inc_id = increment_id - 1;
                } else {
                    inc_id = increment_id;
                }

                $ledger_id = $('#ledger_id_' + inc_id).val();
                $.ajax({
                    type: "POST",
                    url: base_url + 'index.php/tracking/admin/getBillByReferenceLedgerId',
                    data: "bill_name=" + request.term + '&ajax=1&ledger_id=' + $ledger_id,
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    },
                    error: function(request, error) {
                        alert('Please Enter Bill Name');
                    }
                });
            },
            minLength: 0,
            appendTo: $("#bill_form")
        }).focus(function() {
            $(this).autocomplete("search");
        });

        function reference_bill_by_type(bill_type) {
            if (bill_type == 'Against Reference') {
                $('.bill_name:last').attr('class', 'form-control bill_name reference_bill');
                reference_bill_autocomplete();
            } else {
                $('.bill_name:last').attr('class', 'form-control bill_name ');

            }

        }
        //init
        init_bill_tracking_forword_backword();
        //init
        //remove init
        $(".close_pop_bill").on('keypress', function() {
        }).on('keydown', function(e) {
            if (e.keyCode == 13) {
                var ledger_val = $(this).closest('.form-group').find('.bill_amount').val();
                var amount = $('#total_bill').val();
                var all = $(this).closest('.form-group').prevAll().find('.bill_amount');
                $.each(all, function(index, element) {
                    var value = $(element).val()
                    ledger_val = parseFloat(ledger_val) + parseFloat(value);
                });
                if (parseFloat(amount) == ledger_val) {
                    $(this).closest('.form-group').nextAll().remove();
                    $(this).focus();
                } else {
                    return true;
                }
            }
        });
        //end remove init
    }
    var entry_id = $('#entry_id').val();
    var url = base_url + 'index.php/accounts/entries/get_temp_bill_by_id';
    $.ajax({
        type: "POST",
        url: url,
        data: {ledger: ledger, entry_id: entry_id},
        dataType: "json",
        success: function(data) {
            if (data.res == 'success') {
                // console.log('zzzzzzzzzzz')
                $('#bill_body').html('');
                var ledger_value = 0;
                $.each(data.bill_arr, function(index, value) {
                    //add row on bill modal  
                    add_bill_row();
                    //init

                    //set value
                    $('#bill_body').find('.current:last').val(value.ref_type);
                    $('#bill_body').find('.bill_name:last').val(value.bill_name);
                    $('#bill_body').find('.bill_credit_day:last').val(value.credit_days);
                    $('#bill_body').find('.bill_credit_date:last').val(value.credit_date);
                    $('#bill_body').find('.bill_amount:last').val(value.bill_amount);
                    $('#bill_body').find('.account_type_modal:last').val(value.dr_cr);
                    //end set value
                    $('#cr_dr_cal').val(value.dr_cr);
                    ledger_value = ledger_value + parseInt(value.bill_amount);
                });

                // $("#bill_body").html(html);
                $('#total_bill').val(ledger_value);
            } else {
                $('#bill_body').html('');
                add_bill_row();
                $('#total_bill').val(ledger_value);
            }

        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });
    //$('#sub_tracking_body > div').not('div:first').remove();
}

function getTrackingModalBody(ledger) {
    //init show bill modal
    function show_bill_modal() {
        // console.log('ppppppppppppp');
        var dr_total_amount = parseFloat($('#sum_dr').html());
        var cr_total_amount = parseFloat($('#sum_cr').html());
        var inc_id = 0;
        var ledger_value = 0;
        if (dr_total_amount != cr_total_amount) {
            inc_id = increment_id - 1;
        } else {
            inc_id = increment_id;
        }
        var account_type = $('#account_' + inc_id).val();

        if (account_type == 'Dr') {
            ledger_value = parseFloat($('#amount_d_' + inc_id).val());
        }
        if (account_type == 'Cr') {
            ledger_value = parseFloat($('#amount_c_' + inc_id).val());
        }
        // var ledger = $('#account_' + inc_id).closest('.form-group').find('.txt_tracking').attr('data-id');
        var ledger = $('#tracking_ledger_id').val();

        // console.log(ledger);
        //
        var entry_id = $('#entry_id').val();
        var url = base_url + 'index.php/accounts/entries/get_temp_bill_by_id';
        $.ajax({
            type: "POST",
            url: url,
            data: {ledger: ledger, entry_id: entry_id},
            dataType: "json",
            success: function(data) {
                // console.log(data);
                if (data.res == 'success') {
                    $('#cr_dr_cal').val(account_type);
                    $('#bill_body > div').not('div:first').remove();
                    $('.account_type_modal').val(account_type);
                    $("#bill_ledger_name").html(data.ledger_name);
                    $("#bill_ledger_id").val(ledger);
                    getBillModalBody(ledger);
                    //   $('.current').val(account_type);
                    $('.close_pop_bill:last').val(ledger_value);
                    $('#total_bill').val(ledger_value);
                    $('#billModal').modal('show');

                    $("#bill_form").find('#bill_body').find('input').last().focus();
                } else {
                    $("#bill_ledger_id").val(ledger);
                    $('#cr_dr_cal').val(account_type);
                    $("#bill_ledger_name").html(data.ledger_name);
                    $("#bill_ledger_id").val(ledger);
                    $('#bill_body > div').not('div:first').remove();
                    $('.account_type_modal').val(account_type);
                    $('#bill_body > div').not('div:first').remove();
                    //  $('.current').val(account_type);
                    $('.ref_bill_type').val('');
                    $('.bill_name').val('');
                    $('.bill_credit_day').val('');
                    $('.close_pop_bill:last').val(ledger_value);
                    $('#total_bill').val(ledger_value);
                    $('#billModal').modal('show');
                }

            },
            error: function(request, error) {
                alert('connection error. please try again.');
            }
        });
        //

    }
    //end init show bill modal
    //init
    function billing_status_check() {
        var dr_total_amount = parseFloat($('#sum_dr').html());
        var cr_total_amount = parseFloat($('#sum_cr').html());
        var inc_id = 0;
        if (dr_total_amount != cr_total_amount) {
            inc_id = increment_id - 1;
        } else {
            inc_id = increment_id;
        }
        var ledger = $('#select2_sample_' + inc_id).val();

        var url = base_url + 'index.php/accounts/entries/get_ledger_id';
        var queryString = "ledger=" + ledger + '&ajax=1';
        $.ajax({
            type: "POST",
            url: url,
            data: queryString,
            dataType: "json",
            success: function(DATA) {
                if (DATA.SUCESS) {
                    if (DATA.MENU.bill_details_status == '1') {
                        show_bill_modal();
                    } else {
                        if (dr_total_amount != cr_total_amount) {
                            $('.debitTags').focus();
                        } else {
                            $('.narration').focus();
                        }
                    }

                }
            },
            error: function(request, error) {
                alert('connection error. please try again.');
            }
        });
    }
    //init
    function add_tracking_row(current_requir_value) {
        $('#clone_div')
                .clone()
                .appendTo($('#sub_tracking_body')).css('display', 'block');
        $('#sub_tracking_body').find('.current:last').focus();
        $(".sub_tracking_amount:last").val(current_requir_value);
        // console.log(current_requir_value);
        $('.close_pop_dr:last').on('keypress', function(e) {
            if (e.which == '13') {
                // console.log('55555')
                var dr_total_amount = parseFloat($('#sum_dr').html());
                var cr_total_amount = parseFloat($('#sum_cr').html());
                var inc_id = 0;
                if (dr_total_amount != cr_total_amount) {
                    inc_id = increment_id - 1;
                } else {
                    inc_id = increment_id;
                }
                var account_type = $('#account_' + inc_id).val();
                if (account_type == 'Dr') {
                    var ledger_value = parseFloat($('#amount_d_' + inc_id).val());
                }
                if (account_type == 'Cr') {
                    var ledger_value = parseFloat($('#amount_c_' + inc_id).val());
                }

                var ledger_id = $('#ledger_id_' + inc_id).val();
                //var queryString = 'ajax=1&index='+ inc_id +'&ledger_id='+ledger_id ;
                var queryString = 'ajax=1&index=' + inc_id;
                var trackingJsonArray = [];

                var total_sub_tracking_amount = 0.00;
                var rows = $("#sub_tracking_body > div").length;
                for (var i = 0; i < rows; i++) {
                    total_sub_tracking_amount = total_sub_tracking_amount + parseFloat($("#sub_tracking_body > div:eq(" + i + ") div div:last input").val());
                    var jsonObj = {account_type: account_type, ledger_index: inc_id, ledger_id: ledger_id, tracking_amount: parseFloat($("#sub_tracking_body > div:eq(" + i + ") div div:last input").val()), tracking_name: $("#sub_tracking_body > div:eq(" + i + ") div div:first input").val()};
                    trackingJsonArray.push(jsonObj);
                    var trackingJson = JSON.stringify(trackingJsonArray);
                    var queryString = queryString + '&jsonTracking=' + trackingJson;

                }
                //$('#total_sub_tracking').val(ledger_value);
                if (total_sub_tracking_amount <= ledger_value) {
                    var current_requir_value = parseFloat(ledger_value) - parseFloat(total_sub_tracking_amount)
                    if (ledger_value == total_sub_tracking_amount) {

                        set_tracking_data(queryString);

                        $('#DrModal').modal('hide');
                        billing_status_check();
                        //$('#billModal').modal('show'); 
                        //show_bill_modal();
                        // if(dr_total_amount != cr_total_amount){
                        //     $('.debitTags').focus();
                        // }else{
                        //     $('.narration').focus();
                        // }
                    } else {
                        add_tracking_row(current_requir_value);
                    }
                } else {
                    $(this).val('');
                    alert('Please Give Proper Amount..!');
                }
                //init
                init_tracking_forword_backword();
                //init
            }
        });

        // For autocomplete SUDIP For Tracking
        $(".modal_tracking").autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: base_url + 'index.php/tracking/admin/getAccessTrackingDetails',
                    data: "group=" + request.term + '&ajax=1',
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    },
                    error: function(request, error) {
                        alert('Please Enter Tracking Name');
                    }
                });
            },
            appendTo: $("#form")
        });
        init_tracking_forword_backword();
        //init
        $(".close_pop_dr").on('keypress', function() {
        }).on('keydown', function(e) {
            var ledger_val = $(this).val();
            var amount = $('#total_sub_tracking').val();
            if (e.keyCode == 13) {
                // console.log('6666')
                var all = $(this).closest('.form-group').prevAll().find('.sub_tracking_amount');
                $.each(all, function(index, element) {
                    var value = $(element).val()
                    ledger_val = parseFloat(ledger_val) + parseFloat(value);
                });
                if (parseFloat(amount) == ledger_val) {
                    $(this).closest('.form-group').nextAll().remove();
                    $(this).focus();
                } else {
                    return true;
                }
            }
        });
        //init
    }
    var url = base_url + 'index.php/accounts/entries/get_temp_tracking_by_id';
    var entry_id = $("#entry_id").val();
    $.ajax({
        type: "POST",
        url: url,
        data: {ledger: ledger, entry_id: entry_id},
        dataType: "json",
        success: function(data) {
            // console.log(data);
            if (data.res == 'success') {
                $('#sub_tracking_body').html('');
                var ledger_value = 0;
                $.each(data.tracking_arr, function(index, value) {
                    ledger_value = ledger_value + parseInt(value.tracking_amount);
                    //add row on bill modal  
                    add_tracking_row(ledger_value);
                    //init

                    //set value
                    $('#sub_tracking_body').find('.current:last').val(value.tracking_name);
                    $('#sub_tracking_body').find('.sub_tracking_amount:last').val(value.tracking_amount);

                    //end set value

                });
                // $("#bill_body").html(html);
                $('#total_sub_tracking').val(ledger_value);
            } else {
                $('#bill_body').html('');
                add_tracking_row(ledger_value);
                $('#total_sub_tracking').val(ledger_value);
            }

        },
        error: function(request, error) {
            alert('connection error. please try again.');
        }
    });
}
function init_bill_tracking_forword_backword() {
//bill form back
    $("#bill_form").find('#bill_body').find('input').on('keypress', function() {
    }).on('keydown', function(e) {
        if (e.keyCode == 37) {

            var inputs = $(this).parents("#bill_form").find('#bill_body').find("input");
            var idx = inputs.index(this);
            // console.log(idx);
            if (idx == inputs.length - 1) {
                inputs[0].select();
            } else {
                if (typeof inputs[idx - 1] !== 'undefined') {
                    inputs[idx - 1].focus(); //  handles submit buttons
                    inputs[idx - 1].select();
                } else {
                    var ledger = $('#billModal').find("#bill_ledger_id").val();
                    var url = base_url + 'index.php/accounts/entries/get_ledger_by_id';
                    var queryString = "ledger=" + ledger + '&ajax=1';
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: queryString,
                        dataType: "json",
                        success: function(DATA) {
                            if (DATA.SUCESS) {
                                if (DATA.MENU.tracking_status == '1') {
                                    $('#billModal').modal('hide');
                                    getTrackingModalBody(ledger);
                                    $('#DrModal').modal('show');
                                    $("#form").find('#sub_tracking_body').find('input').last().focus();
                                } else {
                                    $('#billModal').modal('hide');
                                    $('input[data-id="' + ledger + '"]').focus();
                                }

                            }
                        },
                        error: function(request, error) {
                            alert('connection error. please try again.');
                        }
                    });

                }
            }

        }
    });
//bill form move forward
    $("#bill_form").find('#bill_body').find('input').on('keypress', function() {
    }).on('keydown', function(e) {
        if (e.keyCode == 13) {
            var inputs = $(this).parents("#bill_form").find('#bill_body').find("input");

            var idx = inputs.index(this);
            // console.log(idx);
            if (idx == inputs.length + 1) {
                inputs[0].select();
            } else {
                if (typeof inputs[idx - 1] !== 'undefined') {
                    if (typeof inputs[idx + 1] !== 'undefined') {
                        inputs[idx + 1].focus(); //  handles submit buttons
                        inputs[idx + 1].select();
                    } else {
                        return true;
                    }
                } else {
                    inputs[1].focus();
                }
            }
            return false;
        }
    });

}

//init tracking forword and backword
function init_tracking_forword_backword() {
    //tracking  form back
    $("#form").find('#sub_tracking_body').find('input').on('keypress', function() {
    }).on('keydown', function(e) {
        if (e.keyCode == 37) {
            var inputs = $(this).parents("#form").find('#sub_tracking_body').find("input");
            var idx = inputs.index(this);
            if (idx == inputs.length - 1) {
                inputs[0].select();
                inputs[0].focus();
            } else {
                if (typeof inputs[idx - 1] !== 'undefined') {

                    inputs[idx - 1].focus(); //  handles submit buttons
                    inputs[idx - 1].select();
                } else {
                    $("#DrModal").modal('hide');
                    $('.modal-backdrop').remove();
                    var ledger_val = $("#tracking_ledger_id").val();
                    $('input[data-id="' + ledger_val + '"]').focus();

                }
            }
            return false;
        }
    });
//bill form move forward
    $("#form").find('#sub_tracking_body').find('input').on('keypress', function() {
    }).on('keydown', function(e) {
        if (e.keyCode == 13) {
            var inputs = $(this).parents("#form").find('#sub_tracking_body').find("input");
            var idx = inputs.index(this);
            if (idx == inputs.length + 1) {
                inputs[0].select();
            } else {
                if (typeof inputs[idx - 1] !== 'undefined') {
                    if (typeof inputs[idx + 1] !== 'undefined') {
                        inputs[idx + 1].focus(); //  handles submit buttons
                        inputs[idx + 1].select();
                    } else {
                        return true;
                    }
                } else {
                    inputs[1].focus();
                }
            }
            return false;
        }
    });
}
//end tracking forword and backwors
//back from narration

$('.narration').on('keypress', function() {
}).on('keydown', function(e) {
    if (e.keyCode == 37) {
        // console.log('bbb')
        var ledger = $(".ledger-block").last().find('.amount').attr('data-id');
        //ledger trac status
        var url = base_url + 'index.php/accounts/entries/get_ledger_by_id';
        var queryString = "ledger=" + ledger + '&ajax=1';
        $.ajax({
            type: "POST",
            url: url,
            data: queryString,
            dataType: "json",
            success: function(DATA) {
                if (DATA.SUCESS) {
                    $('#tracking_ledger_id').val(ledger);
                    $('#bill_ledger_id').val(ledger);
                    $('#tracking_ledger_name').text(DATA.MENU.ladger_name);
                    $('#bill_ledger_name').text(DATA.MENU.ladger_name);
                    if (DATA.MENU.bill_details_status == '1') {
                        //end ledger trac status
                        // console.log(ledger);
                        getBillModalBody(ledger);
                        $('#billModal').modal('show');
                        $("#bill_form").find('#bill_body').find('input').last().focus();
                    } else if (DATA.MENU.tracking_status == '1') {
                        $('#DrModal').modal('show');
                    } else {
                        $(".ledger-block").last().find('.amount').focus();
                    }

                }
            },
            error: function(request, error) {
                alert('connection error. please try again.');
            }
        });

    }
});

//bill form back
$("#bill_form").find('#bill_body').find('input').on('keypress', function() {
}).on('keydown', function(e) {
    if (e.keyCode == 37) {

        var inputs = $(this).parents("#bill_form").find('#bill_body').find("input");
        var idx = inputs.index(this);
        // console.log(idx);

        if (idx == inputs.length - 1) {
            inputs[0].focus();
            inputs[0].select();
        } else {
            if (typeof inputs[idx - 1] !== 'undefined') {
                inputs[idx - 1].focus(); //  handles submit buttons
                inputs[idx - 1].select();
            } else {
                var ledger = $('#billModal').find("#bill_ledger_id").val();
                var url = base_url + 'index.php/accounts/entries/get_ledger_by_id';
                var queryString = "ledger=" + ledger + '&ajax=1';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: queryString,
                    dataType: "json",
                    success: function(DATA) {
                        if (DATA.SUCESS) {
                            if (DATA.MENU.tracking_status == '1') {
                                $('#billModal').modal('hide');
                                var ledger = $('#billModal').find("#bill_ledger_id").val();
                                getTrackingModalBody(ledger);
                                $('#DrModal').modal('show');
                                $("#form").find('#sub_tracking_body').find('input').last().focus();
                            } else {
                                $('#billModal').modal('hide');
                                $('input[data-id="' + ledger + '"]').focus();
                            }

                        }
                    },
                    error: function(request, error) {
                        alert('connection error. please try again.');
                    }
                });

            }
        }

    }
});
//bill form move forward
$("#bill_form").find('#bill_body').find('input').on('keypress', function() {
}).on('keydown', function(e) {
    if (e.keyCode == 13) {
        var inputs = $(this).parents("#bill_form").find('#bill_body').find("input");

        var idx = inputs.index(this);
        // console.log(idx);
        if (idx == inputs.length + 1) {
            inputs[0].select();
        } else {
            if (typeof inputs[idx - 1] !== 'undefined') {
                if (typeof inputs[idx + 1] !== 'undefined') {
                    inputs[idx + 1].focus(); //  handles submit buttons
                    inputs[idx + 1].select();
                } else {
                    return true;
                }
            } else {
                inputs[1].focus();
            }
        }
        return false;
    }
});

//tracking  form back
$("#form").find('#sub_tracking_body').find('input').on('keypress', function() {
}).on('keydown', function(e) {
    if (e.keyCode == 37) {
        var inputs = $(this).parents("#form").find('#sub_tracking_body').find("input");
        var idx = inputs.index(this);
        // console.log(idx);
        if (idx == inputs.length - 1) {
            // console.log('2222');
            inputs[0].select();
        } else {
            if (typeof inputs[idx - 1] !== 'undefined') {
                // console.log('33333');
                inputs[idx - 1].focus(); //  handles submit buttons
                inputs[idx - 1].select();
            } else {
                // console.log('44444');
                $("#DrModal").modal('hide');
                $('.modal-backdrop').remove();
                var ledger_val = $("#tracking_ledger_id").val();
                $('input[data-id="' + ledger_val + '"]').focus();
                // $(".ledger-block").last().find('.amount').focus();
            }
        }
        return false;
    }
});
//tracking form move forward
$("#form").find('#sub_tracking_body').find('input').on('keypress', function() {
}).on('keydown', function(e) {
    if (e.keyCode == 13) {
        var inputs = $(this).parents("#form").find('#sub_tracking_body').find("input");
        var idx = inputs.index(this);
        // console.log(idx);
        if (idx == inputs.length + 1) {
            inputs[0].select();
        } else {
            if (typeof inputs[idx - 1] !== 'undefined') {
                console.log(inputs[idx + 1])
                if (typeof inputs[idx + 1] !== 'undefined') {
                    inputs[idx + 1].focus(); //  handles submit buttons
                    inputs[idx + 1].select();
                } else {
                    return true;
                }

            } else {
                inputs[1].focus();
            }
        }
        return false;
    }
});

// back from account type 

$(".account-type").on('keypress', function() {
}).on('keydown', function(e) {

    if (e.keyCode == 37) {
        var ledger = $(this).closest(".ledger-block").prev().prev().find('.amount').attr('data-id');
        if (typeof ledger === 'undefined') {
            var ledger = $(this).closest("#input_fields_wrap").prev().prev().find('.amount').attr('data-id');
        }
        //ledger trac status
        // console.log(ledger)
        var url = base_url + 'index.php/accounts/entries/get_ledger_by_id';
        var queryString = "ledger=" + ledger + '&ajax=1';
        $.ajax({
            type: "POST",
            url: url,
            data: queryString,
            dataType: "json",
            success: function(DATA) {
                if (DATA.SUCESS) {
                    $('#tracking_ledger_id').val(ledger);
                    $('#bill_ledger_id').val(ledger);
                    $('#tracking_ledger_name').text(DATA.MENU.ladger_name);
                    $('#bill_ledger_name').text(DATA.MENU.ladger_name);
                    if (DATA.MENU.bill_details_status == '1') {
                        //end ledger trac status
                        getBillModalBody(ledger);
                        $('#billModal').modal('show');
                        $("#bill_form").find('#bill_body').find('input').last().focus();
                    } else if (DATA.MENU.tracking_status == '1') {
                        getTrackingModalBody(ledger);
                        $('#DrModal').modal('show');
                        $("#form").find('#sub_tracking_body').find('input').last().focus();
                    } else {
                        $(this).closest(".ledger-block").prev().prev().find('.amount').focus();
                    }

                }
            },
            error: function(request, error) {
                alert('connection error. please try again.');
            }
        });
    }
});

//remove bill row
$(".close_pop_bill").on('keypress', function() {
}).on('keydown', function(e) {
    if (e.keyCode == 13) {
        var ledger_val = $(this).closest('.form-group').find('.bill_amount').val();
        var amount = $('#total_bill').val();
        var all = $(this).closest('.form-group').prevAll().find('.bill_amount');
        $.each(all, function(index, element) {
            var value = $(element).val()
            ledger_val = parseFloat(ledger_val) + parseFloat(value);
        });
        if (parseFloat(amount) == ledger_val) {
            $(this).closest('.form-group').nextAll().remove();
            $(this).focus();
        } else {
            return true;
        }
    }
});
//remove tracking row
$(".close_pop_dr").on('keypress', function() {
}).on('keydown', function(e) {
    var ledger_val = $(this).val();
    var amount = $('#total_sub_tracking').val();
    if (e.keyCode == 13) {
        var all = $(this).closest('.form-group').prevAll().find('.sub_tracking_amount');
        $.each(all, function(index, element) {
            var value = $(element).val()
            ledger_val = parseFloat(ledger_val) + parseFloat(value);
        });
        if (parseFloat(amount) == ledger_val) {
            $(this).closest('.form-group').nextAll().remove();
            $(this).focus();
        } else {
            return true;
        }
        // console.log(ledger_val);
    }
});
//back bank
$(".amount").on('keypress', function() {
}).on('keydown', function(e) {
    var ledger_id=$(this).attr('data-id');
    if (e.keyCode == 37) {
         var url = base_url + 'index.php/accounts/entries/check_bank_betails';
    $.ajax({
        method: "POST",
        url: url,
        data: {ledger_id: ledger_id},
        dataType: 'json'
    }).done(function(data) {
        if (data.res == 'success') {
            var entry_no = $("#entry_no").val();
            $("#bank_ledger_id").val(ledger_id);
            $("#bank_entry_no").val(entry_no);
            //get temp saved data
            var url = base_url + 'index.php/accounts/entries/get_temp_bank_betails';
            $.ajax({
                method: "POST",
                url: url,
                data: {ledger_id: ledger_id, entry_no: entry_no},
                dataType: 'json'
            }).done(function(data) {
                var tran_type_html = '';
                tran_type_html += '<option value="">select tran. type</option>';
                $.each(data.transaction_types, function(index, value) {
                    tran_type_html += '<option value="' + value.id + '">' + value.name + '</option>';
                });
                if (data.res == 'success') {
                    $(".bank_input_fields_wrap").html('');

                    $.each(data.temp_bank_data, function(index, value) {
                        var html = '';
                        html += '<div class="row">';
                        html += '<div class="col-md-2">';
                        html += '<div class="form-group">';
                        html += '<select name="transaction_type[]" class="form-control" id="tran_type_' + index + '">';
                        html += tran_type_html;
                        html += '</select>';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-1 min-padding">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control" placeholder="Instrument No" name="instrument_no[]" value="' + value.instrument_no + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-1 min-padding">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control datepicker" placeholder="Instrument Date" name="instrument_date[]" value="' + value.instrument_date + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-2">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control" placeholder="Bank Name" name="bank_name[]" value="' + value.bank_name + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-2">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control" placeholder="Branch Name" name="branch_name[]" value="' + value.branch_name + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-2">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control" placeholder="IFSC Code" name="ifsc_code[]" value="' + value.ifsc_code + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-2">';
                        html += '<div class="form-group">';
                        html += '<input type="text" class="form-control amount" placeholder="Amount" name="bank_amount[]" value="' + value.bank_amount + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div>';
                        html += '<div class="form-group close-div">';
                        html += '<a href="javascript:void(0);" class="close-btn remove_field"><i class="fa fa-times" aria-hidden="true"></i></a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        $('.bank_input_fields_wrap').append(html);
                        $("#tran_type_" + index + " option[value='" + value.transaction_type + "']").attr("selected", "selected");
                        //init date picker
                        $('.datepicker').datepicker({
                            format: 'yyyy-mm-dd',
                            todayHighlight: true,
                            autoclose: true
                        });
                        //end init date picker
                        //remove feild
                        $('.bank_input_fields_wrap').on("click", ".remove_field", function(e) { //user click on remove text
                            e.preventDefault();
                            $(this).closest('.row').remove();
                        });
                        //end remove feild

                    });
                    $("#bankModal").modal('show');
                } else {
                    $(".bank_input_fields_wrap").html('');
                    var html = '';
                    html += '<div class="row">';
                    html += '<div class="col-md-2">';
                    html += '<div class="form-group">';
                    html += '<select name="transaction_type[]" class="form-control">';
                    html += tran_type_html;
                    html += '</select>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-1 min-padding">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control" placeholder="Instrument No" name="instrument_no[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-1 min-padding">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control datepicker" placeholder="Instrument Date" name="instrument_date[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-2">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control" placeholder="Bank Name" name="bank_name[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-2">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control" placeholder="Branch Name" name="branch_name[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-2">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control" placeholder="IFSC Code" name="ifsc_code[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-2">';
                    html += '<div class="form-group">';
                    html += '<input type="text" class="form-control amount" placeholder="Amount" name="bank_amount[]">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div>';
                    html += '<div class="form-group close-div">';
                    html += '<a href="javascript:void(0);" class="close-btn remove_field"><i class="fa fa-times" aria-hidden="true"></i></a>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    $('.bank_input_fields_wrap').append(html);
                    //init date picker
                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        todayHighlight: true,
                        autoclose: true
                    });
                    //end init date picker
                }
                $("#bankModal").modal('show');
            });
            //end get temp saved data

        }
    });
    }
});
//Debugging ......
function log(msg, color) {
    color = color || "black";
    bgc = "White";
    switch (color) {
        case "success":
            color = "Green";
            bgc = "LimeGreen";
            break;
        case "info":
            color = "DodgerBlue";
            bgc = "Turquoise";
            break;
        case "error":
            color = "Red";
            bgc = "Black";
            break;
        case "start":
            color = "OliveDrab";
            bgc = "PaleGreen";
            break;
        case "warning":
            color = "Tomato";
            bgc = "Black";
            break;
        case "end":
            color = "Orchid";
            bgc = "MediumVioletRed";
            break;
        default:
            color = color;
    }
    if (typeof msg == "object") {
    } else if (typeof color == "object") {
        // console.log("%c" + msg, "color: PowderBlue;font-weight:bold; background-color: RoyalBlue;");
        // console.log(color);
    } else {
        // console.log("%c" + msg, "color:" + color + ";font-weight:bold; background-color: " + bgc + ";");
    }
}

// notification remove
$("#trashEntryMenu .permanentlyDeleteEntry").on('click', function(e) {
    e.preventDefault();
    var anchor = $(this);
    var url = $(this).attr('href');
    var cur_trash_entry_count = $("#trash_entry_count").data('count');
    $.ajax({
        url: url,
        type: 'POST',
        success: function(response) {
            if ( $.trim(response) == "1") {
                anchor.closest('.trashyEntry').remove();
                $(".trash_entry_count").html((cur_trash_entry_count-1));
                $("#trash_entry_count").data("count", (cur_trash_entry_count-1));
            }
            console.log(response);
        },
        error: function(response) {
            console.log(response);
        },
    });

});

// entry restore
$("#trashEntryMenu .restoreEntry").on('click', function(e) {
    e.preventDefault();
    var anchor = $(this);
    var url = $(this).attr('href');
    var cur_trash_entry_count = $("#trash_entry_count").data('count');
    $.ajax({
        url: url,
        type: 'POST',
        success: function(response) {
            if ( $.trim(response) == "1") {
                anchor.closest('.trashyEntry').remove();
                $(".trash_entry_count").html((cur_trash_entry_count-1));
                $("#trash_entry_count").data("count", (cur_trash_entry_count-1));
                // window.location.reload();
            }
            console.log(response);
        },
        error: function(response) {
            console.log(response);
        },
    });

});