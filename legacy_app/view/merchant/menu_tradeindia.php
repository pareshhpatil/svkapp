
<section class="clearfix gray-bg main-collaps-container nav-tab-defult">
    <div class="">
        <div class="leftbar-menu" >
            <div class="page-sidebar-wrapper">
                <div class="page-sidebar navbar-collapse collapse">
                    <a href="https://www.tradeindia.com/my-tradeindia"><div class="mti-text" style="">My Tradeindia</div></a>
                    <ul class="page-sidebar-menu <?php echo $this->language; ?>" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                        
                        <li >
                            &nbsp;
                        </li>
                        <?php foreach ($this->menus[0] as $row) { ?>
                            <li class="<?php
                            if (in_array($row['id'], $this->selectedMenu)) {
                                echo 'active open';
                            }
                            ?>">
                                <a href="<?php
                                if ($row['link'] != '') {
                                    echo $row['link'];
                                } else {
                                    echo 'javascript:;';
                                }
                                ?>">
                                    <i class="<?php echo $row['icon']; ?>"></i>
                                    <span class="title"><?php echo $row['title']; ?></span>
    <?php
    if ($row['link'] == '') {
        echo '<span class="arrow "></span>';
    }
    ?>
                                </a>


                                        <?php if (isset($this->menus[$row['id']])) { ?>
                                    <ul class="sub-menu">
                                            <?php foreach ($this->menus[$row['id']] as $row2) { ?>
                                            <li class="<?php
                                                   if (in_array($row2['id'], $this->selectedMenu)) {
                                                       echo 'active open';
                                                   }
                                                   ?>">
                                                <a href="<?php
                                                    if ($row2['link'] != '') {
                                                        echo $row2['link'];
                                                    } else {
                                                        echo 'javascript:;';
                                                    }
                                                    ?>">
                                                    <?php if ($row2['icon'] != '') { ?> <i class="<?php echo $row2['icon']; ?>"></i><?php } ?>
                                                    <span class="title"><?php echo $row2['title']; ?></span>
                                                    <?php
                                                        if ($row2['link'] == '') {
                                                            echo '<span class="arrow "></span>';
                                                        }
                                                        ?>
                                                </a>


                                                            <?php if (isset($this->menus[$row2['id']])) { ?>
                                                    <ul class="sub-menu">
                                                                <?php foreach ($this->menus[$row2['id']] as $row3) { ?>
                                                            <li class="<?php
                                                if (in_array($row3['id'], $this->selectedMenu)) {
                                                    echo 'active open';
                                                }
                                                                    ?>">
                                                                <a href="<?php
                                        if ($row3['link'] != '') {
                                            echo $row3['link'];
                                        } else {
                                            echo 'javascript:;';
                                        }
                                        ?>">
                                            <?php if ($row3['icon'] != '') { ?> <i class="<?php echo $row3['icon']; ?>"></i><?php } ?>
                                                                    <span class="title"><?php echo $row3['title']; ?></span>
                    <?php
                    if ($row3['link'] == '') {
                        echo '<span class="arrow "></span>';
                    }
                    ?>
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






                    </ul>
                    <!-- END SIDEBAR MENU -->
                </div>
            </div>
        </div>
    </div>
</section>