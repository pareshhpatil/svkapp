<script type="text/javascript">
    AmCharts.makeChart("chartdiv",
            {
                "type": "serial",
                "categoryField": "date",
                "dataDateFormat": "YYYY-MM-DD",
                "colors": [
                            "#275770",
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
					"chartCursor": {
						"animationDuration": 0
					},
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
					"valueAxes": [],
					"allLabels": [],
					"balloon": {},
					"legend": {
						"useGraphSettings": true
					},
					"titles": [],
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


         <div id="chartdiv"  style="width: 98%; height: 300px; background-color: #FFFFFF;" ></div>

        
      