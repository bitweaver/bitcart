<?php 
// FishCart: an online catalog management / shopping system
// Copyright (C) 1997-2002  FishNet, Inc.

// Send the purchaser a confirming email

$fcv = new FC_SQL;
$fcv->query("select * from vend where vendzid=$zid"); 
$fcv->next_record();

// filter nasty shell escapes from the email address 
// $billing_email=EscapeShellCmd($billing_email);

if( $contrib_only ){
 $subject = fc_text('contribsubj');
}elseif( $payment_only ){
 $subject = fc_text('paymentsubj');
}else{
 $subject = fc_text('ordersubj');
}

$body = '';

if( $contrib_only ){
 $body .= fc_text('contribconf');
}elseif( $payment_only ){
 $body .= fc_text('paymentconf');
}else{
 $body .= fc_text('orderconf');
}
$body .= '  '.fc_text('thankyou')."\n";

// BILLING INFORMATION BLOCK
$body  .= "\n";
$body .= sprintf(fc_text('billinfofix')."%s %s %s\n", 
 $billing_first, $billing_mi, $billing_last);
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
$body .= sprintf(fc_text('emailaddrfix')."%s\n",$billing_email);

// PRODUCT INFORMATION BLOCK

require('proddispfixed.php');

// END OF PRODUCT INFORMATION BLOCK

/* SHIPPING INFORMATION BLOCK */
$body .= "\n";
$body .= sprintf("\n".fc_text('shipaddrfix')."%s %s %s\n",
 $shipping_first, $shipping_mi, $shipping_last);
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

/* VENDOR INFORMATION BLOCK */
$body .= "\n".fc_text('orderid')."$cartid\n";
if( !empty($couponid) ){
 $body .="\n".fc_text('coupon')."$couponid\n";
}
if( !empty($esd_count) ){
 $body .="\n".fc_text('dlusernamefix')."$download_user\n";
 $body .=fc_text('dlpasswordfix')."$download_pw\n";
}
if( $payment_only ){
$body .= "\n".fc_text('paymentinv')."\n$payinv\n";
}

$body .= "\n____________________\n";
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
global $gBitSystem;
$email = $gBitSystem->getSenderEmail();
mail( $billing_email, $subject, $body, "From: $email\r\nReturn-Path: $email" );
?>
