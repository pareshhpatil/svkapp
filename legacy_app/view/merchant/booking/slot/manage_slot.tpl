<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=v}

                            <p class="media-heading">{$v.0} - {$v.1}.</p>
                        {/foreach}
                    </div>

                </div>
            {/if}
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="tabbable-line">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="/merchant/bookings/managecalendar/slot/{$link}">Manage Time Slots</a>
                    </li>
                    <li>
                        <a href="/merchant/bookings/managecalendar/holiday/{$link}">Manage Holidays </a>
                    </li>
                    <li>
                        <a href="/merchant/bookings/managecalendar/notification/{$link}">Manage Notifications </a>
                    </li>
                </ul>

            </div>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">



            <!-- BEGIN PORTLET-->


            <div class="portlet">

                <div class="portlet-body">

                    <form class="form-inline" role="form" action="/merchant/bookings/managecalendar/slot/{$link}"
                        method="post">
                        <div class="form-group">
                            <input class="form-control form-control-inline input-sm date-picker" type="text" required=""
                                value="{$from_date}" name="from_date" autocomplete="off" data-date-format="dd M yyyy"
                                placeholder="From date">
                        </div>

                        <div class="form-group">
                            <input class="form-control form-control-inline input-sm date-picker" type="text" required=""
                                value="{$to_date}" name="to_date" autocomplete="off" data-date-format="dd M yyyy"
                                placeholder="To date">
                        </div>


                        <input type="submit" class="btn btn-sm blue" value="Search">
                        <a href="/merchant/bookings/createslotpackages/slot/{$link}" class="btn blue pull-right"> Add
                            more
                            packages</a>
                        <a href="/merchant/bookings/createslot/slot/{$link}" class="btn blue pull-right"> Add more
                            slots</a>
                    </form>

                </div>
            </div>
            <div class="portlet ">
                <div class="portlet-body">
                    <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    ID
                                </th>

                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    Package Name
                                </th>
                                {* <th class="td-c">
                                    Slot Name
                                </th> *}
                                <th class="td-c">
                                    From Time
                                </th>
                                <th class="td-c">
                                    To Time
                                </th>

                                <th class="td-c">
                                    Price
                                </th>
                                <th class="td-c">
                                    Total Seats
                                </th>
                                <th class="td-c">
                                    Avaialble Seats
                                </th>
                                <th class="td-c">
                                    Status
                                </th>
                                <th class="td-c">

                                </th>

                            </tr>
                        </thead>
                        <tbody style="text-align: center;">

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>

<div class="modal fade bs-modal-lg" id="updatecat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="portlet ">
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-10">
                            <br>
                            <form class="form-horizontal" onsubmit="return slotUpdate();" method="post"
                                id="submit_form">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Package <span
                                                class="required">* </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline input-sm" type="text"
                                                disabled id="package_name" name="package_name">
                                            <input class="form-control form-control-inline input-sm" type="hidden"
                                                id="package_id" name="package_id">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Date <span
                                                class="required">* </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline input-sm date-picker"
                                                type="text" required id="slot_date" name="slot_date" autocomplete="off"
                                                data-date-format="dd M yyyy" placeholder="From date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">From Time</label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <select class="form-control input-sm" id="from_hr" name="from_hr"
                                                    style="width:85px">
                                                    <option value="">Hour</option>
                                                    <option value="01">1</option>
                                                    <option value="02">2</option>
                                                    <option value="03">3</option>
                                                    <option value="04">4</option>
                                                    <option value="05">5</option>
                                                    <option value="06">6</option>
                                                    <option value="07">7</option>
                                                    <option value="08">8</option>
                                                    <option value="09">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                </select> &nbsp;&nbsp;&nbsp;
                                                <select class="form-control input-sm" id="from_min" name="from_min"
                                                    style="width:90px">

                                                    <option value="00">00</option>
                                                    <option value="01">01</option>
                                                    <option value="02">02</option>
                                                    <option value="03">03</option>
                                                    <option value="04">04</option>
                                                    <option value="05">05</option>
                                                    <option value="06">06</option>
                                                    <option value="07">07</option>
                                                    <option value="08">08</option>
                                                    <option value="09">09</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                    <option value="21">21</option>
                                                    <option value="22">22</option>
                                                    <option value="23">23</option>
                                                    <option value="24">24</option>
                                                    <option value="25">25</option>
                                                    <option value="26">26</option>
                                                    <option value="27">27</option>
                                                    <option value="28">28</option>
                                                    <option value="29">29</option>
                                                    <option value="30">30</option>
                                                    <option value="31">31</option>
                                                    <option value="32">32</option>
                                                    <option value="33">33</option>
                                                    <option value="34">34</option>
                                                    <option value="35">35</option>
                                                    <option value="36">36</option>
                                                    <option value="37">37</option>
                                                    <option value="38">38</option>
                                                    <option value="39">39</option>
                                                    <option value="40">40</option>
                                                    <option value="41">41</option>
                                                    <option value="42">42</option>
                                                    <option value="43">43</option>
                                                    <option value="44">44</option>
                                                    <option value="45">45</option>
                                                    <option value="46">46</option>
                                                    <option value="47">47</option>
                                                    <option value="48">48</option>
                                                    <option value="49">49</option>
                                                    <option value="50">50</option>
                                                    <option value="51">51</option>
                                                    <option value="52">52</option>
                                                    <option value="53">53</option>
                                                    <option value="54">54</option>
                                                    <option value="55">55</option>
                                                    <option value="56">56</option>
                                                    <option value="57">57</option>
                                                    <option value="58">58</option>
                                                    <option value="59">59</option>
                                                </select>
                                                <select class="form-control input-sm" id="from_ampm" name="from_ampm"
                                                    style="width:85px">
                                                    <option value="am">am</option>
                                                    <option value="pm">pm</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">To Time</label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <select class="form-control input-sm" id="to_hr" name="to_hr"
                                                    style="width:85px">
                                                    <option value="">Hour</option>
                                                    <option value="01">1</option>
                                                    <option value="02">2</option>
                                                    <option value="03">3</option>
                                                    <option value="04">4</option>
                                                    <option value="05">5</option>
                                                    <option value="06">6</option>
                                                    <option value="07">7</option>
                                                    <option value="08">8</option>
                                                    <option value="09">9</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                </select> &nbsp;&nbsp;&nbsp;
                                                <select class="form-control input-sm" id="to_min" name="to_min"
                                                    style="width:90px">

                                                    <option value="00">00</option>
                                                    <option value="01">01</option>
                                                    <option value="02">02</option>
                                                    <option value="03">03</option>
                                                    <option value="04">04</option>
                                                    <option value="05">05</option>
                                                    <option value="06">06</option>
                                                    <option value="07">07</option>
                                                    <option value="08">08</option>
                                                    <option value="09">09</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                    <option value="13">13</option>
                                                    <option value="14">14</option>
                                                    <option value="15">15</option>
                                                    <option value="16">16</option>
                                                    <option value="17">17</option>
                                                    <option value="18">18</option>
                                                    <option value="19">19</option>
                                                    <option value="20">20</option>
                                                    <option value="21">21</option>
                                                    <option value="22">22</option>
                                                    <option value="23">23</option>
                                                    <option value="24">24</option>
                                                    <option value="25">25</option>
                                                    <option value="26">26</option>
                                                    <option value="27">27</option>
                                                    <option value="28">28</option>
                                                    <option value="29">29</option>
                                                    <option value="30">30</option>
                                                    <option value="31">31</option>
                                                    <option value="32">32</option>
                                                    <option value="33">33</option>
                                                    <option value="34">34</option>
                                                    <option value="35">35</option>
                                                    <option value="36">36</option>
                                                    <option value="37">37</option>
                                                    <option value="38">38</option>
                                                    <option value="39">39</option>
                                                    <option value="40">40</option>
                                                    <option value="41">41</option>
                                                    <option value="42">42</option>
                                                    <option value="43">43</option>
                                                    <option value="44">44</option>
                                                    <option value="45">45</option>
                                                    <option value="46">46</option>
                                                    <option value="47">47</option>
                                                    <option value="48">48</option>
                                                    <option value="49">49</option>
                                                    <option value="50">50</option>
                                                    <option value="51">51</option>
                                                    <option value="52">52</option>
                                                    <option value="53">53</option>
                                                    <option value="54">54</option>
                                                    <option value="55">55</option>
                                                    <option value="56">56</option>
                                                    <option value="57">57</option>
                                                    <option value="58">58</option>
                                                    <option value="59">59</option>
                                                </select>
                                                <select class="form-control input-sm" id="to_ampm" name="to_ampm"
                                                    style="width:85px">
                                                    <option value="am">am</option>
                                                    <option value="pm">pm</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <div class="col-md-5"></div>
                                        <div class="col-md-5">
                                            <h3>Multiple seats</h3>
                                            <input type="checkbox" id="ismulcheck" value="1"
                                                onchange="is_multipleslots(this.checked, 'capture_detail');"
                                                class="icheck">
                                            <input type="hidden" name="is_multiple" id="capture_detail_name">
                                        </div>
                                    </div>
                                    <div class="form-group" style="display: none;" id="capture_detail">
                                        <div class="form-group">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-5">
                                                <h4>Available Seat <span class="required">* </span></h4>
                                                <input id="unitavailable" class="form-control form-control-inline"
                                                    required="" min="0" type="number" name="unitavailable" value="0">
                                                (Keep 0 for unlimited Bookings)
                                                <span class="help-block"></span>
                                            </div>

                                        </div>
                                        <div class="form-group">

                                            <div class="col-md-5"></div>
                                            <div class="col-md-3">
                                                <h4>Min Seat <span class="required">* </span></h4>
                                                <input type="number" min="1"
                                                    onchange="validatemax('min_seat1', 'max_seat1');" name="min_seat"
                                                    id="min_seat1" value="1" required=""
                                                    class="form-control form-control-inline">
                                                <span class="help-block">
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <h4>Max Seat <span class="required">* </span></h4>
                                                <input type="number" min="1" name="max_seat" id="max_seat1" value="1"
                                                    required="" class="form-control form-control-inline">
                                                <span class="help-block">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Name <span
                                                class="required">* </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline" required id="slot_title"
                                                name="slot_title" type="text" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Description <span
                                                class="required">* </span></label>
                                        <div class="col-md-5">
                                            <textarea class="form-control" id="slot_description" name="slot_description"
                                                maxlength="500"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Price <span
                                                class="required">* </span></label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline" required step="0.01" min="0"
                                                id="slot_price" name="slot_price" type="number" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-3">
                                            <input type="hidden" id="slot_id" name="slot_id" />
                                            <input type="hidden" value="{$link}" name="calendar_id" />
                                            <button type="submit" class="btn blue">Update</button>
                                        </div>
                                    </div>
                                </div>


                            </form>
                        </div>


                    </div>


                </div>
            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete slot</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this slot in the future?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>