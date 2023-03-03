
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">
            {if isset($haserrors)}
                <div class="alert alert-danger nolr-margin">
                    <div class="media">
                        <p class="media-heading"><strong></strong>{$haserrors}.</p>
                    </div>

                </div>
            {/if}
            {if isset($warning)}
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>{$warning.title}!</strong>
                    <div class="media">
                        <p class="media-heading">{$warning.text}</p>
                    </div>

                </div>
            {/if}
            <!-- BEGIN PORTLET-->
            <div class="portlet">
                
                <!-- BEGIN PORTLET-->

                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Paid on</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}" name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>
                        {if !empty($franchise_list) && $type==1}
                            <div class="form-group">
                                <label class="help-block">Franchise name</label>
                                <select class="form-control " data-placeholder="Franchise name" name="franchise_id">
                                    <option value="0">Select franchise</option>
                                    {foreach from=$franchise_list item=v}
                                        {if {{$franchise_id}=={$v.franchise_id}}}
                                            <option selected value="{$v.franchise_id}" selected>{$v.franchise_name}</option>
                                        {else}
                                            <option value="{$v.franchise_id}">{$v.franchise_name}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                        {/if}

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>
                            <button type="submit" name="export" class="btn  green">Excel export</button>

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
                    <form id="myForm" action="" method="post">
                        <table class="table table-striped table-bordered table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>
                                        Paid on
                                    </th>
                                    <th>
                                        Transaction #
                                    </th>
                                    
                                    {if $has_franchise==1}
                                        <th>
                                            Franchise name
                                        </th>
                                    {/if}
                                    <th>
                                        Email ID
                                    </th>
                                    <th>
                                        Mobile No
                                    </th>
                                    <th>
                                        Reference #
                                    </th>
                                    <th>
                                    {$customer_default_column.customer_name|default:'Contact person name'}
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    {if $type==1}
                                        <th>
                                            Udf 1
                                        </th>
                                        <th>
                                            Udf 2
                                        </th>
                                        <th>
                                            Udf 3
                                        </th>
                                        <th>
                                            Udf 4
                                        </th>
                                        <th>
                                            Udf 5
                                        </th>
                                    {/if}
                                    {if $type==3}
                                        <th>
                                        {$customer_default_column.customer_code|default:'Customer code'}
                                        </th>
                                        <th>
                                            Plan
                                        </th>
                                    {/if}
                                    


                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="{$col_span}" style=""></th>
                                </tr>
                            </tfoot>

                        </table>
                    </form>
                </div>
            </div>

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>

    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

