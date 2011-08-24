				<!--<h1><?php echo $global_error; ?></h1>-->
				</div>
            </div>  
            <div class='clear'></div>  
        </div>
    </div>
    <div id='footer'>
        <a href='about.php'>About Squffies</a>
        &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
        <a href='privacy.php'>Privacy Policy</a>
        &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
        <a href='tos.php'>Terms and Conditions</a>
        &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
        <a href='credits.php'>Credits</a>
        &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
        <a href='#'>Contact Us</a>
	</div>
</body>
</html>

<?php
	mysql_close($con);
	print_gzipped_page();
	//if(isset($mobile_browser) && $mobile_browser > 0) { ob_end_flush(); }
?>