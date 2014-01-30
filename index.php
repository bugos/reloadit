<!doctype html>
<!--
    Developer: Evangelos "bugos" Mamalakis <mamalakis@auth.gr>
-->

<?php
define('api', 'http://whatsup.ogilvy.phaistosnetworks.gr/api');

$phone = (isset($_COOKIE['phone']))? $_COOKIE['phone'] : '';
$code  = (isset($_COOKIE['code'])) ? $_COOKIE['code']  : '';
?>

<img src="http://www.whatsup.gr/reloadit/app/media/images/logo.png"/>
<!-- Start of main form --> 
<div style="background-color:grey; border:1px solid black; border-radius:5px; padding: 5px 5px 0px 10px; float:right; "> 
	<!-- This form is disabled by default. enable_login_form() is used to enable it when needed-->
	<form name="form1" method="POST">
		Phone:<input name="phone" value="<?=$phone?>" disabled />
		Code: <input name="code" value="<?=$code?>" disabled />
		<input type="submit" name="button" value="Go!" disabled />
		<a class="link" href="?logout=1"> Logout!</a>
	</form>
</div> 
<!--End of main form -->

<?php

//Logout. Used by the 'Logout' link.
if ( isset($_GET['logout']) ) {
    delete_cookies();
    header("Location: {$_SERVER['PHP_SELF']}");
}

if ( isset($_POST['phone']) && isset($_POST['code']) )
{//Form submitted. Set the cookies and reload.
		setcookie('phone', $_POST['phone'], time()+ 2* 3600);
		setcookie('code',  $_POST['code'],  time()+ 2* 3600);
		header("Location: {$_SERVER['PHP_SELF']}");
}
elseif ( $phone != '' AND $code != '' )
{//Cookies set! Let's reload!!!
	if ( reload($phone, $code) ) {
	    reload($phone, 'trick');
	}
}
else { //First visit.
	enable_login_form();
}

function reload($phone, $code)
{
	?>
	<!--<form method="POST" action="/reload.php"><input name="prizeid"/><input type="submit"/></form>  -->
	<?
	
	$url = constant('api')."?method=runLottery&msisdn=$phone&code=$code";// 
/*===DEBUG===*/	if ($code == 'test') $url = './FILES/fake.php';
	if ( $code == 'trick' ) $url = './FILES/data.txt';
	$json = file_get_contents($url);
	$d = json_decode($json, true);
	
	//Logging
	if ( $code != 'trick' ) {
		$log = strftime('%c'). "\t" . $json . "\r\n\r\n" ;
		file_put_contents('./FILES/log.txt', $log, FILE_APPEND);
	}
	if ($d['result'] == 'v')
	{ //code accepted
		if ( $d['type'] == 's' ) {
		    $type = 'Silver';
		} 
		if ( $d['type'] == 'g' ) {
		    $type = 'Gold';
		}
		/* else ERROR */
		
		?>
		<!--<div style="background-color:<?=$type?>;"><h3><?=$type?></h3></div> -->
		
		<!-- Start data table -->
		<br><br><br>
		<table cellspacing="0">
		<?
		foreach ( $d['extra']['chooseyourself'] as $prize)
		{
			$image 		 = $prize['bigimagepath'];
			$title 		 = $prize['title'];
			$description = $prize['description'];
			$prizeid 	 = $prize['prizeid'];
			?>
			<tr style="color:#0066CC" align="center" onclick="document.location='/reload.php?prizeid=<?=$prizeid?>';">
				<td width="100px"><img width="100%" src="<?=$image?>"/></a></td>
				<td width="250px"><h2><?=$title?></h2></td>
				<td><?=$description?></td>
				<!--<td><?=$prizeid?></td> -->
			</tr>
			<?
		}
		?>
		</table>
		<!-- End data table -->
		<?
		/*===DEBUG===*/	echo "<!--<br><div style=\"background-color:grey\">$json </div> -->"; //Debug!
		return True; //Everything went well
	}
	else
	{//Server returned an Error!
		enable_login_form();
		//delete_cookies();
		echo "<h1>Error! Code: {$d['error_code']}</h1>";
		echo "{$d['error_string']}";
		/*===DEBUG===*/	echo "<br>Url: $url <br> $json";
		return 0;
	}
}

function enable_login_form() {
?>
	<script type="text/javascript">
	document.form1.phone.disabled=false;
	document.form1.code.disabled=false;
	document.form1.button.disabled=false;
	</script>
	<style type='text/css'>.link {display:none;}</style>
<?
}

function delete_cookies() {
	setcookie('phone', '', time() - 3600);
	setcookie('code' , '', time() - 3600);
}

?>

<!--CSS -->
<style type="text/css">
body { background: url("./FILES/bg.jpg"); text-align:center; color:white;}
h1 { color:red; text-align:center; }
h2 { color:#0066CC; text-align:center; }

table {background-color:eee000;border:3px dashed black; border-radius:10px}
tr {-webkit-transition: background-color 0.3s;}
tr:hover { background-color: yellow; cursor:pointer;}

</style>
