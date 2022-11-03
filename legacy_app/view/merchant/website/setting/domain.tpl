<div class="set-profile-wrapp">
    <form action="" method="post">
        <ul class="steps" style="width: 700px;">
            <li class="left active" style="padding-right: 20px; width: 150px;"><div class="no">1</div><span>Set<br>Domain</span></li>
            <li class="" style="padding-right: 50px; width: 150px;"><div class="no">2</div><span>Verify<br>Domain</span></li>
            <li style="width: 150px;"><div class="no ">3</div><span>Verify<br>DNS</span></li>
            <li class="right" style="width: 150px;"><div class="no">4</div><span>Done</span></li>
        </ul>

        <ul class="form-step1">
            <h6>Enter your domain name:</h6> {if $error!=''}<span style="color: red;">{$error}</span>{/if}
            <li><input type="text" pattern="^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{ldelim}2,{rdelim}$" title="Enter valid domain name." value="{$domain}" name="domain" class="text" placeholder="Enter your domain name eg. www.swipez.in" /></li>
            <li><input type="submit" value="Next" class="button btnNext"></li>
        </ul>
    </form>
</div>


