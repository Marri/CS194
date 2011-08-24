<?php
$selected = 'world';
include("./includes/header.php");

displayErrors($errors);
displayNotices($notices);
?>
<div class="content-header width100p"><b>Market</b></div>
<img class='width100p' src='./images/npcs/market.jpg' usemap="#imap_56" />
<map id="imap_56" name="imap_56" >
  <area shape="rect" coords="746,317,842,361" alt="Matchmaker" title="Matchmaker" href="gypsy_match.php">
  <area shape="rect" coords="736,388,809,427" alt="Jobs" title="Jobs" href="hire.php">
  <area shape="rect" coords="728,439,822,479" alt="Help!!!" title="Help!!!" href="help.php">
  <area shape="rect" coords="366,71,457,132" alt="Trading Circle" title="Trading Circle" href="trading.php">
  <area shape="rect" coords="212,372,330,443" alt="Olivia the Merchant" title="Olivia the Merchant" href="olivia.php">
  <area shape="rect" coords="249,314,323,381" alt="Olivia the Merchant" title="Olivia the Merchant" href="olivia.php">
  <area shape="rect" coords="2,244,110,332" alt="Train" title="Train" href="tournaments.php">
  <area shape="rect" coords="119,291,191,380" alt="Seymose the Trader" title="Stuff 4 Sale" href="seymose.php">
  <area shape="rect" coords="23,335,126,406" alt="Seymose the Trader" title="Seymose the Trader" href="seymose.php">
  <area shape="rect" coords="638,435,694,472" alt="Your Drey" title="Your Drey" href="drey.php">
  <area shape="rect" coords="680,423,723,455" alt="Your Drey" title="Your Drey" href="drey.php">
  <area shape="rect" coords="676,167,732,208" alt="Search" title="Search" href="gypsy_search.php">
  <area shape="rect" coords="709,186,747,224" alt="Search" title="Search" href="gypsy_search.php">
  <area shape="rect" coords="660,217,710,252" alt="Forums" title="Forums" href="forums.php">
  <area shape="rect" coords="691,228,741,276" alt="Forums" title="Forums" href="forums.php">
  <area shape="rect" coords="692,283,744,318" alt="Games" title="Games" href="games.php">
  <area shape="rect" coords="663,299,709,331" alt="Games" title="Games" href="games.php">
  <area shape="rect" coords="651,343,704,378" alt="River" title="River" href="river.php">
  <area shape="rect" coords="693,332,735,367" alt="River" title="River" href="river.php">
  <area shape="rect" coords="647,387,723,423" alt="Farms" title="Farms" href="farms.php">
  <area shape="rect" coords="351,251,593,348" alt="Trading Circle" title="Trading Circle" href="trading.php">
</map>
<?php
include('./includes/footer.php');
?>