<!DOCTYPE html>
<html>

<head>
    <title>The page you were looking for doesn't exist (404)</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- This file lives in public/404.html -->
    <div class="container mx-auto px-4">
        <section class="py-8 px-4 text-center">
            <div class="max-w-auto mx-auto">
                <h2 class="mt-8 text-xl lg:text-5xl">Merchant page not found</h2>
                <p class="mt-6 text-xl lg:text-3xl text-gray-700 md:w-3/5 mx-auto">The merchant page you are looking is not hosted on Swipez anymore.</p>
                <div class="md:max-w-lg mx-auto mt-4">
                    <img class="w-94 mx-auto" src="{{ asset('landingpage/error/404-main-image.svg') }}">
                </div>
                <p class="mt-6 text-xl lg:text-3xl text-gray-600 hover:text-gray-700">Get a free account for your business.</p>
                <div class="md:flex md:flex-wrap justify-between md:w-4/5 mx-auto">
                    <a href="https://www.swipez.in/billing-software" class="py-4 px-2 border-2 mt-4 border-gray-600 rounded-2xl flex flex-col flex-1">
                        <img class="h-38 mx-auto" src="{{asset('landingpage/error/transactions.svg')}}">
                        <h2 class="text-xl lg:text-3xl mt-2 text-gray-600 mx-auto">Billing software</h2>
                    </a>
                    <a href="https://www.swipez.in/payment-collections" class="py-4 px-2 md:ml-4 mt-4 border-2 border-gray-600 rounded-2xl flex-1">
                        <img class="h-20 mx-auto" src="{{asset('landingpage/error/collect.svg')}}">
                        <h2 class="text-xl lg:text-3xl text-gray-600 mx-auto">Payment gateway</h2>
                    </a>
                    <a href="https://www.swipez.in/collect-it-billing-app" class="py-4 px-2 md:ml-4 mt-4 border-2 border-gray-600 rounded-2xl flex-1">
                        <img class="h-20 mx-auto" src="{{asset('landingpage/error/welcome.svg')}}">
                        <h2 class="text-xl lg:text-3xl text-gray-600 mx-auto">Free billing app</h2>
                    </a>
                </div>
            </div>
        </section>
    </div>
</body>

</html>