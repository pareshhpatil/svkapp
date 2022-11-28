
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->

    <!-- END PAGE HEADER-->
    <div class="row no-margin">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <h3>Merchant list</h3>
            <script src="https://maps.google.com/maps/api/js?key=AIzaSyB56K6P_YwJYAnk8X8u58cZ2ozNrRm0_X4" 
          type="text/javascript"></script>
</head> 
<body>
  <div id="map" style="  height: 800px;"></div>

  <script type="text/javascript">
  var iconImg = 'https://h7sak8am43.swipez.in/smlogo.png';
  
  
    var locations = [
      ['IT & ITES',18.549384,73.914863,iconImg,1000000000],
	  ['IT & ITES',18.472654,73.899611,iconImg,1],
	  ['Education', 18.505897,73.852497,iconImg,7],
	  ['Hospitality',18.503469,73.821558,iconImg,8],
	  ['IT & ITES',19.067889,72.903032,iconImg,9],
	  ['Education',18.520430,73.856744,iconImg,18],
	  ['Real estate',18.552267,73.901596,iconImg,24],
	  ['ISP',18.468722,73.860068,iconImg,28],
	  ['ISP',18.468865,73.860553,iconImg,30],
	  ['Research and development',13.036528,80.253271,iconImg,33],
	  ['Sports & Outdoors',18.533387,73.826192,iconImg,37],
	  ['Retail',19.076382,72.834738,iconImg,42],
	  ['Food industry',18.450427,73.834049,iconImg,45],
	  ['Food industry',18.535127,73.897118,iconImg,46],
	  ['Food industry',18.561958,73.916926,iconImg,47],
	  ['Food industry',18.559498,73.790542,iconImg,48],
	  ['IT & ITES', 18.554040,73.899847,iconImg,51],
	  ['Hospitality',19.134373,72.826406,iconImg,53],
	  ['Food industry',18.591288,73.909360,iconImg,56],
	  ['ISP',23.012515,72.522824,iconImg,57],
	  ['Retail',19.101463,72.911505,iconImg,59],
	  ['Education',18.533387,73.826192,iconImg,60],
	  ['Education',18.539150,73.898841,iconImg,63],
	  ['Retail',12.875947,74.847264,iconImg,65],
	  ['ISP',23.024349,72.530152,iconImg,68],
	  ['Food industry',18.572333,73.908587,iconImg,73],
	  ['ISP',18.560481,73.938728,iconImg,75],
	  ['IT & ITES',19.121977,72.886915,iconImg,76],
	  ['ISP',18.530017,73.911457,iconImg,77],
	  ['Home Baker',18.510987,73.934983,iconImg,79],
	  ['Real estate',19.115864,72.910965,iconImg,81],
	  ['IT & ITES',19.121144,72.909137,iconImg,82],
	  ['ISP',18.472654,73.899611,iconImg,83],
	  ['ISP',18.482449,73.797437,iconImg,85],
	  ['Retail',19.099711,72.916425,iconImg,87],
	  ['ISP',18.491595,73.928703,iconImg,88],
	  ['Hospitality',19.075067,72.838765,iconImg,89],
	  ['ISP',18.525579,73.905884,iconImg,90],
	  ['ISP',18.548817,73.910844,iconImg,91],
	  ['ISP',19.878846,75.347420,iconImg,93],
	  ['ISP',18.590416,73.770902,iconImg,94],
	  ['Retail',18.514203,73.875516,iconImg,95],
	  ['Sports & Outdoors',18.558007,73.807520,iconImg,96],
	  ['ISP',17.275672,74.179578,iconImg,97],
	  ['ISP',18.557021,73.949198,iconImg,99],
	  ['ISP',18.638387,73.845780, iconImg,106],
	  ['Financial services',19.117344,72.857179,iconImg,107],
	  ['ISP',18.610671,73.785772,iconImg,108],
	  ['Hospitality',19.773387,73.619412,iconImg,109],
	  ['ISP',16.578478,74.314571,iconImg,110],
	  ['Healthcare',19.116294,72.909680,iconImg,112],
	  ['ISP',18.735395,73.722382,iconImg,113],
	  ['ISP',18.486299,73.931925,iconImg,116],
	  ['ISP',18.650706,73.839320,iconImg,118],
	  ['ISP',18.490831,73.868156,iconImg,121],
	  ['Sports & Outdoors',18.545472,73.910355,iconImg,124],
	  ['ISP',18.459653,73.878043,iconImg,125],
	  ['ISP',18.630013,73.814863,iconImg,127],
	  ['IT & ITES',19.186817,72.974699,iconImg,129],
	  ['ISP',18.498941,73.923318,iconImg,131],
	  ['ISP',19.053607,72.889894,iconImg,132],
	  ['ISP',18.633067,73.819774,iconImg,134],
	  ['ISP',18.501146,73.924225,iconImg,135],
	  ['ISP',18.391382,77.111085,iconImg,136],
	  ['ISP',18.565289,73.915915,iconImg,138],
	  ['Retail',19.148149,72.833246,iconImg,141],
	  ['ISP',18.611558,73.764852,iconImg,142],
	  ['ISP',18.670216,73.848550,iconImg,144],
	  ['ISP',18.468865,73.860553,iconImg,147],
	  ['ISP',18.552502,73.899765,iconImg,148],
	  ['IT & ITES',12.971891,77.641154,iconImg,149],
	  ['ISP',18.561725,73.931295,iconImg,150],
	  ['ISP',18.631237,73.789130,iconImg,152],
	  ['It & ITES',12.985292,77.533849,iconImg,156],
	  ['Hospitality',12.986016,77.755724,iconImg,171],
	  ['Education',12.972529,77.697008,iconImg,175],
	  ['ISP',20.018812,73.762625,iconImg,180],
	  ['Financial services',19.122444,72.881455,iconImg,182],
	  ['ISP',18.526285,73.950657,iconImg,185],
	  ['ISP',18.609919,73.802426,iconImg,186],
	  ['Financial services',28.467937,77.013615,iconImg,190],
	  ['ISP',18.480294,73.928043,iconImg,191],
	  ['ISP',18.612066,73.804330,iconImg,192],
	  ['ISP',18.573953,73.822286,iconImg,193],
	  ['ISP',18.618335,73.762989,iconImg,194],
          ['Education',30.332562,77.995836,iconImg,85194]
           
  
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
     
      center: new google.maps.LatLng(18.522559, 73.860666),

	 zoom: 5,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		icon: locations[i][3],
        map: map
		
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  </script>

            

            
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

