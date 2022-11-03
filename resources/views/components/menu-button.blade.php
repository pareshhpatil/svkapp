
    <div class='row' style="position: fixed;
    right:    20;
    bottom:   15;" >

<ul id="ul"  style="display:none;list-style-type:none;padding: 12px;" class="apps-shadow mb-2" >
    <span style="color: #c1bfbf;
    padding: 4px;">QUICK PICK </span>
      
     @foreach ($menu_list as $mnu)
 
    <li style="padding:5px;"><a href="{{$mnu['link']}}" class="a1">{{$mnu['name']}}</a></li>

   @endforeach
   
  </ul>

     <a   class="toggle-button pull-right popovers "  data-placement="left"   data-container="body" data-trigger="hover" data-content="Sorted as per usage i.e. feature used lately will show up first">
    
      
    <svg style="width: 48px"   xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 24" fill="currentColor"  class="icon-add-circle">
        
      <circle  cx="12" cy="12" r="10" class="primary"/>
      <path class="secondary" style="fill: white;" d="M13 11h4a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4z"/></svg>
  </a>
   
    </div>

    <script>
        $(document).ready(function() {
  $(".toggle-button").click(function() {
    $(this).parent().find("ul").slideToggle(function() {
      // Animation complete.
    });
  });
})
        </script>
