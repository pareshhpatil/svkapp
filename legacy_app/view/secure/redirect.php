<html>
    <head>
        <?php if (isset($this->global_tag)) {
        echo $this->global_tag;
    } ?>
    </head>
    <body oncontextmenu="return false;">
        <form method="post" action="<?php echo $this->post_url; ?>" name="frmTransaction" id="frmTransaction">
            <table border="1">
                <tbody>
                    <?php
                    foreach ($this->post as $key => $value) {
                        echo '<input type="hidden" name="' . $key . '" value="' . $value . '">';
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
