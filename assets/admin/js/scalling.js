var type = '';
$(document).ready(function() {
    type = '1';
})

function change_amount_format() {
    var type = $("#amount_format_input").val();
    $(".balence_hidden").each(function(e) {
        var id = $(this).attr("data-id");
        var val = $(this).val();
        if (val != '') {
            switch (type) {
                case 'K':
                    var value = parseFloat(val) / parseFloat(1000);
                    value = change_amount_format_by_decimal(value);
                    $(".balence_show_" + id).html(value);
                    break;
                case 'L':
                    var value = parseFloat(val) / parseFloat(100000);
                    value = change_amount_format_by_decimal(value);
                    $(".balence_show_" + id).html(value);
                    break;
                case 'CR':
                    var value = parseFloat(val) / parseFloat(10000000);
                    value = change_amount_format_by_decimal(value);
                    $(".balence_show_" + id).html(value);
                    break;
                default:
                    var value = change_amount_format_by_decimal(val);
                    $(".balence_show_" + id).html(value);
                    break;
            }
        }
    });
    $(".rate_hidden").each(function(el) {
        var id = $(this).attr("data-rate-id");
        var val = $(this).val();
        if (val != '') {
            switch (type) {
                case 'K':
                    var value = parseFloat(val) / parseFloat(1000);
                    value = change_amount_format_by_decimal(value);
                    $(".rate_show_" + id).html(value);
                    break;
                case 'L':
                    var value = parseFloat(val) / parseFloat(100000);
                    value = change_amount_format_by_decimal(value);
                    $(".rate_show_" + id).html(value);
                    break;
                case 'CR':
                    var value = parseFloat(val) / parseFloat(10000000);
                    value = change_amount_format_by_decimal(value);
                    $(".rate_show_" + id).html(value);
                    break;
                default:
                    var value = change_amount_format_by_decimal(val);
                    $(".rate_show_" + id).html(value);
                    break;
            }
        }
    });
    
}
function change_amount_format_by_decimal(value) {
    var decimal_number = $("#decimal_number_input").val();
    if (decimal_number != '') {
        return change_amount_format_by_type_decimal(value);
    } else {
        return value;
    }
}
function change_amount_format_by_type_decimal(value) {
    var type_decimal = $('input[name=decimal_type]:checked').val();
    var decimal_number = $("#decimal_number_input").val();
    switch (type_decimal) {
        case 'ro':
            var value = parseFloat(value).toFixed(decimal_number);
//            return addC(value);
            return addC(value);
            break;
        case 'ru':
            return roundUp(value, get_multiple_of_10(decimal_number));
            break;
        case 'rd':
            return myToFixed(value, decimal_number);
            break;
        default:
            return;
            break;
    }
}
function roundUp(num, precision) {
//    return Math.ceil(num * precision) / precision
    return addC(Math.ceil(num * precision) / precision);
}
function get_multiple_of_10(num) {
    var number = 1;
    for ($i = 0; $i < num; $i++) {
        number = parseInt(number) * parseInt(10);
    }
    return number;
}
function myToFixed(i, digits) {
    var pow = Math.pow(10, digits);
//    return Math.floor(i * pow) / pow;
    return addC(Math.floor(i * pow) / pow);
}
function addC(nStr) {
//    nStr += '';
    nStr = $.trim(nStr);
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
