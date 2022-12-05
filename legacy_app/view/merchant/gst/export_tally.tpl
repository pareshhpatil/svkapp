
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->

    <div class="row">


        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong></strong>{$success}
            </div> 
        {/if}
        {if isset($error)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong></strong>{$success}
            </div> 
        {/if}
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->

            <div class="portlet">
                <div class="portlet-body">

                    <form class="form-inline" method="post" action="" role="form">
                        <div class="form-group">
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="dd M yyyy"  name="date_range" type="text" value="{if isset($post.from_date)}{$post.from_date} - {$post.to_date}{/if}" placeholder="Date"/>
                        </div>
                        <div class="form-group">
                            <select required="" class="form-control" name="type">
                                <option>Select Voucher</option>
                                <option {if $post.type=='RI'} selected{/if} value="RI">Sales Voucher</option>
                                <option {if $post.type=='C'} selected{/if} value="C">Credit Notes Voucher</option>
                                <option {if $post.type=='D'} selected{/if} value="D">Debit Notes Voucher</option>
                            </select>
                        </div>
                        {if empty($data)}
                            <input type="hidden" name="gstin" value="">
                        {else}
                            <div class="form-group">
                                <select class="form-control" name="gstin">
                                    <option value="">Select GSTIN</option>
                                    {foreach from=$data key=k item=v}
                                        <option {if $post.gstin==$v.gstin} selected{/if} value="{$v.gstin}">{$v.company_name} - {$v.gstin}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        
                        <!--
                        <div class="form-group">
                            <input class="form-control form-control-inline" id="demo" type="text" required value="{$post.to_date}" name="to_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="To date"/>
                        </div>
                        -->

                        <div class="form-group">
                            <input type="submit" name="submit" class="btn blue" value="Export" />
                        </div>
                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
    {if !empty($list)}
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet ">
                    <div class="portlet-body">
                        <table class="table table-striped  table-hover" id="table-no-export">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        #
                                    </th>
                                    <th class="td-c">
                                        Date
                                    </th>
                                    <th class="td-c">
                                        Type
                                    </th>
                                    <th class="td-c">
                                        From date
                                    </th>
                                    <th class="td-c">
                                        To date
                                    </th>
                                    <th class="td-c">
                                        GST number
                                    </th>
                                    <th class="td-c">
                                        Status
                                    </th>
                                    <th class="td-c">

                                    </th>



                                </tr>
                            </thead>
                            <tbody>
                            <form action="" method="">
                                {foreach from=$list key=kk item=v}
                                    <tr>
                                        <td class="td-c">
                                            {$v.id}
                                        </td>
                                        <td class="td-c">
                                            {$v.created_date}
                                        </td>
                                        <td class="td-c">
                                            {if $v.invoice_type=='RI'}
                                                Sales Voucher
                                            {elseif $v.invoice_type=='C'}
                                                Creditnote Voucher
                                            {else}
                                                Debitnote Voucher
                                            {/if}
                                        </td>
                                        <td class="td-c">
                                            {$v.from_date|date_format:"%d-%m-%Y"}
                                        </td>
                                        <td class="td-c">
                                            {$v.to_date|date_format:"%d-%m-%Y"}
                                        </td>
                                        <td class="td-c">
                                            {$v.gstin}
                                        </td>
                                        <td class="td-c">
                                            {if $v.status==0}
                                                <span class="label label-sm label-default"> Processing</span>
                                            {else if $v.status==2}
                                                <span class="label label-sm label-warning"> No data</span>
                                            {else}
                                                <span class="label label-sm label-success"> Completed</span>
                                            {/if}
                                        </td>
                                        <td class="td-c">
                                            {if $v.status==1}
                                                <a href="/merchant/tallyexport/download/{$v.link}"  class="btn btn-xs blue"> Download </a>
                                            {/if}
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
    {/if}

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->



