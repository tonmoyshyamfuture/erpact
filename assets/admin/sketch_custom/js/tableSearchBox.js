// datable search, info pagination moved to a different place instead of default place
function tableSearchBox(){
    $('.dataTables_info').appendTo($('.footer-button'));
    $('.dataTables_paginate').appendTo($('.footer-button'));    
};

$(document).ready(function(){
    $(".toggleExpand").click(function(){   
        $(this).toggleClass('hidden');
        $(".toggleCollapse").toggleClass('hidden');
        $(".gen_info").toggleClass('hidden');
        $(".add_details").toggleClass('hidden');
    });

    $(".toggleCollapse").click(function(){   
        $(this).toggleClass('hidden');
        $(".toggleExpand").toggleClass('hidden');
        $(".gen_info").toggleClass('hidden');
        $(".add_details").toggleClass('hidden');
    });

});
