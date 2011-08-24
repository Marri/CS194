<?php
$query = "SELECT badge_name, badge_description FROM user_badges, badges WHERE user_id = $id AND badges.badge_id = user_badges.badge_id";
$result = runDBQuery($query);
if(@mysql_num_rows($result) < 1) {
	echo '<br /><span class="small-error">This user has no badges yet :(</span>';
	echo '</td></tr></table>';
	include('./includes/footer.php');
}
echo '<table class="width100p" cellspacing="0">';
$i = 0;
while($badge = @mysql_fetch_assoc($result)) {
	if($i%4 == 0) { echo '<tr>'; }
	echo '<td class="width25p">';
	$img = './images/badges/' . strtolower(str_replace(" ", "", $badge['badge_name'])) . '.png';
	echo '<img src="' . $img . '" alt="' . $badge['badge_name'] . '" title="' . $badge['badge_description'] . '" />';
	echo '</td>';
	if($i%4 == 3) { echo '</tr>'; }
	$i++;
}

if($i%4 > 0) { 
	while($i%4 > 0) { echo '<td class="width150"></td>'; $i++; }
	echo '</tr>'; 
}
?>

</table>

</td>
</tr>
</table>

<?php
include('./includes/footer.php');
?>