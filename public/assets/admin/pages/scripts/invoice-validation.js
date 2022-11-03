var FormValidation = function () {

    // basic validation
  
	    var handleValidation2 = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation

            var invoice = $('#invoice_create');
            var error2 = $('.alert-danger', invoice);
            var success2 = $('.alert-success', invoice);

            invoice.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    billcyclename: {
						alphanumeric: true,
						maxlength: 45,
                        minlength: 2,
                        required: true
                    },
					label: {
						alphanumeric: true,
						maxlength: 45,
                        minlength: 2
                        
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    bill_date: {
						required: true
					},
					due_date: {
						required: true
					},
					selecttemplate: {
						required: true
					},
					mobile: {
						required: true,
						minlength: 10,
						maxlength: 12,
						digits: true
					},
					amount_field: {
						swipezamount: true,
						maxlength: 9,
						required: true
					},
					units: {
						swipezamount: true,
						maxlength: 9,
						required: true
					},
					narrative: {
						maxlength: 45
					},
					percenttax: {
						percentage: true,
						maxlength: 5
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