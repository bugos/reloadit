<!--Allign main form right!!!-->

<?
$phone = (isset($_COOKIE['phone']))? $_COOKIE['phone'] : '';
$code = (isset($_COOKIE['code']))? $_COOKIE['code'] : '';
define('api', 'whatsup.ogilvy.phaistosnetworks.gr/api');
?>
<link rel="shortcut icon" href="whatsup.gr/sites/default/files/whatsupv3_favicon.ico" type="image/x-icon" />

<!-- Start of main form --> 
<div style="background-color:grey; border:2px solid black; padding: 5px 5px 0px 10px; float:right;"> 
	<!-- This form is disabled by default. enable_form() is used to enable it when needed-->
	<form name="form1" method="POST">
		Phone:<input type="text" name="phone" value="<?echo "$phone"?>" disabled="disabled"/>
		Code:<input type="text" name="code" value="<?echo "$code"?>" disabled="disabled"/>
		<input type="submit" name="button" value="Reload!" disabled="disabled"/>
		<a class="link" href="?logout=1"> Logout!</a>
	</form>
</div> 
<!--End of main form -->

<?php
//Logout. Used by the 'Logout' link.
if (isset($_GET['logout'])) {delete_cookies(); header("Location: {$_SERVER['PHP_SELF']}");}

if ($phone!='' AND $code!='')
{//Cookies set! Let's reload!!!
	reload($phone,$code);
}
elseif (isset($_POST['phone']) AND isset($_POST['code']))
{//Form submitted. Set the cookies and reload.
		setcookie('phone', $_POST['phone'], time()+ 4* 3600);
		setcookie('code',  $_POST['code'],  time()+ 4* 3600);
		header("Location: {$_SERVER['PHP_SELF']}");
}
else { //First visit.
		enable_form();
}


function reload($phone, $code)
//tries to reload with the code
{
	$url = 'fake.php';//constant('api')."?method=runLottery&msisdn=$phone&code=$code";// 'fake.php';//
	$json = file_get_contents($url);
	$d = json_decode($json, true);

	if ($d['result'] == 'v')
	{ //code accepted
		
		if ($d['type'] == 's') {$type='Silver';} else {$type='Gold';}
		?>
		<div style="background-color:grey;"><h3>Κατηγορία δώρου: <?echo "$type"?></h3></div>
		
		<!-- Start data table -->
		<table width="90%" align="center">
		<?
		$expressions = array(
			'$prize[\'title\']',
			'"<img src=\"".$prize[\'bigimagepath\']. "\">"',
			'$prize[\'description\']' );
			
		foreach ($expressions as $expression)
		{
			?><tr><?
			foreach ($d['extra']['chooseyourself'] as $prize)
				?></td><? print "$expression" ?></td><?
			?></tr><?
		}
		?>
		</table>
		<!-- End data table -->
		<?
	}
	else 
	{//Error!
		enable_form();
		delete_cookies();
		echo "<h1>Error! <br> Code: {$d['error_code']}</h1>";
		echo "{$d['error_string']}";
		debug($url, $json);
	}
}

function enable_form()
{
?>
<script type="text/javascript">
document.form1.phone.disabled=false;
document.form1.code.disabled=false;
document.form1.button.disabled=false;
</script>

<style type='text/css'>.link {display:none;}</style>
<?
}

function delete_cookies()
{
	setcookie('phone', '', time()-3600);
	setcookie('code',  '', time()-3600);
}

function debug($url, $json)
{?>
	<div style="padding:5px 15px; position: absolute; bottom: 0px; border: 3px red solid; border-radius:25px;">
		<h3>Debug:</h3>
		Request: <b><?echo "$url"?></b> <br>
		Detailed JSON:<br><?echo "$json"?>
	</div>
<?}

?>
<!--{background-color:url(/bg.jpg)/*#9966FF*/; text-align:center; }-->
<!--CSS -->
<style type="text/css">
body { background: url("../bg.jpg"); text-align:center; }
h1 { color:purple; text-align:center; }
h2 { color:#8dbdd8; text-align:center; }
table.hov tbody:hover { background: green;}
td { background-color:yellow; border:2px black;}
</style>
