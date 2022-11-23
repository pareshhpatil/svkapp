<h3 class="modal-title">{{ucwords($integrationData->title)}}

    <a class="close " data-toggle="modal" onclick="return closeSidePanel();">
        <button type="button" class="close" aria-hidden="true"></button></a>
</h3>
<hr>
<div class="portlet light ">
    <div class="portlet-body form">
        <div class="subscription-info">

            <div class="row">
                
                <div class="col-md-8">
                    @if($integrationData->integration_type!=11)
                    @if($getActivePGDetails)
                    <div class="alert alert-info mt-1">
                        You already have a payment gateway assigned to your account. In case you want your payment integration changed, please email us on <a href="mailto:support@swipez.in">support@swipez.in</a>
                    </div>
                    @else
                    <div class="alert alert-info mt-1">
                        @if($checkKYCComplete==1)
                        please email us on <a href="mailto:support@swipez.in">support@swipez.in</a> to activate Payment gateway to your account.
                        @else
                        To activate payment gateway please complete KYC process first.
                        To complete KYC process <a href="{{route('stripe.connect')}}">click here</a>
                        @endif
                    </div>
                    @endif
                    @endif

                    @if($integrationData->integration_type==11)
                    <br>
                    <form action="/merchant/stripe-setup/save" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-4">Account ID</label>
                            <div class="col-md-8">
                                <div class="input-icon right">
                                    <input type="text" required name="stripe_user_id" value="{{$getActivePGDetails->pg_val1??''}}" class="form-control ">
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <div class="form-group">
                            <label class="control-label col-md-4">Stripe Secret</label>
                            <div class="col-md-8">
                                <div class="input-icon right">
                                    <input type="text" required name="secret" value="{{$getActivePGDetails->pg_val4??''}}" class="form-control ">

                                </div>
                            </div>
                        </div>

                        <br>
                        <br>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" name="refresh_token">
                                        <input type="hidden" name="pg_id" value="{{$pg_id??''}}" >
                                        <a onclick="closeSidePanel();" class="btn default">Cancel</a>
                                        <input type="submit" value="Save" class="btn blue" data-cy="expense_dave" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>