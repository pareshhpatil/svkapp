<section class="pageTitleSection">
    <div class="container">
        <h1>Set Top Box List</h1>
    </div>
</section>
<!--bannerSection-->
<section class="ptb80">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-xs-12 col-lg-10 col-sm-offset-1 whiteBox" style="overflow: auto;">
                <div class="setupboxTable" style="min-width: 600px;">
                    <table class="table table-primary" style="border: 1px solid lightgrey;">
                        <tr style="background-color: lightgrey;padding: 10px 10px 10px 10px;">
                            <th><div class="setup80" style="width: auto;">Set Top Box Number</div></th>
                            <th><span class="setup80" style="width: auto;">Package Cost</span></th>
                            <th><span class="setup80" style="width: auto;">Validity</span></th>
                            <th><span class="setup80" style="text-align: center;"></span></th>
                        </tr>
                        {foreach from=$settopbox item=v}
                            <tr style="border: 1px solid lightgrey;">
                                <td><div class="setup80" style="width: auto;">{$v.name}</div></td>
                                <td><span class="setup80" style="width: auto;"><i class="fa fa-rupee-sign"></i> {$v.cost}</span></td>
                                <td><span class="setup80" style="width: auto;">{$v.narrative}</span></td>
                                <td style="text-align: center;"><div><a href="/cable/customerpackages/{$v.link}" style="width: auto;" class="btnBlue hvr-bounce-to-right">View</a></div></td>
                            </tr>
                        {/foreach}
                    </table>
                </div>

            </div>
        </div>
                    <hr/>
            <p style="width: 100%;"> <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
    </div>
</section>