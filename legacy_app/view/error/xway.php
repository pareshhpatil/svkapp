<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
    <body style="font-family: Arial, Helvetica, sans-serif;font-size: 11px;color: #666666;">
        <div id="wrapper-outer" style="margin: 10px auto 0;width: 950px;">
            <div style="border-bottom: 1px solid #666;font-size: 1.3em;"><h3 style="text-align:center;">Oops!</h3></div>
            <!-- Production message -->
            <p style="text-align:center;">
                It seems an error has occured.
            </p>
            <!-- Debug message -->
            <h6 align="left" style="margin-bottom: 0.1em;font-size: 1em;font-weight: bold;line-height: 1em;color: #666;}">Error!</h6>
            <?php
            if ($this->hasError()) {

                foreach ($this->_error as $error_name) {
                    echo '<p align="left">' . $error_name[0] . ' -';
                    $int = 1;
                    while (isset($error_name[$int])) {
                        echo ' ' . $error_name[$int];
                        $int++;
                    }
                }
                echo '</p>';
            }
            ?>
        </div>
    </body></html>