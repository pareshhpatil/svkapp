$(document).ready(function(){	
	$('a.menu-icon').click(function() {
        if ($(this).hasClass('active')) {
            $(this).parent().find('nav').slideUp();
			$(this).removeClass('active');
        } else {
            $(this).addClass('active');
            $(this).parent().find('nav').slideDown();
        }
		$('ul.main-nav li a').click(function() {
			$('nav').slideUp();
			$('a.menu-icon').removeClass('active');
			});
    });
/*---Header-small-------------------------------------*/
function init() {
  window.addEventListener('scroll', function(e){
    var distanceY = window.pageYOffset || document.documentElement.scrollTop,
        shrinkOn = 200,
        header = document.querySelector("header");
    if (distanceY > shrinkOn) {
      header.setAttribute("class","smaller");
    } else {
        header.removeAttribute("class");
    }
  });
}

/*window.onload = init();

jQuery(function( $ ){			
var $target = $('body');			 
$('#s1nav').click(function() {
	$target.scrollTo('#s1', 800);
	$("ul.main-nav li a").removeClass('active');
	$(this).addClass('active');				 
	return false;
});
$('#s2nav').click(function() {
	$target.scrollTo('#s2', 800);
	$("ul.main-nav li a").removeClass('active');
	$(this).addClass('active');
	return false;
});	
$('#s3nav').click(function() {
	$target.scrollTo('#s3', 800);
	$("ul.main-nav li a").removeClass('active');
	$(this).addClass('active');
	return false;
});	
$('#s4nav').click(function() {
	$target.scrollTo('#s4', 800);
	$("ul.main-nav li a").removeClass('active');
	$(this).addClass('active');
	return false;
});	
$('#s5nav').click(function() {
	$target.scrollTo('#s5', 800);
	$("ul.main-nav li a").removeClass('active');
	$(this).addClass('active');
	return false;
});	
$('#s6nav').click(function() {
	$target.scrollTo('#s6', 800);
	$("ul.main-nav li a").removeClass('active');
	$(this).addClass('active');
	return false;
});	
});
function appendObj(){
if($("body").length>0){
winTop = $(window).scrollTop();
winht = $(window).height();
if(winTop+(winht/1.3) >= $("section.banner-img,section.info").offset().top) $("section.banner-img,section.info").addClass("animated bounceInDown"); 
if(winTop+(winht/1.3) >= $("section.about-us").offset().top) $("section.about-us").addClass("animated bounceInLeft"); 
if(winTop+(winht/1.3) >= $("section.our-services").offset().top) $("section.our-services").addClass("animated bounceInRight");  
if(winTop+(winht/1.3) >= $("section.the-team").offset().top) $("section.the-team").addClass("animated slideInUp"); 
if(winTop+(winht/1.3) >= $("section.testimonial").offset().top) $("section.testimonial").addClass("animated slideInUp"); 
if(winTop+(winht/1.3) >= $("section.pricing-plans").offset().top) $("section.pricing-plans").addClass("animated zoomInUp"); 
if(winTop+(winht/1.3) >= $("section.our-projects").offset().top) $("section.our-projects").addClass("animated fadeInLeft");  
if(winTop+(winht/1.3) >= $("section.contact-us").offset().top) $("section.contact-us").addClass("animated fadeInRight");  
if(winTop+(winht/1.3) >= $("footer").offset().top) $("footer").addClass("animated bounceInLeft");
}
}
$(window).load(appendObj);
$(window).scroll(function(){
	appendObj(); 
});*/
});