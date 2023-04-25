@extends('app.master')
@section('content')
    <div class="page-content">
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render() }}
        </div>
        <!-- BEGIN SEARCH CONTENT-->
        <div class="row">
            @include('layouts.alerts')
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet light bordered">

                    <div class="portlet-body form">
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
                                            <input type="submit" value="Save" class="btn blue save-user-btn"/>
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

    <script>
        $(function () {
            let roleForm = $("#role-form");
            let saveRoleBtn = $("#save-role-btn");
            let selectRole = $("#role");

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
                        if(data.success == true) {
                            selectRole.append(`<option value="${data.data.id}">${data.data.name}</option>`);
                            $('#add-role-modal').modal('hide')
                        }

                        if(data.success == false) {
                            let errors = data.messages;
                            errors.name.forEach((err, i) => {
                                let html = `<div class="alert alert-danger validation-errors">${err}</div>`;
                                roleForm.find(".modal-header").append(html);

                            })
                        }

                    }
                });
            });

        })
    </script>
@endsection