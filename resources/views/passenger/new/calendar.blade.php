<link rel='stylesheet' href='/assets/css/calendar.css?v=2'>
<style>
    #calendar {
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -moz-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -moz-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-align-content: center;
        -ms-flex-line-pack: center;
        align-content: center;
    }

    #calendarContainer,
    #organizerContainer {
        float: left;
        margin: 5px;
    }

    .cjslib-events.cjslib-size-small {
        height: 250px;
    }

    .cjslib-day-indicator {
        background-color: #e8481e !important;
    }

    .cjslib-rows {
        padding: 10px;
    }

    .cjslib-rows {
        background-color: transparent !important;
    }

    body.dark-mode .cjslib-rows {
        background-color: #030108 !important;
    }

    body.dark-mode .cjslib-day {
        background-color: #161129 !important;

    }

    body.dark-mode .cjslib-day>.cjslib-day-num {
        color: #ffffff;
    }

    #calendarContainer,
    #organizerContainer {
        float: left;
        margin-top: 0px;
    }
</style>


<div id="calendar" style="display: none;">
    <div id="appCapsule" class="full-height">
        <div class="section tab-content mb-1">
            <div id="calendarContainer"></div>
        </div>
        <div class="section tab-content mb-1">
            <div id="organizerContainer"></div>
        </div>

    </div>

</div>
</div>
