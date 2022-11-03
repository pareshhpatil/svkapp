<div class="page-content" style="min-height:901px">
    <div class="row no-margin">
        <form action="/merchant/gst/updatesaved" method="post">
            <div class="col-md-1"></div>
            <div class="col-md-10" style="text-align: -webkit-center;text-align: -moz-center;">

                <br>
                <div class="portlet light bordered" style="max-width: 900px;padding: 12px 20px 0px 20px;">
                    {if isset($error)}
                        <div class="alert alert-danger alert-dismissable">
                            <div class="media" style="text-align:left">
                               <strong>Error!</strong> {$error}
                            </div>

                        </div>
                    {/if}
                    <div class="invoice" style="text-align: left;">
                        <br>
                        <div class="row">

                            <div class="col-xs-6 invoice-payment">
                                <div class="row">
                                    <div class="form-group" >
                                        <label class="control-label col-md-4">Invoice Number<span class="required">* </span></label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <input type="text" required name="inum" value="{$det.inum}" class="form-control" >
                                            </div>
                                            <br>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group" >
                                        <label class="control-label col-md-4">Invoice Date<span class="required">* </span></label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <input type="text" required name="pdt" value="{$det.pdt|date_format:"%d-%m-%Y"}" class="date-picker form-control" >
                                            </div>	
                                            <br>
                                        </div>				
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group" >
                                        <label class="control-label col-md-4">Invoice Type<span class="required">* </span></label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <select name="invTyp"  required class="form-control" data-placeholder="Select...">
                                                    <option {if $det.invTyp=='B2CS'} selected {/if} value="B2CS" >B2CS</option>
                                                    <option {if $det.invTyp=='B2B'} selected {/if} value="B2B" >B2B</option>
                                                </select>
                                            </div>	
                                            <br>
                                        </div>				
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-6 invoice-payment">
                                <div class="row">
                                    <div class="form-group" >
                                        <label class="control-label col-md-4">Supply Type<span class="required">* </span></label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <select name="splyTy"  required class="form-control" data-placeholder="Select...">
                                                    <option {if $det.splyTy=='INTER'} selected {/if} value="INTER" >INTER</option>
                                                    <option {if $det.splyTy=='INTRA'} selected {/if} value="INTRA">INTRA</option>
                                                </select>
                                            </div>	
                                            <br>
                                        </div>				
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group" >
                                        <label class="control-label col-md-4">State<span class="required">* </span></label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <select name="pos"  required class="form-control" data-placeholder="Select...">
                                                    {foreach from=$state key=k item=v}
                                                        <option {if $k==$det.pos} selected {/if} value="{$k}">{$v} </option>
                                                    {/foreach}
                                                </select>
                                            </div>	
                                            <br>
                                        </div>				
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group" >
                                        <label class="control-label col-md-4">Invoice Amount<span class="required">* </span></label>
                                        <div class="col-md-8">
                                            <div class="input-icon right">
                                                <input type="number" step="0.01"  required name="val" value="{$det.val}" class="form-control" >
                                            </div>	
                                            <br>
                                        </div>				
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-scrollable" style="overflow: auto;">
                                    <table class="table table-striped table-hover" style="width: 1200px !important;" >
                                        <thead>
                                            <tr>
                                                <th >
                                                    #
                                                </th>
                                                <th >
                                                    Description
                                                </th>
                                                <th >
                                                    Qty
                                                </th>
                                                <th >
                                                    Tax Val
                                                </th>
                                                <th >
                                                    CGST%
                                                </th>
                                                <th >
                                                    CGST Amt
                                                </th>
                                                <th >
                                                    SGST%
                                                </th>
                                                <th >
                                                    SGST Amt
                                                </th>
                                                <th >
                                                    IGST%
                                                </th>
                                                <th >
                                                    IGST Amt
                                                </th>
                                                <th >
                                                    Total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            {foreach from=$det.itemDetails item=v}
                                                <tr> 
                                                    <td  style="width: 10px;">
                                                        {$v.num}
                                                    </td>
                                                    <td style="width: 200px;">
                                                        <input type="text" required name="desc[]" value="{$v.desc}" class="form-control" > 
                                                    </td>
                                                    <td >
                                                        <input type="number" step="1"  required name="qty[]" value="{$v.qty}" class="form-control" > 
                                                    </td>

                                                    <td >
                                                        <input type="number" step="0.01"   name="txval[]" value="{$v.txval}" class="form-control" > 
                                                    </td>
                                                    <td >
                                                        <input type="number" step="0.01"   name="crt[]" value="{$v.crt}" class="form-control" > 
                                                    </td>
                                                    <td >
                                                        <input type="number" step="0.01"   name="camt[]" value="{$v.camt}" class="form-control" > 
                                                    </td>
                                                    <td >
                                                        <input type="number" step="0.01"   name="srt[]" value="{$v.srt}" class="form-control" > 
                                                    </td>
                                                    <td >
                                                        <input type="number" step="0.01"   name="samt[]" value="{$v.samt}" class="form-control" > 
                                                    </td>
                                                    <td >
                                                        <input type="number" step="0.01"   name="irt[]" value="{$v.irt}" class="form-control" > 
                                                    </td>
                                                    <td >
                                                        <input type="number" step="0.01"  name="iamt[]" value="{$v.iamt}" class="form-control" > 
                                                    </td>
                                                    <td >
                                                        <input type="number" step="0.01"  required name="sval[]" value="{$v.sval}" class="form-control" >  
                                                    </td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row no-margin">
                            <div class="col-md-12">
                                <textarea style="display: none;" type="hidden" name="json" value="">{$json}</textarea>
                                <input type="hidden" name="id" value="{$id}">
                                <input type="submit" value="Submit" class="btn blue hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;"/>

                            </div>
                        </div>

                    </div>

                </div>
                <!-- END PAGE CONTENT-->
            </div>
        </form>
    </div>





</div>