<?php
header("Content-type: text/css");

include('../objects/user.php');
include('../includes/connect.php');	
include('../scripts/account.php');

//Retrieve layout information from session or database
$layout = NULL;
if(isset($_SESSION['layout'])) {
	$layout = $_SESSION['layout'];
} else {
	$query = "SELECT * FROM `layouts` WHERE `is_default` = 'true'";
	if($loggedin && $user->getLayout() != NULL) { $query = 'SELECT * FROM `layouts` WHERE `layout_id` = ' . $user->getLayout();	}
	$result = runDBQuery($query);
	$layout = @mysql_fetch_assoc($result);
	$layout['folder'] = strtolower($layout['layout_name']);
	$_SESSION['layout'] = $layout;
}
?>

/* GENERAL SETTINGS */
body {
  margin:0 0 0 0; 
  background-color: #<?php echo $layout['background_color']; ?>;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 13px; 
  color: #000000;  
  background-image: url(../images/layouts/bg.png); 
  background-repeat: repeat-y; 
  background-position: center;
}

body, html { 
	height: 100%; 
}

.hidden { display: none; }
.invisible { visibility: hidden; }
.bordered { border: 2px black solid; }
.no-border { border: 0px !important; }
.small { font-size: 11px; }
.large { font-size: 15px; }

.width25p { width: 25%; }
.width33p { width: 33%; }
.width50p { width: 50%; }
.width100p { width: 100%; }
.width20 { width: 20px; }
.width80 { width: 80px; }
.width100 { width: 100px; }
.width125 { width: 125px; }
.width150 { width: 150px; }
.width175 { width: 175px; }
.width200 { width: 200px; }
.width300 { width: 300px; }
.width400 { width: 400px; }
.width450 { width: 450px; }

.male { background-color: #F2F2FF; }
.female { background-color: #FFF2F2; }

.div-center { margin: 0px auto; }
.float-left { float: left; }
.float-right { float: right; }
.clear { clear: both; height: 0px; }
.clear-left { clear: left; }

.vertical-top { vertical-align: top; }
.text-center { text-align: center; }
.text-left { text-align: left; }
.text-right { text-align: right; }

.margin-right-small { margin-right: 5px; }
.margin-top-small { margin-top: 5px; }
.margin-bottom-small { margin-bottom: 5px; }
.no-margin { margin: 0px; }
.padding-10 { padding: 10px; }

.thumbnail { 
	width: 50px; 
    height: 50px; 
    margin-bottom: 3px;
}

.npc {
	width: 300px;
    height: 300px;
    border: 2px #000000 solid;
    margin: 0px auto 10px;
}

input, select, textarea { 
	border: 1px #000000 solid; 
}

input.submit-input:hover { 
	background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/buttonbackhover.png); 
}

input.submit-input {
	height: 30px;
    text-transform: uppercase;
    color: #FFFFFF;
    font-weight: bold;
    background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/buttonback.png); 
    background-repeat: repeat-x;
    border: 2px #000000 solid;
}

input.submit-input-disabled {
	height: 30px;
    text-transform: uppercase;
    color: #FFCCCC;
    font-weight: normal;
    background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/buttonbackhover.png); 
    background-repeat: repeat-x;
}

.errors {
	margin-bottom: 10px;
	border: 4px black solid;
	color: #FF0000;
	font-weight: bold;
	font-size: 15px;
	padding: 5px;
	background-color: #770000;
}
.errors a:link, .errors a:visited { color: #FFFFFF !important; }
.errors a:hover { color: #FFDDDD !important; }
.errors a { font-weight: bold; }
.errors ul { margin: 0px !important; }

.small-error {
	color: #FF0000;
	font-style: italic;
}

.success {
	margin-bottom: 10px;
	border: 4px black solid;
	color: #00FF00;
	font-weight: bold;
	font-size: 15px;
	padding: 5px;
	background-color: #007700;
}
.success a:link, .success a:visited { color: #FFFFFF !important; }
.success a:hover { color: #DDFFDD !important; }

.info {
	margin-bottom: 10px;
	border: 4px black solid;
	color: #0000FF;
	font-weight: bold;
	font-size: 15px;
	padding: 5px;
	background-color: #000077;
}

/* MAIN BACKGROUND CONTAINER */
#wrapper {
    background-color: #FFFFFF;
    width: 1000px;
    border-right: 2px #000000 solid;
    border-left: 2px #000000 solid;
	margin: 0px auto -20px;
    height: 100%;
}  

body > #wrapper { 
    min-height: 100%; 
    height: auto; 
}

#content-wrapper { 
    background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/back.jpg); 
    padding-bottom: 20px;
}

/* HOLDS LAYOUT IMAGES */
#left-column { 
    background-color: #7189d3; 
    border-right: 2px #000000 solid;
    float: left;
    width: 131px;
    padding: 0px;
    padding-bottom: -10px;
    margin: 0px;
    background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/side.jpg);  
    background-repeat: no-repeat;
    height: 709px;
}

body > #left-column { 
	min-height: 709px; 
    height: auto;
}

#header {
	width: 100%;
    height: 104px;
    margin: 0px;
    padding: 0px;
    background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/logo.jpg); 
}

/* HOLDS MAIN CONTENT */
#right-column { 
    border-top: 2px #000000 solid;
    background-color: #FFFFFF; 
    float: left;
    width: 867px;
    padding:0px;
    margin:0px;
}

#content { 
	padding: 5px; 
}

#content a {
	font-weight: bold; 
    color: #990000; 
    text-decoration: none; 
}

#content a:hover { color: #000000; }

/* HOLDS MAIN MENU STYLES */
#menu {
	height: 30px;
    background-color: #<?php echo $layout['background_color']; ?>;
    border-bottom: 2px #000000 solid;
}

#menu a {
	height: 30px;
    line-height: 210%;
    padding: 0px;
    width: 133px;
    text-decoration: none;
    text-transform: uppercase;
    text-align: center;
    border-right: 2px #000000 solid;
    margin: 0px;
    color: #FFFFFF;
    font-weight: bold;
    display: inline-block;
    background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/buttonback.png); 
    background-repeat: repeat-x;
}

#menu a:hover { background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/buttonbackhover.png); }
#menu a.active { background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/buttonbackactive.png); }

#menu a.last-link { 
	width: 192px !important; 
    border-right: 0px !important; 
}

/* HOLDS SUB MENU STYLES */
#submenu {
	height: 30px;
    background-color: #<?php echo $layout['background_color']; ?>;
    border-bottom: 2px #000000 solid;
}

#submenu a {
	height: 30px;
    line-height: 210%;
    padding: 0px;
    text-decoration: none;
    text-transform: uppercase;
    text-align: center;
    border-right: 2px #000000 solid;
    margin: 0px;
    color: #FFFFFF;
    font-weight: bold;
    display: inline-block;
    background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/subbuttonback.png); 
    background-repeat: repeat-x;
}

#submenu a:hover { background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/subbuttonbackhover.png); } 

#submenu a.three { width: 287px; }
#submenu a.threelast { 
	width: 289px; 
    border-right: 0px;
}

#submenu a.fivesmall { width: 130px; }
#submenu a.five { width: 175px; }
#submenu a.fivewide { width: 220px; }
#submenu a.fivelast { 
	width: 159px;
    border-right: 0px;
}

#submenu a.six { width: 143px; }
#submenu a.sixlast { 
	width: 142px;
    border-right: 0px;
}

#submenu a.seven { width: 122px; }
#submenu a.sevenlast { 
	width: 123px;
    border-right: 0px;
}

/* SIDE MENU STYLES */
#side-menu {
    width: 110px;
    margin-top: 17px;
    margin-left: 10px;
    height: 230px;
    overflow: hidden;
    font-weight: bold;
}

#side-menu input { 
	width: 100px;
	margin: 0px;
    margin-bottom: 10px;
    font-size: 13px;
}

#side-menu form { 
	margin: 0px; 
}

#time {
	font-size: 10px;
    margin-top: 34px;
    margin-left: 2px;
    text-align: center;
}

/* FOOTER INFORMATION */
#footer {
    height: 23px;
    background-color: #7189d3;
    margin: 0px auto;
    width: 1000px;
    border-right: 2px #000000 solid;
    border-left: 2px #000000 solid;
    bottom: 0px;
    color: #FFFFFF; 
    font-size:11px; 
    text-align:center;
    line-height: 215%;
    background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/footer.jpg);
}

#footer a {
	color: #000099;
    text-decoration: none;
    font-weight: bold;
}

#footer a:hover { color: #0000DD; }

#footer a:visited {
	color: #000099;
    font-weight: normal;
}

/* Content displaying stuff. TEMP */
.content-table {
	border: 0px;
    width: 100%;
}

.content-header {
	height: 30px;
    font-size: 18px;
    padding: 0px;
    text-decoration: none;
    text-transform: uppercase;
    text-align: center;
    border: 2px #000000 solid;
    margin: 0px;
    color: #000000;
    font-weight: bold;
    background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/titleback.png); 
    background-repeat: repeat-x;
}

.content-subheader {
	height: 30px;
    background-image: url(../images/layouts/<?php echo $layout['folder']; ?>/titleback.png); 
    background-repeat: repeat-x;
    text-transform: uppercase;
    text-align: center;
    border: 2px #000000 solid;
    margin: 0px;
    padding: 0px;
}
