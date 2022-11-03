var FormValidation = function () {

    // basic validation
  
	    var handleValidation2 = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation

            var template = $('#template_create');
            var error2 = $('.alert-danger', template);
            var success2 = $('.alert-success', template);

            template.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    template_name: {
						alphanumeric: true,
						maxlength: 45,
                        minlength: 2,
                        required: true
                    },
					label: {		// header label 
						alphanumeric: true,
						maxlength: 45,
                        minlength: 2
                        
                    },
                    email: {
                        required: true,
                        email: true
                    },
					particular_label: {		// particular label 
						alphanumeric: true,
						maxlength: 45,
                        minlength: 2
                        
                    },
					tax_label: {		// tax label 
						alphanumeric: true,
						maxlength: 45,
                        minlength: 2
                        
                    },
					percenttax: {
						percentage: true,
						minlength: 1,
						maxlength:5
					},
					previuos_dues: {
						previousduesamount: true,   // previousduesamount = value with -ve sign and amount less than 1 lakh rupees
						maxlength:10,
						
					},
					amount_fields: {
						swipezamount: true,    // swipezamount = value below 1 lakh rupees
						maxlength: 9,
						minlength: 1
					},
					selecttemplate: {
						required: true
					},
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success2.hide();
                    error2.show();
                    Swipez.scrollTo(error2, -200);
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var icon = $(element).parent('.input-icon').children('i');
                    icon.removeClass('fa-check').addClass("fa-warning");  
                    icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    
                },

                success: function (label, element) {
                    var icon = $(element).parent('.input-icon').children('i');
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                    icon.removeClass("fa-warning").addClass("fa-check");
                },

                submitHandler: function (form) {
                    success2.show();
                    error2.hide();
                    form[0].submit(); // submit the form
                }
            });


    }
	 
  
    return {
        //main function to initiate the module
        init: function () {
           handleValidation2();

           }

    };

}();