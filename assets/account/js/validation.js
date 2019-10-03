var FormValidation = function() {

    var handleAddNewEntry = function() {

//        $("#select2_sample4").select2({
//            placeholder: 'Select ..',
//            allowClear: true,
//            formatResult: format,
//            formatSelection: format,
//            escapeMarkup: function(m) {
//                return m;
//            }
//        });
//
//        $('#select2_sample4').change(function() {
//            $('#new_entry').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
//        });

        $('#new_entry').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                entry_no: {
                    required: true
                },
                create_date: {
                    required: true
                } 
            },
            messages: {
                entry_no: {
                    required: "Entry no is required."
                },
                create_date: {
                    required: "Date is required."
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit   
                $('.alert-danger', $('#new_entry')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element.closest('.input-icon'));
            },
            submitHandler: function(form) {
                form.submit(); // form validation success, call ajax form submit
            }
        });

        $('#new_entry input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#new_entry').validate().form()) {
                    $('#new_entry').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    return {
        //main function to initiate the module
        init: function() {

            handleAddNewEntry();
        }

    };

}();

