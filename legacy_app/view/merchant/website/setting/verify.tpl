<div class="set-profile-wrapp">
    <form action="/merchant/website/dns" id="vform" method="post">
        <ul class="steps" style="width: 700px;">
            <li class="left" style="padding-right: 20px; width: 150px;">
                <div class="no">1</div><span>Set<br>Domain</span>
            </li>
            <li class="active" style="padding-right: 50px; width: 150px;">
                <div class="no">2</div><span>Verify<br>Domain</span>
            </li>
            <li style="width: 150px;">
                <div class="no ">3</div><span>Verify<br>DNS</span>
            </li>
            <li class="right" style="width: 150px;">
                <div class="no">4</div><span>Done</span>
            </li>
        </ul>

        <ul class="form-step1" style="max-width: 1000px;">
            <h6>Set your TXT NAME in domain setting: {$txt_text}</h6>


            <li>
                <h6 style="color: brown;" id="status_"></h6>
            </li>
            <li><button onclick="return verifyTXT();" type="submit" id="vbtn" class="button btnNext">Verify</button>
                <a href="/merchant/website/domain" style="display: none;width:150px;" id="bbtn" class="button"> < Back</a>
                        <span id="timer"></span>
            </li>
        </ul>
    </form>
</div>