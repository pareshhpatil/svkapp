function projectSelected(project_id) {
    data = '';

    csi_codes='';
    $.ajax({
        type: 'GET',
        url: '/merchant/code/getlist/' + project_id,
        data: data,
        success: function (data) {
          
                try {
                    csi_codes=data;
                } catch (e) { }
            
        }
    });

    $.ajax({
        type: 'GET',
        url: '/merchant/contract/getProjectDetails/' + project_id,
        data: data,
        success: function (data) {
            if (data.length > 0) {
                document.getElementById('project_name').value = data[0].project_name;
                if (data[0].company_name == null || data[0].company_name == '') {
                    document.getElementById('customer_code').value = data[0].customer_id
                } else {
                    document.getElementById('customer_code').value = data[0].customer_company_code
                }
                document.getElementById('customer_name').value = data[0].name;
                document.getElementById('customer_email').value = data[0].email;
                document.getElementById('customer_number').value = data[0].mobile;
                try {
                  //  document.getElementById('project1').value = data[0].project_id;
                    document.getElementById('_project_id').value = project_id;
                } catch (e) { }
            }
        }
    });

    
}