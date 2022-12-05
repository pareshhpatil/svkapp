
<div class="" style="background-color: #ffffff;">
    <!-- BEGIN PAGE HEADER-->

    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <br>
            <h3 class="page-title">Dynamic Variables&nbsp;</h3>
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="portlet-body">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <table class="table table-striped table-hover" id="table-no-export">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Name
                                    </th>
                                    <th class="td-c">
                                        Description
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                            <form action="" method="">
                                {foreach from=$list item=v}
                                    <tr>
                                        <td class="td-c">
                                            {$v.name}
                                        </td>
                                        <td class="td-c">
                                            {$v.description}
                                        </td>
                                    </tr>

                                {/foreach}
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->


