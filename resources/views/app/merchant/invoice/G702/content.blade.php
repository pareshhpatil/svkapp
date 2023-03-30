<div class="w-full bg-white  shadow-2xl font-rubik m-2 p-10 watermark" style="max-width: 1400px; color:#394242;">
    @if($has_watermark)
    <div class="watermark__inner">
        <div class="watermark__body">{{$watermark_text}}</div>
    </div>
    @endif
    <div class="flex flex-row  gap-4">
        @if($has_aia_license)
        <div>
            <img src="{{ asset('images/logo-703.PNG') }}" />
        </div>
        <div>
            <h1 class="text-3xl text-left mt-8 font-bold  text-black">Document G702® – 1992</h1>
        </div>
        @else
        <div>
            <h1 class="text-3xl text-left font-bold  text-black">Document G702 – 1992</h1>
        </div>
        @endif
    </div>
    <h1 class="text-2xl text-left mt-4 font-bold">Application and Certificate for Payment </h1>
    <div class="w-full h-0.5 bg-gray-900 mb-1 mt-1"></div>
    <div>
        <table width="100%">
            <tbody>
                <tr>
                    <td width="25%">
                        <p class="text-xs font-bold">TO OWNER: </p>
                    </td>
                    <td width="25%">
                        <p class="text-xs font-bold">PROJECT: </p>
                    </td>
                    <td width="25%" class="text-left">
                        <p class="text-xs font-bold">APPLICATION NO: {{$invoice_number}}</p>
                    </td>
                    <td width="25%" class="text-right">
                        <p class="text-xs font-bold">Distribution to:</p>
                    </td>
                </tr>
                <tr>
                    <td width="25%" rowspan="2" style="vertical-align: baseline;">
                        <p class="text-xs  mt-1">{{$customer_name}}</p>
                        <span class="text-xs">{{$project_details->owner_address}}</span>
                    </td>
                    <td width="25%" rowspan="2" style="vertical-align: baseline;">
                        <p class="text-xs">{{$project_details->project_name}} </p>
                        <span class="text-xs">{{$project_details->project_address}}</span>
                    </td>
                    <td width="25%" class="text-left">
                        <p class="text-xs font-bold">PERIOD TO: {{$cycle_name}} Bill</p>
                    </td>
                    <td width="25%" class="text-right">
                        <p style="display:inline-flex"><label class="text-xs mr-2 mt-1">OWNER</label> <input class="" type="checkbox" value=""></p>
                    </td>
                </tr>
                <tr>
                    <td width="25%" class="text-left">
                        <p class="text-xs font-bold">CONTRACT FOR:</p>
                    </td>
                    <td width="25%" class="text-right">
                        <p style="display:inline-flex"><label class="text-xs mr-2 mt-1">ARCHITECT</label> <input class="" type="checkbox" value=""></p>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <p class="text-xs font-bold ">FROM CONTRACTOR: </p>
                    </td>
                    <td width="25%">
                        <p class="text-xs font-bold">VIA ARCHITECT:</p>
                    </td>
                    <td width="25%" class="text-left">
                        <p class="text-xs font-bold">CONTRACT DATE: <x-localize :date="$project_details->contract_date" type="date" /> </p>
                    </td>
                    <td width="25%" class="text-right">
                        <p style="display:inline-flex"><label class="text-xs mr-2 mt-1">CONTRACTOR</label> <input class="" type="checkbox" value=""></p>
                    </td>
                </tr>
                <tr>
                    <td width="25%" rowspan="2" style="vertical-align: baseline;">
                        <p class="text-xs">{{$company_name}}</p>
                        <span class="text-xs">{{$project_details->contractor_address}}</span>
                    </td>
                    <td width="25%" rowspan="2" style="vertical-align: baseline;">
                        <span class="text-xs">{{$project_details->architect_address}}</span>
                    </td>
                    <td width="25%" class="text-left">
                        <p class="text-xs font-bold">PROJECT NOS: {{$project_details->project_code}}</p>
                    </td>
                    <td width="25%" class="text-right">
                        <p style="display:inline-flex"><label class="text-xs mr-2 mt-1">FIELD</label> <input class="" type="checkbox" value=""></p>
                    </td>
                </tr>
                <tr>
                    <td width="25%" class="text-left">
                        <p class="text-xs font-bold"></p>
                    </td>
                    <td width="25%" class="text-right">
                        <p style="display:inline-flex"><label class="text-xs mr-2 mt-1">OTHER</label> <input class="" type="checkbox" value=""></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="w-full h-0.5 bg-gray-900 mt-2 lg:mt-0 md:mt-0"></div>
    <div class="grid grid-cols-2 gap-2 mt-1">
        <div>
            <h4 class="font-bold">CONTRACTOR’S APPLICATION FOR PAYMENT</h4>
            @if($has_aia_license)
            <p class="text-xs">Application is made for payment, as shown below, in connection with the Contract.
                AIA Document G703®, Continuation Sheet, is attached.</p>
            @else
            <p class="text-xs">Application is made for payment, as shown below, in connection with the Contract.
                Document G703, Continuation Sheet, is attached.</p>
            @endif
            <div class="grid grid-cols-3 gap-2 mt-1">
                <div class="col-span-2">
                    <p class="font-bold text-xs mt-1">1. ORIGINAL CONTRACT SUM </p>
                    <p class="font-bold text-xs mt-1">2. NET CHANGE BY CHANGE ORDERS </p>
                    <p class="font-bold text-xs mt-1">3. CONTRACT SUM TO DATE <span class="font-light italic">(Line 1 ± 2)</span> </p>
                    <p class="font-bold text-xs mt-1">4. TOTAL COMPLETED &amp; STORED TO DATE<span class="font-light italic "> (Column G on G703)</span> </p>
                    <p class="font-bold text-xs mt-1">5. RETAINAGE: </p>
                    <p class="font-bold text-xs mt-1">a. <span class="font-light border-b border-gray-600">{{$work_complete_perc}}</span><span class="font-light"> % of Completed Work <span class="italic ">(Columns D + E on G703)</span></span> </p>
                    <p class="font-bold text-xs mt-1">b. <span class="font-light border-b border-gray-600"> {{$stored_material_perc}}</span><span class="font-light"> % of Stored Material <span class="italic ">(Column F on G703)</span></span> </p>
                    <p class="font-light text-xs mt-2">Total Retainage <span class="italic ">(Lines 5a + 5b, or Total in Column I of G703)</span> </p>
                    <p class="font-bold text-xs mt-1">6. TOTAL EARNED LESS RETAINAGE </p>
                    <p class="text-light ml-4 text-xs italic">(Line 4 minus Line 5 Total)</p>
                    <p class="font-bold text-xs mt-1">7. LESS PREVIOUS CERTIFICATES FOR PAYMENT </p>
                    <p class="text-light ml-4 text-xs italic">(Line 6 from prior Certificate)</p>
                    <p class="font-bold text-xs mt-5"></p>
                    <p class="font-bold text-xs mt-1">8. CURRENT PAYMENT DUE </p>
                    <p class="font-bold text-xs mt-1">9. BALANCE TO FINISH, INCLUDING RETAINAGE </p>
                    <p class="text-light ml-4 text-xs italic">(Line 3 minus Line 6)</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-xs border-b border-gray-600 mt-1"> {{$original_contract_amount}}</p>
                    <p class="font-bold text-xs border-b border-gray-600 mt-1"> {{$total_co}}</p>
                    <p class="font-bold text-xs border-b border-gray-600 mt-1"> {{$contract_sum_to_date}}</p>
                    <p class="font-bold text-xs border-b border-gray-600 mt-0.5"> {{$total_complete_stored}}</p>
                    <p class="font-bold text-xs mt-5"></p>
                    <p class="font-bold text-xs border-b border-gray-600 mt-2"> {{$total_retainage_amount}}</p>
                    <p class="font-bold text-xs border-b border-gray-600 mt-1"> {{$total_stored_materials}}</p>
                    <p class="font-bold text-xs border-b border-gray-600 mt-2"> {{$total_retainage}}</p>
                    <p class="font-bold text-xs border-b border-gray-600 mt-1"> {{$total_earned_less_retain}}</p>
                    <p class="font-bold text-xs mt-5"></p>
                    <p class="font-bold text-xs border-b border-gray-600 mt-1"> {{$total_previously_billed_amount}}</p>
                    <p class="font-bold text-xs mt-5"></p>
                    <p class="font-bold text-xs border   border-gray-600 mt-0 py-1"> {{$grand_total}}</p>
                    <p class="font-bold text-xs border-b border-gray-600 mt-4"> {{$balance_to_finish}} </p>
                </div>
            </div>
            <table class="mt-2 w-full border-collapse border border-gray-500 overflow-hidden">
                <tbody>
                    <tr>
                        <td class="border-collapse border border-gray-500 py-1 px-1">
                            <p class="text-xs">CHANGE ORDER SUMMARY </p>
                        </td>
                        <td class="border-collapse border border-gray-500 py-1 px-1">
                            <p class="text-xs">ADDITIONS </p>
                        </td>
                        <td class="border-collapse border border-gray-500 py-1 px-1">
                            <p class="text-xs">DEDUCTIONS </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-collapse border-r border-gray-500 py-1 px-1">
                            <p class="text-xs">Total changes approved in previous months by Owner </p>
                        </td>
                        <td class="border-collapse border-r border-gray-500 py-1 px-1">
                            <p class="text-xs text-right">{{$last_month_co_amount_positive}} </p>
                        </td>
                        <td class="py-1 px-1">
                            <p class="text-xs text-right">{{$last_month_co_amount_negative}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-collapse border border-gray-500 py-1 px-1">
                            <p class="text-xs">Total approved this month </p>
                        </td>
                        <td class="border-collapse border border-gray-500 py-1 px-1">
                            <p class="text-xs text-right">{{$this_month_co_amount_positive}}</p>
                        </td>
                        <td class="border-collapse border border-gray-500 py-1 px-1">
                            <p class="text-xs text-right">{{$this_month_co_amount_negative}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right border-collapse border-r border-gray-500 py-1 px-1">
                            <p class="text-xs">TOTAL</p>
                        </td>
                        <td class="border-collapse border-r border-gray-500 py-1 px-1">
                            <p class="text-xs text-right">{{$total_co_amount_positive}} </p>
                        </td>
                        <td class="py-1 px-1">
                            <p class="text-xs text-right">{{$total_co_amount_negative}} </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-collapse border border-gray-500 py-1 px-1">
                            <p class="text-xs">NET CHANGES by Change Order</p>
                        </td>
                        <td colspan="2" class=" border-collapse border border-gray-500 py-1 px-1">
                            <p class="text-xs text-right">{{$total_co}}</p>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <p class="text-xs">The undersigned Contractor certifies that to the best of the Contractor’s knowledge, information
                and belief the Work covered by this Application for Payment has been completed in accordance
                with the Contract Documents, that all amounts have been paid by the Contractor for Work for
                which previous Certificates for Payment were issued and payments received from the Owner, and
                that current payment shown herein is now due.
            </p>
            <p class="font-bold text-sm mt-1">CONTRACTOR:</p>
            <div class="grid grid-cols-3 gap-2 mt-1">
            </div>
            <p class="text-xs mt-1">State of: </p>
            <p class="text-xs mt-2">County of:</p>
            <p class="text-xs mt-2">Subscribed and sworn to before me this <span class="ml-8"> day of</span></p>
            <p class="text-xs mt-2">Notary Public:</p>
            <p class="text-xs mt-1">My commission expires:</p>
            <div class="w-full h-0.5 bg-gray-900 mt-2 "></div>
            <h4 class="font-bold mt-1">ARCHITECT’S CERTIFICATE FOR PAYMENT</h4>
            <p class="text-xs">In accordance with the Contract Documents, based on on-site observations and the data comprising this application, the Architect certifies to the Owner that to the best of the Architect’s knowledge,
                information and belief the Work has progressed as indicated, the quality of the Work is in
                accordance with the Contract Documents, and the Contractor is entitled to payment of the
                AMOUNT CERTIFIED.
            </p>
            <div class="grid grid-cols-3 gap-2 mt-1">
                <div class="col-span-2">
                    <p class="font-bold text-xs mt-1">AMOUNT CERTIFIED</p>
                </div>
                <div>
                    <p class="font-bold text-xs border-b border-gray-600 mt-1 text-right">{{$grand_total}} </p>
                </div>
            </div>
            <p class="text-xs font-light mt-1 italic">(Attach explanation if amount certified differs from the amount applied. Initial all figures on this
                Application and on the Continuation Sheet that are changed to conform with the amount certified.)
            </p>
            <br>
            <p class="text-xs mt-1">This Certificate is not negotiable. The AMOUNT CERTIFIED is payable only to the Contractor
                named herein. Issuance, payment and acceptance of payment are without prejudice to any rights of
                the Owner or Contractor under this Contract.
            </p>
        </div>
    </div>
    <div class="w-full h-0.5 bg-gray-900 mt-2 "></div>
    <div class="mt-2">
        @if($has_aia_license)
        <p class="leading-3"><span class="text-xs"><b>AIA Document G702® – 1992. Copyright</b> © 1953, 1963, 1965, 1971, 1978, 1983 and 1992 by The American Institute of Architects. All rights reserved.</span><span class="text-xs text-red-500"> The “American Institute of Architects,” “AIA,” the AIA Logo, “G702,” and
                “AIA Contract Documents” are registered trademarks and may not be used without permission.</span><span class="text-xs"> To report copyright violations of AIA Contract Documents, e-mail copyright@aia.org.</span></p>
        @endif
    </div>
</div>