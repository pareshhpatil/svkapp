

<div class="gap"></div>
<div class="patron_details">
    <?php require 'inc/merchantmenu.php'; ?>

    <div class="right autoheight" ><h1>Search Criteria</h1>

        <div class="right-two" style="width: 622px;">
            <form name="frm_req" action="" method="post">
                <div><input name="from_date" type="text" value="<?php echo $this->from_date; ?>"  class="date"  id="fromdate"   readonly="readonly" ></div>
                <div><input name="to_date" type="text" value="<?php echo $this->to_date; ?>"  class="date-two"  id="todate"  readonly="readonly" ></div>   
                <div class="search" style="float: right; margin-right: 20px;"> 
                    <input type="submit" id="Btnsubmit" class="search1" name="filter" value="Search >" />
                </div>
            </form>

        </div>
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
<?php
$comma = "";
$int = 0;
foreach ($this->list['date'] as $value) {
    echo $comma . '{ "date": "' . $value . '","column-1": ' . $this->list['value'][$int] . ' }
                        ';
    $comma = ",";
    $int++;
}
?>

                ]
            }
    );
</script>

<div id="chartdiv"  style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
    </div>

 </div>
<script>
    var fromdate = document.getElementById('fromdate').value;
    var todate = document.getElementById('todate').value;
    showDatePicker('fromdate', fromdate);
    showDatePicker('todate', todate);
</script>

