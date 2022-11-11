<style>
    
    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 10% !important;
        /* width: 80em; */
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 100;
    }

    .panel {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        color: #394242;
        overflow-y: scroll;
        overflow-x: hidden;
        padding: 1em;
        box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
        margin-bottom: 0;
    }

    .remove {
        padding: 4px 3px;
        cursor: pointer;
        float: left;
        position: relative;
        top: 0px;
        color: #fff;
        right: 25px;
        z-index: 99999;
    }

    .remove:hover {
        color: #FFF;
    }

    .remove i {
        font-size: 19px !important;
    }

    .subscription-info i {
        font-size: 22px !important;
    }

    .cust-head {
        text-align: left !important;
    }

    .subscription-info h3 {
        text-align: center;
        color: #000;
        margin-bottom: 2px !important;
    }

    .subscription-info h2 {
        font-weight: 600;
        margin-bottom: 0 !important;
        margin-top: 5px !important;
        text-align: center;
    }

    .td-head {
        font-size: 19px;
    }

    @media (max-width: 767px) {
        .cust-head {
            text-align: center !important;
        }

        .panel-wrap {
            /* width: 23em; */
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            position: fixed;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    .right-value {
        text-align: right;
    }
</style>
<div class="panel-wrap" id="panelWrapIdBillCodeAttachment">


    
    <div class="panel">
        <div class='cnt223'>
            <h3 class="modal-title">Bill code attachment

            <a class="close " data-toggle="modal"  onclick="return closeSidePanelBillCodeAttachment();">
                    <button type="button" class="close" aria-hidden="true"></button></a>
            </h3>
          <br/>
            <div class="form-wizard">
                <div class="form-body">
                    <ul class="nav nav-pills ">
                        <li id="listtab1" class="active">
                            <a href="#tab1" data-toggle="tab" class="step" aria-expanded="true">
                              
                                <span class="desc">
                                    Upload attachment </span>
                            </a>
                        </li>
                        <li id="listtab2">
                            <a href="#tab2" onclick="setBillCodeMenuData();" data-toggle="tab" class="step">
                               
                                <span class="desc">
                                    View attachments </span>
                            </a>
                        </li>
                       

                    </ul>
                   
                    <div class="tab-content">
                        <div class="alert alert-danger display-none">
                            <button class="close" data-dismiss="alert"></button>
                            You have some form errors. Please check below.
                        </div>
                        <div class="alert alert-success display-none">
                            <button class="close" data-dismiss="alert"></button>
                            Your form validation is successful!
                        </div>


                        <div class="tab-pane active" id="tab1">
                            <div class=" light " style="padding:0px !important;    border: 0px solid #e1e1e1!important">
                                <input type="hidden" name="_token1" value="{{ csrf_token() }}">
                                    <div id="drag-drop-area-bill"></div>
                               
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <div class="portlet light bordered">
                                <div class="portlet-body form">
                                    <div id="noview" style="display:none;">
                                    <div  class="row w-full   bg-white  shadow-2xl font-rubik m-2 p-10" style="max-width: 1400px;">
                                        <h3 style="margin-left: 29px !important;" class="form-section mt-2">No attachments uploaded</h3>
                                        </div>
                                    </div>
                                    <div id="yesview"  style="display:none;">
                                    <div class="row w-full   bg-white  shadow-2xl font-rubik m-2 py-10" style="max-width: 1400px;">
                                        <div class="tabbable-line col-md-2 col-sm-3 col-xs-3">
           
                                            <div class="container_2 page-sidebar1 navbar-collapse collapse" >

                                            <ul class="tree" id="ulmenu">
                                             
                                               
                                              
                                                
                                             
                                              </ul>
                                        </div>  
                                        </div>
                                  
                                        <div class="col-md-10 col-sm-9 col-xs-9" >
                                            <div class="tab-content container_1"  id="frame_view" >
                                              
                                            </div>
                                        </div>
                                </div></div>
                            </div>
                        </div>
                         </div>
                       

                    </div>
                </div>

            </div>
           
        </div>
    </div>
</div>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://releases.transloadit.com/uppy/v3.3.0/uppy.min.js"></script>

<script>
    var newdocfileslist=[];
 var billcode='';
 const { Compressor } = Uppy;
//uppy file upload code
var uppy_attach = new Uppy.Uppy({ 
  
    autoProceed: true,
    restrictions: {
        maxFileSize: 3000000,
        maxNumberOfFiles: 10,
        minNumberOfFiles: 1,
        allowedFileTypes: ['.jpg','.png','.jpeg','.pdf']
    },
    onBeforeFileAdded: (currentFile, files) => {
        try{
             name = document.getElementById("bill_code"+attach_pos).value + '_' + currentFile.name
        }catch(o)
        {
             name = document.getElementById("billcode"+attach_pos).value + '_' + currentFile.name
        }
       modifiedFile = {
        ...currentFile,
        meta: {
             ...currentFile.meta,
             name
         },
        name
      }
      uppy.log(modifiedFile.name)
      return modifiedFile
    }
});
uppy_attach.use( Compressor, {
  quality: 0.6,
  limit: 10,
});
uppy_attach.use(Uppy.Dashboard, {
    target: '#drag-drop-area-bill', 
   // trigger: '.UppyModalOpenerBtn',
    inline: true,
    height: 400,
    hideAfterFinish: true,
    showProgressDetails: false,
    hideUploadButton: false,
    hideRetryButton: false,
    hidePauseResumeButton: false,
    hideCancelButton: false,
    // doneButtonHandler: () => {
    //     document.getElementById("uppy_file").value = '';
    //     this.uppy_attach.reset()
    //     this.requestCloseModal()
    // },
    // locale: {
    //     strings: {
    //         done: 'Cancel'
    // }}
});
uppy_attach.on('file-added', (file) => {
    document.getElementById("error").innerHTML = '';
   
    console.log('file-added'+billcode);
});
uppy_attach.use(Uppy.XHRUpload, { 
    
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token1"]').attr('content')
    },
    endpoint: '/merchant/uppyfileupload/uploadImage/invoice/billcode',
    method:'post',
    formData: true,
    fieldName: 'image'
});



uppy_attach.on('upload', (data) => {
    console.log('Starting upload');
});
uppy_attach.on('upload-success', (file, response) => {
    path = response.body.fileUploadPath;
    extvalue=document.getElementById("attach-"+attach_pos).value;
   // newdocfileslist.push(path);
    
    if(extvalue!='')
    {
        document.getElementById("attach-"+attach_pos).value=extvalue+','+path;
    }else{
        document.getElementById("attach-"+attach_pos).value=path;
    }
    
    try{
        if (particularray !== undefined) {
            particularray[attach_pos].attachments = document.getElementById("attach-"+attach_pos).value;
        }
    }catch(o){}
    

    var file_count=document.getElementById("attach-"+attach_pos).value;
    var pathlist=file_count.split(",");
    var counts='0 file';
    if(pathlist.length>1)
    counts=pathlist.length+' files';
    else
    counts=pathlist.length+' file';
      
    document.getElementById("icon-"+attach_pos).setAttribute("data-content",""+counts);
    if(response.body.status == 300) {
        document.getElementById("error").innerHTML = response.body.errors;
        uppy_attach.removeFile(file.id);
    } else {
       // document.getElementById("error").innerHTML = '';
    }
});
uppy_attach.on('complete', (result) => {
    //console.log('successful files:', result.successful)
    //console.log('failed files:', result.failed)
});
uppy_attach.on('error', (error) => {
    ///console.error(error.stack);
});
</script>
<script>
    function myFunction(parent,id) {
 
  
 
 
   var text = document.getElementById("ul"+id);
   var ele = document.getElementById("arrow"+id);
 
 
   if (text.style.display == 'block'){
   
     text.style.display = "none";
     ele.classList.remove('fa','fa-angle-down');
     ele.classList.add('fa','fa-angle-right');
    
   } else {
  
    ele.classList.remove('fa','fa-angle-right');
    ele.classList.add('fa','fa-angle-down');
     text.style.display = "block";
   }
 }
 
     function removeactive(p,c)
     {
        
      

 $('div.container_2 a').removeClass('aclass'); 
 $('div.container_2 a').removeClass('active1'); 
 
 $('div.container_1 div').removeClass('active');
   $('div.container_1 div').add('fade'); 

        if(c!='')
        {
        
         var ele=document.getElementById("a"+c);
         document.getElementById("tab_"+c).classList.add('active');
         document.getElementById("tab_"+c).classList.remove('fade');
    
     ele.classList.add("active1");
      
        }
     }
 
 
 
 
     </script>