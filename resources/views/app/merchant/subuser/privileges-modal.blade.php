<style>
    .privileges-access-row {
        border-radius: 4px;
        background: #FFFFFF;
        border: 1px solid #FFFFFF;
        box-shadow: 0 3px 3px rgb(0 0 0 / 12%);
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .privileges-access-item {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        margin: 10px 0 5px;
    }

    .privileges-access-item-title, .privileges-form label {
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

    .rule-engine-row {
        padding: 5px 5px;
        margin-bottom: 15px;
    }

    .rule-engine-row, .add-rule-engine-btn {
        align-self: end;
    }

    .add-rule-engine-btn {
        background: transparent;
        border: 0;
        text-decoration: underline;
        display: none;
        padding: 5px 10px 10px;
    }

    .rule-engine-query {
        display: flex;
    }

    .rule-engine-text {
        margin: 10px;
        color: #394242;
    }

    .rule-engine-query-name {
        width: auto;
        margin: 5px;
    }

    .rule-engine-operator {
        color: #394242;
        margin: 5px;
        width: auto;
    }

    .rule-engine-value {
        width: 60px;
        margin: 5px 10px;
    }

    .custom-show {
        display: block;
    }

    .custom-hide {
        display: none;
    }

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
                    Set Privileges for <span id="panel-user-name"></span>
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
            let userName;

            $(document).on("click",".open-privileges-drawer-btn",function() {
                let panelWrap =  document.getElementById("panelWrapPrivileges");
                userID = $(this).attr("data-user-id");
                userName = $(this).attr("data-user-name");
                panelWrap.style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
                panelWrap.style.transform = "translateX(0%)";
                panelWrap.getElementsByClassName('panel-user-id')[0].value = userID;
                document.getElementById("panel-user-name").innerHTML = userName;

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
                            let ruleEngine = [];
                            let hasRuleEngine = false;

                            if(el.rule_engine_query) {
                                ruleEngine = JSON.parse(el.rule_engine_query);
                            }

                            if(ruleEngine) {
                                hasRuleEngine = ruleEngine.length > 0;
                            }

                            let rule_engine = [];

                            // if(hasRuleEngine) {
                            //     rule_engine = [
                            //         {
                            //             query_name: 'grand_total',
                            //             query_model: 'payment_request',
                            //             query_operator: ruleEngine[0].query_operator,
                            //             query_value: ruleEngine[0].query_value
                            //         }
                            //     ]
                            // }
                            let html = accessHTML(el.type_label, el.access, i, 'customer', hasRuleEngine, ruleEngine);
                            customerValArr.push({
                                value: el.type_id,
                                label: el.type_label,
                                access: el.access,
                                rule_engine_query: ruleEngine
                            })
                            customerArrayHTML.append(html);
                        });

                        projectArrayHTML.empty();
                        projectPrivilegesData.forEach((el, i) => {
                            let ruleEngine = [];
                            let hasRuleEngine = false;

                            if(el.rule_engine_query) {
                                ruleEngine = JSON.parse(el.rule_engine_query);
                            }

                            if(ruleEngine) {
                                hasRuleEngine = ruleEngine.length > 0;
                            }
                            // let rule_engine = [
                            //     {
                            //         query_name: 'grand_total',
                            //         query_model: 'payment_request',
                            //         query_operator: '',
                            //         query_value: '',
                            //     }
                            // ];

                            // if(hasRuleEngine) {
                            //     rule_engine = [
                            //         {
                            //             query_name: 'grand_total',
                            //             query_model: 'payment_request',
                            //             query_operator: ruleEngine[0].query_operator,
                            //             query_value: ruleEngine[0].query_value
                            //         }
                            //     ]
                            // }

                            let html = accessHTML(el.type_label, el.access, i, 'project', hasRuleEngine, ruleEngine);

                            projectValArr.push({
                                value: el.type_id,
                                label: el.type_label,
                                access: el.access,
                                rule_engine_query: ruleEngine
                            })
                            projectArrayHTML.append(html);
                        });

                        contractArrayHTML.empty();
                        contractsPrivilegesData.forEach((el, i) => {
                            let ruleEngine = [];
                            let hasRuleEngine = false;

                            if(el.rule_engine_query) {
                                ruleEngine = JSON.parse(el.rule_engine_query);
                            }

                            if(ruleEngine) {
                                hasRuleEngine = ruleEngine.length > 0;
                            }
                            // let rule_engine = [
                            //     {
                            //         query_name: 'grand_total',
                            //         query_model: 'payment_request',
                            //         query_operator: '',
                            //         query_value: '',
                            //     }
                            // ];

                            // if(hasRuleEngine) {
                            //     rule_engine = [
                            //         {
                            //             query_name: 'grand_total',
                            //             query_model: 'payment_request',
                            //             query_operator: ruleEngine[0].query_operator,
                            //             query_value: ruleEngine[0].query_value
                            //         }
                            //     ]
                            // }

                            let html = accessHTML(el.type_label, el.access, i, 'contract', hasRuleEngine, ruleEngine);
                            contractValArr.push({
                                value: el.type_id,
                                label: el.type_label,
                                access: el.access,
                                rule_engine_query: ruleEngine
                            })
                            contractArrayHTML.append(html);
                        });

                        invoiceArrayHTML.empty();
                        invoicePrivilegesData.forEach((el, i) => {
                            let ruleEngine = [];
                            let hasRuleEngine = false;

                            if(el.rule_engine_query) {
                                ruleEngine = JSON.parse(el.rule_engine_query);
                            }

                            if(ruleEngine) {
                                hasRuleEngine = ruleEngine.length > 0;
                            }
                            // let rule_engine = [
                            //     {
                            //         query_name: 'grand_total',
                            //         query_model: 'payment_request',
                            //         query_operator: '',
                            //         query_value: ''
                            //     }
                            // ]
                            // if(hasRuleEngine) {
                            //     rule_engine = [
                            //         {
                            //             query_name: 'grand_total',
                            //             query_model: 'payment_request',
                            //             query_operator: ruleEngine[0].query_operator,
                            //             query_value: ruleEngine[0].query_value
                            //         }
                            //     ]
                            // }

                            let html = accessHTML(el.type_label, el.access, i, 'invoice', hasRuleEngine, ruleEngine);
                            invoiceValArr.push({
                                value: el.type_id,
                                label: el.type_label,
                                access: el.access,
                                rule_engine_query: ruleEngine
                            })
                            invoiceArrayHTML.append(html);
                        });

                        changeOrderArrayHTML.empty();
                        changeOrderPrivilegesData.forEach((el, i) => {
                            let ruleEngine = [];
                            let hasRuleEngine = false;

                            if(el.rule_engine_query) {
                                ruleEngine = JSON.parse(el.rule_engine_query);
                            }

                            if(ruleEngine) {
                                hasRuleEngine = ruleEngine.length > 0;
                            }

                            // let rule_engine = [
                            //     {
                            //         query_name: 'total_change_order_amount',
                            //         query_model: 'change_order',
                            //         query_operator: '',
                            //         query_value: '',
                            //     }
                            // ]
                            //
                            // if(hasRuleEngine) {
                            //     rule_engine = [
                            //         {
                            //             query_name: 'total_change_order_amount',
                            //             query_model: 'change_order',
                            //             query_operator: ruleEngine[0].query_operator,
                            //             query_value: ruleEngine[0].query_value
                            //         }
                            //     ]
                            // }
                            let html = accessHTML(el.type_label, el.access, i, 'change-order', hasRuleEngine, ruleEngine);
                            changeOrderValArr.push({
                                value: el.type_id,
                                label: el.type_label,
                                access: el.access,
                                rule_engine_query: ruleEngine
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
                            company_name: 'All'
                        }
                        data.unshift(all);
                        const results = data.map(item => {
                            return {
                                id: item.customer_id,
                                text: item.company_name,
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
                // let rule_engine = [
                //     {
                //         query_name: 'grand_total',
                //         query_model: 'payment_request',
                //         query_operator: '',
                //         query_value: '',
                //     }
                // ];

                let rule_engine = [];

                customerValArr.push({
                    value: selected[0].value,
                    label: selected[0].text,
                    access: 'full',
                    rule_engine_query: rule_engine
                });

                let html = accessHTML(selected[0].text, 'full', customerValArrLength, 'customer', false, rule_engine);

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
                let rule_engine = [];
                projectValArr.push({
                    value: selected[0].value,
                    label: selected[0].text,
                    access: 'full',
                    rule_engine_query: rule_engine
                });

                // let rule_engine = [
                //     {
                //         query_name: 'grand_total',
                //         query_model: 'payment_request',
                //         query_operator: '',
                //         query_value: '',
                //     }
                // ];

                let html = accessHTML(selected[0].text, 'full', projectValArrLength, 'project', false, rule_engine);

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
                let rule_engine = [];

                contractValArr.push({
                    value: selected[0].value,
                    label: selected[0].text,
                    access: 'full',
                    rule_engine_query: rule_engine
                });

                let html = accessHTML(selected[0].text, 'full', contractValArrLength, 'contract', false, rule_engine);

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

                let rule_engine = [];

                invoiceValArr.push({
                    value: selected[0].value,
                    label: selected[0].text,
                    access: 'full',
                    rule_engine_query: rule_engine
                });

                let html = accessHTML(selected[0].text, 'full', invoiceValArrLength, 'invoice', false, rule_engine);

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
                let rule_engine = [];

                changeOrderValArr.push({
                    value: selected[0].value,
                    label: selected[0].text,
                    access: 'full',
                    rule_engine_query: rule_engine
                });

                let html = accessHTML(selected[0].text, 'full', changeOrderValArrLength, 'change-order', false, rule_engine);

                changeOrderArrayHTML.append(html);

                select2ChangeOrder.val(null).trigger('change');
            });

            $(document).on("change", "select.privileges-access",function() {
                let privilegesType = $(this).attr('data-type');
                let val = $(this).val();
                let privilegesID = $(this).attr('data-id');

                switch (privilegesType) {
                    case 'customer':
                        if(val === 'full' || val === 'approve') {
                            if(!$('#customer-rule-engine-'+privilegesID).hasClass('custom-show')) {
                                $('#customer-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-hide');
                                $('#customer-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-show');
                            }
                        } else {
                            $('#customer-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-show');
                            $('#customer-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-hide');
                            $('#customer-rule-engine-'+privilegesID).removeClass('custom-show');
                            $('#customer-rule-engine-'+privilegesID).addClass('custom-hide');
                            customerValArr[privilegesID].rule_engine_query = [];
                            // customerValArr[privilegesID].rule_engine_query = [
                            //     {
                            //         query_name: 'grand_total',
                            //         query_model: 'payment_request',
                            //         query_operator: '',
                            //         query_value: '',
                            //     }
                            // ]
                        }
                        customerValArr[privilegesID].access = val;
                        break;
                    case 'project':
                        if(val === 'full' || val === 'approve') {
                            if(!$('#project-rule-engine-'+privilegesID).hasClass('custom-show')) {
                                $('#project-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-hide');
                                $('#project-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-show');
                            }

                        } else {
                            $('#project-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-show');
                            $('#project-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-hide');
                            $('#project-rule-engine-'+privilegesID).removeClass('custom-show');
                            $('#project-rule-engine-'+privilegesID).addClass('custom-hide');
                            projectValArr[privilegesID].rule_engine_query = [];
                            // projectValArr[privilegesID].rule_engine_query = [
                            //     {
                            //         query_name: 'grand_total',
                            //         query_model: 'payment_request',
                            //         query_operator: '',
                            //         query_value: '',
                            //     }
                            // ]
                        }
                        projectValArr[privilegesID].access = val;
                        break;
                    case 'contract':
                        if(val === 'full' || val === 'approve') {
                            if(!$('#contract-rule-engine-'+privilegesID).hasClass('custom-show')) {
                                $('#contract-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-hide');
                                $('#contract-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-show');
                            }
                        } else {
                            $('#contract-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-show');
                            $('#contract-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-hide');
                            $('#contract-rule-engine-'+privilegesID).removeClass('custom-show');
                            $('#contract-rule-engine-'+privilegesID).addClass('custom-hide');
                            contractValArr[privilegesID].rule_engine_query = [];
                            // contractValArr[privilegesID].rule_engine_query = [
                            //     {
                            //         query_name: 'grand_total',
                            //         query_model: 'payment_request',
                            //         query_operator: '',
                            //         query_value: '',
                            //     }
                            // ]
                        }
                        contractValArr[privilegesID].access = val;
                        break;
                    case 'invoice':
                        if(val === 'full' || val === 'approve') {
                            if(!$('#invoice-rule-engine-'+privilegesID).hasClass('custom-show')) {
                                $('#invoice-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-hide');
                                $('#invoice-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-show');
                            }
                        } else {
                            $('#invoice-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-show');
                            $('#invoice-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-hide');
                            $('#invoice-rule-engine-'+privilegesID).removeClass('custom-show');
                            $('#invoice-rule-engine-'+privilegesID).addClass('custom-hide');
                            invoiceValArr[privilegesID].rule_engine_query = [];
                            // invoiceValArr[privilegesID].rule_engine_query = [
                            //     {
                            //         query_name: 'grand_total',
                            //         query_model: 'payment_request',
                            //         query_operator: '',
                            //         query_value: '',
                            //     }
                            // ]
                        }

                        invoiceValArr[privilegesID].access = val;
                        break;
                    case 'change-order':
                        if(val === 'full' || val === 'approve') {
                            if(!$('#change-order-rule-engine-'+privilegesID).hasClass('custom-show')) {
                                $('#change-order-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-hide');
                                $('#change-order-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-show');
                            }
                        } else {
                            $('#change-order-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-show');
                            $('#change-order-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-hide');
                            $('#change-order-rule-engine-'+privilegesID).removeClass('custom-show');
                            $('#change-order-rule-engine-'+privilegesID).addClass('custom-hide');
                            changeOrderValArr[privilegesID].rule_engine_query = [];
                            // changeOrderValArr[privilegesID].rule_engine_query = [
                            //     {
                            //         query_name: 'total_change_order_amount',
                            //         query_model: 'change_order',
                            //         query_operator: '',
                            //         query_value: '',
                            //     }
                            // ]
                        }
                        changeOrderValArr[privilegesID].access = val;
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
                            let hasRuleEngine = false;

                            if(el.rule_engine_query) {
                                hasRuleEngine = el.rule_engine_query.length > 0;
                            }

                            let rule_engine = [
                                {
                                    query_name: 'grand_total',
                                    query_model: 'payment_request',
                                    query_operator: '',
                                    query_value: '',
                                }
                            ];

                            if(hasRuleEngine) {
                                rule_engine = [
                                    {
                                        query_name: 'grand_total',
                                        query_model: 'payment_request',
                                        query_operator: el.rule_engine_query[0].query_operator,
                                        query_value: el.rule_engine_query[0].query_value
                                    }
                                ]
                            }

                            let html = accessHTML(el.label, el.access, i, 'customer', hasRuleEngine, rule_engine);
                            customerArrayHTML.append(html);
                        });
                        break;
                    case 'project':
                        projectValArr.splice(privilegesIndex, 1);
                        // $("#project-access-item-"+privilegesIndex).remove();
                        projectArrayHTML.empty();
                        projectValArr.forEach((el, i) => {
                            let hasRuleEngine = false;

                            if(el.rule_engine_query) {
                                hasRuleEngine = el.rule_engine_query.length > 0;
                            }

                            let rule_engine = [
                                {
                                    query_name: 'grand_total',
                                    query_model: 'payment_request',
                                    query_operator: '',
                                    query_value: '',
                                }
                            ];

                            if(hasRuleEngine) {
                                rule_engine = [
                                    {
                                        query_name: 'grand_total',
                                        query_model: 'payment_request',
                                        query_operator: el.rule_engine_query[0].query_operator,
                                        query_value: el.rule_engine_query[0].query_value
                                    }
                                ]
                            }

                            let html = accessHTML(el.label, el.access, i, 'project', hasRuleEngine, rule_engine);
                            projectArrayHTML.append(html);
                        });
                        break;
                    case 'contract':
                        contractValArr.splice(privilegesIndex, 1);
                        contractArrayHTML.empty();
                        contractValArr.forEach((el, i) => {
                            let hasRuleEngine = false;

                            if(el.rule_engine_query) {
                                hasRuleEngine = el.rule_engine_query.length > 0;
                            }

                            let rule_engine = [
                                {
                                    query_name: 'grand_total',
                                    query_model: 'payment_request',
                                    query_operator: '',
                                    query_value: '',
                                }
                            ];

                            if(hasRuleEngine) {
                                rule_engine = [
                                    {
                                        query_name: 'grand_total',
                                        query_model: 'payment_request',
                                        query_operator: el.rule_engine_query[0].query_operator,
                                        query_value: el.rule_engine_query[0].query_value
                                    }
                                ]
                            }

                            let html = accessHTML(el.label, el.access, i, 'contract', hasRuleEngine, rule_engine);
                            contractArrayHTML.append(html);
                        });
                        break;
                    case 'invoice':
                        invoiceValArr.splice(privilegesIndex, 1);
                        invoiceArrayHTML.empty();
                        invoiceValArr.forEach((el, i) => {
                            let hasRuleEngine = false;

                            if(el.rule_engine_query) {
                                hasRuleEngine = el.rule_engine_query.length > 0;
                            }

                            let rule_engine = [
                                {
                                    query_name: 'grand_total',
                                    query_model: 'payment_request',
                                    query_operator: '',
                                    query_value: '',
                                }
                            ];

                            if(hasRuleEngine) {
                                rule_engine = [
                                    {
                                        query_name: 'grand_total',
                                        query_model: 'payment_request',
                                        query_operator: el.rule_engine_query[0].query_operator,
                                        query_value: el.rule_engine_query[0].query_value
                                    }
                                ]
                            }

                            let html = accessHTML(el.label, el.access, i, 'invoice', hasRuleEngine, rule_engine);
                            invoiceArrayHTML.append(html);
                        });
                        break;
                    case 'change-order':
                        changeOrderValArr.splice(privilegesIndex, 1);
                        changeOrderArrayHTML.empty();
                        changeOrderValArr.forEach((el, i) => {
                            let hasRuleEngine = false;

                            if(el.rule_engine_query) {
                                hasRuleEngine = el.rule_engine_query.length > 0;
                            }

                            let rule_engine = [
                                {
                                    query_name: 'total_change_order_amount',
                                    query_model: 'change_order',
                                    query_operator: '',
                                    query_value: '',
                                }
                            ];

                            if(hasRuleEngine) {
                                rule_engine = [
                                    {
                                        query_name: 'total_change_order_amount',
                                        query_model: 'change_order',
                                        query_operator: el.rule_engine_query[0].query_operator,
                                        query_value: el.rule_engine_query[0].query_value
                                    }
                                ]
                            }
                            let html = accessHTML(el.label, el.access, i, 'change-order', hasRuleEngine, rule_engine);
                            changeOrderArrayHTML.append(html);
                        });
                        break;
                }
            });

            $(document).on("click", ".add-rule-engine-btn",function() {
                let val = $(this).val();
                let privilegesID = $(this).attr('data-id');
                let type = $(this).attr('data-type');
                console.log(privilegesID, type, customerValArr);
                switch (type) {
                    case 'customer':
                        $('#customer-rule-engine-'+privilegesID).removeClass('custom-hide');
                        $('#customer-rule-engine-'+privilegesID).addClass('custom-show');

                        customerValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: 'gt',
                                query_value: '',
                            }
                        ];
                        break;
                    case 'project':
                        $('#project-rule-engine-'+privilegesID).removeClass('custom-hide');
                        $('#project-rule-engine-'+privilegesID).addClass('custom-show');

                        projectValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: 'gt',
                                query_value: '',
                            }
                        ];
                        break;
                    case 'contract':
                        $('#contract-rule-engine-'+privilegesID).removeClass('custom-hide');
                        $('#contract-rule-engine-'+privilegesID).addClass('custom-show');

                        contractValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: 'gt',
                                query_value: '',
                            }
                        ];
                        break;
                    case 'invoice':
                        $('#invoice-rule-engine-'+privilegesID).removeClass('custom-hide');
                        $('#invoice-rule-engine-'+privilegesID).addClass('custom-show');

                        invoiceValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: 'gt',
                                query_value: '',
                            }
                        ];
                        break;
                    case 'change-order':
                        $('#change-order-rule-engine-'+privilegesID).removeClass('custom-hide');
                        $('#change-order-rule-engine-'+privilegesID).addClass('custom-show');

                        changeOrderValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'total_change_order_amount',
                                query_model: 'change_order',
                                query_operator: 'gt',
                                query_value: '',
                            }
                        ];
                        break;
                }

                $(this).removeClass('custom-show');
                $(this).addClass('custom-hide');
            });

            $(document).on("click", ".remove-query-btn", function() {

                let val = $(this).val();
                let privilegesID = $(this).attr('data-id');
                let type = $(this).attr('data-type');

                $('#'+type+'-access-item-'+privilegesID).find('.add-rule-engine-btn').removeClass('custom-hide');
                $('#'+type+'-access-item-'+privilegesID).find('.add-rule-engine-btn').addClass('custom-show');
                $('#'+type+'-rule-engine-'+privilegesID).removeClass('custom-show');
                $('#'+type+'-rule-engine-'+privilegesID).addClass('custom-hide');

                switch(type) {
                    case 'customer':
                        customerValArr[privilegesID].rule_engine_query = [];
                        break;
                    case 'contract':
                        contractValArr[privilegesID].rule_engine_query = [];
                        break;
                    case 'project':
                        projectValArr[privilegesID].rule_engine_query = [];
                        break;
                    case 'invoice':
                        invoiceValArr[privilegesID].rule_engine_query = [];
                        break;
                    case 'change-order':
                        changeOrderValArr[privilegesID].rule_engine_query = [];
                        break;
                }
            });

            $(document).on("change", ".rule-engine-operator", function() {
                let val = $(this).val();
                let privilegesID = $(this).attr('data-id');
                let type = $(this).attr('data-type');
                let queryVal = $('#'+type+'-access-item-'+privilegesID).find('.rule-engine-value').val();

                switch(type) {
                    case 'customer':
                        customerValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: val,
                                query_value: queryVal,
                            }
                        ];
                        break;
                    case 'contract':
                        contractValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: val,
                                query_value: queryVal,
                            }
                        ];
                        break;
                    case 'project':
                        projectValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_operator: val,
                                query_value: queryVal,
                            }
                        ];
                        break;
                    case 'invoice':
                        invoiceValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: val,
                                query_value: queryVal,
                            }
                        ];
                        break;
                    case 'change-order':
                        changeOrderValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'total_change_order_amount',
                                query_model: 'change_order',
                                query_operator: val,
                                query_value: queryVal,
                            }
                        ];
                        break;
                }
            });

            $(document).on("change", ".rule-engine-value", function() {
                let val = $(this).val();
                let privilegesID = $(this).attr('data-id');
                let type = $(this).attr('data-type');
                let queryOperator = $('#'+type+'-access-item-'+privilegesID).find('.rule-engine-operator').val();

                switch(type) {
                    case 'customer':
                        customerValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: queryOperator,
                                query_value: val,
                            }
                        ];
                        break;
                    case 'contract':
                        contractValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: queryOperator,
                                query_value: val,
                            }
                        ];
                        break;
                    case 'project':
                        projectValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: queryOperator,
                                query_value: val,
                            }
                        ];
                        break;
                    case 'invoice':
                        invoiceValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'grand_total',
                                query_model: 'payment_request',
                                query_operator: queryOperator,
                                query_value: val,
                            }
                        ];
                        break;
                    case 'change-order':
                        changeOrderValArr[privilegesID].rule_engine_query = [
                            {
                                query_name: 'total_change_order_amount',
                                query_model: 'change_order',
                                query_operator: queryOperator,
                                query_value: val,
                            }
                        ];
                        break;
                }

            });

            function accessHTML(label, privileges = '', index, type, hasRuleEngine, rule_engine = []) {
                return `<div id="${type}-access-item-${index}" class="privileges-access-row">
<div class="privileges-access-item">
${accessItemTitle(type, label)}
<div style="display: flex;align-items: center;align-self: start">
<select class="privileges-access privileges-access-dropdown form-control" data-id="${index}" data-type="${type}">
    <option value="full" ${privileges === 'full' ? 'selected' : ''}>Full</option>
    <option value="edit" ${privileges === 'edit' ? 'selected' : ''}>Edit</option>
    <option value="approve" ${privileges === 'approve' ? 'selected' : ''}>Approve</option>
    <option value="comment" ${privileges === 'comment' ? 'selected' : ''}>Comment</option>
    <option value="view-only" ${privileges === 'view-only' ? 'selected' : ''}>View Only</option>
</select>
<a class="close" style="margin-left: 10px">
    <button type="button" class="close delete-privilege-access" data-id="${index}" data-type="${type}" aria-hidden="true"></button>
</a>
</div>
</div>
${addRuleBtn(type, privileges, hasRuleEngine, index)}
${showRuleEngineHTML(type, privileges, index, hasRuleEngine, rule_engine[0])}
</div>`;
            }

            // function approveHTML(privileges) {
            //     return `<option value="approve" ${privileges === 'approve' ? 'selected' : ''}>Approve</option>`
            // }

            function showRuleEngineHTML(type, privileges, index, hasRuleEngine, rule_engine) {
                return `<div class="rule-engine-row ${hasRuleEngine ? 'custom-show' : 'custom-hide'}" id="${type}-rule-engine-${index}">
                        <div class="rule-engine-query">
                            ${type === 'change-order' ? changeOrderOptions() : invoiceOptions()}
                            ${ruleEngineOperators(rule_engine?.query_operator, index, type)}
${ruleEngineValue(rule_engine?.query_value, index, type)}
<a class="close remove-query-btn" style="margin-right: 5px; align-self: center" data-id="${index}" data-type="${type}">
    <button type="button" class="close" data-id="${index}" data-type="${type}" aria-hidden="true"></button>
</a>
                        </div>
                        </div>`;
            }

            function addRuleBtn(type, privileges, hasRuleEngine, index) {
                if(hasRuleEngine) {
                    return `<button type="button" class="add-rule-engine-btn custom-hide" data-id="${index}" data-type="${type}">Add Rule</button>`;
                }

                return `<button type="button" class="add-rule-engine-btn ${privileges === 'full' || privileges === 'approve' ? 'custom-show' : 'custom-hide'}" data-id="${index}" data-type="${type}">Add Rule</button>`
            }

            function invoiceOptions() {
                return `<p class="rule-engine-text">If invoice</p><select name="rule-engine-operator" class="rule-engine-query-name form-control">
<option value="grand_total" selected>Grand total</option>
</select>`;
            }

            function changeOrderOptions() {
                return `<p class="rule-engine-text">If Change Order</p><select name="rule-engine-operator" class="rule-engine-query-name form-control">
<option value="total_change_order_amount" selected>Total amount</option>
</select>`;
            }

            function ruleEngineOperators(queryOperator, index, type) {
                let html = '';
                if(queryOperator) {
                    html = `<select name="rule-engine-operator" class="rule-engine-operator form-control" data-id="${index}" data-type="${type}">
                            <option value="gt" ${queryOperator
                    === 'gt' ? 'selected' : ''}>greater than</option>
<option value="lt" ${queryOperator
                    === 'lt' ? 'selected' : ''}>lesser than</option>
<option value="et" ${queryOperator
                    === 'et' ? 'selected' : ''}>equals to</option>
<option value="net" ${queryOperator
                    === 'net' ? 'selected' : ''}>not equals to</option>
</select>`;
                } else {
                    html = `<select name="rule-engine-operator" class="rule-engine-operator form-control" data-id="${index}" data-type="${type}">
                            <option value="gt">greater than</option>
<option value="lt">lesser than</option>
<option value="et">equals to</option>
<option value="net">not equals to</option>
</select>`;
                }

                return html;
            }

            function ruleEngineValue(queryValue, index, type) {
                let html = '';
                if(queryValue) {
                    html = `<input type="number" name="rule-engine-value" value="${queryValue}" class="rule-engine-value form-control" data-id="${index}" data-type="${type}">`;
                } else {
                    html = `<input type="number" name="rule-engine-value" class="rule-engine-value form-control" data-id="${index}" data-type="${type}">`;
                }
                return html;
            }

            function accessItemTitle(type, label) {
                if(type === 'customer') {
                    return `<div>
<p class="privileges-access-item-title">${label}</p>
<p class="text-gray-400 text-font-12">COMPANY NAME</p>
</div>`;
                }
                return `<p class="privileges-access-item-title">${label}</p>`;
            }

        })
    </script>
