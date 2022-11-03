<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Swipez | <?php echo $this->title; ?></title>
<Meta name="robots" content="noydir" />
<Meta name="robots" content="noodp" />
<link rel="canonical" href="<?php echo $this->server_name; ?>/<?php echo $this->canonical; ?>"/>
<link rel="icon" type="image/png" href="/images/swipezico.ico" />


<link href="/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/typo.css" media="screen" />

 
<link rel="stylesheet" type="text/css" href="/css/reset.css" media="screen" />
<link rel="stylesheet" type="text/css" href="/css/source.css" media="screen" />
</head>

    
    
    
    
    
    <body style="overflow-x:hidden;">  <?php if($this->env == 'PROD' && $this->usertype != 'merchant'){include_once("inc/gatracking.php");} ?><a name="top"></a><a name="a">                 

        <div class="billgen">
    <div class="top_band">
        <div class="details">
            <div class="left">
                <h1>Help desk</h1>
            </div>

        </div>
    </div> 
            
            
                   <?php
    if ($this->hasError()) {
        ?>  <div class="errormsg-box" style="max-width: 600px;"> <img src="/images/erroor_msg.gif" class="err-img" /> 

            <?php
            echo '<div class="right"> <ul>';
            foreach ($this->_error as $error_name) {
                ?> 
                <?php
                echo '<li><img src="/images/bullet.gif" width="7" height="7" /> ' . $error_name[0] . ' -';
                $int = 1;
                while (isset($error_name[$int])) {
                    echo ' ' . $error_name[$int];
                    $int++;
                }
                echo '</li>';
            }
            ?>
            </ul></div></div>

<?php }
?>
            
            
            <form action="/helpdesk/send" method="post">    
<div class="patronsignupleft">
            <div class="patronsignuptxtarea">
                <div class="vendetlsfld">
                    <div class="ptarea arialemail">To</div>
                    <div class="tfarea"><input name="" type="text" class="textarea" value="support@swipez.in" disabled style="background: #E8E8E8 ;" /></div>
                </div>
             <?php if($this->isloggedin==FALSE){?>
                <div class="vendetlsfldmrg">
                    <div class="ptarea arialemail">Name</div>
                    <div class="tfarea"><input name="name" value="" type="text"  class="textarea" <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.contact_name"); ?> tabindex="1"/></div>
                </div>
                <div class="vendetlsfldmrg">
                    <div class="ptarea arialemail">Email ID</div>
                    <div class="tfarea"><input name="email" type="text" value="" class="textarea"   <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.email"); ?> tabindex="2"/></div>
                </div>
             <?php }?>
                  <div class="vendetlsfldmrg">
                    <div class="ptarea arialemail">Subject</div>
                    <div class="tfarea"><input name="subject" type="text" value="" class="textarea" tabindex="3" <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.contact_sub"); ?>/></div>
                </div>
                <div class="vendetlsfldmrg">
                    <div class="ptarea arialemail">Message</div>
                    <div class="tfarea"><textarea value="" name="message" class="textarea" style="height:100px;" required tabindex="4" <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.contact_msg"); ?>  ><?php echo isset($_POST['message'])? $_POST['message'] : '' ; ?> </textarea></div>
                </div>
                
                   <div class="vendetlsfldmrg" style="margin-top:90px;">
                    <div class="ptarea arialemail">Enter captcha</div>
                    <div class="tfarea"><input name="captcha" type="text" class="smalltextarea"  tabindex="5" <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.capcha"); ?> />
                        <img src="/captcha_code_file.php?rand=<?php echo rand(); ?>" style=" float: right; width: 160px;"  />
                    </div>
                </div>
                
				  <div class="vendetlsfldmrg" style="float:right;">
                      <div class="submitbtn">
                          <input type="submit" id="Btnsubmit" class="submit1" value="Save >" name="helpdesksubmit"  tabindex="6"/>

                </div>
                </div>


               
              
            </div>
        </div>
            </form>
            
            <script type="text/javascript">
    $("form").submit(function(){
  document.getElementById('Btnsubmit').disabled=true;
  document.getElementById('Btnsubmit').style.backgroundColor='grey';
});

</script>
            
                         