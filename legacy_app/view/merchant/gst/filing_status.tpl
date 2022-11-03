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
            {if isset($error)}
                <div class="alert alert-danger alert-dismissable" style="">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <strong>Error!</strong> {$error}
                </div>
            {/if}

            <div class="alert alert-danger" style="display: none;" id="errorshow">
                <button class="close" onclick="document.getElementById('errorshow').style.display = 'none';"></button>
                <p id="error_display">You have some form errors. Please check below.</p>
            </div>
            <div class="formWpr">
                <div class="">

                        <div class="portlet">

                            <div class="portlet-body mb-2">

                                <form class="form-inline" action="" method="post" role="form">
                                    <div class="form-group">
                                        <label>Enter GSTIN</label>
                                        <input type="text" required name="gst_number" {$validate.gst_number} class="form-control" value="{$gst_number}">
                                    </div>
                                    <input type="submit" class="btn blue" value="Get details" />
                                </form>

                            </div>
                        </div>


                        
                </div>
                <!--  formWpr end  -->
                </section>
                <section class="">
                    <!-- Content Header (Page header) -->
                    <div class="col-md-12">
                        <!--
                        {$id=0}
                        {if isset($return_list.{$id}.dof)}
                            <div class="alert alert-block alert-info">
                                <table class="table" style="margin-bottom: 0;">
                                    <tr>
                                        <td style="border-top: none;">Last filing: {$return_list.{$id}.rtntype}</td>
                            {$date=$return_list.{$id}.ret_prd}
                    <td style="border-top: none;">Filed for: {$datearray.{$date|substr:0:2}}-{$date|substr:2}</td>
                    <td style="border-top: none;">Date of filing: {$return_list.{$id}.dof|date_format:"%d-%b-%Y"}</td>
                </tr>
            </table>
        </div>   
                        {/if}
                        -->
                        {if isset($filereturn_success)}
                            <div class="alert alert-block alert-success ml-0 mr-0">
                                <p style="margin-bottom: 0;">{$filereturn_success}</p>
                            </div>   
                        {/if}
                        {if isset($filereturn_error)}
                            <div class="alert alert-block alert-danger ml-0 mr-0">
                                <p style="margin-bottom: 0;">{$filereturn_error}</p>
                            </div>   
                        {/if}
                        {if !empty($det)}
                            <div class="form-section">GSTN Info</div>
                            <div class="portlet">

                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p></p>
                                            <div class="col-md-6" >
                                                <p><b>Name of business :</b> {if $det.tradename!=''}{$det.tradename}{else}{$det.name}{/if}</p>
                                                <p><b>Location :</b> {$det.pradr.loc}</p>
                                                <p><b>Tax payer type :</b> {$det.type}</p>
                                                <p><b>Status :</b> {$det.status}</p>
                                                <p><b>Tax return status :</b> {$det.status}</p>
                                                <p><b>Principle place of business :</b> {$det.pradr.bno}, {$det.pradr.st}, {$det.pradr.loc}, {$det.pradr.stcd}</p>
                                            </div>
                                            <div class="col-md-6" >
                                                <p><b>Constitution of business :</b> {$det.constitution}</p>
                                                <p><b>Nature of business :</b> {$det.nature}</p>
                                                <p><b>State jurisdiction :</b> {$det.state}</p>
                                                <p><b>Center jurisdiction :</b> {$det.center}</p>
                                                <p><b>Date of registration :</b> {{$det.registrationDate|substr:0:10}|date_format:"%d-%b-%Y"}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}
                        {if !empty($return_list)}
                            <div class="form-section">GSTR3B filing status</div>
                            <div class="portlet">
                                <div class="portlet-body">
                                    <div class="row no-margin">
                                        <div class="col-md-12"  >
                                            <div class="">
                                                <table class="table table-bordered table-no-export_class" >
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Date of filing</th>
                                                            <th>Return period</th>
                                                            <th>Return type</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody >
                                                        {foreach from=$return_list key=k item=v}
                                                            {if $v.rtntype=='GSTR3B'}
                                                                <tr>
                                                                    <td class="td-c">{$v.dof|date_format:"%Y-%m-%d"}</td>
                                                                    <td class="td-c">{$v.dof}</td>
                                                                    <td class="td-c">{$datearray.{$v.ret_prd|substr:0:2}}-{$v.ret_prd|substr:2}</td>
                                                                    <td class="td-c">{$v.rtntype}</td>
                                                                    <td class="td-c">{$v.status}</td>
                                                                </tr>
                                                            {/if}
                                                        {/foreach}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}
                        <br>
                        {if !empty($return_list)}
                            <div class="form-section">GSTR1 filing status</div>
                            <div class="portlet">
                                <div class="portlet-body">
                                    <div class="row no-margin">
                                        <div class="col-md-12"  >
                                            <div class="">
                                                <table class="table table-bordered table-no-export_class" >
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Date of filing</th>
                                                            <th>Return period</th>
                                                            <th>Return type</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody >
                                                        {foreach from=$return_list key=k item=v}
                                                            {if $v.rtntype=='GSTR1'}
                                                                <tr>
                                                                    <td class="td-c">{$v.dof|date_format:"%Y-%m-%d"}</td>
                                                                    <td class="td-c">{$v.dof}</td>
                                                                    <td class="td-c">{$datearray.{$v.ret_prd|substr:0:2}}-{$v.ret_prd|substr:2}</td>
                                                                    <td class="td-c">{$v.rtntype}</td>
                                                                    <td class="td-c">{$v.status}</td>
                                                                </tr>
                                                            {/if}
                                                        {/foreach}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    </div>
                </section>
                </section>
                <!-- /.content -->
                </form>
            </div>
        </div>
    </div>
</div>