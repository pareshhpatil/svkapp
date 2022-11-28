var FormValidation = function() {

    // basic validation
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation

        var profile = $('#profile_update');
        var error1 = $('.alert-danger', profile);
        var success1 = $('.alert-success', profile);

        profile.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            messages: {
                first_name: {
                    required: "This field is required filled",
                },
                last_name: {
                    required: "This field is required filled"
                },
                mobile: {
                    required: "This field is required filled",
                    minlength: "Please enter at least {0} digits"
                },
                landline: {
                    minlength: "Please enter at least {0} digits"
                }
            },
            rules: {
                first_name: {
                    nametype: true,
                    maxlength: 45,
                    minlength: 2,
                    required: true
                },
                last_name: {
                    nametype: false,
                    maxlength: 45,
                    minlength: 2,
                    required: true
                },
                email: {
                    required: true,
                    maxlength: 250,
                    email: true
                },
                mobile: {
                    maxlength: 12
                },
                landline: {
                    maxlength: 12,
                    minlength: 10,
                    digits: true
                },
                addr1: {
                    minlength: 25,
                    maxlength: 250,
                },
                zip: {
                    maxlength: 10,
                    digits: true
                },
                state: {
                    maxlength: 40,
                    lettersonly: true
                },
                city: {
                    maxlength: 40,
                    lettersonly: true
                },
                country: {
                    required: true,
                    lettersonly: true
                },
                dob: {
                    required: true
                },
                pfirst_name: {
                    nametype: true,
                    maxlength: 45,
                    minlength: 2,
                    required: true
                },
                plast_name: {
                    nametype: false,
                    maxlength: 45,
                    minlength: 2,
                    required: true
                },
                pemail: {
                    required: true,
                    email: true
                },
                pmobile: {
                    required: true,
                    maxlength: 13,
                    minlength: 10,
                    digits: true
                },
                plandline: {
                    maxlength: 12,
                    minlength: 10,
                    digits: true
                },
                paddr1: {
                    required: true,
                    minlength: 25,
                    maxlength: 150
                },
                address: {
                    maxlength: 150
                },
                pzipcode: {
                    required: true,
                    minlength: 5,
                    maxlength: 6,
                    digits: true
                },
                pstate: {
                    required: true,
                    lettersonly: true
                },
                pcity: {
                    required: true,
                    lettersonly: true
                },
                pcountry: {
                    required: true,
                    lettersonly: true
                },
                pdob: {
                    required: true
                },
                comptype: {
                    required: true
                },
                entity: {
                    required: true
                },
                bemail: {
                    required: true,
                    email: true
                },
                bmobile: {
                    required: true,
                    maxlength: 13,
                    minlength: 10,
                    digits: true
                },
                baddr1: {
                    required: true,
                    minlength: 2,
                    maxlength: 250
                },
                bzipcode: {
                    required: true,
                    maxlength: 6,
                    digits: true
                },
                bstate: {
                    required: true,
                    lettersonly: true
                },
                bcity: {
                    required: true,
                    lettersonly: true
                },
                bcountry: {
                    required: true,
                    lettersonly: true
                },
                pan: {
                    pannumber: true,
                    maxlength: 10,
                    minlength: 10
                },
                reg_number: {
                    digits: true,
                    maxlength: 30,
                    minlength: 2
                }, template_name: {
                    required: true,
                    maxlength: 40
                }
            },
            invalidHandler: function(event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                Swipez.scrollTo(error1, -200);
            },
            highlight: function(element) { // hightlight error inputs
                $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function(form) {
                success1.show();
                error1.hide();
            }
        });


    }



    return {
        //main function to initiate the module
        init: function() {
            handleValidation1();

        }

    };

}();