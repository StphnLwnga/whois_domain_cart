<?php
	session_start();
	require'db.php';

  if (($_SESSION['userAdded'] && $_SESSION['updatedOrders']) || $_SESSION['userPresent']) {
		$_SESSION['loggedIn'] = TRUE;
    header("refresh: 3; url = https://beta.slashdotlabsprojects.com/cwhoiscart/dashboard.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
<title></title>
</head>
<body>
<div class="container">
<?php
	//var_dump($_GET);
	// var_dump($_SESSION);
?>
<div class="row">
<?php
// PASS USER DETAILS TO TABLE IF USER DOESN'T ALREADY EXIST
// RETRIEVE USER DETAILS i.e. USER ID
// PASS ORDER DETAILS TO DB, txncode FROM IPAY PARAMS AS OrderId

if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $fname = $_SESSION['fname'];
  $lname = $_SESSION['lname'];
  $password = $_SESSION['password'];
  $pass_hash = md5($password);
  $phone = $_SESSION['tel'];
  $address = $_SESSION['str1'];
  $city = $_SESSION['city'];
  $country = $_SESSION['country'];
  $organisation = $_SESSION['org'];

  $email= mysqli_real_escape_string($db, $email);
  $fname= mysqli_real_escape_string($db, $fname);
  $lname= mysqli_real_escape_string($db, $lname);
  $pass_hash= mysqli_real_escape_string($db, $pass_hash);
  $phone = mysqli_real_escape_string($db, $phone);
  $address= mysqli_real_escape_string($db, $address);
  $city= mysqli_real_escape_string($db, $city);
  $country= mysqli_real_escape_string($db, $country);
  $organisation= mysqli_real_escape_string($db, $organisation);

    //Check if the user already exist
  $sql = "SELECT * FROM domaincart_user WHERE email='$email'";;
  $result  =  $db->query($sql);

  if (mysqli_num_rows($result) > 0) {
      // If user already exists
    print "<h3>Welcome back ".$fname." ".$lname."</h3>";
    $_SESSION['userPresent'] = TRUE;
  } else {
      // Add user to users table
    $query = $organisation == "" ?
      "INSERT INTO `domaincart_user` (UserID, dregistered, fname, lname, email, password, phone, address, city, country) VALUES (NULL, CURRENT_TIMESTAMP, '$fname', '$lname', '$email', '$pass_hash', '$phone', '$address', '$city', '$country')" :
      "INSERT INTO `domaincart_user` VALUES (NULL, CURRENT_TIMESTAMP, '$fname', '$lname', '$email', '$pass_hash', '$phone', '$address', '$city', '$country', '$organisation')";

    $results = mysqli_query($db, $query);

    if ($results) {
      print "<h5>Sign Up Complete. Confirm your registration via the email address you signed up with.</h5>";
      $_SESSION['newuser'] = TRUE;
    } else {
      print "Could not complete your registration at this time.";
    }
    $_SESSION['userAdded'] = TRUE;
  }
}

  $hostingdesc = $_SESSION['carthosting'];
  $hostingarr = explode("(",$hostingdesc);
  $hostingplan = trim($hostingarr[0], " ");

  $nameservers = $_SESSION['cartdomain'];
  $currency = $_SESSION['curr'];

  $orderdate=date("Y-m-d h:i:s");
  $expirydate = date("Y-m-d h:i:s", strtotime('+1 years'));

  $status  = $_GET['status'];
  $id = $_GET['id'];
  $invoicenum = $_GET['ivm'];
  $txncd = $_GET['txncd'];
  $msisdn_id = $_GET['msisdn_id'];
  $msisdn_idnum = $_GET['msisdn_idnum'];
  $cardmask = $_GET['card_mask'];
  $tokenemail = $_GET['tokenemail'];
  $merchant = $_GET['tokenid'];
  $channel = $_GET['channel'];
  $mc = $_GET['mc'];

  if ($status == 'fe2707etr5s4wq') print "<h3>Failed transaction. Not all parameters fulfilled.</h3>";
  if ($status == 'bdi6p2yy76etrs') print "<h3>Pending: Incoming Mobile Money Transaction Not found</h3>";
  if ($status == 'cr5i3pgy9867e1') print "<h3>Used: This code has been used already</h3>";
  if ($status == 'dtfi4p7yty45wq') print "<h3>Less: The amount that you have sent via mobile money is LESS than what was required to validate this transaction.</h3>";
  if ($status == 'eq3i7p5yt7645e') print "<h3More: The amount that you have sent via mobile money is MORE than what was required to validate this transaction></h3>";

  if ($status == 'aei7p7yrx4ae34') {
    print "<h3>Success: The transaction is valid.</h3>";
    $getId = "SELECT UserID from `domaincart_user` WHERE email = '$email'";
    $result_id = mysqli_query($db, $getId);
    if ($result_id->num_rows > 0) {
      $row = $result_id->fetch_assoc();
      $userid = $row["UserID"];
			$_SESSION['userid'] = $userid;
      // GET PLAN INFO
      $planinfo = "SELECT productid FROM `domaincart_hosting` WHERE products = '$hostingplan'";
      // var_dump($planinfo);
      $result_plan = mysqli_query($db, $planinfo);
      // var_dump($result_plan);
      if ($result_plan->num_rows > 0) {
        $res_row = $result_plan->fetch_assoc();
        $productid = $res_row["productid"];
      }
      $order_query = "INSERT INTO `domaincart_orders` VALUES ('$txncd', '$userid', '$productid', '$nameservers', $mc, '$currency', '$orderdate', '$expirydate')";
      // var_dump($order_query);
      $order_results =  mysqli_query($db, $order_query);
      if ($order_results === TRUE) {
        print"Orders updated";
        $_SESSION['updatedOrders'] = TRUE;
      };
    }
  }


?>
<div class="col">
  <h5>Transaction Details</h5>
  <table>
    <tr>
      <td>Vendor Name</td>
      <td>Slash Dot Labs Ltd.</td>
    </tr>
    <tr>
      <td>Transaction N<sup>o</sup></td>
      <td><?php echo $txncd;?></td>
    </tr>
    <tr>
      <td>Client</td>
      <td><?php echo  $msisdn_id;?></td>
    </tr>
    <tr>
      <td>Contacts</td>
      <td><?php echo $msisdn_idnum ;?></td>
      <td><?php echo $tokenemail?></td>
    </tr>
    <tr>
      <td>Paid Via</td>
      <td><?php echo $channel;?></td>
    </tr>
    <tr>
      <td>Card Details</td>
      <td><?php echo  $cardmask;?></td>
    </tr>
  </table>

</div>

<?php

	$val = ""; //assigned iPay Vendor ID... hard code it here.
	/*
	these values below are picked from the incoming URL and assigned to variables that we
	will use in our security check URL
    */
    $val1 = $_GET["id"];
    $val2 = $_GET["ivm"]; //invoice number
    $val3 = $_GET["qwh"];
    $val4 = $_GET["afd"];
    $val5 = $_GET["poi"];
    $val6 = $_GET["uyt"];
    $val7 = $_GET["ifd"];



    $ipnurl = "https://www.ipayafrica.com/ipn/?vendor=".$val."&id=".$val1."&ivm=".
    $val2."&qwh=".$val3."&afd=".$val4."&poi=".$val5."&uyt=".$val6."&ifd=".$val7;
    $fp = fopen($ipnurl, "rb");
    $status = stream_get_contents($fp, -1, -1);
	  // var_dump($status);
    // if ($status == "fe2707etr5s4wq") {
    //
    // }
    fclose($fp);


    //the value of the parameter “vendor”, in the url being opened above, is your iPay assigned
    // Vendor ID.
	//this is the correct iPay status code corresponding to this transaction.
	//Use it to validate your incoming transaction(not the one supplied in the incoming url)

	//continue your shopping cart update routine code here below....
	//then redirect to to the customer notification page here...

 ?>
</div>
<div class="row">
	<div class="col-sm-12">
      <p>Registration completed. If you are not redirected in a few seconds,
        <a href="dashboard.php">click here.</a>
      </p>
  </div>
</div>
</div>
</body>
</html>
