function calculatecost(t, a, n, u, o, l) {
    try {
        var m = document.getElementById("unitprice" + t).value, d = document.getElementById("unitnumber" + t).value;

        if ("" == d && (document.getElementById("unitnumber" + t).value = "1", d = 1), m > 0) {
            var r = m * d;
            document.getElementById("cost" + t).value = r > 0 ? Math.round(100 * r) / 100 : 0;
        } else {
            document.getElementById("cost" + t).value = '0';
        }
    } catch (o) {
    }
    var node_listleft = document.getElementsByName("countrowtax");
    var taxcount = Number(node_listleft.length);
    var node_listright = document.getElementsByName("countrow");
    var particularcount = Number(node_listright.length) + 1 + removecount + taxcount;
    for (var c = 0, g = 0, v = 1; particularcount > v; v++)
        try {
            c += Number(document.getElementById("cost" + v).value), g += Number(document.getElementById("unitnumber" + v).value);
        } catch (o) {
        }
    document.getElementById("totalunit").innerHTML = Math.round(100 * g) / 100, document.getElementById("totalcost").innerHTML = Math.round(100 * c) / 100, document.getElementById("totalcostamt").value = Math.round(100 * c) / 100, calculategrandtotal(a, n, u, o, l)


}
function calculatetax(t, n, u, o, l, m) {
    try {
        var d = document.getElementById("taxin" + t).value, r = document.getElementById("applicableamount" + t).value;
        if ("" == r && (r = 0), d > 0) {
            totalcost = d * r / 100, document.getElementById("totaltax" + t).value = totalcost > 0 ? Math.round(100 * totalcost) / 100 : 0;
        } else {
            document.getElementById("totaltax" + t).value = '0';
        }
    } catch (o) {
    }
    var node_listright = document.getElementsByName("countrowtax");
    var taxcount = Number(node_listright.length);
    var node_listleft = document.getElementsByName("countrow");
    var particularcount = Number(node_listleft.length);
    var taxcount = taxcount + 1 + particularcount + removecount;
    for (var c = 0, g = 0, v = 1; taxcount > v; v++)
        try {
            c += Number(document.getElementById("totaltax" + v).value);
        } catch (o) {
        }
    document.getElementById("totaltaxcost").innerHTML = Math.round(100 * c) / 100, document.getElementById("totaltaxamt").value = Math.round(100 * c) / 100, calculategrandtotal(n, u, o, l, m)

}
function calculategrandtotal(t, e, a, n, u) {
    try {
        previous_dues = document.getElementById("previous_due").value;
    } catch (o) {
        previous_dues = 0
    }
    try {
        last_payment = document.getElementById("last_payment").value;
    } catch (o) {
        last_payment = 0;
    }
    try {
        adjustment = document.getElementById("adjustment").value;
    } catch (o) {
        adjustment = 0;
    }



    try {
        document.getElementById("previous_duesclone").value = previous_dues;
    } catch (o) {
    }

    previous_dues = previous_dues - Number(last_payment) - Number(adjustment);
    try {
        amount = document.getElementById("totalcost").innerHTML;
    } catch (o) {
        amount = 0
    }
    try {
        tax_amount = document.getElementById("totaltaxcost").innerHTML;
    } catch (o) {
        tax_amount = 0
    }
    if (previous_dues >= 0) {
        grandtotal = Number(amount) + Number(tax_amount) + Number(previous_dues);
    } else {
        grandtotal = Number(amount) + Number(tax_amount);
        try
        {
            if (previous_dues < 0) {
                grandtotal = grandtotal + Number(previous_dues);
            }
        }
        catch (o) {
        }
    }


    var l = 0;
    if (grandtotal > 0) {
        var m = 0;
        m = "P" == t ? e * grandtotal / 100 : e;
        var d = 0;
        "P" == a ? (l = Number(grandtotal) + Number(m), d = n * l / 100) : d = n, stapply = u * d / 100, document.getElementById("totalamount").value = Math.round(100 * grandtotal) / 100, grandtotal = Number(grandtotal) + Number(m) + Number(d) + Number(stapply), document.getElementById("grandtotal").value = Math.round(100 * grandtotal) / 100
    } else {
        document.getElementById("grandtotal").value = 0;
        document.getElementById("totalamount").value = 0;
    }
}



function calculateEventgrandtotal(t, e, a, n, u) {
   
    try {
        grandtotal = document.getElementById("unitcost").value;
    } catch (o) {
        grandtotal = 0
    }
    
    var l = 0;
    if (grandtotal > 0) {
        var m = 0;
        m = "P" == t ? e * grandtotal / 100 : e;
        var d = 0;
        "P" == a ? (l = Number(grandtotal) + Number(m), d = n * l / 100) : d = n, stapply = u * d / 100,  grandtotal = Number(grandtotal) + Number(m) + Number(d) + Number(stapply), document.getElementById("grandtotal").value = Math.round(100 * grandtotal) / 100
    } else {
        document.getElementById("grandtotal").value = 0;
    }
}

function calculateSchoolAmount(a, n, u, o, l) {
    var node_listleft = document.getElementsByName("countrowtax");
    var taxcount = Number(node_listleft.length);
    var node_listright = document.getElementsByName("countrow");
    var particularcount = Number(node_listright.length) + 1 + removecount + taxcount;
    for (var c = 0, g = 0, v = 1; particularcount > v; v++)
    {
        try {
            amount = Number(document.getElementById("cost" + v).value);
        } catch (o) {
            amount = 0;
        }
        c += amount;
    }
    document.getElementById("totalcost").innerHTML = Math.round(100 * c) / 100;
    document.getElementById("totalcostamt").value = Math.round(100 * c) / 100;
    calculategrandtotal(a, n, u, o, l);

}