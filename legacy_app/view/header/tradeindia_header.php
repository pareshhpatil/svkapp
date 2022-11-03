<link href="/assets/admin/layout/css/trade_india_custom.css<?php echo $this->fileTime('css', 'trade_india_custom'); ?>" rel="stylesheet" type="text/css"/>
<?php $menu = $this->lang['menu']; ?>
<header>
    <div class="fix-row clearfix">
        <?php if ($this->hide_menu != 1) { ?>
            <div class="collapse-icon-bg collapse-icon-pulse visible-xs">
                <div class="collapse-icon"></div>
            </div>
        <?php } else { ?>
            <div class="collapse-icon-bg collapse-icon-pulse visible-xs" style="background: none;">
            </div>
        <?php } ?>
        <div class="ti-logo">
            <a href="<?php echo $this->home_url; ?>">
                <img src="https://tiimg.tistatic.com/new_website1/ti-design-2017/images/tradeindiaLogo.png" alt="tradeindia.com" style=" width: 50px;"/>
            </a>
        </div>

        <div class="notification-userthumb">

            <!--  <div id="clickNotification" class=""></div>
              <div class="notification-open">
                  <div class="notification">
                  </div>

                  <div class="loginArea" id="nofication">
                      <div class="loginLogoCnt">
                          <a class="logo">
                              <span class="markPoint"></span>
                          </a>
                      </div>
                      <div class="notificationList">
                          <ul>
                          </ul>
                          <div class="thinBorder"></div>
                          <p class="bold center mt10">No Notification Yet!</p>
                      </div>
                  </div>

              </div>-->
            <div class="userthumb-detail">

                <div class="userthumb-fun">
                    <div class="user-thumb">
                        <!--<div class="user-thumbnot-found">M</div>--> <!-- User image not found-->
                        <img src="/images/trade-india-profile.gif"/>
                    </div>
                    <div class="user-name">
                        <?php echo $this->display_name; ?>
                    </div>
                </div>

                <div class="loginBox">
                    <div class="navList">
                        <span class="markPoint"></span>
                        <div class="user-name-res">Hi, <?php echo $this->display_name; ?></div><!-- Only for mobile device -->
                        <ul>
                            <?php if ($this->usertype == 'merchant') {
                                ?>
                                <?php if ($this->hide_menu != 1) { ?>
                                   <!-- <li>
                                        <a href="/merchant/dashboard/home">
                                            <i class="icon-home"></i> Home </a>
                                    </li>
                                   -->
                                <?php } else { ?>
                                    <li>
                                        <a href="/merchant/dashboard">
                                            <i class="icon-home"></i> Dashboard </a>
                                    </li>
                                <?php } ?>

                                <li>
                                    <a href="/merchant/profile">
                                        <i class="icon-user"></i> <?php echo $menu['update_profile']; ?> </a>
                                </li>
                                <li class="hidden-xs">
                                    <a href="/merchant/companyprofile">
                                        <i class="fa fa-building"></i> <?php echo $menu['company_profile']; ?></a>
                                </li>
                                <li>
                                    <a href="/merchant/profile/packagedetails">
                                        <i class="fa fa-inr"></i> <?php echo $menu['package_details']; ?></a>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <a href="/profile/preferences">
                                        <i class="icon-user"></i> Preferences </a>
                                </li>
                            <?php } ?>


                        </ul>
                    </div>

                </div>


            </div>
        </div>

    </div>
</header>