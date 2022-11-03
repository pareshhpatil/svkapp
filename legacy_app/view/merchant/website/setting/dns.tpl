<div class="set-profile-wrapp">
    <form id="vform" action="/merchant/website/success" method="post">
        <ul class="steps" style="width: 700px;">
            <li class="left" style="padding-right: 20px; width: 150px;">
                <div class="no">1</div><span>Set<br>Domain</span>
            </li>
            <li class="" style="padding-right: 50px; width: 150px;">
                <div class="no">2</div><span>Verify<br>Domain</span>
            </li>
            <li class="active" style="width: 150px;">
                <div class="no ">3</div><span>Verify<br>DNS</span>
            </li>
            <li class="right" style="width: 150px;">
                <div class="no">4</div><span>Done</span>
            </li>
        </ul>

        <ul class="form-step1" style="max-width: 1000px;">
            <!--<h6>Set your Domain CNAME in domain setting:  {$domain_cname}</h6>-->
            <h6>Change your domain nameservers: </h6>
            <table class="table table-bordered">

                <tr>
                    <td>kami.ns.cloudflare.com
                    </td>
                </tr>
                <tr>
                    <td>todd.ns.cloudflare.com
                    </td>
                </tr>
            </table>


            <li>
                <h6 style="color: brown;" id="status_"></h6>
            </li>
            <li><button type="submit" onclick="return verifyDNS();" id="vbtn" class="button btnNext">Verify</button>
                <span id="timer"></span>
            </li>
        </ul>
    </form>
</div>