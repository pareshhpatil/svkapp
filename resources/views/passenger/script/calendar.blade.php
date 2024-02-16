<script src='/assets/js/calendar.js?v=2'></script>
<script>
    "use strict";

    async function loadCalendar() {
        let res = await axios.get('/calendar-data');
        var data = res.data;
        // initializing a new calendar object, that will use an html container to create itself
        var calendar = new Calendar(
            "calendarContainer", // id of html container for calendar
            "small", // size of calendar, can be small | medium | large
            [
                "Wednesday", // left most day of calendar labels
                3 // maximum length of the calendar labels
            ],
            [
                "#e8481e", // primary color
                "#161129", // primary dark color
                "#FFFFFF", // text color
                "#FFFFFF" // text dark color
            ]
        );

        // initializing a new organizer object, that will use an html container to create itself
        var organizer = new Organizer(
            "organizerContainer", // id of html container for calendar
            calendar, // defining the calendar that the organizer is related to
            data // giving the organizer the static data that should be displayed
        );

    }
</script>
