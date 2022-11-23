
//particular and tax labels , 
var label = 'required aria-required="true" maxlength="40" title="Does not accepts ` and ~ characters"  pattern="[a-zA-Z0-9]+(([\\\!\\\'\\\,\\\.\\\s\\\-\\\@\\\#\\\$\\\%\\\^\\\&\\\*\\\(\\\)\\\_\\\+\\\|\\\\\\\/\\\=\\\{\\\}\\\[\\\]\\\][a-zA-Z0-9])?[a-zA-Z0-9]*)*" ';
// , narrative  
var label1 = 'maxlength="40" title="Does not accepts ` and ~ characters" pattern="[a-zA-Z0-9]+(([\\\!\\\'\\\,\\\.\\\s\\\-\\\@\\\#\\\$\\\%\\\^\\\&\\\*\\\(\\\)\\\_\\\+\\\|\\\\\\\/\\\=\\\{\\\}\\\[\\\]\\\][a-zA-Z0-9])?[a-zA-Z0-9]*)*"';
//percentage tax
var ptax = 'maxlength="5" pattern="[0-9]+([\.][0-9]+)?" title="Accepts only numeric characters.Value less than 100"  step="0.01"';
//amount , absolute cost
var aamt = 'title="Accepts only numeric characters. Value not exceeding (&#x20B9;) 1,00,000.00" pattern="((?=.*[0-9])\\\d{1,5}(?:\\\.\\\d{1,2})?|100000.00|100000)" maxlength="9"';
//quantity , no of units
var units = 'title="Accepts only numeric characters" pattern="((?=.*[0-9])\\\d{1,5}(?:\\\.\\\d{1,2})?|100000.00|100000)" maxlength="9"';
//header
var header = 'required aria-required="true" maxlength="20" title="Does not accepts ` and ~ characters" pattern="[a-zA-Z0-9]+(([\\\!\\\'\\\,\\\.\\\s\\\-\\\@\\\#\\\$\\\%\\\^\\\&\\\*\\\(\\\)\\\_\\\+\\\|\\\\\\\/\\\=\\\{\\\}\\\[\\\]\\\][a-zA-Z0-9])?[a-zA-Z0-9]*)*"';
//duration
var dur = 'required aria-required="true" maxlength="15" title="Does not accepts ` and ~ characters" pattern="[a-zA-Z0-9]+(([\\\!\\\'\\\,\\\.\\\s\\\-\\\@\\\#\\\$\\\%\\\^\\\&\\\*\\\(\\\)\\\_\\\+\\\|\\\\\\\/\\\=\\\{\\\}\\\[\\\]\\\][a-zA-Z0-9])?[a-zA-Z0-9]*)*"';




function DateCheck()
{
    var totalcost = document.getElementById('totalcostamt').value;

    var billDate = document.getElementById('date1').value;
    var dueDate = document.getElementById('date2').value;
    var dDate = new Date(dueDate);
    var bDate = new Date(billDate);
    if (dueDate != '' && billDate != '' && bDate > dDate)
    {
        alert('Please ensure that the Due Date is greater than or equal to the Bill Date');
        document.getElementById('bsubmit').disabled = false;
        document.getElementById('bsubmit').style.backgroundColor = '#2c5294';
        return false;
    }
    else {
        if (Number(totalcost) > 1) {
            document.getElementById('bsubmit').disabled = true;
            document.getElementById('bsubmit').style.backgroundColor = 'grey';

        } else {
            alert('Please Enter atleast one Particular label');
            return false;
        }

    }
}

function SubscriptionCheck()
{

    var startDate = document.getElementById('start_date').value;
    var endDate = document.getElementById('end_date').value;
    if (endDate != '')
    {
        var sDate = new Date(startDate);
        var eDate = new Date(endDate);
        if (sDate > eDate)
        {
            alert('Please ensure that the End Date is greater than or equal to the Bill Date');
            document.getElementById('bsubmit').disabled = false;
            document.getElementById('bsubmit').style.backgroundColor = '#2c5294';
            return false;
        }
    }
    else
    {
         var endmode = document.querySelector('input[name="end_mode"]:checked').value;
        var occurrence = document.getElementById('occurrence').value;
        if (endmode == 2)
        {
            if (occurrence == '')
            {
                alert('Please ensure that the occurrence is greater than 0');
                document.getElementById('bsubmit').disabled = false;
                document.getElementById('bsubmit').style.backgroundColor = '#2c5294';
                return false;
            }
        }
    }
    
    var sbillDate = document.getElementById('start_date').value;
    var sdueDate = document.getElementById('due_datetime').value;
    var sdDate = new Date(sdueDate);
    var ssDate = new Date(sbillDate);
    if (ssDate > sdDate)
    {
        alert('Please ensure that the subscription Due Date is greater than or equal to the Bill Date');
        document.getElementById('bsubmit').disabled = false;
        document.getElementById('bsubmit').style.backgroundColor = '#2c5294';
        return false;
    }


    var totalcost = document.getElementById('totalcostamt').value;

    var billDate = document.getElementById('start_date').value;
    var dueDate = document.getElementById('due_datetime').value;
    var dDate = new Date(dueDate);
    var bDate = new Date(billDate);
    if (dueDate != '' && billDate != '' && bDate > dDate)
    {
        alert('Please ensure that the Due Date is greater than or equal to the Bill Date');
        document.getElementById('bsubmit').disabled = false;
        document.getElementById('bsubmit').style.backgroundColor = '#2c5294';
        return false;
    }
    else {
        if (Number(totalcost) > 1) {
            document.getElementById('bsubmit').disabled = true;
            document.getElementById('bsubmit').style.backgroundColor = 'grey';

        } else {
            alert('Please Enter atleast one Particular label');
            return false;
        }

    }









}


var removecount = 0;
function sameBilladdr(status)
{
    if (status == true) {
        document.getElementById("BA_9").value = document.getElementById("BA_8").value;

    }
    else
    {
        document.getElementById("BA_9").value = '';
    }
}


function showimagepreview(input) {
    if (input.files && input.files[0]) {
        var filerdr = new FileReader();
        filerdr.onload = function(e) {

            $('#imgprvw').attr('src', e.target.result);
        }
        filerdr.readAsDataURL(input.files[0]);
    }
}



$(window).load(function() {
    $("#uploadTrigger").click(function() {
        $("#uploadFile").click();
    });
});//]]>  


function removedivexist(id)
{
    var ab = 'exist' + id;

    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);
}


function removediv(id)
{
    var ab = 'pinnerDiv' + id;
    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);


}

function removenewdiv(id)
{
    var ab = 'pinnerDiv' + id;
    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);
    removecount = removecount + 1;


}

function removedivup(id)
{
    var ab = 'up' + id;
    document.getElementById('p' + id).value = "PN";
    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);


}

function removedivnu(id)
{

    var ab = 'nu' + id;

    var val = document.getElementById('p' + id).value;

    if (val == 'All')
    {
        document.getElementById('p' + id).value = "N";
    }
    else
    {
        document.getElementById('p' + id).value = "PN";
    }


    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);


}




function removedivt(id)
{
    var ab = 'innerDiv' + id;
    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);


}


function removedivupt(id)
{
    var ab = 'upt' + id;
    document.getElementById('T' + id).value = "PA";
    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);


}

function removedivnut(id)
{

    var ab = 'nut' + id;

    var val = document.getElementById('T' + id).value;

    if (val == 'All')
    {
        document.getElementById('T' + id).value = "A";
    }
    else
    {
        document.getElementById('T' + id).value = "PA";
    }


    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);

}







function addHeader() {
    numherder++;
    while (document.getElementById('exist' + numherder)) {
        numherder = numherder + 1;
    }

    var node_listleft = document.getElementsByName("leftcount");
    var node_listright = document.getElementsByName("rightcount");
    var leftcount = Number(node_listleft.length);
    var rightcount = Number(node_listright.length);
    if (Number(rightcount) < Number(leftcount)) {
        var mainDiv = document.getElementById('newHeaderright');
    }
    else
    {
        var mainDiv = document.getElementById('newHeaderleft');
    }


    var newSpan = document.createElement('div');
    newSpan.setAttribute('id', 'exist' + numherder);
    if (rightcount < leftcount) {

        newSpan.innerHTML = '<input type="hidden" name="position[]" value="R"><input name="headercolumn[]" type="text" ' + header + ' class="txtfld-h masterTooltip" value=""   title="your text goes here" /><input name="headertablesave[]" type="hidden" value="metadata"><input name="headermandatory[]" type="hidden" value="2"><input name="headercolumnposition[]" type="hidden" value="-1"><input name="headerisdelete[]" type="hidden" value="1"><input name="headerdatatype[]" type="hidden" value="text" /><input name="rightcount" type="text" class="txtfld-h2" value="text box for data entry" readonly="readonly" /><div style="margin-bottom: 20px;"> <img src="/images/minus1.gif" id="' + numherder + '" onclick="removedivexist(this.id)" style="cursor:pointer;"></div></div>';
    }
    else
    {
        newSpan.innerHTML = '<input type="hidden" name="position[]" value="L"><input name="headercolumn[]" type="text" ' + header + ' class="txtfld-h masterTooltip" value=""  title="your text goes here" /><input name="headertablesave[]" type="hidden" value="metadata"><input name="headermandatory[]" type="hidden" value="2"><input name="headercolumnposition[]" type="hidden" value="-1"><input name="headerisdelete[]" type="hidden" value="1"><input name="headerdatatype[]" type="hidden" value="text" /><input name="leftcount" type="text" class="txtfld-h2" value="text box for data entry" readonly="readonly" /><div style="margin-bottom: 20px;"> <img src="/images/minus1.gif" id="' + numherder + '" onclick="removedivexist(this.id)" style="cursor:pointer;"></div></div>';

    }

    mainDiv.appendChild(newSpan);

}

function addevent() {
    numherder++;
    while (document.getElementById('exist' + numherder)) {
        numherder = numherder + 1;
    }

    var mainDiv = document.getElementById('newevent');

    var newDiv = document.createElement('div');

    var newSpan = document.createElement('div');
    newSpan.setAttribute('id', 'exist' + numherder);
    newSpan.innerHTML = '<input type="hidden" name="position[]" value="-1" /><input type="hidden" name="is_mandatory[]" value="2" /> <input type="hidden" name="datatype[]" value="text" /><div class="profile-row"> <h1>  <input name="column[]" style="width: 180px;" required="" placeholder="Enter column name" aria-required="true" maxlength="20" pattern="[a-zA-Z0-9]+(([\!\'\,\.\s\-\@\#$\%\^\&amp;\*\(\)\_\+\|\\/\=\{\}\[\]\][a-zA-Z0-9])?[a-zA-Z0-9]*)*" type="text" class="field2"></h1><input name="columnvalue[]" type="text" class="field2" value="" required="" aria-required="true" title="" maxlength="50">&nbsp;&nbsp; <img src="/images/minus1.gif" id="' + numherder + '" onclick="removedivexist(this.id)" style="cursor:pointer;"></div>';

    mainDiv.appendChild(newSpan);

}



function Add_ParticularRow() {
    NumOfRow++;
    var mainDiv = document.getElementById('newparticular');
    var newDiv = document.createElement('div');
    newDiv.setAttribute('id', 'particular' + NumOfRow);
    var newSpan = document.createElement('span');
    newSpan.innerHTML = '<div id="add_particulars1"><div class="row_three" style="margin-bottom:-11px;"><div class=" box1" ><div class="txt">Particular label</div></div><div class=" box2"><div class="txt">Unit price</div></div><div class=" box2"><div class="txt">No of units</div></div><div class=" box2"><div class="txt">Absolute cost</div></div><div class=" box2"><div class="txt">Narrative</div></div></div><div class="row_three"><div class="row_three" style="margin:0px;"><div class=" box1"><div class="field_bg"><input name="particularname[]" type="text" class="field" id="id="PT' + NumOfRow + '"" ' + label + '  /></div></div><div class=" box2"><div class="field_bg2"></div></div><div class=" box2"><div class="field_bg2"></div></div><div class=" box2"><div class="field_bg2"></div></div><div class="box2"> <div class="field_bg2"></div></div><img src="/images/minus1.gif" id="' + NumOfRow + '" onclick="removediv(' + NumOfRow + ')" class="minus1" style="cursor:pointer;" /> </div> </div></div>';
    mainDiv.appendChild(newSpan);
}



function Add_taxRow() {
    NumOftaxRow++;
    var mainDiv = document.getElementById('newtax');
    var newDiv = document.createElement('div');
    newDiv.setAttribute('id', 'innerDiv' + NumOftaxRow);
    var newSpan = document.createElement('span');
    newSpan.innerHTML = '<div id="add_particulars1"><div class="row_three" style="margin-bottom:-11px;"><div class=" box1" ><div class="txt">Tax label</div></div><div class=" box2"><div class="txt">Tax in %</div></div><div class=" box2"><div class="txt">Applicable on (&#x20B9;)</div></div><div class=" box2"><div class="txt">Absolute cost (&#x20B9;)</div></div><div class=" box2"><div class="txt">Narrative</div></div></div><div class="row_three"><div class="row_three" style="margin:0px;"><div class=" box1"><div class="field_bg"><input name="taxx[]" type="text" class="field" id="id="TT' + NumOftaxRow + '"" ' + label + ' /></div></div><div class=" box1"><div class="field_bg"><input name="defaultValue[]" type="text" class="field" id="id="TD' + NumOftaxRow + '"" ' + ptax + ' autocomplete="off" /></div></div><div class=" box2"><div class="field_bg2">  </div></div><div class=" box2"><div class="field_bg2"> </div></div><div class="box2"> <div class="field_bg2"> </div></div><img src="/images/minus1.gif" id="' + NumOftaxRow + '" onclick="removedivt(' + NumOftaxRow + ')" class="minus1" style="cursor:pointer;" /> </div> </div></div>';
    newDiv.appendChild(newSpan);
    mainDiv.appendChild(newDiv);

}


function InvoiceParticular(a, n, u, o, l) {
    try {
        var node_listright = document.getElementsByName("countrow");
        var Numrow = Number(node_listright.length) + 1;
    } catch (o)
    {
        Numrow = 1;
    }

    while (document.getElementById('pinnerDiv' + Numrow)) {
        Numrow = Numrow + 1;
    }

    var a = "'" + a + "'";
    var n = "'" + n + "'";
    var u = "'" + u + "'";
    var o = "'" + o + "'";
    var l = "'" + l + "'";
    var mainDiv = document.getElementById('newparticular');
    var newDiv = document.createElement('div');
    newDiv.setAttribute('id', 'pinnerDiv' + Numrow);
    var newSpan = document.createElement('span');
    newSpan.innerHTML = ' <div class="row_three" "><input type="hidden" name="countrow"/><div class=" box1"><div class="txt" >Particular label</div><div class="field_bg"><input name="values[]"  type="text" class="field" ' + label + ' /><input type="hidden" name="ids[]" value="P1"  ></div></div><div class=" box2"><div class="txt">Unit price (&#x20B9;)  </div><div class="field_bg"><input name="values[]" type="text" id="unitprice' + Numrow + '" autocomplete="off" onblur="calculatecost(' + Numrow + ',' + a + ',' + n + ',' + u + ',' + o + ',' + l + ')"   class="field" ' + aamt + '/><input type="hidden" name="ids[]" value="P2"></div></div><div class=" box2"><div class="txt">No of units </div><div class="field_bg"><input name="values[]" autocomplete="off" id="unitnumber' + Numrow + '"  type="text" onblur="calculatecost(' + Numrow + ',' + a + ',' + n + ',' + u + ',' + o + ',' + l + ')" class="field" ' + aamt + ' /><input type="hidden" name="ids[]" value="P3"></div></div><div class=" box2"><div class="txt">Absolute cost (&#x20B9;)  </div><div class="field_bg"><input name="values[]" onkeydown="return false;" tabindex="-1" id="cost' + Numrow + '"  autocomplete="off" style="background-color: #ccc;"    type="text" class="field" ' + aamt + ' /><input type="hidden" name="ids[]" value="P4"></div></div><div class=" box2"><div class="txt">Narrative</div><div class="field_bg"><input name="values[]" autocomplete="off"  type="text" class="field" id="textfield7" ' + label1 + ' /><input type="hidden" name="ids[]" value="P5"></div></div><img src="/images/minus1.gif" id="' + Numrow + '" onclick="removenewdiv(' + Numrow + ');calculatecost(' + Numrow + ',' + a + ',' + n + ',' + u + ',' + o + ',' + l + ');" class="minus1" style="cursor:pointer;" /></div>';
    newDiv.appendChild(newSpan);
    mainDiv.appendChild(newDiv);
}

function InvoiceTax(a, n, u, o, l) {
    try {
        var node_listright = document.getElementsByName("countrowtax");
        var Numrowtax = Number(node_listright.length) + 1;
    } catch (o)
    {
        Numrowtax = 1;
    }

    while (document.getElementById('pinnerDiv' + Numrowtax)) {
        Numrowtax = Numrowtax + 1;
    }
    var a = "'" + a + "'";
    var n = "'" + n + "'";
    var u = "'" + u + "'";
    var o = "'" + o + "'";
    var l = "'" + l + "'";
    var mainDiv = document.getElementById('newtax');
    var newDiv = document.createElement('div');
    newDiv.setAttribute('id', 'pinnerDiv' + Numrowtax);
    var newSpan = document.createElement('span');
    newSpan.innerHTML = ' <div class="row_three" "><input type="hidden" name="countrowtax"/><div class=" box1"><div class="txt" >Tax label</div><div class="field_bg"><input name="values[]" ' + label + ' type="text" class="field"/><input type="hidden" name="ids[]" value="T1"  ></div></div><div class=" box2"><div class="txt">Tax in %  </div><div class="field_bg"><input name="values[]" type="number" step="0.01" max="100" id="taxin' + Numrowtax + '" autocomplete="off" onblur="calculatetax(' + Numrowtax + ',' + a + ',' + n + ',' + u + ',' + o + ',' + l + ');"   class="field"  /><input type="hidden" name="ids[]" value="T2"></div></div><div class=" box2"><div class="txt">Applicable on (&#x20B9;)</div><div class="field_bg"><input name="values[]" autocomplete="off" id="applicableamount' + Numrowtax + '"  type="text" onblur="calculatetax(' + Numrowtax + ',' + a + ',' + n + ',' + u + ',' + o + ',' + l + ');" class="field" ' + aamt + ' /><input type="hidden" name="ids[]" value="T3"></div></div><div class=" box2"><div class="txt">Absolute cost (&#x20B9;)  </div><div class="field_bg"><input name="values[]" onkeydown="return false;" id="totaltax' + Numrowtax + '" tabindex="-1"  autocomplete="off" style="background-color: #ccc;"    type="text" class="field" ' + aamt + ' /><input type="hidden" name="ids[]" value="T4"></div></div><div class=" box2"><div class="txt">Narrative</div><div class="field_bg"><input name="values[]" autocomplete="off"  type="text" class="field" id="textfield7" ' + label1 + ' /><input type="hidden" name="ids[]" value="T5"></div></div><img src="/images/minus1.gif" id="' + Numrowtax + '" onclick="removenewdiv(' + Numrowtax + ');calculatetax(' + Numrowtax + ',' + a + ',' + n + ',' + u + ',' + o + ',' + l + ');" class="minus1" style="cursor:pointer;" /></div>';
    newDiv.appendChild(newSpan);
    mainDiv.appendChild(newDiv);
}



function showimagepreview(input) {
    if (input.files && input.files[0]) {
        var filerdr = new FileReader();
        filerdr.onload = function(e) {
            $('#imgprvw').attr('src', e.target.result);
        }
        filerdr.readAsDataURL(input.files[0]);
    }
}


//School add particular 

function School_addParticular() {
    NumOfRow++;
    var mainDiv = document.getElementById('newparticular');
    var newDiv = document.createElement('div');
    newDiv.setAttribute('id', 'pinnerDiv' + NumOfRow);
    var newSpan = document.createElement('span');
    newSpan.innerHTML = '<div id="add_particulars1"><div class="row_three" style="margin-bottom:-11px;"><div class=" box1" ><div class="txt">Fee type</div></div><div class=" box2"><div class="txt">Duration</div></div><div class=" box2"><div class="txt">Amount (&#x20B9;)</div></div><div class=" box2"><div class="txt">Narrative</div></div></div><div class="row_three"><div class="row_three" style="margin:0px;"><div class=" box1"><div class="field_bg"><input name="particularname[]" type="text" class="field" id="id="PT' + NumOfRow + '"" ' + label + '" /></div></div><div class=" box2"><div class="field_bg2"></div></div><div class=" box2"><div class="field_bg2"></div></div><div class="box2"> <div class="field_bg2"></div></div><img src="/images/minus1.gif" id="' + NumOfRow + '" onclick="removediv(' + NumOfRow + ')" class="minus1" style="cursor:pointer;" /> </div> </div></div>';
    newDiv.appendChild(newSpan);
    mainDiv.appendChild(newDiv);
}

//Hotel add particular 

function AddHotelParticularRow() {
    NumOfRow++;
    var mainDiv = document.getElementById('newparticular');
    var newDiv = document.createElement('div');
    newDiv.setAttribute('id', 'pinnerDiv' + NumOfRow);
    var newSpan = document.createElement('span');
    newSpan.innerHTML = '<div id="add_particulars1"><div class="row_three" style="margin-bottom:-11px;"><div class=" box1" ><div class="txt">Product name</div></div><div class=" box2"><div class="txt">Quantity</div></div><div class=" box2"><div class="txt">Unit cost</div></div><div class=" box2"><div class="txt">Absolute cost</div></div></div><div class="row_three"><div class="row_three" style="margin:0px;"><div class=" box1"><div class="field_bg"><input name="particularname[]" type="text" class="field" id="id="PT' + NumOfRow + '"" ' + label + ' /></div></div><div class=" box2"><div class="field_bg2"></div></div><div class=" box2"><div class="field_bg2"></div></div><div class=" box2"><div class="field_bg2"></div></div><img src="/images/minus1.gif" id="' + NumOfRow + '" onclick="removediv(' + NumOfRow + ')" class="minus1" style="cursor:pointer;" /> </div> </div></div>';

    newDiv.appendChild(newSpan);

    mainDiv.appendChild(newDiv);
}


function HotelParticular(a, n, u, o, l) {
    try {
        var node_listright = document.getElementsByName("countrow");
        var Numrow = Number(node_listright.length) + 1;
    } catch (o)
    {
        Numrow = 1;
    }
    while (document.getElementById('pinnerDiv' + Numrow)) {
        Numrow = Numrow + 1;
    }
    var a = "'" + a + "'";
    var n = "'" + n + "'";
    var u = "'" + u + "'";
    var o = "'" + o + "'";
    var l = "'" + l + "'";

    var mainDiv = document.getElementById('newparticular');
    var newDiv = document.createElement('div');
    newDiv.setAttribute('id', 'pinnerDiv' + Numrow);
    var newSpan = document.createElement('span');
    newSpan.innerHTML = ' <div class="row_three" ><input type="hidden" name="countrow"/><div class=" box1"><div class="txt" >Product name</div><div class="field_bg"><input name="values[]"  type="text" class="field" ' + label + ' /><input type="hidden" name="ids[]" value="P1"></div></div><div class=" box2"><div class="txt">Quantity </div><div class="field_bg"><input name="values[]" autocomplete="off" id="unitnumber' + Numrow + '"  type="text" onblur="calculatecost(' + Numrow + ',' + a + ',' + n + ',' + u + ',' + o + ',' + l + ')" class="field" ' + aamt + '  /><input type="hidden" name="ids[]" value="P2"></div></div><div class=" box2"><div class="txt">Unit cost (&#x20B9;) </div><div class="field_bg"><input name="values[]" type="text" id="unitprice' + Numrow + '" autocomplete="off" onblur="calculatecost(' + Numrow + ',' + a + ',' + n + ',' + u + ',' + o + ',' + l + ')"   class="field" ' + aamt + '  /><input type="hidden" name="ids[]" value="P3"></div></div><div class=" box2"><div class="txt">Absolute cost (&#x20B9;)  </div><div class="field_bg"><input name="values[]" onkeydown="return false;" tabindex="-1" id="cost' + Numrow + '"  autocomplete="off" style="background-color: #ccc;"    type="text" class="field" ' + aamt + ' /><input type="hidden" name="ids[]" value="P4"></div></div><img src="/images/minus1.gif" id="' + Numrow + '" onclick="removenewdiv(' + Numrow + ');calculatecost(' + Numrow + ',' + a + ',' + n + ',' + u + ',' + o + ',' + l + ');" class="minus1" style="cursor:pointer;" /></div>';
    newDiv.appendChild(newSpan);
    mainDiv.appendChild(newDiv);

}


function SchoolInvoiceParticular(a, n, u, o, l) {
    try {
        var node_listright = document.getElementsByName("countrow");
        var Numrow = Number(node_listright.length) + 1;

    } catch (o)
    {
        Numrow = 1;
    }
    while (document.getElementById('pinnerDiv' + Numrow)) {
        Numrow = Numrow + 1;
    }
    var a = "'" + a + "'";
    var n = "'" + n + "'";
    var u = "'" + u + "'";
    var o = "'" + o + "'";
    var l = "'" + l + "'";

    var mainDiv = document.getElementById('newparticular');
    var newDiv = document.createElement('div');
    newDiv.setAttribute('id', 'pinnerDiv' + Numrow);
    var newSpan = document.createElement('span');
    newSpan.innerHTML = ' <div class="row_three" ><input type="hidden" name="countrow"/><div class=" box1"><div class="txt" >Particular label</div><div class="field_bg"><input name="values[]"  type="text" class="field" ' + label + '/><input type="hidden" name="ids[]" value="P1"></div></div><div class=" box2"><div class="txt">Duration </div><div class="field_bg"><input name="values[]" autocomplete="off" id="unitnumber' + Numrow + '"  type="text" class="field" ' + dur + ' /><input type="hidden" name="ids[]" value="P2"></div></div><div class=" box2"><div class="txt">Amount (&#x20B9;) </div><div class="field_bg"><input name="values[]" type="text" id="cost' + Numrow + '" autocomplete="off" onblur="calculateSchoolAmount(' + a + ',' + n + ',' + u + ',' + o + ',' + l + ')"   class="field" ' + aamt + '  /><input type="hidden" name="ids[]" value="P3"></div></div><div class=" box2"><div class="txt">Narrative  </div><div class="field_bg"><input name="values[]"  autocomplete="off"  type="text" class="field" ' + label1 + ' /><input type="hidden" name="ids[]" value="P4"></div></div><img src="/images/minus1.gif" id="' + Numrow + '" onclick="removenewdiv(' + Numrow + ');calculateSchoolAmount(' + a + ',' + n + ',' + u + ',' + o + ',' + l + ');" class="minus1" style="cursor:pointer;" /></div>';
    newDiv.appendChild(newSpan);
    mainDiv.appendChild(newDiv);

}

function removesupplier(id)
{
    removediv(id + 4544);
    document.getElementById("spid" + id).checked = false;
}

function AddsupplierRow(id) {
    if ($('#spid' + id).is(':checked')) {
        var name = document.getElementById('spname' + id).innerHTML;
        var contact = document.getElementById('spcontact' + id).innerHTML;
        var mobile = document.getElementById('spmobile' + id).innerHTML;
        var sptype = document.getElementById('sptype' + id).innerHTML;
        var spemail = document.getElementById('spemail' + id).innerHTML;

        NumOfRow = id + 4544;
        var mainDiv = document.getElementById('newsupplier');
        var newDiv = document.createElement('div');
        newDiv.setAttribute('id', 'pinnerDiv' + NumOfRow);
        var newSpan = document.createElement('span');
        newSpan.innerHTML = '<div id="add_supplier1"><input type="hidden" name="supplier[]" value="' + id + '"> <div class="row_three" style="margin-bottom:-11px;"><div class=" box2"><div class="txt">Supplier company name</div></div><div class=" box2"><div class="txt">Contact person name</div></div><div class=" box2"><div class="txt">Contact email</div></div><div class=" box2"><div class="txt">Contact mobile</div></div><div class=" box2"><div class="txt">Industry type</div></div></div><div class="row_three"><div class="row_three" style="margin:0px;"><div class=" box2"><div class="field_bg2">' + name + '</div></div><div class=" box2"><div class="field_bg2">' + contact + '</div></div><div class=" box2"><div class="field_bg2">' + spemail + '</div></div><div class=" box2"><div class="field_bg2">' + mobile + '</div></div><div class=" box2"><div class="field_bg2">' + sptype + '</div></div><img src="/images/minus1.gif" id="' + id + '" onclick="removesupplier(this.id)" class="minus1" style="cursor:pointer;" /> </div> </div></div>';

        newDiv.appendChild(newSpan);

        mainDiv.appendChild(newDiv);
    }
    else
    {
        removediv(id + 4544);
    }
}

function respond_changes(idd)
{

    if (idd == 4)
    {

        document.getElementById('bank_name').style.display = 'none';
        document.getElementById('bank_transaction_no').style.display = 'none';
        document.getElementById('cheque_no').style.display = 'none';
        document.getElementById('cash_paid_to').style.display = 'none';
        document.getElementById('date').style.display = 'none';
        document.getElementById('amount').style.display = 'none';


    }
    else
    {

        document.getElementById('bank_transaction_no').style.display = 'none';
        document.getElementById('cheque_no').style.display = 'none';
        document.getElementById('cash_paid_to').style.display = 'none';
        document.getElementById('bank_name').style.display = 'block';
        document.getElementById('date').style.display = 'block';
        document.getElementById('amount').style.display = 'block';

        if (idd == 2)
        {
            document.getElementById('cheque_no').style.display = 'block';
            document.getElementById('bank_name').value = 'My Bank Name';
        }
        else if (idd == 1)
        {
            document.getElementById('bank_transaction_no').style.display = 'block';
            document.getElementById('bank_name').value = 'My Bank Name';
        }
        else if (idd == 3)
        {
            document.getElementById('cash_paid_to').style.display = 'block';
            document.getElementById('bank_name').style.display = 'none';
        }
    }

}

