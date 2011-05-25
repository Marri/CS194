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
                	<form action="index.php" method="post">
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
                    <a href="#"><b><?php echo $user->getUsername(); ?></b></a> (#<?php echo $user->getID(); ?>)<br />
					<?php
                    $inventory = $user->getInventory();
                    $nuts = $inventory['cashew'] + $inventory['pistachio'] + $inventory['chestnut'] + $inventory['pecan'] + $inventory['walnut'] + $inventory['almond'];
					$sd = $inventory['squffy_dollar'];
                    ?>
                    <span class="float-left">&nbsp;<b><?php echo $nuts; ?></b> Nuts </span><a href="#"><img src="./images/icons/add.png" alt="+" id="nut-toggle" /></a><br />
                    <div id="nut-holder" class="hidden">
                    	<?php
						echo '<b>' . $inventory['cashew'] . '</b> cashews<br />';
						echo '<b>' . $inventory['pistachio'] . '</b> pistachios<br />';
						echo '<b>' . $inventory['chestnut'] . '</b> chestnuts<br />';
						echo '<b>' . $inventory['pecan'] . '</b> pecans<br />';
						echo '<b>' . $inventory['walnut'] . '</b> walnuts<br />';
						echo '<b>' . $inventory['almond'] . '</b> almonds<br />';
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
						echo '<a href="./profile.php?userid=' . $userid . '" class="fivesmall">Your Profile</a>' .
							 '<a href="./edit_account.php" class="fivewide">Edit Account Settings</a>' .
							 '<a href="./buy.php" class="fivewide">Purchase Squffy Dollars</a>' .
							 '<a href="./refer.php" class="fivesmall">Refer Players</a>' .
							 '<a href="./referred.php" class="fivelast">Referral History</a>';
                    } else {
						echo '<a href="./register.php" class="three">Create Account</a>' .
						 	 '<a href="./activate.php" class="three">Activate Account</a>' .
						 	 '<a href="./reset.php" class="threelast">Reset Password</a>';
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
				if(!isset($forLoggedIn)) { $forLoggedIn = false; }
				if($forLoggedIn && !$loggedin) {
					displayErrors(array("You must be logged in to see this page."));
					include('./includes/footer.php');
					die();
				}
				?>