<!--Allign main form right!!!

<!-- Start of main form --> 
<div style="position: absolute; top: 0px;"> 
	<!-- This form is disabled by default. enable_form() is used to enable it when needed-->
	<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<? if (isset($_COOKIE['phone'])) echo "<a href=\"{$_SERVER['PHP_SELF']}?logout=1\"> Delete cookies</a> &nbsp;&nbsp;"?>
		Phone: <input type="text" name="phone" value="<? if (isset($_COOKIE['phone'])) echo $_COOKIE['phone']?>" disabled="disabled"/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Code: <input type="text" name="code" value="<? if (isset($_COOKIE['code'])) echo $_COOKIE['code']?>" disabled="disabled"/>
		<input type="submit" name="button" value="Reload it!" disabled="disabled"/>
	</form>
</div> 
<br>
<!--End of main form -->


<?php
define('api', 'http://whatsup.ogilvy.phaistosnetworks.gr/api');

//Used by the Delete cookies link
if (isset($_GET['logout'])) {delete_cookies(); header("Location: {$_SERVER['PHP_SELF']}");}


if (isset($_COOKIE['phone']) AND isset($_COOKIE['code']))
{//Cookies set! Let's reload!!!
	reload($_COOKIE['phone'],$_COOKIE['code']);
}
elseif (isset($_POST['phone']) AND isset($_POST['code']))
{//Form submitted. Let's set the cookies.
		setcookie('phone', $_POST['phone'], time()+ 4* 3600);
		setcookie('code',  $_POST['code'],  time()+ 4* 3600);
		header("Location: {$_SERVER['PHP_SELF']}"); //reload
}
else 
{ //First visit.
		enable_form();
}


function reload($phone, $code)
//tries to reload with the code
{
	$url ='fake.php';//constant('api')."?method=runLottery&msisdn=$phone&code=$code";// 'fake.txt';//
	$json = file_get_contents($url);
	$d = json_decode($json, true);

	if ($d['result'] == 'v')
	{ //code accepted
		
		if ($d['type'] == 's') {$type='Silver';} else {$type='Gold';}
		echo "<h3>Κατηγορία δώρου: $type </h3><br>";
		
		?>
		<!-- Start data table -->
		<table width="90%" align="center">
		<tr>
		<?
		for($i=0; $i<3; $i++)
		{
			?>
			<td valign="top" width="33%">
			<table style="background-color:yellow;border:3px dashed black;">
			<tr><td align="center"><h2><? echo "{$d['extra']['chooseyourself'][$i]['title']}"?></h2></td></tr>
			<tr><td align="center"><img src="<? echo "{$d['extra']['chooseyourself'][$i]['bigimagepath']}"?>"/></td></tr>
			<tr><td align="center"><? echo "{$d['extra']['chooseyourself'][$i]['description']}"?></td></tr>
			<tr><td align="center"><? echo "{$d['extra']['chooseyourself'][$i]['prizeid']}"?></td></tr>
			</td>
			</table>
			<?
		}
		?>
		</tr>
		</table>
		<!-- End data table -->
		<?php
	}
	else //Error!
	{
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
	<?
}

function delete_cookies()
{
	setcookie('phone', '', time()-3600);
	setcookie('code',  '', time()-3600);
}

function debug($url, $json)
{
echo '<div style="position: absolute; bottom: 0px;">';
echo "Request: <b>$url</b> <br>";
echo "Detailed JSON:<br>$json";
echo '</div>';
}

?>

<!--CSS -->
<style type="text/css">
body { background-color:#9966FF; text-align:center; }
h1 { color:purple; text-align:center; }
h2 { color:green; text-align:center; }
h3 { color:silver;}
p { color:#C0C0C0;}
</style>
