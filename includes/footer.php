				<!--<h1><?php echo $global_error; ?></h1>-->
				</div>
            </div>  
            <div class='clear'></div>  
        </div>
    </div>
    <div id='footer'>
        <a href='#'>About Squffies</a>
        &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
        <a href='#'>Privacy Policy</a>
        &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
        <a href='#'>Terms and Conditions</a>
        &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
        <a href='#'>Credits</a>
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