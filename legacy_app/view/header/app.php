<?php
include_once '../legacy_app/view/header/header_common.php';
?>
<?php
if ($this->header_file) {
    foreach ($this->header_file as $file) {
        include_once '../legacy_app/view/header/' . $file . '.php';
    }
}
?>
<?php
include_once '../legacy_app/view/header/header_menu.php';
?>
