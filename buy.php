<?php
$selected = 'account';
include("./includes/header.php");

displayErrors($errors);
displayNotices($notices);
?>
<div class="content-header width100p"><b>Buy Squffy Dollars</b></div>
<div class='text-center div-center width400'>
<!--form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="image" src="./images/npcs/buysd.jpg" style="border:0px;" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
	<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
	<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"></form>
    -->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ZPBB4VTGFGL9W">
<input type="image" class="no-border" src="./images/npcs/buysd.jpg" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<div class='float-right'>
<!-- PayPal Logo --><table border="0" cellpadding="10" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="#" onclick="javascript:window.open('https://www.paypal.com/us/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');"><img  src="https://www.paypal.com/en_US/Marketing/i/banner/bnr_payments_120x30_y.gif" border="0" alt="Additional Options"></a></td></tr></table><!-- PayPal Logo -->
</div>
</div>

<?php
include('./includes/footer.php');
?>