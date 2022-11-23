                                
<div class="row ">            
    <div class="col-md-12" >
        {if isset($haserrors)}
            <div class="alert alert-danger" style="text-align:left;">
                <button type="button" class="close" data-dismiss="alert"></button>
                <p>{$haserrors}</p>
            </div>
        {/if}
        <div class="portlet-body form" style="text-align: -webkit-center;text-align: -moz-center;">
            <form action="" method="POST" id="template_create" class="form-horizontal form-row-sepe">
                <div class="form-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="page-title" style="margin-left: 0px !important;text-align: left;">My bills</h3>
                                <div class="col-md-4" style="text-align: right;"><label class="control-label">Enter your Email ID / Mobile No. / Landline No. / {if $merchant_setting.customer_code_label!=''} {$merchant_setting.customer_code_label}{else} Customer code{/if}<span class="required"> * </span></label></div>
                                <div class="col-md-4">

                                    <div class="input-group">
                                        <input type="text" required {if isset($selected)}value="{$selected}"{/if} name="user_id" class="form-control">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn blue "><i class="fa fa-check"></i> Next</button>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4" style="text-align: right;"><label class="control-label">Captcha <span class="required">*
                                        </span></label></div>
                                <div class="col-md-4" style="text-align: left;">
                                    <form id="comment_form" action="form.php" method="post">
                                        <div class="g-recaptcha" data-sitekey="{$capcha_key}"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </form>
            {if (empty($requestlist))}                
                <div class="border panel-info" style="text-align: left; ">
                    <div class="panel-heading">
                        <h3 class="panel-title">Help information to find your bills</h3>
                    </div>
                    <div class="panel-body">


                        <div class="portlet-body" style="text-align: left;">
                            <p>You could search for your pending bills by entering either your email ID OR mobile number OR landline number OR merchant provided account identifier. 
                            </p>
                            <ul>
                                <li>
                                    {if $merchant_setting.customer_code_label!=''} {$merchant_setting.customer_code_label}{else} Customer code{/if} : {if $merchant_setting.customer_code_label!=''} {$merchant_setting.customer_code_label}{else} Customer code{/if} is assigned by a merchant to each consumer. This maybe either a number OR alpha-numeric ID which should be part of the bill presented to you by your merchant.
                                </li>
                                <li>
                                    Email ID : Email ID you have provided your merchant for billing
                                </li>
                                <li>
                                    Mobile number : Mobile number you have provided your merchant. Enter without country code (eg.9820123456)
                                </li>
                                <li>
                                    Landline number : Landline number you have provided your merchant. Enter without area code.
                                </li>
                                

                            </ul>
                            <br>
                            <p>
                                If in case you are unable to locate your bill, please get in touch with your merchant/service provider and update your details. We will make sure it reflects correctly on Swipez.
                            </p>
                        </div>
                    </div>
                </div>
                <br>   
            {/if}

        </div>



    </div>


    <div class="row no-margin">            
        <div class="col-md-12" style="text-align: -webkit-center;text-align: -moz-center;">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            {if (!empty($requestlist))}
                <div class="portlet " style="max-width: 1000px;">
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>

                                        <th >
                                            Sent on
                                        </th>
                                        <th >
                                            Due date
                                        </th>
                                        <th >
                                            Company name
                                        </th>
                                        <th >
                                        {$customer_default_column.customer_code|default:'Customer code'}
                                        </th>
                                        <th >
                                        {$customer_default_column.customer_name|default:'Customer name'}
                                        </th>
                                        <th >
                                            Amount
                                        </th>
                                        <th class="td-c">
                                            Status
                                        </th>
                                        <th class="td-c">
                                            View
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <form action="" method="">
                                    {foreach from=$requestlist item=v}
                                        <tr>
                                            <td>
                                                {{$v.received_date}|date_format:"%Y-%m-%d"}
                                            </td>
                                            <td>
                                                {{$v.due_date}|date_format:"%Y-%m-%d"}
                                            </td>
                                            <td>
                                                {$v.name}
                                            </td>
                                            <td>
                                                {$v.customer_code}
                                            </td>
                                            <td>
                                                {$v.patron_name}
                                            </td>

                                            <td>
                                                {$v.absolute_cost}
                                            </td>
                                            <td class="td-c">
                                                {if {$v.status}=='Submitted'} <span class="label label-sm label-success">Submitted
                                                    </span> {else} <span class="label label-sm label-danger">Failed
                                                    </span> {/if}
                                                </td>
                                                <td class="td-c"> 
                                                    <a href="{$v.paylink}" class="btn btn-xs blue"><i class="fa fa-table"></i> Pay </a>
                                                </td>
                                            </tr>

                                            {/foreach}
                                            </form>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {else}
                                <h3>{$empty_message}</h3>
                                {/if}
                                    <!-- END PAYMENT TRANSACTION TABLE -->
                                    <hr/>
                                    <p>&copy; {$current_year} OPUS Net Pvt. Handmade in Pune. <a target="_BLANK" href="https://www.swipez.in"><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></a></p>
                                </div>
                            </div>
                        </div>	


                    </div>
                </div>
            </form>
        </div>

    </div>	
    <!-- END PAGE CONTENT-->
</div>
</div>