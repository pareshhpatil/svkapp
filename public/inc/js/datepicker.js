$(document).ready(function () {
    // Tooltip only Text
    $('.masterTooltip').hover(function () {
        // Hover over code
        var title = $(this).attr('title');
        $(this).data('tipText', title).removeAttr('title');
        $('<p class="tooltip"></p>')
                .text(title)
                .appendTo('body')
                .fadeIn('slow');
    }, function () {
        // Hover out code
        $(this).attr('title', $(this).data('tipText'));
        $('.tooltip').remove();
    }).mousemove(function (e) {
        var mousex = e.pageX + 20; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
        $('.tooltip')
                .css({top: mousey, left: mousex})
    });
});

function showDatePicker(datepicker_name, dateval) {
    var datepicker_name = '#' + datepicker_name;

    $(datepicker_name).datepicker({
        inline: true,
        dateFormat: 'd M yy' //Setting dateformat for the datepicker
    });
    // Hover states on the static widgets
    $("#dialog-link, #icons li").hover(
            function () {
                $(this).addClass("ui-state-hover");
            },
            function () {
                $(this).removeClass("ui-state-hover");
            }
    );
    $(function () {
        $(datepicker_name).datepicker("setDate", dateval);
    });
}
            