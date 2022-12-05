<div class="form-group">
    <label class="control-label">Search
        @if($product_type=='Goods' && !empty($product_type))
        HSN code for your item
        @else
        SAC for your service
        @endif
    </label>
    <input name="hsn_sac_code" type="text" class="form-control" placeholder="Search code or description" wire:model="searchTerm" wire:keydown.escape="resetfrm" wire:keydown.tab="resetfrm" data-cy="hsn_sac_code" autocomplete="off" id="hsn_sac_code" />
    <input type="gst" wire:model="gst" id="gst_type" hidden>
    {{-- <div wire:loading class="absolute z-10 list-group bg-white w-full rounded-t-none shadow-lg">
        <div class="list-item">Searching...</div>
    </div> --}}
    @if(!empty($searchTerm) && !($resultFound))
    <div class="mt-2">
        @if(!empty($codes))
        <table class="table table-striped table-bordered table-hover" id="table-no-export" style="table-layout: fixed">
            <thead>
                <tr>
                    <th class="td-c" style="width:100px;">
                        Code
                    </th>
                    <th class="td-c">
                        Description
                    </th>
                    <th class="td-c" style="width: 70px;">
                        GST%
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($codes as $i => $code)
                <tr>
                    <td class="td-c"><a attrValue="{{$code['code']}}" attrGST="{{$code['gst']}}" class="sac-list-item">{{ $code['code'] }}</td>
                    <td class="">
                        <p attrValue="{{$code['code']}}" attrGST="{{$code['gst']}}" class="sac-list-item">
                            {!! $highlightWords($code['description'], $searchTerm) !!};
                        </p>
                    </td>
                    <td class="td-c">
                        <p attrValue="{{$code['code']}}" attrGST="{{$code['gst']}}" class="sac-list-item">{{$code['gst']}}</p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center">
            <h5>
                @if($product_type=='Goods' && !empty($product_type))
                Could not find the HSN code for the item. Please provide proper code or description.
                @else
                Could not find the SAC for the service. Please provide proper code or description.
                @endif
            </h5>
        </div>
        @endif
    </div>
    @endif
</div>

<script>
    document.addEventListener("livewire:load", function(event) {
        Livewire.hook('message.processed', (message, component) => {
            $('.sac-list-item').on('click', function(e) {
                let elementName = $(this).attr('attrValue');
                let gstPercent = $(this).attr('attrGST');
                livewire.emit('addTodo', elementName, gstPercent);
                document.getElementById("sac_code").value = elementName;
                document.getElementById("gst_applicable").value = gstPercent;
                livewire.emit('resetfrm');
                //$("#find_hsn_sac_code").trigger("reset");
                $("#close_hsn_sac_lookup_modal").click();
            });
            $("#close_hsn_sac_lookup_modal").on('click', function(e) {
                livewire.emit('resetfrm');
            });
        })
    });
</script>