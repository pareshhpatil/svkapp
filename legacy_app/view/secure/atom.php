<html>
    <body >
        <form method="get" action="<?php echo $this->url; ?>" name="frmTransaction" id="frmTransaction">
            <table border="1">
                <tbody>
                    <?php
                    foreach ($this->get_array as $key => $value) {
                        echo '<input type="hidden" name="' . $key . '" value="' . $value . '">';
                    }
                    ?>
                </tbody>
            </table>
            <input type="submit">
        </form>
    </body>
    <script type="text/javascript">
       // document.getElementById('frmTransaction').submit();

    </script>
</html>
