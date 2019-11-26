<?php
// cWhois Domain Cart V4.4
// Copyright 2003 to 2018 Vibralogix
// You are licensed to use this on one domain only
// For further information email support@vibralogix.com
// session_id();
// session_start();
@error_reporting(E_ERROR);
include $FilePath."cwhois.php";
if (!empty($_GET)) while(list($name, $value) = each($_GET)) $$name = $value;
if (!isset($_SESSION['numberofitems']))
	$_SESSION['numberofitems']=0;
if (!isset($_SESSION['numberremoved']))
	$_SESSION['numberremoved']=0;
$_SESSION['loginerrors'] = [];
$numdomreg=count($register);
$numdomtran=count($transfer);
$numdomren=count($renew);
$numhost=count($host);
$numsugcat=count($suggestcategory);
$numsuggest=count($suggest);
if (!isset($DefaultBuy))
  $DefaultBuy=1;
if (isset($checkoutpage)==false)
  $checkoutpage="";
if (isset($paymentpage)==false)
  $paymentpage="";
if (!isset($allowlookup))
  $allowlookup=true;
if (!isset($SimpleHostingOnly))
  $SimpleHostingOnly=false;
if (!isset($DestroySession))
  $DestroySession=false;
if (!isset($CheckoutTuring))
  $CheckoutTuring=false;
if (!isset($LookupTuring))
  $LookupTuring=false;
if (!isset($SearchTuring))
  $SearchTuring=false;
if (!isset($SimpleRegisterOnly))
  $SimpleRegisterOnly=false;

if (!isset($_SESSION['captchasearchcount']))
  $_SESSION['captchasearchcount']=0;
if (!isset($_SESSION['captchasearchcheck']))
  $_SESSION['captchasearchcheck']=false;
if (!isset($_SESSION['captchasearchtime']))
  $_SESSION['captchasearchtime']=0;
if (!isset($_SESSION['captchasearchspeed']))
  $_SESSION['captchasearchspeed']=-1;

if ($vendoremail!="")
{
  if (!isset($EmailHeaderNoSlashR))
    $EmailHeaderNoSlashR=1;
  if ((!isset($ExtraMailParam)) && (strtolower(@ini_get("safe_mode")) != 'on') && (@ini_get("safe_mode") != '1'))
    $ExtraMailParam="-f ".$vendoremail;
  @ini_set(sendmail_from,$vendoremail);
}
// Convert all coupon codes to lower case
if (isset($coupon))
{
  if(function_exists("array_change_key_case"))
    $coupon=array_change_key_case($coupon);
}
if ($cwaction=="lookup")
{
  require_once ("cwcconf.php");
  if ($LookupTuring)
    $requireturing=2;
  print "<html>\n";
	print "<head>\n";
	print "<title>Domain Lookup</title>\n";
	print "</head>\n";
	print "<body>\n";
	print "<div class=\"cwhoislookup\">\n";
  if ($allowlookup)
	  $i=cWhois($domain,"",$reg, $turing);
	else
	 $i=-1;
	if ($i==-1)
		print "Whois lookup disabled<br>";
	if ($i==4)
	{
		print "<form method=\"GET\" action=\"cwhoiscart.php\">\n";
		print "<input type=\"hidden\" name=\"domain\" value=\"".$domain."\">\n";
		print "<input type=\"hidden\" name=\"cwaction\" value=\"lookup\">\n";
	  print "<table class=\"cwhoislookup\"><tr>\n";
	  print "<td class=\"cwhoislookup\"><p class=\"cwhoislookup\">".$lang['TuringCode']."*</p></td>\n";
	  print "<td class=\"cwhoislookup\"><input class=\"cwhoislookup\" type=\"text\" name=\"turing\" id=\"turing\" size=\"10\">&nbsp;<img class=\"cwhoislookup\" id=\"turingimage\" name=\"turingimage\" src=\"turingimagecw.php\" align=\"absmiddle\" width=\"60\" height=\"30\"><td class=\"cwhoislookup\"></tr>\n";
    print "<tr><td class=\"cwhoislookup\">&nbsp;</td><td class=\"cwhoislookup\"><input type=\"submit\" value=\"".$lang['Lookup']."\"></td></tr>\n";
	  print "</table>\n";
	  print "</form>\n";
	}
	if ($i==5)
		print "Problem connecting with whois server<br>";
	if ($i==2)
		print "Domain extension for $domain not recognised<br>";
	if ($i==3)
		print "$domain is not valid <BR>";
	print "<p class=\"cwhoislookup\"><a class=\"cwhoislookup\" href=\"javascript:window.close()\">Close Window</a></p>\n";
	print "<p class=\"cwhoislookup\">\n";
	if (($i==0) || ($i==1) || ($i==6))
	{
		for($k=0;$k<count($reg);$k++)
		{
			print("$reg[$k]<BR>");
		}
		print "</p>\n";
		print "<a class=\"cwhoislookup\" href=\"javascript:window.close()\">Close Window</a></p>\n";
	}
	print "</div>\n";
	print "</body>\n";
	print "</html>\n";
	exit;
}

if ($cwhoismode==0)
{
	// Setup Javascript functions required for mode 0
	print "<script language=\"JavaScript\">\n";
	print "<!-- JavaScript\n";
	print "function whois(domain)\n";
	print "{\n";
	print "  window.open(\"".$FilePath."cwhoiscart.php?cwaction=lookup&domain=\"+domain,\"whois\",\"width=500,height=300,resizable=yes,scrollbars=yes\")\n";
	print "}\n";
	print "\n";
	print "function CheckIt(form)\n";
	print "{\n";
	print "  if (form.domain.value==\"\")\n";
	print "    return(false)\n";
	print "  form.cwaction.value=\"check\"\n";
	print "  return(true)\n";
	print "}\n";
	print "\n";
	print "function AddToCart(form)\n";
	print "{\n";
	print "  form.cwaction.value=\"add\"\n";
	print "  form.submit()\n";
	print "}\n";
	print "\n";
	print "function AddOut(form)\n";
	print "{\n";
	print "  form.action=\"$checkoutpage\"\n";
	print "  form.cwaction.value=\"addout\"\n";
	print "  form.page.value=window.location.pathname\n";
	print "  form.submit()\n";
	print "}\n";
	print "\n";
	print "function RemoveFromCart(form)\n";
	print "{\n";
	print "  form.cwaction.value=\"remove\"\n";
	print "  form.submit()\n";
	print "}\n";
	print "\n";
	print "function Checkout(form)\n";
	print "{\n";
	print "  form.action=\"$checkoutpage\"\n";
	print "  form.cwaction.value=\"checkout\"\n";
	print "  form.page.value=window.location.pathname\n";
	print "  form.submit()\n";
	print "}\n";
	print "function suggestclicked()\n";
	print "{\n";
	if ($numsugcat>1)
	{
	  print "  if (document.searchform.domsug.checked)\n";
	  print "  {\n";
	  print "    document.searchform.suggestcat.disabled=false\n";
	  print "  }\n";
	  print "  else\n";
	  print "  {\n";
	  print "    document.searchform.suggestcat.disabled=true\n";
	  print "  }\n";
	}
	print "}\n";

?>

  var register = []
  var transfer = []
  var renew = []
  var registerspecial = []
  var transferspecial = []
  var renewspecial = []
  var lang = []
  var numhost = <?php echo $numhost; ?>

<?php
  for ($k=0;$k<count($register);$k++)
    print "register[".$k."]=\"".$register[$k]."\"\n";
  for ($k=0;$k<count($transfer);$k++)
    print "transfer[".$k."]=\"".$transfer[$k]."\"\n";
  for ($k=0;$k<count($renew);$k++)
    print "renew[".$k."]=\"".$renew[$k]."\"\n";
  for ($k=0;$k<count($registerspecial);$k++)
    print "registerspecial[".$k."]=\"".$registerspecial[$k]."\"\n";
  for ($k=0;$k<count($transferspecial);$k++)
    print "transferspecial[".$k."]=\"".$transferspecial[$k]."\"\n";
  for ($k=0;$k<count($renewspecial);$k++)
    print "renewspecial[".$k."]=\"".$renewspecial[$k]."\"\n";
?>
  // Language elements
  lang['Register']="<?php echo $lang['Register']; ?>"
  lang['HostingOnly']="<?php echo $lang['HostingOnly']; ?>"
  lang['Transfer']="<?php echo $lang['Transfer']; ?>"
  lang['Renew']="<?php echo $lang['Renew']; ?>"
  lang['Year']="<?php echo $lang['Year']; ?>"
  var csymbol="<?php echo $csymbol; ?>"
  if (csymbol=="&pound;")
    csymbol="\u00A3";
  var csymbol2="<?php echo $csymbol2; ?>"

function hostingplanchange(domain,ext,row)
{
  <?php
  if ((count($registerspecial)==0) && (count($transferspecial)==0) && (count($renewspecial)==0))
    print "return\n";
  else
  {
  ?>

  var menu1Obj =  document.getElementById("domainselectmenu"+row)
  var menu2Obj =  document.getElementById("hostingselectmenu"+row)
  var menu1index = menu1Obj.selectedIndex
  var menu2index = menu2Obj.selectedIndex
  var menu1length = menu1Obj.length
  var plan = menu2Obj.options[menu2index].value
  if (numhost<1)
    return
  ext=ext.toLowerCase()
  // See what sevices listed in domain menu
  var nohostinglisted=false
  var registerlisted=false
  var transferlisted=false
  var renewlisted=false
  var val
  for (k=0;k<menu1length;k++)
  {
    val=menu1Obj.options[k].value
    if (val==-1)
      nohostinglisted=true
    if ((val>=0) && (val<100))
      registerlisted=true;
    if ((val>=100) && (val<200))
      transferlisted=true;
    if ((val>=200) && (val<300))
      renewlisted=true;
  }
  // See if special prices set for each service for this domain extension and hosting plan
  if (registerlisted)
  {
    registerprices=getdomainprices(register,registerspecial,ext,plan)
  }
  if (transferlisted)
  {
    transferprices=getdomainprices(transfer,transferspecial,ext,plan)
  }
  if (renewlisted)
  {
    renewprices=getdomainprices(renew,renewspecial,ext,plan)
  }
  menu1Obj.options.length=0
  var optioncounter=0
  if (nohostinglisted)
  {
    menu1Obj.options[optioncounter]=new Option(lang['HostingOnly'], '-1')
    optioncounter++;
  }
  if (registerlisted)
  {
    pricesandperiods=registerprices.split(",")
    periodparts=pricesandperiods[0].split(":")
    // Check for domain name length based pricing
    otherprice=""
    if (pricesandperiods[2]!="")
    {
      pricesandperiods[2]=pricesandperiods[2].toLowerCase()
      if (pricesandperiods[2].indexOf("length",0)>=0)
      {
        var pos=pricesandperiods[2].indexOf("(",0)
        var pos2=pricesandperiods[2].indexOf(")",0)
        var len=pricesandperiods[2].substring(pos+1,pos2)
        var lengths=len.split("-")
        minlen=lengths[0]
        maxlen=lengths[1]
        if ((domain.length>=minlen) && (domain.length<=maxlen))
        {
          lenpriceparts=pricesandperiods[2].split("=")
          otherprice=lenpriceparts[1]
        }
      }
    }
    if (otherprice!="")
      priceparts=otherprice.split(":")
    else
      priceparts=pricesandperiods[1].split(":")
    for (k=0;k<periodparts.length;k++)
    {
      menu1Obj.options[optioncounter]=new Option(lang['Register']+' '+periodparts[k]+' '+lang['Year']+' '+csymbol+priceparts[k]+csymbol2, k)
      optioncounter++
    }
  }
  if (transferlisted)
  {
    pricesandperiods=transferprices.split(",")
    periodparts=pricesandperiods[0].split(":")
    // Check for domain name length based pricing
    otherprice=""
    if (pricesandperiods[2]!="")
    {
      pricesandperiods[2]=pricesandperiods[2].toLowerCase()
      if (pricesandperiods[2].indexOf("length",0)>=0)
      {
        var pos=pricesandperiods[2].indexOf("(",0)
        var pos2=pricesandperiods[2].indexOf(")",0)
        var len=pricesandperiods[2].substring(pos+1,pos2)
        var lengths=len.split("-")
        minlen=lengths[0]
        maxlen=lengths[1]
        if ((domain.length>=minlen) && (domain.length<=maxlen))
        {
          lenpriceparts=pricesandperiods[2].split("=")
          otherprice=lenpriceparts[1]
        }
      }
    }
    if (otherprice!="")
      priceparts=otherprice.split(":")
    else
      priceparts=pricesandperiods[1].split(":")
    for (k=0;k<periodparts.length;k++)
    {
      menu1Obj.options[optioncounter]=new Option(lang['Transfer']+' '+periodparts[k]+' '+lang['Year']+' '+csymbol+priceparts[k]+csymbol2, k+100)
      optioncounter++
    }
  }
  if (renewlisted)
  {
    pricesandperiods=renewprices.split(",")
    periodparts=pricesandperiods[0].split(":")
    // Check for domain name length based pricing
    otherprice=""
    if (pricesandperiods[2]!="")
    {
      pricesandperiods[2]=pricesandperiods[2].toLowerCase()
      if (pricesandperiods[2].indexOf("length",0)>=0)
      {
        var pos=pricesandperiods[2].indexOf("(",0)
        var pos2=pricesandperiods[2].indexOf(")",0)
        var len=pricesandperiods[2].substring(pos+1,pos2)
        var lengths=len.split("-")
        minlen=lengths[0]
        maxlen=lengths[1]
        if ((domain.length>=minlen) && (domain.length<=maxlen))
        {
          lenpriceparts=pricesandperiods[2].split("=")
          otherprice=lenpriceparts[1]
        }
      }
    }
    if (otherprice!="")
      priceparts=otherprice.split(":")
    else
      priceparts=pricesandperiods[1].split(":")
    for (k=0;k<periodparts.length;k++)
    {
      menu1Obj.options[optioncounter]=new Option(lang['Renew']+' '+periodparts[k]+' '+lang['Year']+' '+csymbol+priceparts[k]+csymbol2, k+200)
      optioncounter++
    }
  }
  menu1Obj.selectedIndex = menu1index

  <?php } ?>
}

function getdomainprices(dataarray,dataarrayspecial,ext,hostid)
{
  if (hostid>=0)
  {
    for (k=0;k<dataarrayspecial.length;k++)
    {
      arrayparts=dataarrayspecial[k].split(",")
      if (arrayparts[0]==ext)
      {
        // matching domain extensions check for hostid
        planparts=arrayparts[3].split(":")
        for (j=0;j<planparts.length;j++)
        {
          if ((planparts[j]==hostid) || (planparts[j]=="*"))
          {
            // match so return prices string
            var retstr=arrayparts[1]+","+arrayparts[2]
            if (arrayparts[4]!="")
              retstr=retstr+","+arrayparts[4]
            return(retstr)
          }
        }
      }
    }
  }
  for (k=0;k<dataarray.length;k++)
  {
    arrayparts=dataarray[k].split(",")
    if (arrayparts[0]==ext)
    {
      var retstr=arrayparts[1]+","+arrayparts[2]
      if (arrayparts[3]!="")
        retstr=retstr+","+arrayparts[3]
      return(retstr)
    }
  }
  return("")
}

<?php
	print "// - JavaScript - -->\n";
	print "</script>\n";
}

if ($cwhoismode==1)
{
	print "<script language=\"JavaScript\">\n";
	print "<!-- JavaScript\n";
	print "function whois(domain)\n";
	print "{\n";
	print "  window.open(\"".$FilePath."cwhoiscart.php?cwaction=lookup&domain=\"+domain,\"whois\",\"width=800,height=500,resizable=yes,scrollbars=yes\")\n";
	print "}\n";
	print "function Check2a(form)\n";
	print "{\n";
	print "  if (form.d1.value==\"\")\n";
	print "    return\n";
	print "  form.submit()\n";
	print "}\n";
	print "function Available(form)\n";
	print "{\n";
	print "  alert(\"".$lang['YouMust']."\")\n";
	print "  form.d1.focus()\n";
	print "}\n";
	print "function Continue2a(form,dom,ext)\n";
	print "{\n";
	print "  form.action=\"$checkoutpage\"\n";
	print "  form.cwaction.value=\"addout\"\n";
	print "  form.page.value=window.location.pathname\n";
	print "  form.d1.value=dom\n";
	print "  form.x1.value=ext\n";
	  print "  var d1=form.d1.value\n";
	print "  d1=d1.toLowerCase()\n";
	print "  // Remove any http://www. entered\n";
	print "  if (d1.substring(0,11)==\"http://www.\")\n";
	print "    d1=d1.substring(11,d1.length)\n";
	print "  if (d1.substring(0,12)==\"https://www.\")\n";
	print "    d1=d1.substring(12,d1.length)\n";
	print "  if (d1.substring(0,4)==\"www.\")\n";
	print "    d1=d1.substring(4,d1.length)\n";
  print "  form.d1.value=d1\n";
	print "  form.submit()\n";
	print "}\n";
	print "function Continue2b(form)\n";
	print "{\n";
	print "  var domext=CheckValidDomain(form.d1.value)\n";
	print "  if (domext==\"\")\n";
	print "  {\n";
	print "    alert(\"".$lang['NotValid']."\")\n";
	print "    form.d1.focus()\n";
	print "    return\n";
	print "  }\n";
	print "  form.action=\"$checkoutpage\"\n";
	print "  form.cwaction.value=\"addout\"\n";
	print "  form.page.value=window.location.pathname\n";
	print "  form.x1.value=domext\n";
	print "  var d1=form.d1.value\n";
	print "  d1=d1.toLowerCase()\n";
	print "  // Remove any http://www. entered\n";
	print "  if (d1.substring(0,11)==\"http://www.\")\n";
	print "    d1=d1.substring(11,d1.length)\n";
	print "  if (d1.substring(0,12)==\"https://www.\")\n";
	print "    d1=d1.substring(12,d1.length)\n";
	print "  if (d1.substring(0,4)==\"www.\")\n";
	print "    d1=d1.substring(4,d1.length)\n";
	print "  d1=d1.substring(0,d1.length-domext.length)\n";
  print "  form.d1.value=d1\n";
	print "  form.submit()\n";
	print "}\n";
	print "function CheckValidDomain(domain)\n";
	print "{\n";
	print "  domain=domain.toLowerCase()\n";
	print "  // Remove any http://www. entered\n";
	print "  if (domain.substring(0,11)==\"http://www.\")\n";
	print "    domain=domain.substring(11,domain.length)\n";
	print "  if (domain.substring(0,12)==\"https://www.\")\n";
	print "    domain=domain.substring(12,domain.length)\n";
	print "  if (domain.substring(0,4)==\"www.\")\n";
	print "    domain=domain.substring(4,domain.length)\n";
	print "  // Get domain extension\n";
	print "  var found=0\n";
	print "  // Treat .name differently\n";
	print "  if (domain.substring(domain.length-5,domain.length)==\".name\")\n";
	print "  {\n";
	print "    domt=\".name\"\n";
	print "    domain=domain.substring(0,domain.length-5)\n";
	print "    found=1\n";
	print "  }\n";
	print "  else\n";
	print "  {\n";
	print "    // Get domain extension by taking everything from first '.'\n";
	print "    var pos=domain.indexOf(\".\")\n";
	print "    if (pos!=-1)\n";
	print "    {\n";
	print "      domt=domain.substring(pos,domain.length)\n";
	print "      domain=domain.substring(0,pos)\n";
	print "      found=1\n";
	print "    }\n";
	print "  }\n";
	print "  if (found==0)\n";
	print "    return(\"\")\n";
	print "  // Now we should have domain and extension so check them\n";
	print "  // Handle .name seperately\n";
	print "  if (domt==\".name\")\n";
	print "  {\n";
	print "    // Split domain into first a second name parts\n";
	print "    var pos=domain.indexOf(\".\")\n";
	print "    if (pos>=0)\n";
	print "    {\n";
	print "      var section=domain.split(\".\")\n";
	print "      if (section.length>2)\n";
	print "        return(\"\")\n";
	print "      var first=section[0]\n";
	print "      var last=section[1]\n";
	print "      // Check first\n";
	print "      if ((first.length==0) || (first.length>63))\n";
	print "        return(\"\")\n";
	print "      if (ValidChars(first,\"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-\")==false)\n";
	print "        return(\"\")\n";
	print "      if ((first.indexOf(\"--\")>-1) || (first.charAt(first.length-1)==\"-\") || (first.charAt(0)==\"-\"))\n";
	print "        return(\"\")\n";
	print "      // Check last\n";
	print "      if ((last.length<3) || (last.length>63))\n";
	print "        return(\"\")\n";
	print "      if (ValidChars(last,\"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-\")==false)\n";
	print "        return(\"\")\n";
	print "      if ((last.indexOf(\"--\")>-1) || (last.charAt(last.length-1)==\"-\") || (last.charAt(0)==\"-\"))\n";
	print "        return(\"\")\n";
	print "      return(domt)\n";
	print "    }\n";
	print "    else\n";
	print "    {\n";
	print "        if ((domain.length==0) || (domain.length>63))\n";
	print "          return(\"\")\n";
	print "        if (ValidChars(domain,\"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-\")==false)\n";
	print "          return(\"\")\n";
	print "        if ((domain.indexOf(\"--\")>-1) || (domain.charAt(domain.length-1)==\"-\") || (domain.charAt(0)==\"-\"))\n";
	print "          return(\"\")\n";
	print "        return(domt)\n";
	print "    }\n";
	print "  }\n";
	print "  // First check extension part\n";
	print "  if ((domt.length<3) || (domt.length>12))\n";
	print "    return(\"\")\n";
	print "  if (ValidChars(domt,\"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.\")==false)\n";
	print "    return(\"\")\n";
	print "  if ((domt.indexOf(\"..\")>-1) || (domt.charAt(domt.length-1)==\".\"))\n";
	print "    return(\"\")\n";
	print "  // Check name part\n";
	print "  if ((domain.length==0) || (domain.length>63))\n";
	print "    return(\"\")\n";
	print "  valid=\"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-\";\n";
	print "  if (domt==\".no\")\n";
	print "  {\n";
	print "    valid=\"\xE1\xE0\xE2\xE7\xE9\xE8\xEA\xF1\xF3\xF2\xF4\xF6\xFC\xF8\xE5\xE6abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-\";\n";
	print "  }\n";
	print "  if (domt==\".dk\")\n";
	print "  {\n";
	print "    valid=\"\xF8\xE5\xE6abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-\";\n";
	print "  }\n";
	print "  if (domt==\".se\")\n";
	print "  {\n";
	print "    valid=\"\xE4\xE5\xF6\xE9\xFCabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-\";\n";
	print "  }\n";
	print "  if ((domt==\".ch\") || (domt==\".li\") || (domt==\".de\") || (domt==\".at\") || (domt==\".com\") || (domt==\".net\"))\n";
	print "  {\n";
	print "    valid=\"\xE0\xE1\xE2\xE3\xE4\xE5\xE6\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF6\xF8\xF9\xFA\xFB\xFC\xFD\xFE\xFFabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-\";\n";
	print "  }\n";
	print "  if (ValidChars(domain,valid)==false)\n";
	print "    return(\"\")\n";
	print "  if ((domain.indexOf(\"--\")>-1) || (domain.charAt(domain.length-1)==\"-\") || (domain.charAt(0)==\"-\"))\n";
	print "    return(\"\")\n";
	print "  return(domt);\n";
	print "}\n";
	print "function ValidChars(str,valid)\n";
	print "{\n";
	print "  var v=true;\n";
	print "  for (i=0;i<str.length;i++)\n";
	print "  {\n";
	print "    if (valid.indexOf(str.charAt(i))==-1)\n";
	print "    {\n";
	print "      v=false\n";
	print "      break\n";
	print "    }\n";
	print "  }\n";
	print "  return(v)\n";
	print "}\n";
	print "// - JavaScript - -->\n";
	print "</script>\n";
}
if (($cwaction=="") && ($cwhoismode==1))
{
	$hstextcolor="#000000";
	$hstextsize="12pt";

	print "<div class=\"cwhoissimplestep1\"><form method=\"get\">\n";
  print "<input name=\"cwaction\" type=\"hidden\" value=\"hoststep2\">\n";
  if ($domain!="")
    print "<input name=\"domain\" type=\"hidden\" value=\"$domain\">\n";
  if ($dex!="")
    print "<input name=\"dex\" type=\"hidden\" value=\"$dex\">\n";
	print "<table class=\"cwhoissimplestep1\">\n";
	print "<tr>\n";
	print "<td class=\"cwhoissimplestep1\"><p class=\"cwhoissimplestep1\">".$lang['SelectHosting']."</p></td>\n";
	print "<td class=\"cwhoissimplestep1\"><p class=\"cwhoissimplestep1\"><select class=\"cwhoissimplestep1\" name=\"h1\" size=\"1\">\n";
	if ($numhost>0)
	{
	  // We need to add an option for each hosting package
	  for ($j=0;$j<$numhost;$j++)
	  {
	    $hostdesc=strtok($host[$j],",");
	    $hostsetup=strtok(",");
	    $hostprice=strtok(",");
	    $hostrecurr=strtoupper(strtok(","));
      if ($h1==$j)
      {
	      if ($hostsetup==0)
	        print "<option selected value=\"$j\">$hostdesc $csymbol$hostprice$csymbol2</option>\n";
	      else
	        print "<option selected value=\"$j\">$hostdesc $csymbol$hostprice$csymbol2 (setup $csymbol$hostsetup$csymbol2)</option>\n";
      }
      else
      {
	      if ($hostsetup==0)
	        print "<option value=\"$j\">$hostdesc $csymbol$hostprice$csymbol2</option>\n";
	      else
	        print "<option value=\"$j\">$hostdesc $csymbol$hostprice$csymbol2 (setup $csymbol$hostsetup$csymbol2)</option>\n";
      }
	  }
  }
	print "</select>\n";
	print "</td>\n";
	print "</tr>\n";
  if (($SimpleHostingOnly==false) && ($SimpleRegisterOnly==false))
  {
  	print "<tr>\n";
	  print "<td class=\"cwhoissimplestep1\"><p class=\"cwhoissimplestep1\">".$lang['LikeToRegister']."</p></td>\n";
	  print "<td class=\"cwhoissimplestep1\"><p class=\"cwhoissimplestep1\"><input class=\"cwhoissimplestep1\" type=\"radio\" name=\"domexists\" value=\"register\" checked></p></td>\n";
	  print "</tr>\n";
	  print "<tr>\n";
	  print "<td class=\"cwhoissimplestep1\"><p class=\"cwhoissimplestep1\">".$lang['JustHost']."</p></td>\n";
	  print "<td class=\"cwhoissimplestep1\"><p class=\"cwhoissimplestep1\"><input class=\"cwhoissimplestep1\" type=\"radio\" name=\"domexists\" value=\"exists\"></p></td>\n";
	  print "</tr>\n";
  }
	else
	{
	  if ($SimpleHostingOnly==true)
	    print "<input type=\"hidden\" name=\"domexists\" value=\"exists\">\n";
	  if ($SimpleRegisterOnly==true)
	    print "<input type=\"hidden\" name=\"domexists\" value=\"register\">\n";
	}
	print "<tr>\n";
	print "<td class=\"cwhoissimplestep1\"><p class=\"cwhoissimplestep1\">&nbsp;</p></td>\n";
	print "<td class=\"cwhoissimplestep1\" align=\"right\"><p><input class=\"cwhoissimplestep1\" type=\"submit\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\"></p></td>\n";
	print "</tr>\n";
	print "</table>\n";
	print "</form></div>\n";
}
if (($cwaction=="hoststep2") && ($domexists=="register")&& ($cwhoismode==1))
{
  if ($domain!="")
    $d1=$domain;
  if ($dex!="")
    $x1=$dex;
	$hstextcolor="#000000";
	$hstextsize="12pt";
	print "<div class=\"cwhoissimplestep2\"><form method=\"get\">\n";
  print "<input name=\"cwaction\" type=\"hidden\" value=\"hoststep2\">\n";
  print "<input name=\"h1\" type=\"hidden\" value=\"$h1\">\n";
  print "<input name=\"n\" type=\"hidden\" value=\"1\">\n";
  print "<input name=\"buy1\" type=\"hidden\" value=\"on\">\n";
  print "<input name=\"domexists\" type=\"hidden\" value=\"$domexists\">\n";
	print "<input name=\"page\" type=\"hidden\" value=\"\">\n";
	print "<table class=\"cwhoissimplestep2\">\n";
	print "<tr>\n";
	print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">".$lang['CheckDomain']."</p></td>\n";
	print "<td class=\"cwhoissimplestep2\"><input class=\"cwhoissimplestep2\" type=\"text\" name=\"d1\" maxlength=\"63\" size=\"20\" value=\"$d1\"><select class=\"cwhoissimplestep2\" name=\"x1\" size=\"1\">\n";
  // We need to add an option for each domain extension
  for ($j=0;$j<$numdomreg;$j++)
  {
    $dt=strtok($register[$j],",");
    if ($x1==$dt)
	    print "<option selected value=\"$dt\">$dt</option>\n";
    else
	    print "<option value=\"$dt\">$dt</option>\n";
  }
	print "</select><input class=\"cwhoissimplestep2\" type=\"button\" name=\"Check\" value=\"".$lang['CheckButton']."\" OnClick=\"Check2a(this.form);\">\n";
	print "</td>\n";
	print "</tr>\n";
  if ($d1!="")
  {
    // See if extension supported for registration and or transfers
    $registersupported=-1;
    $transfersupported=-1;
    $renewsupported=-1;
    for ($j=0;$j<$numdomreg;$j++)
    {
      if ($x1==strtok($register[$j],","))
      {
	      $registersupported=$j;
	      break;
      }
    }
    for ($j=0;$j<$numdomtran;$j++)
    {
      if ($x1==strtok($transfer[$j],","))
      {
	      $transfersupported=$j;
	      break;
      }
    }
    for ($j=0;$j<$numdomren;$j++)
    {
      if ($x1==strtok($renew[$j],","))
      {
	      $renewsupported=$j;
	      break;
      }
    }
		// Call cWhois to check availability
		// set_time_limit(60); // Insert if lookup takes longer than 30 seconds and times out
    $Reg="*"; // Flag to cWhois not to do second full whois for .com etc as not required here
    $i=cWhois($d1,$x1,$Reg);
    // See if premium domain name. If so get offered price
    $premium="";
    if ($i==6)
    {
      $pos=strpos(strtolower($register[$registersupported]),"premium");
      if (is_integer($pos))
      {
         $pos2=strpos($register[$registersupported],"=",$pos);
         $pos3=strpos($register[$registersupported],",",$pos);
         if (!is_integer($pos3))
           $pos3=strlen($register[$registersupported]);
         $premium=substr($register[$registersupported],$pos2+1,$pos3-$pos2-1);
      }
    }
    if ($i==0)
    {
			print "<tr>\n";
			print "<td>&nbsp;</td><td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">$d1$x1 ".$lang['IsAvailable']."</p></td>\n";
			print "</tr>\n";
			print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">".$lang['SelectReg']."</p></td>\n";
			print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">\n";
      print "<select class=\"cwhoissimplestep2\" size=\"1\" name=\"r1\">\n";
      // We need to add an option for each registration period
      strtok(getdomainprices($register,$registerspecial,$x1,$h1),",");
      $temp=strtok(","); // Get reg period string
      $regperiods=explode(":",$temp);
      $temp=strtok(","); // Get reg prices for each period
      // See whether there is another price for shorter domain names
      $otherprice=strtok(",");
      if (strtolower(substr($otherprice,0,6))=="length")
      {
        $pos=strpos($otherprice,"(");
        $pos2=strpos($otherprice,")");
        $len=substr($otherprice,$pos+1,$pos2-$pos-1);
        $minlen=strtok($len,"-");
        $maxlen=strtok("-");
        if ((strlen($d1)>=$minlen) && (strlen($d1)<=$maxlen))
        {
          $temp=strtok($otherprice,"=");
          $temp=strtok("=");
        }
      }
      $regprices=explode(":",$temp);
      for ($j=0;$j<count($regperiods);$j++)
      {
				print "<option value=\"$j\">".$lang['Register']." $regperiods[$j] ".$lang['Year']." $csymbol$regprices[$j]$csymbol2</option>\n";
      }
      print "</select>\n";
			print "</p></td>\n";
			print "</tr>\n";
		  print "<tr>\n";
		  print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">&nbsp;</p></td>\n";
		  print "<td class=\"cwhoissimplestep2\" align=\"right\"><p class=\"cwhoissimplestep2\"><input class=\"cwhoissimplestep2\" type=\"button\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\" OnClick=\"Continue2a(this.form,'$d1','$x1');\"></p></td>\n";
		  print "</tr>\n";
    }
    if (($i==6) && ($premium!=""))
    {
			print "<tr>\n";
			print "<td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">$d1$x1 ".$lang['IsPremium']."</p></td>\n";
			print "</tr>\n";
			print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">".$lang['SelectReg']."></p></td>\n";
			print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">\n";
      print "<select class=\"cwhoissimplestep2\" size=\"1\" name=\"r1\">\n";
      $pc=substr($premium,0,1);
      if (($pc=="+") || ($pc=="-") || ($pc=="*") || ($pc=="/"))
      {
        $premcost=$Reg[Count($Reg)-1];
        eval("\$premsell=".$premcost.$premium.";");
        $premsell=sprintf("%01.".$decimalplaces."f",$premsell);
      }
			print "<option value=\"premium$premsell\">".$lang['Register']." 1 ".$lang['Year']." $csymbol$premsell$csymbol2</option>\n";
      print "</select>\n";
			print "</p></td>\n";
			print "</tr>\n";
		  print "<tr>\n";
		  print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">&nbsp;</p></td>\n";
		  print "<td class=\"cwhoissimplestep2\" align=\"right\"><p><input class=\"cwhoissimplestep2\" type=\"button\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\" OnClick=\"Continue2a(this.form,'$d1','$x1');\"></p></td>\n";
		  print "</tr>\n";
    }
    if (($i==6) && ($premium==""))
    {
	      print "<tr>\n";
	      print "<td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">\n";
	      print $lang['NoPremium']."</p></td>\n";
	      print "</tr>\n";
				print "<tr><td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\">&nbsp;</td></tr>\n";
			  print "<tr>\n";
			  print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">&nbsp;</p></td>\n";
			  print "<td class=\"cwhoissimplestep2\" align=\"right\"><p class=\"cwhoissimplestep2\"><input class=\"cwhoissimplestep2\" type=\"button\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\" OnClick=\"Available(this.form);\"></p></td>\n";
			  print "</tr>\n";
    }
    if ($i==1)
    {
			print "<tr>\n";
			print "<td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">\n";
      print "$d1$x1 ".$lang['NotAvailable'];
      if ($allowlookup)
        print " (<a class=\"cwhoissimplestep2\" href=\"javascript: void whois('$d1$x1')\">".$lang['Lookup']."</a>)";
      print "</p></td>\n";
			print "</tr>\n";
      if (($transfersupported>=0) || ($renewsupported>=0))
      {
	      print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">".$lang['SelectTran']."</p></td>\n";
	      print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">\n";
	      print "<select class=\"cwhoissimplestep2\" size=\"1\" name=\"r1\">\n";
        if ($transfersupported>=0)
        {
	        // We need to add an option for each registration period
          strtok(getdomainprices($transfer,$transferspecial,$x1,$h1),",");
	        $temp=strtok(","); // Get reg period string
	        $regperiods=explode(":",$temp);
	        $temp=strtok(","); // Get transfer prices for each period
	        // See whether there is another price for shorter domain names
	        $otherprice=strtok(",");
	        if (strtolower(substr($otherprice,0,6))=="length")
	        {
	          $pos=strpos($otherprice,"(");
	          $pos2=strpos($otherprice,")");
	          $len=substr($otherprice,$pos+1,$pos2-$pos-1);
	          $minlen=strtok($len,"-");
	          $maxlen=strtok("-");
	          if ((strlen($d1)>=$minlen) && (strlen($d1)<=$maxlen))
	          {
	            $temp=strtok($otherprice,"=");
	            $temp=strtok("=");
	          }
	        }
	        $regprices=explode(":",$temp);
	        for ($j=0;$j<count($regperiods);$j++)
	        {
	          $t=$j+100;
	          print "<option value=\"$t\">".$lang['Transfer']." $regperiods[$j] ".$lang['Year']." $csymbol$regprices[$j]$csymbol2</option>\n";
	        }
        }
        if ($renewsupported>=0)
        {
	        // We need to add an option for each registration period
          strtok(getdomainprices($renew,$renewspecial,$x1,$h1),",");
	        $temp=strtok(","); // Get reg period string
	        $regperiods=explode(":",$temp);
	        $temp=strtok(","); // Get transfer prices for each period
	        // See whether there is another price for shorter domain names
	        $otherprice=strtok(",");
	        if (strtolower(substr($otherprice,0,6))=="length")
	        {
	          $pos=strpos($otherprice,"(");
	          $pos2=strpos($otherprice,")");
	          $len=substr($otherprice,$pos+1,$pos2-$pos-1);
	          $minlen=strtok($len,"-");
	          $maxlen=strtok("-");
	          if ((strlen($d1)>=$minlen) && (strlen($d1)<=$maxlen))
	          {
	            $temp=strtok($otherprice,"=");
	            $temp=strtok("=");
	          }
	        }
	        $regprices=explode(":",$temp);
	        for ($j=0;$j<count($regperiods);$j++)
	        {
	          $t=$j+200;
	          print "<option value=\"$t\">".$lang['Renew']." $regperiods[$j] ".$lang['Year']." $csymbol$regprices[$j]$csymbol2</option>\n";
	        }
        }
	      print "</select>\n";
	      print "</p></td>\n";
	      print "</tr>\n";
			  print "<tr>\n";
			  print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">&nbsp;</p></td>\n";
			  print "<td class=\"cwhoissimplestep2\" align=\"right\"><p class=\"cwhoissimplestep2\"><input class=\"cwhoissimplestep2\" type=\"button\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\" OnClick=\"Continue2a(this.form,'$d1','$x1');\"></p></td>\n";
			  print "</tr>\n";
      }
      else
      {
				print "<tr><td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\">&nbsp;</td></tr>\n";
			  print "<tr>\n";
			  print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">&nbsp;</p></td>\n";
			  print "<td class=\"cwhoissimplestep2\" align=\"right\"><p class=\"cwhoissimplestep2\"><input class=\"cwhoissimplestep2\" type=\"button\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\" OnClick=\"Available(this.form);\"></p></td>\n";
			  print "</tr>\n";
      }
    }
    if ($i==2)
    {
			print "<tr>\n";
			print "<td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">\n";
      print "We cannot register $d1$x1</p></td>\n";
			print "</tr>\n";
			print "<tr><td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\">&nbsp;</td></tr>\n";
		  print "<tr>\n";
		  print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">&nbsp;</p></td>\n";
		  print "<td class=\"cwhoissimplestep2\" align=\"right\"><p class=\"cwhoissimplestep2\"><input class=\"cwhoissimplestep2\" type=\"button\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\" OnClick=\"Available(this.form);\"></p></td>\n";
		  print "</tr>\n";
    }
    if ($i==3)
    {
			print "<tr>\n";
			print "<td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">\n";
      print $lang['NotValid']."</p></td>\n";
			print "</tr>\n";
			print "<tr><td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\">&nbsp;</td></tr>\n";
		  print "<tr>\n";
		  print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">&nbsp;</p></td>\n";
		  print "<td class=\"cwhoissimplestep2\" align=\"right\"><p class=\"cwhoissimplestep2\"><input class=\"cwhoissimplestep2\" type=\"button\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\" OnClick=\"Available(this.form);\"></p></td>\n";
		  print "</tr>\n";
    }
    if ($i==5)
    {
			print "<tr>\n";
			print "<td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">\n";
      print $lang['WhoisProblem']."</p></td>\n";
			print "</tr>\n";
			print "<tr><td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\">&nbsp;</td></tr>\n";
		  print "<tr>\n";
		  print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">&nbsp;</p></td>\n";
		  print "<td class=\"cwhoissimplestep2\" align=\"right\"><p><input class=\"cwhoissimplestep2\" type=\"button\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\" OnClick=\"Available(this.form);\"></p></td>\n";
		  print "</tr>\n";
    }
	}
  else
  {
		print "<tr><td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\">&nbsp;</td></tr>\n";
		print "<tr><td class=\"cwhoissimplestep2\">&nbsp;</td><td class=\"cwhoissimplestep2\">&nbsp;</td></tr>\n";
	  print "<tr>\n";
	  print "<td class=\"cwhoissimplestep2\"><p class=\"cwhoissimplestep2\">&nbsp;</p></td>\n";
	  print "<td class=\"cwhoissimplestep2\" align=\"right\"><p class=\"cwhoissimplestep2\"><input class=\"cwhoissimplestep2\" type=\"button\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\" OnClick=\"Available(this.form);\"></p></td>\n";
	  print "</tr>\n";
  }
	print "</table>\n";
	print "</form></div>\n";
}
if (($cwaction=="hoststep2") && ($domexists=="exists")&& ($cwhoismode==1))
{
  if ($domain!="")
    $d1=$domain;
  if ($dex!="")
    $d1=$domain.$dex;
	$hstextcolor="#000000";
	$hstextsize="12pt";
	print "<div class=\"cwhoissimplestep2b\"><form method=\"get\">\n";
  print "<input name=\"cwaction\" type=\"hidden\" value=\"hoststep2\">\n";
  print "<input name=\"h1\" type=\"hidden\" value=\"$h1\">\n";
  print "<input name=\"r1\" type=\"hidden\" value=\"-1\">\n";
  print "<input name=\"x1\" type=\"hidden\" value=\"\">\n";
  print "<input name=\"n\" type=\"hidden\" value=\"1\">\n";
  print "<input name=\"buy1\" type=\"hidden\" value=\"on\">\n";
  print "<input name=\"domexists\" type=\"hidden\" value=\"$domexists\">\n";
	print "<input name=\"page\" type=\"hidden\" value=\"\">\n";
	print "<table class=\"cwhoissimplestep2b\">\n";
	print "<tr>\n";
	print "<td  class=\"cwhoissimplestep2b\"><p class=\"cwhoissimplestep2b\">".$lang['DomainToHost']."</p></td>\n";
	print "<td  class=\"cwhoissimplestep2b\"><input class=\"cwhoissimplestep2b\" type=\"text\" name=\"d1\" maxlength=\"80\" size=\"30\" value=\"$d1\">\n";
	print "</td>\n";
	print "</tr>\n";
  if ($allowlookup)
  {
  	print "<tr>\n";
	  print "<td  class=\"cwhoissimplestep2b\"><p class=\"cwhoissimplestep2b\">".$lang['LookupOptional']."</p></td>\n";
    print "<td  class=\"cwhoissimplestep2b\"><input class=\"cwhoissimplestep2b\" type=\"button\" onClick=\"javascript: void whois(form.d1.value)\" value=\"".$lang['Lookup']."\"></td>\n";
	  print "</tr>\n";
  }
	print "<tr><td  class=\"cwhoissimplestep2b\">&nbsp;</td><td  class=\"cwhoissimplestep2b\" class=\"cwhoissimplestep2\">&nbsp;</td></tr>\n";
  print "<tr>\n";
  print "<td  class=\"cwhoissimplestep2b\"><p class=\"cwhoissimplestep2b\">&nbsp;</p></td>\n";
  print "<td  class=\"cwhoissimplestep2b\" align=\"right\"><p><input class=\"cwhoissimplestep2b\" type=\"button\" name=\"formbutton1\" value=\"".$lang['ContinueButton']."\" OnClick=\"Continue2b(this.form);\"></p></td>\n";
  print "</tr>\n";
	print "</table>\n";
	print "</form></div>\n";
}

// If adding to shopping cart
if (($cwaction=="add") || ($cwaction=="addout"))
{
	for ($k=1;$k<=$n;$k++)
	{
		$var="buy".$k;
		if (strcasecmp($$var,"on")==0)
		{
			// Check for duplicate domain
			$dupl=False;
			$dvar="d".$k;
			$xvar="x".$k;
			for ($j=1;$j<=$_SESSION['numberofitems'];$j++)
			{
				if (($_SESSION['domain'.$j]==$$dvar.$$xvar) && ($_SESSION['removed'.$j]==False))
				{
					$dupl=True;
					break;
				}
			}
			if ($dupl==False)
			{
				// Get hosting options for this entry
				$hvar="h".$k;
				if ($$hvar>-1)
				{
					$hostdesc=strtok($host[$$hvar],",");
					$hostsetup=strtok(",");
					$hostprice=strtok(",");
					$hostrecurr=strtoupper(strtok(","));
				}
				else
				{
					$hostdesc="";
					$hostsetup=0;
					$hostprice=0;
					$hostrecurr="";
				}
				// Get domain options for this entry
				$rvar="r".$k;
				if ($$rvar==-1)
				{
					$regtype="H";
					$regprice=0;
					$regperiod=0;
				}
				if (($$rvar>-1) && ($$rvar<100))
				{
					$regtype="R"; // Register domain
          strtok(getdomainprices($register,$registerspecial,$$xvar,$$hvar),",");
					$temp=strtok(","); // Get reg period string
					$regperiods=explode(":",$temp);
					$regperiod=$regperiods[$$rvar];
					$temp=strtok(","); // Get reg prices for each period
          // See whether there is another price for shorter domain names
          $otherprice=strtok(",");
          if (strtolower(substr($otherprice,0,6))=="length")
          {
            $pos=strpos($otherprice,"(");
            $pos2=strpos($otherprice,")");
            $len=substr($otherprice,$pos+1,$pos2-$pos-1);
            $minlen=strtok($len,"-");
            $maxlen=strtok("-");
            if ((strlen($$dvar)>=$minlen) && (strlen($$dvar)<=$maxlen))
            {
              $temp=strtok($otherprice,"=");
              $temp=strtok("=");
            }
          }
					$regprices=explode(":",$temp);
					$regprice=$regprices[$$rvar];
				}
				if (($$rvar>=100) && ($$rvar<200))
				{
					$regtype="T"; // Transfer domain
          strtok(getdomainprices($transfer,$transferspecial,$$xvar,$$hvar),",");
					$temp=strtok(","); // Get reg period string
					$regperiods=explode(":",$temp);
					$regperiod=$regperiods[$$rvar-100];
					$temp=strtok(","); // Get reg prices for each period
          // See whether there is another price for shorter domain names
          $otherprice=strtok(",");
          if (strtolower(substr($otherprice,0,6))=="length")
          {
            $pos=strpos($otherprice,"(");
            $pos2=strpos($otherprice,")");
            $len=substr($otherprice,$pos+1,$pos2-$pos-1);
            $minlen=strtok($len,"-");
            $maxlen=strtok("-");
            if ((strlen($$dvar)>=$minlen) && (strlen($$dvar)<=$maxlen))
            {
              $temp=strtok($otherprice,"=");
              $temp=strtok("=");
            }
          }
					$regprices=explode(":",$temp);
					$regprice=$regprices[$$rvar-100];
				}
				if (($$rvar>=200) && ($$rvar<300))
				{
					$regtype="N"; // Renew domain
          strtok(getdomainprices($renew,$renewspecial,$$xvar,$$hvar),",");
					$temp=strtok(","); // Get reg period string
					$regperiods=explode(":",$temp);
					$regperiod=$regperiods[$$rvar-200];
					$temp=strtok(","); // Get reg prices for each period
          // See whether there is another price for shorter domain names
          $otherprice=strtok(",");
          if (strtolower(substr($otherprice,0,6))=="length")
          {
            $pos=strpos($otherprice,"(");
            $pos2=strpos($otherprice,")");
            $len=substr($otherprice,$pos+1,$pos2-$pos-1);
            $minlen=strtok($len,"-");
            $maxlen=strtok("-");
            if ((strlen($$dvar)>=$minlen) && (strlen($$dvar)<=$maxlen))
            {
              $temp=strtok($otherprice,"=");
              $temp=strtok("=");
            }
          }
					$regprices=explode(":",$temp);
					$regprice=$regprices[$$rvar-200];
				}
				if (strtolower(substr($$rvar,0,7))=="premium")
				{
					$regtype="R"; // Register domain
          $regperiod=1; // premium registrations are for one year
          $regprice=substr($$rvar,7);
        }
				// If hosting package, domain registration, transfer or renew ordered then add to shopping cart
				if (($hostdesc!="") || ($regtype!="H"))
				{
					$_SESSION['numberofitems']++;
					$numitems=$_SESSION['numberofitems'];
					$_SESSION['removed'.$numitems]=False;
	        $_SESSION['domain'.$numitems]=$$dvar.$$xvar;
	        $_SESSION['hostdesc'.$numitems]=$hostdesc;
	        $_SESSION['hostsetup'.$numitems]=$hostsetup;
	        $_SESSION['hostprice'.$numitems]=$hostprice;
	        $_SESSION['hostrecurr'.$numitems]=$hostrecurr;
	        $_SESSION['regtype'.$numitems]=$regtype;
	        $_SESSION['regperiod'.$numitems]=$regperiod;
	        $_SESSION['regprice'.$numitems]=$regprice;
				}
			}
		}
	}
  if (($cwaction=="addout") && (($_SESSION['numberofitems']-$_SESSION['numberremoved'])<1))
  	$cwaction="add";
}
// If removing items from shopping cart
if (($cwaction=="remove") || ($cwaction=="checkout"))
{
	for ($k=1;$k<=$_SESSION['numberofitems'];$k++)
	{
    $rvar="rem".$k;
    if (strcasecmp($$rvar,"on")==0)
    {
	    $_SESSION['numberremoved']++;
	    $_SESSION['removed'.$k]=True;
    }
	}
  if (($cwaction=="checkout") && (($_SESSION['numberofitems']-$_SESSION['numberremoved'])<1))
  	$cwaction="remove";
}
// Display checkbox for each domain supported
if (($cwaction=="check") || ($cwaction=="remove") || ($cwaction=="add")  || (($cwaction=="") && ($cwhoismode==0)))
{
	$cbtextcolor="#000000";
	$cbtextsize="10pt";
	// For conformity make domain lowercase and trim whitespace
	$domain=accentstrtolower(trim($domain));
	if ($spacetohyphen==true)
		$domain=str_replace(" ","-",$domain);
	// Also remove www., http://www or https://
	if (strcmp("https://www.",substr($domain,0,12))==0)
		$domain=substr($domain,11,strlen($domain)-11);
	if (strcmp("http://www.",substr($domain,0,11))==0)
 		$domain=substr($domain,11,strlen($domain)-11);
	if (strcmp("www.",substr($domain,0,4))==0)
		$domain=substr($domain,4,strlen($domain)-4);
	// If no checkbox selected then tick first checkbox
	if ($cwaction=="check")
	{
	  $numdt=0;
	  for ($k=1;$k<=$numdomreg;$k++)
	  {
		  $var="cb".$k;
		  if (strcasecmp($$var,"on")==0)
	  	$numdt++;
	  }
	  // If no checkboxes selected then see if user entered domain extension
	  $domainext="";
	  if ($numdt==0)
	  {
	    if ($dropdownsearch)
	    {
	      $domain=$domain.$dropdownext;
	    }
	  	$found=0;
	  	// Try to get domain extension if one is typed
	  	// Treat .name slightly differently
	  	if (substr($domain,strlen($domain)-5,5)==".name")
	  	{
	  		$domainext=".name";
	  		$domain=substr($domain,0,strlen($domain)-5);
	  		$found=1;
	  	}
	  	else
	  	{
	  		// Get domain extension by taking everything from first '.'
	  		$pos=strpos($domain,".");
	  		if (is_integer($pos))
	  		{
	  			$pdomt=substr($domain,$pos,strlen($domain)-$pos);
	  			for ($k=0;$k<count($dtd);$k++)
	  			{
	  				$dt=strtok($dtd[$k],",");
	  				if (strcasecmp($dt,$pdomt)==0)
	  				{
	  					$domainext=$pdomt;
	  					$domain=substr($domain,0,$pos);
	  					$found=1;
	  					break;
	  				}
	  			}
	  		}
	  	}
	  	// If found=0 then it means user didn't type a recognised domain extension
	  	// so if domain contains a dot then assume everything after it is the extension.
	  	if ($found==0)
	  	{
	  		$pos=strpos($domain,".");
	  		if (is_integer($pos))
	  		{
					$domainext=substr($domain,$pos,strlen($domain)-$pos);
	  			$domain=substr($domain,0,$pos);
	  		}
	  	}
	  	// If no extension type (and no checkbox clicked) then either select all or tick first box
      if ($domainext=="")
      {
      	if ($AssumeAll)
      	{
	        for ($k=1;$k<=$numdomreg;$k++)
	        {
	          $var="cb".$k;
	          $$var="on";
	        }
      	}
        else
        	$cb1="on";
      }
	  }
	}
	// Create table with one checkbox per domain extension
	$twidth=$columns*120;
	if ($dropdownsearch)
	  $twidth=$twidth+100;
	print("<div class=\"cwhoissearch\">\n");
	if (($cwaction=="check") && ($SearchTuring) && ($_SESSION['captchasearchcheck']))
  {
    $turingmatch=false;
    if ((strtolower($_SESSION['ses_cwhoisturingcode'])==strtolower(trim($turing))) && ($_SESSION['ses_cwhoisturingcode']!=""))
    {
      $turingmatch=true;
      $_SESSION['ses_cwhoisturingcode']="";
    }
    else if ((strtolower($_SESSION['ses_cwhoispreviousturingcode'])==strtolower(trim($turing))) && ($_SESSION['ses_cwhoispreviousturingcode']!=""))
    {
      $turingmatch=true;
      $_SESSION['ses_cwhoispreviousturingcode']="";
    }
    if (!$turingmatch)
    {
//      print $lang['TuringCodeRequired']."<br>\n";
      $cwaction="";
    }
    else
    {
      $_SESSION['captchasearchcount']=0;
//      $_SESSION['captchasearchcheck']=false;
    }
  }
  if ($cwaction=="check")
    $_SESSION['captchasearchcount']++;
	print("<form name=\"searchform\" method=\"get\" OnSubmit=\"return CheckIt(this);\">\n");
	print("<table class=\"cwhoissearch\">\n");
	print("<tr>\n");
	print("<td  class=\"cwhoissearch\"><small class=\"cwhoissearch\">\n");
  $showsearchturing=false;
  if ($SearchTuring)
  {
    if ($SearchTuringCount>0)
    {
      if (($_SESSION['captchasearchcount']==0) || ($_SESSION['captchasearchcount']>=$SearchTuringCount))
        $showsearchturing=true;
    }
    if ($SearchTuringTime>0)
    {
      if (($_SESSION['captchasearchspeed']!=-1) && ($_SESSION['captchasearchspeed']<=$SearchTuringTime))
        $showsearchturing=true;
    }
  }
  if ($showsearchturing)
  {
    $_SESSION['captchasearchcheck']=true;
	  print($lang['TuringCodeRequired']."<br>\n");
    print("<input class=\"cwhoissearch\" type=\"text\" name=\"turing\" id=\"turing\" size=\"3\">&nbsp;<img class=\"cwhoiscontact\" id=\"turingimage\" name=\"turingimage\" src=\"".$FilePath."turingimagecw.php\" align=\"absmiddle\" width=\"60\" height=\"30\"><br><br>\n");
  }
  else
    $_SESSION['captchasearchcheck']=false;
  print "<br/></br>";
  print "<br/></br>";
  if ($lang['TipLine1']!="")
	  print($lang['TipLine1']."<BR>\n");
  if ($lang['TipLine2']!="")
	  print($lang['TipLine2']."\n");
	print("</small></td>\n");
	print("</tr>\n");
	print("<tr>\n");
	print("<td  class=\"cwhoissearch\">\n");
	print("<input type=\"hidden\" name=\"cwaction\" value=\"\">");
	if ($dropdownsearch==true)
	{
	  $hidecheckboxes=true;
	  print("<input type=\"text\" name=\"domain\" value=\"$domain\"size=\"30\" class=\"cwhoissearch\">&nbsp;\n");
    print ("<select name=\"dropdownext\" class=\"cwhoissearch\">\n");
    for ($k=1;$k<=$numdomreg;$k++)
    {
      $dt=strtok($register[$k-1],",");
      print ("<option value=\"$dt\"");
      if ($domainext==$dt)
        print " selected ";
      print (">$dt</option>\n");
	  }
	  if ($AssumeAll)
	  {
      print ("<option value=\"\"");
      if (($domainext=="") && ($cwaction=="check"))
        print " selected ";
      print (">All</option>\n");
	  }
    print ("</select>&nbsp\n");
	  print("<input type=\"submit\" name=\"Check\" value=\"".$lang['CheckButton']."\" class=\"cwhoissearch\">\n");
	}
	else
	{
	  print("<input type=\"text\" name=\"domain\" value=\"$domain$domainext\"size=\"30\" class=\"cwhoissearch\">&nbsp;&nbsp;<input type=\"submit\" name=\"Check\" value=\"".$lang['CheckButton']."\" class=\"cwhoissearch\">\n");
	}
	print("</td>\n");
	print("</tr>\n");
  if ($suggestdomains==1)
  {
	  print "<tr>\n";
	  print "<td  class=\"cwhoissearch\">\n";
	  print "<p class=\"cwhoissearch\"><input type=\"checkbox\" name=\"domsug\" value=\"on\" class=\"cwhoissearch\"";
	  if ($domsug=="on")
	    print " checked";
	  print " onClick=\"suggestclicked();\">\n";
	  print $lang['SuggestDomain']."</p>\n";
	  print "</td>\n";
	  print "<td  class=\"cwhoissearch\">\n";
	  print "<p>&nbsp;</p>\n";
	  print "</td>\n";
	  print "</tr>\n";
	  if ($numsugcat>1)
	  {
	    print "<tr>\n";
	    print "<td  class=\"cwhoissearch\">\n";
	    print "<p  class=\"cwhoissearch\"><select name=\"suggestcat\" size=\"1\"  class=\"cwhoissearch\"";
	    if ($domsug!="on")
		    print "disabled";
	    print ">\n";
      for ($k=0;$k<$numsugcat;$k++)
      {
		    print "<option ";
		    if ($suggestcat==$suggestcategory[$k])
		       print "selected ";
		    print "value=\"".$suggestcategory[$k]."\">".$suggestcategory[$k]."</option>\n";
		  }
	    print "</select> ".$lang['SuggestCategory']."</p>\n";
	    print "</td>\n";
	    print "<td  class=\"cwhoissearch\">\n";
	    print "<p>&nbsp;</p>\n";
	    print "</td>\n";
	    print "</tr>\n";
	  }
  }
	print("</table></div>");
	if ($hidecheckboxes==true)
	{
    for ($k=1;$k<=$numdomreg;$k++)
    {
//      print("<input type=\"hidden\" name=\"cb$k\" value=\"on\">\n");
    }
	}
	else
	{
	  print("<div class=\"cwhoischeckbox\"> <table class=\"cwhoischeckbox\" >\n");
	  $twidth=intval((1/$columns)*100);
	  $de=1;
	  while($de<=$numdomreg)
	  {
	    print("<tr>\n");
	    for ($c=1;$c<=$columns;$c++)
	    {
//	      print("<td width=\"$twidth%\">\n");
	      print("<td class=\"cwhoischeckbox\">\n");
	      if ($de<=$numdomreg)
	      {
	        $dt=strtok($register[$de-1],",");
	        print("<p class=\"cwhoischeckbox\"><input type=\"checkbox\" name=\"cb$de\" class=\"cwhoischeckbox\"");
	        if ($cwaction=="check")
	        {
	          $var="cb".$de;
	          if (strcasecmp($$var,"on")==0)
	          print(" checked ");
	        }
	        else
	        {
	          $var="cb".$de;
	          $$var="";
	        }
	        print ">\n";
	        print("$dt</p>\n");
	        print "\n";
	        $de++;
	        print("</td>\n");
	      }
	    }
	    print("</tr>\n");
	  }
	  print("</table></div>");
	}
	print("</form>\n");
}
// If check clicked then create table of results
if ($cwaction=="check")
{
  if ($_SESSION['captchasearchtime']>0)
    $_SESSION['captchasearchspeed']=time()-$_SESSION['captchasearchtime'];
  $_SESSION['captchasearchtime']=time();
	$crtextcolor="#000000";
	$crtextsize="10pt";
	if ($spacetohyphen==true)
		$domain=str_replace(" ","-",$domain);
	print("<div class=\"cwhoisresults\"> <form method=\"get\">\n");
	print("<table class=\"cwhoisresults\" >\n");
	print "<input class=\"cwhoisresults\" name=\"cwaction\" type=\"hidden\" value=\"\">\n";
	print "<input class=\"cwhoisresults\" name=\"page\" type=\"hidden\" value=\"\">\n";
	// Column headings
	print("<tr>\n");
	print("<td   class=\"cwhoisresults\">\n");
	print("<b class=\"cwhoisresults\">".$lang['Domain']."</b>");
	print("</td>\n");

	print("<td   class=\"cwhoisresults\">\n");
	print("<b class=\"cwhoisresults\">".$lang['Available']."</b>");
	print("</td>\n");

	print("<td   class=\"cwhoisresults\">\n");
	print("<b class=\"cwhoisresults\">".$lang['DomainOpt']."</b>");
	print("</td>\n");

  if ($NarrowCart!=true)
  {
  	print("<td   class=\"cwhoisresults\">\n");
  	if ($numhost>0)
  		print("<b class=\"cwhoisresults\">".$lang['HostingOpt']."</b>");
  	else
  		print("&nbsp;");
  	print("</td>\n");
  }


	print("<td   class=\"cwhoisresults\">\n");
	print("<b class=\"cwhoisresults\">".$lang['Buy']."</b>");
	print("</td>\n");
	print("</tr>\n");
	// If user entered domain extension then just deal with that one domain.
	// If not then we should deal with each domain checkbox that is set
	if ($domainext!="")
		$max=1;
	else
		$max=$numdomreg;
	$canbuy=0;
	for ($k=1;$k<=$max;$k++)
	{
	  $var="cb".$k;
	  if ((strcasecmp($$var,"on")==0) || ($domainext!=""))
	  {
	    print("<tr>\n");
	    if ($domainext!="")
		    $dt=$domainext;
	    else
		    $dt=strtok($register[$k-1],",");
		  $canbuy+=displaydomain($k,$domain,$dt,$DefaultBuy);
	  }
	}
	if (($suggestdomains==1) && ($domsug=="on"))
	{
	  print("<tr>\n");
	  print("<td   class=\"cwhoisresults\" colspan=5 align=\"right\">\n");
    print("<HR class=\"cwhoisresults\">\n");
	  print("</td>\n");
	  print("</tr>\n");
	  $numsuggest=count($suggest);
	  $row=$max+1;
	  $tried=0;
    for ($sug=0;$sug<$numsuggest;$sug++)
    {
      $c=strtok($suggest[$sug],",");
      $d=strtok(",");
      if (($c==$suggestcat) || ($numsugcat<2))
      {
	      $sugdomain=str_replace("%",$domain,$d);
	      for ($k=1;$k<=$max;$k++)
	      {
	        $var="cb".$k;
	        if ((strcasecmp($$var,"on")==0) || ($domainext!=""))
	        {
	          print("<tr>\n");
	          if ($domainext!="")
	            $dt=$domainext;
	          else
	            $dt=strtok($register[$k-1],",");
	          $canbuy+=displaydomain($row,$sugdomain,$dt,0);
	          $tried++;
	          $row++;
	        }
	      }
	    }
      if ($tried>=$suggestmax)
       	break;
    }
    $max=$row;
	}
	print("<tr>\n");
	print("<td   class=\"cwhoisresults\" colspan=5 align=\"right\">\n");
	if ($canbuy>0)
	{
		print ("<input class=\"cwhoisresults\" name=\"n\" type=\"hidden\" value=\"$max\">\n");
		print("<input class=\"cwhoisresults\" type=\"button\" value=\"".$lang['AddButton']."\" OnClick=\"AddToCart(this.form);\">&nbsp;&nbsp;<input class=\"cwhoisresults\" type=\"button\" value=\"".$lang['CheckoutButton']."\" OnClick=\"AddOut(this.form);\">\n");
	}
	print("</td>\n");
	print("</tr>\n");
	print("</table>\n");
	print("</form></div>\n");
  if ($showwarning==1)
  {
	  print "<script language=\"JavaScript\">\n";
	  print "<!-- JavaScript\n";
	  print "HideWarning()\n";
	  print "// - JavaScript - -->\n";
	  print "</script>\n";
	}
}

function displaydomain($k,$domain,$dt,$checkbuy)
{
	global $lang,$numdomreg,$numdomtran,$numdomren,$numhost,$register,$transfer,$renew,$host,$csymbol,$csymbol2;
	global $crtextcolor,$crtextsize,$AllowNoHosting,$AllowHostingOnly,$decimalplaces;
	global $NarrowCart,$allowlookup;
	global $registerspecial,$transferspecial,$renewspecial;
  // See if extension supported for registration and or transfers
  $registersupported=-1;
  $transfersupported=-1;
  $renewsupported=-1;
  for ($j=0;$j<$numdomreg;$j++)
  {
    if ($dt==strtok($register[$j],","))
    {
      $registersupported=$j;
      break;
    }
  }
  for ($j=0;$j<$numdomtran;$j++)
  {
    if ($dt==strtok($transfer[$j],","))
    {
      $transfersupported=$j;
      break;
    }
  }
  for ($j=0;$j<$numdomren;$j++)
  {
    if ($dt==strtok($renew[$j],","))
    {
      $renewsupported=$j;
      break;
    }
  }
  // set_time_limit(60); // Insert if lookup takes longer than 30 seconds and times out
  $Reg="*"; // Flag to cWhois not to do second full whois for .com etc as not required here
  $i=cWhois($domain,$dt,$Reg);
//  $i=0;
  // See if premium domain name. If so get offered price
  $premium="";
  if ($i==6)
  {
    $pos=strpos(strtolower($register[$registersupported]),"premium");
    if (is_integer($pos))
    {
       $pos2=strpos($register[$registersupported],"=",$pos);
       $pos3=strpos($register[$registersupported],",",$pos);
       if (!is_integer($pos3))
         $pos3=strlen($register[$registersupported]);
       $premium=substr($register[$registersupported],$pos2+1,$pos3-$pos2-1);
    }
  }
  print ("<input name=\"d$k\" type=\"hidden\" value=\"$domain\">\n");
  print ("<input name=\"x$k\" type=\"hidden\" value=\"$dt\">\n");
  // Domain column
  print("<td   class=\"cwhoisresults\"><p class=\"cwhoisresults\">\n");
  print("$domain$dt");
  print("</p></td>\n");

  // Available column
  print("<td   class=\"cwhoisresults\"><p class=\"cwhoisresults\">\n");
  if ($i==0)
    print($lang['Yes']);
  if ($i==6)
    print($lang['Premium']);
  if ($i==1)
  {
    print($lang['No']);
    if ($allowlookup)
      print (" (<a class=\"cwhoisresults\" href=\"javascript: void whois('$domain$dt')\">".$lang['Lookup']."</a>)");
  }
  if ($i==2)
    print($lang['CannotVerify']);
  if ($i==3)
    print($lang['InvalidDomain']);
  if ($i==5)
    print($lang['WhoisProblem']);
  print("</p></td>\n");

  // Domain Options column
  print("<td   class=\"cwhoisresults\">\n");
  if (($i==0) && ($registersupported>=0))
  {
    print "<select class=\"cwhoisresults\" size=\"1\" name=\"r$k\" id=\"domainselectmenu$k\">\n";
    if (($numhost>0) && ($AllowHostingOnly==true))
      print "<option value=\"-1\">".$lang['HostingOnly']."</option>\n";
    // We need to add an option for each registration period
    // See if special pricing required
    if (($AllowNoHosting) || ($numhost<1))
      strtok($register[$registersupported],",");
    else
    {
      strtok(getdomainprices($register,$registerspecial,$dt,0),",");
    }
    $temp=strtok(","); // Get reg period string
    $regperiods=explode(":",$temp);
    $temp=strtok(","); // Get reg prices for each period
    // See whether there is another price for shorter domain names
    $otherprice=strtok(",");
    if (strtolower(substr($otherprice,0,6))=="length")
    {
      $pos=strpos($otherprice,"(");
      $pos2=strpos($otherprice,")");
      $len=substr($otherprice,$pos+1,$pos2-$pos-1);
      $minlen=strtok($len,"-");
      $maxlen=strtok("-");
      if ((strlen($domain)>=$minlen) && (strlen($domain)<=$maxlen))
      {
        $temp=strtok($otherprice,"=");
        $temp=strtok("=");
      }
    }
    $regprices=explode(":",$temp);
    for ($j=0;$j<count($regperiods);$j++)
    {
      if ($j==0)
        print "<option selected value=\"$j\" >".$lang['Register']." $regperiods[$j] ".$lang['Year']."</option>\n";
      else
        print "<option value=\"$j\">".$lang['Register']." $regperiods[$j] ".$lang['Year']."</option>\n";
    }
    print "</select>\n";
  }
  if (($i==0) && ($registersupported==-1))
  {
    if (($numhost>0) && ($AllowHostingOnly==true))
    {
      print "<select class=\"cwhoisresults\" size=\"1\" name=\"r$k\" id=\"domainselectmenu$k\">\n";
      print "<option value=\"-1\">".$lang['HostingOnly']."</option>\n";
      print "</select>\n";
    }
    else
      print("&nbsp;");
  }
  if (($i==6) && ($registersupported>=0) && ($premium!=""))
  {
    print "<select class=\"cwhoisresults\" size=\"1\" name=\"r$k\" id=\"domainselectmenu$k\">\n";
    if (($numhost>0) && ($AllowHostingOnly==true))
      print "<option value=\"-1\">".$lang['HostingOnly']."</option>\n";
    $pc=substr($premium,0,1);
    if (($pc=="+") || ($pc=="-") || ($pc=="*") || ($pc=="/"))
    {
      $premcost=$Reg[Count($Reg)-1];
      eval("\$premsell=".$premcost.$premium.";");
      $premsell=sprintf("%01.".$decimalplaces."f",$premsell);
      print "<option selected value=premium$premsell>".$lang['Register']." 1 ".$lang['Year']."</option>\n";
    }
    print "</select>\n";
  }
  if (($i==6) && (($registersupported==-1) || ($premium=="")))
  {
    if (($numhost>0) && ($AllowHostingOnly==true))
    {
      print "<select class=\"cwhoisresults\" size=\"1\" name=\"r$k\" id=\"domainselectmenu$k\" >\n";
      print "<option value=\"-1\">".$lang['HostingOnly']."</option>\n";
      print "</select>\n";
    }
    else
      print("&nbsp;");
  }
  if (($i==1) && (($transfersupported>=0) || ($renewsupported>=0)))
  {
    print "<select class=\"cwhoisresults\" size=\"1\" name=\"r$k\" id=\"domainselectmenu$k\" >\n";
    if (($numhost>0) && ($AllowHostingOnly==true))
      print "<option value=\"-1\">".$lang['HostingOnly']."</option>\n";
    // We need to add an option for each registration period if transfer supported
    if ($transfersupported>=0)
    {
      // See if special pricing required
      if ($numhost<1)
        strtok($transfer[$transfersupported],",");
      else
        strtok(getdomainprices($transfer,$transferspecial,$dt,0),",");
      $temp=strtok(","); // Get transfer period string
      $regperiods=explode(":",$temp);
      $temp=strtok(","); // Get transfer prices for each period
      // See whether there is another price for shorter domain names
      $otherprice=strtok(",");
      if (strtolower(substr($otherprice,0,6))=="length")
      {
        $pos=strpos($otherprice,"(");
        $pos2=strpos($otherprice,")");
        $len=substr($otherprice,$pos+1,$pos2-$pos-1);
        $minlen=strtok($len,"-");
        $maxlen=strtok("-");
        if ((strlen($domain)>=$minlen) && (strlen($domain)<=$maxlen))
        {
          $temp=strtok($otherprice,"=");
          $temp=strtok("=");
        }
      }
      $regprices=explode(":",$temp);
      for ($j=0;$j<count($regperiods);$j++)
      {
        $t=$j+100;
        print "<option value=\"$t\">".$lang['Transfer']." $regperiods[$j] ".$lang['Year']."</option>\n";
      }
    }
    // We need to add an option for each registration period if renew supported
    if ($renewsupported>=0)
    {
      // See if special pricing required
      if ($numhost<1)
        strtok($renew[$renewsupported],",");
      else
        strtok(getdomainprices($renew,$renewspecial,$dt,0),",");
      $temp=strtok(","); // Get renew period string
      $regperiods=explode(":",$temp);
      $temp=strtok(","); // Get renew prices for each period
      // See whether there is another price for shorter domain names
      $otherprice=strtok(",");
      if (strtolower(substr($otherprice,0,6))=="length")
      {
        $pos=strpos($otherprice,"(");
        $pos2=strpos($otherprice,")");
        $len=substr($otherprice,$pos+1,$pos2-$pos-1);
        $minlen=strtok($len,"-");
        $maxlen=strtok("-");
        if ((strlen($domain)>=$minlen) && (strlen($domain)<=$maxlen))
        {
          $temp=strtok($otherprice,"=");
          $temp=strtok("=");
        }
      }
      $regprices=explode(":",$temp);
      for ($j=0;$j<count($regperiods);$j++)
      {
        $t=$j+200;
        print "<option value=\"$t\">".$lang['Renew']." $regperiods[$j] ".$lang['Year']."</option>\n";
      }
    }
    print "</select>\n";
  }
  if (($i==1) && ($transfersupported==-1) && ($renewsupported==-1))
  {
    if (($numhost>0) && ($AllowHostingOnly==true))
    {
      print "<select class=\"cwhoisresults\" size=\"1\" name=\"r$k\" id=\"domainselectmenu$k\" >\n";
      print "<option value=\"-1\">".$lang['HostingOnly']."</option>\n";
      print "</select>\n";
    }
    else
      print("&nbsp;");
  }
  if (($i==2) || ($i==5))
  {
    if (($numhost>0) && ($AllowHostingOnly==true))
    {
      print "<select class=\"cwhoisresults\" size=\"1\" name=\"r$k\" id=\"domainselectmenu$k\" >\n";
      print "<option value=\"-1\">".$lang['HostingOnly']."</option>\n";
      print "</select>\n";
    }
    else
      print("&nbsp;");
  }
  if ($i>2)
  {
    print("&nbsp;");
  }
  print("</td>\n");
  // Hosting Options column
  if ($NarrowCart)
    print "<tr><td   class=\"cwhoisresults\">&nbsp;</td><td   class=\"cwhoisresults\">&nbsp;</td>\n";
  print("<td   class=\"cwhoisresults\">\n");
  if ($numhost>0)
  {
    if (($i==0) || ($i==6))
    {
      print "<select class=\"cwhoisresults\" size=\"1\" name=\"h$k\" id=\"hostingselectmenu$k\" onChange=\"hostingplanchange('".$domain."','".$dt."','".$k."')\">\n";
      // If we can register the domain also give option for no hosting
      if ($AllowNoHosting==true)
      {
	      if ((($i==0) && ($registersupported!=-1)) || (($i==6) && ($premium!="")))
	        print "<option disabled value=\"-1\">".$lang['NoHosting']."</option>\n";
      }
      // We need to add an option for each hosting package
      for ($j=0;$j<$numhost;$j++)
      {
        $hostdesc=strtok($host[$j],",");
        $hostsetup=strtok(",");
        $hostprice=strtok(",");
        $hostrecurr=strtoupper(strtok(","));
        if ($hostsetup==0)
          print "<option value=\"$j\">$hostdesc $csymbol$hostprice$csymbol2</option>\n";
        else
          print "<option value=\"$j\">$hostdesc $csymbol$hostprice$csymbol2 (".$lang['Setup']." $csymbol$hostsetup$csymbol2)</option>\n";
      }
      print "</select>\n";
    }
    if (($i==1) && ($AllowHostingOnly))
    {
      print "<select class=\"cwhoisresults\" size=\"1\" name=\"h$k\" id=\"hostingselectmenu$k\" onChange=\"hostingplanchange('".$domain."','".$dt."','".$k."')\">\n";
      // If we can transfer or renew the domain also give option for no hosting
      if ($AllowNoHosting==true)
      {
	      if (($transfersupported!=-1) || ($renewsupported!=-1))
	        print "<option disabled value=\"-1\">".$lang['NoHosting']."</option>\n";
      }
      // We need to add an option for each hosting package
      for ($j=0;$j<$numhost;$j++)
      {
        $hostdesc=strtok($host[$j],",");
        $hostsetup=strtok(",");
        $hostprice=strtok(",");
        $hostrecurr=strtoupper(strtok(","));
        if ((($transfersupported!=-1) || ($renewsupported!=-1)) && ($j==0))
          $select="selected";
        else
          $select="";
        if ($hostsetup==0)
          print "<option $select value=\"$j\">$hostdesc $csymbol$hostprice$csymbol2</option>\n";
        else
          print "<option $select value=\"$j\">$hostdesc $csymbol$hostprice$csymbol2 (".$lang['Setup']." $csymbol$hostsetup$csymbol2)</option>\n";
      }
      print "</select>\n";
    }
    if (($i==2) || ($i==5) && ($AllowHostingOnly))
    {
      print "<select class=\"cwhoisresults\" size=\"1\" name=\"h$k\" id=\"hostingselectmenu$k\">\n";
      // We need to add an option for each hosting package
      for ($j=0;$j<$numhost;$j++)
      {
        $hostdesc=strtok($host[$j],",");
        $hostsetup=strtok(",");
        $hostprice=strtok(",");
        $hostrecurr=strtoupper(strtok(","));
        if ($hostsetup==0)
          print "<option value=\"$j\">$hostdesc $csymbol$hostprice$csymbol2</option>\n";
        else
          print "<option value=\"$j\">$hostdesc $csymbol$hostprice$csymbol2 (".$lang['Setup']." $csymbol$hostsetup$csymbol2)</option>\n";
      }
      print "</select>\n";
    }
    if ($i>2)
    {
      print("&nbsp;");
    }
  }
  else
    print("<input name=\"h$k\" type=\"hidden\" value=\"-1\">\n");
  print("</td>\n");
  // Buy checkbox column
  print("<td   class=\"cwhoisresults\" align=\"center\">\n");
  $allowbuy=0;
  $allowregistration=0;
  if (($i==0) && (($registersupported>-1) || ($numhost>0)))
    $allowbuy=1;
  if ($i==1)
  {
    if (($AllowHostingOnly==true) && ($numhost>0))
      $allowbuy=1;
    if (($transfersupported>-1) || ($renewsupported>-1))
      $allowbuy=1;
  }
  if (($i==2) && ($numhost>0) && ($AllowHostingOnly==true))
    $allowbuy=1;
  if (($i==5) && ($numhost>0) && ($AllowHostingOnly==true))
    $allowbuy=1;
  if (($i==6) && (($premium!="") || ($numhost>0)))
    $allowbuy=1;
  if (($i==0) && ($registersupported>-1))
    $allowregistration=1;
  if (($i==6) && ($registersupported>-1))
    $allowregistration=1;
  if ($allowbuy==1)
  {
    $canbuy=1;
    if (($checkbuy==1) || (($checkbuy==2) && ($allowregistration==1)))
	    print "<input class=\"cwhoisresults\" name=\"buy$k\" type=\"checkbox\" checked >\n";
	  else
	    print "<input class=\"cwhoisresults\" name=\"buy$k\" type=\"checkbox\" >\n";
  }
  else
    print("&nbsp;");
  print("</td>\n");
  print("</tr>\n");

  if ($NarrowCart)
    print "</tr>\n";

  return($canbuy);
}

if (($cwaction=="check") || ($cwaction=="add") || ($cwaction=="remove") || (($cwaction=="") && ($cwhoismode==0)))
{
	// If there are items in shopping cart then display them
	$sctextcolor="#000000";
	$sctextsize="10pt";
	if (($_SESSION['numberofitems']-$_SESSION['numberremoved'])>0)
	{
	  print("<div class=\"cwhoisshop\"><form method=\"get\">\n");
	  print("<table class=\"cwhoisshop\" >\n");
	  print "<input name=\"cwaction\" type=\"hidden\" value=\"\">\n";
	  print "<input name=\"page\" type=\"hidden\" value=\"\">\n";
	  // Column headings
	  print("<tr>\n");
	  print("<td   class=\"cwhoisshop\" colspan=\"5\">\n");
	  print("<big class=\"cwhoisshop\">".$lang['ShoppingCart']."</big><br><HR class=\"cwhoisshop\">");
	  print("</td>");
	  print("</tr>\n");
	  print("<tr>\n");
	  print("<td   class=\"cwhoisshop\">\n");
	  print("<b class=\"cwhoisshop\">".$lang['Domain']."</b>");
	  print("</td>\n");
	  print("<td   class=\"cwhoisshop\">\n");
	  print("<b class=\"cwhoisshop\">".$lang['DomainOpt']."</b>");
	  print("</td>\n");
	  if ($NarrowCart!=true)
	  {
  	  print("<td   class=\"cwhoisshop\">\n");
	    if ($numhost>0)
		    print("<b class=\"cwhoisshop\">".$lang['HostingOpt']."</b>");
	    else
		    print("&nbsp;");
	    print("</td>\n");
	  }
	  print("<td   class=\"cwhoisshop\">\n");
	  print("<b class=\"cwhoisshop\">".$lang['Subtotal']."</b>");
	  print("</td>\n");
	  print("<td   class=\"cwhoisshop\">\n");
	  print("<b class=\"cwhoisshop\">".$lang['Remove']."</b>");
	  print("</td>\n");
	  print("</tr>\n");
	  $total=0.00;
	  $recurringtotal=0.00;
	  for ($k=1;$k<=$_SESSION['numberofitems'];$k++)
	  {
	    if ($_SESSION['removed'.$k]==False)
	    {
	      $subtotal=0.00;
	      print("<tr>\n");
	      print("<td   class=\"cwhoisshop\"><p class=\"cwhoisshop\">\n");
	      print($_SESSION['domain'.$k]);
	      print("</p></td>");
	      print("<td   class=\"cwhoisshop\"><p class=\"cwhoisshop\">\n");
	      if ($_SESSION['regtype'.$k]=="H")
		      print($lang['HostingOnly']);
	      if ($_SESSION['regtype'.$k]=="R")
	      {
		      print($lang['Register']." ");
		      print($_SESSION['regperiod'.$k]);
		      print(" ".$lang['Year']." $csymbol");
		      print($_SESSION['regprice'.$k]);
		      print $csymbol2;
	      }
	      if ($_SESSION['regtype'.$k]=="T")
	      {
		      print($lang['Transfer']." ");
		      print($_SESSION['regperiod'.$k]);
		      print(" ".$lang['Year']." $csymbol");
		      print($_SESSION['regprice'.$k]);
		      print $csymbol2;
	      }
	      if ($_SESSION['regtype'.$k]=="N")
	      {
		      print($lang['Renew']." ");
		      print($_SESSION['regperiod'.$k]);
		      print(" ".$lang['Year']." $csymbol");
		      print($_SESSION['regprice'.$k]);
		      print $csymbol2;
	      }
	      $subtotal=$subtotal+$_SESSION['regprice'.$k];
	      print("</p></td >\n");
        if ($NarrowCart)
          print "<tr><td   class=\"cwhoisshop\"><p class=\"cwhoisshop\">&nbsp;</p></td>\n";
	      print("<td   class=\"cwhoisshop\"><p class=\"cwhoisshop\">\n");
	      if ($numhost>0)
	      {
		      $hostdesc=$_SESSION['hostdesc'.$k];
		      $hostsetup=$_SESSION['hostsetup'.$k];
		      $hostprice=$_SESSION['hostprice'.$k];
		      $hostrecurr=$_SESSION['hostrecurr'.$k];
		      if ($hostdesc=="")
		      {
			      print ($lang['NoHosting']);
		      }
		      else
		      {
		      // Check to see if recurring billing
		      if ($hostsetup==0)
			      print "$hostdesc $csymbol$hostprice$csymbol2";
		      else
			      print "$hostdesc $csymbol$hostprice$csymbol2 (".$lang['Setup']." $csymbol$hostsetup$csymbol2)";
		      $subtotal=$subtotal+$hostprice+$hostsetup;
		      if ($hostrecurr!="S")
			      $recurringtotal=$recurringtotal+$hostprice;
	      	}
	      }
	      else
	        print("&nbsp;");
	      print("</p></td>\n");
	      print("<td   class=\"cwhoisshop\" align=\"right\"><p class=\"cwhoisshop\">\n");
	      printf("$csymbol%01.".$decimalplaces."f".$csymbol2,$subtotal);
	      print("</p></td>\n");
	      print("<td   class=\"cwhoisshop\" align=\"center\">\n");
	      print "<input class=\"cwhoisshop\" name=\"rem$k\" type=\"checkbox\" value=\"ON\">\n";
	      print("</td>\n");
        if ($NarrowCart)
          print "</tr>\n";
	      print("</tr>");
	      $total=$total+$subtotal;
	    }
	  }
	  if ($recurringtotal>0)
	  {
		  print("<tr>\n");
		  print("<td   class=\"cwhoisshop\" align=\"right\" colspan=\"4\">\n");
		  printf("<B class=\"cwhoisshop\">".$lang['MonthlyCharge']."&nbsp;$csymbol%01.".$decimalplaces."f$csymbol2&nbsp;- ".$lang['ToPayNow']."&nbsp;$csymbol%01.".$decimalplaces."f".$csymbol2,$recurringtotal,$total);
	    print("</B></td>");
	    print("<td   class=\"cwhoisshop\"><p class=\"cwhoisshop\">\n");
	    print("&nbsp;");
	    print("</p></td>\n");
	    print("</TR>\n");
	  }
	  else
	  {
	    print("<tr>\n");
	    print("<td   class=\"cwhoisshop\" align=\"right\" colspan=\"4\">\n");
	    printf("<B class=\"cwhoisshop\">".$lang['Total']."&nbsp;&nbsp;$csymbol%01.".$decimalplaces."f".$csymbol2,$total);
	    print("</B></td>");
	    print("<td   class=\"cwhoisshop\"><p class=\"cwhoisshop\">\n");
	    print("&nbsp;");
	    print("</p></td>\n");
	    print("</TR>\n");
	  }
	  print("<tr>\n");
	  print("<td   class=\"cwhoisshop\" align=\"right\" colspan=\"5\">\n");
	  print("<input class=\"cwhoisshop\" type=\"button\" value=\"".$lang['UpdateButton']."\" onclick=\"RemoveFromCart(this.form);\" >&nbsp;&nbsp;<input class=\"cwhoisshop\" type=\"button\" value=\"".$lang['CheckoutButton']."\" onclick=\"Checkout(this.form);\" >\n");
	  print("</td>");
	  print("</TR>\n");
	  print("</table>\n");
	  print("</form></div>\n");
	}
}

// If checkout form submitted with coupon code then verify it is valid. If not then redisplay checkout
$checkoutcouponerror=0;
if (($cwaction=="order") &&  ($cfcouponcode!=""))
{
  $checkoutcouponerror=cwc_ValidateCoupon($cfcouponcode,$total,$recurringtotal,$tmp1,$tmp2);
  if ($checkoutcouponerror>0)
  {
    $cwaction="checkout";
  }
}

// If checkout form submitted with turing code then verify it is correct. If not then redisplay checkout
$checkoutturingerror=false;
if (($cwaction=="order") && ($CheckoutTuring))
{
  $turingmatch=false;
  if ((strtolower($_SESSION['ses_cwhoisturingcode'])==strtolower(trim($turing))) && ($_SESSION['ses_cwhoisturingcode']!=""))
  {
    $turingmatch=true;
    $_SESSION['ses_cwhoisturingcode']="";
  }
  else if ((strtolower($_SESSION['ses_cwhoispreviousturingcode'])==strtolower(trim($turing))) && ($_SESSION['ses_cwhoispreviousturingcode']!=""))
  {
    $turingmatch=true;
    $_SESSION['ses_cwhoispreviousturingcode']="";
  }
  if (!$turingmatch)
  {
    $cwaction="checkout";
    $checkoutturingerror=true;
  }
}

if (($cwaction=="checkout") || ($cwaction=="addout"))
{
	$cotextcolor="#000000";
	$cotextsize="10pt";
	// If there are items in shopping cart then display them so that client can verify
	$buyregister=0;
	$buytransfer=0;
	$buyhosting=0;
	if (($_SESSION['numberofitems']-$_SESSION['numberremoved'])>0)
	{
	  print("<div class=\"cwhoisverify\"><form method=\"get\" action=$page>\n");
	  print "<hr class=\"cwhoisverify\">";
	  print("<table class=\"cwhoisverify\" >\n");
	  print "<input name=\"cwaction\" type=\"hidden\" value=\"\">\n";
	  print("<tr>\n");
	  print("<td   class=\"cwhoisverify\" colspan=\"5\">\n");
	  print("<big class=\"cwhoisverify\">".$lang['YourOrder']."</big><BR>");
	  print("</td>");
	  print("</tr>\n");
	  // Set tax to 0 as it cannot be calculated until address entered
	  $taxrate1=0;
	  $taxrate2=0;
	  // Clear coupon code as we don't have it at this point.
	  $couponcode="";
    print orderdetailshtml($total,$recurringtotal,"cwhoisverify");
    if (($taxratefield1!="") || ($taxratefield2!=""))
    {
	    print("<tr>\n");
	    print("<td   class=\"cwhoisverify\" colspan=\"5\">\n");
	    print("<strong class=\"cwhoisverify\">".$lang['TaxToAdd']."</strong><BR>");
	    print("</td>");
	    print("</tr>\n");
    }
	  print("<tr>\n");
	  print("<td   class=\"cwhoisverify\" align=\"right\" colspan=\"5\">\n");
	  print("<input class=\"cwhoisverify\" type=\"submit\" value=\"".$lang['ModifyButton']."\" >\n");
	  print("</td>");
	  print("</TR>\n");
	  print("<tr>\n");
	  print("<td   class=\"cwhoisverify\" align=\"right\" colspan=\"5\">\n");
	  print("</td>");
	  print("</TR>\n");
	  print("</table>\n");
	  print("<hr class=\"cwhoisverify\">");

	  print("</form></div>\n");
	}
	if ($paymenttarget!="")
	  print "<div class=\"cwhoiscontact\"><form action=\"".$paymentpage."\" target=\"".$paymenttarget."\" method=\"get\" name=\"contactform\" onSubmit=\"return validate(this)\">\n";
	else
	  print "<div class=\"cwhoiscontact\"><form action=\"".$paymentpage."\" method=\"get\" name=\"contactform\" id=\"contactform\" onSubmit=\"return validate(this)\">\n";
	print "<input name=\"cwaction\" type=\"hidden\" value=\"order\">\n";
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Replacement form fields and validation can go here. Delete all lines in this
	// section and replace with you own form fields. Don't include the <form> or
	// </form> though.
	print "<script language=\"JavaScript\">\n";
	print "<!-- JavaScript\n";
	print "  function validate(form)\n";
	print "  {\n";
	if ($overallagree!="")
	{
	  print "if (!form.cfOverallAgree.checked)\n";
	  print "{\n";
	  print "  alert(\"".$lang['OverallAlert']."\")\n";
	  print "  form.cfOverallAgree.focus()\n";
	  print "  return(false)\n";
	  print "}\n";
	}
	if (($buyregister==1) && ($registeragree!=""))
	{
	  print "if (!form.cfRegisterAgree.checked)\n";
	  print "{\n";
	  print "  alert(\"".$lang['RegAlert']."\")\n";
	  print "  form.cfRegisterAgree.focus()\n";
	  print "  return(false)\n";
	  print "}\n";
	}
	if (($buytransfer==1) && ($transferagree!=""))
	{
	  print "if (!form.cfTransferAgree.checked)\n";
	  print "{\n";
	  print "  alert(\"".$lang['TransferAlert']."\")\n";
	  print "  form.cfTransferAgree.focus()\n";
	  print "  return(false)\n";
	  print "}\n";
	}
	if (($buyrenew==1) && ($renewagree!=""))
	{
	  print "if (!form.cfRenewAgree.checked)\n";
	  print "{\n";
	  print "  alert(\"".$lang['RenewAlert']."\")\n";
	  print "  form.cfRenewAgree.focus()\n";
	  print "  return(false)\n";
	  print "}\n";
	}
	if (($buyhosting==1) && ($hostagree!=""))
	{
	  print "if (!form.cfHostAgree.checked)\n";
	  print "{\n";
	  print "  alert(\"".$lang['HostAlert']."\")\n";
	  print "  form.cfHostAgree.focus()\n";
	  print "  return(false)\n";
	  print "}\n";
	}
  // Create form validation code for required fields
  $passfield=false;
  $verifypassfield=false;
  $ra_paymenttypefield=false;
	for ($k=0;$k<count($cform);$k++)
	{
  	$formv=explode(",",$cform[$k]);
    if (strcasecmp($formv[0],"ra_paymenttype")==0)
      $ra_paymenttypefield=true;
    if (strcasecmp($formv[0],"password")==0)
      $passfield=true;
    if (strcasecmp($formv[0],"verifypassword")==0)
    {
      $verifypassfield=true;
      $verifypasswordmsg=$formv[2];
    }
	}
	if ($ra_paymenttypefield)
	{
    print "var paytype=form.cfra_paymenttype[getSelectedButton(form.cfra_paymenttype)].value\n";
    print "paytype=paytype.toLowerCase();\n";
	}
  else
    print "var paytype=\"\"\n";
	for ($k=0;$k<count($cform);$k++)
	{
  	$formv=explode(",",$cform[$k]);
    switch (strtolower($formv[0]))
    {
      case "email":
        if ($formv[2]!="")
        {
    			print "if (ValidEmail(form.cfemail.value)== false)\n";
    			print "{\n";
    			print "  alert(\"$formv[2]\")\n";
    			print "  form.cfemail.focus()\n";
    			print "  return(false)\n";
    			print "}\n";
        }
        break;
      case "cc_name":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"credit card\") || (paytype==\"\"))\n";
        	print "{\n";
      		print "if (form.cf".$formv[0].".value==\"\")\n";
      		print "{\n";
      		print "  alert(\"$formv[2]\")\n";
      		print "  form.cf$formv[0].focus()\n";
      		print "  return(false)\n";
      		print "}\n";
      		print "}\n";
        }
        break;
      case "cc_number":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"credit card\") || (paytype==\"\"))\n";
        	print "{\n";
    			print "if (validateCreditCard(form.cfcc_number.value)== false)\n";
    			print "{\n";
    			print "  alert(\"$formv[2]\")\n";
    			print "  form.cfcc_number.focus()\n";
    			print "  return(false)\n";
    			print "}\n";
      		print "}\n";
        }
        break;
      case "cc_expiry":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"credit card\") || (paytype==\"\"))\n";
        	print "{\n";
          print "  if (validateDateMMYY(form.cfcc_expiry.value,getTodayMMYY(),\"12/99\")==false)\n";
          print "  {\n";
    			print "    alert(\"$formv[2]\")\n";
          print "    form.cfcc_expiry.focus()\n";
          print "    return (false)\n";
          print "  }  \n";
      		print "}\n";
        }
        break;
      case "cc_securitycode":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"credit card\") || (paytype==\"\"))\n";
        	print "{\n";
          print "  if (validateSecCode(form.cfcc_securitycode.value)==false)\n";
          print "  {\n";
    			print "    alert(\"$formv[2]\")\n";
          print "    form.cfcc_securitycode.focus()\n";
          print "    return (false)\n";
          print "  }  \n";
      		print "}\n";
        }
        break;
      case "cc_start":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"credit card\") || (paytype==\"\"))\n";
        	print "{\n";
          print "  if (form.cfcc_start.value!=\"\")\n";
          print "  {\n";
          print "    if (validateDateMMYY(form.cfcc_start.value,\"01/01\",getTodayMMYY())==false)\n";
          print "    {\n";
    			print "      alert(\"$formv[2]\")\n";
          print "      form.cfcc_start.focus()\n";
          print "      return (false)\n";
          print "    }\n";
          print "  }\n";
      		print "}\n";
        }
        break;
      case "cc_issuenumber":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"credit card\") || (paytype==\"\"))\n";
        	print "{\n";
      		print "if (form.cf".$formv[0].".value==\"\")\n";
      		print "{\n";
      		print "  alert(\"$formv[2]\")\n";
      		print "  form.cf$formv[0].focus()\n";
      		print "  return(false)\n";
      		print "}\n";
      		print "}\n";
        }
        break;
      case "cc_cardtype":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"credit card\") || (paytype==\"\"))\n";
        	print "{\n";
      		print "if (form.cf".$formv[0].".selectedIndex==0)\n";
      		print "{\n";
      		print "  alert(\"$formv[2]\")\n";
      		print "  form.cf$formv[0].focus()\n";
      		print "  return(false)\n";
      		print "}\n";
      		print "}\n";
        }
        break;
      case "ba_accountname":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"bank transfer\") || (paytype==\"\"))\n";
        	print "{\n";
      		print "if (form.cf".$formv[0].".value==\"\")\n";
      		print "{\n";
      		print "  alert(\"$formv[2]\")\n";
      		print "  form.cf$formv[0].focus()\n";
      		print "  return(false)\n";
      		print "}\n";
      		print "}\n";
        }
        break;
      case "ba_bankname":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"bank transfer\") || (paytype==\"\"))\n";
        	print "{\n";
      		print "if (form.cf".$formv[0].".value==\"\")\n";
      		print "{\n";
      		print "  alert(\"$formv[2]\")\n";
      		print "  form.cf$formv[0].focus()\n";
      		print "  return(false)\n";
      		print "}\n";
      		print "}\n";
        }
        break;
      case "ba_accountnumber":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"bank transfer\") || (paytype==\"\"))\n";
        	print "{\n";
      		print "if (form.cf".$formv[0].".value==\"\")\n";
      		print "{\n";
      		print "  alert(\"$formv[2]\")\n";
      		print "  form.cf$formv[0].focus()\n";
      		print "  return(false)\n";
      		print "}\n";
      		print "}\n";
        }
        break;
      case "ba_accounttype":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"bank transfer\") || (paytype==\"\"))\n";
        	print "{\n";
      		print "if (form.cf".$formv[0].".value==\"\")\n";
      		print "{\n";
      		print "  alert(\"$formv[2]\")\n";
      		print "  form.cf$formv[0].focus()\n";
      		print "  return(false)\n";
      		print "}\n";
      		print "}\n";
        }
        break;
      case "ba_branchcode":
        if ($formv[2]!="")
        {
        	print "if ((paytype==\"bank transfer\") || (paytype==\"\"))\n";
        	print "{\n";
      		print "if (form.cf".$formv[0].".value==\"\")\n";
      		print "{\n";
      		print "  alert(\"$formv[2]\")\n";
      		print "  form.cf$formv[0].focus()\n";
      		print "  return(false)\n";
      		print "}\n";
      		print "}\n";
        }
        break;
      default:
        if ($formv[2]!="")
        {
          if (substr($formv[0],0,3)=="cb_")
          {
      			print "if (form.cf".$formv[0].".checked==false)\n";
      			print "{\n";
      			print "  alert(\"$formv[2]\")\n";
      			print "  form.cf$formv[0].focus()\n";
      			print "  return(false)\n";
      			print "}\n";
          }
          if (substr($formv[0],0,3)=="dm_")
          {
      			print "if (form.cf".$formv[0].".selectedIndex==0)\n";
      			print "{\n";
      			print "  alert(\"$formv[2]\")\n";
      			print "  form.cf$formv[0].focus()\n";
      			print "  return(false)\n";
      			print "}\n";
          }
          if ((substr($formv[0],0,3)!="cb_") && (substr($formv[0],0,3)!="dm_") && (substr($formv[0],0,3)!="ra_"))
          {
      			print "if (form.cf".$formv[0].".value==\"\")\n";
      			print "{\n";
      			print "  alert(\"$formv[2]\")\n";
      			print "  form.cf$formv[0].focus()\n";
      			print "  return(false)\n";
      			print "}\n";
          }
        }
    }
	}
	// If password and verify password fields defined then check they match
	if ($passfield && $verifypassfield)
	{
	  print "if (form.cfpassword.value!=form.cfverifypassword.value)\n";
	  print "{\n";
		print "  alert(\"$verifypasswordmsg\")\n";
		print "  form.cfverifypassword.focus()\n";
		print "  return(false)\n";
	  print "}\n";
	}
	// If Checkout Turing required then check something is entered
	if ($CheckoutTuring)
	{
	  print "if (form.turing.value==\"\")\n";
	  print "{\n";
		print "  alert(\"".$lang['TuringCodeRequired']."\")\n";
		print "  form.turing.focus()\n";
		print "  return(false)\n";
	  print "}\n";
	}
	print "return(true)\n";
	print "}\n";
 	print "\n";
	print "function ValidEmail (emailStr)\n";
	print "{\n";
	print "var emailPat=/^(.+)@(.+)$/\n";
	print "var specialChars=\"\\\\(\\\\)<>@,;:\\\\\\\\\\\\\\\"\\\\.\\\\[\\\\]\"\n";
	print "var validChars=\"\\[^\\\\s\" + specialChars + \"\\]\"\n";
	print "var quotedUser=\"(\\\"[^\\\"]*\\\")\"\n";
	print "var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/\n";
	print "var atom=validChars + '+'\n";
	print "var word=\"(\" + atom + \"|\" + quotedUser + \")\"\n";
	print "var userPat=new RegExp(\"^\" + word + \"(\\\\.\" + word + \")*$\")\n";
	print "var domainPat=new RegExp(\"^\" + atom + \"(\\\\.\" + atom +\")*$\")\n";
	print "var matchArray=emailStr.match(emailPat)\n";
	print "if (matchArray==null)\n";
	print " return false\n";
	print "var user=matchArray[1]\n";
	print "var domain=matchArray[2]\n";
	print "if (user.match(userPat)==null)\n";
	print "    return false\n";
	print "var IPArray=domain.match(ipDomainPat)\n";
	print "if (IPArray!=null) {\n";
	print "  for (var i=1;i<=4;i++)\n";
	print "  {\n";
	print "    if (IPArray[i]>255)\n";
	print "      return false\n";
	print "  }\n";
	print "  return true\n";
	print "}\n";
	print "var domainArray=domain.match(domainPat)\n";
	print "if (domainArray==null)\n";
	print "    return false\n";
	print "var atomPat=new RegExp(atom,\"g\")\n";
	print "var domArr=domain.match(atomPat)\n";
	print "var len=domArr.length\n";
	print "if (domArr[domArr.length-1].length<2 ||\n";
	print "    domArr[domArr.length-1].length>4)\n";
	print "   return false\n";
	print "if (len<2)\n";
	print "   return false\n";
	print "return true;\n";
	print "}\n";

  print "function validateCreditCard(s)\n";
  print "{\n";
  print "// remove non-numerics\n";
  print "var v = \"0123456789\";\n";
  print "var CC = \"\";\n";
  print "for (i=0; i < s.length; i++)\n";
  print "{\n";
  print "  x = s.charAt(i);\n";
  print "  if (v.indexOf(x,0) != -1)\n";
  print "  CC += x;\n";
  print "}\n";
  print "  if (CC.length > 19)\n";
  print "       return (false);\n";
  print "  if (CC.length < 13)\n";
  print "       return (false);\n";
  print "  sum = 0; mul = 1; l = CC.length;\n";
  print "  for (i = 0; i < l; i++) \n";
  print "  {\n";
  print "    digit = CC.substring(l-i-1,l-i);\n";
  print "    tproduct = parseInt(digit ,10)*mul;\n";
  print "    if (tproduct >= 10)\n";
  print "      sum += (tproduct % 10) + 1;\n";
  print "    else\n";
  print "      sum += tproduct;\n";
  print "    if (mul == 1)\n";
  print "      mul++;\n";
  print "    else\n";
  print "      mul--;\n";
  print "  }\n";
  print "  if ((sum % 10) == 0)\n";
  print "    return (true);\n";
  print "  else\n";
  print "    return (false);\n";
  print "}\n";

  print "function validateDateMMYY(s,first,last)\n";
  print "{\n";
  print "  var p=-1;\n";
  print "  for (var k=0;k<s.length;k++)\n";
  print "  {\n";
  print "    if ((s.charAt(k)==\"/\") || (s.charAt(k)==\"\\\\\"))\n";
  print "	  p=k\n";
  print "  }\n";
  print "  if (p==-1)\n";
  print "    return(false)\n";
  print "  var m=s.substring(0,p)\n";
  print "  var y=s.substring(p+1,s.length)\n";
  print "  if ((m<1) || (m>12))\n";
  print "    return(false)\n";
  print "  if ((y<1) || (y>99))\n";
  print "    return(false)\n";
  print "  if (m.length<2)\n";
  print "    m=\"0\".concat(m)\n";
  print "  if (y.length<2)\n";
  print "    y=\"0\".concat(y)\n";
  print "  var min=first.substring(3,5)+first.substring(0,2)\n";
  print "  var max=last.substring(3,5)+last.substring(0,2)\n";
  print "  var comps=y.concat(m)\n";
  print "  if (comps<min)\n";
  print "    return(false)\n";
  print "  if (comps>max)\n";
  print "    return(false)\n";
  print "  return(true)\n";
  print "}\n";
  print "\n";
  print "function getTodayMMYY()\n";
  print "{\n";
  print "  var today=new Date()\n";
  print "  var mmyy=\"\";\n";
  print "  var m=today.getMonth()+1\n";
  print "  if (m<10)\n";
  print "    mmyy=\"0\"+m.toString()\n";
  print "  else\n";
  print "    mmyy=+m.toString()\n";
  print "  mmyy=mmyy+\"/\"\n";
  print "  var y=today.getFullYear()\n";
  print "  mmyy=mmyy+y.toString().substring(2,4)\n";
  print "  return (mmyy)	\n";
  print "}\n";
  print "function validateSecCode(s)\n";
  print "{\n";
  print "  if ((s.length!=3) && (s.length!=4))\n";
  print "    return (false)\n";
  print "  for (var k=0;k<s.length;k++)\n";
  print "  {\n";
  print "    if ((s.charAt(k)<\"0\") || (s.charAt(k)>\"9\"))\n";
  print "	  return(false)\n";
  print "  }\n";
  print "  return(true)\n";
  print "}\n";
  print "function getSelectedButton(buttonGroup)\n";
  print "  {\n";
  print "		for (var i=0; i<buttonGroup.length; i++)\n";
  print "		{\n";
  print "			if (buttonGroup[i].checked)\n";
  print "			{\n";
  print "				return (i)\n";
  print "			}\n";
  print "		}\n";
  print "	  return(0)\n";
  print "	}\n";
	print "function paymenttypeclicked()\n";
	print "{\n";
  print "  var paytype=document.contactform.cfra_paymenttype[getSelectedButton(document.contactform.cfra_paymenttype)].value\n";
  print "  paytype=paytype.toLowerCase();\n";
	print "  if (paytype==\"credit card\")\n";
	print "  {\n";
	print "    var creditcarddisabled=false\n";
	print "    var banktransferdisabled=true\n";
	print "  }\n";
	print "  if (paytype==\"bank transfer\")\n";
	print "  {\n";
	print "    var creditcarddisabled=true\n";
	print "    var banktransferdisabled=false\n";
	print "  }\n";
	for ($k=0;$k<count($cform);$k++)
	{
  	$formv=explode(",",$cform[$k]);
    if (strcasecmp($formv[0],"cc_cardtype")==0)
    	print "  document.contactform.cfcc_cardtype.disabled=creditcarddisabled\n";
    if (strcasecmp($formv[0],"cc_name")==0)
    	print "  document.contactform.cfcc_name.disabled=creditcarddisabled\n";
    if (strcasecmp($formv[0],"cc_number")==0)
    	print "  document.contactform.cfcc_number.disabled=creditcarddisabled\n";
    if (strcasecmp($formv[0],"cc_expiry")==0)
    	print "  document.contactform.cfcc_expiry.disabled=creditcarddisabled\n";
    if (strcasecmp($formv[0],"cc_securitycode")==0)
    	print "  document.contactform.cfcc_securitycode.disabled=creditcarddisabled\n";
    if (strcasecmp($formv[0],"cc_start")==0)
    	print "  document.contactform.cfcc_start.disabled=creditcarddisabled\n";
    if (strcasecmp($formv[0],"cc_issuenumber")==0)
    	print "  document.contactform.cfcc_issuenumber.disabled=creditcarddisabled\n";
    if (strcasecmp($formv[0],"ba_accountname")==0)
    	print "  document.contactform.cfba_accountname.disabled=banktransferdisabled\n";
    if (strcasecmp($formv[0],"ba_bankname")==0)
    	print "  document.contactform.cfba_bankname.disabled=banktransferdisabled\n";
    if (strcasecmp($formv[0],"ba_accountnumber")==0)
    	print "  document.contactform.cfba_accountnumber.disabled=banktransferdisabled\n";
    if (strcasecmp($formv[0],"ba_accounttype")==0)
    	print "  document.contactform.cfba_accounttype.disabled=banktransferdisabled\n";
    if (strcasecmp($formv[0],"ba_branchcode")==0)
    	print "  document.contactform.cfba_branchcode.disabled=banktransferdisabled\n";
	}
	print "}\n";

	print "\n";
	print "  // - JavaScript - -->\n";
	print "  </script>\n";
  if ((($buyregister==1) && ($registeragree!="")) || (($buytransfer==1) && ($transferagree!="")) || (($buyrenew==1) && ($renewagree!="")) || (($buyhosting==1) && ($hostagree!="")) || ($overallagree!=""))
	{
	  print "<table class=\"cwhoiscontact\" >\n";
	  print "<tr>\n";
	  print "<td   class=\"cwhoiscontact\"><big class=\"cwhoiscontact\">".$lang['Agreements']."</big></td>\n";
	  print "</tr>\n";
	  if ($overallagree!="")
	  {
	    print "<tr>\n";
	    print "<td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" name=\"cfOverallAgree\" value=\"".$lang['Agreed']."\" type=\"checkbox\">\n";
      // Insert hyperlink in relevant part of text
      $agreetext=explode("!!!",$lang['OverallAgree']);
      print "&nbsp;&nbsp;".$agreetext[0];
      if (count($agreetext)>1)
        print "<a class=\"cwhoiscontact\" href=\"$overallagree\" target=\"_blank\">".$agreetext[1]."</a>";
      if (count($agreetext)>2)
        print $agreetext[2];
	    print "</p></td>\n";
	    print "</tr>\n";
	  }
	  if (($buyregister==1) && ($registeragree!=""))
	  {
	    print "<tr>\n";
	    print "<td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" name=\"cfRegisterAgree\" value=\"".$lang['Agreed']."\" type=\"checkbox\">\n";
      // Insert hyperlink in relevant part of text
      $agreetext=explode("!!!",$lang['RegAgree']);
      print "&nbsp;&nbsp;".$agreetext[0];
      if (count($agreetext)>1)
        print "<a class=\"cwhoiscontact\" href=\"$registeragree\" target=\"_blank\">".$agreetext[1]."</a>";
      if (count($agreetext)>2)
        print $agreetext[2];
	    print "</p></td>\n";
	    print "</tr>\n";
	  }
	  if (($buytransfer==1) && ($transferagree!=""))
	  {
	    print "<tr>\n";
	    print "<td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" name=\"cfTransferAgree\" value=\"".$lang['Agreed']."\" type=\"checkbox\">\n";
      // Insert hyperlink in relevant part of text
      $agreetext=explode("!!!",$lang['TransferAgree']);
      print "&nbsp;&nbsp;".$agreetext[0];
      if (count($agreetext)>1)
        print "<a class=\"cwhoiscontact\" href=\"$transferagree\" target=\"_blank\">".$agreetext[1]."</a>";
      if (count($agreetext)>2)
        print $agreetext[2];
	    print "</p></td>\n";
	    print "</tr>\n";
	  }
	  if (($buyrenew==1) && ($renewagree!=""))
	  {
	    print "<tr>\n";
	    print "<td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" name=\"cfRenewAgree\" value=\"".$lang['Agreed']."\" type=\"checkbox\">\n";
      // Insert hyperlink in relevant part of text
      $agreetext=explode("!!!",$lang['RenewAgree']);
      print "&nbsp;&nbsp;".$agreetext[0];
      if (count($agreetext)>1)
        print "<a class=\"cwhoiscontact\" href=\"$renewagree\" target=\"_blank\">".$agreetext[1]."</a>";
      if (count($agreetext)>2)
        print $agreetext[2];
	    print "</p></td>\n";
	    print "</tr>\n";
	  }
	  if (($buyhosting==1) && ($hostagree!=""))
	  {
	    print "<tr>\n";
	    print "<td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" name=\"cfHostAgree\" value=\"".$lang['Agreed']."\" type=\"checkbox\">\n";
      // Insert hyperlink in relevant part of text
      $agreetext=explode("!!!",$lang['HostAgree']);
      print "&nbsp;&nbsp;".$agreetext[0];
      if (count($agreetext)>1)
        print "<a class=\"cwhoiscontact\" href=\"$hostagree\" target=\"_blank\">".$agreetext[1]."</a>";
      if (count($agreetext)>2)
        print $agreetext[2];
	    print "</p></td>\n";
	    print "</tr>\n";
	  }
	  print"</table>\n";
	  print"<BR>";
	}
	print "  <table class=\"cwhoiscontact\" >\n";
	print "  <tr>\n";
	if (!isset($_SESSION['loggedIn'])) print "  <td   class=\"cwhoiscontact\" colspan=\"2\"><big class=\"cwhoiscontact text-center\">".$lang['ContactDetails']."</big></td>\n";
	//	print "  <td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\">&nbsp;</p></td>\n";
	print "  </tr>\n";

  // Create table row and text input for each form field
	if (!$_SESSION['loggedIn'])
	{
	 for ($k=0;$k<count($cform);$k++) {
	  	$formv=explode(",",$cform[$k]);
	  	$varval="cf".$formv[0];
	    if (($checkoutcouponerror>0) && ($formv[0]=="couponcode"))
	    {
	      print "  <tr>\n";
		    print "  <td   class=\"cwhoiscontact\">&nbsp;</td><td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\" style=\"color:#FF0000\">";
		    if ($checkoutcouponerror==1)
		      print $lang['CouponNotValid'];
		    if ($checkoutcouponerror==2)
		      print $lang['CouponExpired'];
		    print "</p></td>\n";
		    print "  </tr>\n";
	    }
			print "  <tr>\n";
			print "  <td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\">\n";
	    print "  ".$formv[1];
	    if ($formv[2]!="")
	      print "*";
	    print "</p></td>";
		  switch (strtolower($formv[0]))
		  {
				case "total":
					print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" disabled type=\"text\" name=\"cf".$formv[0]."\" value=\"$total\"></td>\n";
					break;
				case "curr":
					print "<td   class=\"cwhoiscontact\"><select class=\"cwhoiscontact\" name=\"cf".$formv[0]."\">\n";
					for ($j=3;$j<count($formv);$j++)
					{
						print "<option value=\"".$formv[$j]."\"";
						if ($$varval==$formv[$j]) print " selected";
						print ">".$formv[$j]."</option>\n";
					}
					print "</select></td>\n";
					break;
		    case "password":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"password\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
			    break;
		    case "verifypassword":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"password\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
			    break;
		    case "cc_number":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"23\" size=\"35\"></td>\n";
			    break;
		    case "cc_expiry":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"5\" size=\"5\"></td>\n";
			    break;
		    case "cc_start":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"5\" size=\"5\"></td>\n";
			    break;
		    case "cc_securitycode":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"4\" size=\"5\"></td>\n";
			    break;
		    case "cc_name":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
			    break;
		    case "cc_issuenumber":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
			    break;
		    case "cc_cardtype":
	        print "<td   class=\"cwhoiscontact\"><select class=\"cwhoiscontact\" name=\"cf".$formv[0]."\">\n";
	        for ($j=3;$j<count($formv);$j++)
	        {
	          print "<option value=\"".$formv[$j]."\"";
	          if ($$varval==$formv[$j]) print " selected";
	          print ">".$formv[$j]."</option>\n";
	        }
	        print "</select></td>\n";
			    break;
		    case "ba_accountname":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
			    break;
		    case "ba_bankname":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
			    break;
		    case "ba_accountnumber":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
			    break;
		    case "ba_accounttype":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
			    break;
		    case "ba_branchcode":
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
			    break;
		    case "ra_paymenttype":
	  		    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"radio\" name=\"cf".$formv[0]."\" value=\"".$formv[3]."\"";
	          if (($checkoutturingerror==true) || ($checkoutcouponerror>0))
	          {
	  		    if ($$varval==$formv[3])
	  		      print " checked=\"checked\" ";
	          }
	          else
	          {
	  		    if (strtolower($formv[4])=="checked")
	  		      print " checked=\"checked\" ";
	          }
	  		    print " onClick=\"paymenttypeclicked();\"></td>\n";
			    break;
		    default:
		      if (substr($formv[0],0,3)=="ta_")
		  	    print "<td   class=\"cwhoiscontact\"><textarea class=\"cwhoiscontact\" name=\"cf".$formv[0]."\" cols=\"40\" rows=\"5\">".$$varval."</textarea></td>\n";
		      if (substr($formv[0],0,3)=="cb_")
		      {
	  		    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"checkbox\" name=\"cf".$formv[0]."\" value=\"".$formv[3]."\"";
	          if (($checkoutturingerror==true) || ($checkoutcouponerror>0))
	          {
	  		      if ($$varval==$formv[3])
	  		        print " checked=\"checked\" ";
	          }
	          else
	          {
	  		      if (strtolower($formv[4])=="checked")
	  		        print " checked=\"checked\" ";
	          }
	  		    print "></td>\n";
					}
		      if (substr($formv[0],0,3)=="ra_")
		      {
	  		    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"radio\" name=\"cf".$formv[0]."\" value=\"".$formv[3]."\"";
	          if (($checkoutturingerror==true) || ($checkoutcouponerror>0))
	          {
	  		    if ($$varval==$formv[3])
	  		      print " checked=\"checked\" ";
	          }
	          else
	          {
	  		      if (strtolower($formv[4])=="checked")
	  		        print " checked=\"checked\" ";
	          }
	  		    print "></td>\n";
					}
		      if (substr($formv[0],0,3)=="dm_")
		      {
	          print "<td   class=\"cwhoiscontact\"><select class=\"cwhoiscontact\" name=\"cf".$formv[0]."\">\n";
	          for ($j=3;$j<count($formv);$j++)
	          {
	            print "<option value=\"".$formv[$j]."\"";
	            if ($$varval==$formv[$j]) print " selected";
	            print ">".$formv[$j]."</option>\n";
	          }
	          print "</select></td>\n";
		      }
		      if ((substr($formv[0],0,3)!="ta_") && (substr($formv[0],0,3)!="cb_") && (substr($formv[0],0,3)!="ra_") && (substr($formv[0],0,3)!="dm_"))
			    print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
			    break;
		  }
			print "  </tr>\n";
		}
	} else {
		for ($k=0;$k<count($cform);$k++) {
			$formv=explode(",",$cform[$k]);
			$varval="cf".$formv[0];
			print "  <tr>\n";
			print "  <td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\">\n";
			print "  ".$formv[1];
			if ($formv[2]!="") print "*";
			print "</p></td>";
			switch (strtolower($formv[0]))
			{
				case "password":
					print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"password\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
					break;
				case "verifypassword":
					print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"password\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
					break;
				case "curr":
					print "<td   class=\"cwhoiscontact\"><select class=\"cwhoiscontact\" name=\"cf".$formv[0]."\">\n";
					for ($j=3;$j<count($formv);$j++)
					{
						print "<option value=\"".$formv[$j]."\"";
						if ($$varval==$formv[$j]) print " selected";
						print ">".$formv[$j]."</option>\n";
					}
					print "</select></td>\n";
					break;
					case "email":
						print "<td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"cf".$formv[0]."\" value=\"".$$varval."\" maxlength=\"50\" size=\"35\"></td>\n";
						break;
				default:
					break;
			}
			print "  </tr>\n";
		}
	}

/*
	// If mollieideal used then get bank code (this list may need updating once in a while
	if (false!==strpos(strtolower($payprocess),"mollieideal"))
	{
    $url="https://secure.mollie.nl/xml/ideal?a=banklist";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
	  print "  <tr>\n";
	  print "  <td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\">\n";
	  print "  ".$lang['IdealBankList'];
	  print " </p></td><td   class=\"cwhoiscontact\">\n";
	  print " <select class=\"cwhoiscontact\" size=\"1\" name=\"cfmollieidealbank\">\n";
	  $pos=0;
	  do
	  {
	    $pos1=strpos($response,"<bank_id>",$pos);
	    $pos2=strpos($response,"</bank_id>",$pos1);
	    if ((false!==$pos1) && (false!==$pos2))
	    {
  	    $bankcode=substr($response,$pos1+9,$pos2-$pos1-9);
  	    $pos=$pos2+10;
  	    $pos1=strpos($response,"<bank_name>",$pos);
  	    $pos2=strpos($response,"</bank_name>",$pos1);
  	    $bankname=substr($response,$pos1+11,$pos2-$pos1-11);
  	    $pos=$pos2+12;
        print " <option value=\"$bankcode\">$bankname</option>\n";
  	  }
  	  else
  	    $pos++;
	  }
	  while ($pos<strlen($response));
	  print " </select>\n";
	  print " </td>\n";
	  print " </tr>\n";
	}
	*/

  // If more than one payment processor selected then offer choice.
  $pprocs=explode(",",$payprocess);
  $pprocsname=explode(",",$payprocessnames);
  if (count($pprocs)>0)
  {
	  print "  <tr>\n";
	  print "  <td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\">\n";
	  print "  ".$lang['PayMethod'];
	  print " </p></td><td   class=\"cwhoiscontact\">\n";
	  print " <select disabled class=\"cwhoiscontact\" size=\"1\" name=\"paymethod\">\n";
    for ($k=0;$k<count($pprocs);$k++)
    {
		  print " <option value=\"".$pprocs[$k]."\">";
		  if (isset($pprocsname[$k]))
		  	print $pprocsname[$k];
		  else
			  print $pprocs[$k];
		  print "</option>\n";
	  }
	  print " </select>\n";
	  print " </td>\n";
	  print " </tr>\n";
  }
	// print "<tr>\n";
	// print "<td class='cwhoiscontact'>\n";
	// print "<p class='cwhoiscontact'> Currency</p>\n";
	// print "</td>\n";
	// print "<td class='cwhoiscontact'>\n";
	// print "<select class='cwhoiscontact' name='ipaycurr'>\n";
	// print "<option value='KES'>KES</option><option value='USD'>USD</option>\n";
	// print "</select>\n";
	// print "</td>\n";
	// print "</tr>\n";
  if ($checkoutcouponerror>0)
  {
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "document.contactform.cfcouponcode.focus()\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
  }
  // If required display turing code
  if ($CheckoutTuring)
  {
    if ($checkoutturingerror==true)
    {
      print "  <tr>\n";
	    print "  <td   class=\"cwhoiscontact\">&nbsp;</td><td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\" style=\"color:#FF0000\">".$lang['TuringCodeRequired']."</p></td>\n";
	    print "  </tr>\n";
    }
    print "  <tr>\n";
	  print "  <td   class=\"cwhoiscontact\"><p class=\"cwhoiscontact\">".$lang['TuringCode']."*</p></td><td   class=\"cwhoiscontact\"><input class=\"cwhoiscontact\" type=\"text\" name=\"turing\" id=\"turing\" size=\"10\">&nbsp;<img class=\"cwhoiscontact\" id=\"turingimage\" name=\"turingimage\" src=\"".$FilePath."turingimagecw.php\" align=\"absmiddle\" width=\"60\" height=\"30\"></td>\n";
	  print "  </tr>\n";
    if ($checkoutturingerror==true)
    {
      print "<script language=\"JavaScript\">\n";
      print "<!-- JavaScript\n";
      print "document.contactform.turing.focus()\n";
      print "// - JavaScript - -->\n";
      print "</script>\n";
    }
  }

	print "<br/>";

  print "  <tr>\n";
	print "  <td   class=\"cwhoiscontact\">&nbsp;</td><td   class=\"cwhoiscontact\" align=\"right\"><input class=\"cwhoiscontact\" type=\"submit\" name=\"button2\" value=\" ".$lang['CheckoutButton']." \" ></td>\n";
	print "  </tr>\n";
	print "  </table>\n";
	if ($ra_paymenttypefield)
	{
  	print "<script language=\"JavaScript\">\n";
	  print "<!-- JavaScript\n";
	  print "paymenttypeclicked()\n";
  	print "// - JavaScript - -->\n";
	  print "</script>\n";
	}

// Replacement form and validation code ends here
//////////////////////////////////////////////////////////////////////////////////
	print "  </form></div>\n";
}
if ($cwaction=="order")
{
	// This is the final step where we call the credit card handling service.
	// Send email to vendor with order details. As this happens before the purchasers
	// credit card is approved we should warn the vendor to verify order goes through.

  // See if tax rate sent in for via ra_formtaxrate, cb_formtaxrate, dm_formtaxrate or formtaxrate
  if (isset($cfra_formtaxrate))
    $taxrate=$cfra_formtaxrate;
  if (isset($cfcb_formtaxrate))
    $taxrate=$cfcb_formtaxrate;
  if (isset($cfdm_formtaxrate))
    $taxrate=$cfdm_formtaxrate;
  if (isset($cfformtaxrate))
    $taxrate=$cfformtaxrate;
	// We should check for any tax1 and tax2 that depend on the address details entered by the user
	$taxrate1=0;
	$taxrate2=0;
  if ((count($tax1)>0) && ($taxratefield1!=""))
  {
  	$formvar="cf".$taxratefield1;
    for ($k=0;$k<count($tax1);$k++)
    {
    	$loc=strtok($tax1[$k],",");
    	$rate=strtok(",");
    	if (strcasecmp($loc,trim($$formvar))==0)
    	{
    		$taxrate1=$rate;
    		break;
    	}
    }
  }
  if ((count($tax2)>0)  && ($taxratefield2!=""))
  {
  	$formvar="cf".$taxratefield2;
    for ($k=0;$k<count($tax2);$k++)
    {
    	$loc=strtok($tax2[$k],",");
    	$rate=strtok(",");
    	if (strcasecmp($loc,trim($$formvar))==0)
    	{
    		$taxrate2=$rate;
    		break;
    	}
    }
  }
	$ordnum=(string) time(); // Unique vendor order number based on time and date.
  if (isset($paymethod))
  {
	  if ($paymethod!="")
	  	$payprocess=$paymethod;
  }
  // Get all form variables
  reset($_GET);
  while(list($namepair, $valuepair) = each($_GET))
  {
    $$namepair = $valuepair;
    if (substr($namepair,0,2)=="cf")
    {
      $formname[]=substr($namepair,2,strlen($namepair)-2);
      $formvalue[]=$valuepair;
    }
  }
  // Create text for order details and get totals.
  // $total=0.00;
	$total = $_SESSION['ipaytotal'];
  $recurringtotal=0.00;
  if ($HTMLEmail=="Y")
  {
		$cotextcolor="#000000";
		$cotextsize="10pt";
    $orderdetails="</p><table class=\"cwhoisemail\" border=\"0\" cellpadding=\"0\" cellspacing=\"10\" bgcolor=\"#FFFFFF\">\n";
    $couponcode=$cfcouponcode;
	  $orderdetails.=orderdetailshtml($total,$recurringtotal,"cwhoisemail");
	  $orderdetails.="</table><p class=\"cwhoisemail\">\n";
	}
	else
	{
    $couponcode=$cfcouponcode;
	  $orderdetails=orderdetailstext($total,$recurringtotal);
  }
  // Email for vendor
  $mailBody="";
  for ($i=0;$i<count($vemail);$i++)
  {
    $lne=$vemail[$i];
    if (is_integer(strpos($lne,"!!!orderdetails!!!")))
    {
      // Insert order details here
      $mailBody.=$orderdetails;
      $mailBody.="\n";
      continue;
    }
    if (is_integer(strpos($lne,"!!!formfields!!!")))
    {
		  if ($HTMLEmail=="Y")
		    $mailBody.="</p><table class=\"cwhoisemail\" bgcolor=\"#FFFFFF\">\n";
      // Insert form fields here
      for ($j=0;$j<count($formname);$j++)
      {
			  if ($HTMLEmail=="Y")
			  {
			  	$mailBody.="<tr><td   class=\"cwhoisemail\"><p class=\"cwhoisemail\">&nbsp;&nbsp;$formname[$j]</p></td>\n";
			  	$mailBody.="<td   class=\"cwhoisemail\"><p class=\"cwhoisemail\">$formvalue[$j]</p></td></tr>\n";
			  }
        else
        	$mailBody.=$formname[$j].": ".$formvalue[$j]."\n";
      }
		  if ($HTMLEmail=="Y")
		  {
	      $mailBody.="<tr><td   class=\"cwhoisemail\"><p class=\"cwhoisemail\">&nbsp;&nbsp;ipaddress</p></td>\n";
	      $mailBody.="<td   class=\"cwhoisemail\"><p class=\"cwhoisemail\">".trim(strtok($_SERVER['REMOTE_ADDR'],","))."</p></td></tr>\n";
			  if ((isset($paymethod)) && ($paymethod!=""))
			  {
		      $mailBody.="<tr><td   class=\"cwhoisemail\"><p class=\"cwhoisemail\">&nbsp;Payment Method</p></td>\n";
		      $mailBody.="<td   class=\"cwhoisemail\"><p class=\"cwhoisemail\">$paymethod</p></td></tr>\n";
				}
	    }
      else
      {
	      $mailBody.="ipaddress: ".trim(strtok($_SERVER['REMOTE_ADDR'],","))."\n";
			  if ((isset($paymethod)) && ($paymethod!=""))
		      $mailBody.="Payment Method: $paymethod\n";
	    }
		  if ($HTMLEmail=="Y")
		    $mailBody.="</table><p class=\"cwhoisemail\">\n";
      continue;
    }
    $lne=str_replace("!!!vendorcompany!!!",$vendorcompany,$lne);
    $lne=str_replace("!!!vendoremail!!!",$vendoremail,$lne);
    $lne=str_replace("!!!ordnum!!!",$ordnum,$lne);
    $lne=str_replace("!!!ipaddress!!!",$_SERVER['REMOTE_ADDR'],$lne);
    $lne=str_replace("!!!useragent!!!",$_SERVER['HTTP_USER_AGENT'],$lne);
    $lne=str_replace("!!!servername!!!",$_SERVER['HTTP_HOST'],$lne);
    $lne=str_replace("!!!scriptname!!!",$_SERVER['SCRIPT_NAME'],$lne);
    $datetime=date("D M j G:i:s T Y");
    $lne=str_replace("!!!datetime!!!",$datetime,$lne);
    for ($j=0;$j<count($formname);$j++)
      $lne=str_replace("!!!".$formname[$j]."!!!",$formvalue[$j],$lne);
    if ($i==0)
      $subject=$lne;
    else
    {
		  if ($HTMLEmail=="Y")
	      $mailBody.="&nbsp;&nbsp;".$lne."<br>\n";
	    else
	      $mailBody.=$lne."\n";
    }
  }
  if ($HTMLEmail=="Y")
  {
    $styles.="<style type=\"text/css\">\n";
    $styles.="p.cwhoisemail {\n";
    $styles.="	font-family: Arial, Helvetica, sans-serif;\n";
    $styles.="	font-size: 10pt;\n";
    $styles.="	color: #000000;\n";
    $styles.="	font-weight: normal;\n";
    $styles.="	position: static;\n";
    $styles.="}\n";
    $styles.="b.cwhoisemail {\n";
    $styles.="	font-family: Arial, Helvetica, sans-serif;\n";
    $styles.="	font-size: 10pt;\n";
    $styles.="	color: #000000;\n";
    $styles.="	font-weight: bold;\n";
    $styles.="	position: static;\n";
    $styles.="}\n";
    $styles.="a.cwhoisemail {\n";
    $styles.="    position: static;\n";
    $styles.="}\n";
    $styles.="table.cwhoisemail {\n";
    $styles.="    position: static;\n";
    $styles.="}\n";
    $styles.="</style>\n";
    $mailBody ="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD W3 HTML//EN\">\n<HTML>\n<HEAD>\n".$styles."<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">\n<TITLE>$subject</TITLE>\n</HEAD>\n<BODY>\n<p class=\"cwhoisemail\">".$mailBody;
    $mailBody.="</p></BODY>\n</HTML>\n";
  }
    // If cc number used then hide it in emails which are not secure
  $ccnumber="";
  for ($j=0;$j<count($formname);$j++)
  {
    if ($formname[$j]=="cc_number")
      $ccnumber=$formvalue[$j];
    if ($formname[$j]=="ba_accountnumber")
      $banumber=$formvalue[$j];
  }
  // If required create a file copy of the order details and store on server
  if ($orderfolder!="")
  {
    if ($HTMLEmail=="Y")
      $fname=$orderfolder.$ordnum.".html";
    else
      $fname=$orderfolder.$ordnum.".txt";
    $fh=fopen($fname,"w");
    if ($fh!=false)
    {
      fputs($fh,$mailBody);
      fclose($fh);
    }
  }
  if ($ccnumber!="")
    $mailBody=str_replace($ccnumber,"************".substr($ccnumber,strlen($ccnumber)-4),$mailBody);
  if (($banumber!="") && (strlen($banumber>6)))
    $mailBody=str_replace($banumber,"************".substr($banumber,strlen($banumber)-4),$mailBody);

  // Add referrer details from cookie if available
  $reflinklokcookie=$_COOKIE['REFLINKLOK'];
  $sesreflinklokcookie=$_COOKIE['SESREFLINKLOK'];
  if ((isset($reflinklokcookie)) && ($reflinklokcookie!=""))
  {
    $cookiefound=true;
    $reflinklokvals=explode("|",$reflinklokcookie);
    if ($HTMLEmail=="Y")
    {
      $mailBody.="Date of first visit: ".$reflinklokvals[0]." ".$reflinklokvals[2]."<br>\n";
      $mailBody.="Time of first visit: ".$reflinklokvals[1]." ".$reflinklokvals[2]."<br>\n";
      $mailBody.="Entry page first visit: ".$reflinklokvals[3]."<br>\n";
      $mailBody.="Referer first visit: ".$reflinklokvals[4]."<br>\n";
    }
    else
    {
      $mailBody.="Date of first visit: ".$reflinklokvals[0]." ".$reflinklokvals[2]."\n";
      $mailBody.="Time of first visit: ".$reflinklokvals[1]." ".$reflinklokvals[2]."\n";
      $mailBody.="Entry page first visit: ".$reflinklokvals[3]."\n";
      $mailBody.="Referer first visit: ".$reflinklokvals[4]."\n";
    }
  }
  if ((isset($sesreflinklokcookie)) && ($sesreflinklokcookie!=""))
  {
    $cookiefound=true;
    $sesreflinklokvals=explode("|",$sesreflinklokcookie);
    if ($HTMLEmail=="Y")
    {
      $mailBody.="Date of session start: ".$sesreflinklokvals[0]." ".$sesreflinklokvals[2]."<br>\n";
      $mailBody.="Time of session start: ".$sesreflinklokvals[1]." ".$sesreflinklokvals[2]."<br>\n";
      $mailBody.="Entry page this session: ".$sesreflinklokvals[3]."<br>\n";
      $mailBody.="Referer this session: ".$sesreflinklokvals[4]."<br>\n\n";
    }
    else
    {
      $mailBody.="Date of session start: ".$sesreflinklokvals[0]." ".$sesreflinklokvals[2]."\n";
      $mailBody.="Time of session start: ".$sesreflinklokvals[1]." ".$sesreflinklokvals[2]."\n";
      $mailBody.="Entry page this session: ".$sesreflinklokvals[3]."\n";
      $mailBody.="Referer this session: ".$sesreflinklokvals[4]."\n\n";
    }
  }

  cwc_SendEmailOut($vendoremail, $vendoremail, $vendorcompany, $subject, $mailBody, $HTMLEmail);
  if ($vendoremail2!="")
    cwc_SendEmailOut($vendoremail2, $vendoremail, $vendorcompany, $subject, $mailBody, $HTMLEmail);
	// Send email to buyer confirming order.
  $subject=$cemail[0];
  $mailBody="";
  for ($i=0;$i<count($cemail);$i++)
  {
    $lne=$cemail[$i];
    if (is_integer(strpos($lne,"!!!orderdetails!!!")))
    {
      // Insert order details here
      $mailBody.=$orderdetails."\n";
      continue;
    }
    if (is_integer(strpos($lne,"!!!formfields!!!")))
    {
		  if ($HTMLEmail=="Y")
		    $mailBody.="</p><table bgcolor=\"#FFFFFF\">\n";
      // Insert form fields here
      for ($j=0;$j<count($formname);$j++)
      {
			  if ($HTMLEmail=="Y")
			  {
			  	$mailBody.="<tr><td   class=\"cwhoisemail\"><p class=\"cwhoisemail\">&nbsp;&nbsp;$formname[$j]</p></td>\n";
			  	$mailBody.="<td   class=\"cwhoisemail\"><p class=\"cwhoisemail\">$formvalue[$j]</p></td></tr>\n";
			  }
        else
        	$mailBody.=$formname[$j].": ".$formvalue[$j]."\n";
      }
		  if ($HTMLEmail=="Y")
		    $mailBody.="</table><p class=\"cwhoisemail\">\n";
      continue;
    }
    $lne=str_replace("!!!vendorcompany!!!",$vendorcompany,$lne);
    $lne=str_replace("!!!vendoremail!!!",$vendoremail,$lne);
    $lne=str_replace("!!!ordnum!!!",$ordnum,$lne);
		$lne=str_replace("!!!ipaddress!!!",$_SERVER['REMOTE_ADDR'],$lne);
    $lne=str_replace("!!!useragent!!!",$_SERVER['HTTP_USER_AGENT'],$lne);
    $lne=str_replace("!!!servername!!!",$_SERVER['HTTP_HOST'],$lne);
    $lne=str_replace("!!!scriptname!!!",$_SERVER['SCRIPT_NAME'],$lne);
    $datetime=date("D M j G:i:s T Y");
    $lne=str_replace("!!!datetime!!!",$datetime,$lne);
    for ($j=0;$j<count($formname);$j++)
      $lne=str_replace("!!!".$formname[$j]."!!!",$formvalue[$j],$lne);
    if ($i==0)
      $subject=$lne;
    else
    {
		  if ($HTMLEmail=="Y")
	      $mailBody.="&nbsp;&nbsp;".$lne."<br>\n";
	    else
	      $mailBody.=$lne."\n";
    }
  }
  if ($HTMLEmail=="Y")
  {
	  $mailBody="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD W3 HTML//EN\">\n<HTML>\n<HEAD>\n".$styles."<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">\n<TITLE>$subject</TITLE>\n</HEAD>\n<BODY>\n<p class=\"cwhoisemail\">".$mailBody;
	  $mailBody.="</p></BODY>\n</HTML>\n";
  }
  if ($ccnumber!="")
    $mailBody=str_replace($ccnumber,"************".substr($ccnumber,strlen($ccnumber)-4),$mailBody);
  if (($banumber!="") && (strlen($banumber>6)))
    $mailBody=str_replace($banumber,"************".substr($banumber,strlen($banumber)-4),$mailBody);
  cwc_SendEmailOut($cfemail, $vendoremail, $vendorcompany, $subject, $mailBody, $HTMLEmail);

	// Send SMS via Clickatel if required.
	if (($clickatell_api_id!="") && ($clickatell_user!="") && ($clickatell_password!="") && ($clickatell_to!=""))
	{
	  $fh=fopen("http://api.clickatell.com/http/sendmsg?api_id=".$clickatell_api_id."&user=".$clickatell_user."&password=".$clickatell_password."&to=".$clickatell_to."&from=".$clickatell_to."&text=".urlencode("cWhois order ".$ordnum." from ".$cffname." ".$cflname." (".$cfemail.")"),"rb");
	}
	// Add cart variables to session data in case an externalapp needs them
  $_SESSION['carttax']=$carttax;
  $_SESSION['carttaxglobal']=$carttaxglobal;
  $_SESSION['cartrecurringtaxglobal']=$cartrecurringtaxglobal;
  $_SESSION['carttax1']=$carttax1;
  $_SESSION['carttax2']=$carttax2;
  $_SESSION['cartrecurringtax']=$cartrecurringtax;
  $_SESSION['cartrecurringtax1']=$cartrecurringtax1;
  $_SESSION['cartrecurringtax2']=$cartrecurringtax2;
  $_SESSION['carttaxrate']=$taxrate;
  $_SESSION['carttaxrate1']=$carttaxrate1;
  $_SESSION['carttaxrate2']=$carttaxrate2;
  $_SESSION['cartextrafee']=$cartextrafee;
  $_SESSION['cartsubtotal']=$cartsubtotal;
  $_SESSION['cartrecurringsubtotal']=$cartrecurringsubtotal;
  $_SESSION['carttotal']=$carttotal;
	$_SESSION['cartrecurringtotal']=$cartrecurringtotal;
  $_SESSION['cartdomain']=$cartdomain;
  $_SESSION['cartdomainopt']=$cartdomainopt;
  $_SESSION['cartdomaintime']=$cartdomaintime;
  $_SESSION['cartdomainprice']=$cartdomainprice;
  $_SESSION['carthosting']=$carthosting;
  $_SESSION['carthostingrecurr']=$carthostingrecurr;
  $_SESSION['carthostingprice']=$carthostingprice;
  $_SESSION['carthostingsetup']=$carthostingsetup;
  $_SESSION['cartitemtotal']=$cartitemtotal;
  $_SESSION['cartitemrecurringtotal']=$cartitemrecurringtotal;
  $_SESSION['cartpayprocess']=$payprocess;
	for ($k=0;$k<count($cform);$k++)
 	{
   	$formv=explode(",",$cform[$k]);
 	  $_SESSION[$formv[0]]=$_REQUEST["cf".$formv[0]];
 	}
  if ($UseMySQL)
  {
	//StoreOrderMysql();
  	global $ordnum,$cffname, $cflname, $cforg ,$cfstr1,$cfcity,$cfstate,
    	$cfcountry,$cftel,$cfcurr,$cfemail,$orderdate,$cartdomain, $cfpassword,
    	$cartdomainopt,$cartdomaintime,$carthosting,$cartsubtotal,$carttotal;
	//
  //   $orderdate=date("Y-m-d h:i:s");
	// //$expirydate = date("Y-m-d h:i:s", strtotime('+1 years'));
	//
  //   $servername = "mysql.s801.sureserver.com";
	// $username = "beta";
	// $password = "K@ribu098!";
  //   $dbname = 'slashdotpro_beta';
	//
  //   $conn = mysqli_connect($servername, $username, $password, $dbname);

	// Check connection
	if (!$conn) {
    	die("Connection failed: " . mysqli_connect_error());
	}

    $Query = "INSERT INTO domaincart_user (fname, lname, email, password, phone, address, city, country, organisation) VALUES ('".$cffname."','".$cflname."','".$cfemail."','".$cfpassword."','".$cftel."','".$cfstr1."','".$cfcity."','".$cfcountry."','".$cforg == "" ? NULL : $cforg."')";

    if ($conn->query($Query) === TRUE) {
    	echo "New record created successfully";
	} else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
	}
  }

	// Now get payment processed. Use chosen system
  // $_SESSION['sessionended']="1";
  //if ($DestroySession==true)
    //session_destroy();


	// iPay Payment Processing
	if (strcasecmp($payprocess,"iPay")==0) {
			if (!isset($cfemail)) $cfemail = $_SESSION['email'];
			if (!isset($cfcurr)) $cfcurr = $_SESSION['ses_csymbol'];
			if (!isset($cftel)) $cftel = $_SESSION['phone'];
      $datastring = $live . $oid . $inv . $total . $cftel . $cfemail . $vid . $cfcurr . $cbk . $cst . $crl;
      $hash_string = hash_hmac('sha1', $datastring, $hsh);
      $hash = $hash_string;
      print "<script language=\"JavaScript\" type=\"text/javascript\">\n";
      print "<!-- JavaScript\n";
      print "window.top.location.replace(\"https://payments.ipayafrica.com/v3/ke?live=$live&oid=$oid&inv=$inv&ttl=$total&tel=$cftel&eml=$cfemail&vid=$vid&curr=$cfcurr&autopay=$autopay&cbk=$cbk&cst=$cst&crl=$crl&hsh=$hash\")\n";
      print "// - JavaScript - -->\n";
      print "</script>\n";

	}

	// OTHER PAYMENT GATEWAYS...SKIP TO LINE 4517

	// 2checkout.com payment processing
	if (strcasecmp($payprocess,"2CO")==0)
	{
		$recurringhandled=0;
		if ($recurringtotal>0)
    {
			$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
      // See if an existing 2CO product exists for combination of initial and monthly charge
      for ($k=0; $k<count($recprodid2co);$k++)
      {
       	$pid=strtok($recprodid2co[$k],",");
       	$initcost=strtok(",");
       	$monthlycost=strtok(",");
        $firstcharge=sprintf("%01.".$decimalplaces."f",$initcost+$monthlycost);
        if (($recurringtotal==$monthlycost) && ($total==$firstcharge))
        {
          $recurringhandled=1;
          break;
        }
      }
      if ($recurringhandled==1)
      {
        // There is a recurring product setup on 2CO to handle this order combination
        print "<form action=\"https://www2.2checkout.com/2co/buyer/purchase\" method=\"POST\" name=\"order2co\">\n";
        print "<input type=\"hidden\" name=\"sid\" value=\"$vendorid2co\">\n";
        print "<input type=\"hidden\" name=\"product_id\" value=\"$pid\">\n";
        print "<input type=\"hidden\" name=\"merchant_order_id\" value=\"$ordnum\">\n";
	      if ($demo2co=="Y")
          print "<input type=\"hidden\" name=\"demo\" value=\"Y\">\n";
        print "<input type=\"hidden\" name=\"card_holder_name\" value=\"$cffname\">\n";
        print "<input type=\"hidden\" name=\"street_address\" value=\"$cfstr1\">\n";
        print "<input type=\"hidden\" name=\"street_address2\" value=\"$cfstr2\">\n";
        print "<input type=\"hidden\" name=\"city\" value=\"$cfcity\">\n";
        print "<input type=\"hidden\" name=\"state\" value=\"$cfstate\">\n";
        print "<input type=\"hidden\" name=\"zip\" value=\"$cfzip\">\n";
        print "<input type=\"hidden\" name=\"country\" value=\"$cfcountry\">\n";
        print "<input type=\"hidden\" name=\"email\" value=\"$cfemail\">\n";
        print "<input type=\"hidden\" name=\"phone\" value=\"$cftel\">\n";
        print "</form>\n";
        print "<script language=\"JavaScript\">\n";
        print "document.order2co.submit()\n";
        print "</script>\n";
      }
    }
		if (($recurringtotal==0) || ($recurringhandled==0))
    {
    	// For new 2CO rules we need to specify each product ordered
			//*************************************************************************
      $cco_domain=explode(",",$cartdomain);
      $cco_domainopt=explode(",",$cartdomainopt);
      $cco_domaintime=explode(",",$cartdomaintime);
      $cco_domainprice=explode(",",$cartdomainprice);
			$cco_hosting=explode(",",$carthosting);
			$cco_hostingprice=explode(",",$carthostingprice);
			$cco_hostingrecurr=explode(",",$carthostingrecurr);
      $prodcount=1;
      for ($k=0;$k<count($cco_domain);$k++)
      {
      	if ($cco_domainprice[$k]>0.00)
      	{
      		// Domain product purchased
      		$cprod[$prodcount]="";
      		$cname[$prodcount]="";
          $cdescription[$prodcount]="";
          $cprice[$prodcount]=$cco_domainprice[$k];
          $cprice[$prodcount]=sprintf("%01.".$decimalplaces."f",$cprice[$prodcount]);
      		if ($cco_domainopt[$k]=="R")
      		{
      		  $cprod[$prodcount]="Reg_";
      		  $cname[$prodcount]="Register .";
      		}
      		if ($cco_domainopt[$k]=="T")
      		{
      		  $cprod[$prodcount]="Tran_";
      		  $cname[$prodcount]="Transfer .";
      		}
      		if ($cco_domainopt[$k]=="N")
      		{
      		  $cprod[$prodcount]="Ren_";
      		  $cname[$prodcount]="Renew .";
      		}
	        if (substr($cco_domain[$k],strlen($cco_domain[$k])-5,5)==".name")
	        {
	          $cprod[$prodcount].="name_";
	          $cname[$prodcount].="name ";
	        }
	        else
	        {
	          // Get domain extension by taking everything from first '.'
	          $pos=strpos($cco_domain[$k],".");
	          if (is_integer($pos))
	          {
	            $cprod[$prodcount].=substr($cco_domain[$k],$pos+1,strlen($cco_domain[$k])-$pos-1)."_";
	            $cname[$prodcount].=substr($cco_domain[$k],$pos+1,strlen($cco_domain[$k])-$pos-1)." ";
	          }
      		}
          $cprod[$prodcount].=$cco_domaintime[$k];
          $cname[$prodcount].="for ".$cco_domaintime[$k]." year(s)";
          $cdescription[$prodcount]=$cname[$prodcount];
          $prodcount++;
      	}
      	if ($cco_hostingprice[$k]>0.00)
      	{
          // Hosting product purchased
      		$cprod[$prodcount]="";
      		$cname[$prodcount]="";
          $cdescription[$prodcount]="";
          $cprice[$prodcount]=$cco_hostingprice[$k];
          $cprice[$prodcount]=sprintf("%01.".$decimalplaces."f",$cprice[$prodcount]);
      		$cprod[$prodcount]="Host_".$cco_hosting[$k];
      		$cprod[$prodcount]=str_replace(" ","_",$cprod[$prodcount]);
      		$cname[$prodcount]="Hosting ".$cco_hosting[$k];
          $cdescription[$prodcount]=$cname[$prodcount];
          $prodcount++;
      	}
      }
      print "<form action=\"https://www2.2checkout.com/2co/buyer/purchase\" method=\"POST\" name=\"order2co\">\n";
      print "<input type=\"hidden\" name=\"sid\" value=\"$vendorid2co\">\n";
      print "<input type=\"hidden\" name=\"total\" value=\"$total\">\n";
      print "<input type=\"hidden\" name=\"cart_order_id\" value=\"$ordnum\">\n";
      print "<input type=\"hidden\" name=\"id_type\" value=\"1\">\n";
      if ($demo2co=="Y")
        print "<input type=\"hidden\" name=\"demo\" value=\"Y\">\n";
      for ($k=1;$k<$prodcount;$k++)
      {
	      print "<input type=\"hidden\" name=\"c_prod_$k\" value=\"".$cprod[$k]."\">\n";
	      print "<input type=\"hidden\" name=\"c_name_$k\" value=\"".$cname[$k]."\">\n";
	      print "<input type=\"hidden\" name=\"c_description_$k\" value=\"".$cdescription[$k]."\">\n";
	      print "<input type=\"hidden\" name=\"c_price_$k\" value=\"".$cprice[$k]."\">\n";
	      print "<input type=\"hidden\" name=\"c_tangible_$k\" value=\"N\">\n";
      }
      // Prepopulate contact details
      print "<input type=\"hidden\" name=\"card_holder_name\" value=\"$cffname\">\n";
      print "<input type=\"hidden\" name=\"street_address\" value=\"$cfstr1\">\n";
      print "<input type=\"hidden\" name=\"street_address2\" value=\"$cfstr2\">\n";
      print "<input type=\"hidden\" name=\"city\" value=\"$cfcity\">\n";
      print "<input type=\"hidden\" name=\"state\" value=\"$cfstate\">\n";
      print "<input type=\"hidden\" name=\"zip\" value=\"$cfzip\">\n";
      print "<input type=\"hidden\" name=\"country\" value=\"$cfcountry\">\n";
      print "<input type=\"hidden\" name=\"email\" value=\"$cfemail\">\n";
      print "<input type=\"hidden\" name=\"phone\" value=\"$cftel\">\n";

      print "</form>\n";
      print "<script language=\"JavaScript\">\n";
      print "document.order2co.submit()\n";
      print "</script>\n";
//*************************************************************************
    }
	}
// 	// PayPal payment processing
// 	if (strcasecmp($payprocess,"PayPal")==0)
// 	{
// 		// See if we need to do one off or recurring billing
// 		if ($recurringtotal>0)
// 		{
// 			$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
//       print "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" name=\"paypalbuy\">\n";
//       print "<input type=\"hidden\" name=\"cmd\" value=\"_xclick-subscriptions\">\n";
//       print "<input type=\"hidden\" name=\"business\" value=\"$paypalemail\">\n";
//       print "<input type=\"hidden\" name=\"item_name\" value=\"$paypaldesc\">\n";
//       print "<input type=\"hidden\" name=\"item_number\" value=\"$ordnum\">\n";
//       print "<input type=\"hidden\" name=\"no_note\" value=\"1\">\n";
//       print "<input type=\"hidden\" name=\"currency_code\" value=\"$paypalcurrency\">\n";
//       print "<input type=\"hidden\" name=\"return\" value=\"$paypalreturn\">\n";
//       print "<input type=\"hidden\" name=\"cancel_return\" value=\"$paypalcancel\">\n";
//       print "<input type=\"hidden\" name=\"invoice\" value=\"$ordnum\">\n";
//       print "<input type=\"hidden\" name=\"a1\" value=\"$total\">\n";
//       print "<input type=\"hidden\" name=\"p1\" value=\"1\">\n";
//       print "<input type=\"hidden\" name=\"t1\" value=\"M\">\n";
//       print "<input type=\"hidden\" name=\"a3\" value=\"$recurringtotal\">\n";
//       print "<input type=\"hidden\" name=\"p3\" value=\"1\">\n";
//       print "<input type=\"hidden\" name=\"t3\" value=\"M\">\n";
//       print "<input type=\"hidden\" name=\"src\" value=\"1\">\n";
//       print "<input type=\"hidden\" name=\"sra\" value=\"1\">\n";
//       print "<input type=\"hidden\" name=\"custom\" value=\"".session_id()."\">\n";
//
// //      print "<input type=\"hidden\" name=\"no_shipping\" value=\"0\">\n";
// //      print "<input type=\"hidden\" name=\"email\" value=\"$cfemail\">\n";
// //      print "<input type=\"hidden\" name=\"address1\" value=\"$cfstr1\">\n";
// //      print "<input type=\"hidden\" name=\"address2\" value=\"$cfstr2\">\n";
// //      print "<input type=\"hidden\" name=\"city\" value=\"$cfcity\">\n";
// //      print "<input type=\"hidden\" name=\"state\" value=\"$cfstate\">\n";
// //      print "<input type=\"hidden\" name=\"zip\" value=\"$cfzip\">\n";
// //      print "<input type=\"hidden\" name=\"country\" value=\"$cfcountry\">\n";
//
//       print "</form>\n";
// 		  print "<script language=\"JavaScript\">\n";
// 		  print "<!-- JavaScript\n";
// 		  print "document.paypalbuy.submit()\n";
// 		  print "// - JavaScript - -->\n";
// 		  print "</script>\n";
// 		}
// 		else
// 		{
//       print "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" name=\"paypalbuy\">\n";
//       print "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">\n";
//       print "<input type=\"hidden\" name=\"business\" value=\"$paypalemail\">\n";
//       print "<input type=\"hidden\" name=\"item_name\" value=\"$paypaldesc\">\n";
//       print "<input type=\"hidden\" name=\"item_number\" value=\"$ordnum\">\n";
//       print "<input type=\"hidden\" name=\"amount\" value=\"$total\">\n";
//       print "<input type=\"hidden\" name=\"no_note\" value=\"1\">\n";
//       print "<input type=\"hidden\" name=\"currency_code\" value=\"$paypalcurrency\">\n";
//       print "<input type=\"hidden\" name=\"return\" value=\"$paypalreturn\">\n";
//       print "<input type=\"hidden\" name=\"cancel_return\" value=\"$paypalcancel\">\n";
//       print "<input type=\"hidden\" name=\"invoice\" value=\"$ordnum\">\n";
//       print "<input type=\"hidden\" name=\"custom\" value=\"".session_id()."\">\n";
//
// //      print "<input type=\"hidden\" name=\"no_shipping\" value=\"0\">\n";
// //      print "<input type=\"hidden\" name=\"email\" value=\"$cfemail\">\n";
// //      print "<input type=\"hidden\" name=\"address1\" value=\"$cfstr1\">\n";
// //      print "<input type=\"hidden\" name=\"address2\" value=\"$cfstr2\">\n";
// //      print "<input type=\"hidden\" name=\"city\" value=\"$cfcity\">\n";
// //      print "<input type=\"hidden\" name=\"state\" value=\"$cfstate\">\n";
// //      print "<input type=\"hidden\" name=\"zip\" value=\"$cfzip\">\n";
// //      print "<input type=\"hidden\" name=\"country\" value=\"$cfcountry\">\n";
//
//       print "</form>\n";
// 		  print "<script language=\"JavaScript\">\n";
// 		  print "<!-- JavaScript\n";
// 		  print "document.paypalbuy.submit()\n";
// 		  print "// - JavaScript - -->\n";
// 		  print "</script>\n";
// 		}
//     // End of mod
// 	}

	// Revecom / Paysystems processing
	if (strcasecmp($payprocess,"Paysystems")==0)
	{
    $paysysdesc=urlencode($paysysdesc);
	  // See if we need to do one off or recurring billing
	  if ($recurringtotal>0)
	  {
	    $recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "window.location.replace(\"https://secure.paysystems1.com/cgi-v310/payment/onlinesale-tpppro.asp?companyid=$paysysid&product1=$paysysdesc&total=$total&redirect=$paysysreturn&redirectfail=$paysyscancel&option1=$ordnum&reoccur=Y&cycle=$paysyscycle&totalperiod=$paysystotalperiod&repeatamount=$recurringtotal\")\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
	  }
	  else
	  {
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "window.location.replace(\"https://secure.paysystems1.com/cgi-v310/payment/onlinesale-tpppro.asp?companyid=$paysysid&product1=$paysysdesc&total=$total&redirect=$paysysreturn&redirectfail=$paysyscancel&option1=$ordnum\")\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
		}
	}
	// Worldpay Futurepay payment processing
	if (strcasecmp($payprocess,"WpFuturepay")==0)
	{
		// See if we need to do one off or recurring billing
		if ($recurringtotal>0)
		{
			$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
			$wpfppaldesc=urlencode($wpfpdesc);
			print "<script language=\"JavaScript\">\n";
			print "<!-- JavaScript\n";
			print "window.location.replace(\"https://select.worldpay.com/wcc/purchase?instId=$wpfpinstid&cartId=$wpfpcartid&amount=$total&normalAmount=$recurringtotal&currency=$wpfpcurrency&desc=$wpfpdesc&testMode=$wpfptest&futurePayType=regular&startDelayUnit=3&startDelayMult=1&intervalUnit=3&intervalMult=1&option=$wpfpoption&hideCurrency\")\n";
			print "// - JavaScript - -->\n";
			print "</script>\n";
		}
		else
		{
			$wpfpdesc=urlencode($paypaldesc);
			print "<script language=\"JavaScript\">\n";
			print "<!-- JavaScript\n";
			print "window.location.replace(\"https://select.worldpay.com/wcc/purchase?instId=$wpfpinstid&cartId=$wpfpcartid&amount=$total&currency=$wpfpcurrency&desc=$wpfpdesc&authMode=E&hideCurrency\")\n";
			print "// - JavaScript - -->\n";
			print "</script>\n";
		}
	}
	// Worldpay standard payment processing
	if (strcasecmp($payprocess,"Worldpay")==0)
	{
    print "<form action=\"https://secure.wp3.rbsworldpay.com/wcc/purchase\" method=\"POST\"  name=\"worldpaybuy\">\n";
    print "<input type=\"hidden\" name=\"instId\" value=\"$wpinstid\" >\n";
    print "<input type=\"hidden\" name=\"cartId\" value=\"$wpcartid\" >\n";
    print "<input type=\"hidden\" name=\"amount\" value=\"$total\" >\n";
    print "<input type=\"hidden\" name=\"currency\" value=\"$wpcurrency\" >\n";
    print "<input type=\"hidden\" name=\"desc\" value=\"$wpdescid\" >\n";
    print "<input type=\"hidden\" name=\"testMode\" value=\"$wptestmode\" >\n";
	  print "</form>\n";
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		print "document.worldpaybuy.submit()\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}
  // Stormpay payment processing
	if (strcasecmp($payprocess,"Stormpay")==0)
	{
		$paypaldesc=urlencode($paypaldesc);
		$paypalemail=urlencode($paypalemail);
	  // See if we need to do one off or recurring billing
	  if ($recurringtotal>0)
		{
			$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
      $stormtotal=sprintf("%01.".$decimalplaces."f",$total-$recurringtotal);
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
			print "window.location.replace(\"https://www.stormpay.com/stormpay/handle_gen.php?generic=1&payee_email=$stormemail&product_name=$stormdesc&subscription=YES&setup_fee=$stormtotal&recurrent_charge=$recurringtotal&duration=$stormcycle&return_URL=$stormreturn&cancel_URL=$stormcancel&transaction_ref=$ordnum\")\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
    else
    {
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
			print "window.location.replace(\"https://www.stormpay.com/stormpay/handle_gen.php?generic=1&payee_email=$stormemail&product_name=$stormdesc&amount=$total&return_URL=$stormreturn&cancel_URL=$stormcancel&transaction_ref=$ordnum\")\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
  }
  // Nochex payment processing
	if (strcasecmp($payprocess,"Nochex")==0)
	{
		$nochexdesc=urlencode($nochexdesc);
		$nochexemail=urlencode($nochexemail);
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "window.location.replace(\"https://www.nochex.com/nochex.dll/checkout?email=$nochexemail&amount=$total&ordernumber=$ordnum&description=$nochexdesc&returnurl=$nochexreturn\")\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
  }
	// authorize.net payment processing
	if (strcasecmp($payprocess,"Authorize")==0)
	{
    $authdesc=urlencode($authdesc);
    // Calculate authentication code
    $tstamp = time ();
		srand(time());
		$sequence = rand(1, 1000);
		$fingerprint = hmac($authtxnkey, $authloginid . "^" . $sequence . "^" . $tstamp . "^" . $total . "^" . $authcurrency);
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		print "window.location.replace(\"https://secure.authorize.net/gateway/transact.dll?x_Login=$authloginid&x_Description=$authdesc&x_Amount=$total&x_show_form=PAYMENT_FORM&x_FP_Sequence=$sequence&x_FP_Timestamp=$tstamp&x_FP_Hash=$fingerprint&x_Invoice_Num=$ordnum&x_Currency_Code=$authcurrency\")\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}
	// Network 1 payment processing
	if (strcasecmp($payprocess,"Network1")==0)
	{
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		print "window.location.replace(\"https://va.eftsecure.net/eftcart/forms/express.asp?M_id=$net1loginid&C_memo=$net1desc&T_amt=$total&T_ordernum=$ordnum\")\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}
	// Centipaid payment processing
	if (strcasecmp($payprocess,"Centipaid")==0)
	{
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		print "window.location.replace(\"http://pay.centipaid.com/cart.php?x_Login=$centilogin&x_Amount=$total&x_Description=$centidesc&x_Invoice_Num=$ordnum\")\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}
	// Moneybookers payment processing
	if (strcasecmp($payprocess,"Moneybookers")==0)
	{
	  if ($recurringtotal>0)
		{
			$startdate=date("d/m/Y",time()+(86400*$monbookcycle));
      $enddate=date("d/m/Y",time()+(86400*$monbookcycle*$monbooktotalperiod));
			print "<script language=\"JavaScript\">\n";
			print "<!-- JavaScript\n";
			print "window.location.replace(\"https://www.moneybookers.com/app/payment.pl?pay_to_email=$monbookemail&language=EN&transaction_id=$ordnum&currency=$monbookcurrency&amount=$total&detail1_description=Product:&detail1_text=$monbookdesc&return_url=$monbookreturn&cancel_url=$monbookcancel&rec_amount=$recurringtotal&rec_period=31&rec_start_date=$startdate&rec_end_date=$enddate\")\n";
			print "// - JavaScript - -->\n";
			print "</script>\n";
    }
    else
    {
			print "<script language=\"JavaScript\">\n";
			print "<!-- JavaScript\n";
			print "window.location.replace(\"https://www.moneybookers.com/app/payment.pl?pay_to_email=$monbookemail&language=EN&transaction_id=$ordnum&currency=$monbookcurrency&amount=$total&detail1_description=Product:&detail1_text=$monbookdesc&return_url=$monbookreturn&cancel_url=$monbookcancel\")\n";
			print "// - JavaScript - -->\n";
			print "</script>\n";
    }
	}
	// Bluepaid payment processing
	if (strcasecmp($payprocess,"Bluepaid")==0)
	{
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		print "window.location.replace(\"https://www.bluepaid.com/in.php?id_boutique=$blueidboutique&id_client=$blueidclient&devise=$bluedevise&langue=$bluelangue&montant=$total&divers=$ordnum\")\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}
  //  Caixagalicia payment processing
	if (strcasecmp($payprocess,"Caixagalicia")==0)
	{
		$caixagdesc=urlencode($caixagdesc);
		$centamount=intval($total*100);
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "window.location.replace(\"http://sdicomercio.caixagalicia.es/tpv2/default.asp?pedido=$ordnum&comercio=$caixagcomercio&vuelta=$caixagvuelta&importe=$centamount&moneda=$caixagmoneda\")\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
  }
  // cdgcommerce payment processing
	if (strcasecmp($payprocess,"cdgcommerce")==0)
	{
		$cdgdesc=urlencode($cdgdesc);
	  // See if we need to do one off or recurring billing
	  if ($recurringtotal>0)
		{
			$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
			print "window.location.replace(\"https://secure.paymentclearing.com/cgi-bin/mas/buynow.cgi?vendor_id=$cdgvendorid&home_page=$cdghome&showaddr=1&1-desc=$cdgdesc&1-cost=$total&1-qty=1&ret_addr=$cdgreturn&mername=$cdgmername&visaimage=1&mcimage=1&ameximage=1&discimage=1&dinerimage=1&acceptcards=1&recur_recipe=$cdgrecipe&recur_reps=999&recur_total=$recurringtotal&recur_desc=$cdgdesc\")\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
    else
    {
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
			print "window.location.replace(\"https://secure.paymentclearing.com/cgi-bin/mas/buynow.cgi?vendor_id=$cdgvendorid&home_page=$cdghome&showaddr=1&1-desc=$cdgdesc&1-cost=$total&1-qty=1&ret_addr=$cdgreturn&mername=$cdgmername&visaimage=1&mcimage=1&ameximage=1&discimage=1&dinerimage=1&acceptcards=1\")\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
  }
	// iKobo payment processing
	if (strcasecmp($payprocess,"iKobo")==0)
	{
	  print "<form action=\"https://www.iKobo.com/store/index.php\" method=\"post\" name=\"ikobobuy\">\n";
	  print "<input type=\"hidden\" name=\"cmd\" value=\"cart\">\n";
	  print "<input type=\"hidden\" name=\"poid\" value=\"$ikoboid\">\n";
	  print "<input type=\"hidden\" name=\"item\" value=\"$ikobodesc\">\n";
	  print "<input type=\"hidden\" name=\"price\" value=\"$total\">\n";
	  print "</form>\n";
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		print "document.forms[0].submit()\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}

  // Internet Secure payment processing
	if (strcasecmp($payprocess,"internetsecure")==0)
	{
		$insecdesc=urlencode($intsecdesc);
	  // See if we need to do one off or recurring billing
	  if ($recurringtotal>0)
		{
			$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
			print "window.location.replace(\"https://secure.internetsecure.com/process.cgi?MerchantNumber=$intsecid&ReturnURL=$intsecreturn&Products=Price::Qty::Code::Description::Flags|$total::1::$intsecitem::$intsecdesc::$intsecflags {RB amount=$recurringtotal startmonth=%2B1 frequency=monthly duration=0 email=2}\")\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
    else
    {
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
			print "window.location.replace(\"https://secure.internetsecure.com/process.cgi?MerchantNumber=$intsecid&ReturnURL=$intsecreturn&Products=Price::Qty::Code::Description::Flags|$total::1::$intsecitem::$intsecdesc::$intsecflags\")\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
  }
  // ePN payment processing
	if (strcasecmp($payprocess,"epn")==0)
	{
	  // See if we need to do one off or recurring billing
	  if ($recurringtotal>0)
		{
			$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
	    print "<FORM name=\"epnform\" ACTION=\"https://www.eProcessingNetwork.com/cgi-bin/dbe/order.pl\" METHOD=POST>\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"ePNAccount\" VALUE=\"$epnid\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"Total\" VALUE=\"$total\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"ID\" VALUE=\"$ordnum\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"ApprovedURL\" VALUE=\"$epnreturn\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"DeclinedURL\" VALUE=\"$epndecline\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"BackgroundColor\" VALUE=\"FFFFFF\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"TextColor\" VALUE=\"000000\">\n";
	    print "<input type=\"hidden\" name=\"RecurAmountOverride\" value=\"$recurringtotal\">\n";
	    print "<input type=hidden name=RecurMethodID value=\"$epnrecurr\">\n";
	    print "</FORM>\n";
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.epnform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
    else
    {
	    print "<FORM name=\"epnform\" ACTION=\"https://www.eProcessingNetwork.com/cgi-bin/dbe/order.pl\" METHOD=POST>\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"ePNAccount\" VALUE=\"$epnid\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"Total\" VALUE=\"$total\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"ID\" VALUE=\"$ordnum\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"ApprovedURL\" VALUE=\"$epnreturn\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"DeclinedURL\" VALUE=\"$epndecline\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"BackgroundColor\" VALUE=\"FFFFFF\">\n";
	    print "<INPUT TYPE=\"HIDDEN\" NAME=\"TextColor\" VALUE=\"000000\">\n";
	    print "</FORM>\n";
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.epnform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
  }
  // MetaCharge payment processing
	if (strcasecmp($payprocess,"MetaCharge")==0)
	{
	  // See if we need to do one off or recurring billing
	  if ($recurringtotal>0)
		{
			$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
	    print "<form  name=\"metaform\" action=\"https://secure.metacharge.com/mcpe/purser\" method=\"post\">\n";
	    print "<input type=\"hidden\" name=\"intInstID\" value=\"$metaid\">\n";
	    print "<input type=\"hidden\" name=\"strCartID\" value=\"$ordnum\">\n";
	    print "<input type=\"hidden\" name=\"strCurrency\" value=\"$metacurrency\">\n";
	    print "<input type=\"hidden\" name=\"strDesc\" value=\"$metadesc\">\n";
	    print "<input type=\"hidden\" name=\"fltSchAmount1\" value=\"$total\">\n";
	    print "<input type=\"hidden\" name=\"strSchPeriod1\" value=\"1M\">\n";
	    print "<input type=\"hidden\" name=\"fltSchAmount2\" value=\"$recurringtotal\">\n";
	    print "<input type=\"hidden\" name=\"strSchPeriod2\" value=\"1M\">\n";
	    print "<input type=\"hidden\" name=\"intRecurs\" value=\"1\">\n";
	    print "</FORM>\n";
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.metaform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
    else
    {
	    print "<form  name=\"metaform\" action=\"https://secure.metacharge.com/mcpe/purser\" method=\"post\">\n";
	    print "<input type=\"hidden\" name=\"intInstID\" value=\"$metaid\">\n";
	    print "<input type=\"hidden\" name=\"strCartID\" value=\"$ordnum\">\n";
	    print "<input type=\"hidden\" name=\"fltAmount\" value=\"$total\">\n";
	    print "<input type=\"hidden\" name=\"strCurrency\" value=\"$metacurrency\">\n";
	    print "<input type=\"hidden\" name=\"strDesc\" value=\"$metadesc\">\n";
	    print "</FORM>\n";
      print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.metaform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
  }
	// eGold payment processing
	if (strcasecmp($payprocess,"eGold")==0)
	{
	  print "<form name=\"egoldform\" action=\"https://www.e-gold.com/sci_asp/payments.asp\" method=\"POST\">\n";
	  print "<input type=\"hidden\" name=\"PAYEE_ACCOUNT\" value=\"$egoldid\">\n";
	  print "<input type=\"hidden\" name=\"PAYEE_NAME\" value=\"$egoldname\">\n";
	  print "<input type=\"hidden\" name=\"PAYMENT_AMOUNT\" value=\"$total\">\n";
	  print "<input type=\"hidden\" name=\"PAYMENT_UNITS\" value=\"$egoldunits\">\n";
	  print "<input type=\"hidden\" name=\"PAYMENT_METAL_ID\" value=\"$egoldmetalid\">\n";
	  print "<input type=\"hidden\" name=\"PAYMENT_URL\" value=\"$egoldreturn\">\n";
	  print "<input type=\"hidden\" name=\"NOPAYMENT_URL\" value=\"$egoldcancel\">\n";
	  print "<input type=\"hidden\" name=\"PAYMENT_URL_METHOD\" value=\"LINK\">\n";
	  print "<input type=\"hidden\" name=\"NOPAYMENT_URL_METHOD\" value=\"LINK\">\n";
	  print "<input type=\"hidden\" name=\"SUGGESTED_MEMO\" value=\"$egoldmemo\">\n";
	  print "<input type=\"hidden\" name=\"BAGGAGE_FIELDS\" value=\"\">\n";
	  print "</form>\n";
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "document.egoldform.submit()\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
	}
  	// OKO Banks payment processing
	if (strcasecmp($payprocess,"okobank")==0)
	{
		$viite=viite($ordnum);
	  print "<form name=\"okoform\" action=\"https://kultaraha.op.fi:443/cgi-bin/krcgi\" method=\"POST\">\n";
	  print "<input type=\"hidden\" name=\"action_id\" value=\"701\">\n";
	  print "<input type=\"hidden\" name=\"VERSIO\" value=\"1\">\n";
	  print "<input type=\"hidden\" name=\"MAKSUTUNNUS\" value=\"$ordnum\">\n";
	  print "<input type=\"hidden\" name=\"MYYJA\" value=\"$okoid\">\n";
	  print "<input type=\"hidden\" name=\"SUMMA\" value=\"$total\">\n";
	  print "<input type=\"hidden\" name=\"VIITE\" value=\"$viite\">\n";
	  print "<input type=\"hidden\" name=\"VIEST1\" value=\"$ordnum\">\n";
	  print "<input type=\"hidden\" name=\"VIEST2\" value=\"\">\n";
	  print "<input type=\"hidden\" name=\"VIESTI\" value=\"$okodesc-$ordnum\">\n";
	  print "<input type=\"hidden\" name=\"TARKISTE-VERSIO\" value=\"1\">\n";
	  print "<?php\n";
		$tarkiste=md5("1".$ordnum.$okoid.$total.$viite.$ordnum."".$okocurrency."1".$okokey);
		$tarkiste=strtoupper($tarkiste);
	  print "?>\n";
	  print "<input type=\"hidden\" name=\"TARKISTE\" value=\"$tarkiste\">\n";
	  print "<input type=\"hidden\" name=\"PALUU-LINKKI\" value=\"$okoreturn\">\n";
	  print "<input type=\"hidden\" name=\"PERUUTUS-LINKKI\" value=\"$okocancel\">\n";
	  print "<input type=\"hidden\" name=\"VALUUTTALAJI\" value=\"$okocurrency\">\n";
	  print "</form>\n";
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		print "document.okoform.submit()\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}
	// payson.se payment processing
	if (strcasecmp($payprocess,"payson")==0)
	{
	  $cost=$total;
	  $cost=str_replace(".",",",$cost);
		$paysonemail=urlencode($paysonemail);
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		//		print "window.location.replace(\"https://www.payson.se/prod/SendMoney/default.aspx?3m_m=2&Description=$ordnum&sellerEmail=$paysonemail&cost=$cost$paysonoption\")\n";
    print "window.location.replace(\"https://www.payson.se/SendMoney/?De=$ordnum&Se=$paysonemail&cost=$cost&ShippingAmount=0%2c00$paysonoption\")\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}
	// Picpay payment processing
	if (strcasecmp($payprocess,"Picpay")==0)
	{
	  print "<form action=\"https://www.picpay.com/securepay.php\" method=\"post\" name=\"picpayform\">\n";
	  print "<input type=\"hidden\" name=\"member\" value=\"$picpaymember\">\n";
	  print "<input type=\"hidden\" name=\"memo\" value=\"$picpaydesc\">\n";
	  print "<input type=\"hidden\" name=\"amount\" value=\"$total\">\n";
	  print "<input type=\"hidden\" name=\"returnurl\" value=\"$picpayreturn\">\n";
	  print "<input type=\"hidden\" name=\"cancelurl\" value=\"$picpaycancel\">\n";
	  print "</form>\n";
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		print "document.picpayform.submit()\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}
  // setcom.co.za payment processing
	if (strcasecmp($payprocess,"Setcom")==0)
	{
      print"<FORM NAME=\"setcomform\" METHOD=\"POST\" ACTION=\"https://www.setcom.com/secure/index.cfm\">\n";
      print"<INPUT TYPE=\"HIDDEN\" NAME=\"ButtonAction\" VALUE=\"buynow\">\n";
      print"<INPUT TYPE=\"HIDDEN\" NAME=\"MerchantIdentifier\" VALUE=\"$setcomid\">\n";
      print"<INPUT TYPE=\"HIDDEN\" NAME=\"CurrencyAlphaCode\" VALUE=\"$setcomcurrency\">\n";
      print"<INPUT TYPE=\"HIDDEN\" NAME=\"LIDSKU\" VALUE=\"$setcomsku\">\n";
      print" <INPUT TYPE=\"HIDDEN\" NAME=\"LIDDesc\" VALUE=\"$setcomdesc\">\n";
      print"<INPUT TYPE=\"HIDDEN\" NAME=\"LIDPrice\" VALUE=\"$total\">\n";
      print"<INPUT TYPE=\"HIDDEN\" NAME=\"LIDQty\" VALUE=\"1\">\n";
      print"<INPUT TYPE=\"HIDDEN\" NAME=\"ShippingRequired\" VALUE=\"0\">\n";
      print"<INPUT TYPE=\"HIDDEN\" NAME=\"MerchCustom\" VALUE=\"$ordnum\">\n";
      print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.setcomform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
  }
  // SecPay payment processing
	if (strcasecmp($payprocess,"SecPay")==0)
	{
	  // See if we need to do one off or recurring billing
	  if ($recurringtotal>0)
		{
			$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
			$startdate=date("Ymd",time()+(86400*30));
      $secpaydigest=md5($ordnum.$total.$secpayremotepass);
      print "<form name=\"secpayform\" method=\"post\" action=\"https://www.secpay.com/java-bin/ValCard\">\n";
      if ($secpayremotepass!="")
        print "<input name=\"digest\" type=\"hidden\" value=\"$secpaydigest\" />\n";
      print "<input name=\"merchant\" type=\"hidden\" value=\"$secpaymerchant\" />\n";
      print "<input name=\"trans_id\" type=\"hidden\" value=\"$ordnum\" />\n";
      print "<input name=\"amount\" type=\"hidden\" value=\"$total\" />\n";
      print "<input name=\"callback\" type=\"hidden\" value=\"$secpayreturn\" />\n";
      print "<input name=\"currency\" type=\"hidden\" value=\"$secpaycurrency\" />\n";
      print "<input name=\"repeat\" type=\"hidden\" value=\"$startdate/monthly/-1:$recurringtotal\" />\n";
      if ($secpaytest=="true")
        print "<input type=\"hidden\" name=\"test_status\" value=\"true\">\n";
      if ($secpaytemplate!="")
        print "<input type=\"hidden\" name=\"template\" value=\"$secpaytemplate\">\n";
      print "<input type=\"hidden\" name=\"options\" value=\"cart=cwhoisdomaincart\" />\n";
      print "</form>\n";
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.secpayform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
    else
    {
      $secpaydigest=md5($ordnum.$total.$secpayremotepass);
      print "<form name=\"secpayform\" method=\"post\" action=\"https://www.secpay.com/java-bin/ValCard\">\n";
      if ($secpayremotepass!="")
        print "<input name=\"digest\" type=\"hidden\" value=\"$secpaydigest\" />\n";
      print "<input name=\"merchant\" type=\"hidden\" value=\"$secpaymerchant\" />\n";
      print "<input name=\"trans_id\" type=\"hidden\" value=\"$ordnum\" />\n";
      print "<input name=\"amount\" type=\"hidden\" value=\"$total\" />\n";
      print "<input name=\"callback\" type=\"hidden\" value=\"$secpayreturn\" />\n";
      print "<input name=\"currency\" type=\"hidden\" value=\"$secpaycurrency\" />\n";
      if ($secpaytest=="true")
        print "<input type=\"hidden\" name=\"test_status\" value=\"true\">\n";
      print "<input type=\"hidden\" name=\"options\" value=\"cart=cwhoisdomaincart\" />\n";
      print "</form>\n";
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.secpayform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
  }
  // mals-e payment processing
	if (strcasecmp($payprocess,"Mals")==0)
	{
      print "<form name=\"malsform\" action=\"$malsurl/cf/addmulti.cfm\" method=\"post\">\n";
      print "<input type=\"hidden\" name=\"userid\" value=\"$malsuserid\" />\n";
      print "<input type=\"hidden\" name=\"noqty\" value=\"1\" />\n";
      print "<input type=\"hidden\" name=\"product\" value=\"$malsdesc - $ordnum\" />\n";
      print "<input type=\"hidden\" name=\"price\" value=\"$total\" />\n";
      print "<input type=\"hidden\" name=\"return\" value=\"$malsreturn\" />\n";
      print "</form>\n";
      print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.malsform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
  }
  // Linkpoint payment processing
	if (strcasecmp($payprocess,"Linkpoint")==0)
	{
      print "<form name=\"lpform\" action=\"https://www.linkpointcentral.com/lpc/servlet/lppay\" method=\"post\">\n";
      print "<input type=\"hidden\" name=\"storename\" value=\"$lpstorename\">\n";
      print "<input type=\"hidden\" name=\"chargetotal\" value=\"$total\">\n";
      print "<input type=\"hidden\" name=\"txnorg\" value=\"eci\">\n";
      print "<input type=\"hidden\" name=\"mode\" value=\"payplus\">\n";
      print "<input type=\"hidden\" name=\"txntype\" value=\"sale\">\n";
      print "<input type=\"hidden\" name=\"oid\" value=\"$ordnum\">\n";
      print "<input type=\"hidden\" name=\"responseSuccessURL\" value=\"$lpreturn\">\n";
      print "<input type=\"hidden\" name=\"responseFailURL\" value=\"$lpcancel\">\n";
      print "</form>\n";
      print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.lpform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
  }
  // paymate.com.au payment processing
	if (strcasecmp($payprocess,"Paymate")==0)
	{
		$paymatedesc=urlencode($paymatedesc);
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "window.location.replace(\"https://www.paymate.com/PayMate/ExpressPayment?mid=$paymateid&amt=$total&ref=$paymatedesc&currency=$paymatecurrency&\")\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
  }
  // Google Checkout payment processing
	if (strcasecmp($payprocess,"GoogleCheckout")==0)
	{
      print "<form name=\"gcform\" action=\"https://checkout.google.com/cws/v2/Merchant/".$gcmerchantid."/checkoutForm\" id=\"BB_BuyButtonForm\" method=\"post\" name=\"BB_BuyButtonForm\">\n";
      print "<input name=\"item_name_1\" type=\"hidden\" value=\"".$ordnum."\"/>\n";
      print "<input name=\"item_description_1\" type=\"hidden\" value=\"".$gcdesc."\"/>\n";
      print "<input name=\"item_quantity_1\" type=\"hidden\" value=\"1\"/>\n";
      print "<input name=\"item_price_1\" type=\"hidden\" value=\"".$total."\"/>\n";
      print "<input name=\"item_currency_1\" type=\"hidden\" value=\"".$gccurrency."\"/>\n";
      print "<input name=\"_charset_\" type=\"hidden\" value=\"utf-8\"/>\n";
      print "</form>\n";
      print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.gcform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
  }
  // Pagseguro payment processing
	if (strcasecmp($payprocess,"Pagseguro")==0)
	{
    print "<form name='pgform' target='pagseguro' action='https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx' method='post'>\n";
    print " <input type='hidden' name='email_cobranca' value='".$pgemailcobranca."' />\n";
    print "<input type='hidden' name='tipo' value='".$pgtipo."' />\n";
    print "<input type='hidden' name='moeda' value='".$pgmoeda."' />\n";
    print "<input type='hidden' name='item_id_1' value='".$total."' />\n";
    print "<input type='hidden' name='item_descr_1' value='".$pgdesc."' />\n";
    print "<input type='hidden' name='item_quant_1' value='1' />\n";
    print "<input type='hidden' name='item_valor_1' value='".$total."' />\n";
    print "<input type='hidden' name='item_frete_1' value='0' />\n";
    print "</form>\n";
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
//    print "document.pgform.submit()\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
	}

  // Ideal payment processing
  if (strcasecmp($payprocess,"Ideal")==0)
  {    $totald=$total*100;
      print "<script language=\"JavaScript\">\n";
      print "<!-- JavaScript\n";
      print "window.location.replace(\" https://ideal.secure-ing.com/ideal/mpiPayInitIng.do?merchantID=$idealid&subID=0&amount=$totald&purchaseID=$ordnum&language=$ideallang&currency=$idealcurrency&description=$idealdesc&itemNumber1=$idealitem&itemDescription1=$idealdesc&itemQuantity1=1&itemPrice1=$totald&paymentType=ideal&validUntil=2008-12-01T12:00:00:0000Z\")\n";
      print "// - JavaScript - -->\n";
      print "</script>\n";
  }
  // AlertPay payment processing
	if (strcasecmp($payprocess,"AlertPay")==0)
	{
	  // See if we need to do one off or recurring billing
	  if ($recurringtotal>0)
		{
			$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);

      print "<form name=\"alertpayform\"method=\"post\" action=\"https://www.alertpay.com/PayProcess.aspx\" >\n";
      print "<input type=\"hidden\" name=\"ap_purchasetype\" value=\"subscription\"/>\n";
      print "<input type=\"hidden\" name=\"ap_merchant\" value=\"$alertpayid\"/>\n";
      print "<input type=\"hidden\" name=\"ap_itemname\" value=\"$alertpayname\"/>\n";
      print "<input type=\"hidden\" name=\"ap_currency\" value=\"$alertpaycurrency\"/>\n";
      print "<input type=\"hidden\" name=\"ap_returnurl\" value=\"$alertpayreturn\"/>\n";
      print "<input type=\"hidden\" name=\"ap_itemcode\" value=\"$alertpaycode\"/>\n";
      print "<input type=\"hidden\" name=\"ap_quantity\" value=\"1\"/>\n";
      print "<input type=\"hidden\" name=\"ap_description\" value=\"$alertpaydesc\"/>\n";
      print "<input type=\"hidden\" name=\"ap_trialamount\" value=\"$total\"/>\n";
      print "<input type=\"hidden\" name=\"ap_trialtimeunit\" value=\"Month\"/>\n";
      print "<input type=\"hidden\" name=\"ap_trialperiodlength\" value=\"1\"/>\n";
      print "<input type=\"hidden\" name=\"ap_trialperiodcount\" value=\"0\"/>\n";
      print "<input type=\"hidden\" name=\"ap_amount\" value=\"$recurringtotal\"/>\n";
      print "<input type=\"hidden\" name=\"ap_timeunit\" value=\"Month\"/>\n";
      print "<input type=\"hidden\" name=\"ap_periodlength\" value=\"1\"/>\n";
      print "<input type=\"hidden\" name=\"ap_periodcount\" value=\"0\"/>\n";
      print "<input type=\"hidden\" name=\"ap_cancelurl\" value=\"$alertpaycancel\"/>\n";
      print "</form>\n";
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.alertpayform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
    else
    {
      print "<form name=\"alertpayform\"method=\"post\" action=\"https://www.alertpay.com/PayProcess.aspx\" >\n";
      print "<input type=\"hidden\" name=\"ap_purchasetype\" value=\"service\"/>\n";
      print "<input type=\"hidden\" name=\"ap_merchant\" value=\"$alertpayid\"/>\n";
      print "<input type=\"hidden\" name=\"ap_itemname\" value=\"$alertpayname\"/>\n";
      print "<input type=\"hidden\" name=\"ap_currency\" value=\"$alertpaycurrency\"/>\n";
      print "<input type=\"hidden\" name=\"ap_returnurl\" value=\"$alertpayreturn\"/>\n";
      print "<input type=\"hidden\" name=\"ap_itemcode\" value=\"$alertpaycode\"/>\n";
      print "<input type=\"hidden\" name=\"ap_quantity\" value=\"1\"/>\n";
      print "<input type=\"hidden\" name=\"ap_description\" value=\"$alertpaydesc\"/>\n";
      print "<input type=\"hidden\" name=\"ap_amount\" value=\"$total\"/>\n";
      print "<input type=\"hidden\" name=\"ap_cancelurl\" value=\"$alertpaycancel\"/>\n";
      print "</form>\n";
	    print "<script language=\"JavaScript\">\n";
	    print "<!-- JavaScript\n";
	    print "document.alertpayform.submit()\n";
	    print "// - JavaScript - -->\n";
	    print "</script>\n";
    }
  }
  // Swreg payment processing
  if (strcasecmp($payprocess,"Swreg")==0)
  {
      print "<script language=\"JavaScript\">\n";
      print "<!-- JavaScript\n";
      print "window.location.replace(\"https://usd.swreg.org/cgi-bin/s.cgi?s=$swregid&p=$swregproduct&v=0&d=0&q=1&vp=$total\")\n";
      print "// - JavaScript - -->\n";
      print "</script>\n";
  }
	// Virtualpaycash Processing
	if (strcasecmp($payprocess,"vpcash")==0)
	{
	  print "<form name=\"vpcash\" action=\"https://www.virtualpaycash.net/handle.php\" method=\"POST\">\n";
	  print "<input type=\"hidden\" name=\"merchantAccount\" value=\"$vpc_merchand\">\n";
	  print "<input type=\"hidden\" name=\"vpc_currency\" value=\"$vpc_currency\">\n";
	  print "<input type=\"hidden\" name=\"amount\" value=\"$total\">\n";
	  print "<input type=\"hidden\" name=\"item_id\" value=\"$vpc_item\">\n";
	  print "<input type=\"hidden\" name=\"merchantSecurityWord\" value=\"$vpc_merchantSecurityWord\">\n";
	  print "<input type=\"hidden\" name=\"return_url\" value=\"$vpc_return_url\">\n";
	  print "<input type=\"hidden\" name=\"notify_url\" value=\"$vpc_notify_url\">\n";
	  print "<input type=\"hidden\" name=\"cancel_url\" value=\"$vpc_cancel_url\">\n";
	  print "</form>\n";
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "document.vpcash.submit()\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
	}

	// Global Digital Pay Processing
	if (strcasecmp($payprocess,"GlobalDigitalPay")==0)
	{
    print "<form name=\"gdp\" method=\"post\" action=\"https://www.globaldigitalpay.com/process.htm\">\n";
    print "<input type=\"hidden\" name=\"member\" value=\"$gdp_member\">\n";
    print "<input type=\"hidden\" name=\"action\" value=\"service\">\n";
    print "<input type=\"hidden\" name=\"product\" value=\"$gdp_desc\">\n";
    print "<input type=\"hidden\" name=\"price\" value=\"$total\">\n";
    print "<input type=\"hidden\" name=\"currency\" value=\"$gdp_currency\">\n";
    print "<input type=\"hidden\" name=\"nocheck\" value=\"1\">\n";
    print "<input type=\"hidden\" name=\"store_id\" value=\"$gdp_storeid\">\n";
    print "<input type=\"hidden\" name=\"comments\" value=\"$ordnum\">\n";
    print "<input type=\"hidden\" name=\"success_url\" value=\"$gdp_success\">\n";
    print "<input type=\"hidden\" name=\"cancel_url\" value=\"$gdp_cancel\">\n";
    print "</form>\n";
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "document.gdp.submit()\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
	}

	// Amazon Pay Processing
	if (strcasecmp($payprocess,"Amazon")==0)
	{
	  $sig="";
    print "<form name=\"amazon\" action=\"https://authorize.payments.amazon.com/pba/paypipeline\" method=\"post\">\n";
    print "<input type=\"hidden\" name=\"immediateReturn\" value=\"1\" >\n";
    print "<input type=\"hidden\" name=\"collectShippingAddress\" value=\"0\" >\n";
    print "<input type=\"hidden\" name=\"signatureVersion\" value=\"2\" >\n";
    print "<input type=\"hidden\" name=\"signatureMethod\" value=\"HmacSHA256\" >\n";
    print "<input type=\"hidden\" name=\"accessKey\" value=\"$amazon_accesskey\" >\n";
    print "<input type=\"hidden\" name=\"referenceId\" value=\"$ordnum\" >\n";
    print "<input type=\"hidden\" name=\"amount\" value=\"$amazon_currency $total\" >\n";
    print "<input type=\"hidden\" name=\"signature\" value=\"$sig\" >\n";
    print "<input type=\"hidden\" name=\"isDonationWidget\" value=\"0\" >\n";
    print "<input type=\"hidden\" name=\"description\" value=\"$amazon_desc\" >\n";
    print "<input type=\"hidden\" name=\"amazonPaymentsAccountId\" value=\"$amazon_payid\" >\n";
    print "<input type=\"hidden\" name=\"returnUrl\" value=\"$amazon_success\" >\n";
    print "<input type=\"hidden\" name=\"processImmediate\" value=\"1\" >\n";
    print "<input type=\"hidden\" name=\"cobrandingStyle\" value=\"logo\" >\n";
    print "<input type=\"hidden\" name=\"abandonUrl\" value=\"$amazon_cancel\" >\n";
    print "</form>\n";
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "document.gdp.submit()\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
	}

  // MoIP payment processing
  if (strcasecmp($payprocess,"MoIP")==0)
  {
    $centamount=intval($total*100);
    print "<form name='pgform' target='moip' action='https://www.moip.com.br/PagamentoMoIP.do' method='post'>\n";
    print " <input type='hidden' name='id_carteira' value='".$pgemailcobranca."' />\n";
    print "<input type='hidden' name='id_transacao' value='".$pgtrans."' />\n";
    print "<input type='hidden' name='moeda' value='".$pgmoeda."' />\n";
    print "<input type='hidden' name='valor' value='".$centamount."' />\n";
    print "<input type='hidden' name='nome' value='".$pgdesc."' />\n";
    print "<input type='hidden' name='item_quant_1' value='1' />\n";
    print "<input type='hidden' name='item_frete_1' value='0' />\n";
    print "</form>\n";
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "document.pgform.submit()\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
  }

    // Mollie Ideal payment processing
  if (strcasecmp($payprocess,"MollieIdealOld")==0)
  {
    $mollieideal_reporturl=urlencode($mollieideal_reporturl);
    $mollieideal_returnurl=urlencode($mollieideal_returnurl);
    $mollieideal_desc=urlencode($mollieideal_desc);
    $totald=$total*100;
    // Call mollie to get bank URL to call
    $url="https://secure.mollie.nl/xml/ideal?a=fetch&partnerid=$mollieideal_partnerid&description=$mollieideal_desc&reporturl=$mollieideal_reporturl&returnurl=$mollieideal_returnurl&amount=$totald&bank_id=$cfmollieidealbank";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $pos=strpos($response,"<URL>");
    $pos2=strpos($response,"</URL>",$pos);
    $bankurl=substr($response,$pos+5,$pos2-$pos-5);
    $bankurl=str_replace("&amp;","&",$bankurl);
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "window.location.replace(\"$bankurl\")\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
  }

  // Mollie Ideal payment processing
  if (strcasecmp($payprocess,"MollieIdeal")==0)
  {
    require_once "Mollie/API/Autoloader.php";
    try
    {
    	$mollie = new Mollie_API_Client;
    	$mollie->setApiKey($mollieideal_api);
    	$order_id = $ordnum;
    	$protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
    	$hostname = $_SERVER['HTTP_HOST'];
    	$path     = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);
    	$payment = $mollie->payments->create(array(
    		"amount"       => $total,
    		"method"       => $mollieideal_method,
    		"description"  => $mollieideal_desc,
    		"redirectUrl"  => $mollieideal_returnurl."?order_id=".$order_id,
    		"metadata"     => array(
    			"order_id" => $order_id,
    		),
    		"issuer"       => NULL
    	));
    	$bankurl=$payment->getPaymentUrl();
      print "<script language=\"JavaScript\">\n";
      print "<!-- JavaScript\n";
      print "window.location.replace(\"$bankurl\")\n";
      print "// - JavaScript - -->\n";
      print "</script>\n";
    }
    catch (Mollie_API_Exception $e)
    {
      echo "Mollie API call failed: " . htmlspecialchars($e->getMessage());
      exit;
    }
  }

  // PayuLatam payment processing
  // http://docs.payulatam.com/en/web-checkout-integration/integrate/
  if (strcasecmp($payprocess,"PayuLatam")==0)
  {
    $signature=md5("$payulapikey~$payulmerchantid~$ordnum~$total~$payulcurrency");
    print"<form name='payulform' method='post' action='https://stg.gateway.payulatam.com/ppp-web-gateway/'>\n";
    print"<input name='merchantId' type='hidden' value='$payulmerchantid'/>\n";
    print"<input name='accountId' type='hidden' value='$payulaccountid'/>\n";
    print"<input name='description' type='hidden' value='$payulaccountdesc'/>\n";
    print"<input name='referenceCode' type='hidden' value='$ordnum'/>\n";
    print"<input name='amount' type='hidden' value='$total'/>\n";
    print"<input name='tax' type='hidden' value='16'/>\n";
    print"<input name='taxReturnBase' type='hidden' value='0'/>\n";
    print"<input name='shipmentValue' value='0' type='hidden'/>\n";
    print"<input name='currency' type='hidden' value='$payulcurrency'/>\n";
    print"<input name='lng' type='hidden' value='$payullng'/>\n";
    print"<input name='sourceUrl' id='urlOrigen' value='' type='hidden'/>\n";
    print"<input name='buttonType' value='SIMPLE' type='hidden'/>\n";
    print"<input name='signature' value='$signature' type='hidden'/>\n";
    print"</form>\n";
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "document.payulform.submit()\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
  }

  // AccessPay payment processing
  if (strcasecmp($payprocess,"AccessPay")==0)
  {
    print"<form name='upay_form' id='upay_form' method='post' action='https://cipg.accessbankplc.com/MerchantServices/MakePayment.aspx' target='_top'>\n";
    print"<input type='hidden' name='mercId' value='$accesspaymercid'>\n";
    print"<input type='hidden' name='currCode' value='$accesspaycurrcode'>\n";
    print"<input type='hidden' name='amt' value='$total'>\n";
    print"<input type='hidden' name='orderId' value='$ordnum'>\n";
    print"<input type='hidden' name='prod' value='$accesspaydesc'>\n";
    print"<input type='hidden' name='email' value='$cfemail'>\n";
    print"</form>\n";
    print "<script language=\"JavaScript\">\n";
    print "<!-- JavaScript\n";
    print "document.upay_form.submit()\n";
    print "// - JavaScript - -->\n";
    print "</script>\n";
  }


  // Manual payment processing
	if (($payprocess=="") ||(strcasecmp($payprocess,"manual")==0))
	{
	  print "<form action=\"$manualreturn\" method=\"get\" name=\"manualform\">\n";
	  print "<input type=\"hidden\" name=\"ordnum\" value=\"$ordnum\">\n";
	  print "<input type=\"hidden\" name=\"total\" value=\"$total\">\n";
	  print "<input type=\"hidden\" name=\"recurringtotal\" value=\"$recurringtotal\">\n";
	  print "<input type=\"hidden\" name=\"sessionid\" value=\"".session_id()."\">\n";
	  print "</form>\n";
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		print "document.manualform.submit()\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}
	// Manual payment processing (second option)
	if (strcasecmp($payprocess,"manual2")==0)
	{
	  print "<form action=\"$manual2return\" method=\"get\" name=\"manual2form\">\n";
	  print "<input type=\"hidden\" name=\"ordnum\" value=\"$ordnum\">\n";
	  print "<input type=\"hidden\" name=\"total\" value=\"$total\">\n";
	  print "<input type=\"hidden\" name=\"recurringtotal\" value=\"$recurringtotal\">\n";
	  print "<input type=\"hidden\" name=\"sessionid\" value=\"".session_id()."\">\n";
	  print "</form>\n";
		print "<script language=\"JavaScript\">\n";
		print "<!-- JavaScript\n";
		print "document.manual2form.submit()\n";
		print "// - JavaScript - -->\n";
		print "</script>\n";
	}
}

function hmac($key, $data)
{
	// If key is longer than 64 then hash it
	if (strlen($key)>64)
	  $key=md5($key);
	// If key is shorter than 64 then pad 0's
	if (strlen($key)<64)
	{
	  for ($k=strlen($key);$k<64;$k++)
	    $key=$key.chr(0);
	}
	// xor converted key with ipad
	$str1="";
	for ($k=0;$k<64;$k++)
	  $str1.=chr(ord($key[$k])^0x36);
	// Append data
	$str1=$str1.$data;
	// Hash str1
	$str1=cwhex2bin(md5($str1));
	// xor converted key with opad
	$str2="";
	for ($k=0;$k<64;$k++)
	  $str2.=chr(ord($key[$k])^0x5C);
	// Append str1 to str2
	$str2.=$str1;
	// Finally hash result
	$str2=md5($str2);
  return($str2);
}

function cwhex2bin($s)
{
	for ($i=0;$i< strlen($s);$i+=2)
	{
		$bin.=chr(hexdec(substr($s,$i,2)));
	}
	return $bin;
}

function viite($ref)
{
  $weights="137";
  $weightpos=2;
  $total=0;
  for ($k=strlen($ref)-1;$k>=0;$k--)
  {
    $total=$total+($ref[$k]*$weights[$weightpos]);
    $weightpos--;
    if ($weightpos<0)
      $weightpos=2;
  }
  $checkdigit=((intval($total/10)*10)+10)-$total;
  if ($checkdigit==10)
    $checkdigit=0;
  $viite=$ref.sprintf("%u",$checkdigit);
  return ($viite);
}

function cwc_SendEmailOut($toemail, $fromemail, $fromname, $subject, $mailBody, $htmlformat)
{
  global $EmailHeaderNoSlashR, $ExtraMailParam, $ErrorTemplate, $ErrorEmail, $UsePHPmailer, $UsePearMail;
  global $Custom_Mail_Headers;
  // Remove any comma in from name
  $fromname = str_replace(",", " ", $fromname);
  // Handle multiple email addresses
  $sendtoemail=explode(",",$toemail);
  // If phpmailer setup then use it otherwise handle with PHP mail() function
  if ($UsePHPmailer == 1)
  {
    global $EmailUsername, $EmailPassword, $EmailServer, $EmailPort, $EmailAuth, $EmailServerSecurity;
    if ($EmailPort=="")
      $EmailPort=25;
    if ($EmailAuth="")
      $EmailAuth=1;
    require_once("class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = $EmailServer;
		$mail->Port = $EmailPort;
    if ($EmailAuth=="0")
  		$mail->SMTPAuth = false;
		else
  		$mail->SMTPAuth = true;
  	if ($EmailServerSecurity!="")
  	  $mail->SMTPSecure = $EmailServerSecurity;
    $mail->Username = $EmailUsername;
    $mail->Password = $EmailPassword;
    $mail->From = $fromemail;
    $mail->FromName = $fromname;
    for ($k=0; $k<count($sendtoemail); $k++)
      $mail->AddAddress($sendtoemail[$k]);
    if ($htmlformat == "Y")
      $mail->IsHTML(true);
    else
      $mail->IsHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $mailBody;
    if ($Custom_Mail_Headers!="")
    {
      $cushd=explode("\r\n",$Custom_Mail_Headers);
      for ($k=0;$k<count($cushd);$k++)
      {
        if ($cushd[$k]!="")
          $mail->AddCustomHeader($cushd[$k]);
      }
    }
    $mail->Send();
    return;
  }
  if ($UsePearMail == 1)
  {
    global $EmailUsername, $EmailPassword, $EmailServer;
    $headers = array ('From' => $fromemail,
                      'To' => $sendtoemail[0],
                      'Subject' => $subject);
    $smtp = Mail::factory('smtp',array ('host' => $EmailServer,
    'auth' => true,
    'username' => $EmailUsername,
    'password' => $EmailPassword));
    $mail = $smtp->send($sendtoemail[0], $headers, $mailBody);
    if (PEAR::isError($mail))
    {
      echo($mail->getMessage());
    }
    return;
  }
  // If still here then use PHP mail() function
  $headers = "From: " . $fromname . " <" . $fromemail . ">\r\n";
  $headers.= "Reply-To: " . $fromname . " <" . $fromemail . ">\r\n";
  $headers.= "MIME-Version: 1.0\r\n";
  if ($htmlformat=="Y")
  {
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "Content-Transfer-Encoding: base64\r\n";
    $mailBody=chunk_split(base64_encode($mailBody));
  }
  else
    $headers .= "Content-type: text/plain\r\n";
  if ($Custom_Mail_Headers!="")
    $headers .= $Custom_Mail_Headers;
  if ($EmailHeaderNoSlashR == 1)
    $headers = str_replace("\r", "", $headers);
  for ($k=0; $k<count($sendtoemail); $k++)
  {
    if ($ExtraMailParam != "")
      $sent = mail($sendtoemail[$k], $subject, $mailBody, $headers, $ExtraMailParam);
    else
      $sent = mail($sendtoemail[$k], $subject, $mailBody, $headers);
  }
}

function orderdetailshtml(&$total,&$recurringtotal,$cssstyle)
{
	global $lang,$cotextsize,$cotextcolor,$numhost,$csymbol,$csymbol2,$decimalplaces,$taxrate,$extrafee;
	global $taxrate1,$taxrate2,$taxontax,$buyhosting,$buyregister,$buytransfer,$buyrenew;
	global $ipaytotal;
  // Simple cart variables
  global $carttax,$cartsubtotal,$carttotal,$cartrecurringtotal,$cartdomain,$cartdomainopt,$cartdomaintime,$cartdomainprice;
  global $carthosting,$carthostingopt,$carthostinrecurr,$carthostingprice,$cartitemtotal,$cartitemrecurringtotal;
  global $carttaxrate,$carttaxrate1,$carttaxrate2,$cartextrafee,$cartrecurringsubtotal,$cartrecurringtax;
  global $carttaxglobal,$cartrecurringtaxglobal,$carttax1,$cartrecurringtax1,$carttax2,$cartrecurringtax2,$carthostingsetup;
  global $couponcode;
  $carttax=0.00;
  $carttaxglobal=0.00;
  $carttax1=0.00;
  $carttax2=0.00;
  $cartcoupondiscount=0.00;
  $cartcoupondiscountrecurring=0.00;
  $cartcouponcode="";
  $cartrecurringtaxglobal=0.00;
  $cartrecurringtax1=0.00;
  $cartrecurringtax2=0.00;
  $cartsubtotal=0.00;
  $cartrecurringsubtotal=0.00;
  $carttotal=0.00;
	$cartrecurringtotal=0.00;
  $cartdomain="";
  $cartdomainopt="";
  $cartdomainprice="";
  $carthosting="";
  $carthostingrecurr="";
  $carthostingprice="";
  $carthostingsetup="";
  $cartitemtotal="";
  $cartitemrecurringtotal="";
	// End
	$buf="";
  // Column headings
  $buf.="<tr>\n";
  $buf.="<td  class=\"".$cssstyle."\">\n";
  $buf.="<b class=\"".$cssstyle."\">".$lang['Domain']."</b>";
  $buf.="</td>\n";
  $buf.="<td  class=\"".$cssstyle."\">\n";
  $buf.="<b class=\"".$cssstyle."\">".$lang['DomainOpt']."</b>";
  $buf.="</td>\n";
  $buf.="<td  class=\"".$cssstyle."\">\n";
  if ($numhost>0)
    $buf.="<b class=\"".$cssstyle."\">".$lang['HostingOpt']."</b>";
  else
    $buf.="&nbsp;";
  $buf.="</td>\n";
  // Calculate recurring amount so that we know in advance whether to display monthly column.
  $recurringtotal=0.00;
  for ($k=1;$k<=$_SESSION['numberofitems'];$k++)
  {
    if ($_SESSION['removed'.$k]==False)
    {
      if ($numhost>0)
      {
        $hostrecurr=$_SESSION['hostrecurr'.$k];
        $hostprice=$_SESSION['hostprice'.$k];
        if ($hostrecurr!="S")
          $recurringtotal=$recurringtotal+$hostprice;
      }
    }
  }
  if ($recurringtotal>0.00)
  {
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\">\n";
    $buf.="<b class=\"".$cssstyle."\">".$lang['Now']."</b>";
    $buf.="</td>\n";
    $buf.="<td  class=\"".$cssstyle."\">\n";
    $buf.="<b class=\"".$cssstyle."\">".$lang['Monthly']."</b>";
    $buf.="</td>\n";
    $buf.="</tr>\n";
  }
  else
  {
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\">\n";
    $buf.="<b class=\"".$cssstyle."\">".$lang['Subtotal']."</b>";
    $buf.="</td>\n";
    $buf.="<td  class=\"".$cssstyle."\">&nbsp;</td></tr>\n";
  }
  $total=0.00;
  for ($k=1;$k<=$_SESSION['numberofitems'];$k++)
  {
    if ($_SESSION['removed'.$k]==False)
    {
	      // Simple cart variables
	      if ($cartdomain!="") $cartdomain.=",";
	      if ($cartdomainopt!="") $cartdomainopt.=",";
	      if ($cartdomaintime!="") $cartdomaintime.=",";
	      if ($cartdomainprice!="") $cartdomainprice.=",";
	      if ($carthosting!="") $carthosting.=",";
	      if ($carthostingrecurr!="") $carthostingrecurr.=",";
	      if ($carthostingprice!="") $carthostingprice.=",";
	      if ($carthostingsetup!="") $carthostingsetup.=",";
	      if ($cartitemtotal!="") $cartitemtotal.=",";
	      if ($cartitemrecurringtotal!="") $cartitemrecurringtotal.=",";
	      $cartdomain.=$_SESSION['domain'.$k];
	      $cartdomainopt.=$_SESSION['regtype'.$k];
        $cartdomaintime.=$_SESSION['regperiod'.$k];
        $cartdomainprice.=$_SESSION['regprice'.$k];
	      $carthosting.=$_SESSION['hostdesc'.$k];
	      $carthostingrecurr.=$_SESSION['hostrecurr'.$k];
	      $carthostingprice.=$_SESSION['hostprice'.$k];
	      $carthostingsetup.=$_SESSION['hostsetup'.$k];
	      // End
        $subtotal=0.00;
        $buf.="<tr>\n";
        $buf.="<td  class=\"".$cssstyle."\"><p class=\"".$cssstyle."\">\n";
        $buf.=$_SESSION['domain'.$k];
        $buf.="</p></td>";
        $buf.="<td  class=\"".$cssstyle."\"><p class=\"".$cssstyle."\">\n";
        if ($_SESSION['regtype'.$k]=="H")
          $buf.=$lang['HostingOnly'];
        if ($_SESSION['regtype'.$k]=="R")
        {
          $buyregister=1;
          $buf.=$lang['Register']." ";
          $buf.=$_SESSION['regperiod'.$k];
          $buf.=" ".$lang['Year'];
        }
        if ($_SESSION['regtype'.$k]=="T")
        {
          $buytransfer=1;
          $buf.=$lang['Transfer']." ";
          $buf.=$_SESSION['regperiod'.$k];
          $buf.=" ".$lang['Year'];
        }
        if ($_SESSION['regtype'.$k]=="N")
        {
          $buyrenew=1;
          $buf.=$lang['Renew']." ";
          $buf.=$_SESSION['regperiod'.$k];
          $buf.=" ".$lang['Year'];
        }
        $subtotal=$subtotal+$_SESSION['regprice'.$k];
        $buf.="</p></td>\n";
        $buf.="<td  class=\"".$cssstyle."\"><p class=\"".$cssstyle."\">\n";
        if ($numhost>0)
        {
          $hostdesc=$_SESSION['hostdesc'.$k];
          $hostsetup=$_SESSION['hostsetup'.$k];
          $hostprice=$_SESSION['hostprice'.$k];
          $hostrecurr=$_SESSION['hostrecurr'.$k];
          if ($hostdesc=="")
          {
            $buf.=$lang['NoHosting'];
          }
          else
          {
            $buyhosting=1;
            // Check to see if recurring billing
            if ($hostsetup==0)
              $buf.=$hostdesc;
            else
              $buf.=$hostdesc;
            $subtotal=$subtotal+$hostprice+$hostsetup;
//              if ($hostrecurr!="S")
//                $recurringtotal=$recurringtotal+$hostprice;
          }
        }
        else
          $buf.="&nbsp;";
        $buf.="</p></td>\n";
        $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
        $cartitemtotal.=sprintf("%01.".$decimalplaces."f",$subtotal);
        $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$subtotal);
        $buf.="</p></td>\n";
        $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
        if ($recurringtotal>0.00)
        {
          if ($hostrecurr!="S")
          {
            $cartitemrecurringtotal.=sprintf("%01.".$decimalplaces."f",$hostprice);
            $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$hostprice);
          }
          else
          {
            $cartitemrecurringtotal.="0.00";
            $buf.="$csymbol"."0.00".$csymbol2;
          }
        }
        $buf.="</p></td >\n";
        $buf.="</tr>";
        $total=$total+$subtotal;
    }
  }
  // Admin fee
  if ($extrafee>0)
  {
    $buf.="<tr>\n";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\" colspan=\"3\">\n";
    $buf.="<p class=\"".$cssstyle."\">".$lang['ExtraFee']."</p></td><td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
    $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$extrafee);
    $buf.="</p></td>\n";
    if ($recurringtotal>0.00)
    {
      $buf.="<td  class=\"".$cssstyle."\" align=\"right\">\n";
      $buf.="<p class=\"".$cssstyle."\">".$csymbol."0.00".$csymbol2."</p></td>\n";
    }
    $buf.="</TR>\n";
    $total=$total+$extrafee;
  }

  // See if any coupon codes apply to order
  $coupondiscount="0.00";
  $coupondiscountrecurring="0.00";
  cwc_ValidateCoupon($couponcode,$total,$recurringtotal,$coupondiscount,$coupondiscountrecurring);
  if (($coupondiscount>0) || ($coupondiscountrecurring>0))
  {
    $buf.="<tr>\n";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\" colspan=\"3\"><p class=\"".$cssstyle."\">\n";
    $buf.=$lang['Discount']."</p></td>";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
    $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$coupondiscount);
    $buf.="</p></td>";
    if ($recurringtotal>0.00)
    {
      $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
      $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$coupondiscountrecurring);
      $buf.="</p></td>\n";
    }
    $buf.="</tr>\n";
    $total=$total-$coupondiscount;
    $recurringtotal=$recurringtotal-$coupondiscountrecurring;
  }
  //End of coupon code


  // Subtotals
  if (($taxrate>0) || ($taxrate1>0) || ($taxrate2>0))
  {
    $buf.="<tr>\n";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\" colspan=\"3\"><p class=\"".$cssstyle."\">\n";
    $buf.=$lang['Subtotal']."</p></td>";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
    $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$total);
    $buf.="</p></td>";
    if ($recurringtotal>0.00)
    {
      $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
      $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$recurringtotal);
      $buf.="</p></td>\n";
    }
    $buf.="</tr>\n";
  }
  // Simple cart variables
	$cartsubtotal=$total;
	$cartrecurringsubtotal=$recurringtotal;
  // End

  // Global tax
  if ($taxrate>0)
  {
    $buf.="<tr>\n";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\" colspan=\"3\">\n";
    $buf.="<p class=\"".$cssstyle."\">".$lang['Tax']." ".$taxrate."%\n";
    $buf.="</p></td>\n";
    $buf.="\n";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
    $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$total*($taxrate/100));
    $buf.="</p></td>\n";
    $carttaxglobal=$total*($taxrate/100);
    if ($recurringtotal>0.00)
    {
      $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
      $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$recurringtotal*($taxrate/100));
      $buf.="</p></td>\n";
      $cartrecurringtaxglobal=$recurringtotal*($taxrate/100);
    }
    $buf.="</TR>\n";
    $total=$total+($total*($taxrate/100));
    $recurringtotal=$recurringtotal+($recurringtotal*($taxrate/100));
  }
  $pretax1total=$total;
  $pretax1rtotal=$recurringtotal;
  // Tax rate 1
  if ($taxrate1>0)
  {
    $buf.="<tr>\n";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\" colspan=\"3\"><p class=\"".$cssstyle."\">\n";
    $buf.=$lang['Tax1']." ".$taxrate1."%\n";
    $buf.="</p></td>\n";
    $buf.="\n";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
    $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$total*($taxrate1/100));
    $buf.="</p></td>\n";
    $carttax1=$total*($taxrate1/100);
    if ($recurringtotal>0.00)
    {
      $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
      $buf.=sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$recurringtotal*($taxrate1/100));
      $buf.="</p></td>\n";
      $cartrecurringtax1=$recurringtotal*($taxrate1/100);
    }
    $buf.="</TR>\n";
    $total=$total+($total*($taxrate1/100));
    $recurringtotal=$recurringtotal+($recurringtotal*($taxrate1/100));
  }
  // Tax rate 2
  if ($taxrate2>0)
  {
    $buf.="<tr>\n";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\" colspan=\"3\">\n";
    $buf.="<p class=\"".$cssstyle."\">".$lang['Tax2']." ".$taxrate2."%\n";
    $buf.="</p></td>\n";
    $buf.="\n";
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">\n";
    if ($taxontax)
    {
	    $buf.=sprintf("$csymbol%01.".$decimalplaces."f$csymbol2",$total*($taxrate2/100));
      $carttax2=$total*($taxrate2/100);
	    $total=$total+($total*($taxrate2/100));
	  }
	  else
	  {
	    $buf.=sprintf("$csymbol%01.".$decimalplaces."f$csymbol2",$pretax1total*($taxrate2/100));
      $carttax2=($pretax1total*($taxrate2/100));
	    $total=$total+($pretax1total*($taxrate2/100));
	  }
    $buf.="</p></td>\n";
    if ($recurringtotal>0.00)
    {
      $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><p class=\"".$cssstyle."\">&nbsp;\n";
	    if ($taxontax)
	    {
				$buf.=sprintf("$csymbol%01.".$decimalplaces."f$csymbol2",$recurringtotal*($taxrate2/100));
        $cartrecurringtax2=$recurringtotal*($taxrate2/100);
		    $recurringtotal=$recurringtotal+($recurringtotal*($taxrate2/100));
 	    }
 	    else
 	    {
				$buf.=sprintf("$csymbol%01.".$decimalplaces."f$csymbol2",$pretax1rtotal*($taxrate2/100));
        $cartrecurringtax2=($pretax1rtotal*($taxrate2/100));
				$recurringtotal=$recurringtotal+($pretax1rtotal*($taxrate2/100));
 	    }
      $buf.="</p></td>\n";
    }
    $buf.="</TR>\n";
  }
  // Totals
  $buf.="<tr>\n";
  $buf.="<td  class=\"".$cssstyle."\" align=\"right\" colspan=\"3\">\n";
  $buf.="<b class=\"".$cssstyle."\">".$lang['Total']."</b></td>";
  $buf.="\n";
  $buf.="<td  class=\"".$cssstyle."\" align=\"right\"><b class=\"".$cssstyle."\">\n";
  $buf.=sprintf("$csymbol%01.".$decimalplaces."f$csymbol2",$total);
  $buf.="</b></td>";
  if ($recurringtotal>0.00)
  {
    $buf.="<td  class=\"".$cssstyle."\" align=\"right\">\n";
    $buf.=sprintf("<B class=\"".$cssstyle."\">$csymbol%01.".$decimalplaces."f$csymbol2",$recurringtotal);
    $buf.="</b></td>\n";
  }
  $buf.="</tr>\n";
	$total=sprintf("%01.".$decimalplaces."f",$total);
	$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);


  // Simple cart variables
	$carttotal=$total;
	$cartrecurringtotal=$recurringtotal;
  $carttax=$carttotal-$cartsubtotal;
  $cartrecurringtax=$cartrecurringtotal-$cartrecurringsubtotal;
	$cartextrafee=$extrafee;
	$carttaxrate=$taxrate;
	$carttaxrate1=$taxrate1;
	$carttaxrate2=$taxrate2;
	$cartsubtotal=sprintf("%01.".$decimalplaces."f",$cartsubtotal);
	$cartrecurringsubtotal=sprintf("%01.".$decimalplaces."f",$cartrecurringsubtotal);
	$carttax=sprintf("%01.".$decimalplaces."f",$carttax);
	$carttaxglobal=sprintf("%01.".$decimalplaces."f",$carttaxglobal);
	$carttax1=sprintf("%01.".$decimalplaces."f",$carttax1);
	$carttax2=sprintf("%01.".$decimalplaces."f",$carttax2);
	$cartrecurringtaxglobal=sprintf("%01.".$decimalplaces."f",$cartrecurringtaxglobal);
	$cartrecurringtax1=sprintf("%01.".$decimalplaces."f",$cartrecurringtax1);
	$cartrecurringtax2=sprintf("%01.".$decimalplaces."f",$cartrecurringtax2);
	$cartrecurringtax=sprintf("%01.".$decimalplaces."f",$cartrecurringtax);
  $cartcoupondiscount=sprintf("%01.".$decimalplaces."f",$coupondiscount);
  $cartcoupondiscountrecurring=sprintf("%01.".$decimalplaces."f",$coupondiscountrecurring);
  $cartcouponcode=$couponcode;
	// $_SESSION['ttl'] = ;;
	// $_SESSION['ipaytotal'] = $to
	$_SESSION['ipaytotal'] = $total;
	// setcookie("ipay_total", $total, time()+ 6000,'/');
	// $_GET['cftotal'] = $_SESSION['ipaytotal'];

  // End
  return($buf);
}

function orderdetailstext(&$total,&$recurringtotal)
{
	global $taxrate,$csymbol,$csymbol2,$decimalplaces,$lang,$extrafee,$numhost,$taxrate1,$taxrate2,$taxontax;
  // Simple cart variables
  global $carttax,$cartsubtotal,$carttotal,$cartrecurringtotal,$cartdomain,$cartdomainopt,$cartdomaintime,$cartdomainprice;
  global $carthosting,$carthostingopt,$carthostinrecurr,$carthostingprice,$cartitemtotal,$cartitemrecurringtotal;
  global $carttaxrate,$carttaxrate1,$carttaxrate2,$cartextrafee,$cartrecurringsubtotal,$cartrecurringtax;
  global $carttaxglobal,$cartrecurringtaxglobal,$carttax1,$cartrecurringtax1,$carttax2,$cartrecurringtax2,$carthostingsetup;
  global $couponcode;
  $carttax=0.00;
  $carttaxglobal=0.00;
  $carttax1=0.00;
  $carttax2=0.00;
  $cartrecurringtaxglobal=0.00;
  $cartrecurringtax1=0.00;
  $cartrecurringtax2=0.00;
  $cartcoupondiscount=0.00;
  $cartcoupondiscountrecurring=0.00;
  $cartcouponcode="";
  $cartsubtotal=0.00;
  $cartrecurringsubtotal=0.00;
  $carttotal=0.00;
	$cartrecurringtotal=0.00;
  $cartdomain="";
  $cartdomainopt="";
  $cartdomainprice="";
  $carthosting="";
  $carthostingrecurr="";
  $carthostingprice="";
  $carthostingsetup="";
  $cartitemtotal="";
  $cartitemrecurringtotal="";
	// End
	$total=0.00;
	$recurringtotal=0.00;
	$orderdetails="";
	for ($k=1;$k<=$_SESSION['numberofitems'];$k++)
	{
	  if ($_SESSION['removed'.$k]==False)
	  {
      // Simple cart variables
      if ($cartdomain!="")
        $cartdomain.=",";
      if ($cartdomainopt!="")
        $cartdomainopt.=",";
      if ($cartdomaintime!="")
        $cartdomaintime.=",";
      if ($cartdomainprice!="")
        $cartdomainprice.=",";
      if ($carthosting!="")
        $carthosting.=",";
      if ($carthostingrecurr!="")
        $carthostingrecurr.=",";
      if ($carthostingprice!="")
        $carthostingprice.=",";
      if ($carthostingsetup!="")
        $carthostingsetup.=",";
      if ($cartitemtotal!="")
        $cartitemtotal.=",";
      if ($cartitemrecurringtotal!="")
        $cartitemrecurringtotal.=",";
      $cartdomain.=$_SESSION['domain'.$k];
      $cartdomainopt.=$_SESSION['regtype'.$k];
      $cartdomaintime.=$_SESSION['regperiod'.$k];
      $cartdomainprice.=$_SESSION['regprice'.$k];
      $carthosting.=$_SESSION['hostdesc'.$k];
      $carthostingrecurr.=$_SESSION['hostrecurr'.$k];
      $carthostingprice.=$_SESSION['hostprice'.$k];
      $carthostingsetup.=$_SESSION['hostprice'.$k];
      // End
	    $subtotal=0;
	    if ($_SESSION['regtype'.$k]=="H")
		    $orderdetails.=$lang['HostingOnly']." ".$_SESSION['domain'.$k];
	    if ($_SESSION['regtype'.$k]=="R")
	    {
		    $orderdetails.=$lang['Register']." ".$_SESSION['domain'.$k];
		    $orderdetails.=" ".$_SESSION['regperiod'.$k]." ".$lang['Year']." ".$csymbol.$_SESSION['regprice'.$k].$csymbol2;
	    }
	    if ($_SESSION['regtype'.$k]=="T")
	    {
		    $orderdetails.=$lang['Transfer']." ".$_SESSION['domain'.$k];
		    $orderdetails.=" ".$_SESSION['regperiod'.$k]." ".$lang['Year']." ".$csymbol.$_SESSION['regprice'.$k].$csymbol2;
	    }
	    if ($_SESSION['regtype'.$k]=="N")
	    {
		    $orderdetails.=$lang['Renew']." ".$_SESSION['domain'.$k];
		    $orderdetails.=" ".$_SESSION['regperiod'.$k]." ".$lang['Year']." ".$csymbol.$_SESSION['regprice'.$k].$csymbol2;
	    }
	    $subtotal=$subtotal+$_SESSION['regprice'.$k];
	    // If hosting options listed and user selected one with value greater than 0 then show it
	    if (($numhost>0) && ($_SESSION['hostdesc'.$k]!=""))
	    {
		    if ($_SESSION['hostsetup'.$k]>0)
			    $orderdetails.=", ".$_SESSION['hostdesc'.$k]." ".$csymbol.$_SESSION['hostprice'.$k].$csymbol2." (".$lang['Setup']." ".$csymbol.$_SESSION['hostsetup'.$k].$csymbol2.")";
		    if ($_SESSION['hostsetup'.$k]==0)
			    $orderdetails.=", ".$_SESSION['hostdesc'.$k]." ".$csymbol.$_SESSION['hostprice'.$k].$csymbol2;
		    if ($_SESSION['hostrecurr'.$k]!="S")
			    $recurringtotal=$recurringtotal+$_SESSION['hostprice'.$k];
		    $subtotal=$subtotal+$_SESSION['hostsetup'.$k]+$_SESSION['hostprice'.$k];
	    }
      $cartitemtotal.=sprintf("%01.".$decimalplaces."f",$subtotal);
	    $subtotal=sprintf("%01.".$decimalplaces."f",$subtotal);
	    $orderdetails.=". ".$lang['Subtotal']." ".$csymbol.$subtotal.$csymbol2."\n";
      if ($recurringtotal>0.00)
      {
        if ($hostrecurr!="S")
          $cartitemrecurringtotal.=sprintf("%01.".$decimalplaces."f",$hostprice);
        else
          $cartitemrecurringtotal.="0.00";
      }
      $total=$total+$subtotal;
	  }
	}
	if ($extrafee>0)
	{
    $subtotal=sprintf("%01.".$decimalplaces."f",$extrafee);
    $orderdetails.=$lang['ExtraFee']." ".$csymbol.$subtotal.$csymbol2."\n";
		$total=$total+$extrafee;
	}
  // See if any coupon codes apply to order
  $coupondiscount="0.00";
  $coupondiscountrecurring="0.00";
  cwc_ValidateCoupon($couponcode,$total,$recurringtotal,$coupondiscount,$coupondiscountrecurring);
  if (($coupondiscount>0) || ($coupondiscountrecurring>0))
  {
    $orderdetails.=$lang['Discount']." ".sprintf("$csymbol%01.".$decimalplaces."f".$csymbol2,$coupondiscount)."\n";
    $total=$total-$coupondiscount;
    $recurringtotal=$recurringtotal-$coupondiscountrecurring;
  }
  //End of coupon code

	$total=sprintf("%01.".$decimalplaces."f",$total);
  // Simple cart variables
	$cartsubtotal=$total;
	$cartrecurringsubtotal=$recurringtotal;
  // End
	if ($taxrate>0)
	{
		$orderdetails.=$lang['Tax']." ".$taxrate."%\n";
    $carttaxglobal=$total*($taxrate/100);
		$total=$total+($total*$taxrate/100);
		if ($recurringtotal>0)
		{
      $cartrecurringtaxglobal=$recurringtotal*($taxrate/100);
			$recurringtotal=$recurringtotal+($recurringtotal*$taxrate/100);
		}
	}
	$pretax1total=$total;
	$pretax1rtotal=$recurringtotal;
	if ($taxrate1>0)
	{
		$orderdetails.=$lang['Tax1']." ".$taxrate1."%\n";
		$total=$total+($total*$taxrate1/100);
    $carttax1=$total*($taxrate1/100);
		if ($recurringtotal>0)
		{
			$recurringtotal=$recurringtotal+($recurringtotal*$taxrate1/100);
      $cartrecurringtax1=$recurringtotal*($taxrate1/100);
		}
	}
	if ($taxrate2>0)
	{
		$orderdetails.=$lang['Tax2']." ".$taxrate2."%\n";
    if ($taxontax)
    {
      $carttax2=$total*($taxrate2/100);
	    $total=$total+($total*($taxrate2/100));
    }
	  else
	  {
      $carttax2=($pretax1total*($taxrate2/100));
	    $total=$total+($pretax1total*($taxrate2/100));
	  }
		if ($recurringtotal>0)
    {
	    if ($taxontax)
	    {
        $cartrecurringtax2=$recurringtotal*($taxrate2/100);
	      $recurringtotal=$recurringtotal+($recurringtotal*($taxrate2/100));
	    }
	    else
	    {
        $cartrecurringtax2=($pretax1rtotal*($taxrate2/100));
	      $recurringtotal=$recurringtotal+($pretax1rtotal*($taxrate2/100));
	    }
		}
	}
	$total=sprintf("%01.".$decimalplaces."f",$total);
	if ($recurringtotal>0)
		$orderdetails.=$lang['ToPayNow']." ".$csymbol.$total.$csymbol2." ".$lang['MonthlyCharge']." ".$csymbol.sprintf("%01.".$decimalplaces."f",$recurringtotal).$csymbol2."\n";
	else
		$orderdetails.=$lang['Total']." ".$csymbol.$total.$csymbol2."\n";
	$total=sprintf("%01.".$decimalplaces."f",$total);
	$recurringtotal=sprintf("%01.".$decimalplaces."f",$recurringtotal);
  // Simple cart variables
	$carttotal=$total;
	$cartrecurringtotal=$recurringtotal;
  $carttax=$carttotal-$cartsubtotal;
  $cartrecurringtax=$cartrecurringtotal-$cartrecurringsubtotal;
	$cartextrafee=$extrafee;
	$carttaxrate=$taxrate;
	$carttaxrate1=$taxrate1;
	$carttaxrate2=$taxrate2;
	$cartsubtotal=sprintf("%01.".$decimalplaces."f",$cartsubtotal);
	$cartrecurringsubtotal=sprintf("%01.".$decimalplaces."f",$cartrecurringsubtotal);
	$carttax=sprintf("%01.".$decimalplaces."f",$carttax);
	$carttaxglobal=sprintf("%01.".$decimalplaces."f",$carttaxglobal);
	$carttax1=sprintf("%01.".$decimalplaces."f",$carttax1);
	$carttax2=sprintf("%01.".$decimalplaces."f",$carttax2);
	$cartrecurringtaxglobal=sprintf("%01.".$decimalplaces."f",$cartrecurringtaxglobal);
	$cartrecurringtax1=sprintf("%01.".$decimalplaces."f",$cartrecurringtax1);
	$cartrecurringtax2=sprintf("%01.".$decimalplaces."f",$cartrecurringtax2);
	$cartrecurringtax=sprintf("%01.".$decimalplaces."f",$cartrecurringtax);
  $cartcoupondiscount=sprintf("%01.".$decimalplaces."f",$coupondiscount);
  $cartcoupondiscountrecurring=sprintf("%01.".$decimalplaces."f",$coupondiscountrecurring);
  $cartcouponcode=$couponcode;
  // End
  return($orderdetails);
}
// This is a function that can be used/modifed to add order details to a myslq table
// To enable it copy the following setting lines to cwcconf.php and uncomment them.
// You must ensure in your conatct form that you use the following field names for the address
// name, orgn, str1, str2, city, state, zip, country, tel, fax, email
 // $UseMySQL=true;
// $DbHost=""; // MySQL host
// $DbUser="";          // MySQL username
// $DbPassword="!";      // MySQL password
// $DbName="";          // MySQL database name
// $DbOrders="";
// $DbRegister=""   // MySQL table name

function StoreOrderMysql()
{
	global $DbHost,$DbUser,$DbPassword,$DbName,$DbOrders,$DbRegister;
  global $ordnum,$cffname, $cflname, $cforg ,$cfstr1,$cfcity,$cfstate,$cfzip,$cfcountry,$cftel,$cfcurr,$cfemail,$orderdate,$cartdomain,$cartdomainopt,$cartdomaintime,$carthosting,$cartsubtotal,$carttotal,$carttax;
  if (function_exists("mysqli_connect"))
  {
    $mysql_link=mysqli_connect($DbHost,$DbUser,$DbPassword);
    if ($mysql_link==0)
    {
      print("Can't connect to MySQL server: ". mysqli_connect_error());
      exit();
    }
    $db=mysqli_select_db($mysql_link,$DbName);
    if ($db==false)
    {
      print("Can't open database");
      mysqli_close($mysql_link);
      exit;
    }
    $orderdate=date("Y-m-d h:i:s");
		$expirydate = date("Y-m-d h:i:sa", strtotime('+1 years'));

    // $Query="INSERT INTO ".$DbTableName." (orderno,name,orgn,str1,str2,city,state,zip,country,tel,fax,email,orderdate,domain,domainoption,domaintime,hosting,subtotal,total,tax,ip) VALUES('".$ordnum."','".$cfname."','".$cforgn."','".$cfstr1."','".$cfstr2."','".$cfcity."','".$cfstate."','".$cfzip."','".$cfcountry."','".$cftel."','".$cffax."','".$cfemail."','".$orderdate."','".$cartdomain."','".$cartdomainopt."','".$cartdomaintime."','".$carthosting."','".$cartsubtotal."','".$carttotal."','".$carttax."','".trim(strtok($_SERVER['REMOTE_ADDR'],","))."')";

		$Query = "INSERT INTO ".$DbRegister." (fname, lname, enail, password, phone, address, city, country, organisation) VALUES ('".$cffname."','".$cflname."','".$cfemail."','".$cfpassword."','".$cftel."','".$cfstr1."','".$cfcity."','".$cfcountry."','".$cforg == "" ? NULL : $cforg."')";
		//
		// $Query .= "INSERT INTO ".$DbOrders." (orderId, UserID, NameServers, Amount, currency, OrderDate, ExpiryDate)
		// VALUES ('".$ordernum."','".$userId."','".$cartdomain."',''".$carttotal."','".$cfcurr."','".$orderdate."', '".$expirydate."')";

    // $mysql_result=mysqli_multi_query($mysql_link,$Query);

		$mysql_result=mysqli_query($mysql_link,$Query);

    mysqli_close($mysql_link);
  }
  else
  {
		$mysql_link=mysql_connect($DbHost,$DbUser,$DbPassword);
    if ($mysql_link==0)
    {
      print("Can't connect to MySQL server");
      exit;
    }
    $db=mysql_select_db($DbName,$mysql_link);
    if ($db==False)
    {
      print("Can't open database");
      mysql_close($mysql_link);
      exit;
    }
		$orderdate=date("Y-m-d h:i:s");
		$expirydate = date("Y-m-d h:i:sa", strtotime('+1 years'));

		$Query = "INSERT INTO ".$DbRegister." (fname, lname, enail, password, phone, address, city, country, organisation) VALUES ('".$cffname."','".$cflname."','".$cfemail."','".$cfpassword."','".$cftel."','".$cfstr1."','".$cfcity."','".$cfcountry."','".$cforg == "" ? NULL : $cforg."')";

		// $Query .= "INSERT INTO ".$DbOrders." (orderId, UserID, NameServers, Amount, currency, OrderDate, ExpiryDate)
		// VALUES ('".$ordernum."','".$userId."','".$cartdomain."',''".$carttotal."','".$cfcurr."','".$orderdate."', '".$expirydate."')";
		//
    // $mysql_result=mysqli_multi_query($Query,$mysql_link);

		$mysql_result=mysqli_query($mysql_link,$Query);
    mysql_close($mysql_link);
  }
}

function getdomainprices($dataarray,$dataarrayspecial,$ext,$hostid)
{
  for ($k=0;$k<count($dataarrayspecial);$k++)
  {
    $arrayparts=explode(",",$dataarrayspecial[$k]);
    if ($arrayparts[0]==$ext)
    {
      // matching domain extensions check for hostid
      $planparts=explode(":",$arrayparts[3]);
      for ($j=0;$j<count($planparts);$j++)
      {
        if (($planparts[$j]==$hostid) || (($planparts[$j]=="*") && ($hostid!=-1)))
        {
          // match so return ext,periods,prices,otherprice string
          $return=$arrayparts[0].",".$arrayparts[1].",".$arrayparts[2];
          if ($arrayparts[4]!="")
            $return.=",".$arrayparts[4];
          return($return);
        }
      }
    }
  }
  for ($k=0;$k<count($dataarray);$k++)
  {
    $arrayparts=explode(",",$dataarray[$k]);
    if ($arrayparts[0]==$ext)
    {
      // matching domain extension so return ext,periods,prices,otherprice string
      $return=$arrayparts[0].",".$arrayparts[1].",".$arrayparts[2];
      if ($arrayparts[3]!="")
        $return.=",".$arrayparts[3];
      return($return);
    }
  }
  return("");
}

function cwc_ValidateCoupon($couponcode,$total,$recurringtotal,&$coupondiscount,&$coupondiscountrecurring)
{
  global $coupon;
  $coupondiscount="0.00";
  $coupondiscountrecurring="0.00";
  $couponcode=strtolower(trim($couponcode));
  if ($coupon[$couponcode]!="")
  {
    $coupondata=explode(",",$coupon[$couponcode]);
    // Handle discount on initial payment
    if (isset($coupondata[0]))
    {
      $disval=trim($coupondata[0]);
      if ($disval[strlen($disval)-1]=="%")
      {
        $disval=substr($disval,0,strlen($disval)-1);
        $coupondiscount=$total*($disval/100);
      }
      else
        $coupondiscount=$disval;
      if (isset($coupondata[1]))
      {
        if ($total<trim($coupondata[1]))
          $coupondiscount=0;
      }
    }
    // Handle discount on recurring payment
    if (isset($coupondata[2]))
    {
      $disval=trim($coupondata[2]);
      if ($disval[strlen($disval)-1]=="%")
      {
        $disval=substr($disval,0,strlen($disval)-1);
        $coupondiscountrecurring=$recurringtotal*($disval/100);
      }
      else
        $coupondiscountrecurring=$disval;
      if (isset($coupondata[3]))
      {
        if ($recurringtotal<trim($coupondata[3]))
          $coupondiscountrecurring=0;
      }
    }
    // Check totals not going to be negative
    if ($coupondiscount>$total)
      $coupondiscount=0;
    if ($coupondiscountrecurring>$recurringtotal)
      $coupondiscountrecurring=0;
    // See if coupon expired
    if (isset($coupondata[4]))
    {
      $disval=trim($coupondata[4]);
      if ($disval!="")
      {
        if (mktime(23,59,59,substr($disval,2,2),substr($disval,0,2),substr($disval,4,4))<time())
        {
          $coupondiscount=0;
          $coupondiscountrecurring=0;
          return(2);
        }
      }
    }
    return(0);
  }
  return(1);   // Coupon code not recognised
}

?>
