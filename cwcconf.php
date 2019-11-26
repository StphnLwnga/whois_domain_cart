<?php
// cWhois Domain Cart V4.4
// Copyright 2003 to 2018 Vibralogix
// You are licensed to use this on one domain only
// For further information email support@vibralogix.com

// Add list of extensions you support for registering. See domains.txt for full list of supported domains.
// domain extension,list of years allowed seperated by :, list of prices seperated by :
$register[]=".co.ke,1,0";
$register[]=".ke,1,0";
$register[]=".com,1,0";
$register[]=".org,1,0";
$register[]=".net,1,0";
$register[]=".co.ug,1,0";
$register[]=".info,1,0";
$register[]=".biz,1,0";
$register[]=".me,1,0";


// Optional list of extensions you support for transfers.
// domain extension,list of years allowed seperated by :, list of prices seperated by :
// $transfer[]=".com,1:2:5:10,14.99:28.99:64.99:119.99";
// $transfer[]=".net,1:2:5:10,14.99:28.99:64.99:119.99";
// $transfer[]=".org,1:2:5:10,14.99:28.99:64.99:119.99";
// $transfer[]=".co.uk,2,17.99";
// $transfer[]=".name,1:2:10,14.99:28.99:119.99";
// $transfer[]=".info,2:5:10,24.99:64.99:119.99";
// $transfer[]=".biz,2:5:10,24.99:64.99:119.99";
// $transfer[]=".cc,1:2:5,39.99:74.99:179.99";
// $transfer[]=".tv,1:2:5,39.99:74.99:179.99";

// Optional list of extensions you support for renewals.
// domain extension,list of years allowed seperated by :, list of prices seperated by :
$renew[]=".co.ke,1,0";
$renew[]=".ke,1,0";
$renew[]=".com,1,0";
$renew[]=".org,1,0";
$renew[]=".net,1,0";
$renew[]=".co.ug,1,0";
$renew[]=".info,1,0";
$renew[]=".biz,1,0";
$renew[]=".me,1,0";

// Optional list of hosting packages and prices
// description,setup,price,S (single charge) or R (monthly recurring charge)
// $host[]="PRO150 1 year,0,109,S";
// $host[]="PRO150 monthly,0,9.99,R";
// $host[]="PRO350 1 year,0,219,S";
// $host[]="PRO350 monthly,0,19.99,R";
// $host[]="PRO700 1 year,0,329,S";
// $host[]="PRO700 monthly,0,29.99,R";

$host[]="Baby Slash (512MB + A2 hosting),0.00,3500,S";
$host[]="Savanna (1GB  A2 hosting),0.00,5500,S";
$host[]="Rift Valley (5GB + A2 hosting),0.00,7000,S";
$host[]="Longonot (10GB + A2 hosting),0.00,8500,S";
$host[]="Elgon (20GB + A2 hosting),0.00,10500,S";
$host[]="Kenya (40GB + A2 hosting),0.00,11000,S";
$host[]="Kilimanjaro (50GB + A2 hosting),0.00,11500,S";
$host[]="Everest (75GB + A2 hosting),0.00,18000,S";
$host[]="Indian Ocean (1GB + ICDSoft hosting),0.00,6900,S";
$host[]="Atlantic Ocean (10GB + ICDSoft hosting),0.00,9000,S";
$host[]="Pacific Ocean (15GB + ICDSoft hosting),0.00,13500,S";
$host[]="Southern Ocean (50GB + ICDSoft hosting),0.00,32000,S";
$host[]="Global Boss (200 + ICDSoft hosting),0.00,55000,S";

// Optional agreements for domain register, transfer and hosting. Set to "" if not required
$overallagree="";
$registeragree="";
$transferagree="";
$renewagree="";
$hostagree="";

// Other settings

include "english.php";													 // Set shopping cart language
$vendoremail="info@slashdotlabs.com";               // Your email to receive orders
$vendoremail2="accounts@ipayafrica.com";                                // Optional second admin email address
$vendorcompany="Slash Dot Labs Ltd.";
$columns=3;                                      // Number of columns in domain extensions table
$csymbol="KES "; 		                               // Symbol to use for currency. Displayed before amount.
$csymbol2="";                                    // Currency symbol to display after amount (leave as "" if not required)
$decimalplaces=2;																 // Most currencies have 2 but some like Yen have 0.
$backcolor="grey";                            // Background color
$cwhoismode=0;                   								 // Set to 1 to use simple hosting mode
// $checkoutpage="democheck.php";                // Set if you wish to use a different template for the order details page
$AllowNoHosting=false;														 // Set to false to force hosting plan to be selected
$AllowHostingOnly=false;												 	 // Set to false to force domain purchase or transfer
$AssumeAll=true;																 // When true if no check boxes ticked and no extension typed then check all
																								 // When false first extension (check box) is assumed
$SimpleHostingOnly=false;                        // When true hosting only.(Removes radio boxes from simple hosting mode)
$SimpleRegisterOnly=false;                       // When true remove register option from simple hosting mode.

$taxrate=0;																			 // Tax percentage to add to total. Set to 0 to ignore. Do not include % character
$taxratefield1="";      				                 // The form field name used to decide level 1 taxation
$taxratefield2="";              			           // The form field name used to decide level 2 taxation
$taxontax=true;                                  // Set to true if level 2 taxation taxes level 1 (e.g. Canada). Otherwise set to false
$tax1[]="";                           				   // Tax level 1 match and tax rate. Add a line for each
$tax2[]="";                            					 // Tax level 2 match and tax rate. Add a line for each

// Coupon codes
$coupon['code1']="10%,10,10%,10,";
$coupon['code2']="5,9,3,9,01082011";

$HTMLEmail="Y";                                  // Set to Y for HTML format emails. This is recommended.

$spacetohyphen=true;														 // if set to true converts spaces to hyphens in domain names. false disables.
$hidecheckboxes=false;													 // Set to true to hide checkboxes. This will check all domain extensions without user selecting them.
$NarrowCart=false;															 // Set to true to make the cart narrower in normal cart mode
$DefaultBuy=1;                                   // 1 checks buy checkboxes. 0 leaves them unchecked. 2 check only those for available domains
$dropdownsearch=false;                             // Set to true for drop down menu instead of checkboxes
$allowlookup=true;                               // Set to false to disable the whois lookup link
$CheckoutTuring=false;                           // Set to true to require turing on checkout. false disables this.
$LookupTuring=false;                             // Set to true to require turing for full lookup. false disables this.
$payprocess="iPay";            			 			   	 // Set to "2CO", "Paypal",  "WpFuturepay", "Paysystems", "Stormpay", "Nochex", "Authorize",
																								 // "network1", "Centipaid", "Moneybookers", "Bluepaid", "Caixagalicia", "cdgcommerce", "iKobo"
																								 // "InternetSecure", "ePN", "MetaCharge", "okobank", "eGold"
																								 // "payson," "picpay", "SecPay", "Mals", "Linkpoint", "Paymate", "GoogleCheckout", "Pagseguro"
																								 // "Ideal" "AlertPay" "Swreg" "vpcash" "GlobalDigitalPay" "Amazon" "MoIP" "MollieIdeal" or "manual" for manual processing.
																								 // For multiple payment processors seperate by commas.
$payprocessnames="iPay";														 // For multiple payment processors set titles to be used in drop down menu.


// iPay Africa Payment Gateway settings
$live       = '0';
$vid        = 'demo';
$inv 				= substr(md5(time()), 0 , 6);
$inv 				= strtoupper($inv);
$oid				=$inv;
$p1         = '';
$p2         = '';
$p3         = '';
$p4         = '';
$autopay 		= '1';
$crl        = '0';
$cst        = '1';
$cbk        = 'https://beta.slashdotlabsprojects.com/success';
$hsh        = 'demoCHANGED';
$ipayrate   = 1.0;


// // 2checkout.com settings
// $vendorid2co="12345";                            // 2CO vendor ID
// // Due to the limitations of 2CO's recurring billing system you must
// // set up product ID's for all combinations of setup and monthly charge
// // on 2CO's admin panel and then enter them here.
// // $recprodid2co[]="product id,setup cost,monthly cost";
// $recprodid2co[]="1,0.00,9.99";
// $recprodid2co[]="2,14.99,9.99";
// $recprodid2co[]="3,0,19.99";
// $recprodid2co[]="4,14.99,19.99";

// // paypal.com settings
// $paypalemail="paypal@yoursite.com";              // Paypal payment email
// $paypalcurrency="USD";                           // Currency USD, GBP, EUR, CAD or JPY (also set $csymbol appropriately)
// $paypalreturn="http://www.yoursite.com/ok.htm";  // full URL to return to after successful transaction
// $paypalcancel="http://www.yoursite.com/can.htm"; // full URL to return to if user cancels transaction
// $paypaldesc="Web Services";				               // Description for item purchased

// // Revecom / Paysystems settings
// $paysysid=3800;												    			 // Paysystems account ID
// $paysysreturn="http://www.yoursite.com/ok.htm";  // full URL to return to after successful transaction
// $paysyscancel="http://www.yoursite.com/can.htm"; // full URL to return to if user cancels transaction
// $paysysdesc="Web Services";     				         // Description for item purchased
// $paysyscycle="30";                               // Payment cycle in days (set to 30 or 31 normally)
// $paysystotalperiod=12;                           // Total number of charges to make
//
// // Worldpay settings
// $wpinstid="123456";								               // instId value
// $wpcartid="15";															  	 // cartId value
// $wpcurrency="GBP";														   // currency value
// $wpdescid="Item Description";                    // Item Description
// $wptestmode="0";                                 // 0=Live Mode 100=Test Mode
//
// // Worldpay Futurepay settings
// $wpfpinstid="62346";								             // instId value
// $wpfpcartid="15";																 // cartId value
// $wpfpcurrency="GBP";														 // currency value
// $wpfpdesc="Web Services";                        // Description for purchase
// $wpfptest="0";																	 // testMode
// $wpfpoption="1";																 // option value
//
// // Stormpay settings
// $stormemail="stormpay@yoursite.com";				 		 // Stormpay email
// $stormreturn="http://www.yoursite.com/ok.htm"; 	 // full URL to return to after successful transaction
// $stormcancel="http://www.yoursite.com/can.htm";	 // full URL to return to if user cancels transaction
// $stormdesc="Web Services";										 	 // Description for item purchased
// $stormcycle="30";																 // Payment cycle in days (set to 30 or 31 normally)
//
// // Nochex settings
// $nochexemail="nochex@yoursite.com";							 // Nochex email
// $nochexdesc="Web Services";											 // Description for item purchased
// $nochexreturn="http://www.yoursite.com/ok.htm";  // full URL to return to after transaction
//
// //  Authorize.net settings
// $authloginid=""; 											 				   // Authorize.net login ID
// $authdesc="Web Services";								 				 // Description of services ordered
// $authcurrency="USD";											 			 // Currency
// $authtxnkey="";																	 // Secret transaction key
//
// //  Network1 settings
// $net1loginid="";														   	 // Network 1 login ID
// $net1desc="Web Services";								 				 // Description of services ordered
//
// //  Centipaid settings
// $centilogin="AEF001";						 						   	 // Centipaid login ID
// $centidesc="Web Services";							 				 // Description of services ordered
//
// // Moneybookers settings
// $monbookemail="moneybookers@yoursite.com"; 			 // Moneybookers email
// $monbookreturn="http://www.yoursite.com/ok.htm"; // full URL to return to after successful transaction
// $monbookcancel="http://www.yoursite.com/can.htm";// full URL to return to if user cancels transaction
// $monbookdesc="Web Services";										 // Description for item purchased
// $monbookcurrency="USD";													 // Currency code
// $monbookcycle="31";															 // Payment cycle in days (set to 30 or 31 normally)
// $monbooktotalperiod="12";												 // Total number of charges to make
//
// // Bluepaid settings
// $blueidboutique="*******";											 // Bluepaid boutique id
// $blueidclient="***********";										 // client id
// $bluedevise="EUR";															 // Currency to use
// $bluelangue="FR";																 // Form language
//
// // Caixagalicia settings
// $caixagcomercio="comercio";									 		 // comercio
// $caixagvuelta="http://www.yoursite.com/gracias.html";	 // vuelta
// $caixagmoneda="EUR";														 // moneda
//
// // cdgcommerce settings
// $cdgvendorid="12345";														 // vendor_id
// $cdghome="http://www.yoursite.com";           	 // Your site home page (home_page)
// $cdgdesc="Web Services";												 // Description of item purchased
// $cdgreturn="http://www.yoursite.com";				 		 // Page to return to
// $cdgmername="MERCHANT INC";									 		 // Merchant Name (mername)
// $cdgrecipe="monthlybusiness";                    // Name of the predefined monthly recurring recipe
//
// // iKobo settings
// $ikoboid="JA56454GB";													 	 // Your iKobo account number
// $ikobodesc="Web+Services";											 // Description of item purchased
//
// // Internet Secure settings
// $intsecid="1234";													 	     // Your Internet Secure account number
// $intsecdesc="Web Services";											 // Description of service purchased
// $intsecitem="WEB";															 // Id for service purchased
// $intsecflags="{GST}{PST}{HST}";									 // Flags for tax etc. See Internet Secure documentation
// $intsecreturn="http://www.yoursite.com";				 // Page to return to
//
// // ePN settings
// $epnid="04971";																	 // ePN account number
// $epnreturn="http://www.yoursite.com";						 // URL to go to after payment
// $epndecline="http://www.yoursite.com";					 // URL to go to if payment declined
// $epnrecurr="1";																	 // Recurrng method id to use. Must allow recurring amount overide.
//
// // MetaCharge settings
// $metaid="123456";																 // MetaCharge account id
// $metacurrency="GBP";														 // Currency code to use
// $metadesc="Web services";      									 // Description of service purchased
//
// // eGold settings
// $egoldid="123456";															 // eGold account id
// $egoldname="Your Company";											 // Your company name
// $egoldunits="1";																 // eGold units. 1 for USD, 44 for GBP, 85 for Euro. See egold.com for further details
// $egoldmetalid="1";															 // eGold metal id. 1 for gold, 2 for silver, 3 for platinum. See egold.com for further details
// $egoldmemo="Web Services";											 // Memo displayed on checkout form
// $egoldreturn="http://www.yoursite.com/ok.htm";	 // Page to return to after payment
// $egoldcancel="http://www.yoursite.com/can.htm";	 // Page to return to if payment cancelled
//
// // OKO Bank settings
// $okoid="ACCOUNT"; 															 // OKO Bank invoicer id
// $okodesc="Web Services";          						   // Descripion of service purchased
// $okoreturn="http://www.yoursite.com";            // Page to return
// $okocancel="http://www.yoursite.com";            // Page to return to if cancelled
// $okocurrency="EUR";                              // Currency normally EUR or FIM
// $okokey="CHECKKEY";														   // Check information key from OKO Bank
//
// // payson.se settings
// $paysonemail="payson@yoursite.com";              // payson.se seller email address
// $paysonoption="&Sp=1";                           // Can be "&Sp=1" or "&Gr=1"
//                                                  // and/or "&BuyerPaysFee=1"
//                                                  // If 2 are used just put together "&BuyerPaysFee=1&GuaranteeRequired=1"
//
// // Picpay settings
// $picpaymember="fastdime";                        // Picpay member name
// $picpaydesc="Web services";                      // Description of service purchased
// $picpayreturn="http://www.yoursite.com";         // Page to return
// $picpaycancel="http://www.yoursite.com";         // Page to return to if cancelled
//
// // setcom.co.za settings
// $setcomid="12345";                               // Setcom account if
// $setcomcurrency="USD";                           // setcom currency (e.g. USD)
// $setcomdesc="Web Services";                      // Description of service provided
// $setcomsku="ID1";                                // Item code for service provided
//
// // SecPay settings
// $secpaymerchant="secpay";                        // Secpay merchant id
// $secpayreturn="http://www.yoursite.com/";        // Page to return to (callback)
// $secpaycurrency="GBP";                           // Currency code
// $secpayremotepass="secpay";                      // Recommended to use this setting (ask Secpay to enable it)
// $secpaytest="true";                              // Set to true for test mode
// $secpaytemplate="";                              // Optional template to use
//
// // Mals eCommerce settings
// $malsuserid="12345";														 // Mals-e account id
// $malsurl="http://ww8.aitsafe.com/";					     // Mals URL allocated to you
// $malsdesc="Web Services";                        // Description of service provided
//
// // Linkpoint settings
// $lpstorename="12345";														 // Linkpoint storename
// $lpreturn="http://www.yoursite.com";					   // Page to return to
// $lpcancel="Web Services";                        // Page to return to if cancelled
//
// // Paymate.com.au settings
// $paymateid="";                                   // Paymate account id
// $paymatedesc="Web Services";                     // Description of service provided
// $paymatecurrency="USD";                          // Currency code
//
// // Google checkout settings
// $gcmerchantid="";                                 // Google Checkout merchant id
// $gcdesc="Web services";                           // Description of services provided
// $gccurrency="GBP";                                // Account currency code
//
// // Pagseguro settings
// $pgemailcobranca="";                               // Email
// $pgtipo="CP";                                      // Tipo
// $pgmoeda="BRL";                                    // Currency code
// $pgdesc="Web services";                            // Description of services provided
//
// // Ideal Light settings
// $idealid="12345678";                               // Merchant id
// $ideallang="nl";                                   // Language
// $idealcurrency="EUR";                              // Currency code
// $idealdesc="Hostingpakket";                        // Description of services sold
// $idealitem="123456";                               // Item number
// $idealvalid="2008-12-01T12:00:00:0000Z";           // Valid until
//
// // Alertpay settings
// $alertpayid="alertpay@yoursite.com";               // Alertpay account email address
// $alertpayname="Web services";                      // Item name
// $alertpaycode="001";                               // Item code to use
// $alertpaydesc="Web services";                       // Item description
// $alertpaycurrency="USD";                           // Currency code (e.g. USD)
// $alertpayreturn="http://www.yoursite.com/order.php";      // return URL
// $alertpaycancel="http://www.yoursite.com/cancel.html";     // Cancel URL
//
// // Swreg settings
// $swregid="123456";																// Swreg account id
// $swregproduct="123456-1";													// Product id
//
// // Virtualpaycash settings
// $vpc_merchant="123456";                           // Virtualpaycash merchant account id
// $vpc_currency="USD";                              // Currency code to use
// $vpc_item="Web Services";                         // Item Description
// $vpc_merchantSecurityWord="secret";               // Your Virtualpaycash secret word
// $vpc_return_url="http://www.yoursite.com/success.php";  // Return URL
// $vpc_cancel_url="http://www.yoursite.com/cancel.php";   // Cancel URL
// $vpc_notify_url="http://www.yoursite.com/cancel.php";   // Notify URL
//
// // Digital Global Pay settings
// $gdp_member="G012345";                            // Account number
// $gdp_storeid="123";                                // Store ID
// $gdp_desc="Web Services";                         // Item description
// $gdp_currency="USD";                              // Currency code
// $gdp_success="http://yoursite.com/success.php";   // Return URL
// $gdp_cancel="http://yoursite.com/cancel.php";     // Cancel URL
//
// // Amazon Settings
// $amazon_accesskey="";                              // Your amazon access key
// $amazon_currency="USD";                            // Currency code
// $amazon_desc="Web Services";                       // Item description
// $amazon_payid="";                                  // Amazon payment account id
// $amazon_success="http://yoursite.com/success.php"; // Return URL
// $amazon_cancel="http://yoursite.com/cancel.php";   // Cancel URL
//
// // Mollie Ideal Settings
// $mollieideal_api="test_EDA18cqZvHsvd3NsNhrDwmiHjdKTHY";  // Mollie API
// $mollieideal_desc="Web Services";                     // Description of item purchased
// $mollieideal_returnurl="http://www.yoursite.com/return.php";    // return_url
// $mollieideal_method="";
//
// // PayuLatam Settings
// $payulmerchantid="123456";
// $payulaccountid="123456";
// $payulapikey="342789w47t17892634";
// $payulaccountdesc="";
// $payulcurrency="COP";
// $payullng="es";
//
// // Access Pay Settings
// $accesspaymercid="00037";
// $accesspaycurrcode="566";
// $accesspaydesc="Hosting";
//
// manual payment settings
$manualreturn="";                                // Return URL if handling payment manually

// manual2 payment settings (second manual option)
$manual2return="";                                // Return URL

// Domain name suggestion system
$suggestdomains=0;															 // Set to 1 to suggest domain names in cwhoismode=0
$suggestmax=30;																	 // Maximum domains to check
// Domain name suggestion categories
$suggestcategory[]="eCommerce";
$suggestcategory[]="Net Talk";
$suggestcategory[]="Cyber";
// Domain name suggestion strings
$suggest[]="eCommerce,%commerce";
$suggest[]="eCommerce,buy%";
$suggest[]="eCommerce,shop%";
$suggest[]="eCommerce,%4sale";
$suggest[]="eCommerce,%store";
$suggest[]="eCommerce,bargain%";
$suggest[]="eCommerce,order%";
$suggest[]="Net Talk,ez%";
$suggest[]="Net Talk,eze%";
$suggest[]="Net Talk,%portal";
$suggest[]="Net Talk,surf%";
$suggest[]="Net Talk,%boost";
$suggest[]="Net Talk,go2%";
$suggest[]="Cyber,%wire";
$suggest[]="Cyber,%file";
$suggest[]="Cyber,%online";
$suggest[]="Cyber,%scape";
$suggest[]="Cyber,%wired";


//$UseMySQL=true;
//$DbHost="mysql.s801.sureserver.com:3306"; // MySQL host
//$DbUser="beta";          // MySQL username
//$DbPassword="K@ribu098!";      // MySQL password
//$DbName="slashdotpro_beta";          // MySQL database name
//$DbTableName="";     // MySQL table name

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Code below this point should not need modifying. Do so at your opwn risk!                               //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
@error_reporting(E_ERROR);
reset($_GET);
$domain="";
if (!empty($_GET)) while(list($name, $value) = each($_GET)) $$name = $value;
// Start session or open existing one
if (!isset($cwaction))
	$cwaction="";
if (!isset($csymbol2))
  $csymbol2="";
// session_start();
if (($_SESSION['ses_csymbol']!=$csymbol) || (($cwaction=="") && ($cwhoismode==1)))
{
  session_destroy();
  session_start();
}
$_SESSION['ses_csymbol']=$csymbol;
$_SESSION['ses_csymbol2']=$csymbol2;
if (!isset($HTMLEmail))
{
	if (strlen($csymbol)>1)
		$HTMLEmail="Y";
	else
		$HTMLEmail="N";
}
$showwarning=0;
function pleasewait($msg,$width,$height,$color,$backcolor,$bordcolor)
{
	global $cwaction,$showwarning;
	$y=intval(($height-20)/2);
	$showwarning=1;
	if ($cwaction=="check")
	{
	  print "<script language=\"JavaScript\">\n";
	  print "<!--\n";
	  print "function ShowWarning()\n";
	  print "{\n";
	  print "  waitWindow.style.top = (document.body.offsetHeight / 2) - ($height / 2)\n";
	  print "  waitWindow.style.left = (document.body.offsetWidth / 2) - ($width / 2)\n";
	  print "  waitWindow.style.display = \"\"\n";
	  print "}\n";
	  print "function HideWarning()\n";
	  print "{\n";
	  print "  waitWindow.style.display = \"none\"\n";
	  print "}\n";
	  print "// -->\n";
	  print "</script>\n";
	  print "<DIV id=\"waitWindow\" style=\"BORDER-RIGHT: $bordcolor 1px solid; BORDER-TOP: $bordcolor 1px solid; DISPLAY: none; BORDER-LEFT: $bordcolor 1px solid; WIDTH: ".$width."px; COLOR: $color; PADDING-TOP: ".$y."px; BORDER-BOTTOM: $bordcolor 1px solid; POSITION: absolute; TOP: 10px; HEIGHT: ".$height."px; BACKGROUND-COLOR: $backcolor;\"\n";
	  print "align=\"center\"><b>$msg</b>\n";
	  print "</DIV>\n";
	  print "<script language=\"JavaScript\">\n";
	  print "<!-- JavaScript\n";
	  print " ShowWarning()\n";
	  print "// - JavaScript - -->\n";
	  print "</script>\n";
	}
}
?>
