<script>
    var t = '{$pg.swipez_fee_type}';
    var e = '{$pg.swipez_fee_val}';
    var a = '{$pg.pg_fee_type}';
    var n = '{$pg.pg_fee_val}';
    var u = '{$pg.pg_tax_val}';

</script>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-1"></div>
        <div class="col-md-10" >
            <div class="portlet " id="form_wizard_1" >
                <div class="portlet-title">
                    <div class="caption">
                        PUNEEATOUTS PRIVILAGE CARD 2017-18
                    </div>
                </div>
                <div class="portlet-body form">
                    <form action="{$post_link}" onsubmit="return validate();" class="form-horizontal" id="submit_form" method="POST">
                        <div class="form-wizard">
                            <div class="form-body">
                                <ul class="nav nav-pills nav-justified steps">
                                    <li>
                                        <a href="#tab1" data-toggle="tab" class="step">
                                            <span class="number">
                                                1 </span>
                                            <span class="desc">
                                                <i class="fa fa-check"></i>  Personal Info </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab2" data-toggle="tab" class="step">
                                            <span class="number">
                                                2 </span>
                                            <span class="desc">
                                                <i class="fa fa-check"></i> Tasting Profile </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab3" data-toggle="tab" class="step active">
                                            <span class="number">
                                                3 </span>
                                            <span class="desc">
                                                <i class="fa fa-check"></i> Payment </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab4" data-toggle="tab" class="step">
                                            <span class="number">
                                                4 </span>
                                            <span class="desc">
                                                <i class="fa fa-check"></i> Confirm </span>
                                        </a>
                                    </li>
                                </ul>
                                <div id="bar" class="progress progress-striped" role="progressbar">
                                    <div class="progress-bar progress-bar-success">
                                    </div>
                                </div>
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
                                        <h3 class="block">Provide your personal details</h3>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <h4 >* Your Name ? <span style="font-size: 13px;">Your card will have your 1st and last name. No other name or pet name please.</span> </h4>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label>* First name</label>
                                                                <input type="text" required="" id="f_name" onblur="copy_name(this.value);"  {$validate.name} name="1_value" class="form-control">
                                                                <input type="hidden" name="custom_column_id[]" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label>* Last name</label>
                                                                <input type="text" required="" id="l_name" onblur="copy_name(this.value);"  {$validate.name} name="2_value" class="form-control">
                                                                <input type="hidden" name="custom_column_id[]" value="2">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* Your Email ? </h4>
                                                                <input type="text" required=""  {$validate.email} onblur="copyfield(this.value, 'email');" name="3_value" class="form-control">
                                                                <input type="hidden" name="custom_column_id[]" value="3">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* Phone Number </h4>
                                                                <input type="text" {$validate.mobile} onblur="copyfield(this.value, 'mobile');"  required="" name="8_value" class="form-control">
                                                                <input type="hidden" name="custom_column_id[]" value="8">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* Address <span style="font-size: 13px;">You will receive your card on this address. Ensure you have filled all the details correctly.</span>
                                                                </h4>
                                                                <textarea required="" name="4_value" onblur="copyfield(this.value, 'address');" {$validate.address}  class="form-control"></textarea>
                                                                <input type="hidden" name="custom_column_id[]" value="4">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* City </h4>
                                                                <input type="text" required="" onblur="copyfield(this.value, 'city');" {$validate.name} name="5_value" class="form-control">
                                                                <input type="hidden" name="custom_column_id[]" value="5">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* State </h4>
                                                                <input type="text" required=""  onblur="copyfield(this.value, 'state');" {$validate.name} name="6_value" class="form-control">
                                                                <input type="hidden" name="custom_column_id[]" value="6">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* Pincode </h4>
                                                                <input type="text" {$validate.number} onblur="copyfield(this.value, 'pincode');" required="" name="7_value" class="form-control">
                                                                <input type="hidden" name="custom_column_id[]" value="7">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* Your Birthdate ? </h4>
                                                                <input type="hidden" name="custom_column_id[]" value="9">
                                                                <input class="form-control form-control-inline date-picker" type="text" required  name="9_value" data-date-format="dd-MM" data-date-viewmode="years" data-date-minviewmode="months" placeholder="Birth date"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 > Your Anniversary ?</h4>
                                                                <input type="hidden" name="custom_column_id[]" value="10">
                                                                <input class="form-control form-control-inline date-picker" type="text"  name="10_value" data-date-format="dd-MM" data-date-viewmode="years" data-date-minviewmode="months" placeholder="Anniversary date"/>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab2">
                                        <h3 class="block">This section is to know you better and also invite you for the food events based on your food preferences and expertise</h3>
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* Your Age ? </h4>
                                                                <select required class="form-control" name="11_value">
                                                                    <option value="">Please select..</option>
                                                                    <option value="Less than 25 years">Less than 25 years</option>
                                                                    <option value="25 to 35 Years">25 to 35 Years</option>
                                                                    <option value="35 to 50 Years">35 to 50 Years</option>
                                                                    <option value="50+ Years">50+ Years</option>
                                                                </select>
                                                                <input type="hidden" name="custom_column_id[]" value="11">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* Gender? </h4>
                                                                <select required="" class="form-control" name="12_value">
                                                                    <option value="">Please select..</option>
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                </select>
                                                                <input type="hidden" name="custom_column_id[]" value="12">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* Area in Pune closest to you? <span style="font-size: 13px;">Chose any one - It need not be exact but just closest to you </span> </h4>
                                                                <select required class="form-control" name="13_value">
                                                                    <option value="">Please select..</option>
                                                                    <option value="Aundh">Aundh</option>
                                                                    <option value="Hinjewadi">Hinjewadi</option>
                                                                    <option value="Pashan">Pashan</option>
                                                                    <option value="Nigdi">Nigdi</option>
                                                                    <option value="VimanNagar">VimanNagar</option>
                                                                    <option value="Koregaon Park">Koregaon Park</option>
                                                                    <option value="Hadapsar">Hadapsar</option>
                                                                    <option value="Swargate I Katraj">Swargate I Katraj</option>
                                                                    <option value="Kothrud">Kothrud</option>
                                                                    <option value="Laxmi Road">Laxmi Road</option>
                                                                    <option value="Chinchwad">Chinchwad</option>
                                                                    <option value="NIBM">NIBM</option>
                                                                </select>
                                                                <input type="hidden" name="custom_column_id[]" value="13">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5">

                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* Your mother cuisine / State ? <span style="font-size: 13px;"> Just like mother tongue, we want to know the cuisine that you inherited. Just let us know your mother state.</span> </h4>
                                                                <select required="" class="form-control" name="14_value">
                                                                    <option value="">Please select..</option>
                                                                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                                    <option value="Assam">Assam</option>
                                                                    <option value="Bihar">Bihar</option>
                                                                    <option value="Chhattisgarh">Chhattisgarh</option>
                                                                    <option value="Chandigarh">Chandigarh</option>
                                                                    <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                                                    <option value="Daman and Diu">Daman and Diu</option>
                                                                    <option value="Delhi">Delhi</option>
                                                                    <option value="Goa">Goa</option>
                                                                    <option value="Gujarat">Gujarat</option>
                                                                    <option value="Haryana">Haryana</option>
                                                                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                                    <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                                                    <option value="Jharkhand">Jharkhand</option>
                                                                    <option value="Karnataka">Karnataka</option>
                                                                    <option value="Kerala">Kerala</option>
                                                                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                                    <option value="Maharashtra">Maharashtra</option>
                                                                    <option value="Manipur">Manipur</option>
                                                                    <option value="Meghalaya">Meghalaya</option>
                                                                    <option value="Mizoram">Mizoram</option>
                                                                    <option value="Nagaland">Nagaland</option>
                                                                    <option value="Orissa">Orissa</option>
                                                                    <option value="Punjab">Punjab</option>
                                                                    <option value="Pondicherry">Pondicherry</option>
                                                                    <option value="Rajasthan">Rajasthan</option>
                                                                    <option value="Sikkim">Sikkim</option>
                                                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                                                    <option value="Tripura">Tripura</option>
                                                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                                    <option value="Uttarakhand">Uttarakhand</option>
                                                                    <option value="West Bengal">West Bengal</option>
                                                                </select>
                                                                <input type="hidden" name="custom_column_id[]" value="14">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >Speciality of cuisinesIn <span style="font-size: 13px;">case you want to add super speciality of cuisines do let us know. E.g. In Maharashtra you can mention Kolhapuri or Khandeshi.</span> </h4>
                                                                <input type="text" name="15_value" class="form-control">
                                                                <input type="hidden" name="custom_column_id[]" value="15">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <h4 >* Your most explored cuisines ? <span style="font-size: 13px;">Select any 2. This is not to get the exact cuisine details but just to know a little more about you.</span> </h4>

                                                                <label><input type="checkbox" name="16_value[]" value="North Indian" class="form-control roles">North Indian</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="16_value[]" value="Maharashtrian" class="form-control roles">Maharashtrian</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="16_value[]" value="South Indian" class="form-control roles">South Indian</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="16_value[]" value="East Indian" class="form-control roles">East Indian</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="16_value[]" value="European" class="form-control roles">European</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="16_value[]" value="Chinese" class="form-control roles">Chinese</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="16_value[]" value="Pan Asian" class="form-control roles">Pan Asian</label>&nbsp;&nbsp;
                                                                <input type="hidden" name="custom_column_id[]" value="16">
                                                                <input type="text" style="width: 0px;border: none;" name="cuisinesaaa">
                                                            </div>


                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* How Often do you eat out? </h4>
                                                                <select required="" class="form-control"  name="18_value">
                                                                    <option value="">Please select..</option>
                                                                    <option value="Less than Once a week">Less than Once a week</option>
                                                                    <option value="Once a week">Once a week</option>
                                                                    <option value="2-4 Times a week">2-4 Times a week</option>
                                                                    <option value="More than 4 times a week">More than 4 times a week</option>
                                                                </select>
                                                                <input type="hidden" name="custom_column_id[]" value="18">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <h4 >* How Often do you cook yourself ? </h4>
                                                                <select required="" class="form-control"  name="19_value">
                                                                    <option value="">Please select..</option>
                                                                    <option value="Never">Never</option>
                                                                    <option value="Once a week">Once a week</option>
                                                                    <option value="More than once a week">More than once a week</option>
                                                                </select>
                                                                <input type="hidden" name="custom_column_id[]" value="19">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <h4 >* Tick all that you eat </h4>

                                                                <label><input type="checkbox" name="17_value[]" value="Veg Only" class="form-control eat">Veg Only</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="17_value[]" value="Seafood" class="form-control eat">Seafood</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="17_value[]" value="Chicken" class="form-control eat">Chicken</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="17_value[]" value="Lamb/Goat" class="form-control eat">Lamb/Goat</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="17_value[]" value="Beef" class="form-control eat">Beef</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="17_value[]" value="Pork" class="form-control eat">Pork</label>&nbsp;&nbsp;
                                                                <label><input type="checkbox" name="17_value[]" value="I eat everything that can be eaten" class="form-control eat">I eat everything that can be eaten</label>&nbsp;&nbsp;
                                                                <input type="hidden" name="custom_column_id[]" value="17">
                                                                <input type="text" style="width: 0px;border: none;" name="eatt">
                                                            </div>


                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <h4 >Name any 2 of your favorite eating places in Pune </h4>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="20_value[]" placeholder="Place 1" class="form-control">
                                                                    <span class="help-block"> </span>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="20_value[]" placeholder="Place 2" class="form-control">
                                                                    <span class="help-block"> </span>
                                                                </div>
                                                                <input type="hidden" name="custom_column_id[]" value="20">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tab3">
                                        <h3 class="block">Provide your billing details</h3>

                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">Name <span class="required">
                                                                            * </span></label>
                                                                    <div class="col-md-8">
                                                                        <input type="text" required name="name" id="name" {$validate.name} class="form-control" >
                                                                        <span class="help-block"> </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">Email <span class="required">
                                                                            * </span></label>
                                                                    <div class="col-md-8">
                                                                        <input type="text" required="" id="email"  name="email" {$validate.email} class="form-control" >
                                                                        <span class="help-block"> </span>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">Mobile <span class="required">
                                                                            * </span> </label>

                                                                    <div class="col-md-8">
                                                                        <input type="text" required name="mobile" id="mobile"  {$validate.mobile} class="form-control" >
                                                                        <span class="help-block"> </span>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                        <div class="col-md-5">

                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">Address <br><span style="font-size: x-small;">(min 25 char)</span> <span class="required">
                                                                            * </span> </label>
                                                                    <div class="col-md-8">
                                                                        <textarea type="text" required name="address" id="address" {$validate.address} class="form-control" ></textarea>
                                                                        <span class="help-block"> </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">City <span class="required">
                                                                            * </span> </label>
                                                                    <div class="col-md-8">
                                                                        <input type="text" required name="city" id="city" {$validate.name} class="form-control" value="">
                                                                        <span class="help-block"> </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">Zipcode <span class="required">
                                                                            * </span> </label>
                                                                    <div class="col-md-8">
                                                                        <input type="digit" required name="zipcode" id="pincode" {$validate.zipcode} class="form-control" value="">
                                                                        <span class="help-block"> </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">State <span class="required">
                                                                            * </span></label>
                                                                    <div class="col-md-8">
                                                                        <input type="text" required name="state" id="state" {$validate.name} class="form-control" value="">
                                                                        <span class="help-block"> </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    {if $info.event_type!=2}

                                                        <div class="row">
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-10">
                                                                {if $info.capture_details==1}
                                                                    <span style="font-size: initial;font-weight: 100;"> Enter Attendee Information</span>
                                                                    <span style="font-weight: 100;"> (Optional)</span>
                                                                {else}
                                                                    <span style="font-size: initial;font-weight: 100;"> Package Information</span>
                                                                {/if}

                                                                <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                                                    <tr style="font-weight: bold;">
                                                                        <th style="width: 10%;">#</th>
                                                                        <th style="width: 50%;">Details</th>
                                                                        <th style="width: 10%;">Units</th>
                                                                        <th style="width: 15%;">Price</th>
                                                                        <th style="width: 15%;">Total</th>
                                                                    </tr>
                                                                </table>
                                                                <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                                                    <tr >
                                                                        <td style="width: 10%;">1</td>
                                                                        <td style="width: 50%;">{$package.0.package_name}</td>
                                                                        <td style="width: 10%;">{$package.0.seat}</td>
                                                                        <td style="width: 15%;">{$package.0.price}</td>
                                                                        <td style="width: 15%;">{{$package.0.price*$package.0.seat}|string_format:"%.2f"}</td>
                                                                    </tr>
                                                                </table>
                                                                <table class="table table-condensed table-hover" style="margin-bottom: 0px;width: 40%;">
                                                                    {$package_name=$package.0.package_name}
                                                                    {assign var=id value=1}
                                                                    {foreach from=$package item=v}
                                                                        {if $v.seat>0}
                                                                            {if $v.package_name!=$package_name}
                                                                                {$package_name=$v.package_name}
                                                                                {$id=$id+1}
                                                                            </table>
                                                                            {if $info.capture_details==1}
                                                                                <br>
                                                                                <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                                                                    <tr style="font-weight: bold;">
                                                                                        <th style="width: 10%;">#</th>
                                                                                        <th style="width: 50%;">Details</th>
                                                                                        <th style="width: 10%;">Units</th>
                                                                                        <th style="width: 15%;">Price</th>
                                                                                        <th style="width: 15%;">Total</th>
                                                                                    </tr>
                                                                                </table>
                                                                            {/if}
                                                                            <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                                                                <tr>
                                                                                    <td style="width: 10%;">{$id}</td>
                                                                                    <td style="width: 50%;">{$v.package_name}</td>
                                                                                    <td style="width: 10%;">{$v.seat}</td>
                                                                                    <td style="width: 15%;">{$v.price}</td>
                                                                                    <td style="width: 15%;">{{$v.price*$v.seat}|string_format:"%.2f"}</td>
                                                                                </tr>
                                                                            </table>
                                                                            <table class="table table-condensed table-hover" style="margin-bottom: 0px;width: 40%;">
                                                                            {/if}
                                                                            {for $foo=1 to {$v.seat}}
                                                                                <input name="package_id[]"   type="hidden" value="{$v.package_id}" />
                                                                                <input name="price[]"   type="hidden" value="{$v.price}" />
                                                                                {if $info.capture_details==1}
                                                                                    <table class="table table-condensed table-bordered " style="margin-bottom: 0px;">
                                                                                        <tr>
                                                                                            <td style="width: 10%;">{$foo}</td>
                                                                                            <td style="width: 50%;"><input name="attendeename[]"  class="form-control input-sm" maxlength="100" type="text" placeholder="Attendee name"  /></td>
                                                                                                {if $info.mobile_capture==1}
                                                                                                <td style="width: 30%;">
                                                                                                    <input name="attendeemobile[]" {$validate.mobile} class="form-control input-sm" title="Enter your valid mobile number" maxlength="12" pattern="^(\+[\d]{ldelim}1,5{rdelim}|0)?[7-9]\d{ldelim}9{rdelim}$"   type="text" placeholder="Mobile"  />
                                                                                                </td> 
                                                                                            {/if}
                                                                                            {if $info.age_capture==1}
                                                                                                <td style="width: 20%;">
                                                                                                    <input name="attendeeage[]" class="form-control input-sm" max="100" min="0" step="1"  type="number" placeholder="Age" />
                                                                                                </td>
                                                                                            {/if}
                                                                                        </tr>
                                                                                    </table>
                                                                                {/if}
                                                                            {/for}
                                                                        </table>
                                                                        {if $v.is_flexible==1}       
                                                                            <div class="row">
                                                                                <br>
                                                                                <div class="col-md-8">
                                                                                    <label class="font-red">* Minimum : <i class="fa fa-inr fa-large"></i> {$v.min_price}  & Maximum : <i class="fa fa-inr fa-large"></i> {$v.max_price} </label>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <input name="flexible_amount[]" type="number" step="0.01" min="{$v.min_price}" max="{$v.max_price}" placeholder="Enter amount" required class="form-control" value="" />
                                                                                    <br>
                                                                                </div>

                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                {/foreach}
                                                            </div>
                                                        </div>
                                                    {/if}
                                                    <input name="amount" readonly type="hidden"  value="{$absolute_cost}" />
                                                    <input name="increpted" readonly type="hidden"  value="{$absolute_cost_incr}" />

                                                    {if isset($radio)}
                                                        <div class="form-group form-md-radios">
                                                            <h4>* Select payment mode {if $surcharge_amount>0} <span style="font-size: 13px;font-weight: 500;">(Convenience fee of <i class="fa  fa-inr"></i> {$surcharge_amount|string_format:"%.2f"}/- applicable for credit card & debit card payments)</span>{/if}</h4>
                                                            <div id="form_gender_error"></div>
                                                            <div class="md-radio-inline">
                                                                {$int=6}
                                                                {foreach from=$radio item=v}
                                                                    <div class="md-radio">
                                                                        <input type="radio"  required id="radio{$int}" name="payment_mode" onchange="getgrandtotal('{$absolute_cost_incr}', '{$v.fee_id}');" value="{$v.fee_id}"  class="md-radiobtn">
                                                                        <label for="radio{$int}">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span>
                                                                            {if $v.name=='PAYTM'}
                                                                                <img src="/assets/admin/layout/img/paytm.png">
                                                                            {else}
                                                                                {$v.name}
                                                                            {/if}    
                                                                        </label>
                                                                    </div>
                                                                    {$int=$int+1}
                                                                {/foreach}
                                                            </div>
                                                        </div>
                                                    {/if}
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="tab-pane" id="tab4">
                                        <div class="row">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <p>
                                                                The Privilege Card entitles you to exclusive benefits at select places. These benefits may change time to time depending on the restaurants. All these benefits are published on facebook group and uploaded on our website as well. The card does not guarantee any privileges other than published time to time on PEO and website. </p>

                                                            <p>
                                                                <label>
                                                                    <input type="checkbox" required="" name="21_value" value="Yes" class="form-control">Checkbox (I Agree Option Only) </label>
                                                                <input type="hidden" name="custom_column_id[]" value="21">
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <p>
                                                                Please note: We do not store any of your card/ account information when you make a payment. For online payment, we may redirect you to a secure banking/payment gateway website to provide your card/account credentials.</p>
                                                            <p>
                                                                <label><input type="checkbox" required></span> I accept the <a class="iframe" href="{$server_name}/terms-popup{$terms_id}">Terms and conditions</a> & <a class="iframe" href="{$server_name}/privacy-popup{$policy_id}">Privacy policy</a> </label>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-9 col-md-3">
                                        <input name="payment_req" type="hidden" size="60" value="{$payment_request_id}" />
                                        <input name="seat" type="hidden"  value="{$seat}" />
                                        <input name="occurence_id" type="hidden"  value="{$occurence_id}" />
                                        <input name="coupon_id" type="hidden"  value="{$coupon_id}" />
                                        <input name="tax" type="hidden"  value="{$tax_amount}" />
                                        <input name="discount" type="hidden"  value="{$discount_amount}" />
                                        <a href="javascript:;" class="btn default button-previous">
                                            <i class="m-icon-swapleft"></i> Back </a>
                                        <a href="javascript:;" class="btn blue button-next">
                                            Continue <i class="m-icon-swapright m-icon-white"></i>
                                        </a>
                                        <a onclick="return validate();" href="javascript:;" class="btn blue button-submit">
                                            Submit <i class="m-icon-swapright m-icon-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    function copy_name(val)
    {
            firstName = document.getElementById('f_name').value;
            lastName = document.getElementById('l_name').value;
            lastName = document.getElementById('name').value = firstName + ' ' + lastName;

        }
        function copyfield(val, id)
    {
            document.getElementById(id).value = val;
        }
        function changedate(val)
    {
            var day = document.getElementById(val + '_day').value;
            var month = document.getElementById(val + '_month').value;
            document.getElementById(val + '_date').value = day + '-' + month;
        }
</script>