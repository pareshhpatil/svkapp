@extends('app.master')
<style>
    .ajax-request-error {
        display: none;
    }
</style>
@section('content')
    <div class="page-content">
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render() }}
        </div>
        <!-- BEGIN SEARCH CONTENT-->
        <div class="row">
            @include('layouts.alerts')
            <div class="alert alert-danger ajax-request-error">
                <button type="button" class="close close-ajax-request-error"></button>
                <strong>Error!</strong>
                <div class="media">
                </div>
            </div>
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet">

                    <div class="portlet-body">
                        <form action="/merchant/subusers/create" method="post" id="create_user_form" class="form-horizontal form-row-sepe">
                            {{ csrf_field() }}
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="email" required name="email_id" class="form-control" value="{{ old('email_id') }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">First name <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" required name="first_name" class="form-control" value="{{ old('first_name') }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Last name <span class="required">*
                                                </span>
                                            </label>
                                            <div class="col-md-4">
                                                <input type="text" required name="last_name" class="form-control" value="{{ old('last_name') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Role<span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <select required id="role" class="form-control select2me" name="role">
                                                        <option value="">Select Role</option>
                                                        @foreach($briqRoles as $briqRole)
                                                            <option value="{{$briqRole->id}}">{{$briqRole->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <a href="#add-role-modal" class="btn green" data-toggle="modal" style="">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </span>
                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                </div>
                            </div>
                            <!-- End profile details -->

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <a href="{!! url('/merchant/subusers') !!}" class="btn default">Cancel</a>
                                            <input type="button" value="Save" class="btn blue save-user-btn"/>
                                            <input type="hidden" data-user-id="" data-user-name="" class="open-privileges-drawer-btn">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
    <!-- END CONTENT -->

    <div class="modal fade" id="add-role-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/merchant/roles/create" class="form-horizontal form-row-sepe" id="role-form">
                    {{ csrf_field() }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add new role</h4>
                    </div>
                    <div class="modal-body">
                        <div class="portlet-body form">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Name <span class="required">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" minlength="2" maxlength="50" name="name" class="form-control" placeholder="Role name" value="{{ old('name') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Description </label>
                                            <div class="col-md-8">
                                                <input type="text" minlength="1" name="description" class="form-control" placeholder="Description" value="{{ old('description') }}">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn blue" id="save-role-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('app.merchant.subuser.privileges-modal')
    <script src="/assets/admin/layout/scripts/invoiceformat.js?version={{time()}}" type="text/javascript"></script>

    <script>
        $(function () {
            let roleForm = $("#role-form");
            let saveRoleBtn = $("#save-role-btn");
            let selectRole = $("#role");
            let userForm = $('#create_user_form');
            let saveUserBtn = userForm.find('.save-user-btn');
            let adminRoleID = {{$adminID}};
            let ajaxReqError = $('.ajax-request-error');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            roleForm.find('input[name="name"]').on("keydown", function() {
                roleForm.find(".validation-errors").fadeOut().remove();
            })


            saveRoleBtn.on("click", function () {
                let data = {
                    name: roleForm.find('input[name="name"]').val(),
                    description: roleForm.find('input[name="description"]').val()
                }

                loader();
                $.ajax({
                    url: '/merchant/roles/create/ajax',
                    type: 'POST',
                    data: data,
                    success: function(data) {
                        document.getElementById('loader').style.display = 'none';
                        if(data.success == 'true') {
                            selectRole.append(`<option value="${data.data.id}">${data.data.name}</option>`);
                            $('#add-role-modal').modal('hide')
                        }

                        if(data.success == 'false') {
                            let errors = data.messages;
                            errors.name.forEach((err, i) => {
                                let html = `<div class="alert alert-danger validation-errors">${err}</div>`;
                                roleForm.find(".modal-header").append(html);

                            })
                        }

                    }
                });
            });

            saveUserBtn.on("click", function() {
                let roleID = userForm.find("#role").val();
                // let userID = userForm.find('input[name="user_id"]').val();
                let openPrivilegesBtn = userForm.find('.open-privileges-drawer-btn');

                if(roleID && roleID != adminRoleID) {
                    let data = {
                        first_name: userForm.find('input[name="first_name"]').val(),
                        last_name: userForm.find('input[name="last_name"]').val(),
                        email_id: userForm.find('input[name="email_id"]').val(),
                        role: roleID
                    };

                    $.ajax({
                        url: `/merchant/subusers/create/ajax`,
                        type: 'POST',
                        data: data,
                        success: function(data) {
                            if(data.success == true) {
                                openPrivilegesBtn.attr('data-user-id', data.user.user_id)
                                openPrivilegesBtn.attr('data-user-name', data.user.first_name)
                                openPrivilegesBtn.click();
                            }

                            if(data.success == false) {

                                let errors = Object.values(data.messages);

                                errors.forEach(error => {
                                    let html = `<p class="media-heading">${error.join('')}</p>`;
                                    ajaxReqError.append(html);
                                    ajaxReqError.css({display:'block'});
                                })
                            }

                        }
                    });
                } else {
                    userForm.submit();
                }
            })

            $(".close-ajax-request-error").on("click", function () {
                ajaxReqError.find('.media-heading').remove()
                ajaxReqError.css({display:'none'})
            })
        })
    </script>
@endsection