<?php
$selected = "squffies";
$css[] = 'slider';
$css[] = 'uiCore';
$css[] = 'uiTheme';
$js[] = 'jqueryUI';
$js[] = 'jqueryUIWidget';
$js[] = 'jqueryUIMouse';
$js[] = 'jqueryUISlider';
$js[] = 'gypsy';
$cur = 'odd';
include("./includes/header.php");

if(isset($_POST['search'])) {
	include('./scripts/find_squffies.php');
}

displayErrors($errors);
displayNotices($notices);
?>
<div class='content-header width100p'><b>Nadya the Gypsy</b></div>

<div class='text-center'>
	<img src='./images/npcs/nadia.jpg' /><br />
What kind of squffy might you be searching for today, dearie?<br /><br />
</div>

<form action="gypsy_search.php" method="post">
<table cellspacing='0' class='width100p'>
<tr><th colspan="3" class="content-subheader">Search Criteria</th></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">Name</td>
<td colspan="2"><input class="width100" id="squffy" autocomplete="off" name="squffy" type="text"><div id="autoComplete-squffy" class="autocomplete width300 hidden"></div></td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">Owner</td>
<td colspan="2"><input class="width100" id="owner" autocomplete="off" name="owner" type="text"><div id="autoComplete-owner" class="autocomplete width300 hidden"></div></td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">gender</td>
<td colspan="2"><input type="checkbox" name="male" value="1" checked> Male <input type="checkbox" name="female" value="1" checked> Female</td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">age</td>
<td colspan="2">
<input type="checkbox" name="adult" value="1" checked> Adult 
<input type="checkbox" name="teen" value="1" checked> Child 
<input type="checkbox" name="child" value="1" checked> Hatchling 
<input type="checkbox" name="egg" value="1"> Egg</td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">species</td>
<td colspan="2">
<?php
$query = "SELECT * FROM species";
$result = runDBQuery($query);
while($species = @mysql_fetch_assoc($result)) {
	echo '<input type="checkbox" name="species[]" value="' . $species['species_id'] . '" checked> ' . $species['species_name'] . ' ';
}
?>
</td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width150">strength</td>
<td class="width80"><span id="c1min">0</span> - <span id="c1max">100</span>
<input type="hidden" id="minc1" name="minc1" value="0"><input type="hidden" id="maxc1" name="maxc1" value="100">
</td><td><div id="c1slider" class="width300"></div></td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">speed</td>
<td><span id="c2min">0</span> - <span id="c2max">100</span>
<input type="hidden" id="minc2" name="minc2" value="0"><input type="hidden" id="maxc2" name="maxc2" value="100">
</td><td><div id="c2slider" class="width300"></div></td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">agility</td>
<td><span id="c3min">0</span> - <span id="c3max">100</span>
<input type="hidden" id="minc3" name="minc3" value="0"><input type="hidden" id="maxc3" name="maxc3" value="100">
</td><td><div id="c3slider" class="width300"></div></td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">endurance</td>
<td><span id="c4min">0</span> - <span id="c4max">100</span>
<input type="hidden" id="minc4" name="minc4" value="0"><input type="hidden" id="maxc4" name="maxc4" value="100">
</td><td><div id="c4slider" class="width300"></div></td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">fertility</td>
<td><span id="c5min">0</span> - <span id="c5max">100</span>
<input type="hidden" id="minc5" name="minc5" value="0"><input type="hidden" id="maxc5" name="maxc5" value="100">
</td><td><div id="c5slider" class="width300"></div></td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">heritability</td>
<td><span id="c6min">0</span> - <span id="c6max">100</span>
<input type="hidden" id="minc6" name="minc6" value="0"><input type="hidden" id="maxc6" name="maxc6" value="100">
</td><td><div id="c6slider" class="width300"></div></td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">gene dominance</td>
<td><span id="c7min">0</span> - <span id="c7max">100</span>
<input type="hidden" id="minc7" name="minc7" value="0"><input type="hidden" id="maxc7" name="maxc7" value="100">
</td><td><div id="c7slider" class="width300"></div></td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader">xx dominance</td>
<td><span id="c8min">0</span> - <span id="c8max">100</span>
<input type="hidden" id="minc8" name="minc8" value="0"><input type="hidden" id="maxc8" name="maxc8" value="100">
</td><td><div id="c8slider" class="width300"></div></td>
</tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader vertical-top">only find</td>
<td colspan="2">
<input type="checkbox" name="market" value="1"> Available in the market<br />
<input type="checkbox" name="hire" value="1"> Available for hire<br />
<input type="checkbox" name="breed" value="1"> Available for breeding</td>
</tr>
<tr<?php row($cur); ?>><th class="content-miniheader vertical-top">appearance</td>
<td colspan="2">
<input type="radio" checked name="traits" value="has"> Can have other traits <input type="radio" name="traits" value="only"> Matches the following exactly<br />
<a href="#"><img src="./images/icons/add.png" alt="+"> Add a trait to search for</a></td></tr>
<!--tr<?php $cur = row($cur); ?>><td></td><td id="traitsearch" colspan="2">TRAITS</td></tr-->

<tr><td></td>
<td colspan="2"><input type="submit" class="submit-input" name="search" value="Gaze into the crystal ball" /></td>
</tr>
</table>
</form>

<br /><br />

<?php 
include("./includes/footer.php");
?>
