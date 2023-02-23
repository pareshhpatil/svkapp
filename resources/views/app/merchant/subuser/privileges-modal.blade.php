<style>
    .privileges-access-item {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        margin: 10px 0;
        border-radius: 4px;
        background: #FFFFFF;
        border: 1px solid #FFFFFF;
        box-shadow: 0 3px 3px rgb(0 0 0 / 12%);
    }

    .privileges-access-item-title, .privileges-form label {
        color: #394242;
    }

    .privileges-access-dropdown {
        padding: 5px 10px;
        color: #394242;
    }

    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 50% !important;
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 100;
    }

    .panel {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        color: #394242;
        overflow-y: scroll;
        overflow-x: hidden;
        padding: 1em;
        box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
        margin-bottom: 0;
    }

    .remove {
        padding: 4px 3px;
        cursor: pointer;
        float: left;
        position: relative;
        top: 0;
        color: #fff;
        right: 25px;
        z-index: 99999;
    }

    .remove:hover {
        color: #FFF;
    }

    .remove i {
        font-size: 19px !important;
    }

    .subscription-info i {
        font-size: 22px !important;
    }

    .subscription-info h3 {
        text-align: center;
        color: #000;
        margin-bottom: 2px !important;
    }

    .subscription-info h2 {
        font-weight: 600;
        margin-bottom: 0 !important;
        margin-top: 5px !important;
        text-align: center;
    }

    .select2-container {
        z-index: 999;
    }

    .form .form-actions {
        background: transparent;
        padding: 20px 0;
        margin-top: 30px;
    }

    /*.panel-footer .btn {*/
    /*    font-size: 16px;*/
    /*    padding: 10px;*/
    /*    width: 100%;*/
    /*}*/

    /*.panel-footer .save-btn {*/
    /*    background-color: #3E4AA3;*/
    /*    color: #fff;*/
    /*}*/

    /*.panel-footer .save-btn:hover {*/
    /*    color: #fff;*/
    /*}*/

    .select2-selection__rendered {
        min-height: 32px;
    }

    @media (max-width: 767px) {
        .panel-wrap {
            /* width: 23em; */
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            position: fixed;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }
</style>
<div class="panel-wrap" id="panelWrapPrivileges">
        <div class="panel">
            <div>
                <h3 class="modal-title">
                    Set Privileges
                    <a class="close " data-toggle="modal"  onclick="closePrivilegesDrawer()">
                        <button type="button" class="close" aria-hidden="true"></button>
                    </a>
                </h3>

            </div>

            <div style="margin-top: 30px;">
                <ul id="privileges-tab" class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab1" data-toggle="tab" class="step" aria-expanded="true">
                            <span class="desc">Customer</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab" class="step" aria-expanded="true">
                            <span class="desc">Project</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab3" data-toggle="tab" class="step" aria-expanded="true">
                            <span class="desc">Contract</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab4" data-toggle="tab" class="step" aria-expanded="true">
                            <span class="desc">Invoice</span>
                        </a>
                    </li>
                    <li>
                        <a href="#tab5" data-toggle="tab" class="step" aria-expanded="true">
                            <span class="desc">Change Order</span>
                        </a>
                    </li>
                </ul>

                <form method="post" action="/merchant/subusers/privileges" class="privileges-form form" onsubmit="loader();" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" class="panel-user-id">
                    <div class="tab-content" style="margin-top: 30px">
                        <div class="tab-pane active" id="tab1">
                            <div style="border-radius: 4px;border: 1px solid #e5e5e5;padding: 20px;">
                                <div class="form-group">
                                    <label for="select-customer">Select Customer</label>
                                    <select class="form-control select2-customer">
                                    </select>
                                    <input type="hidden" name="customers_privileges">
                                </div>
                                <div class="customer-array"></div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn cancel-btn" onclick="closePrivilegesDrawer()">Cancel</button>
                                                <button type="button" class="btn blue save-btn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <div style="border-radius: 4px;border: 1px solid #e5e5e5;padding: 20px;">
                                <div class="form-group">
                                    <label for="select-project" style="display: block">Select Project</label>
                                    <select class="form-control select2-project" style="width: 100%; display: block">
                                    </select>
                                    <input type="hidden" name="projects_privileges">
                                </div>
                                <div class="project-array"></div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn cancel-btn" onclick="closePrivilegesDrawer()">Cancel</button>
                                                <button type="button" class="btn blue save-btn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab3">
                            <div style="border-radius: 4px;border: 1px solid #e5e5e5;padding: 20px;">
                                <div class="form-group">
                                    <label for="select-customer" style="display: block">Select Contract</label>
                                    <select class="form-control select2-contract" style="width: 100%; display: block">
                                    </select>
                                    <input type="hidden" name="contracts_privileges">
                                </div>
                                <div class="contract-array"></div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn cancel-btn" onclick="closePrivilegesDrawer()">Cancel</button>
                                                <button type="button" class="btn blue save-btn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab4">
                            <div style="border-radius: 4px;border: 1px solid #e5e5e5;padding: 20px;">
                                <div class="form-group">
                                    <label for="select-customer" style="display: block">Select Invoice</label>
                                    <select class="form-control select2-invoice" style="width: 100%; display: block">
                                    </select>
                                    <input type="hidden" name="invoices_privileges">
                                </div>
                                <div class="invoice-array"></div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn cancel-btn" onclick="closePrivilegesDrawer()">Cancel</button>
                                                <button type="button" class="btn blue save-btn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab5">
                            <div style="border-radius: 4px;border: 1px solid #e5e5e5;padding: 20px;">
                                <div class="form-group">
                                    <label for="select-customer" style="display: block">Select Change Order</label>
                                    <select class="form-control select2-change-order" style="width: 100%; display: block">
                                    </select>
                                    <input type="hidden" name="change_orders_privileges">
                                </div>
                                <div class="change-order-array"></div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <button type="button" class="btn cancel-btn" onclick="closePrivilegesDrawer()">Cancel</button>
                                                <button type="button" class="btn blue save-btn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function closePrivilegesDrawer() {
            document.getElementById("panelWrapPrivileges").style.boxShadow = "none";
            document.getElementById("panelWrapPrivileges").style.transform = "translateX(100%)";
        }

        $(document).ready(function() {
            let privilegesFormWrap = $('.privileges-form');
            let select2Customer = $('.select2-customer');
            let select2Project = $('.select2-project');
            let select2Contract = $('.select2-contract');
            let select2Invoice = $('.select2-invoice');
            let select2ChangeOrder = $('.select2-change-order');
            let customerArrayHTML = $('.customer-array');
            let projectArrayHTML = $('.project-array');
            let contractArrayHTML = $('.contract-array');
            let invoiceArrayHTML = $('.invoice-array');
            let changeOrderArrayHTML = $('.change-order-array');
            let customerValArr = [];
            let projectValArr = [];
            let contractValArr = [];
            let invoiceValArr = [];
            let changeOrderValArr = [];
            let userID;

            $(document).on("click",".open-privileges-drawer-btn",function() {
                let panelWrap =  document.getElementById("panelWrapPrivileges");
                userID = $(this).attr("data-user-id");
                panelWrap.style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
                panelWrap.style.transform = "translateX(0%)";
                panelWrap.getElementsByClassName('panel-user-id')[0].value = userID;

                fetch(`/merchant/subusers/privileges/${userID}`)
                    .then((response) => response.json())
                    .then((data) => {
                        let customerPrivilegesData = data.customers_privileges;
                        let projectPrivilegesData = data.projects_privileges;
                        let contractsPrivilegesData = data.contracts_privileges;
                        let invoicePrivilegesData = data.invoices_privileges;
                        let changeOrderPrivilegesData = data.change_orders_privileges;

                        customerArrayHTML.empty();
                        customerPrivilegesData.forEach((el, i) => {
                            let html = accessHTML(el.type_label, el.access, i, 'customer');
                            customerValArr.push({
                                value: el.type_id,
                                label: el.type_label,
                                access: el.access
                            })
                            customerArrayHTML.append(html);
                        });

                        projectArrayHTML.empty();
                        projectPrivilegesData.forEach((el, i) => {
                            let html = accessHTML(el.type_label, el.access, i, 'project');

                            projectValArr.push({
                                value: el.type_id,
                                label: el.type_label,
                                access: el.access
                            })
                            projectArrayHTML.append(html);
                        });

                        contractArrayHTML.empty();
                        contractsPrivilegesData.forEach((el, i) => {
                            let html = accessHTML(el.type_label, el.access, i, 'contract');
                            contractValArr.push({
                                value: el.type_id,
                                label: el.type_label,
                                access: el.access
                            })
                            contractArrayHTML.append(html);
                        });

                        invoiceArrayHTML.empty();
                        invoicePrivilegesData.forEach((el, i) => {
                            let html = accessHTML(el.type_label, el.access, i, 'invoice');
                            invoiceValArr.push({
                                value: el.type_id,
                                label: el.type_label,
                                access: el.access
                            })
                            invoiceArrayHTML.append(html);
                        });

                        changeOrderArrayHTML.empty();
                        changeOrderPrivilegesData.forEach((el, i) => {
                            let html = accessHTML(el.type_label, el.access, i, 'change-order');
                            changeOrderValArr.push({
                                value: el.type_id,
                                label: el.type_label,
                                access: el.access
                            })
                            changeOrderArrayHTML.append(html);
                        });

                    });
            })

            $('#privileges-tab a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            })

            $('.save-btn').on("click", function() {
                privilegesFormWrap.find('[name="customers_privileges"]').val(JSON.stringify(customerValArr));
                privilegesFormWrap.find('[name="projects_privileges"]').val(JSON.stringify(projectValArr));
                privilegesFormWrap.find('[name="contracts_privileges"]').val(JSON.stringify(contractValArr));
                privilegesFormWrap.find('[name="invoices_privileges"]').val(JSON.stringify(invoiceValArr));
                privilegesFormWrap.find('[name="change_orders_privileges"]').val(JSON.stringify(changeOrderValArr));

                privilegesFormWrap.submit();
            })

            select2Customer.select2({
                placeholder: "search customer...",
                ajax: {
                    url: `/select/customer`,
                    dataType: 'json',
                    data: (params) => {
                        let uid = $('input.panel-user-id').val();
                        return {
                            query: params.term,
                            user_id: uid
                        }
                    },
                    processResults: (data, params) => {
                        let all = {
                            customer_id: 'all',
                            first_name: 'All',
                            last_name: ''
                        }
                        data.unshift(all);
                        const results = data.map(item => {
                            return {
                                id: item.customer_id,
                                text: item.first_name + ' ' + item.last_name,
                            };
                        });
                        return {
                            results: results,
                        }
                    },
                },
            });

            select2Customer.on('select2:select', function (e) {
                let selected = select2Customer.find(':selected');
                let customerValArrLength = customerValArr.length;
                customerValArr.push({
                    value: selected[0].value,
                    label: selected[0].text,
                    access: 'full'
                });

                let html = accessHTML(selected[0].text, 'full', customerValArrLength, 'customer');

                customerArrayHTML.append(html);

                select2Customer.val(null).trigger('change');
            });

            select2Project.select2({
                placeholder: "search project...",
                ajax: {
                    url: "/select/project",
                    dataType: 'json',
                    data: (params) => {
                        let uid = $('input.panel-user-id').val();
                        return {
                            query: params.term,
                            user_id: uid
                        }
                    },
                    processResults: (data, params) => {
                        let all = {
                            id: 'all',
                            project_id: 'All',
                            project_name: ''
                        }
                        data.unshift(all);
                        const results = data.map(item => {
                            return {
                                id: item.id,
                                text: item.project_id + ' | ' + item.project_name,
                            };
                        });
                        return {
                            results: results,
                        }
                    },
                },
            });

            select2Project.on('select2:select', function (e) {
                let selected = select2Project.find(':selected');
                let projectValArrLength = projectValArr.length;
                projectValArr.push({
                    value: selected[0].value,
                    label: selected[0].text,
                    access: 'full'
                });

                let html = accessHTML(selected[0].text, 'full', projectValArrLength, 'project');

                projectArrayHTML.append(html);

                select2Project.val(null).trigger('change');
            });

            select2Contract.select2({
                placeholder: "search contract...",
                ajax: {
                    url: "/select/contract",
                    dataType: 'json',
                    data: (params) => {
                        let uid = $('input.panel-user-id').val();
                        return {
                            query: params.term,
                            user_id: uid
                        }
                    },
                    processResults: (data, params) => {
                        let all = {
                            contract_id: 'all',
                            contract_code: 'All'
                        }
                        data.unshift(all);
                        const results = data.map(item => {
                            return {
                                id: item.contract_id,
                                text: item.contract_code,
                            };
                        });
                        return {
                            results: results,
                        }
                    },
                },
            });

            select2Contract.on('select2:select', function (e) {
                let selected = select2Contract.find(':selected');
                let contractValArrLength = contractValArr.length;
                contractValArr.push({
                    value: selected[0].value,
                    label: selected[0].text,
                    access: 'full'
                });

                let html = accessHTML(selected[0].text, 'full', contractValArrLength, 'contract');

                contractArrayHTML.append(html);

                select2Contract.val(null).trigger('change');
            });

            select2Invoice.select2({
                placeholder: "search invoice...",
                ajax: {
                    url: "/select/invoice",
                    dataType: 'json',
                    data: (params) => {
                        let uid = $('input.panel-user-id').val();
                        return {
                            query: params.term,
                            user_id: uid
                        }
                    },
                    processResults: (data, params) => {
                        let all = {
                            payment_request_id: 'all',
                            invoice_number: 'All'
                        }
                        data.unshift(all);
                        const results = data.map(item => {
                            return {
                                id: item.payment_request_id,
                                text: item.invoice_number,
                            };
                        });
                        return {
                            results: results,
                        }
                    },
                },
            });

            select2Invoice.on('select2:select', function (e) {
                let selected = select2Invoice.find(':selected');
                let invoiceValArrLength = invoiceValArr.length;
                invoiceValArr.push({
                    value: selected[0].value,
                    label: selected[0].text,
                    access: 'full'
                });

                let html = accessHTML(selected[0].text, 'full', invoiceValArrLength, 'invoice');

                invoiceArrayHTML.append(html);

                select2Invoice.val(null).trigger('change');
            });

            select2ChangeOrder.select2({
                placeholder: "search order...",
                ajax: {
                    url: "/select/change-order",
                    dataType: 'json',
                    data: (params) => {
                        let uid = $('input.panel-user-id').val();
                        return {
                            query: params.term,
                            user_id: uid
                        }
                    },
                    processResults: (data, params) => {
                        let all = {
                            order_id: 'all',
                            order_no: 'All'
                        }
                        data.unshift(all);
                        const results = data.map(item => {
                            return {
                                id: item.order_id,
                                text: item.order_no,
                            };
                        });
                        return {
                            results: results,
                        }
                    },
                },
            });

            select2ChangeOrder.on('select2:select', function (e) {
                let selected = select2ChangeOrder.find(':selected');
                let changeOrderValArrLength = changeOrderValArr.length;
                changeOrderValArr.push({
                    value: selected[0].value,
                    label: selected[0].text,
                    access: 'full'
                });

                let html = accessHTML(selected[0].text, 'full', changeOrderValArrLength, 'change-order');

                changeOrderArrayHTML.append(html);

                select2ChangeOrder.val(null).trigger('change');
            });

            $(document).on("change","select.privileges-access",function() {
                let privilegesType = $(this).attr('data-type');
                switch (privilegesType) {
                    case 'customer':
                        customerValArr[$(this).attr('data-id')].access = $(this).val();
                        break;
                    case 'project':
                        projectValArr[$(this).attr('data-id')].access = $(this).val();
                        break;
                    case 'contract':
                        contractValArr[$(this).attr('data-id')].access = $(this).val();
                        break;
                    case 'invoice':
                        invoiceValArr[$(this).attr('data-id')].access = $(this).val();
                        break;
                    case 'change-order':
                        changeOrderValArr[$(this).attr('data-id')].access = $(this).val();
                        break;
                }
            });

            $(document).on("click", ".delete-privilege-access", function() {
                let privilegesType = $(this).attr('data-type');
                let privilegesIndex = $(this).attr('data-id');
                switch (privilegesType) {
                    case 'customer':
                        customerValArr.splice(privilegesIndex, 1);
                        customerArrayHTML.empty();
                        customerValArr.forEach((el, i) => {
                            let html = accessHTML(el.label, el.access, i, 'customer');
                            customerArrayHTML.append(html);
                        });
                        break;
                    case 'project':
                        projectValArr.splice(privilegesIndex, 1);
                        projectArrayHTML.empty();
                        projectValArr.forEach((el, i) => {
                            let html = accessHTML(el.label, el.access, i, 'project');
                            projectArrayHTML.append(html);
                        });
                        break;
                    case 'contract':
                        contractValArr.splice(privilegesIndex, 1);
                        contractArrayHTML.empty();
                        contractValArr.forEach((el, i) => {
                            let html = accessHTML(el.label, el.access, i, 'contract');
                            contractArrayHTML.append(html);
                        });
                        break;
                    case 'invoice':
                        invoiceValArr.splice(privilegesIndex, 1);
                        invoiceArrayHTML.empty();
                        invoiceValArr.forEach((el, i) => {
                            let html = accessHTML(el.label, el.access, i, 'invoice');
                            invoiceArrayHTML.append(html);
                        });
                        break;
                    case 'change-order':
                        changeOrderValArr.splice(privilegesIndex, 1);
                        changeOrderArrayHTML.empty();
                        changeOrderValArr.forEach((el, i) => {
                            let html = accessHTML(el.label, el.access, i, 'change-order');
                            changeOrderArrayHTML.append(html);
                        });
                        break;
                }
            });

            function accessHTML(label, privileges = '', index, type) {
                return `<div id="${type}-access-item-${index}" class="privileges-access-item">
<p class="privileges-access-item-title">${label}</p>
<div style="display: flex;align-items: center;">
<select class="privileges-access privileges-access-dropdown" data-id="${index}" data-type="${type}">
    <option value="full" ${privileges === 'full' ? 'selected' : ''}>Full</option>
    <option value="edit" ${privileges === 'edit' ? 'selected' : ''}>Edit</option>
    <option value="comment" ${privileges === 'comment' ? 'selected' : ''}>Comment</option>
    ${type === 'change-order' || type === 'invoice' ? approveHTML(privileges) : ''}
    <option value="view-only" ${privileges === 'view-only' ? 'selected' : ''}>View Only</option>
</select>
<a class="close" style="margin-left: 10px">
    <button type="button" class="close delete-privilege-access" data-id="${index}" data-type="${type}" aria-hidden="true"></button>
</a>
</div>
</div>`;
            }

            function approveHTML(privileges) {
                return `<option value="approve" ${privileges === 'approve' ? 'selected' : ''}>Approve</option>`
            }
        })
    </script>