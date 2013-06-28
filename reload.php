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
	//reload variables
	$r_url         = constant('api')."?method=secondProvisioning&msisdn=$phone&code=$code&prizeid=$prizeid";
	$r_json        = file_get_contents($r_url);
	$r_decoded     = json_decode($r_json, true);
	
	//contest opt-in variables
	$opt_url       = constant('api')."?method=optin&msisdn=$phone&code=$code";
	$opt_json      = file_get_contents($opt_url);
	$opt_response  = json_decode($opt_json, true);//['response'];
	
	//Debug!
	echo $r_json . ' ' . $r_url . '<br>';
	echo $opt_json . ' ' . $opt_url;
	
	if (False)//($r_decoded == 1)
	{ //code accepted
		echo '<h2>Reload Succesfull<br></h2>';
		if ($opt_response) //contest submission accepted
			echo '<h3>Contest submission Succesfull</h3>';
	} 
	else 
	{ //code failes
		echo '<h1>Reload Error! Wtf are you trying to do?</h1>
				<a href="\index.php">Go back</a>';
	}
}
else 
{
	echo 'vars not set';
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
