
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
                    "type": "pie",
                            "angle": 26.1,
                            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                            "depth3D": 15,
                            "innerRadius": "40%",
                            "labelRadius": 28,
                            "baseColor": "#2C5294",
                            "colors": [],
                            "gradientRatio": [
                            0.2
                    ],
                            "titleField": "category",
                            "valueField": "value",
                            "allLabels": [],
                            "balloon": {},
                            "titles": [],
                            "dataProvider": [
                {$comma = ""}
                {$int = 0}
                {foreach from=$list item=v}
                    {$comma}{ "category": "{$v.name}", "value": {$v.value} }
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