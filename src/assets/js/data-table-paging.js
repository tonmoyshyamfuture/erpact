function dataTablePaging() {
    if ($(window).width() > 800) {
        $('.dataTables_info').appendTo($('.footer-band'));
        $('.dataTables_paginate').appendTo($('.footer-band'));
    }    
    setTimeout(function () {
        $('input[type="search"]').first().attr("placeholder", "Search any data...");
    }, 1000);
}