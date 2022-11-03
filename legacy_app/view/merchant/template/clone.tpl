<link href="/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<style>
 .card2 {
       
        
          border: 0.1px solid #fff;
         
      }
    .card2:hover {
        transition: all .1s ease-in-out;
          box-shadow: 0px 4px 8px rgba(43, 28, 28, 0.2);
          border: 0.1px solid #cccccc;
         
      }


      

.custom-ui-container {
	max-width:400px;
  width: 400px;
	height:250px;
	top:50%;
	left:50%;
	transform:translate(-50%,-50%);
  background: #fff;
  box-sizing: border-box;
  display: block;
  position: absolute;
  -webkit-box-shadow: 0px 6px 2px rgba(0, 0, 0, 0.16), 0px 6px 2px rgba(0, 0, 0, 1);
    box-shadow: 0px 6px 2px rgba(0, 0, 0, 0.16), 0px 6px 2px rgba(0, 0, 0, 0.23);
  border-radius: 10px;
}


</style>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="tabbable-line boxless">
                <div class="tab-pane active" id="tab_2">
                    <!-- BEGIN FILTER -->
                    <div class="filter-v1 margin-top-10">
                        <div class="row mix-grid thumbnails flex-container">
                            {foreach from=$systemtemplatelist item=v}
        <div class="col-xs-12 col-sm-6 col-md-4 mb-1 flex-item">
       
            <div class="card2 box-plugin apps-shadow  mr-1"
                style="background-color: #ffffff; padding-left: 3px; padding-right: 5px;">
                <div class="apps-box">
                   <div class="row no-margin">
                        <div class="col-xs-12">
                            <h3 class="mb-1" id="title_8">{$v.template_name}
                            </h3>

                        </div>

                    </div>
                    <div class="row no-margin">

                        <div class="col-md-12" style="margin-bottom: 24px;">
                            <div class="row">
                                <div class="col-md-12" style="margin-top: 3px;">{$v.template_description} </div>

                            </div>

                        </div>

                    </div>





                </div>
                <br><br>
            </div>

          {if $v.template_type!='travel_ticket_booking'}
                                        <div class="row " style="margin-top: -60px; margin-bottom: 24px;">
                                            <div class="col-md-12 center middle">
                                                <a  href="/assets/admin/pages/media/template/{$v.template_type}_view.jpg"
                                                    class="btn green fancybox-button {if $v.template_type=='construction'}open_fancybox{/if}"> Preview
                                                </a>
                                                {if $v.template_type=='travel' || $v.template_type=='school'}
                                                    <a href="/merchant/invoiceformat/choose-color/create/travel/909898/{$v.encrypted_id}"
                                                    class="btn btn-primary ">
                                                    Pick template</a>
                                               {else if $v.template_type=='isp'}
                                                <a href="/merchant/invoiceformat/choose-design/create/{$v.encrypted_id}"
                                                        class="btn btn-primary ">
                                                        Pick template</a>
                                                {else}
                                                    <a href="/merchant/invoiceformat/create/{$v.encrypted_id}"
                                                        class="btn btn-primary ">
                                                        Pick template</a>
                                                {/if}
                                            </div>
                                            <br>
                                            <br>
                                            <br>
                                        </div>
                                    {/if}
           
        </div>


                               

                            {/foreach}

                        </div>
                    </div>
                    <!-- END FILTER -->
                </div>

            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->