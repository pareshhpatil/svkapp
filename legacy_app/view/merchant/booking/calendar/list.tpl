
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
     <div class="page-bar">
        {include file="../../../common/breadcumbs.tpl" title={$title} links=$links}
        <a class="btn blue-madison pull-right" style="margin-left:10px;" target="_BLANk" href="https://api.whatsapp.com/send?text=Book time slots and venues hosted by {$company_name} at your convenience. Click here to view available slots - {$booking_link}">
            <i class="fa fa-whatsapp"></i>&nbsp; Share on Whatsapp</a>
        <a href="javascript:;"  class="btn green pull-right bs_growl_show" data-clipboard-action="copy" data-clipboard-target="abc1" style="margin-left: 10px;"><i class="fa fa-clipboard"></i> &nbsp; Copy booking link</a>
        <a href="/merchant/bookings/addcalendar" class="btn blue pull-right"><i class="fa fa-plus"></i>&nbsp; New calendar</a>
        <div style="font-size: 0px;"><abc1>{$booking_link}</abc1></div>
    </div>
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
                                    Calendar title
                                </th>
                                <th class="td-c">
                                    Category name
                                </th>
                                <th class="td-c">
                                    Created date
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
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.calendar_id}
                                    </td>
                                    <td class="td-c">
                                        {$v.calendar_title}
                                    </td>
                                    <td class="td-c">
                                        {$v.category_name}
                                    </td>
                                    <td class="td-c">
                                        {$v.created_date}
                                    </td>
                                    <td class="td-c">
                                        {if $v.calendar_active==0}
                                            <span class="label label-sm label-warning">Draft</span>
                                        {else}
                                            <span class="label label-sm label-success">Published</span>
                                        {/if}
                                    </td>

                                    <td class="td-c">
                                        <div class="btn-group dropup">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    {if $v.calendar_active==0}
                                                        <a href="/merchant/bookings/publishstatus/{$v.encrypted_id}/12" ><i class="fa fa-upload"></i> Publish </a>
                                                    {else}
                                                        <a href="/merchant/bookings/publishstatus/{$v.encrypted_id}/02" ><i class="fa fa-download"></i> Unpublish </a>
                                                    {/if}
                                                </li>
                                                <li>
                                                    <a href="/merchant/bookings/managecalendar/slot/{$v.encrypted_id}" ><i class="fa fa-chain"></i> Manage Calendar </a>
                                                </li>
                                                <li>
                                                    <a href="/merchant/bookings/reservations/{$v.calendar_id}" ><i class="fa fa-users"></i> Reservations </a>
                                                </li>
                                                <li>
                                                    <a href="#updatecat" onclick="setvalue({$v.calendar_id}, '{$v.calendar_title}', '{$v.category_id}', '{$v.category_name}', '{$v.description}', '{$v.logo}', '{$v.booking_unit}', '{$v.max_booking}');"
                                                       data-toggle="modal" ><i class="fa fa-edit"></i> Update </a>
                                                </li>
                                                <li>
                                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/bookings/delete/2/{$v.encrypted_id}'" data-toggle="modal" ><i class="fa fa-times"></i> Delete </a>
                                                </li>
                                        </div>
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
                            <form class="form-horizontal" action="/merchant/bookings/updatecalendar" method="post"  id="submit_form"  enctype="multipart/form-data">
                                {CSRF::create('booking_calender_update')}
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-5 control-label">Category name</label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline" id="cat_id" name="category" required="" type="hidden" value="" />
                                            <input class="form-control form-control-inline" id="cat_name" readonly type="text" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-5 control-label">Calendar title <span class="required">* </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline" maxlength="45" id="calendar_title" name="calendar_name" required="" type="text" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-5 control-label">Booking unit <span class="required">* </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline" maxlength="20" id="booking_unit" name="booking_unit" required="" type="text" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-5 control-label">Max booking allowed <span class="required">* </span></label>
                                        <div class="col-md-5">
                                            <select name="max_booking" id="max_booking" required class="form-control" data-placeholder="Select...">
                                                <option value="0">Unlimited</option>
                                                {for $foo=1 to 10}
                                                    <option value="{$foo}"> {$foo}</option>
                                                {/for}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-5 control-label">Description</label>
                                        <div class="col-md-5">
                                            <textarea class="form-control form-control-inline" id="description" name="description" ></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-5 control-label">Image</label>
                                        <div class="col-md-5 fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 130px;">
                                                <img class="img-responsive templatelogo" id="img_logo" src="" alt=""/>
                                            </div>
                                            <div  class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                            </div>
                                            <div>
                                                <span class="btn btn-sm default btn-file">
                                                    <span class="fileinput-new">
                                                        Update image </span>
                                                    <span class="fileinput-exists">
                                                        Change </span>
                                                    <input onchange="return validatefilesize(500000);" id="imgupload" type="file" accept="image/*"  name="uploaded_file">
                                                </span>
                                                <a href="javascript:;" id="imgdismiss" class="btn-sm btn default fileinput-exists" data-dismiss="fileinput">
                                                    Remove </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-5"></div>
                                    <div class="col-md-5 center">
                                        <input type="hidden" id="image_name" name="image_name"  />
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
    function setvalue(id, title, cat_id, cat_name, description, image, unit, max)
    {
        document.getElementById('cal_id').value = id;
        document.getElementById('calendar_title').value = title;
        document.getElementById('cat_name').value = cat_name;
        document.getElementById('cat_id').value = cat_id;
        document.getElementById('description').innerHTML = description;
        document.getElementById('img_logo').src = image;
        document.getElementById('image_name').value = image;
        document.getElementById('booking_unit').value = unit;
        document.getElementById('max_booking').value = max;
    }
</script>