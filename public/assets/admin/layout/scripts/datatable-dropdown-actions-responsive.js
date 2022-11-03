$(document).on('shown.bs.dropdown', '.table-scrollable', function (e) {
    // The .dropdown container
    var $container = $(e.target);
    // Find the actual .dropdown-menu
    var $dropdown = $container.find('.dropdown-menu');
    if ($dropdown.length) {
        // Save a reference to it, so we can find it after we've attached it to the body
        $container.data('dropdown-menu', $dropdown);
    } else {
        $dropdown = $container.data('dropdown-menu');
    }
    $dropdown.css('top', ($container.offset().top + $container.outerHeight()) + 'px');
    $dropdown.css('left', ($container.offset().left- 150)  + 'px');
    $dropdown.css('position', 'absolute');
    $dropdown.css('display', 'block');
    $dropdown.appendTo('body');
});

$(document).on('hide.bs.dropdown', '.table-scrollable', function (e) {
    // Hide the dropdown menu bound to this button
    $(e.target).data('dropdown-menu').css('display', 'none');
});