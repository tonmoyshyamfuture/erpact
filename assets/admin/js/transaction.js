var full_path = window.location.protocol + "//" + window.location.host+'/';
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
$('#contact_id').select2({
  ajax: {
    url: full_path+'transaction_inventory/inventory/getAllContacts',
    dataType: 'json',
    processResults: function (data) {
      // Tranforms the top-level key of the response object from 'items' to 'results'
      return {
        results: data
      };
    }
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
    $("#addGroup").modal('show');
});
$(document).ready(function() {
    //default init two ledger autocomplete
    //initLedgerTAutocomplete(1);
    //initLedgerTAutocomplete(2);
    initPriceInput();
    initAmountType(1);
    initAmountType(2);
    accountTypeValid();
    initSetAmountType();
    //init transaction date
    // $("#tr_date").inputmask("d/m/y", {placeholder: "dd/mm/yyyy", "oncomplete": function() {
    //         $("#Text1").focus();
    //     }});
    //init voucher date
    $("#voucher_date").inputmask("d/m/y", {placeholder: "dd/mm/yyyy", "oncomplete": function() {

        }});

});
//transaction date input
// $('#tr_date').bind('keyup', 'keydown', function(e) {
//     $.post(full_path + "api-get-date-by-finance-year", {date: e.target.value}, function(data) {
//         if (data.res) {
//             $("#tr_date").val(data.date);
//         }
//     }, "json");
// })
//voucher date input
$('#voucher_date').bind('keyup', 'keydown', function(e) {
    $.post(full_path + "api-get-date-by-finance-year", {date: e.target.value}, function(data) {
        if (data.res) {
            $("#voucher_date").val(data.date);
        }
    }, "json");
})
//recurring
$('.formSubmitAll').delegate('.recurring', 'focusin', function() {
    var availableType = [
        "No",
        "Dealy",
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
//go to next feild 

$("#tran-update-form").on('keypress', 'input,select', function(e) {
    if (e.which == 13) {
        e.preventDefault();
        var self = $(this);
        if ($(this).hasClass("tr_cr_amount") || $(this).hasClass("tr_dr_amount")) {
            if ($(this).val() != '') {
                var ledger_id = $(this).closest('tr').find('.tr_ledger_id').val();
                var total_amount = $(this).val();

                var tran_ledger_type = $(this).closest('tr').find('.tr_type').val();
                if (ledger_id != '' && !isNaN(ledger_id)) {
                    var data = getLedgerDetails(ledger_id);
                    if (data.res == 'success') {
                        if (data.ledger_details.tracking_status == 1) {
                            $('#popupModal').find('.modal-dialog').removeClass('modal-lg');
                            $('#popupModal').find('.modal-dialog').addClass('modal-sm');
                            $('#popupModal').find('.modal-dialog').removeAttr("style");
                            makeTrackingModalHtml(ledger_id, tran_ledger_type);
                            $("#tr_tracking_ledger_name").html(data.ledger_details.ladger_name);
                            setTimeout(function() {
                                console.log(total_amount)
                                $("#tr_tracking_total").val(total_amount);
                                $("#tr_tracking_total").attr('value', total_amount);
                                $('#popupModal').find('input[type=text],select').filter(':visible:first').focus();
                            }, 600);
                            $("#popupModal").modal("show");
                        } else if (data.is_bank_group == 'yes') {
                            $('#popupModal').find('.modal-dialog').removeClass('modal-sm');
                            $('#popupModal').find('.modal-dialog').addClass('modal-lg');
                            $('#popupModal').find('.modal-dialog').css({width: '1100px'});
                            $("#tr_tracking_ledger_name").html(data.ledger_details.ladger_name);
                            makeBankingModalHtml(ledger_id, tran_ledger_type);
                            setTimeout(function() {
                                $("#tr_banking_amount_total").val(total_amount);
                                $("#tr_banking_amount_total").attr('value', total_amount);
                                $('#popupModal').find('input[type=text],select').filter(':visible:first').focus();
                            }, 600);
                            $("#popupModal").modal("show");
                        } else if (data.ledger_details.bill_details_status == 1 && data.is_detors_creditors_group == 'yes'  && parseInt($('input[name="advance_entry"]:checked').val())==0 && (transaction_type_id != 5 || transaction_type_id != 6 || transaction_type_id != 4 || parent_id != 5 || parent_id != 6 || parent_id != 4)) {
                            $('#popupModal').find('.modal-dialog').removeClass('modal-sm');
                            $('#popupModal').find('.modal-dialog').addClass('modal-lg');
                            $('#popupModal').find('.modal-dialog').removeAttr("style");
                            makeBillingModalHtml(ledger_id, tran_ledger_type);
                            $("#tr_tracking_ledger_name").html(data.ledger_details.ladger_name);
                            setTimeout(function() {
                                $("#tr_billing_total").val(total_amount);
                                $('#popupModal').find('input[type=text],select').filter(':visible:first').focus();
                            }, 500);
                            $("#popupModal").modal("show");
                        }else if (data.is_detors_creditors_group == 'yes' && parseInt($('input[name="advance_entry"]:checked').val()) == 1 && (transaction_type_id == 1 || parent_id==1)) {
                            $('input[value="'+ledger_id+'"][class="tr_ledger_id"]').closest('tr').find('.tr_type').attr("ledger-type","debitors")            
                            $('.tax_or_advance').val(1);
                            $('#serviceModal').addClass('advance-entry');
                            $('#serviceModal').find('.modal-title').html('Advance Entry');
                            $("#serviceModal").modal('show');
                                    } else {
                            var element = $(':focusable');
                            var index = element.index(document.activeElement) + 1;
                            if (index >= element.length)
                                index = 0;
                            element.eq(index).focus();
                        }
                    } else {
                        var element = $(':focusable');
                        var index = element.index(document.activeElement) + 1;
                        if (index >= element.length)
                            index = 0;
                        element.eq(index).focus();
                    }
                } else {
                    Command: toastr["error"]('Select Ledger first.');
                }
            } else {
                Command: toastr["error"]('Please enter amount.');
            }
        } else {
            var element = $(':focusable');
            var index = element.index(document.activeElement) + 1;
            if (index >= element.length)
                index = 0;
            element.eq(index).focus();
        }
    }
});


//transaction row html
function addTransactionRow(count, adding_row_type, adjust_balance) {
    var html = '';
    html += '<tr class="tr_row_ledger" id="' + count + '">';
    html += '<td>';
    html += '<input type="text" id="tr_ledger_' + count + '" class="form-control tr_ledger" name="tr_ledger[]" placeholder="Select Ledger">';
    html += '<input type="hidden" class="tr_ledger_id" name="tr_ledger_id[]">';
    html += '</td>';
    html += '<td><input type="text" class="form-control tr_type tr_type_' + count + '" name="tr_type[]" placeholder="' + adding_row_type + '" value="' + adding_row_type + '" autocomplete="off"></td>';
    if (adding_row_type == 'Dr') {
        html += '<td><input type="text" class="form-control text-right tr_dr_amount price" value="' + adjust_balance + '" name="amount[]" autocomplete="off"></td>';
        html += '<td><input type="text" class="form-control text-right tr_cr_amount price" disabled name="amount[]" autocomplete="off"></td>';
    } else if (adding_row_type == 'Cr') {
        html += '<td><input type="text" class="form-control text-right tr_dr_amount price" disabled name="amount[]" autocomplete="off"></td>';
        html += '<td><input type="text" class="form-control text-right tr_cr_amount price" value="' + adjust_balance + '" name="amount[]" autocomplete="off"></td>';
    }
    html += '</tr>';
    return html;
}
//init TAutocomplete for ajax
function initLedgerTAutocomplete(count) {
    $("#tr_ledger_" + count).closest('tr').find('.tr_type').focus();
    var text = $("#tr_ledger_" + count).tautocomplete({
        width: "626px",
        columns: ['Id', 'Ledger', 'Code', 'Type', 'Closing Balance'],
        placeholder: 'Select Ledger',
        delay: 0,
        hide: ['id'],
        norecord: "No Ledger Found",
        ajax: {
            url: full_path + "transaction/entries/getLedgerByVoucher?voucher=",
            data: {voucher: 1},
            type: "GET",
            dataType: "json",
            data: function() {
                return [{test: text.searchdata()}];
            },
                    success: function(data) {
                        var filterData = [];
                        var searchData = eval("/" + text.searchdata() + "/gi");
                        $.each(data, function(i, v) {
                            if (v.ledger.search(new RegExp(searchData)) != -1) {
                                filterData.push(v);
                            }
                        });
                        return filterData;
                    }
        },
        onchange: function() {
            var ledger = text.all();
            console.log(text);
            console.log(text.all());
            setTimeout(function() {
                if (ledger.Type == 'Cr') {
                    $("#tr_ledger_" + count).closest('tr').find('.tr_dr_amount').attr("disabled", 'disabled');
                    $("#tr_ledger_" + count).closest('tr').find('.tr_cr_amount').removeAttr("disabled");
                    $("#tr_ledger_" + count).closest('tr').find('.tr_dr_amount').val("");
                } else if (ledger.Type == 'Dr') {
                    $("#tr_ledger_" + count).closest('tr').find('.tr_cr_amount').attr("disabled", 'disabled');
                    $("#tr_ledger_" + count).closest('tr').find('.tr_dr_amount').removeAttr("disabled");
                    $("#tr_ledger_" + count).closest('tr').find('.tr_cr_amount').val("");

                }
                $("#tr_ledger_" + count).closest('tr').find('.tr_type').val(ledger.Type);
                $("#tr_ledger_" + count).closest('tr').find('.tr_type').focus();
            }, 10);
            $("#tr_ledger_" + count).closest('tr').find('.tr_ledger_id').val(text.id());
            $("#ta-id").html(text.id());
        }
    });
}

//init transaction ledger

$(function() {
    $('#tran-update-form').delegate('.tr_ledger', 'focusin', function() {

        $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/entries/getTransactionLedgerDetails',
                    data: "ledger=" + request.term + '&trans_type=' + transaction_type_id + '&ajax=1',
                    dataType: "json",
                    success: function(data) {
                        response(data);
                    },
                    error: function(request, error) {
                        console.log("connection error");
                    }
                });
            },
            minLength: 0,
            select: function(e, ui) {
                var self = $(this);
                e.preventDefault(); // <--- Prevent the value from being inserted.
                $(this).val(ui.item.label); // display the selected text
                $(this).next(".tr_ledger_id").val(ui.item.value); // save selected id to hidden input

                $.ajax({
                    type: "POST",
                    url: full_path + 'transaction/entries/getLedgerType',
                    data: {ledger_id: ui.item.value},
                    dataType: "json",
                    success: function(data) {
                        setTimeout(function() {
                            if (data.type == 'Cr') {
                                $(self).closest('tr').find('.tr_dr_amount').attr("disabled", 'disabled');
                                $(self).closest('tr').find('.tr_cr_amount').removeAttr("disabled");
                                $(self).closest('tr').find('.tr_dr_amount').val("");
                            } else if (data.type == 'Dr') {
                                $(self).closest('tr').find('.tr_cr_amount').attr("disabled", 'disabled');
                                $(self).closest('tr').find('.tr_dr_amount').removeAttr("disabled");
                                $(self).closest('tr').find('.tr_cr_amount').val("");

                            }
                        }, 10);
                        $(self).closest('tr').find('.tr_type').val(data.Type);
                        $(self).closest('tr').find('.tr_type').focus();

                    }
                });
            }
        });
    });
})

//init price feild
function initPriceInput() {
    $(".price").on("keyup", function() {
        var valid = /^\d{0,12}(\.\d{0,2})?$/.test(this.value),
                val = this.value;

        if (!valid) {
            console.log("Invalid input!");
            this.value = val.substring(0, val.length - 1);
        }
    });
}
//append transaction row after current row
function appendRow(no, adding_row_type, adjust_balance) {
    var count = parseInt(no) + 1;
    var html = addTransactionRow(count, adding_row_type, adjust_balance);
    $("#" + no).after(html);
    // initLedgerTAutocomplete(count);
    initPriceInput();
    initAmountType(count);
    accountTypeValid();
    initSetAmountType();
}

//calculate Dr sum
function calculateDrSum() {
    // var _dr = 0.00;
    // var dr_arr = $(".tr_dr_amount");
    // $.each(dr_arr, function(index, element) {
    //     if ($(element).val() != '') {
    //         _dr = _dr + parseFloat($(element).val());
    //     }
    // });
    // return _dr;

    var total_dr = 0.00;

    $(".tr_dr_amount").each(function(index, element){
        var isDisabled = $(element).is(':disabled');
        if (!isDisabled) {
            total_dr += +$(element).val();
        }        
    });
    return total_dr.toFixed(2);
}

//calculate Cr sum
function calculateCrSum() {
    // var _cr = 0;
    // var cr_arr = $(".tr_cr_amount");
    // $.each(cr_arr, function(index, element) {
    //     if ($(element).val() != '') {
    //         _cr = _cr + parseFloat($(element).val());
    //     }
    // });
    // return _cr;

    var total_cr = 0.00;
    $(".tr_cr_amount").each(function(index, element){
        var isDisabled = $(element).is(':disabled');
        if (!isDisabled) {
            total_cr += +$(element).val();
        }
    });
    return total_cr.toFixed(2);
}

//calculate sum between Dr and Cr
function differenceDrCr() {
    // $('.tr_total_dr').html(total_dr.toFixed(2));
    // $('.tr_total_cr').html(total_cr.toFixed(2));
    var dr = calculateDrSum();
    var cr = calculateCrSum();
    $('.tr_total_dr').html(dr);
    $('.tr_total_cr').html(cr);
    $("input[name=tr_total_dr]").val(parseFloat(dr).toFixed(2));
    $("input[name=tr_total_cr]").val(parseFloat(cr).toFixed(2));
    return parseFloat(dr- cr).toFixed(2);
    // return parseFloat(dr - cr);

    // $('.tr_total_dr').html(dr.toFixed(2));
    // $('.tr_total_cr').html(cr.toFixed(2));
    // $("input[name=tr_total_dr]").val(dr.toFixed(2));
    // $("input[name=tr_total_cr]").val(cr.toFixed(2));
    // return (dr.toFixed(2)-cr.toFixed(2));
    
}

// calculate dr and cr on load to calculate total
$(document).ready(function() {    
    differenceDrCr();
});

function calculateAddRowType(dr_cr_diff) {
    if ( dr_cr_diff > 0 ) {
        var add_row_type = 'Cr';
    } else if ( dr_cr_diff < 0) {
        var add_row_type = 'Dr';
    }
    return add_row_type;
}

$('body').delegate('.tr_cr_amount, .tr_dr_amount', 'blur', function(event) {
    event.stopPropagation();
    var num = parseInt($(this).closest('tr').attr('id'));
    if (num != 1) {
        var last_dr_amount = $('tr[class="tr_row_ledger"]:last .tr_dr_amount').val();
        var last_cr_amount = $('tr[class="tr_row_ledger"]:last .tr_cr_amount').val();
        var dr_cr_diff = differenceDrCr();
        var adding_row_type = calculateAddRowType(dr_cr_diff);
        var adjust_balance = Math.abs(dr_cr_diff);
        // var adjust_balance = dr_cr_diff;
        var cr_amount = parseFloat($("#" + num).closest('tr').find('.tr_cr_amount').val());
        var dr_amount = parseFloat($("#" + num).closest('tr').find('.tr_dr_amount').val());
        if (isNaN(cr_amount)) {
            cr_amount = 0;
        }
        if (isNaN(dr_amount)) {
            dr_amount = 0;
        }
        var element_arr = $("#" + num).closest('tr').prevAll('tr');
        $.each(element_arr, function(index, value) {
            var tr_cr_amount = isNaN(parseFloat($(value).find('.tr_cr_amount').val())) ? 0 : parseFloat($(value).find('.tr_cr_amount').val())
            var tr_dr_amount = isNaN(parseFloat($(value).find('.tr_dr_amount').val())) ? 0 : parseFloat($(value).find('.tr_dr_amount').val())
            cr_amount = cr_amount + tr_cr_amount;
            dr_amount = dr_amount + tr_dr_amount;
        });
        if (parseFloat(dr_cr_diff) !=0 && (parseInt(last_dr_amount) || parseInt(last_cr_amount))) {
            var last_tr_id = $('tr[class="tr_row_ledger"]:last').attr('id');
            appendRow(last_tr_id, adding_row_type, adjust_balance);
            setTimeout(function() {
                var dr = calculateDrSum();
                var cr = calculateCrSum();
                $('.tr_total_dr').html(dr);
                $('.tr_total_cr').html(cr);
                var last = parseInt(num) + 1;
                if (!$('#popupModal').hasClass('in')) {
                    $("#" + last).find('input[type=text],select').filter(':visible:first').focus();
                }
            }, 500)
        } else {
            $("#tran-update-btn").show();
            if (parseFloat(dr_amount).toFixed(2) == parseFloat(cr_amount).toFixed(2)) {
                if (parseInt($('.expences_ledger_id').val())) {
                    $("#serviceModal").modal('show');
                }
                $("#" + num).nextAll('.tr_row_ledger').remove();
            } else {
                var last = num + 1;
                $("#" + last).find('input[type=text],select').filter(':visible:first').focus();
            }
        }
    }
})

function initAmountType(count) {
    $('.tr_type_' + count).autoComplete({
        minChars: 0,
        source: function(term, suggest) {
            term = term.toLowerCase();
            var choices = [['Dr', 'Dr'], ['Cr', 'Cr']];
            var suggestions = [];
            for (i = 0; i < choices.length; i++)
                if (~(choices[i][0] + ' ' + choices[i][1]).toLowerCase().indexOf(term))
                    suggestions.push(choices[i]);
            suggest(suggestions);
        },
        renderItem: function(item, search) {
            search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
            return '<div class="autocomplete-suggestion" data-value="' + item[0] + '" data-type="' + item[1] + '" data-val="' + search + '">' + item[0].replace(re, "<b>$1</b>") + '</div>';
        },
        onSelect: function(e, term, item) {
            $('.tr_type_' + count).val(item.data('type'));
            if (item.data('type') == 'Cr') {
                $(".tr_type_" + count).closest('tr').find('.tr_dr_amount').attr("disabled", 'disabled');
                $(".tr_type_" + count).closest('tr').find('.tr_cr_amount').removeAttr("disabled");
                $(".tr_type_" + count).closest('tr').find('.tr_dr_amount').val("");
            } else if (item.data('type') == 'Dr') {
                $(".tr_type_" + count).closest('tr').find('.tr_cr_amount').attr("disabled", 'disabled');
                $(".tr_type_" + count).closest('tr').find('.tr_dr_amount').removeAttr("disabled");
                $(".tr_type_" + count).closest('tr').find('.tr_cr_amount').val("");

            }
        }
    });
}
//on enter change 
function initSetAmountType() {
    $(".tr_type").on("keypress", function(e) {
        var val = $(this).val();
        if (e.keyCode == 13) {
            if (val == 'Cr') {
                $(this).closest('tr').find('.tr_dr_amount').attr("disabled", 'disabled');
                $(this).closest('tr').find('.tr_cr_amount').removeAttr("disabled");
                $(this).closest('tr').find('.tr_dr_amount').val("");
            } else if (val == 'Dr') {
                $(this).closest('tr').find('.tr_cr_amount').attr("disabled", 'disabled');
                $(this).closest('tr').find('.tr_dr_amount').removeAttr("disabled");
                $(this).closest('tr').find('.tr_cr_amount').val("");

            }
        }
    });
}
function accountTypeValid() {
    $(".tr_type").on("keyup", function(e) {
        e.preventDefault();
        if (e.keyCode != 8) {
            var val = this.value;
            switch (val)
            {
                case 'd':
                    this.value = 'Dr';
                    break;
                case 'c':
                    this.value = 'Cr';
                    break;
                case 'D':
                    this.value = 'Dr';
                    break;
                case 'C':
                    this.value = 'Cr';
                    break;
                case 'Dr':
                    this.value = 'Dr';
                    break;
                case 'Cr':
                    this.value = 'Cr';
                    break;
                default:
                    this.value = val.substring(0, val.length - 1);
                    break;
            }

        }
    });
}


function getLedgerDetails(ledger_id) {
    var res = null;
    $.ajax({
        'async': false,
        'type': "POST",
        'global': false,
        'dataType': 'json',
        'url': full_path + "api-get-ledger-details",
        'data': {'ledger_id': ledger_id},
        'success': function(data) {
            res = data;
        }
    });
    return res;
}

function makeTrackingModalHtml(ledger_id, ledger_type) {
    var entry_id = $("#entry_id").val();
    $.post(full_path + "api-get-temp-tracking-data", {ledger_id: ledger_id, entry_id: entry_id}, function(data) {
        var html = '';
        html += '<input type="hidden" value="' + ledger_type + '" id="popup_tr_ledger_type" name="popup_tr_ledger_type">';
        html += '<input type="hidden" value="' + ledger_id + '" id="popup_tr_ledger_id" name="popup_tr_ledger_id">';
        html += ' <input type="hidden" value="' + entry_id + '" id="popup_tr_entry_id" name="popup_tr_entry_id">';
        html += '<div class="form-group">';
        html += '<div class="row">';
        html += '<div class="col-xs-7"><label>Tracking Name</label></div>';
        html += '<div class="col-xs-5"><label>Amount</label></div>';
        html += '</div>';
        html += '</div>';
        html += '<div id="tracking_container">';
        console.log(ledger_id)
        if (data.res == 'success') {
            console.log('hdff')
            $.each(data.temp_tracking_details, function(index, value) {
                var num = index + 1;
                html += '<div class="form-group tracking-row" trac-data-row-id="' + num + '">';
                html += '<div class="row">';
                html += '<div class="col-xs-7">';
                html += '<input type="text" name="tr_tracking_name[]" class="form-control tr_tracking_name" value="' + value.tracking_name + '">';
                html += '<input type="hidden" class="tr_tracking_id" name="tr_tracking_id[]" value="' + value.tracking_id + '">';
                html += '</div>';
                html += '<div class="col-xs-5">';
                html += '<input type="text" name="tr_tracking_amount[]"  class="form-control tr_tracking_amount price" value="' + value.tracking_amount + '">';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            });
        } else {
            html += '<div class="form-group tracking-row" trac-data-row-id="1">';
            html += '<div class="row">';
            html += '<div class="col-xs-7">';
            html += '<input type="text" name="tr_tracking_name[]" class="form-control tr_tracking_name">';
            html += '<input type="hidden" class="tr_tracking_id" name="tr_tracking_id[]">';
            html += '</div>';
            html += '<div class="col-xs-5">';
            html += '<input type="text" name="tr_tracking_amount[]"  class="form-control tr_tracking_amount price">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        }
        html += '</div>';
        var footer_html = '<div class="row">';
        footer_html += '<div class="col-xs-7 text-right"><label>Total</label></div>';
        footer_html += '<div class="col-xs-5"><input type="text" class="form-control" id="tr_tracking_total" readonly="readonly"></div>';
        footer_html += '</div>';
        $('#popup_footer').html(footer_html);
        $("#popup_form").html(html);
    }, "json");
}

function makeBillingModalHtml(ledger_id, tran_ledger_type) {
    var entry_id = $("#entry_id").val();
    if (tran_ledger_type == 'Dr') {
        var bill_type = 'Cr';
    } else if (tran_ledger_type == 'Cr') {
        var bill_type = 'Dr';
    }
    $.post(full_path + "api-get-temp-billing-data", {ledger_id: ledger_id, entry_id: entry_id}, function(data) {
        var html = '';
        html += '<input type="hidden" value="' + tran_ledger_type + '" id="popup_tr_ledger_type" name="popup_tr_ledger_type">';
        html += '<input type="hidden" value="' + ledger_id + '" id="popup_tr_ledger_id" name="popup_tr_ledger_id">';
        html += ' <input type="hidden" value="' + entry_id + '" id="popup_tr_entry_id" name="popup_tr_entry_id">';
        html += '<div class="form-group">'
        html += '<div class="row">'
        html += '<div class="col-xs-2"><label>Type of Ref.</label></div>';
        html += '<div class="col-xs-3"><label>Name </label></div>';
        html += '<div class="col-xs-2"><label>Credit Days</label></div>';
        html += '<div class="col-xs-2"><label>Credit Date</label></div>';
        html += '<div class="col-xs-2"><label>Amount</label></div>';
        html += '<div class="col-xs-1"><label>Dr/Cr</label></div>';
        html += '</div>';
        html += '</div>';
        html += '<div id="bill_body">';
        console.log(data)
        if (data.res == 'success') {
            $.each(data.temp_billing_details, function(index, value) {
                var num = index + 1;
                html += '<div class="form-group billing-row" bill-data-row-id="' + num + '">';
                html += '<div class="row">';
                html += ' <div class="col-xs-2"><input type="text" class="form-control tr_ref_bill_type" name="ref_bill_type[]" readonly="readonly" value="Against Reference" ></div>';
                html += '<div class="col-xs-3"><input type="text" class="form-control bill_name" name="bill_name[]" id="" value="' + value.bill_name + '"></div>';
                html += '<div class="col-xs-2"><input type="number" class="form-control bill_credit_day" name="bill_credit_day[]" id="" value="' + value.credit_days + '" readonly="readonly"></div>';
                html += '<div class="col-xs-2"><input type="text" class="form-control bill_credit_date" name="bill_credit_date[]" id="bill_credit_date" value="' + value.credit_date + '" readonly="readonly" ></div>';
                html += '<div class="col-xs-2"><input type="text" class="form-control bill_amount " name="bill_amount[]" id="" value="' + value.bill_amount + '"></div>';
                html += '<div class="col-xs-1"><input type="text" class="form-control close_pop_bill  account_type_modal" name="account_type_modal[]" id="" value="' + value.dr_cr + '" readonly="readonly"></div>';
                html += ' </div>';
                html += '</div>';
            });
        } else {
            html += '<div class="form-group billing-row" trac-data-row-id="1">';
            html += '<div class="row">';
            html += ' <div class="col-xs-2"><input type="text" class="form-control tr_ref_bill_type" name="ref_bill_type[]" readonly="readonly" value="Against Reference"></div>';
            html += '<div class="col-xs-3"><input type="text" class="form-control bill_name " name="bill_name[]" id=""></div>';
            html += '<div class="col-xs-2"><input type="number" class="form-control bill_credit_day" name="bill_credit_day[]" id="" readonly="readonly"></div>';
            html += '<div class="col-xs-2"><input type="text" class="form-control bill_credit_date" name="bill_credit_date[]" id="bill_credit_date" readonly="readonly" ></div>';
            html += '<div class="col-xs-2"><input type="text" class="form-control bill_amount " id="" name="bill_amount[]"></div>';
            html += '<div class="col-xs-1"><input type="text" class="form-control close_pop_bill  account_type_modal" name="account_type_modal[]" id="" value="' + bill_type + '" readonly="readonly"></div>';
            html += ' </div>';
            html += '</div>';
        }
        html += '</div>';
        var footer_html = '<div class="row">';
        footer_html += '<div class="col-xs-8 text-right"><label>Total</label></div>';
        footer_html += '<div class="col-xs-3"><input type="text" class="form-control" id="tr_billing_total"  readonly="readonly"></div>';
        footer_html += '<div class="col-xs-1 "><input type="text" class="form-control" id="cr_dr_cal" value="' + bill_type + '"  readonly="readonly"></div>';
        footer_html += '</div>';
        $('#popup_footer').html(footer_html);
        $("#popup_form").html(html);
    }, "json");
}

//make banking modal html
function makeBankingModalHtml(ledger_id, tran_ledger_type) {
    var entry_id = $("#entry_id").val();

    $.post(full_path + "api-get-temp-banking-data", {ledger_id: ledger_id, entry_id: entry_id}, function(data) {
        var html = '';
        html += '<input type="hidden" value="' + tran_ledger_type + '" id="popup_tr_ledger_type" name="popup_tr_ledger_type">';
        html += '<input type="hidden" value="' + ledger_id + '" id="popup_tr_ledger_id" name="popup_tr_ledger_id">';
        html += ' <input type="hidden" value="' + entry_id + '" id="popup_tr_entry_id" name="popup_tr_entry_id">';

        html += '<div id="banking_container">';
        if (data.res == 'success') {
            $.each(data.temp_banking_details, function(index, value) {
                var num = index + 1;
                var date = new Date(value.instrument_date),
                        yr = date.getFullYear(),
                        month = date.getMonth() < 10 ? '0' + date.getMonth() : date.getMonth(),
                        day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
                        newDate = day + '/' + month + '/' + yr;
                html += '<div class="row tr_banking_row" id="tr_banking_row' + num + '" bank-data-row-id="' + num + '">';
                html += '<div class="col-md-2" style="padding: 0 2px;">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control tr_banking_transaction_type" placeholder="Transaction Type" name="tr_banking_transaction_type[]" value="' + value.name + '">';
                html += '<input type="hidden" class="tr_transaction_id_modal" name="tr_transaction_id_modal[]" value="' + value.transaction_type + '">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-1 min-padding" style="padding: 0 2px;">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control tr_banking_instrument_no" placeholder="Ins No" name="instrument_no[]" value="' + value.instrument_no + '">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-1 min-padding" style="padding: 0 2px;">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control tr_banking_instrument_date" placeholder="Date" name="tr_banking_instrument_date[]" value="' + newDate + '">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-2" style="padding: 0 2px;">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control tr_banking_bank_name" placeholder="Bank Name" name="tr_banking_bank_name[]" value="' + value.bank_name + '">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-2" style="padding: 0 2px;">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control tr_banking_branch_name" placeholder="Branch Name" name="tr_banking_branch_name[]" value="' + value.branch_name + '">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-2" style="padding: 0 2px;">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control tr_banking_ifsc_code" placeholder="IFSC Code" name="tr_banking_ifsc_code[]" value="' + value.ifsc_code + '">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-2" style="padding: 0 2px;">';
                html += '<div class="form-group">';
                html += '<input type="text" class="form-control tr_banking_bank_amount text-right" placeholder="Amount" name="tr_banking_bank_amount[]" value="' + value.bank_amount + '">';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            });
        } else {
            html += '<div class="row tr_banking_row" id="tr_banking_row_1" bank-data-row-id="1">';
            html += '<div class="col-md-2" style="padding: 0 2px;">';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control tr_banking_transaction_type" placeholder="Transaction Type" name="tr_banking_transaction_type[]">';
            html += '<input type="hidden" class="tr_transaction_id_modal" name="tr_transaction_id_modal[]">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-1 min-padding" style="padding: 0 2px;">';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control tr_banking_instrument_no" placeholder="Ins No" name="instrument_no[]">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-1 min-padding" style="padding: 0 2px;">';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control tr_banking_instrument_date" placeholder="Date" name="tr_banking_instrument_date[]">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-2" style="padding: 0 2px;">';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control tr_banking_bank_name" placeholder="Bank Name" name="tr_banking_bank_name[]">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-2" style="padding: 0 2px;">';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control tr_banking_branch_name" placeholder="Branch Name" name="tr_banking_branch_name[]">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-2" style="padding: 0 2px;">';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control tr_banking_ifsc_code" placeholder="IFSC Code" name="tr_banking_ifsc_code[]">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-2" style="padding: 0 2px;">';
            html += '<div class="form-group">';
            html += '<input type="text" class="form-control tr_banking_bank_amount text-right" placeholder="Amount" name="tr_banking_bank_amount[]">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        }
        html += '</div>';
        var footer_html = '<div class="row">';
        footer_html += '<div class="col-xs-10 text-right"><label>Total</label></div>';
        footer_html += '<div class="col-xs-2" style="padding: 0 2px;">';
        footer_html += '<input type="text" class="form-control text-right" id="tr_banking_amount_total" readonly="readonly">';
        footer_html += '</div>';
        footer_html += '</div>';
        $('#popup_footer').html(footer_html);
        $("#popup_form").html(html);
    }, "json");
}

//enter operation on tracking modal
$("#popupModal").on('keypress', 'input,select', function(e) {
    if (e.which == 13) {
        e.preventDefault();
        var self = $(this);
        if ($(this).hasClass("tr_tracking_amount")) {
            var cal_track_amount = calculateTotalTrackingAmount();
            var tracking_total = $("#tr_tracking_total").val();

            if ($(this).val() != '') {
                if (cal_track_amount != tracking_total) {
                    if (parseFloat(cal_track_amount) < parseFloat(tracking_total)) {
                        var num = $(".tracking-row").last().attr('trac-data-row-id');
                        var tracking_adjust_amount = parseFloat(tracking_total) - parseFloat(cal_track_amount);
                        generateTrackingRow(tracking_adjust_amount, num);
                    } else {
                        $(this).closest('.form-group').nextAll('.form-group').remove();
                        Command: toastr["error"]('Tracking amount can not be larger then total tracking amount.');
                    }
                } else if (parseFloat(cal_track_amount) == parseFloat(tracking_total)) {
                    if ($(this).is('.tr_tracking_amount:last')) {
                        //tracking form submit
                        var data = $("#popup_form").serialize();
                        var ledger_id = $("#popup_tr_ledger_id").val();
                        var tran_ledger_type = $("#popup_tr_ledger_type").val();
                        var total_amount = $("#tr_tracking_total").val();
                        console.log('POST');
                        $.ajax({
                            url: full_path + "api-save-temp-tracking-data",
                            data: data,
                            dataType: 'json',
                            type: 'POST',
                            success: function(data) {
                                if (data.res == 'success') {
                                    if (data.ledger_details.bill_details_status == 1 && data.is_detors_creditors_group == 'yes' && parseInt($('input[name="advance_entry"]:checked').val()) == 0 && (transaction_type_id != 5 || transaction_type_id != 6 || transaction_type_id != 4 || parent_id != 5 || parent_id != 6 || parent_id != 4)) {
                                        $('#popupModal').find('.modal-dialog').removeAttr("style");
                                        $('#popupModal').find('.modal-dialog').removeClass('modal-sm');
                                        $('#popupModal').find('.modal-dialog').addClass('modal-lg');
                                        makeBillingModalHtml(ledger_id, tran_ledger_type);
                                        $("#tr_tracking_ledger_name").html(data.ledger_details.ladger_name);
                                        setTimeout(function() {
                                            $("#tr_billing_total").val(total_amount);
                                            $('#popupModal').find('input[type=text],select').filter(':visible:first').focus();
                                        }, 500);
                                        $("#popupModal").modal("show");
                                    } else if (data.is_bank_group == 'yes') {
                                        $('#popupModal').find('.modal-dialog').removeClass('modal-sm');
                                        $('#popupModal').find('.modal-dialog').addClass('modal-lg');
                                        $('#popupModal').find('.modal-dialog').css({width: '1100px'});
                                        $("#tr_tracking_ledger_name").html(data.ledger_details.ladger_name);
                                        makeBankingModalHtml(ledger_id, tran_ledger_type);
                                        setTimeout(function() {
                                            $("#tr_banking_amount_total").val(total_amount);
                                            $('#popupModal').find('input[type=text],select').filter(':visible:first').focus();
                                        }, 500);
                                        $("#popupModal").modal("show");
                                    } else if (data.is_detors_creditors_group == 'yes' && parseInt($('input[name="advance_entry"]:checked').val()) == 1 && (transaction_type_id == 1 || transaction_type_id == 2 || parent_id == 1 || parent_id == 2)) {
                                       $('input[value="'+ledger_id+'"][class="tr_ledger_id"]').closest('tr').find('.tr_type').attr("ledger-type","debitors")
                                        $('.tax_or_advance').val(1);
                                        $('#serviceModal').addClass('advance-entry');
                                        $('#serviceModal').find('.modal-title').html('Advance Entry');
                                        $("#serviceModal").modal('show');
                                    } else {
                                        $("#popupModal").modal("hide");
                                        $("#tran-update-form").find('input[value="' + ledger_id + '"]').closest("tr").next().find('input[type=text],select').filter(':visible:first').focus();
                                    }
                                } else if (data.res == 'validation_error') {
                                    $.each(data.message, function(index, value) {
                                        Command: toastr["error"](value);
                                    });
                                } else {
                                    Command: toastr["error"](data.message);
                                }
                            }
                        });
                    } else {
                        var element = $(':focusable');
                        var index = element.index(document.activeElement) + 1;
                        if (index >= element.length)
                            index = 0;
                        element.eq(index).focus();
                    }
                    //end tracking form submit
                } else {
                    $(this).closest('.form-group').nextAll('.form-group').remove();
                    var element = $(':focusable');
                    var index = element.index(document.activeElement) + 1;
                    if (index >= element.length)
                        index = 0;
                    element.eq(index).focus();
                }
            } else {
                Command: toastr["error"]('Please Enter amount.');
            }
        } else if ($(this).hasClass("close_pop_bill")) {
            var cal_bill_amount = calculateTotalBillingAmount();
            var bill_total = $("#tr_billing_total").val();
            var tr_ledger_type = $("#popup_tr_ledger_type").val();
            if ($(this).closest('.form-group').find('.bill_amount').val() != '') {
                //bill amount check
                if (parseFloat(cal_bill_amount) != parseFloat(bill_total)) {
                    if (parseFloat(cal_bill_amount) < parseFloat(bill_total)) {
                        var num = $(".billing-row").last().attr('bill-data-row-id');
                        var billing_adjust_amount = parseFloat(bill_total) - parseFloat(cal_bill_amount);
                        generateBillingRow(billing_adjust_amount, num, tr_ledger_type);
                    } else {
                        $(this).closest('.form-group').nextAll('.form-group').remove();
                        Command: toastr["error"]('Billing amount can not be larger then total billing amount.');
                    }
                } else if ((parseFloat(cal_bill_amount) == parseFloat(bill_total))) {
                    if ($(this).is('.close_pop_bill:last')) {
                        //billing form submit
                        var data = $("#popup_form").serialize();
                        var ledger_id = $("#popup_tr_ledger_id").val();
                        var tran_ledger_type = $("#popup_tr_ledger_type").val();
                        var total_amount = $(this).val();
                        $.ajax({
                            url: full_path + "api-save-temp-billing-data",
                            data: data,
                            dataType: 'json',
                            type: 'POST',
                            success: function(data) {
                                if (data.res == 'success') {
                                    if (data.is_detors_creditors_group == 'yes' && parseInt($('input[name="advance_entry"]:checked').val()) == 1 && (transaction_type_id == 1 || transaction_type_id == 2 || parent_id == 1 || parent_id == 2)) {
                                       $('input[value="'+ledger_id+'"][class="tr_ledger_id"]').closest('tr').find('.tr_type').attr("ledger-type","debitors")
                                        $("#popupModal").modal("hide");
                                        $('.tax_or_advance').val(1);
                                        $('#serviceModal').addClass('advance-entry');
                                        $('#serviceModal').find('.modal-title').html('Advance Entry');
                                        $("#serviceModal").modal('show');
                                    } else {
                                        $("#popupModal").modal("hide");
                                        $("#tran-update-form").find('input[value="' + ledger_id + '"]').closest("tr").next().find('input[type=text],select').filter(':visible:first').focus();
                                    }
                                } else if (data.res == 'validation_error') {
                                    $.each(data.message, function(index, value) {
                                        Command: toastr["error"](value);
                                    });
                                } else {
                                    Command: toastr["error"](data.message);
                                }
                            }
                        });
                        //end billing form submit
                    } else {
                        var element = $(':focusable');
                        var index = element.index(document.activeElement) + 1;
                        if (index >= element.length)
                            index = 0;
                        element.eq(index).focus();
                    }
                }
            } else {
                Command: toastr["error"]('Please Enter amount.');
            }
        } else if ($(this).hasClass("tr_banking_bank_amount")) {
            var cal_bank_amount = calculateTotalBankAmount();
            var bank_total = $("#tr_banking_amount_total").val();
            if ($(this).val() != '') {
                if (parseFloat(cal_bank_amount) != parseFloat(bank_total)) {
                    if (parseFloat(cal_bank_amount) < parseFloat(bank_total)) {
                        var num = $(".tr_banking_row").last().attr('bank-data-row-id');
                        var bank_adjust_amount = parseFloat(bank_total) - parseFloat(cal_bank_amount);
                        generateBankRow(bank_adjust_amount, num);
                    } else {
                        $(this).closest('.row').nextAll('.row').remove();
                        Command: toastr["error"]('Bank amount can not be larger then total Bank amount.');
                    }
                } else if (parseFloat(cal_bank_amount) == parseFloat(bank_total)) {
                    if ($(this).is('.tr_banking_bank_amount:last')) {
                        //bank form submit
                        var data = $("#popup_form").serialize();
                        var ledger_id = $("#popup_tr_ledger_id").val();
                        var tran_ledger_type = $("#popup_tr_ledger_type").val();
                        $.ajax({
                            url: full_path + "api-save-temp-bank-data",
                            data: data,
                            dataType: 'json',
                            type: 'POST',
                            success: function(data) {
                                if (data.res == 'success') {
                                    if (data.is_detors_creditors_group == 'yes' && parseInt($('input[name="advance_entry"]:checked').val()) == 1 && (transaction_type_id == 1 || transaction_type_id == 2 || parent_id == 1 || parent_id == 2)) {
                                        $('input[value="'+ledger_id+'"][class="tr_ledger_id"]').closest('tr').find('.tr_type').attr("ledger-type","debitors")
                                        $("#popupModal").modal("hide");
                                        $('.tax_or_advance').val(1);
                                        $('#serviceModal').addClass('advance-entry');
                                        $('#serviceModal').find('.modal-title').html('Advance Entry');
                                        $("#serviceModal").modal('show');
                                    }else{
                                    $("#popupModal").modal("hide");
                                    $("#tran-update-form").find('input[value="' + ledger_id + '"]').closest("tr").next().find('input[type=text],select').filter(':visible:first').focus();
                                }
                                } else if (data.res == 'validation_error') {
                                    $.each(data.message, function(index, value) {
                                        Command: toastr["error"](value);
                                    });
                                } else {
                                    Command: toastr["error"](data.message);
                                }
                            }
                        });
                        //end bank form submit  
                    } else {
                        var element = $(':focusable');
                        var index = element.index(document.activeElement) + 1;
                        if (index >= element.length)
                            index = 0;
                        element.eq(index).focus();
                    }
                }
            } else {
                Command: toastr["error"]('Please Enter amount.');
            }
        } else {
            var element = $(':focusable');
            var index = element.index(document.activeElement) + 1;
            if (index >= element.length)
                index = 0;
            element.eq(index).focus();
        }
    }
});

var trackingArray = [];
//init tracking name for modal
$('#popupModal').delegate('.tr_tracking_name', 'focusin', function() {

    $(this).autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: full_path + "api-get-tracking-name",
                data: "tracking=" + request.term + '&trackingArr=' + JSON.stringify(trackingArray) + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                    //console.log(data);
                },
                error: function(request, error) {

                    console.log(error);
                }
            });
        },
        minLength: 0,
        select: function(e, ui) {
            var self = $(this);
            e.preventDefault(); // <--- Prevent the value from being inserted.
            $(this).val(ui.item.label); // display the selected text
            $(this).next(".tr_tracking_id").val(ui.item.value); // save selected id to hidden input
            $(this).closest('.tracking-row').find('.tr_tracking_amount').focus();
            var found = jQuery.inArray(ui.item.value, trackingArray);
            if (found >= 0) {
                // Element was found, remove it.
                trackingArray.splice(ui.item.value, 1);
            } else {
                // Element was not found, add it.
                trackingArray.push(ui.item.value);
            }
        }
    }).focus(function() {
        $(this).autocomplete("search", "");
    });

});

$(function() {

    //price feild accept only float value
    $('body').delegate('.price', 'keyup', function() {
        var valid = /^\d{0,12}(\.\d{0,2})?$/.test(this.value),
                val = this.value;

        if (!valid) {
            console.log("Invalid input!");
            this.value = val.substring(0, val.length - 1);
        }
    });
});


//calculate total tracking amount
function calculateTotalTrackingAmount() {
    var _tracking_amount = 0;
    var tracking_arr = $(".tr_tracking_amount");
    $.each(tracking_arr, function(index, element) {
        if ($(element).val() != '') {
            _tracking_amount = _tracking_amount + parseFloat($(element).val());
        }
    });
    return _tracking_amount;
}

//calculate total billing amount
function calculateTotalBillingAmount() {
    var _billing_amount = 0;
    var bill_arr = $(".bill_amount");
    $.each(bill_arr, function(index, element) {
        if ($(element).val() != '') {
            _billing_amount = _billing_amount + parseFloat($(element).val());
        }
    });
    return _billing_amount;
}

//calculate total tracking amount
function calculateTotalBankAmount() {
    var _bank_amount = 0;
    var bank_arr = $(".tr_banking_bank_amount");
    $.each(bank_arr, function(index, element) {
        if ($(element).val() != '') {
            _bank_amount = _bank_amount + parseFloat($(element).val());
        }
    });
    return _bank_amount;
}

function generateTrackingRow(tracking_adjust_amount, num) {
    num = parseInt(num) + 1;
    var html = '<div class="form-group tracking-row" trac-data-row-id="' + num + '">';
    html += '<div class="row">';
    html += '<div class="col-xs-7">';
    html += '<input type="text" name="tr_tracking_name[]" class="form-control tr_tracking_name">';
    html += '<input type="hidden" class="tr_tracking_id" name="tr_tracking_id[]">';
    html += '</div>';
    html += '<div class="col-xs-5">';
    html += '<input type="text" name="tr_tracking_amount[]"  class="form-control tr_tracking_amount" value="' + tracking_adjust_amount + '">';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("#tracking_container").append(html);
    setTimeout(function() {
        $('#popupModal').find('.tr_tracking_name').filter(':visible:last').focus();
    }, 100);
}

//generate billing row html
function generateBillingRow(tracking_adjust_amount, num, tr_ledger_type) {
    if (tr_ledger_type == 'Dr') {
        tr_ledger_type = 'Cr';
    } else if (tr_ledger_type == 'Cr') {
        tr_ledger_type = 'Dr';
    }
    num = parseInt(num) + 1;
    var html = '<div class="form-group billing-row" bill-data-row-id="' + num + '">';
    html += '<div class="row">';
    html += ' <div class="col-xs-2"><input type="text" class="form-control tr_ref_bill_type" name="ref_bill_type[]" readonly="readonly" value="Against Reference"></div>';
    html += '<div class="col-xs-3"><input type="text" class="form-control bill_name " name="bill_name[]" id="" ></div>';
    html += '<div class="col-xs-2"><input type="number" class="form-control bill_credit_day" name="bill_credit_day[]" id="" readonly="readonly"></div>';
    html += '<div class="col-xs-2"><input type="text" class="form-control bill_credit_date" name="bill_credit_date[]" id="bill_credit_date"  readonly="readonly" ></div>';
    html += '<div class="col-xs-2"><input type="text" class="form-control bill_amount " id="" name="bill_amount[]" value="' + tracking_adjust_amount + '"></div>';
    html += '<div class="col-xs-1"><input type="text" class="form-control close_pop_bill  account_type_modal" name="account_type_modal[]" id="" readonly="readonly" value="' + tr_ledger_type + '"></div>';
    html += ' </div>';
    html += '</div>';
    $("#bill_body").append(html);
    setTimeout(function() {
        $('#popupModal').find('.ref_bill_type').filter(':visible:last').focus();
    }, 300);
}


//generate billing row html
function generateBankRow(tracking_adjust_amount, num) {
    num = parseInt(num) + 1;
    var html = '<div class="row tr_banking_row" id="tr_banking_row_1" bank-data-row-id="' + num + '">';
    html += '<div class="col-md-2" style="padding: 0 2px;">';
    html += '<div class="form-group">';
    html += '<input type="text" class="form-control tr_banking_transaction_type" placeholder="Transaction Type" name="tr_banking_transaction_type[]">';
    html += '<input type="hidden" class="tr_transaction_id_modal" name="tr_transaction_id_modal[]">';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-1 min-padding" style="padding: 0 2px;">';
    html += '<div class="form-group">';
    html += '<input type="text" class="form-control tr_banking_instrument_no" placeholder="Ins No" name="instrument_no[]">';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-1 min-padding" style="padding: 0 2px;">';
    html += '<div class="form-group">';
    html += '<input type="text" class="form-control tr_banking_instrument_date" placeholder="Date" name="tr_banking_instrument_date[]">';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-2" style="padding: 0 2px;">';
    html += '<div class="form-group">';
    html += '<input type="text" class="form-control tr_banking_bank_name" placeholder="Bank Name" name="tr_banking_bank_name[]">';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-2" style="padding: 0 2px;">';
    html += '<div class="form-group">';
    html += '<input type="text" class="form-control tr_banking_branch_name" placeholder="Branch Name" name="tr_banking_branch_name[]">';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-2" style="padding: 0 2px;">';
    html += '<div class="form-group">';
    html += '<input type="text" class="form-control tr_banking_ifsc_code" placeholder="IFSC Code" name="tr_banking_ifsc_code[]">';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-2" style="padding: 0 2px;">';
    html += '<div class="form-group">';
    html += '<input type="text" class="form-control tr_banking_bank_amount text-right" placeholder="Amount" name="tr_banking_bank_amount[]" value="' + tracking_adjust_amount + '">';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("#banking_container").append(html);
    setTimeout(function() {
        $('#popupModal').find('.tr_banking_transaction_type').filter(':visible:last').focus();
    }, 100);
}


var referenceArray = [];

//get bill name
$("#popupModal").delegate('.bill_name', 'focusin', function() {

    $(this).autocomplete({
        source: function(request, response) {

            var ledger_id = $('#popupModal #popup_tr_ledger_id').val();

            $.ajax({
                type: "POST",
                url: full_path + 'transaction/entries/getBillByReferenceLedgerId',
                data: "bill_name=" + request.term + "&ajax=1&ledger_id=" + ledger_id + "&total_bill_array=" + JSON.stringify(referenceArray),
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    console.log(error);
                }
            });
        },
        minLength: 0,
        select: function(e, ui) {
            var self = $(this);
            e.preventDefault();


            var billGot = ui.item.label;
            var billNameRegenerated = $.trim(billGot.substring(0, billGot.indexOf(':')));

            $(this).val(billNameRegenerated);


            var found = jQuery.inArray(ui.item.value, referenceArray);
            if (found >= 0) {
                // Element was found, remove it.
                referenceArray.splice(ui.item.value, 1);
            } else {
                // Element was not found, add it.
                referenceArray.push(ui.item.value);
            }

            var ledgerId = $('#popupModal #popup_tr_ledger_id').val();


            var url = full_path + 'transaction/entries/getBillByBillnameLedgerId';
            var queryString = "bill_name=" + billNameRegenerated + "&ledger_id=" + ledgerId + '&ajax=1';
            $.ajax({
                type: "POST",
                url: url,
                data: queryString,
                dataType: "json",
                success: function(DATA) {
                    if (DATA.SUCCESS) {
                        var dateAr = DATA.MENU.credit_date.split('-');
                        var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];

                        $(self).closest('.billing-row').find(".bill_credit_day").val(DATA.MENU.credit_days);
                        $(self).closest('.billing-row').find(".bill_credit_date").val(newDate);

                    }
                },
                error: function(request, error) {

                }
            });

        }
    }).focus(function() {
        $(this).autocomplete("search");
    });

});

//init bank instrument date masking
$('#popupModal').delegate('.tr_banking_instrument_date', 'focusin', function() {
    $(this).inputmask("d/m/y", {placeholder: "dd/mm/yyyy"});
});

//get banking transaction type
$('#popupModal').delegate('.tr_banking_transaction_type', 'focusin', function() {

    $(this).autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: full_path + 'transaction/admin/getTransactionTypes',
                data: "transaction=" + request.term + '&ajax=1',
                dataType: "json",
                success: function(data) {
                    response(data);
                    console.log(data);
                },
                error: function(request, error) {

                    console.log(error);
                }
            });
        },
        minLength: 0,
        select: function(e, ui) {
            var self = $(this);
            e.preventDefault(); // <--- Prevent the value from being inserted.
            $(this).val(ui.item.label); // display the selected text
            $(this).next(".tr_transaction_id_modal").val(ui.item.value); // save selected id to hidden input
            $(this).closest('.row').find('.tr_banking_instrument_no').focus();
        }
    }).focus(function() {
        $(this).autocomplete("search", "");
    });

});


$("form#tran-update-form").submit(function(e) {
    e.preventDefault();
    var l = Ladda.create(document.querySelector('.ladda-button'));
    l.start();
    var form = $(this),
            url = form.attr('action'),
            data = form.serialize();
    var dataString = $("#tran-update-form, #service_modal_form").serialize();
    $.ajax({
        url: url,
        type: 'POST',
        data: dataString,
        dataType: 'json',
        success: function(data) {
            l.stop();
            if (data.res == 'success') {
                var activity = $('input[name="activity_submit"]:checked').val();
                Command: toastr["success"](data.message);
                if (parseInt(activity) == 1) {
                    location.reload();
                } else if (parseInt(activity) == 2) {
                    if (typeof data.redirect_url != 'undefined') {
                        window.location.href = data.redirect_url;
                    }
                } else if (parseInt(activity) == 3) {
                    window.location.href = data.print_url;
                }
            } else if (data.res == 'save_err') {
                Command: toastr["error"](data.message);
            } else if (data.res == 'error') {
                Command: toastr["error"](data.message);
            }
        }
    })

})

//add group
$("#add_group_form_te").submit(function(event) {
    event.preventDefault();
    var l = Ladda.create(document.querySelector('.add-group-btn'));
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


//-----------------service-------------

$('body').delegate('.search_item', 'focusin', function() {
    var self = $(this);
    var shippingCountry = $('.in_ledger_country').val();
    var shippingState = $('.in_ledger_state').val();
    var type_service_product = $(this).closest('tr').find("#service_product").val();
    $(this).autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: full_path + 'transaction/accounts_inventory/getProducts',
                data: {term: request.term, type_service_product: type_service_product},
                dataType: "json",
                success: function(data) {
                    response(data);
                },
                error: function(request, error) {
                    console.log(error);
                }
            });
        },
        minLength: 0,
        select: function(e, ui) {
            var self = $(this);
            e.preventDefault(); // <--- Prevent the value from being inserted.
            $(this).val(ui.item.label); // display the selected text
            self.closest('tr').find('.tr_service_id').val(ui.item.value);
            var pId = ui.item.value;
            var productName = ui.item.label;
            $.ajax({
                type: "POST",
                url: full_path + 'transaction/accounts_inventory/getProductDetails',
                data: "pId=" + pId + "&shippingcountry=" + shippingCountry + "&shippingstate=" + shippingState + "&type_service_product=" + type_service_product,
                dataType: "json",
                success: function(data) {

                    var shippingState = $('.in_ledger_state').val();
                    var shippingCountry = $('.in_ledger_country').val();
                    var price = self.closest('tr').find('.service_amount').val();
                    var taxPerc = parseFloat(data['productTax']);
                    var companyState = data['billingStateId'];
                    var companyCountry = data['billingCountryId'];
                    var taxVal = parseFloat((taxPerc / 100) * price);
                    var cessStatus = data['cess_present'];
                    var cessPercentage = data['cess_value'];
                    self.closest('tr').find('.tax_percentage').val(taxPerc);
                    self.closest('tr').find('.cess_percentage').val(cessPercentage);
                    if (parseInt(cessStatus) == 1) {
                        $("#cess_status").val(1);
                        var cessVal = parseFloat((cessPercentage / 100) * taxVal);
                    } else {
                        cessPercentage = 0;
                        var cessVal = 0;
                        $("#cess_status").val(0);
                    }
                    if ($.trim(parseInt(companyCountry)) == $.trim(parseInt(shippingCountry))) {
                        $("#export_status").val(0);
                        if ($.trim(parseInt(companyState)) == $.trim(parseInt(shippingState))) {
                            $("#igst_status").val(0);
                            self.closest('tr').find('.igst_percentage').html(parseFloat(0));
                            self.closest('tr').find('.igst_value').html(parseFloat(0));
                            self.closest('tr').find('.sgst_percentage').html(parseFloat(taxPerc / 2));
                            self.closest('tr').find('.sgst_value').html(parseFloat(taxVal / 2));
                            self.closest('tr').find('.cgst_percentage').html(parseFloat(taxPerc / 2));
                            self.closest('tr').find('.cgst_value').html(parseFloat(taxVal / 2));
                            self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage));
                            self.closest('tr').find('.cess_value').html(parseFloat(cessVal));
                        } else {
                            $("#igst_status").val(1);
                            self.closest('tr').find('.igst_percentage').html(parseFloat(taxPerc));
                            self.closest('tr').find('.igst_value').html(parseFloat(taxVal));
                            self.closest('tr').find('.sgst_percentage').html(parseFloat(0));
                            self.closest('tr').find('.sgst_value').html(parseFloat(0));
                            self.closest('tr').find('.cgst_percentage').html(parseFloat(0));
                            self.closest('tr').find('.cgst_value').html(parseFloat(0));
                            self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage));
                            self.closest('tr').find('.cess_value').html(parseFloat(cessVal));
                        }
                    } else {

                        if (parseInt($('input[name="tax_status_country"]:checked').val()) == 1) {
                            $("#export_status").val(2);
                            $("#igst_status").val(1);
                            self.closest('tr').find('.igst_percentage').html(parseFloat(0));
                            self.closest('tr').find('.igst_value').html(parseFloat(0));
                            self.closest('tr').find('.sgst_percentage').html(parseFloat(0));
                            self.closest('tr').find('.sgst_value').html(parseFloat(0));
                            self.closest('tr').find('.cgst_percentage').html(parseFloat(0));
                            self.closest('tr').find('.cgst_value').html(parseFloat(0));
                            self.closest('tr').find('.cess_percentage').html(parseFloat(0));
                            self.closest('tr').find('.cess_value').html(parseFloat(0));
                        } else {
                            $("#export_status").val(1);
                            self.closest('tr').find("#igst_status").val(1);
                            self.closest('tr').find('.igst_percentage').html(parseFloat(taxPerc));
                            self.closest('tr').find('.igst_value').html(parseFloat(taxVal));
                            self.closest('tr').find('.sgst_percentage').html(parseFloat(0));
                            self.closest('tr').find('.sgst_value').html(parseFloat(0));
                            self.closest('tr').find('.cgst_percentage').html(parseFloat(0));
                            self.closest('tr').find('.cgst_value').html(parseFloat(0));
                            self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage));
                            self.closest('tr').find('.cess_value').html(parseFloat(cessVal));
                        }
                    }
                },
                error: function(request, error) {
                    //alert('connection error. please try again.');
                    console.log(error);
                }
            });
        }

    }).focus(function() {
        $(this).autocomplete("search", "");
    });
}); // autocomplete 
$('#serviceModal').on('keypress keyup', '.service_amount', function(e) {
        if ($("#serviceModal").hasClass("service-entry")) {
            if (e.which == 13) {
                var tr_igst_val = $('.transaction-form').find('input[value="31"]').closest("tr").find('input[name="amount[]"]').val();
                if (!tr_igst_val) {
                    var tr_igst_val = $('.transaction-form').find('input[value="32"]').closest("tr").find('input[name="amount[]"]').val();
                }
                var tr_cgst_val = $('.transaction-form').find('input[value="33"]').closest("tr").find('input[name="amount[]"]').val();
                if (!tr_cgst_val) {
                    var tr_cgst_val = $('.transaction-form').find('input[value="34"]').closest("tr").find('input[name="amount[]"]').val();
                }
                var tr_sgst_val = $('.transaction-form').find('input[value="35"]').closest("tr").find('input[name="amount[]"]').val();
                if (!tr_sgst_val) {
                    var tr_sgst_val = $('.transaction-form').find('input[value="36"]').closest("tr").find('input[name="amount[]"]').val();
                }
                var tr_cess_val = $('.transaction-form').find('input[value="37"]').closest("tr").find('input[name="amount[]"]').val();
                if (!tr_cess_val) {
                    var tr_cess_val = $('.transaction-form').find('input[value="38"]').closest("tr").find('input[name="amount[]"]').val();
                }
                if (typeof tr_igst_val == 'undefined') {
                    tr_igst_val = parseFloat(0);
                } else {
                    tr_igst_val = parseFloat(tr_igst_val);
                }
                if (typeof tr_cgst_val == 'undefined') {
                    tr_cgst_val = parseFloat(0);
                } else {
                    tr_cgst_val = parseFloat(tr_cgst_val);
                }
                if (typeof tr_sgst_val == 'undefined') {
                    tr_sgst_val = parseFloat(0);
                } else {
                    tr_sgst_val = parseFloat(tr_sgst_val);
                }
                if (typeof tr_cess_val == 'undefined') {
                    tr_cess_val = parseFloat(0);
                } else {
                    tr_cess_val = parseFloat(tr_cess_val);
                }
                var _igst_val = 0;
                var igst_arr = $(".igst_value");
                $.each(igst_arr, function(index, element) {
                    _igst_val = _igst_val + parseFloat($(element).html());
                });
                var _cgst_val = 0;
                var cgst_arr = $(".cgst_value");
                $.each(cgst_arr, function(index, element) {
                    _cgst_val = _cgst_val + parseFloat($(element).html());
                });
                var _sgst_val = 0;
                var sgst_arr = $(".sgst_value");
                $.each(sgst_arr, function(index, element) {
                    _sgst_val = _sgst_val + parseFloat($(element).html());
                });
                var _cess_val = 0;
                var cess_arr = $(".cess_value");
                $.each(cess_arr, function(index, element) {
                    _cess_val = _cess_val + parseFloat($(element).html());
                });
                var matched_igst = 0;
                var matched_sgst = 0;
                var matched_cgst = 0;
                var matched_cess = 0;
                if (tr_igst_val > _igst_val.toFixed(2)) {
                    generateServiceRow();
                } else if (tr_igst_val < _igst_val.toFixed(2)) {
                    Command: toastr["error"]('IGST amount do not matched.');
                } else if (tr_igst_val == _igst_val.toFixed(2)) {
                    var matched_igst = 1;
                }
                if (tr_sgst_val > _sgst_val.toFixed(2)) {
                    generateServiceRow();
                } else if (tr_sgst_val < _sgst_val.toFixed(2)) {
                    Command: toastr["error"]('SGST amount do not matched.');
                } else if (tr_sgst_val == _sgst_val.toFixed(2)) {
                    var matched_sgst = 1;
                }
                if (tr_cgst_val > _cgst_val.toFixed(2)) {
                    generateServiceRow();
                } else if (tr_cgst_val < _cgst_val.toFixed(2)) {
                    Command: toastr["error"]('CGST amount do not matched.');
                } else if (tr_cgst_val == _cgst_val.toFixed(2)) {
                    var matched_cgst = 1;
                }
                if (tr_cess_val > _cess_val.toFixed(2)) {
                    generateServiceRow();
                } else if (tr_cess_val < _cess_val.toFixed(2)) {
                    Command: toastr["error"]('CESS amount do not matched.');
                } else if (tr_cess_val == _cess_val.toFixed(2)) {
                    var matched_cess = 1;
                }
                console.log(tr_igst_val);
                console.log(_igst_val.toFixed(2));
                if (matched_igst && matched_sgst && matched_cgst && matched_cess) {
                    Command: toastr["success"]('GST value matched properly.');
                    $('.tr_narration').focus();
                    $("#serviceModal").modal('hide');
                }
            } else {
                var self = $(this);
                var cess_status = $("#cess_status").val();
                var igst_status = $("#igst_status").val();
                var export_status = $("#export_status").val();
                var taxPerc = self.closest('tr').find(".tax_percentage").val();
                var price = $(this).val();
                var taxVal = parseFloat((taxPerc / 100) * price);
                console.log('ASIT')
                console.log(taxVal)
                var cessPercentage = self.closest('tr').find('.cess_percentage').val();
                if (!cessPercentage) {
                    cessPercentage = 0;
                }
                var cessVal = parseFloat((cessPercentage / 100) * taxVal);
                if (parseInt(igst_status) == 0) {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(taxPerc / 2).toFixed(2));
                    self.closest('tr').find('.sgst_value').html(parseFloat(taxVal / 2).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(taxPerc / 2).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(taxVal / 2).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(cessVal).toFixed(2));
                } else {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(taxPerc).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(taxVal).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(cessVal).toFixed(2));
                }
                if ((parseInt(export_status) == 1) && parseInt($('input[name="tax_status_country"]:checked').val()) == 1) {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(0));
                    self.closest('tr').find('.sgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(0).toFixed(2));
                }
            }
        } else if ($("#serviceModal").hasClass("advance-entry")) {
            console.log('advance')
            if (e.which == 13) {
                var self = $(this);
                var advance_amount = $('input[ledger-type="debitors"]').closest('tr').find('input[name="amount[]"][disabled!=disabled]').val();
                advance_amount = parseFloat(advance_amount);

                var _advance_val = 0;
                var advance_arr = $(".service_amount");
                $.each(advance_arr, function(index, element) {
                    if ($(element).val() != '') {
                        _advance_val = _advance_val + parseFloat($(element).val());
                    }
                });
                if (advance_amount > _advance_val.toFixed(2)) {
                    generateServiceRow();
                } else if (advance_amount < _advance_val.toFixed(2)) {
                    self.closest('tr').nextAll('tr').remove();
                    Command: toastr["error"]('Advance amount do not matched.');
                } else if (advance_amount == _advance_val.toFixed(2)) {
                    self.closest('tr').nextAll('tr').remove();
                    Command: toastr["success"]('Advance amount matched properly.');
                    $("#serviceModal").modal('hide');
                    $('input[ledger-type="debitors"]').closest('tr').next('tr').find('input:first').focus();
                }

            } else {
                var self = $(this);
                var cess_status = $("#cess_status").val();
                var igst_status = $("#igst_status").val();
                var export_status = $("#export_status").val();
                var taxPerc = self.closest('tr').find(".tax_percentage").val();
                var price = $(this).val();
                var taxVal = parseFloat((taxPerc / (100 + parseFloat(taxPerc))) * price);
                console.log('ASIT')
                console.log(taxVal)
                var cessPercentage = self.closest('tr').find('.cess_percentage').val();
                if (!cessPercentage) {
                    cessPercentage = 0;
                }
                var cessVal = parseFloat((cessPercentage / 100) * taxVal);
                if (parseInt(igst_status) == 0) {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(taxPerc / 2).toFixed(2));
                    self.closest('tr').find('.sgst_value').html(parseFloat(taxVal / 2).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(taxPerc / 2).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(taxVal / 2).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(cessVal).toFixed(2));
                } else {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(taxPerc).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(taxVal).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(cessPercentage).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(cessVal).toFixed(2));
                }
                if ((parseInt(export_status) == 1) && parseInt($('input[name="tax_status_country"]:checked').val()) == 1) {
                    self.closest('tr').find('.igst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.igst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.sgst_percentage').html(parseFloat(0));
                    self.closest('tr').find('.sgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cgst_value').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_percentage').html(parseFloat(0).toFixed(2));
                    self.closest('tr').find('.cess_value').html(parseFloat(0).toFixed(2));
                }
            }
        }
    });

function generateServiceRow() {
    var html = '';
    html += '<tr>';
    html += '<td>';
    html += '<div class="form-group">';
    html += '<select class="form-control" name="service_product" id="service_product">';
    html += '<option value="1">Service</option>';
    html += '<option value="2">Product</option>';
    html += ' </select>';
    html += '</div>';
    html += '</td>';
    html += '<td>';
    html += ' <div class="form-group">';
    html += '<input type="text" class="form-control search_item" placeholder="Select Service">';
    html += '<input type="hidden" class="tr_service_id" name="tr_service_id[]">'
    html += '</div>';
    html += '</td>';
    html += '<td>';
    html += '<div class="form-group">';
    html += '<input type="text" class="form-control service_amount" name="service_amount[]" placeholder="Amount">';
    html += '<input type="hidden" name="tax_percentage[]" class="tax_percentage">';
    html += '<input type="hidden" name="cess_percentage[]" class="cess_percentage">';
    html += '</div>';
    html += '</td>';
    html += '<td>';
    html += '<span class="igst_percentage">0</span>';
    html += '</td>';
    html += '<td>';
    html += '<span class="igst_value">0</span>';
    html += '</td>';
    html += '<td>';
    html += '<span class="sgst_percentage">0</span>';
    html += '</td>';
    html += '<td>';
    html += '<span class="sgst_value">0</span>';
    html += '</td>';
    html += '<td>';
    html += '<span class="cgst_percentage">0</span>';
    html += '</td>';
    html += '<td>';
    html += '<span class="cgst_value">0</span>';
    html += '</td>';
    html += '<td>';
    html += '<span class="cess_percentage">0</span>';
    html += '</td>';
    html += '<td>';
    html += '<span class="cess_value">0</span>';
    html += '</td>';
    html += '</tr>';
    $("#serviceModal").find('table').find('tbody').append(html);
}

function checkGroup(obj) {
        var group_id = $(obj).val();
        $.ajax({
            type: "POST",
            url: full_path+'accounts/accounts/checkGroup',
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
          $( "input.tr_ledger:first" ).focus();
        }
        


      }
    });

