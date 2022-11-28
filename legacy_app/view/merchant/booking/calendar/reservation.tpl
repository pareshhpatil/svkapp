
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
     <div class="page-bar">
        {include file="../../../common/breadcumbs.tpl" title={$title} links=$links}
        {if $calendar_id>0}
            <a href="/merchant/bookings/reservations/{$calendar_id}/export" class="btn btn-sm green pull-right">Excel export</a>
        {/if}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->

    {if $calendar_id==0}
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">

                    <div class="portlet-body" >

                        <form class="form-inline" role="form" action="" method="post">
                             <div class="form-group">
                            <input class="form-control form-control-inline input-sm date-picker" type="text" required="" value="{$from_date}" name="from_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="From date">
                        </div>

                        <div class="form-group">
                            <input class="form-control form-control-inline input-sm date-picker" type="text" required="" value="{$to_date}" name="to_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="To date">
                        </div>

                            <input type="submit" class="btn btn-sm blue" value="Search">
                            <input type="submit" name="export" class="btn btn-sm green" value="Excel export">
                        </form>

                    </div>
                </div>

                <!-- END PORTLET-->
            </div>
        </div>
    {/if}

    <div class="row">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=v}

                            <p class="media-heading">{$v.0} - {$v.1}.</p>
                        {/foreach}
                    </div>

                </div>
            {/if}
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->


            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    ID
                                </th>

                                <th class="td-c">
                                    Calendar detail
                                </th>
                                <th class="td-c">
                                    Time duration
                                </th>

                                <th class="td-c">
                                    {$customer_default_column.customer_name|default:'Customer name'}
                                </th>
                                <th class="td-c">
                                {$customer_default_column.customer_code|default:'Customer code'}
                                </th>
                                <th class="td-c">
                                    Email
                                </th>
                                <th class="td-c">
                                    Mobile
                                </th>
                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.id}
                                    </td>

                                    <td class="td-c">
                                        {$v.category_name} {$v.calendar_title}
                                    </td>
                                    <td class="td-c">
                                        {$v.slot}
                                    </td>
                                    <td class="td-c">
                                        {$v.paid_by}
                                    </td>
                                    <td class="td-c">
                                        {$v.customer_code}
                                    </td>
                                    <td class="td-c">
                                        {$v.email}
                                    </td>
                                    <td class="td-c">
                                        {$v.mobile}
                                    </td>
                                    <td class="td-c">
                                        {$v.calendar_date}
                                    </td>
                                    <td class="td-c">
                                        {$v.amount}
                                    </td>
                                    <td class="td-c">
                                        <a href="/merchant/transaction/receipt/{$v.encrypted_id}" class="iframe btn blue btn-xs"><i class="fa fa-file"></i> Receipt </a>
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

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete category</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this calendar in the future?
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

<div class="modal fade bs-modal-lg" id="updatecat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="portlet ">

                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-10">
                            <br>
                            <form class="form-horizontal" action="/merchant/bookings/updatecalendar" method="post"  id="submit_form" >
                                {CSRF::create('booking_calender_update')}
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-5 control-label">Category name</label>
                                        <div class="col-md-5">
                                            <select name="category" id="category_id" required class="form-control"  data-placeholder="Select...">
                                                <option value="">Select category name</option>
                                                {foreach from=$category_list item=v}
                                                    <option value="{$v.category_id}"> {$v.category_name}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-5 control-label">Calendar title</label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline" id="calendar_title" name="calendar_name" required="" type="text" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-5 control-label">Calendar admin email</label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline" id="admin_email" name="admin_email" type="text" value="" />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-3">
                                            <input type="hidden" id="cal_id" name="calendar_id"  />
                                            <button type="submit" class="btn blue">Update</button>
                                        </div>
                                    </div>
                                </div>


                            </form>		
                        </div>


                    </div>


                </div>
            </div>

        </div>

    </div>

</div>
<!-- /.modal-content -->
</div>

<script>
    function setvalue(id, title, cat_id, ad_email)
    {
        document.getElementById('cal_id').value = id;
        document.getElementById('calendar_title').value = title;
        document.getElementById('admin_email').value = ad_email;
        $('#category_id').val("" + cat_id + "").attr('selected', 'selected');
    }
</script>