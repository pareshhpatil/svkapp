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
        <ul style="padding-top:10px;" class="page-sidebar-menu <?php echo $this->language; ?>" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            
            <?php foreach ($this->menus[0] as $row) { ?>
            <li class="<?php if(in_array($row['id'], $this->selectedMenu)){ echo 'active open';} ?>">
                    <a href="<?php if($row['link']!=''){ echo $row['link']; }else{ echo 'javascript:;';}  ?>">
                        <i class="<?php echo $row['icon']; ?>"></i>
                        <?php if($row['id']==170){?> <span class="badge badge-success">New</span> <?php }  ?>
                        <span class="title"><?php echo $row['title']; ?></span>

                        <?php if($row['link']==''){ echo '<span class="arrow "></span>'; }  ?>
                    </a>
                
                
                <?php if(isset($this->menus[$row['id']])){ ?>
                <ul class="sub-menu">
                <?php foreach ($this->menus[$row['id']] as $row2) { ?>
                    <li class="<?php if(in_array($row2['id'], $this->selectedMenu)){ echo 'active open';} ?>">
                        <a href="<?php if($row2['link']!=''){ echo $row2['link']; }else{ echo 'javascript:;';}  ?>">
                            <?php if($row2['icon']!=''){?> <i class="<?php echo $row2['icon']; ?>"></i><?php }?>
                            <span class="title"><?php echo $row2['title']; ?></span>
                            <?php if($row2['link']==''){ echo '<span class="arrow "></span>'; }  ?>
                        </a>
                        
                        
                         <?php if(isset($this->menus[$row2['id']])){ ?>
                <ul class="sub-menu">
                <?php foreach ($this->menus[$row2['id']] as $row3) { ?>
                    <li class="<?php if(in_array($row3['id'], $this->selectedMenu)){ echo 'active open';} ?>">
                        <a href="<?php if($row3['link']!=''){ echo $row3['link']; }else{ echo 'javascript:;';}  ?>">
                           <?php if($row3['icon']!=''){?> <i class="<?php echo $row3['icon']; ?>"></i><?php }?>
                            <span class="title"><?php echo $row3['title']; ?></span>
                            <?php if($row3['link']==''){ echo '<span class="arrow "></span>'; }  ?>
                        </a>
                    </li>
                <?php } ?>
                </ul>
                <?php } ?>
                        
                        
                    </li>
                <?php } ?>
                </ul>
                <?php } ?>
                </li>
            <?php } ?>

            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                <a href="javascript:;">
                    <i id="side-bar-toggle" style="position:fixed; bottom:50px; font-size: 25px; margin-left: 32px; width:32px" 
                    onMouseOver="this.style.color='#0f9dae'"
                    onMouseOut="this.style.color='#275770'"
                    class="sidebartoggle fa fa-chevron-circle-left"></i>
                </a>
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>

        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>