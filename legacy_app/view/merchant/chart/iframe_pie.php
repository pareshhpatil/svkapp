       <script type="text/javascript">
    AmCharts.makeChart("chartdiv",
            {
               "type": "pie",
                "angle": 12,
                "balloonText": "",
                "depth3D": 15,
                "labelRadiusField": "column-1",
                "labelText": "",
                 "baseColor": "#275770",
                "startAngle": 0,
                "alphaField": "column-1",
                "descriptionField": "column-1",
                "hideLabelsPercent": 25,
                "pulledField": "column-1",
                "titleField": "category",
                "valueField": "column-1",
                "visibleInLegendField": "category",
                "allLabels": [],
                "balloon": {},
                "legend": {
                    "align": "center",
                    "markerType": "circle"
                },
                "titles": [],
                "dataProvider": [
                <?php
                $comma = "";
                foreach ($this->reportlist as $value) {
                    echo $comma . '{ "category": "' . $value['name'] . '",
                                            "column-1": ' . $value['value'] . ' }';
                    $comma = ",";
                }
                ?>
                ]
            }
         );
</script>
<?php if(empty($this->reportlist)){ echo '<div align="center" style="margin-top:50px;"><h5>No records found</h5></div>'; } else{?>
        <div id="chartdiv"  style="width: 98%; height: 300px; background-color: #FFFFFF;" ></div>
<?php } ?>
       