<?php
  session_start();
  include"cwcconf.php";
  require"login.php";
?>
<html>
<head>
  <link href="cwhoisstyles.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<title>cWhois Domain Cart</title>
</head>
<body bgcolor="#FFFFFF">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li><?php
        if (isset($_SESSION['loggedIn'])) print "<a href='dashboard.php'><p class='text-center'>".$_SESSION['fname']." ".$_SESSION['lname']."</p></a>";
      ?></li>
      <li>
        <?php
          if (isset($_SESSION['loggedIn'])) print "<button><a href='logout.php'>Logout</a></button>";
        ?>
      </li>
    </ul>
    <?php
      if (!isset($_SESSION['loggedIn'])) {
      echo "	<form class='form-inline my-2 my-lg-0' method='post'>
          <div class='input-group'>
            <div class='input-group-prepend'>
              <span class='input-group-text' id='basic-addon1'>@</span>
            </div>
            <input type='email' name='useremail' placeholder='Email' id='useremail' required class='form-control'/>
          </div>
          <div class='input-group'>
            <div class='input-group-prepend'>
              <span class='input-group-text' id='basic-addon1'>**</span>
            </div>
            <input type='password' name='userpassword' placeholder='Password' id='userpassword' required class='form-control mr-sm-2'/>
          </div>
          <input type='submit' value='Login' name='login' class='btn btn-outline-success my-2 my-sm-0'/>
        </form>";
        if (isset($_SESSION['wrongemail'])) echo "<br/><p class='text-center text-danger'>Incorrect Email<p>";
        if (isset($_SESSION['wrongpwd'])) echo "<br/><p class='text-center text-danger'>Incorrect Password<p>";
      }
    ?>
  </div>
</nav>
