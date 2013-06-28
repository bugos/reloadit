<?php
define('api', 'http://whatsup.ogilvy.phaistosnetworks.gr/api');
?>

<div style="position: absolute; top: 0px;">
<a href= <?php echo $_SERVER['PHP_SELF']?>?logout=1 > Delete cookies </a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Phone: <b><?php if (isset($_COOKIE['phone'])) echo $_COOKIE['phone']?> </b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Code: <b><?php if (isset($_COOKIE['code'])) echo $_COOKIE['code']?> </b>
</div> <br>

<style type="text/css">
body { background-color:#9966FF; text-align:center; }
h1 { color:purple; text-align:center; }
h2 { color:green; text-align:center; }
p { color:#C0C0C0;}
</style>

<?php
if (isset($_GET['logout'])) {logout();header("Location: {$_SERVER['PHP_SELF']}");}

if (!isset($_COOKIE['phone']) OR !isset($_COOKIE['code']))
	if (!isset($_POST['phone']) OR !isset($_POST['code']))
	{ //first visit
		show_form();
	}
	else 
	{//form submitted
		setcookie('phone', $_POST['phone'], time()+ 4* 3600);
		setcookie('code',  $_POST['code'],  time()+ 4* 3600);
		header("Location: {$_SERVER['PHP_SELF']}"); 
	}
else
{//cookies set
	submit($_COOKIE['phone'],$_COOKIE['code']);
}

function submit($phone, $code)
//tries to reload with the code
{
	$url = 'fake.php';//constant('api')."?method=runLottery&msisdn=$phone&code=$code";
	$json = file_get_contents($url);
	$d = json_decode($json, true);

	if ($d['result'] == 'v')
	{ //code accepted
		echo "<h2>Success!!!</h2>";
		echo "<p>Detailed JSON:<br>$json</p>";
	}
	else //error!
	{
		logout();
		show_form();
		echo "<h1>Error! <br> Code: {$d['error_code']}</h1>";
		echo "{$d['error_string']}";
		echo "<p>Detailed JSON:<br>$json</p>";
	}
}

function logout()
{
	setcookie('phone', '', time()-3600);
	setcookie('code',  '', time()-3600);
	//header("Location: {$_SERVER['PHP_SELF']}"); 
}

function show_form()
{
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	Phone: <input type="text" name="phone" value="<?php if (isset($_COOKIE['phone'])) echo $_COOKIE['phone']?>"/><br />
	Code: <input type="text" name="code" value="<?php if (isset($_COOKIE['code'])) echo $_COOKIE['code']?>"/><br />
	<input type="submit" value="Reload it!" />
	</form>
	<?php
}

?>