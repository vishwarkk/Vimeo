<?php 
$bonusMinAmount = 10000;
$bonuspercent = 10;
$merchantsecret = '4Pb27BG3uGf8cNscFjiqkp4Uv1odjGt4T8MMz9WWiLmE';
$merchantid = "1219769";
//bank details
$bankname = "People's Bank - Homagama";
$banknumber = "49400760264559";
$bankholder = "Delegate";

function getDir(){
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
$filedir = $protocol.$_SERVER['SERVER_NAME'] .dirname($_SERVER['PHP_SELF']); 
return $filedir;
}


function clean($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function email_exist($email, $con){
	$select = mysqli_query($con, "SELECT * FROM user_info WHERE user_email = '".$email."'");
if(mysqli_num_rows($select)) {
    return true;
}else{
	return false;
}}

function pk_exist($id, $con){
	$select = mysqli_query($con, "SELECT * FROM tute_packages WHERE pk_id = '".$id."'");
if(mysqli_num_rows($select)) {
    return true;
}else{
	return false;
}}

function classid_exist($id, $con){
	$select = mysqli_query($con, "SELECT * FROM classes WHERE class_id = '".$id."'");
if(mysqli_num_rows($select)) {
    return true;
}else{
	return false;
}}

function email_exist2($email, $con){
	$select = mysqli_query($con, "SELECT * FROM staff_info WHERE email = '".$email."'");
if(mysqli_num_rows($select)) {
    return true;
}else{
	return false;
}}

function tele_exist($tele, $con){
	$select = mysqli_query($con, "SELECT * FROM user_info WHERE user_tele = '".$tele."'");
if(mysqli_num_rows($select)) {
    return true;
}else{
	return false;
}}


function logged_in($con){
	$query = mysqli_query($con, "SELECT * FROM  class_cycle");
	date_default_timezone_set('Asia/Colombo');
	while($rows = mysqli_fetch_array($query)){
		$tdate = date("Y-m-d");
		$ex_date = $rows['next_cycle'];
		$cls_id = $rows['class_id'];
		if ($ex_date <= $tdate ){
    		$ccycle = substr($ex_date, 0, -3);
    		$ncycle = endCycle($ex_date, 1);
    		$cycle_id = $cls_id."^".$ccycle;
    		mysqli_query($con, "UPDATE class_cycle SET current_cycle = '$ccycle', next_cycle = '$ncycle', cycle_id = '$cycle_id' WHERE class_id = '$cls_id'");
    		mysqli_query($con, "UPDATE classes SET cycle_id = '$cycle_id' WHERE class_id ='$cls_id' ");
			
		}
	}
	
	if (!isset($_SESSION['mail'])) {
		return false;
	}else{
		$logemail = $_SESSION['mail'];
		if (email_exist($logemail,$con)) {
			return true;
		}else{
			return false;
		}
	}

}
function logged_in_admin($con){
	$query = mysqli_query($con, "SELECT * FROM  class_cycle");
	date_default_timezone_set('Asia/Colombo');
	while($rows = mysqli_fetch_array($query)){
		$tdate = date("Y-m-d");
		$ex_date = $rows['next_cycle'];
		$cls_id = $rows['class_id'];
		if ($ex_date <= $tdate ){
    		$ccycle = substr($ex_date, 0, -3);
    		$ncycle = endCycle($ex_date, 1);
    		$cycle_id = $cls_id."^".$ccycle;
    		mysqli_query($con, "UPDATE class_cycle SET current_cycle = '$ccycle', next_cycle = '$ncycle', cycle_id = '$cycle_id' WHERE class_id = '$cls_id'");
    		mysqli_query($con, "UPDATE classes SET cycle_id = '$cycle_id' WHERE class_id ='$cls_id' ");
			
		}
	}

	if (!isset($_SESSION['mail'])) {
		return false;
	}else{
		$logemail = $_SESSION['mail'];
		if (email_exist2($logemail,$con)) {
			return true;
		}else{
			return false;
		}
	}

}

function esc($data){

	return addslashes($data);
}

function gen_otp(){
	$otpnum = rand(100000,999999);
	return $otpnum;
}
function gen_num(){
	$num = rand(10,99);
	return $num;
}
function pk_request($id,$index,$pkg,$con){

	$result = mysqli_query($con, "SELECT student_id, pk_id FROM tute_request WHERE (student_id = '$id' AND pk_id = '$pkg') OR (ad_num = '$index' AND pk_id = '$pkg')");

	if(mysqli_num_rows($result) != 0){
		return false;
	}else{
		return true;
	}

}

function is_exist($con,$table,$column,$to_fetch){
	$select = mysqli_query($con, "SELECT * FROM $table WHERE $column = '".$to_fetch."'");
if(mysqli_num_rows($select)) {
    return true;
}else{
	return false;
}}
function add_months($months, DateTime $dateObject) 
    {
        $next = new DateTime($dateObject->format('Y-m-d'));
        $next->modify('last day of +'.$months.' month');

        if($dateObject->format('d') > $next->format('d')) {
            return $dateObject->diff($next);
        } else {
            return new DateInterval('P'.$months.'M');
        }
    }

function endCycle($d1, $months)
    {
        $date = new DateTime($d1);

        // call second function to add the months
        $newDate = $date->add(add_months($months, $date));

        // goes back 1 day from date, remove if you want same day of month
        $newDate->sub(new DateInterval('P1D')); 

        //formats final date to Y-m-d form
        $dateReturned = $newDate->format('Y-m-d'); 
        $dateReturned = date('Y-m-d', strtotime($dateReturned. ' + 1 days'));
        return $dateReturned;
    }

function getthis($con,$table,$tofetch,$comcol,$comdata){
	$get = mysqli_query($con,"SELECT $tofetch FROM $table WHERE $comcol ='$comdata'");
	if(mysqli_num_rows($get) > 0 ){
	$row = mysqli_fetch_array($get);
	$data = $row["$tofetch"];
	return $data;
}else{
	return "null";
}
}
function updatethis($con,$table,$tosetcol,$colval,$tocomcol,$tocomval){
	$sql = "UPDATE $table SET $tosetcol='$colval' WHERE $tocomcol='$tocomval'";
	if(mysqli_query($con,$sql)){
		return true;
	}else{
		false;
	}
}


function update_wallet_balance($con,$student_id){
	$query = mysqli_query($con,"SELECT * FROM wallet_deposites WHERE status ='approved' AND added_balance='not added' AND student_id='$student_id'");
	while($row=mysqli_fetch_array($query)){
		$student_id = $row['student_id'];
		$dep_amounttemp = $row['dep_amount'];
		$bonus = $row['bonus'];
		$dep_amount = $dep_amounttemp+$bonus;
		$query2 = mysqli_query($con,"SELECT * FROM wallet_balance WHERE student_id = '$student_id'");
		$row2 = mysqli_fetch_array($query2);
		$total_amount = $row2['total_amount'];
		$balance = $row2['balance'];
		$newtotal = $total_amount + $dep_amount;
		$newbalance = $balance + $dep_amount;
		mysqli_query($con, "UPDATE wallet_deposites SET added_balance='added' WHERE student_id='$student_id'");
		mysqli_query($con, "UPDATE wallet_balance SET total_amount='$newtotal', balance='$newbalance' WHERE student_id='$student_id'");
	}
}
function update_wallet_users($con,$student_id){
	if(is_exist($con,'wallet_deposites','student_id',$student_id)){
		$query2 = mysqli_query($con,"SELECT * FROM wallet_balance WHERE student_id ='$student_id'");
		if(mysqli_num_rows($query2)==0){
			$query3 = mysqli_query($con,"INSERT INTO wallet_balance(student_id,total_amount,balance) VALUES('$student_id','0','0')");
		}
	}
	update_wallet_balance($con,$student_id);
}

function gettoken(){
   
    $request_url = "https://sandbox.payhere.lk/merchant/v1/oauth/token";
    $headers = array(
        "authorization: Basic NE9WeDNlc0d3azQ0SkJ2V21FcEVvUDNQUDo4Z2ZEeEVPdWNsZzhYMkxRdDdhTVpaOG00TjBRQXZSWnM0T1Z5ZmRYZGhvdA==",
    );


$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $request_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>  "grant_type=client_credentials",
  CURLOPT_HTTPHEADER => $headers,
  ),
);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
  echo "cURL Error #:" . $err;
} else {
    $tokendata =  json_decode($response);
    $token = $tokendata->access_token;
    return $token;
}}

function getorderdetails($o_id){
  $order_id = $o_id;
  $token = gettoken();
  $request_url = "https://sandbox.payhere.lk/merchant/v1/payment/search?order_id=$order_id";
  $headers = array(
    "Authorization: Bearer ".$token,
     "Content-Type: application/json",
  );

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $request_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => $headers,
  ),
);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
  echo "cURL Error #:" . $err;
} else {
    return json_decode($response);
    
}

}

function refund($p_id,$desc){
  $payment_id = $p_id;
  $decription = $desc;
  $token = gettoken();
  $request_url = "https://sandbox.payhere.lk/merchant/v1/payment/refund";
  $headers = array(
    "Authorization: Bearer ".$token,
     "Content-Type: application/json",
  );
$body = array(
	'payment_id' => $p_id,
	'description' => $decription
);
$fieldsArr = json_encode($body);

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $request_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>  $fieldsArr,
  CURLOPT_HTTPHEADER => $headers,
  ),
);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
  echo "cURL Error #:" . $err;
} else {
    return json_decode($response);
    
}

}

function deleteDeclinedClass($con,$cycle_id,$student_id){
	$enrolled_classes = getthis($con,'enrolled_class','classes','student_id',$student_id);
	$classArr = explode("&",$enrolled_classes);
	if(in_array($cycle_id,$classArr)){
		$new_classes = "";
		foreach($classArr as $value){
			if($value != $cycle_id AND $value != ""){
				$new_classes .= $value."&";
			}
		}

		updatethis($con,'enrolled_class','classes',$new_classes,'student_id',$student_id);

	}

}

function getClassState($conpay,$cycle_id,$student_id){
	$classArr = explode('^',$cycle_id);
	$class_id = $classArr[0];
	$cycle = $classArr[1];

	$sql = "SELECT status FROM $class_id WHERE student_id='$student_id' AND cycle='$cycle' AND (status='approved' OR status='pending')";
	$result = mysqli_query($conpay,$sql);
	$row = mysqli_fetch_array($result);
	$status = $row['status'];
	return $status;
}

function CheckAccess()
{
  //allowed IP. Change it to your static IP
  
  $ip = $_SERVER['REMOTE_ADDR'];
  $ipArr = array('123.231.104.244','127.0.0.1','123.231.111.121','112.134.192.214');
  foreach($ipArr as $allowedip){
	if($ip == $allowedip){
		return true;
	}
  }
  return false;
  
}

function getmonth($cycle){
	$monthNum = substr($cycle, -2, 2);
	$months = array("01"=>"January", "02"=>"February", "03"=>"March", "04"=>"April", "05"=>"May", "06"=>"June", "07"=>"July", "08"=>"August", "09"=>"September", "10"=>"October", "11"=>"November", "12"=>"December");
	$monthName = $months[$monthNum];
	return $monthName;
	
}

function is_enrolled($con,$student_id,$cycle_id){
	$enrolled_classes = getthis($con,'user_info','enrolled_class','student_id',$student_id);
	$classArr = explode("&",$enrolled_classes);
	unset($classArr[count($classArr)-1]);
	if(in_array($cycle_id,$classArr)){
		return true;
	}else{	
		return false;
	}
}

function in_cart($con,$class_id,$student_id){
	$value = false;
	if(is_exist($con,'cart','student_id',$student_id)){
        $items = getthis($con,'cart','items','student_id',$student_id);
        if($items != ""){
            $itemsArr = explode("&",$items);
            unset($itemsArr[count($itemsArr)-1]);
            if(in_array($class_id,$itemsArr)){
                $value = true;
            }

        }
	}
	return $value;
}
function is_cls_valid($con,$clsIDs){
	$clsArr = explode("&",$clsIDs);
	unset($clsArr[count($clsArr)-1]);
	foreach($clsArr as $clsId){
		if(!is_exist($con,'classes','cycle_id',$clsId)){
			return false;
			exit;
		}

	}
	return true;
	exit;
}
?>