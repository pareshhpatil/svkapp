var UIBootstrapGrowl = function() {
    var allign='right';
    var delayy=5000;
    var from ='top';
    var type='success';
    var texxtt='Link copied';
    return {
        //main function to initiate the module
        init: function(tex='') {

            if(tex!='')
            {
                texxtt=tex;
            }

            $('.bs_growl_show').click(function(event) {
                $.bootstrapGrowl(texxtt, {
                    ele: 'body', // which element to append to
                    type: type, // (null, 'info', 'danger', 'success', 'warning')
                    offset: {
                        from: from,
                        amount: parseInt(100)
                    }, // 'top', or 'bottom'
                    align: allign, // ('left', 'right', or 'center')
                    width: parseInt(250), // (integer, or 'auto')
                    delay: delayy, // Time while the message will be displayed. It's not equivalent to the *demo* timeOut!
                    allow_dismiss: 1, // If true then will display a cross to close the popup.
                    stackup_spacing: 10 // spacing between consecutively stacked growls.
                });

            });

        }

    };

}();