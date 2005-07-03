<?php 
// FishCart: an online catalog management / shopping system
// Copyright (C) 1997-2002  FishNet, Inc.

// Final step to process an offline order.
// The vendor record has been pulled and is in $fcv.

if(!$pub_inc){
 require('./public.php');
}
$now=time();

?><html>
<head><title>Order Form</title></head><body bgcolor="#ffffff">
<h2 align=center></h2>
<h3 align=center>Order Form</h3>

<pre><?php 
$tmp=sprintf("Billing Information:  %s %s\n", $billing_first, $billing_last);
echo "$tmp";
if($billing_address1!=""){
 $tmp=sprintf("                      %s\n",$billing_address1);
 echo $tmp;
}
if($billing_address2!=""){
 $tmp=sprintf("                      %s\n",$billing_address2);
 echo $tmp;
}
$tmp=sprintf("                      %s, ",$billing_city);
echo $tmp;
$tmp=sprintf("%s  ",$billing_state);
echo $tmp;
$tmp=sprintf("%s  ",$billing_zip);
echo $tmp;
$tmp=sprintf("%s\n",$billing_country);
echo $tmp;
$tmp=sprintf("                      %s %s",$billing_acode,$billing_phone);
if($billing_ext!=""){
 $tmp.=sprintf(" x.%s",$billing_ext);
}
echo $tmp;

echo "\n";
$tmp=sprintf("E-Mail Address:       %s\n",$billing_email);
echo $tmp;

/* PRODUCT INFORMATION BLOCK */

require('proddispecho.php');

/* END OF PRODUCT INFORMATION BLOCK */

/* SHIPPING INFORMATION BLOCK */
echo "\n";
$tmp=sprintf("Shipping Address:     %s %s\n", $shipping_first,$shipping_last);
echo $tmp;
if($shipping_address1!=""){
 $tmp=sprintf("                      %s\n",$shipping_address1);
 echo $tmp;
}
if($shipping_address2!=""){
 $tmp=sprintf("                      %s\n",$shipping_address2);
 echo $tmp;
}
$tmp=sprintf("                      %s, ",$shipping_city);
echo $tmp;
$tmp=sprintf("%s  ",$shipping_state);
echo $tmp;
$tmp=sprintf("%s  ",$shipping_zip);
echo $tmp;
$tmp=sprintf("%s\n\n",$shipping_country);
echo $tmp;

if($cc_number!=""){
 /* CREDIT INFORMATION BLOCK */
 $tmp=sprintf("Credit Card:          %s\n",$cctype);
 echo $tmp;
 $tmp=sprintf("Customer Name on CC:  %s\n",$cc_name);
 echo $tmp;
 $tmp=sprintf("CC #:                 %s\n",$cc_number);
 echo $tmp;
 $tmp=sprintf("CC Exp Date:          %s/%s\n",$ccexp_month,$ccexp_year);
 echo $tmp;
 echo "\nSignature:            _____________________________\n\n";
}


echo "Order ID: $cartid\n\n";
if($aid){
 echo "AID: $aid\n\n";
}
if( !empty($couponid) ){
 echo "Coupon: $couponid\n\n";
}
 
/* VENDOR INFORMATION BLOCK */
$fax=$fcv->f("vsvcfax");
if( empty($fax) ){?>

_______________________________________________________________
<i>Please print, sign and mail to the address below.  Thank you!</i>
<?php }else{?>

_________________________________________________________________________
<i>Please print, sign and mail or fax to the address below.  Thank you!</i>
<?php }
$tmp=sprintf("\n%s\n", $fcv->f("vsvcname"));
echo "$tmp";
$tmp=$fcv->f("vsvcaddr1");
if($tmp!=""){
 $tmp=sprintf("%s\n",$tmp);
 echo $tmp;
}
$tmp=$fcv->f("vsvcaddr2");
if($tmp!=""){
 $tmp=sprintf("%s\n",$tmp);
 echo $tmp;
}
$tmp=sprintf("%s,  %s  %s  %s\n",
	$fcv->f("vsvccity"),
	$fcv->f("vsvcstate"),
	$fcv->f("vsvczip"),
	$fcv->f("vsvcnatl"));
echo $tmp;
$tmp=$fcv->f("vsvcphone");
if($tmp!=""){
 $tmp=sprintf("Phone: %s\n", $tmp);
 echo $tmp;
}
if( !empty($fax) ){
 $tmp=sprintf("Fax: %s\n", $fax);
 echo $tmp;
}
?></pre>
<p>
<a href="<?php echo $nsecurl ?>/"> <?php echo fc_text('homepage'); ?></a>

<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
