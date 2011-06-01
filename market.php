<?php
$selected = 'world';
include("./includes/header.php");

displayErrors($errors);
displayNotices($notices);
?>
<div class="content-header width100p"><b>Market</b></div>
<img class='width100p' src='./images/npcs/market.jpg' usemap="#imap_56" />
<map id="imap_56" name="imap_56" >
  <area shape="rect" coords="371,61,479,149" alt="Trading Circle" title="Trading Circle" href="trading.php">
  <area shape="rect" coords="256,316,335,386" alt="Olivia the Merchant" title="Olivia the Merchant" href="olivia.php">
  <area shape="rect" coords="129,297,203,361" alt="Seymose the Trader" title="Seymose the Trader" href="seymose.php">
  <area shape="rect" coords="675,214,721,265" alt="Forums" title="Forums" href="forums.php">
  <area shape="rect" coords="703,228,747,278" alt="Forums" title="Forums" href="forums.php">
  <area shape="rect" coords="736,242,770,289" alt="Forums" title="Forums" href="forums.php">
  <area shape="rect" coords="664,448,725,491" alt="Your Drey" title="Your Drey" href="drey.php">
  <area shape="rect" coords="697,441,737,483" alt="Your Drey" title="Your Drey" href="drey.php">
  <area shape="rect" coords="717,433,747,470" alt="Your Drey" title="Your Drey" href="drey.php">
</map>
<?php
include('./includes/footer.php');
?>