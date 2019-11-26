<?php
  session_start();

  require'db.php';

  // if ( !isset($_POST['useremail'], $_POST['userpassword']) ) {
  // 	// Could not get the data that should have been sent.
  // 	echo 'Please fill both the username and password field!';
  // }
  // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
  if (isset($_POST['login'])){
    // var_dump($_POST);
    if ($stmt = $db->prepare('SELECT email, password FROM `domaincart_user` WHERE email = ?')) {
     // Bind parameters (s = string, i = int, b = blob, etc), in our case the email is a string so we use "s"
     $stmt->bind_param('s', $_POST['useremail']);
     $stmt->execute();
     // Store the result so we can check if the account exists in the database.
     $stmt->store_result();
     if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        if (md5($_POST['userpassword']) == $password) {
        	// Verification success! User has loggedin!
        	// Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
        	session_regenerate_id();
          // $_SESSION['userPresent'] = TRUE;
        	$_SESSION['loggedIn'] = TRUE;
        	$_SESSION['email'] = $_POST['useremail'];
          $email = $_SESSION['email'];
          unset($_SESSION['wrongpwd']);
          unset($_SESSION['wrongemail']);
          }
        } else {
        	$_SESSION['wrongpwd'] = 'Incorrect password!';
        }
      } else {
      	$_SESSION['wrongemail'] = 'Incorrect email!';
      }
      $stmt->close();
    }
    if (isset($_SESSION['loggedIn'])) {
      $email = $_SESSION['email'];
      $sql = "SELECT * FROM `domaincart_user` WHERE email = '$email'";

      $result = mysqli_query($db, $sql);
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row["email"];
        $_SESSION['userid'] = $row["UserID"];
        $_SESSION['fname'] = $row["fname"];
        $_SESSION['lname'] = $row["lname"];
        $_SESSION['dregistered'] = $row["dregistered"];
        $_SESSION['address'] = $row["address"];
        $_SESSION['city'] = $row["city"];
        $_SESSION['country'] = $row["country"];
        $_SESSION['phone'] = $row["phone"];
        $_SESSION['org'] = $row["organisation"];
    }
      // var_dump($_SESSION);
  }
  // if (!isset($_SESSION['loggedIn'])) {
  //   echo"
  //     <div class='login'>
  //     	<h1>Login</h1>
  //     	<form  method='post'>
  //     		<label for='useremail'>
  //     			<i class='fas fa-user'></i>
  //     		</label>
  //     		<input type='email' name='useremail' placeholder='Email' id='useremail' required>
  //     		<label for='userpassword'>
  //     			<i class='fas fa-lock'></i>
  //     		</label>
  //     		<input type='password' name='userpassword' placeholder='Password' id='userpassword' required>
  //     		<input type='submit' value='Login' name='login'>
  //     	</form>
  //     ";
  //     if (isset($_SESSION['wrongemail'])) echo '<br/><p>Incorrect Email<p>';
  //     if (isset($_SESSION['wrongpwd'])) echo '<br/><p>Incorrect Password<p>';
  //   echo "</div>";
  // }
  // else {
  //
  // }
?>
