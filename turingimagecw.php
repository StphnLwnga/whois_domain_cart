<?php
  @error_reporting(E_ERROR);
  // session_start();
  // If no session key then make a new random one
  if (!isset($_SESSION['ses_cwhoishashkey']))
    $_SESSION['ses_cwhoishashkey']=rand(1000000,9999999);
  // Make a new random turing code.
  $validchars  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  $turingcode="";
  for ($k=0;$k<5;$k++)
    $turingcode.=substr($validchars,mt_rand(0,25),1);
  if ($_SESSION['ses_cwhoisturingcode']!="")
    $_SESSION['ses_cwhoispreviousturingcode']=$_SESSION['ses_cwhoisturingcode'];
  $_SESSION['ses_cwhoisturingcode']=$turingcode;
  // Choose a random background image
  $bg=mt_rand(1,4);
  $image = imagecreatefromjpeg("turingbg$bg.jpg");
  // Select black text
  $txtcolor = imagecolorallocate ($image, 0, 0, 0);
  // Add text to background
  imagestring ($image, 5, 5, 8,  $turingcode, $txtcolor);
  // Send image to browser
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  header('Content-type: image/jpeg');
  imagejpeg($image);
  imagedestroy($image);
?>
