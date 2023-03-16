<!-- BEGIN CONTAINER -->

<!-- BEGIN CONTENT -->
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="col-md-1"></div>
    <div class="thank-container">
        <br>
        <h3 class="page-title">Verification successful </h3>
        <br>
        <div class="note note-success">
            <h4 class="block">Thank you for completing your registration with Briq.</h4>

            <p>
                @if($status == 'activated')
                You have already verified your email id with Briq. You can now start using Swipez via your account <a href="{!! config('app.url') !!}/merchant/register/dashboard">Dashboard</a>
                @else
                Now you can collect payments and keep track of all your transactions with a single login.<br><a href="{!! config('app.url') !!}/merchant/register/dashboard">Click here</a> to access your dashboard and start collecting payments from your consumer. If you have any queries, please write to us on <a href="{!! config('app.url') !!}/helpdesk" class="iframe">contact us. </a>
                @endif
            </p>
        </div>
    </div>

    <div class="clearfix">
    </div>
    <!-- END PAGE CONTENT-->
</div>
<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<style>
    .thank-container {
        width: 83%;
        margin: 0 auto;
        padding-right: 15px;
        padding-left: 15px;
        font-family: sans-serif;
    }

    .page-title{
        color: #859494;
        font-weight: 600;
        font-size: 1.5rem;
        letter-spacing: 0;
        margin-bottom: 0;
        margin-top: 4px;
    }

    .note {
        margin: 0 0 20px 0;
        padding: 15px 30px 15px 15px;
        border-left: 5px solid #eee;
        -webkit-border-radius: 0 4px 4px 0;
        -moz-border-radius: 0 4px 4px 0;
        -ms-border-radius: 0 4px 4px 0;
        -o-border-radius: 0 4px 4px 0;
        border-radius: 0 4px 4px 0;
    }

    .note.note-success {
        background-color: #f3fbfe;
        border-color: #3E4AA3;
        color: #3E4AA3;
    }

    .block {
        font-size: 17px;
        font-weight: 400;
    }

    p{
        font-size: 13px;
        font-weight: 400;
        line-height: 1.5;
    }

</style>