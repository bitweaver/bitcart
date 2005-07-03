<?php 
// FishCart: an online catalog management / shopping system
// Copyright (C) 1997-2002  FishNet, Inc.

$now=time();

if( $zflag1 & $flag_zonesplitcc ){
 $cc_number=ereg_replace(" ","",$cc_number);
 $cc_number=ereg_replace("-","",$cc_number);	
 // split out the last 6 digits
 $cc_lastsix = substr((string)$cc_number,-6,6);
 // split out all but the last 6 digits
 $cc_number = substr((string)$cc_number,0,-6) . "......";
}

// this file is included, already queried vendor table
//$fcv = new FC_SQL;
//$fcv->query("select * from vend where vendzid=$zid"); 
//$fcv->next_record();

/* filter nasty shell escapes from the email address 
$billing_email=EscapeShellCmd($billing_email); */

$body = '';

/* BILLING INFORMATION BLOCK */
$body .= "\n";
$body .= sprintf("Billing Information:  %s %s %s\n", $billing_first, $billing_mi, $billing_last);
if($billing_company){
 $body .= sprintf("                      %s\n",$billing_company);
}
if($billing_address1){
 $body .= sprintf("                      %s\n",$billing_address1);
}
if($billing_address2){
 $body .= sprintf("                      %s\n",$billing_address2);
}
$body .= sprintf("                      %s, ",$billing_city);
$body .= sprintf("%s  ",$billing_state);
$body .= sprintf("%s %s  ",$billing_zip,$billing_zip4);
$body .= sprintf("%s\n",$billing_country);
$body .= sprintf("                      %s %s",$billing_acode,$billing_phone);
if( $billing_ext ){
 $body.=sprintf(" x.%s",$billing_ext);
}
$body .= "\n";
$body .= sprintf("E-Mail Address:       %s\n",$billing_email);

// PRODUCT INFORMATION BLOCK

require('proddispfixed.php');

// END OF PRODUCT INFORMATION BLOCK

/* SHIPPING INFORMATION BLOCK */
$body .= "\n";
$body .= sprintf("Shipping Address:     %s %s %s\n", $shipping_first,$shipping_mi,$shipping_last);
if($shipping_company){
 $body .= sprintf("                      %s\n",$shipping_company);
}
if($shipping_address1){
 $body .= sprintf("                      %s\n",$shipping_address1);
}
if($shipping_address2){
 $body .= sprintf("                      %s\n",$shipping_address2);
}
$body .= sprintf("                      %s, ",$shipping_city);
$body .= sprintf("%s  ",$shipping_state);
$body .= sprintf("%s %s  ",$shipping_zip,$shipping_zip4);
$body .= sprintf("%s\n",$shipping_country);

/* CREDIT INFORMATION BLOCK */
// if( $zflag1 & $flag_zonecc ){
if( $zflag1 & $flag_zonesplitcc ){
	$body .= "\n";
	$body .= sprintf("Credit Card:          %s\n",$cctype);
	$body .= sprintf("Name on Credit Card:  %s\n",$cc_name);
	$body .= sprintf("CC #:                 %s\n",$cc_number);
	$body .= sprintf("CC Exp Date:          %s/%s\n",$ccexp_month,$ccexp_year);
	$body .= sprintf("CVV2:                 %s\n",$cc_cvv);
}
							  
/* VENDOR INFORMATION BLOCK */
$body .= "\nOrder ID: $cartid\n";
if($aid){
 $body .= "AID: $aid\n\n";
}
if( !empty($couponid) ){
 $body .="\n".fc_text('coupon')."$couponid\n\n";
}
if( $payment_only ){
$body .= "\n".fc_text('paymentinv')."\n$payinv\n\n";
}
if( !empty($esd_count) ){
 $body .="\n".fc_text('dlusernamefix')."$download_user\n";
 $body .=fc_text('dlpasswordfix')."$download_pw\n\n";
}

$body .= "\n";
$body .= sprintf("%s\n", $fcv->f("vsvcname"));

if( $fcv->f("vsvcaddr1") ){
 $body .= sprintf("%s\n", $fcv->f('vsvcaddr1'));
}

if( $fcv->f("vsvcaddr2") ){
 $body .= sprintf("%s\n", $fcv->f('vsvcaddr2'));
}

$body .= sprintf("%s,  %s  %s  %s\n", $fcv->f("vsvccity"),
 $fcv->f("vsvcstate"), $fcv->f("vsvczip"), $fcv->f("vsvcnatl"));

if( $fcv->f("vsvcphone") ){
 $body .= sprintf("Phone: %s\n", $fcv->f('vsvcphone'));
}

if( $fcv->f("vsvcfax") ){
 $body .= sprintf("Fax: %s\n", $fcv->f('vsvcfax'));
}

$body .= fc_text('orderorigin');
global $gBitSystem;
mail( $fcv->f("vendoemail"), "Online Order", $body, "From: ".$gBitSystem->getSenderEmail() );

if( $zflag1 & $flag_zonesplitcc ){
 // add this CC to the split CC database
 $fccc = new FC_SQL;
 $fccc->User = '';
 $fccc->Password = '';
 if ( $databaseeng == 'odbc' && $dialect == 'solid' ){
  // solid uses views for the split cc work
  $fccc->query(
  "insert into _ccnum (userid,tstamp,fetched,orderid,cc6) ".
  "values (,$now,'0','$cartid','$cc_lastsix')");
 }else{
  // the value of 1 below for userid is coordinated with 
  // the ${instid}_users table set up in sql_mysql.sql
  $fccc->query(
  "insert into ${instid}_ccnums (userid,tstamp,fetched,orderid,cc6) ".
  "values (1,$now,'0','$cartid','$cc_lastsix')");
 }
 $fccc->commit();
}
?>
