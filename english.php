<?php
// cWhois Domain Cart English language V3.9

// Search section
$lang['CheckButton'] = "Check";
// The next two tip lines can be left blank "" if you wish
$lang['TipLine1'] = "Type your domain name. If no extension is selected, .com is assumed";
$lang['TipLine2'] = "Tip: For hosting only type domain name & any extension.";

// Results section headers and buttons
$lang['Domain'] = "Domain";
$lang['Available'] = "Available";
$lang['DomainOpt'] = "Domain Option";
$lang['HostingOpt'] = "Hosting Option";
$lang['Buy'] = "Buy?";
$lang['CheckoutButton'] = "Checkout";
$lang['AddButton'] = "Add to Cart";

// Results section response
$lang['Yes'] = "Yes";
$lang['No'] = "No";
$lang['Premium'] = "Premium";
$lang['Lookup'] = "Lookup";
$lang['CannotVerify'] = "Cannot Verify";
$lang['InvalidDomain'] = "Invalid Domain";
$lang['WhoisProblem'] = "Whois Problem";

// Domain Options
$lang['Register'] = "Register";
$lang['HostingOnly'] = "Hosting Only";
$lang['Transfer'] = "Transfer";
$lang['Renew'] = "Renew";
$lang['Year'] = "year";

// Hosting Options
$lang['NoHosting'] = "No Hosting";
$lang['Setup'] = "setup";

// Shopping cart section
$lang['ShoppingCart'] = "Shopping Cart";
$lang['Remove'] = "Remove";
$lang['Subtotal'] = "Subtotal";
$lang['Total'] = "Total";
$lang['MonthlyCharge'] = "Monthly charge";
$lang['ToPayNow'] = "Total to pay now";
$lang['RemoveButton'] = "Remove";
$lang['UpdateButton'] = "Update";
$lang['Monthly'] = "Monthly";
$lang['Now'] = "Now";
$lang['Tax'] = "Tax at";
$lang['ExtraFee'] = "Administration Fee";
$lang['Tax1'] = "Tax at";
$lang['Tax2'] = "Tax at";
$lang['TaxToAdd'] = "Tax maybe added to this total";
$lang['Discount'] = "Discount";

// Simple hosting mode
$lang['SelectHosting'] = "Select Hosting Package";
$lang['LikeToRegister'] = "I would like to register a domain";
$lang['JustHost'] = "I just want a hosting package";
$lang['ContinueButton'] = "Continue";
$lang['CheckDomain'] = "Check domain availability";
$lang['SelectReg'] = "Select registration option";
$lang['SelectTran'] = "Select transfer option";
$lang['IsAvailable'] = "is available";
$lang['NotAvailable'] = "not available";
$lang['NoPremium'] = "We do not register premium names";
$lang['IsPremium'] = "is available at a premium";
$lang['NotValid'] = "Domain name is not valid";
$lang['YouMust'] = "You must enter a domain name and check availability";
$lang['DomainToHost'] = "Enter domain name to host";
$lang['LookupOptional'] = "Lookup (optional)";

// Domain suggestion system
$lang['SuggestDomain']="Suggest domain names";
$lang['SuggestCategory']="Select category";

// Contact Form

$lang['YourOrder'] = "Your Order";
$lang['ModifyButton'] = "Modify";
$lang['Agreements'] = "Agreements";
$lang['Agreed'] = "Agreed";
$lang['OverallAgree'] = "I agree to the !!!terms and conditions!!!";
$lang['OverallAlert'] = "You must tick the terms and conditions agreement box";
$lang['RegAgree'] = "I am authorized to register domains & agree to the !!!registration terms!!!";
$lang['RegAlert'] = "You must tick the domain register agreement box";
$lang['TransferAgree'] = "I am authorized to transfer domains & agree to the !!!transfer terms!!!";
$lang['TransferAlert'] = "You must tick the domain transfer agreement box";
$lang['RenewAgree'] = "I am authorized to renew domains & agree to the !!!renew terms!!!";
$lang['RenewAlert'] = "You must tick the domain renewal agreement box";
$lang['HostAgree'] = "I agree to the !!!hosting services terms!!!";
$lang['HostAlert'] = "You must tick the hosting service agreement box";
$lang['ContactDetails'] = "Your Contact Details";
$lang['PayMethod'] = "Payment Method";
$lang['TuringCode'] = "Security Code";
$lang['TuringCodeRequired'] = "Please enter the security code displayed";
$lang['CouponNotValid'] = "Sorry this coupon code is not recognized";
$lang['CouponExpired'] = "Sorry this coupon code has expired";
$lang['IdealBankList'] = "Select your bank";

// The next variables define the contact form fields.
// $cform[] = "form variable,Label Displayed,Optional alert text if field must be filled in";
// You can add and delete them as required. Leave the alert text part blank if the field is optional.
// Keep the variable names short. For the users name and email you must use the variables
// 'name' and 'email' as they are used when sending the order confirmation.
// If you require a password to be entered use filenames password and verifypassword.

$cform[] = "org,Organisation,";
$cform[] = "fname,Name,Fill out the registration form to complete your purchase";
$cform[] = "lname,Last Name,Enter your last name";
$cform[] = "password,Password,Please enter a strong password";
$cform[] = "verifypassword,Verify Password, Passwords do not match";
$cform[] = "str1,Address,You must enter your address";
$cform[] = "city,City,You must enter your city";
$cform[] = "country,Country,You must enter your country";
$cform[] = "tel,Telephone,You must enter your telephone number";
$cform[] = "email,Email,You must enter your valid email address";
$cform[] = "curr,Currency,Please select a currency,KES,USD";
$cform[] = "total, Total, Enter Value";

// Customer email
// First line is the subject.

$cemail[] = "Order confirmation from !!!vendorcompany!!!";
$cemail[] = "Thank you for your order. For reference the !!!vendorcompany!!! order number is !!!ordnum!!!.";
$cemail[] = "";
$cemail[] = "Order details are as follows:-";
$cemail[] = "";
$cemail[] = "!!!orderdetails!!!";
$cemail[] = "";
$cemail[] = "If you have any questions about this order you can contact !!!vendorcompany!!! on 01905 745339 or via email: !!!vendoremail!!!.";

// Vendor email
// First line is subject

$vemail[] = "Order received from !!!name!!!";
$vemail[] = "Order !!!ordnum!!! received from:-";
$vemail[] = "";
$vemail[] = "!!!formfields!!!";
$vemail[] = "";
$vemail[] = "Order details:-";
$vemail[] = "";
$vemail[] = "!!!orderdetails!!!";

?>
