
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}&nbsp;
    </h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">

        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div> {/if}

            <!-- BEGIN PORTLET-->

            <!-- END PORTLET-->
        </div>



        {if $gst_connection_failed==1}
            {if $showotp==1}
                <div class="row">
                    <div class="col-md-8">
                        <div class="alert alert-success">
                            <strong>Success!</strong> OTP has been sent
                        </div>
                    </div>
                </div>
            {else}
                <div class="row">
                    <div class="col-md-8">
                        <div class="alert alert-danger">
                            <strong>Error!</strong> GST Connection lost Please connect with OTP
                        </div>
                    </div>
                </div>
            {/if}
            <div class="row">
                <div class="col-md-8">
                    <form action="/merchant/gst/gstdraft" method="post">
                        {CSRF::create('gst_draft')}
                        <input type="hidden" name="fp" value="{$month}">
                        <input type="hidden" name="gstin" value="{$gstin}">
                        <input type="hidden" name="month" value="{$month}{$gstin}">
                        {if $showotp==1}
                            <div class="form-group">
                                <label class="control-label col-md-1">OTP <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" name="otp_text" class="form-control">
                                </div>
                            </div>

                            <input type="submit" name="submit_otp" value="Submit OTP" class="btn green"/>
                        {/if}
                        <input type="submit" name="otp" value="Request OTP" class="btn blue"/>
                    </form>

                </div>
            </div>
        {else}
            {if !empty($summary)}
                <div class="row">
                    <div class="col-md-12">

                        <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                        <div class="portlet ">
                            <div class="portlet-body">
                                <h4><b>3.1 Details of Outward Supplies and inward supplies liable to reverse charge</b></h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nature of Supplies	</th>
                                            <th>Total Taxable value	</th>
                                            <th>Integrated Tax	</th>
                                            <th>Central Tax	</th>
                                            <th>State / UT Tax	</th>
                                            <th>Cess	</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>(a) Outward taxable supplies (other than zero rated, nil rated and exempted)</td>
                                            <td  colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td>GSTN</td>
                                            <td>{$summary.supDetail.osupDet.txval}</td>
                                            <td>{$summary.supDetail.osupDet.iamt}</td>
                                            <td>{$summary.supDetail.osupDet.camt}</td>
                                            <td>{$summary.supDetail.osupDet.samt}</td>
                                            <td>{$summary.supDetail.osupDet.csamt}</td>
                                        </tr>
                                        <tr>
                                            <td>(b) Outward taxable supplies (zero rated )</td>
                                            <td  colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td>GSTN</td>
                                            <td>{$summary.supDetail.osupZero.txval}</td>
                                            <td>{$summary.supDetail.osupZero.iamt}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>(c) Other outward supplies (Nil rated, exempted)</td>
                                            <td  colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td>GSTN</td>
                                            <td>{$summary.supDetail.osupNilExmp.txval}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>(d) Inward supplies (liable to reverse charge)</td>
                                            <td  colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td>GSTN</td>
                                            <td>{$summary.supDetail.isupRev.txval}</td>
                                            <td>{$summary.supDetail.isupRev.iamt}</td>
                                            <td>{$summary.supDetail.isupRev.camt}</td>
                                            <td>{$summary.supDetail.isupRev.samt}</td>
                                            <td>{$summary.supDetail.isupRev.csamt}</td>
                                        </tr>
                                        <tr>
                                            <td>(e) Non-GST outward supplies</td>
                                            <td  colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td>GSTN</td>
                                            <td>{$summary.supDetail.osup_nongst.txval}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                    </tbody>

                                </table>



                                <h4><b>3.2 Of the supplies shown in 3.1 (a) above, details of inter-State supplies made to unregistered persons, composition taxable persons and UIN holders</b></h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Details	</th>
                                            <th> Count of Place of Supply (State/UT) 	</th>
                                            <th> Total Taxable value 	</th>
                                            <th> Amount of Integrated Tax 	</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> Supplies made to Unregistered Persons </td>
                                            <td  colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td>GSTN</td>
                                            <td>{$summary.c_unreg.t1}</td>
                                            <td>{$summary.c_unreg.t2}</td>
                                            <td>{$summary.c_unreg.t3}</td>
                                        </tr>
                                    </tbody>

                                </table>

                                <h4><b>4. Eligible ITC</b></h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Details	</th>
                                            <th>  Integrated Tax 	</th>
                                            <th>  Central Tax 	</th>
                                            <th>  State / UT Tax 	</th>
                                            <th>  Cess 	</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>  (A) ITC Available (whether in full or part)  </td>
                                            <td  colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td>GSTN</td>
                                            <td>{$summary.itcElg.itcNet.iamt}</td>
                                            <td>{$summary.itcElg.itcNet.camt}</td>
                                            <td>{$summary.itcElg.itcNet.samt}</td>
                                            <td>{$summary.itcElg.itcNet.csamt}</td>
                                        </tr>
                                    </tbody>

                                </table>

                                <div class="col-md-offset-5 col-md-6">
                                    <form action="/merchant/gst/gstsubmitsave" id="gst_submit" method="post">
                                        <input type="hidden" name="fp" value="{$det.fp}">
                                        <input type="hidden" name="gstin" value="{$det.gstin}">
                                        <input type="hidden" name="frm_type" value="GSTR3B">
                                        <input type="hidden" name="bulk_upload_id" value="{$det.bulk_upload_id}">
                                        <input type="hidden" name="month" value="{$month}{$gstin}">
                                        <a href="#submit" data-toggle="modal" class="btn blue"> Submit Draft</a>
                                        <a href="#basic" data-toggle="modal" class="btn red" value="Delete draft" >Delete draft</a>
                                    </form>
                                </div>
                                <br>
                                <br>
                            </div>
                        </div>

                        <!-- END PAYMENT TRANSACTION TABLE -->
                    </div>
                </div>

            {else}
                {if isset($success)}
                {else}
                    <div class="row">
                        <div class="col-md-8">
                            <div class="alert alert-info">
                                <strong>Draft Empty!</strong> Draft not exist for this period. Please create draft 
                                <br>
                                <br>
                                <input type="hidden" name="month" value="{$v.fp}{$v.gstin}">
                                <a href="/merchant/gst/draft3b/{$link}/create"  class="btn btn-sm green" >Create draft</a>
                            </div>
                        </div>
                    </div>
                {/if}
            {/if}
        {/if}
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Draft</h4>
            </div>
            <div class="modal-body">
                You are about to delete draft for {$datearray.{$det.fp|substr:0:2}}-{$det.fp|substr:2} & {$det.gstin}. Are you sure you would like to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="/merchant/gst/draft3b/{$link}/delete" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="submit" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Submit Draft</h4>
            </div>
            <div class="modal-body">
                You are about to save invoices for 3b for {$datearray.{$det.fp|substr:0:2}}-{$det.fp|substr:2} & {$det.gstin}. Are you sure you would like to proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a onclick="submitfrm('gst_submit');" class="btn blue">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function submitfrm(id)
    {
        document.getElementById(id).submit();
    }
</script>