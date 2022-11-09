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
                        <li class="active">
                            <a href="#tab1" data-toggle="tab" class="step" aria-expanded="true">
                              
                                <span class="desc">
                                    Upload attachment </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tab2" data-toggle="tab" class="step">
                               
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
                            <div class="portlet light bordered" style="padding:0px !important;    border: 0px solid #e1e1e1!important">
                                <input type="hidden" name="_token1" value="{{ csrf_token() }}">
                                    <div id="drag-drop-area-bill"></div>
                               
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <div class="portlet light bordered">
                                <div class="portlet-body form">
                                    <div class="row w-full   bg-white  shadow-2xl font-rubik m-2 py-10" style="max-width: 1400px;">
                                        <div class="tabbable-line col-md-2 col-sm-3 col-xs-3">
                                            @php
                                            $files  ='["https://s3.ap-south-1.amazonaws.com/uat.expense/invoices/otp-screen5701.png","https://s3.ap-south-1.amazonaws.com/uat.expense/invoices/login-sign-in-screen5845.png"
                                 ,"https://s3.ap-south-1.amazonaws.com/uat.expense/invoices/q19WxeL74pM3AZUducMCrn_Ogm_59Nk2ll9eWQ9386.pdf"
                                  ,"https://s3.ap-south-1.amazonaws.com/uat.expense/invoices/pricing-slider2.png46431825.png"
                                 ,"https://s3.ap-south-1.amazonaws.com/uat.expense/invoices/MoTv9_Fq3hZavFcAs2o673_Ogm_59Nk1k1tdVg4277.png"
                                 ,"https://s3.ap-south-1.amazonaws.com/uat.expense/invoices/Abhijeet Choubey_2022-Jul-18 14_07_47.pdf11556322.pdf"
                                 ,"https://s3.ap-south-1.amazonaws.com/uat.expense/invoices/MoTv9_Fq3hZavFcAs2o673_Ogm_59Nk1k1tdVg4277.png"
                                 ,"https://s3.ap-south-1.amazonaws.com/uat.expense/invoices/Abhijeet Choubey_2022-Jul-18 14_07_47.pdf11556322.pdf"
                                ]';
                                $files=json_decode($files,1);
                                $selectedDoc='["Invoice", "","login-sign..."]';
                                $selectedDoc=json_decode($selectedDoc,1);
                                $menus=array();
            $doclist=array();
           
            $menus['title']="Invoice";
            $menus['id']='Invoice';
            $menus['full']='Invoice';
            $menus['link']="";
          
            $menus1=array();
            $menus2=array();
            $pos=1;
          
            foreach($files as $key=>$item)
            {


                $menus1['id']=str_replace(' ','_',substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,7));
                $menus1['full']=basename($item);
                $nm=substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,10);
                $menus1['title']=strlen(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4)) < 10 ?substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4):$nm.'...';
              
             
                $menus1['link']=substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,7);
                $menus1['menu']="";
                $menus1['type']="invoice";
                $menus2[ $pos]=$menus1;
                
                $pos++;
               
            }

            $menus['menu']=$menus2;
             $doclist[]=$menus;
             $docs= $doclist;
        
                                           @endphp    
                                         
                                            
                                           
                                         <div class="container_2 page-sidebar1 navbar-collapse collapse" >

                                            <ul class="tree">
                                              @isset($docs)
                                              @foreach ($docs as $row)
                                                <li >
                                                
                                                <a onclick="myFunction('title','title')" class="popovers" data-placement="top" data-container="body" data-trigger="hover"  data-content="{{$row['full']}}">
                                                  <label   id="ltitle" class=" tree_label @if(in_array($row['title'], $selectedDoc)) active1  @endif" for="{{$row['title']}}">{{$row['title']}}</label>
                                                  <div id="arrowtitle" style="float: right;" class='@if(in_array($row['title'], $selectedDoc))fa fa-angle-down  active1 @else fa fa-angle-right @endif'></div>
                                                </a>
                                                  @if(!empty($row['menu']))
                                                  <ul id="ul{{$row['id']}}" style="display:@if(in_array($row['title'], $selectedDoc)) block @else none  @endif">
                                                    @foreach ($row['menu'] as $row2)
                                                    <li >
                                                      
                                                      <a style="color: #636364;"  id="a{{$row2['id']}}" class=" @if(in_array($row2['title'], $selectedDoc)) aclass active1  @endif" href="@if($row2['link']!='')#tab_{{$row2['link']}}@else javascript:; @endif" data-toggle="tab">
                                                      <span onclick="removeactive('{{$row['id']}}','{{$row2['id']}}','');" class="tree_label1  popovers"  data-placement="top" data-container="body" data-trigger="hover"  data-content="{{$row2['full']}}">{{$row2['title']}}</span>
                                                      </a>
                                                   
                                                      
                                                    </li>
                                                    @endforeach
                                                  </ul>
                                    @endif
                                                </li>
                                                @endforeach
                                                @endisset
                                                
                                             
                                              </ul>
                                        </div>  
                                        </div>
                                  
                                        <div class="col-md-10 col-sm-9 col-xs-9" >
                                            <div class="tab-content"  >
                                                @foreach ($files as $key=>$item)
                        @php
                            $nm=substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,10);
                            $nm=strlen(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4)) < 10 ?substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4):$nm.'...';
                             
                        @endphp
                                              <div class="tab-pane @if(in_array($nm, $selectedDoc)) active @else fade @endif" id="tab_{{substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,7)}}" >
                       
                                                    <div class="grid grid-cols-3  gap-4 mb-2">
                                                        <div class="col-span-2">
                                                     <h2 class="text-lg text-left  font-normal  text-black">{{substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4)}} </h2>
                                                        </div>
                                                   
                                                       
                                                    </div>
                                                    <hr>
                                                    <p class="mt-2">
                                                        <iframe src="{{$item}}" width="100%" height="800px">
                                                        </iframe>
                                                    </p>
                                                </div>
                                                @endforeach
                        
                                        
                        
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                         </div>
                       

                    </div>
                </div>

            </div>
            @php
            $docjson=json_encode($docs);
        @endphp
        </div>
    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://releases.transloadit.com/uppy/v1.28.1/uppy.min.js"></script>
<script>
    var newdocfileslist=[];
//uppy file upload code
var uppy_attach = Uppy.Core({ 
    autoProceed: true,
    restrictions: {
        maxFileSize: 3000000,
        maxNumberOfFiles: 10,
        minNumberOfFiles: 1,
        allowedFileTypes: ['.jpg','.png','.jpeg','.pdf']
    }
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
uppy_attach.use(Uppy.XHRUpload, { 
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token1"]').attr('content')
    },
    endpoint: '/merchant/uppyfileupload/uploadImage/invoice',
    method:'post',
    formData: true,
    fieldName: 'image'
});

uppy_attach.on('file-added', (file) => {
    document.getElementById("error").innerHTML = '';
    console.log('file-added');
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
 
   var list ={!!$docjson!!}; 
 
   for(var i=0;i<list.length;i++)
   {
      var filenm=list[i]['id'];
      if(parent!=filenm)
      {
      var text = document.getElementById("ul"+filenm);
      text.style.display = "none";
      var ele = document.getElementById("arrow"+filenm);
      ele.classList.remove('fa','fa-angle-down');
     ele.classList.add('fa','fa-angle-right');
      }
   }
 
 
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
 
     function removeactive(p,c,s)
     {
        
      
 
 $('div.container_2 label').removeClass('active1')
 $('div.container_2 label').removeClass('active');
 //$('div.container_2 ul').removeClass('show');
 
 $('div.container_2 li').removeClass('active1');
 $('div.container_2 li').removeClass('active'); 
 $('div.container_2 a').removeClass('aclass'); 
 $('div.container_2 a').removeClass('active1'); 
 $('div.container_2 div').removeClass('active'); 
 $('div.container_2 div').removeClass('active1'); 
 
  if(p!='')
        {
        
        var ele= document.getElementById("l"+p);
        document.getElementById("arrow"+p).classList.add('active1');
        ele.classList.add("active1");
      
        }
        if(c!='')
        {
         if(s=='')
        {
        
         var ele=document.getElementById("a"+c);
     
     ele.classList.add("active1");
      
        }else
        {
     var ele=document.getElementById("l"+c);
     document.getElementById("arrow"+c).classList.add('active1');
      ele.classList.add("active1");
        }
     
        }
        if(s!='')
        {
        
         var ele=document.getElementById("a"+s);
     
     ele.classList.add("active1");
      
        }
     }
 
 
 
 
     </script>