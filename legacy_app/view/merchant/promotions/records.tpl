
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-12">
            <!-- BEGIN BULK UPLOAD REQUEST TABLE -->
            <br>
            <div class="portlet ">

                <div class="portlet-body" style="max-height: 500px;">

                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    #
                                </th>
                                <th class="td-c">
                                {$customer_default_column.customer_code|default:'Customer code'}                                </th>
                                <th class="td-c">
                                {$customer_default_column.customer_name|default:'Customer name'}
                                </th>
                                <th class="td-c">
                                    Mobile
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            {$int=1}
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {$int}
                                    </td>
                                    <td  class="td-c">
                                        {$v.customer_code}
                                    </td>
                                    <td  class="td-c">
                                        {$v.customer_name}
                                    </td>
                                    <td  class="td-c">
                                        {$v.mobile}
                                    </td>
                                </tr>
                                {$int=$int+1}
                            {/foreach}
                        </tbody>
                    </table>


                </div>
            </div>
            <!-- END BULK UPLOAD REQUEST TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>

<script>
    setTimeout(function() {
        location.reload();
    }, 180000);
</script>