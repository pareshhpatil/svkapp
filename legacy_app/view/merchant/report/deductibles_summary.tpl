
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">

            <!-- BEGIN PORTLET-->
            <div class="portlet">
                
                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>



                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>

                        </div>
                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div> 

        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th>
                                    Deductible
                                </th>

                                <th>
                                    Deduct Amount
                                </th>



                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                <tr>
                                    <td>
                                        {$v.tax_name} 
                                    </td>
                                    <td>
                                        {$v.amount} 
                                    </td>
                                </tr>
                            {/foreach}
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

