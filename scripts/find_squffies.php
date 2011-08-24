<?php
$valid = true;
$add = '';

//ages: postfilter
//anything hidden by age: postfilter (gender for eggs, appearance, chromosomes)

//Name
$name = $_POST['squffy'];
if($name != "") {
	$safe_name = @mysql_real_escape_string($name);
	$add .= " AND `squffy_name` LIKE '%$safe_name%'";
}

//Owner
$referer = $_POST['owner'];
if(strlen($referer) > 0) {
	if(Verify::VerifyID($referer)) {
		$add .= ' AND squffy_owner = ' . $referer;
	} else if(!Verify::VerifyUsername($referer, true)) {
		$safe_owner = @mysql_real_escape_string($referer);
		$owner = User::getUserByUsername($safe_owner);
		if($owner == NULL) {
			$errors[] = 'The username you entered for the owner does not exist.';
			$valid = false;
		} else {
			$add .= ' AND squffy_owner = ' . $owner->getID();
		}
	} else {
		$errors[] = 'You did not enter either a valid username or user ID for the owner.';
		$valid = false;
	}
}

if(!isset($_POST['male']) && !isset($_POST['female']) && !isset($_POST['egg'])) {
	$errors[] = 'You deselected male, female and egg; there are no possible results.';
	$valid = false;
}

if(!isset($_POST['species'])) {
	$errors[] = 'You deselected all available species; there are no possible results.';
	$valid = false;
} else {
	$species = $_POST['species'];
	$in = '';
	foreach($species as $s) {
		$in .= ', ' . $s;
	}
	$add .= ' AND squffy_species IN (' . substr($in, 2) . ')';
}

$cFilter = false;
for($i = 1; $i < 9; $i++) {
	$min = $_POST['minc'.$i];
	$max = $_POST['maxc'.$i];
	if($min > 0) { $add .= " AND c$i >= $min"; }
	if($max < 100) { $add .= " AND c$i <= $max"; }
	if($min > 0 || $max < 100) { $cFilter = true; }
}

if(isset($_POST['market'])) { $add .= ' AND is_in_market = \'true\''; }
if(isset($_POST['hire'])) { $add .= ' AND is_hireable = \'true\''; }
if(isset($_POST['breed'])) { $add .= ' AND is_breedable = \'true\''; }

if($valid) {	
	$query = "SELECT * FROM `squffies`";
	if(strlen($add) > 0) { $query .= ' WHERE ' . substr($add, 5); }
	
	$canBeMale = isset($_POST['male']);
	$canBeFemale = isset($_POST['female']);
	$canBeEgg = isset($_POST['egg']);
	$canBeAdult = isset($_POST['adult']);
	$canBeTeen = isset($_POST['teen']);
	$canBeChild = isset($_POST['child']);
	
	echo '<table class="width100p" cellspacing="0"><tr><th colspan="5" class="content-header">Search Results</th></tr>
	<tr><td colspan="4"><table class="width100p">';
	$i = 0;
	
	$squffies = Squffy::getSquffies($query);
	$cached_links = array();
	foreach($squffies as $squffy) {
		if($cFilter && $squffy->isEgg()) { continue; }
		if(!$canBeEgg && $squffy->isEgg()) { continue; }
		if(!$canBeMale && $squffy->getGender() == 'M') { continue; }
		if(!$canBeFemale && $squffy->getGender() == 'F') { continue; }
		if(!$canBeAdult && $squffy->isAdult()) { continue; }
		if(!$canBeChild && $squffy->isHatchling()) { continue; }
		if(!$canBeTeen && $squffy->isTeenager()) { continue; }
		
		if($i%4 == 0) { echo '<tr>'; }
		echo '<td class="vertical-top width150 bordered ';
		if($squffy->getGender() == 'F') { echo 'female'; }
		elseif($squffy->getGender() == 'M') { echo 'male'; }
		echo ' text-center"><div class="float-left width100">
		<a href="view_squffy.php?id=' . $squffy->getID() .'">
		<img src="' . $squffy->getThumbnail() . '" /><br>';
		echo $squffy->getName() . '</a></div>
		<div class="info-box" style="width: 107px !important; float: right;">
		<b>Owner</b>: ';
		if(isset($owner)) { echo $owner->getLink(); }
		elseif(isset($cached_links[$squffy->getOwnerID()])) { echo $cached_links[$squffy->getOwnerID()]; }
		else {
			$own = User::getUserByID($squffy->getOwnerID());
			echo $own->getLink();
			$cached_links[$squffy->getOwnerID()] = $own->getLink();
		}
		echo '<br />';
		echo ucfirst($squffy->getStage()) . '<br /><br />';
		if($squffy->isSick()) { echo '<img src="./images/icons/sick.png" title="Sick" /> Sick<br />'; }
		if($squffy->isHungry()) { echo '<img src="./images/icons/hungry.png" title="Hungry" /> Hungry<br />'; }
		if($squffy->isSick() || $squffy->isHungry()) { echo '<br />'; }
		//if($squffy->isForSale()) { echo '<img src="./images/icons/sale.png" title="For sale" /> '; }
		if($squffy->isBreedable()) { echo '<img src="./images/icons/heart.png" title="For breeding" /> Breedable<br />'; }
		if($squffy->isPregnant()) { echo '<img src="./images/icons/egg.png" title="Pregnant" /> Pregnant<br />'; }
		if($squffy->isWorking()) { echo '<img src="./images/icons/clock.png" title="Working" /> Working<br />'; }
		echo '</div></td>';
		if($i%4 == 3) { echo '</tr>'; }
		$i++;
	}
	
	if($i%4 > 0) { 
		while($i%4 > 0) { echo '<td class="width150"></td>'; $i++; }
		echo '</tr>'; 
	}
	
	echo '</table></td></tr></table>';
	include('./includes/footer.php');
	die();
}
?>