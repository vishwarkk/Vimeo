<?php
require "vendor/autoload.php";
use Vimeo\Vimeo;

define('C_ID','fab1241c976814019cbe050a4edea77a7a72a8fc');
define('C_SECRET','EAZBGBk7eMpIf1h14Bpsa2pBk36EnYIfWh6nxdZaEMLHB1GSPUG22aUXHDf0zHWF/snaDzxnlxKgO3YZ0dW7vyqoLHw58LwNcrH3BEV7VBKcfeeW3BfAFx2PPcorId9y');
define('TOKEN','0b6e092eeee91ed2c037b666719e788c');

$client = new Vimeo(C_ID, C_SECRET, TOKEN);

if(isset($_POST['submit'])){

	$title = $_POST['title'];
	$desc = $_POST['desc'];

	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["videofile"]["name"]);
	if (move_uploaded_file($_FILES["videofile"]["tmp_name"], $target_file)) {
		$file_name = $target_file;
		$uri = $client->upload($file_name, array(
	  	"name" => $title,
	  	"description" => $desc
		));

		echo "Your video URI is: " . $uri;
	}
}







?>
<!DOCTYPE html>
<html>
<head>
	<title>Vimeo Test</title>
</head>
<body>
	<form method="POST" action="#" enctype="multipart/form-data">
		<input type="file" name="videofile">
		<input type="text" name="title" placeholder="Title">
		<input type="text" name="desc" placeholder="description">
		<input type="submit" name="submit">
	</form>
</body>
</html>