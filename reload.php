<?
$phone   = (isset($_COOKIE['phone']))? $_COOKIE['phone'] : '';
$code    = (isset($_COOKIE['code']))? $_COOKIE['code'] : '';
$prizeid = (isset($_GET['prizeid']))? $_GET['prizeid'] : '';
define('api', 'http://whatsup.ogilvy.phaistosnetworks.gr/api');
?>

<div style="background-color:black">

<?
if ($phone!='' AND $code!='')
{
	//reload and fetch the result
	$r_url         = constant('api')."?method=secondProvisioning&msisdn=$phone&code=$code&prizeid=$prizeid";
	$r_json        = file_get_contents($r_url);
	$r_decoded     = json_decode($r_json, true);
	
	//contest opt-in and fetch the result
	$opt_url       = constant('api')."?method=optin&msisdn=$phone&code=$code";
	$opt_json      = file_get_contents($opt_url);
	$opt_decoded  = json_decode($opt_json, true);
	
	//Debug!
	echo $r_json . ' ' . $r_url . '<br>';
	echo $opt_json . ' ' . $opt_url;
	
	
	//Output
	if ($r_decoded['response'] == 1)
	{ //code accepted
		echo '<h2>Reload Succesfull<br></h2>';
	} 
	else if ($r_decoded['response'] == 0)
	{ //code failes
		echo '<h1>Reload Error! Wtf are you trying to do?</h1>
				<a href="\index.php">Go back</a>';
	}
	if ($opt_decoded['response'] == 1) //contest submission accepted
			echo '<h3>Contest submission Succesfull</h3>';
	else if ($opt_decoded['response'] == 0)
			echo '<h1>Contest submission Failed!</h1>';
}

else 
{
	echo 'vars not set. how did you get here?';
	//echo '<meta http-equiv="REFRESH" content="0;url=./index.php">'
}

?>

</div>

<!--CSS -->
<style type="text/css">
body { background: url("./bg.jpg"); text-align:center; color:white;}
h1 { color:red; }
h2 { color:green;}
h3 { color:green;}
</style>
