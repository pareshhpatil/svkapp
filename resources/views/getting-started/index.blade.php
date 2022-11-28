@extends('getting-started.layout')
@if($d_type=='web')
@include('home.googleauth')
@endif
@section('content')
<div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative sm:max-w-4xl sm:mx-auto">
        <div class="bg-white px-4 py-5 md:p-3 mx-2 lg:mx-0 shadow-xl rounded-xl">
            <div class="flex flex-row justify-center">
                <p class="text-xs text-gray-400 font-bold">1 of {{$total_step}}</p>
            </div>
            <div class="flex flex-col md:flex-row">
                <div class="overflow-hidden">
                    <img class="transform object-cover translate-y-0 md:translate-y-6" src="{{ asset('images/company-profile1.svg') }}" alt="App screenshot">
                </div>
                <div class="pl-4 flex flex-wrap content-start bg-white rounded-xl">
                    <div class="w-full max-w-xl mx-auto">
                        <h2 class="text-2xl font-bold text-aqua sm:text-4xl text-center">
                            Tell us about your business
                        </h2>
                        <p class="text-gray-400 pb-4">Setup your company information and account</p>

                        @if ($errors->any())
                        <div class="flex justify-left items-left m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                            <div class="text-sm font-normal  max-w-full flex-initial">
                                @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        <form action="/merchant/registersave" id="{{$d_type}}-form" x-data="data()" method="post" novalidate>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 ">Company name</label>
                                <div class="mt-1">
                                    <input type="text" x-model="companyname" maxlength="100" name="company_name" id="company-name" placeholder="Super Company Pvt Ltd" class="shadow-sm block w-full sm:text-sm border border-gray-300 rounded-md placeholder-gray-300" :class="showvalidation==true && companyname=='' ? 'border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-aqua focus:border-aqua' " aria-describedby="company-name" required>
                                    <span class="text-red-600 font-sm text-sm" x-text="showvalidation==true && companyname=='' ? 'Enter your company name' : '' "></span>
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mt-3">Full
                                    name</label>
                                <div class="mt-1">
                                    <input type="text" x-model="name" maxlength="100" name="name" id="name" class="shadow-sm block w-full sm:text-sm border border-gray-300 rounded-md placeholder-gray-300" :class="showvalidation==true && name=='' ? 'border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-aqua focus:border-aqua' " placeholder="Rohit Jadhav" aria-describedby="name" required>
                                    <span class="text-red-600 font-sm text-sm" x-text="showvalidation==true && name=='' ? 'Enter your full name' : '' "></span>

                                </div>
                            </div>
                            @if($detail->email_id=='')
                            <div id="email-enter">
                                <label for="email" class="block text-sm font-medium text-gray-700 mt-3">
                                    Email</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="mt-1 col-span-2">
                                        <input type="email" x-model="email" name="email" value="" id="email-id" class="shadow-sm block w-full sm:text-sm border border-gray-300 rounded-md placeholder-gray-300 " :class="validateemail()!='' ? 'border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-aqua focus:border-aqua' " placeholder="rohitjadhav@swipez.in" aria-describedby="email" required>
                                    </div>
                                    <div class="mt-1 text-center">
                                        <button type="button" onclick="hideMobileDiv('email-id')" id="{{$d_type}}-btn" class="h-full px-2 lg:px-5
                                            text-white hover:bg-aqua-700
                                            bg-aqua border rounded inline-flex 
                                            items-center text-base font-medium">Send OTP</button>
                                    </div>
                                </div>
                            </div>
                            @else
                            <input type="hidden" x-model="email" name="email" value="{{$detail->email_id}}" required>
                            @endif
                            @if($detail->mobile=='')
                            <div id="mobile-enter">
                                <label for="email" class="block text-sm font-medium text-gray-700 mt-3">Mobile
                                    number</label>
                                <div class="mt-1 grid grid-cols-3 gap-2 ">
                                    <div class="mt-1 relative col-span-2">
                                        <div class="absolute inset-y-0 left-0 flex items-center">
                                            <label for="country" class="sr-only">Country</label>
                                            <select id="country" name="country_code" class="h-full py-0 pl-3 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">


                                                <option selected value="91">91</option>
                                                <option value="93">93</option>
                                                <option value="355">355</option>
                                                <option value="213">213</option>
                                                <option value="376">376</option>
                                                <option value="244">244</option>
                                                <option value="672">672</option>
                                                <option value="54">54</option>
                                                <option value="374">374</option>
                                                <option value="297">297</option>
                                                <option value="61">61</option>
                                                <option value="43">43</option>
                                                <option value="994">994</option>
                                                <option value="973">973</option>
                                                <option value="880">880</option>
                                                <option value="375">375</option>
                                                <option value="32">32</option>
                                                <option value="501">501</option>
                                                <option value="229">229</option>
                                                <option value="975">975</option>
                                                <option value="591">591</option>
                                                <option value="387">387</option>
                                                <option value="267">267</option>
                                                <option value="55">55</option>
                                                <option value="246">246</option>
                                                <option value="673">673</option>
                                                <option value="359">359</option>
                                                <option value="226">226</option>
                                                <option value="257">257</option>
                                                <option value="855">855</option>
                                                <option value="237">237</option>
                                                <option value="1">1</option>
                                                <option value="238">238</option>
                                                <option value="236">236</option>
                                                <option value="235">235</option>
                                                <option value="56">56</option>
                                                <option value="86">86</option>
                                                <option value="61">61</option>
                                                <option value="61">61</option>
                                                <option value="57">57</option>
                                                <option value="269">269</option>
                                                <option value="682">682</option>
                                                <option value="506">506</option>
                                                <option value="385">385</option>
                                                <option value="53">53</option>
                                                <option value="599">599</option>
                                                <option value="357">357</option>
                                                <option value="420">420</option>
                                                <option value="243">243</option>
                                                <option value="45">45</option>
                                                <option value="253">253</option>
                                                <option value="670">670</option>
                                                <option value="593">593</option>
                                                <option value="20">20</option>
                                                <option value="503">503</option>
                                                <option value="240">240</option>
                                                <option value="291">291</option>
                                                <option value="372">372</option>
                                                <option value="251">251</option>
                                                <option value="500">500</option>
                                                <option value="298">298</option>
                                                <option value="679">679</option>
                                                <option value="358">358</option>
                                                <option value="33">33</option>
                                                <option value="689">689</option>
                                                <option value="241">241</option>
                                                <option value="220">220</option>
                                                <option value="995">995</option>
                                                <option value="49">49</option>
                                                <option value="233">233</option>
                                                <option value="350">350</option>
                                                <option value="30">30</option>
                                                <option value="299">299</option>
                                                <option value="502">502</option>
                                                <option value="224">224</option>
                                                <option value="245">245</option>
                                                <option value="592">592</option>
                                                <option value="509">509</option>
                                                <option value="504">504</option>
                                                <option value="852">852</option>
                                                <option value="36">36</option>
                                                <option value="354">354</option>
                                                <option value="62">62</option>
                                                <option value="98">98</option>
                                                <option value="964">964</option>
                                                <option value="353">353</option>
                                                <option value="972">972</option>
                                                <option value="39">39</option>
                                                <option value="225">225</option>
                                                <option value="81">81</option>
                                                <option value="962">962</option>
                                                <option value="7">7</option>
                                                <option value="254">254</option>
                                                <option value="686">686</option>
                                                <option value="383">383</option>
                                                <option value="965">965</option>
                                                <option value="996">996</option>
                                                <option value="856">856</option>
                                                <option value="371">371</option>
                                                <option value="961">961</option>
                                                <option value="266">266</option>
                                                <option value="231">231</option>
                                                <option value="218">218</option>
                                                <option value="423">423</option>
                                                <option value="370">370</option>
                                                <option value="352">352</option>
                                                <option value="853">853</option>
                                                <option value="389">389</option>
                                                <option value="261">261</option>
                                                <option value="265">265</option>
                                                <option value="60">60</option>
                                                <option value="960">960</option>
                                                <option value="223">223</option>
                                                <option value="356">356</option>
                                                <option value="692">692</option>
                                                <option value="222">222</option>
                                                <option value="230">230</option>
                                                <option value="262">262</option>
                                                <option value="52">52</option>
                                                <option value="691">691</option>
                                                <option value="373">373</option>
                                                <option value="377">377</option>
                                                <option value="976">976</option>
                                                <option value="382">382</option>
                                                <option value="212">212</option>
                                                <option value="258">258</option>
                                                <option value="95">95</option>
                                                <option value="264">264</option>
                                                <option value="674">674</option>
                                                <option value="977">977</option>
                                                <option value="31">31</option>
                                                <option value="599">599</option>
                                                <option value="687">687</option>
                                                <option value="64">64</option>
                                                <option value="505">505</option>
                                                <option value="227">227</option>
                                                <option value="234">234</option>
                                                <option value="683">683</option>
                                                <option value="850">850</option>
                                                <option value="47">47</option>
                                                <option value="968">968</option>
                                                <option value="92">92</option>
                                                <option value="680">680</option>
                                                <option value="970">970</option>
                                                <option value="507">507</option>
                                                <option value="675">675</option>
                                                <option value="595">595</option>
                                                <option value="51">51</option>
                                                <option value="63">63</option>
                                                <option value="64">64</option>
                                                <option value="48">48</option>
                                                <option value="351">351</option>
                                                <option value="974">974</option>
                                                <option value="242">242</option>
                                                <option value="262">262</option>
                                                <option value="40">40</option>
                                                <option value="7">7</option>
                                                <option value="250">250</option>
                                                <option value="590">590</option>
                                                <option value="290">290</option>
                                                <option value="590">590</option>
                                                <option value="508">508</option>
                                                <option value="685">685</option>
                                                <option value="378">378</option>
                                                <option value="239">239</option>
                                                <option value="966">966</option>
                                                <option value="221">221</option>
                                                <option value="381">381</option>
                                                <option value="248">248</option>
                                                <option value="232">232</option>
                                                <option value="65">65</option>
                                                <option value="421">421</option>
                                                <option value="386">386</option>
                                                <option value="677">677</option>
                                                <option value="252">252</option>
                                                <option value="27">27</option>
                                                <option value="82">82</option>
                                                <option value="211">211</option>
                                                <option value="34">34</option>
                                                <option value="94">94</option>
                                                <option value="249">249</option>
                                                <option value="597">597</option>
                                                <option value="47">47</option>
                                                <option value="268">268</option>
                                                <option value="46">46</option>
                                                <option value="41">41</option>
                                                <option value="963">963</option>
                                                <option value="886">886</option>
                                                <option value="992">992</option>
                                                <option value="255">255</option>
                                                <option value="66">66</option>
                                                <option value="228">228</option>
                                                <option value="690">690</option>
                                                <option value="676">676</option>
                                                <option value="216">216</option>
                                                <option value="90">90</option>
                                                <option value="993">993</option>
                                                <option value="688">688</option>
                                                <option value="256">256</option>
                                                <option value="380">380</option>
                                                <option value="971">971</option>
                                                <option value="44">44</option>
                                                <option value="1">1</option>
                                                <option value="598">598</option>
                                                <option value="998">998</option>
                                                <option value="678">678</option>
                                                <option value="379">379</option>
                                                <option value="58">58</option>
                                                <option value="84">84</option>
                                                <option value="681">681</option>
                                                <option value="212">212</option>
                                                <option value="967">967</option>
                                                <option value="260">260</option>
                                                <option value="263">263</option>

                                            </select>
                                        </div>
                                        <input type="number" x-model="mobile" required maxlength="10" name="mobile" step="1" pattern="^(\+[\d]{1,5}|0)?[1-9]\d{9}$" id="phone_number" class="block w-full pl-16 sm:text-sm border-gray-300 rounded-md placeholder-gray-300" :class="showvalidation==true && mobile.length<10 ? 'border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-aqua focus:border-aqua' " id="mobile" placeholder="7303885709">
                                    </div>
                                    <div class="mt-1">
                                        <!-- <button type="button" class="h-full lg:w-full
                                            text-white hover:bg-aqua-700
                                            bg-aqua border rounded inline-flex 
                                            content-center text-base font-medium">Send OTP</button> -->
                                        <button type="button" onclick="hideMobileDiv('phone_number')" id="{{$d_type}}-btn" 
                                        class="bg-aqua hover:bg-aqua-700 border rounded text-white h-full w-full md:px-1 sm:px-4 lg:px-4 rounded">
                                            Send OTP
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="mobile" value="{{$detail->mobile}}" required>
                            @endif
                            <div id="web-otp-container" class="hidden">
                                <label for="email" class="block text-sm font-medium text-gray-700 mt-3">Enter OTP</label>
                                <div class="mt-1 grid grid-cols-6">
                                    <div class="mt-1 relative rounded-md col-span-3">
                                        <input type="number" name="otp" id="web-otp" onkeydown="limit(this);" onkeyup="limit(this);" 
                                        class="block w-full sm:text-sm border-gray-300 rounded-md placeholder-gray-300
                                            border-gray-300 focus:ring-aqua focus:border-aqua" placeholder="1234">
                                    </div>
                                    <div class="mt-1 relative rounded-md text-center absolute col-span-2">
                                        <button type="button" id="submitbutton" onclick="enableSubmit()" 
                                        class="w-4/5 bg-aqua hover:bg-aqua-700 border rounded text-white h-full">
                                            Verify OTP
                                        </button>
                                    </div>
                                    <div class="mt-1 relative rounded-md text-center absolute col-span-1">
                                        <button type="button" id="backbutton" onclick="backButtonClicked()" 
                                        class="bg-white border rounded text-white w-4/5 h-full 
                                        border border-gray-300 rounded-md 
                                            shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 
                                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua">
                                            Back
                                        </button>
                                    </div>
                                    <!-- <span class="text-red-600 font-sm text-sm" x-text="showvalidation==true && mobile.length < 10 ? 'Mobile number should be 10 digit' : '' "></span> -->
                                </div>
                                <p class="text-gray-600" id="otpSentSuccessMessage"></p>
                                <span class="text-red-600 font-sm text-sm" id="otpErrorMessage"></span>
                                <span class="text-red-600 font-sm text-sm" id="otpVerifiedMessage"></span>
                            </div>
                            <span class="text-red-600 font-sm text-sm" id="web-error"></span>
                            @if($google_validate!=true)
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mt-3">
                                    Password
                                </label>
                                <div class="mt-1">
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input id="password" x-model="password" name="password" :type="show ? 'password' : 'text'" maxlength="20" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2  border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm" :class="showvalidation==true && password.length<6 ? 'border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-aqua focus:border-aqua' ">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">

                                            <svg class="h-4 text-gray-600" fill="none" @click="show = !show" :class="{'hidden': !show, 'block':show }" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 576 512">
                                                <path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                                </path>
                                            </svg>

                                            <svg class="h-4 text-gray-600" fill="none" @click="show = !show" :class="{'block': !show, 'hidden':show }" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 640 512">
                                                <path fill="currentColor" d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                                </path>
                                            </svg>

                                        </div>

                                    </div>
                                    <span class="text-red-600 font-sm text-sm" x-text="showvalidation==true && password.length < 6 ? 'Password length should be between 6 to 20 characters' : '' "></span>
                                </div>
                            </div>
                            @else
                            <input type="hidden" x-model="password" name="password" value="{{$password}}" required>
                            @endif

                            <br /><br /><br />
                            <div class="absolute bottom-0 right-0">
                                <input type="hidden" name="recaptcha_response" id="captcha{{$d_type}}">
                                <input type="hidden" @if($d_type=='web' ) id="service_id" @endif name="service_id" value="{{$service_id}}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" id="web-username" name="username">
                                <input type="hidden" id="exist_type" name="exist_type" value="3">

                                <button id="setupAccount" type="submit" @click="return validate();" class="mr-5 lg:mr-3 mb-3 
                                    bg-aqua border border-transparent rounded-md shadow px-6 py-3 inline-flex 
                                    items-center text-base font-medium text-white hover:bg-aqua-700 opacity-50" disabled>Setup account</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $('#web-otp').keypress(function(e) {
      var arr = [];
      var kk = e.which;
   
      for (i = 48; i < 58; i++)
          arr.push(i);
   
      if (!(arr.indexOf(kk)>=0))
          e.preventDefault();
  });
</script>
<script>
    data = function() {
        return {
            show: true,
            showvalidation: false,
            password: '{{$password}}',
            email: '{{old("email")??$detail->email_id}}',
            emailerror: '',
            mobile: '{{old("mobile")??$detail->mobile}}',
            companyname: '{{old("company_name")}}',
            name: '{{old("name")}}',
            validateemail() {
                const rgx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                if (!rgx.test(this.email) && this.showvalidation == true) {
                    return "Enter valid email id";
                } else {
                    return "";
                }
            },
            validate() {
                this.showvalidation = true;
                var validform = true;
                if (this.companyname == '') {
                    validform = false;
                }
                if (this.name == '') {
                    validform = false;
                }

                if (this.mobile.length < 10) {
                    validform = false;
                }

                if (this.validateemail() != "") {
                    validform = false;
                }

                if (this.password.length < 6) {
                    validform = false;
                }
                return validform;

            }
        }
    };
</script>


@endsection