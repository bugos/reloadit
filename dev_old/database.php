

<?
phpinfo();
$f = json_decode(file_get_contents('fake.php'), true);
$d = json_decode(file_get_contents('data.php'), true);

foreach($f['extra']['chooseyourself'] as $prize)
{
	$image 		 = $prize['bigimagepath'];
	$title 		 = $prize['title'];
	$description = $prize['description'];
	$prizeid 	 = $prize['prizeid'];
	if (!in_array($prize,$d['extra']['chooseyourself'])) {
		array_push($d['extra']['chooseyourself'], $prize);
	}
}

print_r($d['extra']['chooseyourself']);
echo "\n\n";
echo json_encode($d);
#file_put_contents('data.php', json_encode($d, JSON_UNESCAPED_UNICODE));

?>
