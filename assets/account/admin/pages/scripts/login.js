var Login = function () {

	var handleLogin = function() {

		$('.login-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            rules: {
	                username: {
	                    required: true
	                },
	                password: {
	                    required: true
	                },
	                remember: {
	                    required: false
	                }
	            },

	            messages: {
	                username: {
	                    required: "Username is required."
	                },
	                password: {
	                    required: "Password is required."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   
	                $('.alert-danger', $('.login-form')).show();
	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
	                form.submit(); // form validation success, call ajax form submit
	            }
	        });

	        $('.login-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.login-form').validate().form()) {
	                    $('.login-form').submit(); //form validation success, call ajax form submit
	                }
	                return false;
	            }
	        });
	}

	var handleForgetPassword = function () {
		$('.forget-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                email: {
	                    required: true,
	                    email: true
	                }
	            },

	            messages: {
	                email: {
	                    required: "Email is required."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   

	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

	        $('.forget-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.forget-form').validate().form()) {
	                    $('.forget-form').submit();
	                }
	                return false;
	            }
	        });

	        jQuery('#forget-password').click(function () {
	            jQuery('.login-form').hide();
	            jQuery('.forget-form').show();
	        });

	        jQuery('#back-btn').click(function () {
	            jQuery('.login-form').show();
	            jQuery('.forget-form').hide();
	        });

	}

	var handleRegister = function () {

            //function format(state) {
            //if (!state.id) return state.text; // optgroup
            //return "<img class='flag' src='../../assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
        //}


	$("#select2_sample4").select2({
            placeholder: 'Select a Country',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });
        
        $('#select2_sample4').change(function () {
                $('.register-form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
        
        $("#select2_sample5").select2({
            placeholder: 'Select a State',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });
        
        $('#select2_sample5').change(function () {
                $('.register-form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
        
        $("#select2_sample6").select2({
            placeholder: 'Select a City/Town',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });


                $('#select2_sample6').change(function () {
                $('.register-form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
            


         $('.register-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                
	                name: {
	                    required: true
	                },
	                email: {
	                    required: true,
	                    email: true
	                },
                        contect_no:{
                          required: true,
                          digits: true,
                          minlength: 10,
                          maxlength: 10
                        },
	                address: {
	                    required: true
	                },
	                country: {
	                    required: true
	                },
                        state: {
	                    required: true
	                },
	                city: {
	                    required: true
	                },
                        zip: {
	                    required: true
	                },
	                password: {
	                    required: true,
                            minlength: 5
	                },
	                rpassword: {
	                    equalTo: "#register_password"
	                },

	                tnc: {
	                    required: true
	                }
	            },

	            messages: { // custom messages for radio buttons and checkboxes
	                tnc: {
	                    required: "Please accept TNC first."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   

	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                if (element.attr("name") == "tnc") { // insert checkbox errors after the container                  
	                    error.insertAfter($('#register_tnc_error'));
	                } else if (element.closest('.input-icon').size() === 1) {
	                    error.insertAfter(element.closest('.input-icon'));
	                } else {
	                	error.insertAfter(element);
	                }
	            },

	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

			$('.register-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.register-form').validate().form()) {
	                    $('.register-form').submit();
	                }
	                return false;
	            }
	        });

	        jQuery('#register-btn').click(function () {
	            jQuery('.login-form').hide();
	            jQuery('.register-form').show();
	        });

	        jQuery('#register-back-btn').click(function () {
	            jQuery('.login-form').show();
	            jQuery('.register-form').hide();
	        });
	}
        
        var handleNewEntry = function () {

            //function format(state) {
            //if (!state.id) return state.text; // optgroup
            //return "<img class='flag' src='../../assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
        //}


	$("#select2_sample4").select2({
            placeholder: 'Select a Country',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });
        
        $('#select2_sample4').change(function () {
                $('.register-form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
        
        $("#select2_sample5").select2({
            placeholder: 'Select a State',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });
        
        $('#select2_sample5').change(function () {
                $('.register-form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
        
        $("#select2_sample6").select2({
            placeholder: 'Select a City/Town',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });


                $('#select2_sample6').change(function () {
                $('.new-entry_form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
            


         $('.new-entry_form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                
	                name: {
	                    required: true
	                },
	                email: {
	                    required: true,
	                    email: true
	                },
                        contect_no:{
                          required: true,
                          digits: true,
                          minlength: 10,
                          maxlength: 10
                        },
	                address: {
	                    required: true
	                },
	                country: {
	                    required: true
	                },
                        state: {
	                    required: true
	                },
	                city: {
	                    required: true
	                },
                        zip: {
	                    required: true
	                },
	                password: {
	                    required: true,
                            minlength: 5
	                },
	                rpasswordpassword: {
	                    equalTo: "#register_password"
	                },

	                tnc: {
	                    required: true
	                }
	            },

	            messages: { // custom messages for radio buttons and checkboxes
	                tnc: {
	                    required: "Please accept TNC first."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   

	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                if (element.attr("name") == "tnc") { // insert checkbox errors after the container                  
	                    error.insertAfter($('#register_tnc_error'));
	                } else if (element.closest('.input-icon').size() === 1) {
	                    error.insertAfter(element.closest('.input-icon'));
	                } else {
	                	error.insertAfter(element);
	                }
	            },

	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

			$('.register-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.register-form').validate().form()) {
	                    $('.register-form').submit();
	                }
	                return false;
	            }
	        });

	        jQuery('#register-btn').click(function () {
	            jQuery('.login-form').hide();
	            jQuery('.register-form').show();
	        });

	        jQuery('#register-back-btn').click(function () {
	            jQuery('.login-form').show();
	            jQuery('.register-form').hide();
	        });
	}
        
        var handleAddNewEntry = function() {

		$('.new-entry').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            rules: {
	                entry_no: {
	                    required: true
	                },
	                create_date: {
	                    required: true
	                },
	            },

	            messages: {
	                entry_no: {
	                    required: "Username is required."
	                },
	                create_date: {
	                    required: "Password is required."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   
	                $('.alert-danger', $('.new-entry')).show();
	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
	                form.submit(); // form validation success, call ajax form submit
	            }
	        });

	        $('.new-entry input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.new-entry').validate().form()) {
	                    $('.new-entry').submit(); //form validation success, call ajax form submit
	                }
	                return false;
	            }
	        });
	}
    
    return {
        //main function to initiate the module
        init: function () {
        	
            handleLogin();
            handleForgetPassword();
            handleRegister();
            handleAddNewEntry();     
        }

    };

}();