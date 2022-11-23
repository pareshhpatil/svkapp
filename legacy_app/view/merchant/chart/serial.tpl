
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <!-- BEGIN PORTLET-->

            <div class="portlet">
                
                <div class="portlet-body" >

                    <form class="form-inline" role="form" action="" method="post">
                        <div class="form-group">
                            <input class="form-control form-control-inline input-sm date-picker" type="text" required  value="{$from_date}" name="from_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="From date"/>
                        </div>

                        <div class="form-group">
                            <input class="form-control form-control-inline input-sm date-picker" type="text" required value="{$to_date}" name="to_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="To date"/>
                        </div>

                        <input type="submit" class="btn btn-sm blue" value="Search">
                    </form>

                </div>
            </div>

            <!-- END PORTLET-->
        </div>
    </div>
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <script type="text/javascript">
                AmCharts.makeChart("chartdiv",
        {
            "type": "serial",
                    "categoryField": "date",
                    "dataDateFormat": "YYYY-MM-DD",
                    "colors": [
                    "#2C5294",
                    "#FCD202",
                    "#B0DE09",
                    "#0D8ECF",
                    "#2A0CD0",
                    "#CD0D74",
                    "#CC0000",
                    "#00CC00",
                    "#0000CC",
                    "#DDDDDD",
                    "#999999",
                    "#333333",
                    "#990000"
            ],
                    "categoryAxis": {
            "parseDates": true
            },
                    "chartCursor": {},
                    "chartScrollbar": {},
                    "trendLines": [],
                    "graphs": [
                {
                    "bullet": "round",
                    "id": "AmGraph-1",
                    "title": "Received",
                    "valueField": "column-1"
                }
            ],
            "guides": [],
            "valueAxes": [
                {
                    "id": "ValueAxis-1",
                    "title": "# Payments"
                }
            ],
            "allLabels": [],
            "balloon": {},
                    "legend": {
                "useGraphSettings": true
            },
            "titles": [
                {
                    "id": "Title-1",
                    "size": 15,
                    "text": "Payment received"
                }
            ],
            "dataProvider": [
                {$comma = ""}
                {$int = 0}
                {foreach from=$list.date item=v}
                    {$comma}{ "date": "{$v}", "column-1": {$list.value.$int} }
                    {$comma=","}
                    {$int=$int+1}
                {/foreach}

            ]
            }
            );</script>

            {if empty($list)}
                <br>
                <h4 class="center">No record found</h4>
            {else}
                <div id="chartdiv" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
            {/if}

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

</div>
<!-- /.modal -->