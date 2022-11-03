<html>
     <body oncontextmenu="return false;">
        <form method="post" action="<?php echo $this->post_url; ?>/_payment" name="frmTransaction" id="frmTransaction">
            <table border="1">
                <tbody>
                    <?php
                    foreach ($this->post as $key => $value) {
                        if ($key == 'productinfo') {
                            echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($value) . '">';
                        } else {
                            echo '<input type="hidden" name="' . $key . '" value="' . $value . '">';
                        }
                    }
                    ?>
                </tbody>
            </table>

   </form>
    </body>
<script type="text/javascript">
document.getElementById('frmTransaction').submit();

</script>
</html>
