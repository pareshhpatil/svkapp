<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">

        <div class="col-md-1"></div>
        <div class="col-md-12">
                <div class="alert alert-danger">
                    <strong>Error!</strong>
                    <div class="media">
                         <?php
            if (!empty($this->error)) {

                foreach ($this->error as $error_name) {
                    echo '<p align="left">' . $error_name[0] . ' -';
                    $int = 1;
                    while (isset($error_name[$int])) {
                        echo ' ' . $error_name[$int];
                        $int++;
                    }
                }
                echo '</p>';
            }

            if (!empty($this->errorlist)) {

                foreach ($this->errorlist as $error_name) {
                    echo '<p align="left">' . $error_name['code'].'<br>';
                    echo ' Message - ' . $error_name['msg'] . '<br> key name - ' . $error_name['keyname'] . '<br>  key path - ' . $error_name['keypath'];
                }
                echo '</p>';
            }
            ?>
                    </div>

                </div>
                </div>
                </div>
</div>
</div>