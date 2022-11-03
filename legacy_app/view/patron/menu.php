<?php
switch ($this->selectedMenu) {
    case 'patron_request':
        $select = 'm3';
        break;
    case 'receipt':
        $select = 'm4';
        break;
    case 'profile':
        $select = 'm5';
        break;
    case 'suggest':
        $select = 'm5';
        break;
    case 'dashboard':
        $select = 'm6';
        break;
}
?>
<div class="page-sidebar-wrapper">
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->

            <li>
                &nbsp;<br>&nbsp;
            </li>

            <li class="<?php echo ($select == 'm6') ? 'active open' : 'start'; ?>">
                <a href="/patron/dashboard">
                    <i class="icon-home"></i>
                    <span class="<?php echo ($select == 'm6') ? 'active' : 'title'; ?>">Dashboard</span>
                </a>
            </li>
            <li class="<?php echo ($select == 'm3') ? 'active open' : ''; ?>">
                <a href="/patron/paymentrequest/viewlist">
                    <i class="icon-drawer"></i>
                    <span class="title">Payments due</span>
                    <span class="arrow "></span>
                    <?php echo ($select == 'm3') ? '<span class="selected"></span>' : ''; ?>
                </a>

            </li>
            <li class="<?php echo ($select == 'm4') ? 'active open' : ''; ?>">
                <a href="/patron/transaction/viewlist">
                    <i class="icon-credit-card"></i>
                    <span class="title">Receipts</span>
                    <span class="arrow"></span>
                    <?php echo ($select == 'm4') ? '<span class="selected"></span>' : ''; ?>
                </a>

            </li>

            <li class="<?php echo ($select == 'm5') ? 'active open' : ''; ?>">
                <a href="javascript:;">
                    <i class="icon-user"></i>
                    <span class="title">Profile</span>
                    <span class="arrow "></span>
                    <?php echo ($select == 'm5') ? '<span class="selected"></span>' : ''; ?>
                </a>
                <ul class="sub-menu">
                    <li <?php
                        if ($this->selectedMenu == 'suggest') {
                            echo 'class="active"';
                        }
                        ?>>
                        <a href="/patron/profile/suggest">
                            Suggest a merchant</a>
                    </li>
                    <li <?php
                        if ($this->selectedMenu == 'profile') {
                            echo 'class="active"';
                        }
                        ?>>
                        <a href="/patron/profile">
                            Update profile</a>
                    </li>
                    <li <?php
                        if ($this->selectedMenu == 'reset') {
                            echo 'class="active"';
                        }
                        ?>>
                        <a href="/profile/reset">
                            Password reset</a>
                    </li>
                </ul>
            </li>

            <li class="<?php echo ($this->selectedMenu == 'mybills') ? 'active open' : 'start'; ?>">
                <a href="/mybills">
                    <i class="icon-anchor"></i>
                    <span class="<?php echo ($this->selectedMenu == 'mybills') ? 'active' : 'title'; ?>">Search my bills</span>
                </a>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>