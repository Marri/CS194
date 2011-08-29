<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
    <title>Squffies</title>
    
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
    
    <link rel="shortcut icon" href="./images/icons/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="./includes/include.php?file=style&type=php&folder=css" type="text/css" />
    <script type="text/javascript" src="./includes/include.php?file=jquerymin&type=js"></script>
    <script type="text/javascript" src="./includes/include.php?file=menu&type=js"></script>
    <?php 
	if(isset($js)) {
		foreach($js as $file) { echo '<script type="text/javascript" src="./includes/include.php?file=' . $file . '&type=js"></script>'; }
	} 
	if(isset($css)) {
		foreach($css as $file) { echo '<link rel="stylesheet" href="./css/' . $file . '.css" type="text/css" />'; }
	}
	?>
</head>

<body>
	<div id='wrapper'>
        <div id='header'></div>
        <div id='content-wrapper'>
            <div id='left-column'>
            	<div id='side-menu'>
                	<?php if(!$loggedin) { ?>
                	<form action="" method="post">
						<label for="uname">Login Name:</label>
    						<input type="text" name="login_name" size="10" maxlength="50" />
						<label for="pword">Password: </label>
							<input  type="password" name="password" size="10" maxlength="20" />
						<input type="submit" class="submit-input" value="Login" name="logging_in" />
					</form>
					<form action="./register.php" method="get">
						<input type="submit" class="submit-input" value="Register" />
					</form>
                    <?php } else { ?>
                    <a href="profile.php?id=<?php echo $user->getID(); ?>"><b><?php echo $user->getUsername(); ?></b></a> (#<?php echo $user->getID(); ?>)<br />
					<?php
                    $inventory = $user->getInventory();
                    $nuts = $inventory['cashew'] + $inventory['pistachio'] + $inventory['chestnut'] + $inventory['pecan'] + $inventory['walnut'] + $inventory['almond'];
					$sd = $inventory['squffy_dollar'];
                    ?>
                    <span class="float-left">&nbsp;<b><?php echo $nuts; ?></b> Nuts </span><a href="#"><img src="./images/icons/add.png" alt="+" id="nut-toggle" /></a><br />
                    <div id="nut-holder" class="hidden">
                    	<?php
						if($inventory['cashew'] > 0) { echo '<b>' . $inventory['cashew'] . '</b> cashews<br />'; }
						if($inventory['pistachio'] > 0) { echo '<b>' . $inventory['pistachio'] . '</b> pistachios<br />'; }
						if($inventory['chestnut'] > 0) { echo '<b>' . $inventory['chestnut'] . '</b> chestnuts<br />'; }
						if($inventory['pecan'] > 0) { echo '<b>' . $inventory['pecan'] . '</b> pecans<br />'; }
						if($inventory['walnut'] > 0) { echo '<b>' . $inventory['walnut'] . '</b> walnuts<br />'; }
						if($inventory['almond'] > 0) { echo '<b>' . $inventory['almond'] . '</b> almonds<br />'; }
						?>
                    </div>
                    &nbsp;<b><?php echo $sd; ?></b> SD<br /><br />
                    <b>Quick Links</b><br />
                    &nbsp;<a href="drey.php">Drey</a><br />
                    &nbsp;<a href="hoard.php">Item Hoard</a><br />
                    &nbsp;<a href="messages.php">Messages</a><br />
                    &nbsp;<a href="recent.php">Recent Posts</a><br />
                    &nbsp;<a href="sitemap.php">Sitemap</a><br /><br />
                    
                    <form action="" method="post">
						<input type="submit" name="logging_out" class="submit-input margin-left-small" value="Logout" />
					</form>
                    <?php } ?>
                </div>
                <div id='time'>
        			<?php echo date("g:i A"); ?><br />
                    <?php echo date("F j, Y"); ?><br />
                    Squffy Time
                </div>
            </div>
            <div id='right-column'>
            	<div id='menu'>
                	<?php
					if(!isset($selected)) { $selected = "home"; }
                	echo '<a href="./"';
					if($selected == "home") { echo ' class="active"'; }
					echo '>Home</a><a href="#"';
					if($selected == "account") { echo ' class="active"'; }
					echo '>Your Account</a><a href="#"';
					if($selected == "squffies") { echo ' class="active"'; }
					echo '>Squffies</a><a href="#"';
					if($selected == "world") { echo ' class="active"'; }
					echo '>In the World</a><a href="#"';
					if($selected == "interact") { echo ' class="active"'; }
					echo '>Interact</a><a href="#" class="last-link';
					if($selected == "help") { echo ' active'; }
					echo '">Help and Information</a>';
					?>
                </div>
                <div id='submenu'>
                    <span id='youraccount' class='submenu-span'>
                    <?php
                    if($loggedin) {
						echo '<a href="./profile.php?id=' . $userid . '" class="fivesmall">Your Profile</a>' .
							 '<a href="./edit_account.php" class="fivelarge">Edit Account Settings</a>' .
							 '<a href="./buy.php" class="fivelarge">Purchase Squffy Dollars</a>' .
							 '<a href="./refer.php" class="fivesmall">Refer Friends</a>' .
							 '<a href="./referrals.php" class="fivelast">Your Referrals</a>';
                    } else {
						echo '<a href="./register.php" class="five">Create Account</a>' .
						 	 '<a href="./activate.php" class="five">Activate Account</a>' .
						 	 '<a href="./resend.php" class="five">Resend Activation</a>' .
						 	 '<a href="./fix_email.php" class="five">Fix Email Address</a>' .
						 	 '<a href="./reset.php" class="fivelast">Reset Password</a>';
                    }
                    ?>
                    </span>
                    <span id='squffies' class='submenu-span hidden'>
                    	<?php
						echo '<a href="./drey.php" class="five">Drey</a>' .
							 '<a href="./nursery.php" class="five">Nursery</a>' .
							 '<a href="./gypsy.php" class="five">Gypsy</a>' .
							 '<a href="./hire.php" class="five">Job Fair</a>' .
							 '<a href="./customs.php" class="fivelast">Customs</a>';
						?>
                    </span>
                    <span id='intheworld' class='submenu-span hidden'>
                    	<?php
						echo '<a href="./hoard.php" class="six">Hoard</a>' .
							 '<a href="./pantry.php" class="six">Pantry</a>' .
							 '<a href="./farms.php" class="six">Farmland</a>' .
							 '<a href="./market.php" class="six">Marketplace</a>' .
							 '<a href="./tournaments.php" class="six">Tournaments</a>' .
							 '<a href="./games.php" class="sixlast">Games</a>';
						?>            
                    </span>
                    <span id='interact' class='submenu-span hidden'>                    
                    	<?php
						echo '<a href="./messages.php" class="seven">Messages</a>' .
							 '<a href="./notifications.php" class="seven">Notifications</a>' .
							 '<a href="./forums.php" class="seven">Forums</a>' .
							 '<a href="./recent.php" class="seven">Recent Posts</a>' .
							 '<a href="./friends.php" class="seven">Friends</a>' .
							 '<a href="./blocked.php" class="seven">Blocked</a>' .
							 '<a href="./online.php" class="sevenlast">Online Now</a>';
						?>         
                    </span>
                    <span id='helpandinformation' class='submenu-span hidden'>                    
                    	<?php
						echo '<a href="./help.php" class="seven">Squffies 101</a>' .
							 '<a href="./news.php" class="seven">Latest News</a>' .
							 '<a href="./code.php" class="seven">Code Releases</a>' .
							 '<a href="./sitemap.php" class="seven">Sitemap</a>' .
							 '<a href="./bugs.php" class="seven">Bug Reports</a>' .
							 '<a href="./staff.php" class="seven">Staff</a>' .
							 '<a href="./credits.php" class="sevenlast">Credits</a>';
						?>           
                    </span>
                </div>
                <div id='content'>
                
                <?php
				if(isset($_POST['resetPass'])) {
					include('./scripts/finish_reset.php');
				}
				
				if($loggedin && $user->getLevel() == User::RESET_PASSWORD_USER) {
					$cur = 'odd';
					$errors[] = 'You have recently reset your password. Please change your temporary password now.';
					displayErrors($errors);
					echo '<form action="" method="post"><table cellspacing="0" class="width100p">
					<tr';
					$cur = row($cur);
					echo '><td class="content-miniheader width200">New password</td><td><input type="password" class="width200" name="pass" /></td></tr>
					<tr';
					$cur = row($cur);
					echo '><td class="content-miniheader width200">Confirm password</td><td><input type="password" class="width200" name="confirm" /></td></tr>
					<tr';
					$cur = row($cur);
					echo '><td class="text-center" colspan="2"><input type="submit" name="resetPass" value="Change password" class="submit-input" /></td></tr>
					</table>';
					include('./includes/footer.php');
					die();
				}
				
				if(sizeof($errors) > 0) { 
					displayErrors($errors); 
					$errors = array();
				}
				
				if(!isset($forLoggedIn)) { $forLoggedIn = false; }
				if($forLoggedIn && !$loggedin) {
					displayErrors(array('You must be logged in to see this page.'));
					include('./includes/footer.php');
					die();
				}
				
				if(!isset($forNewbies)) { $forNewbies = false; }
				if($forNewbies && $loggedin) {
					displayErrors(array("You are already logged in."));
					include('./includes/footer.php');
					die();
				}
				
				if($loggedin) {
					$rand = mt_rand(100, 10000);
					if($rand == 274) {
						displayNotices(array('You have found some iron ore lying on the ground! How useful.'));
						$user->updateInventory('iron_ore', 2, true);
					}

					$query = "select is_read as num from messages where to_id = $userid and is_read = 'false'";
					$result = runDBQuery($query);
					if(@mysql_num_rows($result) > 0) { displayNotices(array('You have unread messages! <a href="messages.php">Go to your inbox to read them</a>.')); }
				}
				?>