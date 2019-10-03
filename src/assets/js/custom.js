var adminPanel = function() {    
    var fixedHourlyChecked = function() {
        $('#fixed').on('click', function() {
            // alert($(this).children('input[type="radio"]').val());
            $(".rate-hourly").hide();
            $(".rate-fixed").show();
        });
        $('#hourly').on('click', function() {
            //alert($(this).children('input[type="radio"]').val());
            $(".rate-fixed").hide();
            $(".rate-hourly").show();
        });
    }; //fixedHourlyChecked   
    
    // Returns
    return {
        init: function() {
            fixedHourlyChecked();            
        }
    };
}();

adminPanel.init();

