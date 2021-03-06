<?php
require "vendor/autoload.php";
require "config.php";
require "functions.php";
use Vimeo\Vimeo;

define('C_ID','fab1241c976814019cbe050a4edea77a7a72a8fc');
define('C_SECRET','EAZBGBk7eMpIf1h14Bpsa2pBk36EnYIfWh6nxdZaEMLHB1GSPUG22aUXHDf0zHWF/snaDzxnlxKgO3YZ0dW7vyqoLHw58LwNcrH3BEV7VBKcfeeW3BfAFx2PPcorId9y');
define('TOKEN','0b6e092eeee91ed2c037b666719e788c');

$client = new Vimeo(C_ID, C_SECRET, TOKEN);
$curi = getthis($con,'class1','uri','id',3);

if(isset($_POST['submit'])){

	$title = $_POST['title'];
	$desc = $_POST['desc'];
	$class_id = $_POST['class_id'];
	$number = $_POST['number'];
	$month = 'January';

	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["videofile"]["name"]);
	if (move_uploaded_file($_FILES["videofile"]["tmp_name"], $target_file)) {
		$file_name = $target_file;
		$uri = $client->upload($file_name, array(
	  	"name" => $title,
	  	"description" => $desc
		));

		if(mysqli_query($con,"INSERT INTO class1(class_id, Month, rec_num, uri) VALUES('$class_id','$month','$number','$uri')")){
			echo "done <br>";
		}

		$response = $client->request($uri . '?fields=link');
		echo "Your video link is: " . $response['body']['link'];
	}
}

if(isset($_POST['update'])){
	$ntitle = $_POST['ntitle'];
	$ndesc = $_POST['ndesc'];
	//echo $uri."<br>".$ntitle."<br>".$ndesc;
	
	$client->request($curi, array(
	  'name' => $ntitle,
	  'description' => $ndesc,
	), 'PATCH');

	echo 'The title and description for ' . $curi . ' has been edited.';
}

if(isset($_POST['protect'])){

	$client->request($curi, array(
	  'privacy' => array(
	    'view' => 'disable'
	  )
	), 'PATCH');

	echo $curi . 'Protected';
}

if(isset($_POST['whitelist'])){

	$domain = $_POST['domain'];
	$client->request($curi . '/privacy/domains/'.$domain, array(
	  'privacy' => array(
	    'embed' => 'whitelist'
	  )
	), 'PUT');

	echo $curi . ' will only be embeddable on http://'.$domain;

}

if(isset($_POST['delete'])){
	$domain = $_POST['del_domain'];
	$client->request($curi. '/privacy/domains/'.$domain, array(
	  'privacy' => array(
	    'embed' => 'whitelist'
	  )
	), 'DELETE');

	echo $curi . ' embed permission deleted on http://'.$domain;
}
//echo '<pre>';
//print_r($client->request($curi. '/privacy/domains'));


if(isset($_POST['thumb'])){
	$result3 = $client->uploadImage($curi. '/pictures', 'uploads/1-2.webp', true);
	echo 'Thubnail uploaded successfully';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Vimeo Test</title>
</head>
<body>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
		<input type="file" name="videofile">
		<input type="text" name="title" placeholder="Title">
		<input type="text" name="desc" placeholder="description">
		<input type="text" name="class_id" placeholder="Class ID">
		<input type="number" name="number" min='1'>
		<input type="submit" name="submit">
	</form>

	<h2>Change title and description</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<input type="text" name="ntitle" placeholder="new title">
		<input type="text" name="ndesc" placeholder="new decription">
		<input type="submit" name="update" value="update">
	</form>
	<h2>Protect</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<input type="submit" name="protect" value="protect">
	</form>

	<h2>Whitelist Domain</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<input type="text" name="domain" placeholder="example.com">
		<input type="submit" name="whitelist" value="whitelist">
	</form>

	<h2>Delete Domain</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<input type="text" name="del_domain" placeholder="example.com">
		<input type="submit" name="delete" value="delete">
	</form>

	<h2>Update thumb</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
		<input type="submit" name="thumb" value="update">
	</form>
</body>
</html>