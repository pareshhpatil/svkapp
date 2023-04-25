@extends('app.master')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
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
                        <form action="{{ route('merchant.subusers.update', $user->user_id) }}" onsubmit="loader();" method="post" id="update_user_form" class="form-horizontal form-row-sepe">
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
                                            <label class="control-label col-md-4">First name <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" required name="first_name" class="form-control" value="{{ $user->first_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Last name <span class="required">*
                                                </span>
                                            </label>
                                            <div class="col-md-4">
                                                <input type="text" required name="last_name" class="form-control" value="{{ $user->last_name }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Role<span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <select required id="role" class="form-control select2me" name="role">
                                                    <option value="">Select Role</option>
                                                    @foreach($briqRoles as $briqRole)
                                                        <option value="{{$briqRole->id}}" {{ ($selected_role_id == $briqRole->id) ? 'selected' : '' }}>{{$briqRole->name}}</option>
                                                    @endforeach
                                                </select>
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
                                            <input type="hidden" name="user_id" value="{{$user->user_id}}"/>
                                            <a href="{!! url('/merchant/subusers') !!}" class="btn default">Cancel</a>
                                            <input type="button" value="Update" class="btn blue update-btn"/>
                                            <input type="hidden" data-user-id="{{$user->user_id}}" data-user-name="{{$user->first_name}}" class="open-privileges-drawer-btn">
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
    @include('app.merchant.subuser.privileges-modal')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/assets/admin/layout/scripts/invoiceformat.js?version={{time()}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(function () {
            let updateBtn = $('.update-btn');
            let userForm = $('#update_user_form');
            let adminRoleID = {{$adminID}};


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            updateBtn.on("click", function() {
                let roleID = userForm.find("#role").val();
                let userID = userForm.find('input[name="user_id"]').val();
                let openPrivilegesBtn = userForm.find('.open-privileges-drawer-btn');

                if(roleID != adminRoleID) {
                    let data = {
                        first_name: userForm.find('input[name="first_name"]').val(),
                        last_name: userForm.find('input[name="last_name"]').val(),
                        role: roleID
                    };

                    $.ajax({
                        url: `/merchant/subusers/${userID}/edit/ajax`,
                        type: 'POST',
                        data: data,
                        success: function(data) {
                            if(data.success == true) {
                                openPrivilegesBtn.click();
                            }

                            if(data.success == false) {
                                let ajaxReqError = $('.ajax-request-error');
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