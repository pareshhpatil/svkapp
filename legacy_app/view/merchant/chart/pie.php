

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
<?php
$comma = "";
foreach ($this->reportlist as $value) {
    echo $comma . '{ "category": "' . $value['name'] . '",
                                     "value": ' . $value['value'] . ' }';
    $comma = ",";
}
?>
                        ]
                    }
            );
        </script>
        <?php if (!empty($this->reportlist)) { ?>
            <div id="chartdiv"  style="width: 100%; height: 434px; background-color: #FFFFFF;" ></div>
        <?php } ?>

    </div>
    <?php
    if (empty($this->reportlist)) {
        echo '<br><br><br><div align="center" ><h5>No records found</h5></div>';
    }
    ?>
</div>







<script>
    var fromdate = document.getElementById('fromdate').value;
    var todate = document.getElementById('todate').value;
    showDatePicker('fromdate', fromdate);
    showDatePicker('todate', todate);
</script>
