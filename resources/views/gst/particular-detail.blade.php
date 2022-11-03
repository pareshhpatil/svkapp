<div class="row">
  <div class="col-md-12">
    <div class="portlet ">
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="users-table2">
          <thead>
            <tr>
              <th class="td-c" colspan="6">
                MY DATA
              </th>
              <th class="td-c" colspan="6">
                GST DATA
              </th>
            </tr>
            <tr>
              <th class="td-c">
                NO
              </th>
              <th class="td-c">
                TAXABLE Value
              </th>
              <th class="td-c">
                TAX RATE
              </th>
              <th class="td-c">
                CGST
              </th>
              <th class="td-c">
                SGST
              </th>
              <th class="td-c">
                IGST
              </th>
              <th class="td-c">
                NO
              </th>
              <th class="td-c">
                TAXABLE Value
              </th>
              <th class="td-c">
                TAX RATE
              </th>
              <th class="td-c">
                CGST
              </th>
              <th class="td-c">
                SGST
              </th>
              <th class="td-c">
                IGST
              </th>
            </tr>
          </thead>
          <tbody>
            @if($row_count > 0)
            @foreach($list as $v)
            <tr>
              <td class="td-c">
                {{$v->purch_item_number}}
              </td>
              <td class="td-c">
                {{$v->purch_item_taxable_value}}
              </td>
              <td class="td-c">
                {{$v->purch_item_tax_rate}}
              </td>
              <td class="td-c">
                {{$v->purch_item_cgst}}
              </td>
              <td class="td-c">
                {{$v->purch_item_sgst}}
              </td>
              <td class="td-c">
                {{$v->purch_item_igst}}
              </td>
              <td class="td-c">
                {{$v->gst_item_number}}
              </td>
              <td class="td-c">
                {{$v->gst_item_taxable_value}}
              </td>
              <td class="td-c">
                {{$v->gst_item_tax_rate}}
              </td>
              <td class="td-c">
                {{$v->gst_item_cgst}}
              </td>
              <td class="td-c">
                {{$v->gst_item_sgst}}
              </td>
              <td class="td-c">
                {{$v->gst_item_igst}}
              </td>
            </tr>
            @endforeach
            @else
            <span>Sorry no Data available</span>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  // $(function() {
  //   $('#users-table2').DataTable({
  //     processing: true,
  //     serverSide: true,
  //     ajax: '/merchant/gst/reconciliation/ParticulardetailData/{{$id}}',
  //     columns: [{
  //         data: 'action',
  //         name: 'action',
  //         orderable: false,
  //         searchable: false
  //       },
  //       {
  //         data: 'purch_item_number'
  //       },
  //       {
  //         data: 'purch_item_taxable_value'
  //       },
  //       {
  //         data: 'purch_item_tax_rate'
  //       },
  //       {
  //         data: 'purch_item_cgst'
  //       },
  //       {
  //         data: 'purch_item_sgst'
  //       },
  //       {
  //         data: 'purch_item_igst'
  //       },
  //       {
  //         data: 'gst_item_number'
  //       },
  //       {
  //         data: 'gst_item_taxable_value'
  //       },
  //       {
  //         data: 'gst_item_tax_rate'
  //       },
  //       {
  //         data: 'gst_item_cgst'
  //       },
  //       {
  //         data: 'gst_item_sgst'
  //       },
  //       {
  //         data: 'gst_item_igst'
  //       }
  //     ],
  //     createdRow: function(row, data, index) {
  //       if (data['diff'] < 0 || data['diff'] > 0) {
  //         $('td', row).eq(7).addClass('bg-lightyellow');
  //       } else {
  //         $('td', row).eq(7).addClass('bg-othercolumn');
  //       }
  //     }
  //   });

  // });
</script>