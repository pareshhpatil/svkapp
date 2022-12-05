
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}&nbsp;
    </h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Beneficiary ID
                                </th>
                                <th class="td-c">
                                    Beneficiary code
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Email
                                </th>
                                <th class="td-c">
                                    Mobile
                                </th>
                                <th class="td-c">
                                    Bank account
                                </th>
                                <th class="td-c">
                                    IFSC
                                </th>
                                <th class="td-c">
                                    UPI
                                </th>
                                
                                
                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.beneficiary_id}
                                    </td>
                                    <td class="td-c">
                                        {$v.beneficiary_code}
                                    </td>
                                    <td class="td-c">
                                        {$v.type}
                                    </td>
                                    <td class="td-c">
                                        {$v.name}
                                    </td>
                                    <td class="td-c">
                                        {$v.email_id}
                                    </td>
                                    <td class="td-c">
                                        {$v.mobile}
                                    </td>
                                    <td class="td-c">
                                        {$v.bank_account_no}
                                    </td>
                                    <td class="td-c">
                                        {$v.ifsc_code}
                                    </td>
                                    <td class="td-c">
                                        {$v.upi}
                                    </td>
                                </tr>
                            {/foreach}
                        </form>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

