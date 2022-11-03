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
        <div class="col-md-12">

            {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>{$success}
            </div> {/if}

            <!-- BEGIN PORTLET-->



            <!-- END PORTLET-->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            {if empty($list)}
                <div class="alert alert-danger alert-dismissable">
                    <div class="media" style="text-align:left">
                        <strong>Error!</strong> Did not get a response from GST system. Please try again later.
                    </div>
                </div>
            {/if}

            <div class="portlet ">
                <div class="portlet-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab">
                                Error </a>
                        </li>
                        <li>
                            <a href="#tab_1_2" data-toggle="tab">
                                Warning </a>
                        </li>
                        <li>
                            <a href="#tab_1_3" data-toggle="tab">
                                Success </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_1_1">
                            <table class="table table-striped  table-hover">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            Invoice No
                                        </th>
                                        <th class="td-c">
                                            Bill date
                                        </th>
                                        <th class="td-c">
                                            Type
                                        </th>
                                        <th class="td-c">
                                            Month
                                        </th>
                                        <th class="td-c">
                                            Amount
                                        </th>
                                        <th class="td-c">
                                            GSTIN
                                        </th>

                                        <th class="td-c">
                                            Status
                                        </th>
                                        <th class="td-c">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$list item=v}
                                        {if $v.deleted!=1}
                                            {if $v.hasError==1}
                                                <tr>
                                                    <td class="td-c">
                                                        {$v.inum}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.idt}
                                                    </td>
                                                    <td class="td-c">
                                                        {if $v.dty=='RI'}
                                                            Sales
                                                        {else}
                                                            Credit Note
                                                        {/if}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.fp}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.val}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.gstin}
                                                    </td>
                                                    <td class="td-c">
                                                        {if $v.hasError==1}
                                                            <a href="/merchant/gst/showinvoice/error/{$v.gstin}{$v.id}"
                                                                class="iframe btn btn-xs red"> Error </a>
                                                        {/if}
                                                        {if $v.hasWarning==1}
                                                            <a href="/merchant/gst/showinvoice/warning/{$v.gstin}{$v.id}"
                                                                class="iframe btn btn-xs yellow"> Warning </a>
                                                        {/if}
                                                        {if $v.hasWarning==0 && $v.hasError==0}
                                                            <span class="label label-sm label-success">
                                                                Validated
                                                            </span>
                                                        {/if}
                                                    </td>
                                                    <td class="td-c">
                                                        <a href="/merchant/gst/showinvoice/view/{$v.gstin}{$v.id}" target="_BLANK"
                                                            class="btn btn-xs green"> View </a>
                                                        {if $v.deleted!=1}
                                                            <a href="#basic"
                                                                onclick="document.getElementById('deleteanchor').href = '/merchant/gst/deleteinvoice/{$link}/{$v.fp}{$v.gstin}{$v.id}'"
                                                                data-toggle="modal" class="btn btn-xs red"> Delete </a>
                                                        {/if}
                                                    </td>
                                                </tr>
                                            {/if}
                                        {/if}

                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="tab_1_2">
                            <table class="table table-striped  table-hover" >
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            Invoice No
                                        </th>
                                        <th class="td-c">
                                            Bill date
                                        </th>
                                        <th class="td-c">
                                            Type
                                        </th>
                                        <th class="td-c">
                                            Month
                                        </th>
                                        <th class="td-c">
                                            Amount
                                        </th>
                                        <th class="td-c">
                                            GSTIN
                                        </th>

                                        <th class="td-c">
                                            Status
                                        </th>
                                        <th class="td-c">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$list item=v}
                                        {if $v.deleted!=1}
                                            {if $v.hasWarning==1 && $v.hasError==0}
                                                <tr>
                                                    <td class="td-c">
                                                        {$v.inum}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.idt}
                                                    </td>
                                                    <td class="td-c">
                                                        {if $v.dty=='RI'}
                                                            Sales
                                                        {else}
                                                            Credit Note
                                                        {/if}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.fp}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.val}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.gstin}
                                                    </td>
                                                    <td class="td-c">
                                                        {if $v.hasError==1}
                                                            <a href="/merchant/gst/showinvoice/error/{$v.gstin}{$v.id}"
                                                                class="iframe btn btn-xs red"> Error </a>
                                                        {/if}
                                                        {if $v.hasWarning==1}
                                                            <a href="/merchant/gst/showinvoice/warning/{$v.gstin}{$v.id}"
                                                                class="iframe btn btn-xs yellow"> Warning </a>
                                                        {/if}
                                                        {if $v.hasWarning==0 && $v.hasError==0}
                                                            <span class="label label-sm label-success">
                                                                Validated
                                                            </span>
                                                        {/if}
                                                    </td>
                                                    <td class="td-c">
                                                        <a href="/merchant/gst/showinvoice/view/{$v.gstin}{$v.id}" target="_BLANK"
                                                            class="btn btn-xs green"> View </a>
                                                        {if $v.deleted!=1}
                                                            <a href="#basic"
                                                                onclick="document.getElementById('deleteanchor').href = '/merchant/gst/deleteinvoice/{$link}/{$v.fp}{$v.gstin}{$v.id}'"
                                                                data-toggle="modal" class="btn btn-xs red"> Delete </a>
                                                        {/if}
                                                    </td>
                                                </tr>
                                            {/if}
                                        {/if}

                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="tab_1_3">
                            <table class="table table-striped  table-hover" id="table-no-export">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            Invoice No
                                        </th>
                                        <th class="td-c">
                                            Bill date
                                        </th>
                                        <th class="td-c">
                                            Type
                                        </th>
                                        <th class="td-c">
                                            Month
                                        </th>
                                        <th class="td-c">
                                            Amount
                                        </th>
                                        <th class="td-c">
                                            GSTIN
                                        </th>

                                        <th class="td-c">
                                            Status
                                        </th>
                                        <th class="td-c">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    {foreach from=$list item=v}
                                        {if $v.deleted!=1}
                                            {if $v.hasWarning==0 && $v.hasError==0}
                                                <tr>
                                                    <td class="td-c">
                                                        {$v.inum}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.idt}
                                                    </td>
                                                    <td class="td-c">
                                                        {if $v.dty=='RI'}
                                                            Sales
                                                        {else}
                                                            Credit Note
                                                        {/if}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.fp}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.val}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.gstin}
                                                    </td>
                                                    <td class="td-c">
                                                        {if $v.hasError==1}
                                                            <a href="/merchant/gst/showinvoice/error/{$v.gstin}{$v.id}"
                                                                class="iframe btn btn-xs red"> Error </a>
                                                        {/if}
                                                        {if $v.hasWarning==1}
                                                            <a href="/merchant/gst/showinvoice/warning/{$v.gstin}{$v.id}"
                                                                class="iframe btn btn-xs yellow"> Warning </a>
                                                        {/if}
                                                        {if $v.hasWarning==0 && $v.hasError==0}
                                                            <span class="label label-sm label-success">
                                                                Validated
                                                            </span>
                                                        {/if}
                                                    </td>
                                                    <td class="td-c">
                                                        <a href="/merchant/gst/showinvoice/view/{$v.gstin}{$v.id}" target="_BLANK"
                                                            class="iframe btn btn-xs green"> View </a>
                                                        {if $v.deleted!=1}
                                                            <a href="#basic"
                                                                onclick="document.getElementById('deleteanchor').href = '/merchant/gst/deleteinvoice/{$link}/{$v.fp}{$v.gstin}{$v.id}'"
                                                                data-toggle="modal" class="btn btn-xs red"> Delete </a>
                                                        {/if}
                                                    </td>
                                                </tr>
                                            {/if}
                                        {/if}

                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
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


<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Invoice</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this invoice in the future?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>