$('#gstin_list').select2({
  selectOnClose: true
});

$('#gstin').select2({
  selectOnClose: true
});
$('#recon_status_filter').select2({
  selectOnClose: true,
  templateResult: formatState
});

function formatState(state) {
  if (!state.id) {
    return state.text;
  }

  if (state.id == 'Missing in vendor GST filing') {
    var desc = 'Data unavailable on GST portal';
  } else if (state.id == 'Matched') {
    var desc = 'Data is same on both ends';
  } else if (state.id == 'Reconciled') {
    var desc = 'Data marked resolved/processed';
  } else if (state.id == 'Missing in my data') {
    var desc = 'Data unavailable in expenses';
  } else if (state.id == 'Mismatch in values') {
    var desc = 'Conflict in values';
  } else if (state.id == 'all') {
    var desc = 'All available data';
  } else if (state.id == 'Pending') {
    var desc = 'Mark entry as pending i.e. clarification required';
  }

  var $state = $(
    '<div><b>' + state.text + '</b><p style="margin-bottom: 0px;"> ' + desc + '</p>'
  );
  return $state;
};

function createJob() {
  event.preventDefault();
  var form_data = $("#form-id").serialize();
  $.ajax({
    type: 'POST',
    url: '/merchant/gst/reconciliation/validateConnection',
    headers: {
      'X-CSRF-TOKEN': document.getElementById("token").value
    },
    data: form_data,
    success: function (response) {
      if (response.response == 1) {
        $.ajax({
          type: 'POST',
          url: '/merchant/gst/reconciliation/CreateJob',
          headers: {
            'X-CSRF-TOKEN': document.getElementById("token").value
          },
          data: form_data,
          success: function (reponseReturn) {
            var obj = JSON.parse(reponseReturn);
            if (obj == 1) {
              window.location.reload();
            }
            if (obj == 4) {
              var x2 = document.getElementById("month_error");
              x2.style.display = "inline-block";
            }
            if (obj == 5) {
              var x2 = document.getElementById("expense_error");
              x2.style.display = "inline-block";
            }
            return false;
          }

        });
      } else {
        //error
        var x2 = document.getElementById("conn_error");
        x2.style.display = "inline-block";
      }
    }

  });
}

function deleteRowbyJobID(job_id) {
  var data = [];
  $.ajax({
    type: 'POST',
    url: '/merchant/gst/reconciliation/delete/' + job_id,
    headers: {
      'X-CSRF-TOKEN': document.getElementById("token").value
    },
    data: data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj == 1) {
        window.location.reload();
      }
      return false;
    }

  });
}

function checkbetweenStatus() {
  var dropdown_value = document.getElementById("between-dropdown").value;
  var x2 = document.getElementById("not_between");
  var x = document.getElementById("between");

  if (dropdown_value == 'between') {
    x.style.display = "inline-block";
    x2.style.display = "none";
  } else {
    x.style.display = "none";
    x2.style.display = "inline-block";
  }

}

function sortTaxableDiff(type) {
  document.getElementById("taxable_difference_sort").value = type;
  document.getElementById('sort-form').submit();
}

function nextpage(pageNumber) {
  document.getElementById("page_limit").value = parseInt(pageNumber) + 1;
  document.getElementById('sort-form').submit();
}

function prevpage(pageNumber) {
  document.getElementById("page_limit").value = parseInt(pageNumber) - 1;
  document.getElementById('sort-form').submit();
}

function callSidePanel(data) {
  if (data.type != '') {
    document.getElementById("panelWrapId").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
    document.getElementById("panelWrapId").style.transform = "translateX(0%)";
    document.getElementById("close_tab").style.display = "block";
    $.ajax({
      type: "POST",
      url: '/merchant/gst/reconciliation/actionbar',
      data: data,
      datatype: 'html',
      success: function (response) {

        $("#integration_view_ajax").html(response);
        //SubscriptionTableAdvanced.init();
      },
      error: function () { },
    });
  }
  return false;
}

function closeSidePanel() {
  document.getElementById("panelWrapId").style.boxShadow = "none";
  document.getElementById("panelWrapId").style.transform = "translateX(100%)";
  document.getElementById("close_tab").style.display = "none";
  return false;
}

function actionBarClicked(type, id = null, job_id = null) {
  var token = document.getElementById("token").value;

  var sList = "";
  $('input[type=checkbox]').each(function () {
    if (this.checked) {
      var sThisVal = this.value
      sList += (sList == "" ? sThisVal : "," + sThisVal);
    }
  });

  if (type == 'recon') {
    var recon_status = document.getElementById("recon_status").value;

    data = {
      list: sList,
      type: "recon_status",
      value: recon_status,
      _token: token
    }

  } else if (type == 'vendor') {

    data = {
      list: sList,
      type: "vendor",
      _token: token
    }

  } else if (type == 'update_data') {

    data = {
      list: sList,
      type: "update_data",
      _token: token
    }

  } else if (type == 'view_invoice') {
    data = {
      list: sList,
      type: "view_invoice",
      row_id: id,
      job_id: job_id,
      _token: token
    }
    var x = document.getElementById("stickyActionBar");
    x.style.display = "none";
    callSidePanel(data);
  }

  $.ajax({
    type: 'POST',
    url: '/merchant/gst/reconciliation/actionbar',
    data: data,
    success: function (response) {
      if (response > 0) {
        window.location.reload();
      }
    }
  });
}

function showHideActionBar() {
  var x = document.getElementById("stickyActionBar");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    var sList = "";
    $('input[type=checkbox]').each(function () {
      if (this.checked) {
        var sThisVal = (this.checked ? "1" : "0");
        sList += (sList == "" ? sThisVal : "," + sThisVal);
      }

    });
    const string = "1";
    if (!sList.includes(string)) {
      x.style.display = "none";
    }
  }
}

function setReconStatus(type) {
  document.getElementById("recon_status").value = type;
  actionBarClicked('recon')
}