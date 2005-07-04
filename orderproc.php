<?php /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2002  FishNet, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,
USA.

   N. Michael Brennen
   FishNet(R), Inc.
   850 S. Greenville, Suite 102
   Richardson,  TX  75081
   http://www.fni.com/
   mbrennen@fni.com
   voice: 972.669.0041
   fax:   972.669.8972

This is the final stop to process an order.  All the info
is checked, and if it is okay we require() the given order
processing script from 'vendonline' or 'vendofline' from
the vendor table to process the data.
*/

require_once( '../bit_setup_inc.php' );

require('./functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// custid cookie is mime encoded, don't escape
$CookieCustid = getcookie("Cookie${instid}Custid");
$CookieCart   = getcookie("Cookie${instid}Cart");
$remote_addr  = getserver('REMOTE_ADDR');
$cartid = getparam('cartid');
$zid = (int)getparam('zid');
$lid = (int)getparam('lid');
$aid = (int)getparam('aid');
$referer = getparam('referer');
$billing_email = getparam('billing_email');
$billing_first = getparam('billing_first');
$billing_mi = getparam('billing_mi');
$billing_last = getparam('billing_last');
$billing_address1 = getparam('billing_address1');
$billing_address2 = getparam('billing_address2');
$billing_city = getparam('billing_city');
$billing_state = getparam('billing_state');
$billing_zip = getparam('billing_zip');
$billing_zip4 = getparam('billing_zip4');
$billing_country = getparam('billing_country');
$billing_acode = getparam('billing_acode');
$billing_phone = getparam('billing_phone');
$shipping_email = getparam('shipping_email');
$shipping_first = getparam('shipping_first');
$shipping_mi = getparam('shipping_mi');
$shipping_last = getparam('shipping_last');
$shipping_address1 = getparam('shipping_address1');
$shipping_address2 = getparam('shipping_address2');
$shipping_city = getparam('shipping_city');
$shipping_state = getparam('shipping_state');
$shipping_zip = getparam('shipping_zip');
$shipping_zip4 = getparam('shipping_zip4');
$shipping_country = getparam('shipping_country');
$shipping_acode = getparam('shipping_acode');
$shipping_phone = getparam('shipping_phone');
$cc_name = getparam('cc_name');
$cc_number = getparam('cc_number');
$cc_cvv = (int)getparam('cc_cvv');
$cctype = getparam('cctype');
$onoff = getparam('onoff');
$custid = (int)getparam('custid');
$itot = (int)getparam('itot');
$zflag1 = (int)getparam('zflag1');
$ccexp_years = (int)getparam('ccexp_years');
$ccexp_year = (int)getparam('ccexp_year');
$ccexp_month = (int)getparam('ccexp_month');
$promoemail=(int)getparam('promoemail');
$ptotal=(double)getparam('ptotal');
$ttotal=(double)getparam('ttotal');
$contrib=(int)getparam('contrib');
$contamt=(double)getparam('contamt');
$payment=(double)getparam('payment');
$contrib_only=(int)getparam('contrib_only');
$payment_only=(int)getparam('payment_only');
$stotal=(double)getparam('stotal');
$pstotal=(double)getparam('pstotal');
$mtotal=(double)getparam('mtotal');
$mstotal=(double)getparam('mstotal');
$wtotal=(double)getparam('wtotal');
$oship=(double)getparam('oship');
$shipsubtotal=(double)getparam('shipsubtotal');
$otax=(double)getparam('otax');
$ototal=(double)getparam('ptotal');
$cpndisc=(double)getparam('cpndisc');
$staxn=(double)getparam('staxn');
$staxs=(double)getparam('staxs');
$taxsubtotal=(double)getparam('taxsubtotal');
$ptaxsubtotal=(double)getparam('ptaxsubtotal');
$pstaxn=(double)getparam('pstaxn');
$pstaxs=(double)getparam('pstaxs');
$tqty=(double)getparam('tqty');
$shamt=(double)getparam('shamt');
$giftorder=(int)getparam('giftorder');
$approvetc=(int)getparam('approvetc');
$retain_addr=(int)getparam('retain_addr');
// ==========  end of variable loading  ==========

$orderproc_flag=1;	// defined only in orderproc, affects cartid.php

require('./public.php');
require('./flags.php');
require('./cartid.php');
require('./languages.php');

$fcz = new FC_SQL;
$fcz->query("select zflag1 from zone where zoneid=$zid"); 
$fcz->next_record();
$zflag1=$fcz->f("zflag1");
$fcz->free_result();

$now=time();

// for product option processing in the included files
$fpo = new FC_SQL;
$fps = new FC_SQL;
$coupon = new FC_SQL;

// lock purpose is to allow only one update on the order header
// record in case submit is double clicked
$forupd='';
$flck = new FC_SQL;
if ( $databaseeng == 'mysql' ){

  // from http://www.mysql.com/doc/L/O/LOCK_TABLES.html
  // When you use LOCK TABLES, you must lock all tables that you are going
  // to use and you must use the same alias that you are going to use in your
  // queries! If you are using a table multiple times in a query (with aliases),
  // you must get a lock for each alias!

  // sql locks explicitely broken below at rollbacks/completion
  $flck->query(
	"lock tables ohead write, oline write, olineopt write, ".
	"prod write, prodlang read, prodopt read, ship read, ".
	"subzship read, shipthresh read, cust write, ".
	"lang read, vend read, zone read, subzone read, ".
	"${instid}_ccnums write, coupon write, pw write, ".
	"weightthresh read");

}elseif ( $databaseeng == 'postgres' ){

  // http://www.postgresql.org/idocs/index.php?sql-lock.html
  // rollback/commit breaks the lock
  $flck->query("begin work");
  $flck->query("lock table ohead in row exclusive mode");
  
}elseif ( $databaseeng == 'mssql' ){

  // http://msdn.microsoft.com/library/default.asp?url=/library/en-us/acdata/ac_8_con_7a_1hf7.asp
  $msupd=' with (UPDLOCK)';
  $flck->query("begin transaction");

}elseif ( $databaseeng == 'oracle' ){

  $flck->query("lock table ohead in row exclusive mode");

}elseif ( $databaseeng == 'odbc' && '' == 'solid' ){

  // in pessimistic mode, set at table creation, should be exclusive lock
  // rollback/commit breaks the lock
  $forupd=' for update';
  $flck->query("set transaction read write");

}

$fcoc=new FC_SQL;
$fcoc->query(
	"select aid,contrib,shipid,subz,couponid from ohead$msupd ".
	"where orderid='$cartid' and complete < 1$forupd");
if( !$fcoc->next_record() ){
 echo fc_text('invalidorder');
 $fcoc->rollback();
 if ( $databaseeng == 'mysql' ){
  $flck->query('unlock tables');
 }
 if ( $databaseeng == 'postgres' ){
  $flck->query('rollback work');
 }
 exit;
}

$curshipid=(int)$fcoc->f("shipid");
$subz=(int)$fcoc->f("subz");
$couponid=$fcoc->f("couponid");

if( ($zflag1 & $flag_zonetclink) && ($approvetc == 0) ){
	// terms and conditions were not approved
	// if javascript is working we should never get here
?>
<html>
<head>
<link rel=stylesheet href="style.css" type="text/css" />
<title>Terms and Conditions Not Approved</title>
</head>
<body bgcolor="#FFFFFF">
<?php echo fc_text('tcnotapproved');?>
<p>
<a href="<?php echo $nsecurl ?>/">ATZ <?php echo fc_text('homepage'); ?></a>
</p>
<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php
  $fcoc->rollback();
  if ( $databaseeng == 'mysql' ){
   $flck->query("unlock tables");
  }
  if ( $databaseeng == 'postgres' ){
   $flck->query('rollback work');
  }
  exit;
}

if($contrib){
 $contrib=str_replace("$","",$contrib);
 $contrib=str_replace(",","",$contrib);
 $contrib=(double)$contrib;
 if( $contrib < 0 ){
  $contrib = (double)0;
 }
}

if($contamt){
 $contamt=str_replace("$","",$contamt);
 $contamt=str_replace(",","",$contamt);
 $contamt=(double)$contamt;
 if( $contamt < 0 ){
  $contamt = (double)0;
 }
}

if( $contrib_only ) {
 if( $contamt == 0 ){?>
<html>
<head>
<link rel=stylesheet href="style.css" type="text/css" />
<title>Error</title>
</head>
<body bgcolor="#FFFFFF">
<?php echo fc_text('contribblank');?>
<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php
  $fcoc->rollback();
  if ( $databaseeng == 'mysql' ){
   $flck->query("unlock tables");
  }
  if ( $databaseeng == 'postgres' ){
   $flck->query('rollback work');
  }
  exit;
 }
 $contamt= (double)$contamt;
 $ttotal = (double)$contamt;
 $ptotal=0;	// periodic service total
 $stotal=0;
 $pstotal=0;
 $mtotal=0;
 $mstotal=0;
 $oship=0;
 $otax=0;
 $ototal=0;
 $cpndisc=0;
 $staxn=0;
 $staxs=0;
 $pstaxn=0;
 $pstaxs=0;
}elseif( $payment_only ){
 $payment= (double)$payment;
 if( $payment == 0 ){?>
<html>
<head>
<link rel=stylesheet href="style.css" type="text/css" />
<title>Error</title>
</head>
<body bgcolor="#FFFFFF">
<?php echo fc_text('payamblank');?>
<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php
  $fcoc->rollback();
  if ( $databaseeng == 'mysql' ){
   $flck->query("unlock tables");
  }
  if ( $databaseeng == 'postgres' ){
   $flck->query('rollback work');
  }
  exit;
 }
 $ttotal=$payment;
 $ptotal=0;	// periodic service total
 $stotal=0;
 $pstotal=0;
 $mtotal=0;
 $mstotal=0;
 $oship=0;
 $otax=0;
 $ototal=0;
 $cpndisc=0;
 $contamt=0;
 $staxn=0;
 $staxs=0;
 $pstaxn=0;
 $pstaxs=0;
}else{
 $contamt=(double)$fcoc->f("contrib");
 $ttotal=(double)$ttotal;

 $fcsh = new FC_SQL;
 $fcsh->query("select shipdescr from ship where shipid=$curshipid");
 $fcsh->next_record();
 $shipdescr=stripslashes($fcsh->f("shipdescr"));
 $fcsh->free_result();
}
$fcoc->free_result();

$fcl = new FC_SQL;
$fcl->query("select langfinl from lang where langid=$lid");
$fcl->next_record();
$final=$fcl->f("langfinl");
$fcl->free_result();

if($billing_first=="" || $billing_last=="" ||
  ($billing_address1=="" && $billing_address2=="") ||
  $billing_city=="" || $billing_state=="" ||
  $billing_zip==""  || $billing_email=="" ){?>
<html>
<head>
<link rel=stylesheet href="style.css" type="text/css" />
<title>Error</title>
</head>
<body bgcolor="#FFFFFF">
<?php  
  echo fc_text('invalidfield');?>
<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php
  $fcoc->rollback();
  if ( $databaseeng == 'mysql' ){
   $flck->query("unlock tables");
  }
  if ( $databaseeng == 'postgres' ){
   $flck->query('rollback work');
  }
  exit;
}

// verify email syntax validity
if(!eregi("^[a-z0-9_\'\.-]+@[a-z0-9_\.-]+\.[a-z]{2,4}$",$billing_email)){ ?> 
<html>
<head>
<link rel=stylesheet href="style.css" type="text/css" />
<title>Error</title>
</head>
<body bgcolor="#FFFFFF">
<?php  
  echo fc_text('invalidemail');?>
<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php
  $fcoc->rollback();
  if ( $databaseeng == 'mysql' ){
   $flck->query("unlock tables");
  }
  if ( $databaseeng == 'postgres' ){
   $flck->query('rollback work');
  }
  exit;
}

if( $onoff != 'on' && $onoff != 'off' ){?>
<html>
<head>
<link rel=stylesheet href="style.css" type="text/css" />
<title>Error</title>
</head>
<body bgcolor="#FFFFFF">
<?php  
  echo fc_text('invalidoffon');?>
<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php
  $fcoc->rollback();
  if ( $databaseeng == 'mysql' ){
   $flck->query("unlock tables");
  }
  if ( $databaseeng == 'postgres' ){
   $flck->query('rollback work');
  }
  exit;
}

$shipping_sal=      trim($shipping_sal);
$shipping_first=    trim($shipping_first);
$shipping_company=  trim($shipping_company);
$shipping_mi=       trim($shipping_mi);
$shipping_last=     trim($shipping_last);
$shipping_addr1=    trim($shipping_addr1);
$shipping_addr2=    trim($shipping_addr2);
$shipping_city=     trim($shipping_city);
$shipping_state=    trim($shipping_state);
$shipping_zip=      trim($shipping_zip);
$shipping_zip4=     trim($shipping_zip4);
$shipping_country=  trim($shipping_country);
$shipping_acode=    trim($shipping_acode);
$shipping_phone=    trim($shipping_phone);
$shipping_facode=   trim($shipping_facode);
$shipping_fax=      trim($shipping_fax);
$shipping_email=    trim($shipping_email);

if( !$shipping_email){    $shipping_email    = $billing_email;    }
if( !$shipping_first){    $shipping_first    = $billing_first;    }
if( !$shipping_mi){       $shipping_mi       = $billing_mi;       }
if( !$shipping_last){     $shipping_last     = $billing_last;     }
if( !$shipping_company){  $shipping_company  = $billing_company;  }
if( !$shipping_address1){ $shipping_address1 = $billing_address1; }
if( !$shipping_address2){ $shipping_address2 = $billing_address2; }
if( !$shipping_city){     $shipping_city     = $billing_city;     }
if( !$shipping_state){    $shipping_state    = $billing_state;    }
if( !$shipping_zip){      $shipping_zip      = $billing_zip;      }
if( !$shipping_zip4){     $shipping_zip4     = $billing_zip4;     }
if( !$shipping_country){  $shipping_country  = $billing_country;  }
if( !$shipping_acode){    $shipping_acode    = $billing_acode;    }
if( !$shipping_phone){    $shipping_phone    = $billing_phone;    }
if( !$shipping_facode){   $shipping_facode   = $billing_facode;   }
if( !$shipping_fax){      $shipping_fax      = $billing_fax;      }

$billing_acode=
	sprintf("%s",ereg_replace("[^0-9]+","",$billing_acode));
$billing_phone=
	sprintf("%s",ereg_replace("[^0-9]+","",$billing_phone));

$shipping_acode=
	sprintf("%s",ereg_replace("[^0-9]+","",$shipping_acode));
$shipping_phone=
	sprintf("%s",ereg_replace("[^0-9]+","",$shipping_phone));

$fcv = new FC_SQL;
// select all fields, the included files use them
$fcv->query("select * from vend where vendzid=$zid"); 
$fcv->next_record();

// escape apostrophe for sql use
$sql_billing_email=ereg_replace("'","''",$billing_email);
$sql_shipping_email=ereg_replace("'","''",$shipping_email);

if( !$contrib_only && !$payment_only ){

// recalculate the order totals
// START OF ORDER TOTAL RECALC

$fcsz=new FC_SQL;
$fcsz->query("select subztaxpern,subztaxpers,subztaxcmtn,subztaxcmts ".
 "from subzone where subzid=$zid and subzsid=$subz");
if( $fcsz->next_record() ){
 $taxpern=(double)$fcsz->f("subztaxpern");
 $taxpers=(double)$fcsz->f("subztaxpers");
 $staxcmtn=$fcsz->f("subztaxcmtn"); // shipping not taxed
 $staxcmts=$fcsz->f("subztaxcmts"); // shipping taxed
 $fcsz->free_result();
}

$tqty=0;			// total quantity
$shamt=0.0;			// shipping amount
$ototal=0.0;		// order total without contribution
$stotal=0.0;		// discounted product subtotal
$pstotal=0.0;		// undiscounted product subtotal
$mtotal=0.0;		// discounted periodic service subtotal
$mstotal=0.0;		// undiscounted periodic service subtotal
$ttotal=0.0;		// order grand total
$ptotal=0.0;		// periodic service total
$wtotal=0.0;		// total accumulated weight
$taxsubtotal=0.0;	// taxable product subtotal
$ptaxsubtotal=0.0;	// taxable periodic subtotal
$shipsubtotal=0.0;	// product subtotal subject to shipping

$fpr = new FC_SQL;
$fco = new FC_SQL;
$fcw = new FC_SQL;

$fco->query("select * from oline where orderid='$cartid'"); 
while( $fco->next_record() ) {

 $qty=$fco->f("qty");
 $sku=$fco->f("sku");
 $csku=$fco->f("compsku");

 $fcw->query(
  "select prodweight from prod where prodzid=$zid and prodsku='$sku'"); 
 if($fcw->next_record()){
  $prodweight=(double)$fcw->f("prodweight");
  $fcw->free_result();
 }

 // accumulate $popttotal, $poptsettot
 while( get_prodopts($csku) ){
 }

 // figure options modified product price, accumulate setup total
 // warning: prod_price() sets various globals
 $prodprice = prod_price ( $sku );
 if($prodsetup){
  // for one time product setup regardless of qty, comment the line below
  $prodsetup=$prodsetup*$qty;
  $prodsettot=rnd($prodsettot+$prodsetup);
 }

 $ltotal = line_total( $qty, $prodprice );
 $lweight= rnd($prodweight * $qty);
 $tqty=rnd($tqty+$qty);
 if( $flag1 & $flag_persvc ){
  $mtotal=rnd($ltotal+$mtotal);
  $mstotal=rnd($ltotal+$pmtotal);
  $ptotal=rnd($ltotal+$ptotal);
 }else{
  $stotal=rnd($ltotal+$stotal);
  $pstotal=rnd($ltotal+$pstotal);
  $ototal=rnd($ltotal+$ototal);
  $ttotal=rnd($ltotal+$ttotal);
  $wtotal=rnd($wtotal+$lweight);
 }
 
 $i++;
}	// while( fco->next_record
$fco->free_result();

// accumulate setup into order total
$pstotal=rnd($prodsettot+$pstotal);
$stotal=rnd($prodsettot+$stotal);
$ototal=rnd($prodsettot+$ototal);
$ttotal=rnd($prodsettot+$ttotal);

if( ($zflag1 & $flag_zonecoupon) && $couponid ){
 // $stotal is the discounted product subtotal
 $cpndisc=(double)coupon_discount($couponid,$stotal,$tqty);
 if( $cpndisc > 0 ){
  $shipsubtotal=rnd($shipsubtotal - $cpndisc);
  $taxsubtotal=rnd($taxsubtotal - $cpndisc);
  $stotal=rnd($stotal - $cpndisc);
  $ototal=rnd($ototal - $cpndisc);
  $ttotal=rnd($ttotal - $cpndisc);
 }
 // update the redemption counter
 $coupon->query(
  "select cpnredeem,cpnmaximum from coupon where cpnid='$couponid'");
 if( $coupon->next_record() ){
  $maximum=(int)$coupon->f("cpnmaximum");
  $redeem=(int)$coupon->f("cpnredeem") + 1;
  $coupon->free_result();
  if( $redeem <= $maximum ){
   $coupon->query(
    "update coupon set cpnredeem=$redeem where cpnid='$couponid'");
  }
 }
}else{
 $cpndisc=0;
}

$fct = new FC_SQL;
$fct->query("select shipid,shipcalc,shippercent,shipitem,shipitem2,shipsvccode ".
	"from ship where shipid=$curshipid");
if( $fct->next_record() ){
 $shipcalc = $fct->f("shipcalc");
 if( $shipcalc ){
  $shipcalc = './' . $shipcalc;
  if ( file_exists($shipcalc) ){
   include( $shipcalc );
  }
 }
 $fct->free_result();
}else{
 $shamt=0.0;
}

// accumulate shipping into order total
$ototal=rnd($ototal+$shamt);
$ttotal=rnd($ttotal+$shamt);

$staxn=0.0;
$staxs=0.0;
$pstaxn=0.0;
$pstaxs=0.0;
if( $taxpern ){ // nontaxable shipping
 // periodic service tax
 $pstaxn=rnd($taxpern*$ptaxsubtotal);
 // accumulate tax into order total
 $ptotal=rnd($ptotal+$pstaxn);

 // product tax
 $staxn=rnd($taxpern*$taxsubtotal);
 // accumulate tax into order total
 $ttotal=rnd($ttotal+$staxn);
}
if( $taxpers ){ // taxable shipping
 $staxs=rnd($taxpers*($taxsubtotal+$shamt));
 // accumulate tax into order total
 $ttotal=rnd($ttotal+$staxs);
}

// add contribution to ttotal
$ttotal=rnd($contamt+$ttotal);

// END OF ORDER TOTAL RECALC

// now that ttotal is known good, check the credit card info

if(($zflag1 & $flag_zonecc) && 
    $ttotal &&
	($onoff=='on' || ($onoff=='off' && $cc_number!=''))){

	$cc_number=ereg_replace('-','',$cc_number);
	$cc_number=ereg_replace(' ','',$cc_number);
	require('./cc.php');
	if( cc_check($cctype,$cc_number,$ccexp_year,$ccexp_month) ){
		$fcoc->rollback();
		if ( $databaseeng == 'mysql' ){
			$flck->query("unlock tables");
		}
		if ( $databaseeng == 'postgres' ){
			$flck->query('rollback work');
		}
		exit;
	}

}  // onoff=="on"

} // end of contrib_only

if( $onoff=='on' && $zflag1 & $flag_zonefishgate ) {
	include('./fishnet_gateway.php');
}

if( $onoff=='on' && $zflag1 & $flag_zoneauthorizenet ) {
	include('./authorizenet.php');
}

if( $onoff=='on' && $zflag1 & $flag_zonecambist ) {
	include('./cambist.php');
}

if( $onoff=='on' && $zflag1 & $flag_zonecybercash ) {
	include('./cyberclear.php');
}

if( $onoff=='on' && $zflag1 & $flag_zonepmtclear ) {
	include('./paymentclearing.php');
}

$fch = new FC_SQL;
$fcp = new FC_SQL;

// update inventory quantity in a central place
$fch->query("select * from oline where orderid='$cartid'"); 
while( $fch->next_record() ){
	$sku=$fch->f("sku");
	$qty=(int)$fch->f("qty");
	$fcp->query("select produseinvq,prodinvqty from prod ".
		"where prodzid=$zid and prodsku='$sku'");
	$fcp->next_record();
	$useq=(int)$fcp->f("produseinvq");
	if($useq>0){
	 $iqty=(int)$fcp->f("prodinvqty");
	 $iqty=$iqty-$qty;
	 $fcp->free_result();
	 $fcp->query("update prod set prodinvqty=$iqty ".
		"where prodzid=$zid and prodsku='$sku'");
	}
	$fcp->free_result();
}

$fcc = new FC_SQL;

// update the customer record
$custid=(int)$custid;
$no_cust_record=0;
// decide which column to use as the update key, if any
$fcc->query("select custid,custototal,custbtotal from cust ".
	"where custid=$custid");
if( $fcc->next_record() ){
	$cupdate="custid=$custid";
	$fcc->free_result();
}else{
	$fcc->query("select custid,custototal,custbtotal from cust ".
	 "where custbemail='$sql_billing_email'");
	if( $fcc->next_record() ){
		$custid=$fcc->f("custid");
		$cupdate="custbemail='$sql_billing_email'";
		$fcc->free_result();
	}else{
		$no_cust_record=1;
	}
}
if( $no_cust_record ){
	// create a new customer record
	// check for random number collisions; start by assuming a collision
	$i=0;
	$collision = 1;
	while ( $collision ){
		srand((double)microtime()*1000000);
		$custid=(int)rand()+1;
		$fcc->query(
			"select count(*) as cnt from cust where custid=$custid");
		$fcc->next_record();
		// collision is the count of rows found, leave loop when 0
		$collision = (int)$fcc->f('cnt');
		$fcc->free_result();
		$i++;
		if( ($i % 10) == 0 ){
			global $gBitSystem;
			mail($gBitSystem->getErrorEmail(),
			" CUSTOMER ID COLLISION LOOP",
			"Order Number: $cartid\nLoop count: $i\n");
			sleep(1);       // try a 1 second sleep
		}
   	}
	$btotal=rnd( $ttotal + $mtotal );
	$res = $fcc->query("insert into cust (".
	"custid,".
	"custbsal,".
	"custbfname,".
	"custbmname,".
	"custblname,".
	"custbcompany,".
	"custbemail,".
	"custbaddr1,".
	"custbaddr2,".
	"custbcity,".
	"custbstate,".
	"custbzip,".
	"custbzip4,".
	"custbnatl,".
	"custbacode,".
	"custbphone,".
	"custssal,".
	"custsfname,".
	"custsmname,".
	"custslname,".
	"custscompany,".
	"custsemail,".
	"custsaddr1,".
	"custsaddr2,".
	"custscity,".
	"custsstate,".
	"custszip,".
	"custszip4,".
	"custsnatl,".
	"custsacode,".
	"custsphone,".
	"custototal,".
	"custbtotal,".
	"custloamt,".
	"custlodate,".
	"custfodate,".
	"custpromoemail".
	") values (".
	"$custid,".
	"'$billing_sal',".
	"'$billing_first',".
	"'$billing_mi',".
	"'$billing_last',".
	"'$billing_company',".
	"'$sql_billing_email',".
	"'$billing_address1',".
	"'$billing_address2',".
	"'$billing_city',".
	"'$billing_state',".
	"'$billing_zip',".
	"'$billing_zip4',".
	"'$billing_country',".
	"'$billing_acode',".
	"'$billing_phone',".
	"'$shipping_sal',".
	"'$shipping_first',".
	"'$shipping_mi',".
	"'$shipping_last',".
	"'$shipping_company',".
	"'$sql_shipping_email',".
	"'$shipping_address1',".
	"'$shipping_address2',".
	"'$shipping_city',".
	"'$shipping_state',".
	"'$shipping_zip',".
	"'$shipping_zip4',".
	"'$shipping_country',".
	"'$shipping_acode',".
	"'$shipping_phone',".
	"1,".
	"$btotal,".
	"$btotal,".
	"$now,".
	"$now,".
	"$promoemail ".
	")");
}else{
	$ocount=(int)$fcc->f("custototal") + 1;
	$btotal=rnd( (double)$fcc->f("custbtotal") + $ttotal + $mtotal );
	$fcc->free_result();
	// update the customer record
	$fcc->query("update cust set ".
		"custbsal='$billing_sal',".
		"custbfname='$billing_first',".
		"custbmname='$billing_mi',".
		"custblname='$billing_last',".
		"custbcompany='$billing_company',".
		"custbemail='$sql_billing_email',".
		"custbaddr1='$billing_address1',".
		"custbaddr2='$billing_address2',".
		"custbcity='$billing_city',".
		"custbstate='$billing_state',".
		"custbzip='$billing_zip',".
		"custbzip4='$billing_zip4',".
		"custbnatl='$billing_country',".
		"custbacode='$billing_acode',".
		"custbphone='$billing_phone',".
		"custbfacode='$billing_facode',".
		"custbfax='$billing_fax',".
		"custssal='$shipping_sal',".
		"custsfname='$shipping_first',".
		"custsmname='$shipping_mi',".
		"custslname='$shipping_last',".
		"custscompany='$shipping_company',".
		"custsemail='$sql_shipping_email',".
		"custsaddr1='$shipping_address1',".
		"custsaddr2='$shipping_address2',".
		"custscity='$shipping_city',".
		"custsstate='$shipping_state',".
		"custszip='$shipping_zip',".
		"custszip4='$shipping_zip4',".
		"custsnatl='$shipping_country',".
		"custsacode='$shipping_acode',".
		"custsphone='$shipping_phone',".
		"custsfacode='$shipping_facode',".
		"custsfax='$shipping_fax',".
		"custototal=$ocount,".
		"custbtotal=$btotal,".
		"custloamt=$btotal,".
		"custlodate=$now,".
		"custpromoemail=$promoemail ".
		"where $cupdate");
}
$fcc->commit();

// set a three year cookie
// force the cookie so custid and email addr is updated if changed
if( $retain_addr ){
 setcookie("Cookie${instid}CustID",base64_encode($custid.":".$billing_email),$now+94608000,'/');
}else{
 setcookie("Cookie${instid}CustID","",time()-1000,'/');
}

if($onoff=='on'){
	$complete=1;
}elseif($onoff=='off'){
	$complete=3;
}

$fm = new FC_SQL;

$cc_cvv=(int)$cc_cvv;
// see if we need to keep the CC info in the customer record
// zflag1 is passed from orderform.php
// check for a non blank CC so we don't overwrite a former unprocessed CC
if( ($onoff == 'on') && $cc_number && ($zflag1 & $flag_zonekeepcc) ){
	$fcc->query(
	"update cust set ".
	"custccname='$cc_name',".
	"custccnumber='$cc_number',".
	"custcctype='$cctype',".
	"custccexpmo='".sprintf("%02d",substr($ccexp_month,0,2))."',".
	"custccexpyr='$ccexp_year',".
	"custcccvv=$cc_cvv ".
	"where custid=$custid");

	$fm->query("update ohead set ".
	"oheadccname='$cc_name',".
	"oheadccnumber='$cc_number',".
	"oheadcctype='$cctype',".
	"oheadccexpmo='".sprintf("%02d",substr($ccexp_month,0,2))."',".
	"oheadccexpyr='$ccexp_year',".
	"oheadcccvv=$cc_cvv ".
	"where orderid='$cartid'");
}

// update the order record with the purchaser ID
// mark complete to show the order is no longer pending
// this may have been set at time of order creation in cartid.php
// define the cmt1-5 variables as required in orderform.php
$fm->query("update ohead set pstotal=$pstotal,discount=$cpndisc,".
	"shamt=$shamt,nstax=$staxn,tstax=$staxs,ostotal=$ototal,tstamp=$now,".
	"contrib=$contamt,ototal=$ttotal,purchid=$custid,complete=$complete,".
	"cmt1='$cmt1',cmt2='$cmt2',cmt3='$cmt3',cmt4='$cmt4',cmt5='$cmt5',".
	"mstotal=$mstotal,payinv='$payinv',oheadcustip='$remote_addr',".
	"oheadgift=$giftorder,".
	"oheadssal='$shipping_sal',".
	"oheadsfname='$shipping_first',".
	"oheadsmname='$shipping_mi',".
	"oheadslname='$shipping_last',".
	"oheadscompany='$shipping_company',".
	"oheadsemail='$sql_shipping_email',".
	"oheadsaddr1='$shipping_address1',".
	"oheadsaddr2='$shipping_address2',".
	"oheadscity='$shipping_city',".
	"oheadsstate='$shipping_state',".
	"oheadszip='$shipping_zip',".
	"oheadszip4='$shipping_zip4',".
	"oheadsnatl='$shipping_country',".
	"oheadsacode='$shipping_acode',".
	"oheadsphone='$shipping_phone',".
	"oheadsfacode='$shipping_facode',".
	"oheadsfax='$shipping_fax' ".
	"where orderid='$cartid'");
$fm->commit();

if ( $databaseeng == 'mysql' ){
 $flck->query("unlock tables");
}
if ( $databaseeng == 'postgres' ){
 $flck->query('commit work');
}

// process any ESD items (do_esd defined in functions.php)
$esd_count = do_esd();

if($onoff=="on"){
	$tmp = $fcv->f("vendonline");
	$file = './' . $tmp;
	if(!empty($tmp) && file_exists($file)){
		include( $file );
	}else{
		echo "<b>The vendor profile online ordering script &quot;${tmp}&quot; is empty or does not exist.</b><br />\n";
	}
	require( './emailconfirm.php' );
}elseif($onoff=="off"){
	$tmp = $fcv->f("vendofline");
	$file = './' . $tmp;
	if(!empty($tmp) && file_exists($file)){
		include( $file );
	}else{
		echo "<b>The vendor profile offline ordering script &quot;${tmp}&quot; is empty or does not exist.</b><br />\n";
	}
}

// delete the order cookie
setcookie("Cookie${instid}Cart","",time()-1000,'/');

if( $zflag1 & $flag_zonesqldel ){
 // delete from the SQL file
 $fcc->query("delete from ohead where orderid='$cartid'");
 $fcc->query("delete from oline where orderid='$cartid'");
 $fcc->commit();
}

header("Location: $securl$secdir/$final?zid=$zid&lid=$lid");
?>
