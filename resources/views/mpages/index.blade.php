<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
        <!-- <link href="{{ asset('assets/admin/layout/css/movingintotailwind.css') }}" rel="stylesheet"> -->
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        <!-- Styles -->
        <style>
        .gray-bg-1 {
            background-color: #EAEAEA;
        }
        .gray-bg-2 {
            background-color: #DDDDDD;
        }
        .blue-bg-1 {
            background-color: #1C729F;
        }
        .text-color-1 {
            color: #1C729F;
        }
        .text-color-2 {
            color: #555555;
        }
        .carousel-open:checked + .carousel-item {
                    position: static;
                    opacity: 100;
                }
        .carousel-open:checked + .carousel-item-hero {
                    position: static;
                    opacity: 100;
                }
                .carousel-item, .carousel-item-hero {
                    -webkit-transition: opacity 0.6s ease-out;
                    transition: opacity 0.6s ease-out;
                }

                #carousel-1-hero:checked ~ .control-1-hero,
                #carousel-2-hero:checked ~ .control-2-hero,
                #carousel-3-hero:checked ~ .control-3-hero {
                    display: block;
                }

                .carousel-indicators, .carousel-indicators-hero {
                    list-style: none;
                    margin: 0;
                    padding: 0;
                    position: absolute;
                    bottom: 2%;
                    left: 0;
                    right: 0;
                    text-align: center;
                    z-index: 10;
                }
                #carousel-1-hero:checked ~ .control-1-hero ~ .carousel-indicators-hero li:nth-child(1) .carousel-bullet,
                #carousel-2-hero:checked ~ .control-2-hero ~ .carousel-indicators-hero li:nth-child(2) .carousel-bullet,
                #carousel-3-hero:checked ~ .control-3-hero ~ .carousel-indicators-hero li:nth-child(3) .carousel-bullet {
                    color: #2b6cb0;  /*Set to match the Tailwind colour you want the active one to be */
                }
        </style>
        
    </head>
    <body>
    <!-- section header -->
    <!-- <header x-data="{ mobileMenuOpen : false }" class="flex flex-wrap flex-row justify-between items-center md:space-x-4 bg-white py-4 px-4 xl:px-6 xl:mx-28 2xl:mx-64 relative"> -->
    <header class="flex flex-wrap flex-row justify-between items-center md:space-x-4 bg-white py-4 px-4 xl:px-6 xl:mx-28 2xl:mx-64 relative">
        <a href="#" class="block">
          <img class="h-12 md:h-10 " src="{{asset('img/logo.png')}}" alt="Themes.dev Logo" title="Themes.dev Logo">
        </a>
        <button class="inline-block md:hidden w-12 h-12 text-color-1 p-1" id="hamburger">
          <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
        </button>
        <!-- <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-block md:hidden w-12 h-12 text-color-1 p-1">
          <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
        </button> -->
        <nav id="navbar-list" class="sm:text-center navbar-list hidden md:block absolute md:relative top-16 left-0 md:top-0 z-20 md:flex flex-col md:flex-row md:space-x-6 font-semibold w-full md:w-auto bg-white shadow-md md:rounded-none md:shadow-none md:bg-transparent p-6 pt-0 md:p-0"
        :class="{ 'flex' : mobileMenuOpen , 'hidden' : !mobileMenuOpen}">
          <a href="#" class="text-center block py-2 md:py-1 text-color-2 hover:text-gray-700 focus:text-blue-700">Home</a>
          <a href="#" class="text-center block py-2 md:py-1 text-color-2 hover:text-gray-700 focus:text-blue-700">About us</a>
          <a href="#" class="text-center block py-2 md:py-1 text-color-2 hover:text-gray-700 focus:text-blue-700">Services</a>
          <a href="#" class="text-center block py-2 md:py-1 text-color-2 hover:text-gray-700 focus:text-blue-700">Blog</a>
          <a href="#" class="text-center block py-2 md:py-1 text-color-2 hover:text-gray-700 focus:text-blue-700">Contact</a>
          <span class="inline-flex md:ml-auto py-2 md:py-1 md:pl-10 items-center sm:mt-0 mt-2 justify-center sm:ml-0">
            <a class="text-gray-500">
              <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
              </svg>
            </a>
            <a class="ml-3 text-gray-500">
              <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
              </svg>
            </a>
            <a class="ml-3 text-gray-500">
              <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
              </svg>
            </a>
            <a class="ml-3 text-gray-500">
              <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="w-5 h-5" viewBox="0 0 24 24">
                <path stroke="none" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path>
                <circle cx="4" cy="4" r="2" stroke="none"></circle>
              </svg>
            </a>
          </span>
        </nav>
      </header>
      <!-- end section header -->

      <!-- panel section -->

      <section class="text-gray-600 body-font gray-bg-1">
        <div class="container mx-auto md:pt-10 flex flex-col gray-bg-1">
          <div class="relative">
            <div class="carousel relative bg-white lg:mx-20">
              <div class="carousel-inner relative overflow-hidden w-full gray-bg-1">
                <!--Slide 1-->
                <input class="carousel-open hero-carousel" type="radio" id="carousel-1-hero" name="carousel-hero" aria-hidden="true" hidden="" checked="checked">
                <div class="carousel-item-hero absolute opacity-0 lg:mx-5 gray-bg-1" style="height:60vh;">
                  <div class="block h-full w-full bg-indigo-500 text-white text-5xl text-center">
                    <img alt="content" class="object-cover object-center h-full w-full" src="{{asset('img/hills.jpg')}}">
                  </div>
                </div>
                <!-- <div class="absolute top-0 left-0 justify-center md:w-2/5 w-4/5 md:pl-16 md:pt-20 pt-20 px-4 text-white">
                  <h1 class="md:text-4xl text-2xl font-semibold">Your slider heading goes here on each slide</h1>
                  <p class="leading-relaxed text-base mt-4 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse euismod suscipit urna non convallis.</p>
                </div> -->
                <label for="carousel-3-hero" style="padding-top:2px;" class="prev ml-2 md:ml-0 items-center control-1-hero w-12 h-12 absolute cursor-pointer hidden text-3xl text-white hover:text-white rounded-full opacity-80 blue-bg-1 leading-tight text-center items-center z-10 inset-y-0 left-0 md:mb-auto mt-auto mb-12">‹</label>
                <label for="carousel-2-hero" style="padding-top:2px;" class="next control-1-hero w-12 h-12 mr-2 md:mr-0 absolute cursor-pointer hidden text-3xl text-white hover:text-white rounded-full opacity-80 blue-bg-1 leading-tight text-center items-center z-10 inset-y-0 right-0 md:mb-auto mt-auto mb-12" id="move-right1">›</label>
                
                <!--Slide 2-->
                <input class="carousel-open hero-carousel" type="radio" id="carousel-2-hero" name="carousel-hero" aria-hidden="true" hidden="">
                <div class="carousel-item-hero absolute opacity-0 md:mx-5 gray-bg-1" style="height:60vh;">
                  <div class="block h-full w-full bg-orange-500 text-white text-5xl text-center">
                    <img alt="content" class="object-cover object-center h-full w-full" src="{{asset('img/mountains.jpg')}}">
                  </div>
                </div>
                <!-- <div class="absolute top-0 left-0 justify-center md:w-2/5 w-4/5 md:pl-16 md:pt-20 pt-20 px-4 text-white">
                  <h1 class="md:text-4xl text-2xl font-semibold">Your slider heading goes here on each slide</h1>
                  <p class="leading-relaxed text-base mt-4 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse euismod suscipit urna non convallis.</p>
                </div> -->
                <label for="carousel-1-hero" style="padding-top:2px;" class="prev control-2-hero w-12 h-12 ml-2 md:ml-0 absolute cursor-pointer hidden text-3xl text-white hover:text-white rounded-full opacity-80 blue-bg-1 hover:opacity-80 leading-tight text-center z-10 inset-y-0 left-0 md:mb-auto mt-auto mb-12">‹</label>
                <label for="carousel-3-hero" style="padding-top:2px;" class="next control-2-hero w-12 h-12 mr-2 md:mr-0 absolute cursor-pointer hidden text-3xl text-white hover:text-white rounded-full opacity-80 blue-bg-1 hover:opacity-80 leading-tight text-center z-10 inset-y-0 right-0 md:mb-auto mt-auto mb-12" id="move-right2">›</label> 
                
                <!--Slide 3-->
                <input class="carousel-open hero-carousel" type="radio" id="carousel-3-hero" name="carousel-hero" aria-hidden="true" hidden="">
                <div class="carousel-item-hero absolute opacity-0 md:mx-5 gray-bg-1" style="height:60vh;">
                  <div class="block h-full w-full bg-green-800 text-white text-5xl text-center">
                    <img alt="content" class="object-cover object-center h-full w-full" src="{{asset('img/river.jpg')}}">
                  </div>
                </div>
                <!-- <div class="absolute top-0 left-0 justify-center md:w-2/5 w-4/5 md:pl-16 md:pt-20 pt-20 px-4 text-white">
                  <h1 class="md:text-4xl text-2xl font-semibold">Your slider heading goes here on each slide</h1>
                  <p class="leading-relaxed text-base mt-4 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse euismod suscipit urna non convallis.</p>
                </div> -->
                <label for="carousel-2-hero" style="padding-top:2px;" class="prev control-3-hero w-12 h-12 ml-2 md:ml-0 absolute cursor-pointer hidden text-3xl text-white hover:text-white rounded-full opacity-80 blue-bg-1 hover:opacity-80 leading-tight text-center z-10 inset-y-0 left-0 md:mb-auto mt-auto mb-12">‹</label>
                <label for="carousel-1-hero" style="padding-top:2px;" class="next control-3-hero w-12 h-12 mr-2 md:mr-0 absolute cursor-pointer hidden text-3xl text-white hover:text-white rounded-full opacity-80 blue-bg-1 hover:opacity-80 leading-tight text-center z-10 inset-y-0 right-0 md:mb-auto mt-auto mb-12" id="move-right3">›</label>
                 
              </div>
            </div>
            <div class="absolute top-0 left-0 justify-center 2xl:w-2/5 xl:w-1/2 md:3/5 sm:w-3/5 lg:w-3/5 w-4/5 md:pl-36 md:pt-20 pt-20 px-4 text-white">
                <h1 class="md:text-4xl text-2xl font-semibold">Your slider heading goes here on each slide</h1>
                <p class="leading-relaxed text-base mt-4 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse euismod suscipit urna non convallis.</p>
            </div>
        </div>
    </div>
</section> 
<section class="text-gray-600 body-font gray-bg-1 md:pb-0 pb-8">
    <div class="container mx-auto md:-mt-20 -mt-8 flex flex-col gray-bg-1">
        <div class="text-center rounded-2xl md:mx-14 xl:mx-52 lg:mx-40 px-2 sm:px-6 bg-white relative z-10 mx-2">
          <h1 class="text-2xl tracking-tight pt-4 px-1 font-bold text-color-1">Hey, where do you want to go?</h1>
          <div class="flex w-full sm:flex-row flex-col mx-auto md:px-6 px-4 py-6 sm:space-x-4 sm:space-y-0 space-y-4 sm:px-0 md:items-end">
            <div class="relative flex-grow w-full">
              <input type="text" id="full-name" name="full-name" placeholder="Name" class="w-full bg-opacity-50 rounded border border-gray-900 h-12 focus:border-blue-500 focus:bg-transparent focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
            </div>
            <div class="relative flex-grow w-full">
              <input type="email" id="email" name="email" placeholder="Email" class="w-full bg-opacity-50 rounded border border-gray-900 h-12 focus:border-blue-500 focus:bg-transparent focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
            </div>
            <button class="2xl:w-1/3 xl:w-1/2 sm:w-4/5 md:w-3/4 w-1/2 mx-auto text-white h-12 blue-bg-1 border-0 px-2 font-light focus:outline-none hover:bg-blue-600 rounded text-lg"><i class="fas fa-search"></i> Search Rooms</button>
          </div>
        </div>
    </div>
</section>
<div class="h-16 gray-bg-1"></div>
<section class="text-gray-600 body-font overflow-hidden">
  <div class="container py-16 mx-auto">
    <div class="md:relative lg:w-3/4 mx-auto flex flex-wrap">
      <img alt="ecommerce" class="sm:w-1/2 hidden lg:block my-10 object-cover object-center rounded-l-2xl" style="height:50vh" src="{{asset('img/river.jpg')}}">
      <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0 mx-4 lg:mx-0 bg-gray-100 pr-10 rounded-3xl py-10 pl-10 lg:py-0 lg:pl-0 lg:pt-10">
        <h1 class="text-gray-900 text-4xl title-font font-medium mb-1 text-center">Why work with us?</h1>
        <p class="leading-relaxed text-lg mt-10">Fam locavore kickstarter distillery. Mixtape chillwave tumeric sriracha taximy chia microdosing tilde DIY. XOXO fam indxgo juiceramps cornhole raw denim forage brooklyn. Everyday carry +1 seitan poutine tumeric. Gastropub blue bottle austin listicle pour-over, neutra jean shorts keytar banjo tattooed umami cardigan.</p>
        <div class="flex mt-10">
          <button class="flex mx-auto text-white blue-bg-1 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded-xl">GET IN TOUCH</button>

        </div>
      </div>
    </div>
  </div>
</section>

      <script>
          var hero = document.querySelectorAll('.hero-carousel');
          var hero_length = hero.length;
          var current_hero = 0;
          setInterval(function(){
            if(current_hero == hero_length){
                current_hero = 0;
            }
            for(let i=0;i<hero_length;i++){
                hero[i].removeAttribute('checked');
            }
            hero[current_hero].setAttribute('checked', "checked");
            current_hero = current_hero+1; 
          },3000);

          document.getElementById('hamburger').addEventListener('click', function(){
            let element = document.getElementsByClassName('navbar-list');
            if(element.length >0){
                document.getElementById('navbar-list').classList.remove('hidden');
                document.getElementById('navbar-list').classList.remove('navbar-list');
            }  
            else{
                document.getElementById('navbar-list').classList.add('hidden');
                document.getElementById('navbar-list').classList.add('navbar-list');
            }
         
          });
      </script>
</body>
</html>