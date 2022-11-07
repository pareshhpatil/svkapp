<div class="page-sidebar-wrapper">
    <div class="container_2 page-sidebar1 navbar-collapse collapse" style="max-width: 151px;">

        <ul class="tree">
          @isset($docs)
          @foreach ($docs as $row)
            <li >
            
            <a onclick="myFunction('{{$row['id']}}','{{$row['id']}}')" class="popovers" data-placement="top" data-container="body" data-trigger="hover"  data-content="{{$row['full']}}">
              <label   id="l{{$row['id']}}" class=" tree_label @if(in_array($row['title'], $selectedDoc)) active1  @endif" for="{{$row['title']}}">{{$row['title']}}</label>
              <div id="arrow{{$row['id']}}" style="float: right;" class='@if(in_array($row['title'], $selectedDoc))fa fa-angle-down  active @else fa fa-angle-right @endif'></div>
            </a>
              @if(!empty($row['menu']))
              <ul id="ul{{$row['id']}}" style="display:@if(in_array($row['title'], $selectedDoc)) block @else none  @endif">
                @foreach ($row['menu'] as $row2)
                <li >
                  @if(!empty($row2['menu']))
                  <a onclick="myFunction('{{$row['id']}}','{{$row2['id']}}')" class="popovers" data-placement="top" data-container="body" data-trigger="hover"  data-content="{{$row2['full']}}">
                  <label  for="{{$row2['title']}}" id="l{{$row2['id']}}"  class=" tree_label @if(in_array($row2['title'], $selectedDoc)) active  @endif">{{$row2['title']}}</label>
                  <div id="arrow{{$row2['id']}}" style="float: right;" class='@if(in_array($row2['title'], $selectedDoc))fa fa-angle-down  active @else fa fa-angle-right  @endif'></div>
                  </a>
                  @else
                  <a class=" @if(in_array($row2['title'], $selectedDoc)) aclass active  @endif" href="@if($row2['link']!='')#tab_{{$row2['link']}}@else javascript:; @endif" data-toggle="tab">
                  <span onclick="removeactive('{{$row['id']}}','{{$row2['id']}}','');" class="tree_label1  popovers"  data-placement="top" data-container="body" data-trigger="hover"  data-content="{{$row2['full']}}">{{$row2['title']}}</span>
                  </a>
                  @endif
                  @if(!empty($row2['menu']))
                  <ul id="ul{{$row2['id']}}" style="display:@if(in_array($row2['title'], $selectedDoc)) block @else none  @endif">
                    @foreach ($row2['menu'] as $row3)
                    <li onclick="removeactive('{{$row['id']}}','{{$row2['id']}}','{{$row3['id']}}');" class="popovers"  data-placement="top" data-container="body" data-trigger="hover"  data-content="{{$row3['full']}}">
                      <a class=" @if(in_array($row3['title'], $selectedDoc)) aclass active  @endif" href="@if($row3['link']!='')#tab_{{$row3['link']}}@else javascript:; @endif" data-toggle="tab">
                      <span class="tree_label1"  >{{$row3['title']}}</span>
                      </a>
                    </li>
                  @endforeach
                  </ul>
                  @endif
                </li>
                @endforeach
              </ul>
@endif
            </li>
            @endforeach
            @endisset
            
         
          </ul>
    </div>
    @php
        $docjson=json_encode($docs);
    @endphp
</div>
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
$('div.container_2 a').removeClass('active'); 
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
      
    var ele=document.getElementById("l"+c);
    document.getElementById("arrow"+c).classList.add('active');
    ele.classList.add("active");
    
       }
    //    if(s!='')
    //    {
       
    //    var ele= document.getElementById(""+s);
    //    ele.className = " open ";
     
    //    }
    }




    </script>