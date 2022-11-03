<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

?>
<html>
<head>
</head>
<body oncontextmenu="return false;">
		<form method="post" action="<?php echo $this->PAYTM_TXN_URL; ?>" name="f1" id="frmTransaction">
		<table border="1">
			<tbody>
			<?php
			foreach($this->paramList as $name => $value) {
				echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
			}
			?>
			<input type="hidden" name="CHECKSUMHASH" value="<?php echo $this->checksum; ?>">
			</tbody>
		</table>
	</form>
</body>
<script type="text/javascript">

document.getElementById("frmTransaction").submit();

</script>
</html>