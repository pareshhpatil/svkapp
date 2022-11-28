<!-- BEGIN CONTAINER -->

<!-- BEGIN CONTENT -->
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="col-md-1"></div>
    <div class="col-md-10">

        {if isset($haserrors)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Failure!</strong>
                <div class="media">
                    {foreach from=$haserrors item=v}

                        <p class="media-heading">{$v.0} - {$v.1}.</p>
                    {/foreach}
                </div>

            </div>

        {/if}
        {if isset($info_message)}
            <div class="alert alert-info">
                <div class="media">
                    {$info_message}                 <button type="button" class="close" data-dismiss="alert"></button>

                </div>
            </div>
        {/if}



        <div class="row">
            <div class="col-md-12">
                <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                <div class="portlet " id="form_wizard_1">
                    <div class="portlet-body form">
                        <form action="/merchant/profile/completesaved" class="form-horizontal"  id="submit_form" method="POST" enctype="multipart/form-data">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li>
                                            <a href="#tab1" data-toggle="tab" class="step ">
                                                <span class="number circle-c">
                                                    <i class="fa fa-briefcase fa18"></i> </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Company </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab2" data-toggle="tab" class="step ">
                                                <span class="number circle-c">
                                                    <i class="fa fa-address-book fa18"></i> </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Contact </span>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab3" data-toggle="tab" class="step ">
                                                <span class="number circle-c">
                                                    <i class="fa fa-university fa18"></i> </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Online payments </span>
                                            </a>
                                        </li>
                                        <li id="kycdiv" class="active">
                                            <a href="#tab4" data-toggle="tab" class="step active">
                                                <span class="number circle-c">
                                                    <i class="fa fa-id-card fa18"></i> </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> KYC information </span>
                                            </a>
                                        </li>

                                    </ul>
                                    <div id="bar" class="progress progress-striped" role="progressbar">
                                        <div class="progress-bar progress-bar-success">
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div id="error" class="alert alert-danger display-none">
                                            Please fill all required fields
                                        </div>
                                        <div class="alert alert-success display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            Your form validation is successful!
                                        </div>

                                        <div class="tab-pane active" id="tab3">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div id="gstav">
                                                        {if $account.gst_certificate != ''}
                                                            <label class="control-label col-md-5">GST certificate 
                                                            </label>
                                                            <div class="col-md-6">
                                                                <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$account.gst_certificate}">View doc</a>
                                                                    <a onclick="updateDoc(2, 1);" class="btn btn-xs blue">Update</a>
                                                                </span>
                                                                <span id="update2" style="display: none;">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a2');
                                                                            submitDoc();" id="a2" name="doc_gst_cer" >
                                                                    <span class="help-block red">* Max file size 1 MB
                                                                        <a onclick="updateDoc(2, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        {else}
                                                            <div id="gstdiv" {if $account.gst_available ==0}style="display: none;"{/if}>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-5">GST certificate <span class="required">*
                                                                        </span>
                                                                    </label>
                                                                    <div class="col-md-3">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a2');
                                                                                submitDoc();" id="a2" name="doc_gst_cer" >
                                                                        <span class="help-block red">* Max file size 1 MB</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        {/if}
                                                    </div>
                                                    <div class="col-md-12">
                                                    {if $account.cancelled_cheque != ''}
                                                     <div class="col-md-12">
                                                        <label class="control-label col-md-5">Cancelled cheque/ account statement 
                                                        </label>
                                                        <div class="col-md-6">
                                                            <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$account.cancelled_cheque}">View doc</a>
                                                                <a onclick="updateDoc(1, 1);" class="btn btn-xs blue">Update</a>
                                                            </span>
                                                            <span id="update1" style="display: none;">
                                                                <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a1');
                                                                        submitDoc();" id="a1" name="doc_cancelled_cheque" >
                                                                <span class="help-block red">* Max file size 1 MB
                                                                    <a onclick="updateDoc(1, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    {else}
                                                    <div class="col-md-12">
                                                        <div id="ccheque" class="form-group">
                                                         <div class="col-md-12">
                                                            <label class="control-label col-md-5">Cancelled cheque/ account statement <span class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a1');
                                                                        submitDoc();" id="a1" name="doc_cancelled_cheque" >
                                                                <span class="help-block red">* Max file size 1 MB</span>
                                                            </div>
                                                             </div>
                                                        </div>
                                                        </div>
                                                    {/if}
                                                    </div>
                                                    <div id="pvt" style="display: none;">
                                                     <div class="col-md-12">
                                                        {if !empty($account.address_proof)} 
                                                            <div class="col-md-12">
                                                                {foreach from=$account.address_proof key=k item=v}
                                                                    {$int=$k+68}
                                                                    <div id="address1">
                                                                        <label class="control-label col-md-5">Directors Address proof (Drivers license OR Election card OR Passport - both sides)
                                                                        </label>
                                                                        <div class="col-md-5">
                                                                            <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$v}">View doc</a>
                                                                                <a onclick="updateDoc({$int}, 1);" class="btn btn-xs blue">Update</a>
                                                                            </span>
                                                                            <span id="update{$int}" style="display: none;">
                                                                                <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a3');
                                                                                        submitDoc();" id="a3" name="address_prrof[]" >
                                                                                <span class="help-block red">* Max file size 1 MB
                                                                                    <a onclick="updateDoc({$int}, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <a onclick="addMoreAddressProof(1, 'Directors');" class="btn btn-xs blue">Add More Directors Proof</a>
                                                                        </div>
                                                                    </div>
                                                                {/foreach}   
                                                            </div>
                                                        {else}
                                                            {if $int==68}
                                                                <div id="address1">
                                                                {/if}
                                                                 <div class="col-md-12">
                                                                    <label class="control-label col-md-5">Directors Address proof (Drivers license OR Election card OR Passport - both sides) <span class="required">*
                                                                        </span>
                                                                    </label>
                                                                    <div class="col-md-5">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a3');
                                                                                submitDoc();" id="a3" name="address_prrof[]" >
                                                                        <span class="help-block red">* Max file size 1 MB</span>
                                                                    </div>
                                                                </div>
                                                                {if $int==68}
                                                                    <div class="col-md-1">
                                                                        <a onclick="addMoreAddressProof(1, 'Directors');" class="btn btn-xs blue">Add More Directors Proof</a>
                                                                    </div>
                                                                </div>
                                                            {/if}
                                                        {/if}
                                                         </div>
                                                        <div class="col-md-12">
                                                        {if $account.company_incorporation_certificate != ''}
                                                            <div class="col-md-12">
                                                                <label class="control-label col-md-5">Company incorporation certificate 
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$account.company_incorporation_certificate}">View doc</a>
                                                                        <a onclick="updateDoc(4, 1);" class="btn btn-xs blue">Update</a>
                                                                    </span>
                                                                    <span id="update4" style="display: none;">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a4');
                                                                                submitDoc();" id="a4" name="company_certificate" >
                                                                        <span class="help-block red">* Max file size 1 MB
                                                                            <a onclick="updateDoc(4, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        {else}
                                                         <div class="col-md-12">
                                                            <div class="form-group">
                                                             <div class="col-md-12">
                                                                <label class="control-label col-md-5">Company incorporation certificate<span class="required">*
                                                                    </span>
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a4');
                                                                            submitDoc();" id="a4" name="company_certificate" >
                                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                                </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        {/if}
                                                        </div>
                                                         <div class="col-md-12">
                                                        {if $account.company_moa != ''}
                                                            <div class="col-md-12">
                                                                <label class="control-label col-md-5">Upload MOA Document
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$account.company_incorporation_certificate}">View doc</a>
                                                                        <a onclick="updateDoc(33, 1);" class="btn btn-xs blue">Update</a>
                                                                    </span>
                                                                    <span id="update33" style="display: none;">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a4');
                                                                                submitDoc();" id="a4" name="company_moa" >
                                                                        <span class="help-block red">* Max file size 1 MB
                                                                            <a onclick="updateDoc(33, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        {else}
                                                         <div class="col-md-12">
                                                            <div class="form-group">
                                                            <div class="col-md-12">
                                                                <label class="control-label col-md-5">Upload MOA Document
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a4');
                                                                            submitDoc();" id="a4" name="company_moa" >
                                                                    <span class="help-block red">Max file size 1 MB</span>
                                                                </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        {/if}
                                                        </div>
                                                         <div class="col-md-12">
                                                        {if $account.company_aoa != ''}
                                                            <div class="col-md-12">
                                                                <label class="control-label col-md-5">Upload AOA Document
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$account.company_incorporation_certificate}">View doc</a>
                                                                        <a onclick="updateDoc(44, 1);" class="btn btn-xs blue">Update</a>
                                                                    </span>
                                                                    <span id="update44" style="display: none;">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a4');
                                                                                submitDoc();" id="a4" name="company_aoa" >
                                                                        <span class="help-block red">* Max file size 1 MB
                                                                            <a onclick="updateDoc(44, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        {else}
                                                         <div class="col-md-12">
                                                            <div class="form-group">
                                                             <div class="col-md-12">
                                                                <label class="control-label col-md-5">Upload AOA Document
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a4');
                                                                            submitDoc();" id="a4" name="company_aoa" >
                                                                    <span class="help-block red">Max file size 1 MB</span>
                                                                </div>
                                                                 </div>
                                                            </div>
                                                            </div>
                                                        {/if}
                                                        </div>
                                                    </div>

                                                    <div id="propriter"  style="display: none;">
                                                     <div class="col-md-12">
                                                        {if $account.adhar_card != ''}
                                                            <div class="col-md-12">
                                                                <label class="control-label col-md-5">{if $merchant.entity_type==6}Trust Owner's{else}Proprietors{/if} Address proof (Drivers license OR Election card OR Passport - both sides)
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$account.adhar_card}">View doc</a>
                                                                        <a onclick="updateDoc(6, 1);" class="btn btn-xs blue">Update</a>
                                                                    </span>
                                                                    <span id="update6" style="display: none;">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a6');
                                                                                submitDoc();" id="a6" name="doc_adhar_card" >
                                                                        <span class="help-block red">* Max file size 1 MB
                                                                            <a onclick="updateDoc(6, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        {else}
                                                         <div class="col-md-12">
                                                            <div class="form-group">
                                                             <div class="col-md-12">
                                                                <label class="control-label col-md-5">{if $merchant.entity_type==6}Trust Owner's{else}Proprietors{/if} Address proof (Drivers license OR Election card OR Passport - both sides)<span class="required">
                                                                    </span>
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a6');
                                                                            submitDoc();" id="a6" name="doc_adhar_card" >
                                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                                </div>
                                                                 </div>
                                                            </div>
                                                            </div>
                                                        {/if}
                                                        </div>
                                                        <div class="col-md-12">
                                                        {if $account.pan_card != ''}
                                                         <div class="col-md-12">
                                                                <label class="control-label col-md-5">{if $merchant.entity_type==6}Society{else}Business{/if} pan card
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$account.pan_card}">View doc</a>
                                                                        <a onclick="updateDoc(6, 1);" class="btn btn-xs blue">Update</a>
                                                                    </span>
                                                                    <span id="update6" style="display: none;">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a7');
                                                                                submitDoc();" id="a7" name="doc_pan_card" >
                                                                        <span class="help-block red">* Max file size 1 MB
                                                                            <a onclick="updateDoc(6, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                                   </div>
                                                        {else}
                                                         <div class="col-md-12">
                                                            <div class="form-group">
                                                             <div class="col-md-12">
                                                                <label class="control-label col-md-5">{if $merchant.entity_type==6}Society{else}Business{/if} pan card<span class="required">
                                                                    </span>
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a7');
                                                                            submitDoc();" id="a7" name="doc_pan_card" >
                                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                                </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        {/if}
                                                        </div>

                                                    </div>

                                                    <div id="llp"  style="display: none;">
                                                    <div class="col-md-12">
                                                        {if !empty($account.address_proof)} 
                                                            {foreach from=$account.address_proof key=k item=v}
                                                                {$int=$k+28}
                                                                {if $int==28}
                                                                    <div id="address3">
                                                                    {/if}
                                                                    <div class="col-md-12">
                                                                        <label class="control-label col-md-5">Partners Address proof (Drivers license OR Election card OR Passport - both sides)
                                                                        </label>
                                                                        <div class="col-md-5">
                                                                            <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$v}">View doc</a>
                                                                                <a onclick="updateDoc({$int}, 1);" class="btn btn-xs blue">Update</a>
                                                                            </span>
                                                                            <span id="update{$int}" style="display: none;">
                                                                                <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a{$int}');
                                                                                        submitDoc();" id="a{$int}" name="address_prrof[]" >
                                                                                <span class="help-block red">* Max file size 1 MB
                                                                                    <a onclick="updateDoc({$int}, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                        {if $int==28}
                                                                            <div class="col-md-1">
                                                                                <a onclick="addMoreAddressProof(3, 'Partners');" class="btn btn-xs blue">Add More Partners Proof</a>
                                                                            </div>
                                                                        </div>
                                                                    {/if}
                                                                </div>

                                                            {/foreach}
                                                        {else}
                                                        <div class="col-md-12">
                                                            <div id="address3">
                                                                <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <label class="control-label col-md-5">Partners Address proof (Drivers license OR Election card OR Passport - both sides)<span class="required">*
                                                                        </span>
                                                                    </label>
                                                                    <div class="col-md-5">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a9');
                                                                                submitDoc();" id="a9" name="address_prrof[]" >
                                                                        <span class="help-block red">* Max file size 1 MB</span>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <a onclick="addMoreAddressProof(3, 'Partners');" class="btn btn-xs blue">Add More Partners Proof</a>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                             </div>
                                                        {/if}
                                                    </div>
                                                        <div class="col-md-12">
                                                        {if !empty($account.partner_pan_card)} 
                                                            {foreach from=$account.partner_pan_card key=k item=v}
                                                                {$int=$k+48}
                                                                {if $int==48}
                                                                    <div id="addpan">
                                                                    {/if}
                                                                    <div class="col-md-12">
                                                                        <label class="control-label col-md-5">Partners pan card
                                                                        </label>
                                                                        <div class="col-md-5">
                                                                            <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$v}">View doc</a>
                                                                                <a onclick="updateDoc({$int}, 1);" class="btn btn-xs blue">Update</a>
                                                                            </span>
                                                                            <span id="update{$int}" style="display: none;">
                                                                                <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a{$int}');
                                                                                        submitDoc();" id="a{$int}" name="partner_pan_card[]" >
                                                                                <span class="help-block red">* Max file size 1 MB
                                                                                    <a onclick="updateDoc({$int}, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                        {if $int==48}
                                                                            <div class="col-md-1">
                                                                                <a onclick="addMorePanCard();" class="btn btn-xs blue">Add More</a>
                                                                            </div>
                                                                        </div>
                                                                    {/if}
                                                                </div>
                                                            {/foreach}
                                                        {else}
                                                            <div id="addpan">
                                                             <div class="col-md-12">
                                                                <div id="partnerpancard" class="form-group">
                                                                  <div class="col-md-12">
                                                                    <label class="control-label col-md-5">Partners pan card<span class="required">*
                                                                        </span>
                                                                    </label>
                                                                    <div class="col-md-5">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a10');
                                                                                submitDoc();" id="a10" name="partner_pan_card[]" >
                                                                        <span class="help-block red">* Max file size 1 MB</span>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <a onclick="addMorePanCard();" class="btn btn-xs blue">Add More</a>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </div>
                                                        {/if}
                                                        </div>
                                                        
                                                    <div class="col-md-12">
                                                        {if $account.partnership_deed != ''}
                                                            <div class="col-md-12">
                                                                <label class="control-label col-md-5">Partnership deed
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$account.partnership_deed}">View doc</a>
                                                                        <a onclick="updateDoc(61, 1);" class="btn btn-xs blue">Update</a>
                                                                    </span>
                                                                    <span id="update61" style="display: none;">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a11');
                                                                                submitDoc();" id="a11" name="partnership_deed" >
                                                                        <span class="help-block red">* Max file size 1 MB
                                                                            <a onclick="updateDoc(61, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        {else}
                                                         <div class="col-md-12">
                                                            <div class="form-group">
                                                             <div class="col-md-12">
                                                                <label class="control-label col-md-5">Partnership deed<span class="required">*
                                                                    </span>
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a11');
                                                                            submitDoc();" id="a11" name="partnership_deed" >
                                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                                </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        {/if}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" >
                                                        {if $account.business_reg_proof != ''}
                                                            <div class="col-md-12">
                                                                <label class="control-label col-md-5">{if $merchant.entity_type==6}Society{else}Business{/if} registration proof (Shop act, IE certificate)
                                                                </label>
                                                                <div class="col-md-6">
                                                                    <span class="help-block"><a class="btn btn-xs green" target="_BLANK" href="/uploads/documents/{$account.merchant_id}/{$account.business_reg_proof}">View doc</a>
                                                                        <a onclick="updateDoc(5, 1);" class="btn btn-xs blue">Update</a>
                                                                    </span>
                                                                    <span id="update5" style="display: none;">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a5');
                                                                                submitDoc();" id="a5" name="biz_reg_proof" >
                                                                        <span class="help-block red">* Max file size 1 MB
                                                                            <a onclick="updateDoc(5, 0);" class="btn btn-xs red"><i class="fa fa-remove"></i></a>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        {else}
                                                            <div id="biz_reg">
                                                             <div class="col-md-12">
                                                                <div class="form-group">
                                                                 <div class="col-md-12">
                                                                    <label class="control-label col-md-5">{if $merchant.entity_type==6}Society{else}Business{/if} registration proof (Shop act, IE certificate)<span id="reqspan" class="required">*</span> 
                                                                    </label>
                                                                    <div class="col-md-3">
                                                                        <input type="file" accept="image/*,application/pdf" onchange="validatefilesize(1000000, 'a5');
                                                                                submitDoc();" id="a5" name="biz_reg_proof" >
                                                                        <span class="help-block red">* Max file size 1 MB</span>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </div>
                                                        {/if}
                                                    </div>
                                                    <div {if $document_complete==1} {else}style="display:none;"{/if} id="confirmbox" class="form-group">
                                                        <label class="control-label col-md-3">&nbsp; 
                                                        </label>
                                                        <div class="col-md-9">
                                                            <br>
                                                            <label> <input type="checkbox" name="confirm" value="1" id="confirm"> I confirm that the documents uploaded are genuine business proofs related to the bank account and business mentioned by me. The submitted proofs can be used in case of queries and compliance requirements.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">

                                    <div class="row">
                                        <div class="col-4">
                                        <a href="/merchant/dashboard"  class="btn btn-link pull-left">
                                        <input type="hidden" value="" id="detail" name="detail">
                                        <input type="hidden" value="{$info.entity_type}" name="biz_type">
                                        <input type="hidden" name="form_type" value="document">
                                        <input type="hidden" id="submit_type" name="submit_type" value="">
                                        <input type="hidden" id="save_partial_data" name="save_partial_data" value="0">
                                        Back to Dashboard </a></div>
                                        <div class="col-8 hidden-xs" >
                                            <div class="col-12 no-padding">
                                                <input type="submit" {if $document_complete==1}class="btn blue pull-right" 
                                                {else}class="btn default pull-right" disabled=""{/if} id="submit_doc"  
                                                onclick="return validateConfirm(true, 1)" name="submit_document" value="Submit documents for verification"/>

                                                <button type="submit" onclick="validateConfirm(false, 0);" 
                                                name="skip_document" class="btn green pull-right  mr-1">
                                                    Save progress and submit later 
                                                </button>

                                                <a href="/merchant/profile/complete/bank" class="btn btn-link pull-right mr-1">
                                                    Back </a>
                                            </div>
                                        </div>

                                        <div class="col-8 visible-xs-block">
                                            <div class="col-12 no-padding">
                                                 <a href="/merchant/profile/complete/bank" class="btn btn-link pull-right mr-1">
                                                    Back </a>
                                            </div>
                                        </div>
                                        <div class="col-6 visible-xs-block">
                                            <div class="col-12 no-padding">
                                                <input type="submit" {if $document_complete==1}class="btn blue pull-right" 
                                                {else}class="btn default pull-right" disabled=""{/if} id="submit_doc"  
                                                onclick="return validateConfirm(true, 1)" name="submit_document" value="Submit documents for verification"/>
                                            </div>
                                        </div>
                                        <div class="col-6 visible-xs-block">
                                            <div class="col-12 no-padding">
                                                 <button type="submit" onclick="validateConfirm(false, 0);" 
                                                name="skip_document" class="btn green pull-right  mr-1">
                                                    Save progress and submit later 
                                                </button>
                                            </div>
                                        </div>

                                       

                                               
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix">
    </div>
    <!-- END PAGE CONTENT-->
</div>
<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<script>
    BizType({$info.entity_type});
</script>