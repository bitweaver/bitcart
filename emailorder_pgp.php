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

if(''!=''){

 // if using gpg instead of pgp...
 // $pname="/usr/bin/gpg --lock-never --batch -r  -ea | mail -s [Order] ".$fcv->f("vendoemail");

 $vemail=EscapeShellCmd($fcv->f("vendoemail"));
 $pname='pgp -feat +batchmode  | mail -s Order '.$vemail;
}else{
 $pname='mail -s Order '.$fcv->f("vendoemail");
}
$cf=popen($pname,"w");

/* BILLING INFORMATION BLOCK */
fputs($cf,"\n");
$tmp=sprintf("Billing Information:  %s %s %s\n",
		$billing_first, $billing_mi, $billing_last);
fputs($cf,"$tmp");
if($billing_company){
 $tmp=sprintf("                      %s\n",$billing_company);
 fputs($cf,$tmp);
}
if($billing_address1){
 $tmp=sprintf("                      %s\n",$billing_address1);
 fputs($cf,$tmp);
}
if($billing_address2){
 $tmp=sprintf("                      %s\n",$billing_address2);
 fputs($cf,$tmp);
}
$tmp=sprintf("                      %s, ",$billing_city);
fputs($cf,$tmp);
$tmp=sprintf("%s  ",$billing_state);
fputs($cf,$tmp);
$tmp=sprintf("%s  ",$billing_zip);
fputs($cf,$tmp);
$tmp=sprintf("%s\n",$billing_country);
fputs($cf,$tmp);
$tmp=sprintf("                      %s %s",$billing_acode,$billing_phone);
if($billing_ext){
 $tmp.=sprintf(" x.%s",$billing_ext);
}
fputs($cf,$tmp);

fputs($cf,"\n");
$tmp=sprintf("E-Mail Address:       %s\n",$billing_email);
fputs($cf,$tmp);

// PRODUCT INFORMATION BLOCK

require('proddisppgp.php');

// END OF PRODUCT INFORMATION BLOCK

/* SHIPPING INFORMATION BLOCK */
fputs($cf,"\n");
$tmp=sprintf("Shipping Address:     %s %s\n", $shipping_first,$shipping_last);
fputs($cf,$tmp);
if($shipping_company){
 $tmp=sprintf("                      %s\n",$shipping_company);
 fputs($cf,$tmp);
}
if($shipping_address1){
 $tmp=sprintf("                      %s\n",$shipping_address1);
 fputs($cf,$tmp);
}
if($shipping_address2){
 $tmp=sprintf("                      %s\n",$shipping_address2);
 fputs($cf,$tmp);
}
$tmp=sprintf("                      %s, ",$shipping_city);
fputs($cf,$tmp);
$tmp=sprintf("%s  ",$shipping_state);
fputs($cf,$tmp);
$tmp=sprintf("%s  ",$shipping_zip);
fputs($cf,$tmp);
$tmp=sprintf("%s\n",$shipping_country);
fputs($cf,$tmp);

/* CREDIT INFORMATION BLOCK */
fputs($cf,"\n");
$tmp=sprintf("Credit Card:          %s\n",$cctype);
fputs($cf,$tmp);
$tmp=sprintf("Name on Credit Card:  %s\n",$cc_name);
fputs($cf,$tmp);
$tmp=sprintf("CC #:                 %s\n",$cc_number);
fputs($cf,$tmp);
$tmp=sprintf("CC Exp Date:          %s/%s\n",$ccexp_month,$ccexp_year);
fputs($cf,$tmp);
$tmp=sprintf("CVV2:                 %c\n",$cc_cvv);
fputs($cf,$tmp);
 
/* VENDOR INFORMATION BLOCK */
fputs($cf,"\nOrder ID: $cartid\n");
if($aid){
 fputs($cf,"AID: $aid\n");
}
if( !empty($couponid) ){
	 fputs($cf,"Coupon: $couponid\n");
}
if( $payment_only ){
$body .= "\n".fc_text('paymentinv')."\n$payinv\n";
}
$tmp=sprintf("\n%s\n", $fcv->f("vsvcname"));
fputs($cf,"$tmp");
$tmp=$fcv->f("vsvcaddr1");
if($tmp){
 $tmp=sprintf("%s\n",$tmp);
 fputs($cf,$tmp);
}
$tmp=$fcv->f("vsvcaddr2");
if($tmp){
 $tmp=sprintf("%s\n",$tmp);
 fputs($cf,$tmp);
}
$tmp=sprintf("%s,  %s  %s  %s\n",
	$fcv->f("vsvccity"),
	$fcv->f("vsvcstate"),
	$fcv->f("vsvczip"),
	$fcv->f("vsvcnatl"));
fputs($cf,$tmp);
$tmp=$fcv->f("vsvcphone");
if($tmp){
 $tmp=sprintf("Phone: %s\n", $tmp);
 fputs($cf,$tmp);
}
$tmp=$fcv->f("vsvcfax");
if($tmp){
 $tmp=sprintf("Fax: %s\n", $tmp);
 fputs($cf,$tmp);
}

fputs($cf, fc_text('orderorigin'));

pclose($cf);

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
