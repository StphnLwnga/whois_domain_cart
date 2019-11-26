<?php
  session_start();
  require 'db.php';
  include "includes/header.php";
  //var_dump($_SESSION);
  $domain = $_SESSION['cartdomain'];
  $user = $_SESSION['fname']." ".$_SESSION['lname'];
  $email = $_SESSION['email'];
  $contact = $_SESSION['phone'];
  $address = $_SESSION['address'].", ".$_SESSION['city'].", ".$_SESSION['country'];
  $org = $_SESSION['org'];
  $userid = $_SESSION['userid'];

  //GET PREVIOUS Orders

  if (isset($_SESSION['loggedIn'])) {
    if(isset($_SESSION['userid'])) {
      $sql = "SELECT domaincart_orders.orderId, domaincart_orders.OrderDate, domaincart_orders.ExpiryDate, domaincart_orders.NameServers, domaincart_hosting.products, domaincart_hosting.description
      FROM `domaincart_orders`
      INNER JOIN `domaincart_hosting`
      ON domaincart_orders.productid = domaincart_hosting.productid
      WHERE domaincart_orders.UserID = $userid";

      $results = $db->query($sql);
      $_SESSION['orders'] = [];
      if (mysqli_num_rows($results) > 0) {
        while ($row = $results->fetch_assoc()) {
          array_push($_SESSION['orders'], $row);
        }
      }
    }
  } else {
    array_push($_SESSION['loginerrors'], 'You do not have access to this page');
    foreach ($_SESSION['loginerrors'] as $val) {
      echo "
        <h3>$val</h3>
      ";
    }
  }

  if (isset($_SESSION['loggedIn'])) {
    echo "
      <div class='container'>
        <div class='row'>
          <div class='col-sm-12'>
            <h1 class='text-center'>Dashboard</h1>
            <br/>
          </div>
        </div>
        <div class='row'>
          <div class='col-sm-12'>
            <h4 class='text-center'>Domains</h4>
            <br/>
            <table>
              <tr>
                <th>Order I.D.</th>
                <th>Nameservers</th>
                <th>Date Activated</th>
                <th>Active Until</th>
                <th>Hosting Plan</th>
                <th>Status</th>
              </tr>
    ";
      foreach ($_SESSION['orders'] as $index => $val) {
        $orderid = $val["orderId"];
        $nameservers = $val["NameServers"];
        $orderdate = $val["OrderDate"];
        $expiry = $val["ExpiryDate"];
        $package = $val["products"]." (".$val["description"].")";
        $status = $expiry > date("Y-m-d H:m:s") ? "Active" : "Inactive";
        print "<tr>";
        print "<td>$orderid</td>";
        print "<td>";
       if (isset($_POST['editNameservers']) && ($_POST['selectedOid'] === "$orderid")) {
         print "
          <form class='$orderid' action=".htmlentities($_SERVER['PHP_SELF'])." method='post'>
            <input type='text' value='$nameservers' data-orderid='$orderid' name='editNameservers' />
            <input type='hidden' name='currentOid' value='$orderid'>
            <br/>
           	<input id='' type='submit' value='Edit NSE' name='updateNameservers'/>
          </form>"
          ;
       } else {
         $nselist = explode(',', $nameservers);
         foreach ($nselist as $val) {
           print "<span class='text-center'>$val </span>";
         }
         //
         print $nameservers;
       }
        print "</td>";
        print "<td>$orderdate</td>";
        print "<td>$expiry</td>";
        print "<td>$package</td>";
        print "<td>$status</td>";
        print "<td>";
         print "<form action=".htmlentities($_SERVER['PHP_SELF'])." method='post'>";
        print "<input type='hidden' name='selectedOid' value='$orderid'/>";
         print " <input type=\"submit\" value=\"Edit Nameservers\" name=\"editNameservers\" />
        </form>";
        print "</td>";
        print "</tr>";
      }
      print "
          </table>
        </div>
          </div>
          <div class='row'>
            <div class='col-sm-12'>
              <h4 class='text-center'>SSL</h4>
              <br/>
              <p>All our hosting plans support the free Let's Encrypt SSL certificates</p>
              <p></p>
            </div>
          </div>
          <div class='row'>
            <div class='col-sm-12'>
              <h4 class='text-center'>Account Details</h4><br/>
              <table>
              <tr>
      ";
      $org != "" ? print "<th>Organisation</th>" : "";

      echo"
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
            </tr><tr>
      ";
      $org != "" ? print "<td>$org</td>" : "";
      print "<td>$user</td>";
      print "<td>$email</td>";
      print "<td>$contact</td>";
      print "<td>$address</td>";
      echo "</table><br/>";?>
          <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
              <input type="submit" value="Edit Account Details" name="allowEdit" />
          </form>
        </div><br/>
      <?php
      if (isset($_POST['allowEdit'])) {
        print "<div class='row'><div class='col-sm-12'>";?>
        <form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post">"
      <?php
        print "<table><tr><th></th><th></th></tr>";
        print "
          <tr>
            <td>Organisation</td>
            <td>
              <input type='text' value='$org' name='org'/>
            </td>
          </tr>
          <tr>
            <td>First Name</td>
            <td>
              <input type='text' value=".htmlentities($_SESSION['fname'])." name='fname' required/>
            </td>
          </tr>
          <tr>
            <td>Last Name</td>
            <td>
              <input type='text' value=".htmlentities($_SESSION['lname'])." name='lname' required/>
            </td>
          </tr>
          <tr>
            <td>Password</td>
            <td>
              <input type='password' name='password' required/>
            </td>
          </tr>
          <tr>
            <td>Verify Password</td>
            <td>
              <input type='password' name='verifypassword' required/>
            </td>
          </tr>
          <tr>
            <td>Phone</td>
            <td>
              <input type='text' value=$contact name='phone' required/>
            </td>
          </tr>
          <tr>
            <td>Address</td>
            <td>
              <input type='text' value=".htmlentities($_SESSION['address'])." name='addr' required/>
            </td>
          </tr>
          <tr>
            <td>City</td>
            <td>
              <input type='text' value=".htmlentities($_SESSION['city'])." name='city' required/>
            </td>
          </tr>
          <tr>
            <td>Country</td>
            <td>
              <input type='text' value=".htmlentities($_SESSION['country'])." name='country' required/>
            </td>
          </tr>
          <tr>
            <td><input type=\"submit\" value=\"Update Account\" name=\"submitUpdate\" /></td>
          </tr>
        ";
        print "</table>";
        print "</form>";
        print "</div></div>";
        //
        // if (isset($_POST['submitUpdate'])) {
        //   var_dump($_POST);
        // }
      }
      if (isset($_POST['submitUpdate'])) {
          // var_dump($_POST);
          $fname = $_POST['fname'];
          $lname = $_POST['lname'];
          $org = $_POST['org'] == "" ? NULL : $_POST['org'];
          $password = md5($_POST['password']);
          $addr = $_POST['addr'];
          $city = $_POST['city'];
          $country = $_POST['country'];
          $phone = $_POST['phone'];
          $update = "UPDATE `domaincart_user`
            SET fname='$fname', lname='$lname', organisation='$org', password='$password', address='$addr', city='$city', country='$country', phone='$phone'
            WHERE UserID=$userid
            ";
          $results = $db->query($update);
          // var_dump($results);
          if ($results) {
            print "
              <div class='row'>
                <div class='col-12'>
                  <p class='text-center text-success'>Successfully edited your account</p>
                </div>
              </div>
            ";
          }
      }
      if (isset($_POST['updateNameservers'])) {
        $nses = $_POST['editNameservers'];
        $cuid = $_POST['currentOid'];
        $updatequery = "UPDATE `domaincart_orders`
          SET NameServers='$nses'
          WHERE orderId = $cuid
        ";
        // var_dump($_POST, $updatequery);
        $updateresults = $db->query($updatequery);
        if ($updateresults) {
          print "
            <div class='row'>
              <div class='col-12'>
                <p class='text-center text-success'>Successfully edited nameservers.</p>
              </div>
            </div>
          ";
        } else {
          print "
            <div class='row'>
              <div class='col-12'>
                <p class='text-center text-danger'>Could not edit nameservers.</p>
              </div>
            </div>
          ";
        }
      }
      echo"
          </div>
        </div>
      ";

      // print "
      //   <script>
      //   $('.$orderid').on('submit',function(){
      //      var oid = $('input.$orderid').attr('data-orderid');
      //      var nses = $('input.$orderid').val();
      //      $.post('edit_nses.php',{'oid':oid, 'nses':nses});
      //    });
      //   </script>
      // ";
  }

  includes"footer.php";
?>
