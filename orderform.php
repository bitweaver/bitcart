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
*/

// this file has been converted to smarty
$mid = 'bitpackage:bitcart/order_form.tpl';

require_once( '../bit_setup_inc.php' );

require('./functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// custid cookie is mime encoded, don't escape
$CookieCustID = getcookie("Cookie${instid}CustID");
$CookieCart   = getcookie("Cookie${instid}Cart");
$cartid = getparam('cartid');
$zid = (int)getparam('zid');
$lid = (int)getparam('lid');
$subz = (int)getparam('subz');
$custid = (int)getparam('custid');
// ==========  end of variable loading  ==========

require('./public.php');
require('./flags.php');
require('./cartid.php');
require('./languages.php');

$browserTitle = fc_text('titletag');


$fcz=new FC_SQL;
$fcz->query("select zonecurrsym,zflag1 from zone where zoneid=$zid");
if($fcz->next_record()){
 $csym=stripslashes($fcz->f("zonecurrsym"));
 $csym=trim($csym);
 $zflag1=(int)$fcz->f("zflag1");
}else{
 $csym="";
 $zflag1=0;
}
$fcz->free_result();

if( $zflag1 & $flag_zonepwcatalog ){
	// password controlled access
	include('./pw.php');
}

$stotal=0.0;	// product subtotal
$pstotal=0.0;
$mtotal=0.0;	// periodic service subtotal
$pmtotal=0.0;
$ototal=0.0;
$ttotal=0.0;	// product total
$ptotal=0.0;	// periodic service total
$wtotal=0.0;
$ccexp_years = 8;

if(empty($cartid)){
 header("Location: $nsecurl$cartdir/index.php?cartid=$cartid&zid=$zid&lid=$lid");
 exit;
}

if( ($zflag1 & $flag_zonetcpage) && ($approvetc == 0) ){
	// terms and conditions were not approved
	// if javascript is working we should never get here
?>
<html>
<head>
<link rel=stylesheet href="style.css" type="text/css" />
<title>Terms and Conditions Not Approved</title>
</head>
<body bgcolor="#FFFFFF">
<p><b>The Terms and Conditions were not approved; your order cannot be
completed without this approval.  Please click the &quot;Back&quot; button
on your browser to approve them, or click on the link below to abandon your
order and return to the front page.  Thank you</b></p>
<p>
<a href="<?php echo $nsecurl ?>/"> {tr}homepage{/tr}</a>
</p>
<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php
  exit;
}

$fai=new FC_SQL;
$fcc=new FC_SQL;
$fct=new FC_SQL;
$fcl=new FC_SQL;
$fco = new FC_SQL;
$fcol = new FC_SQL;
$fpo=new FC_SQL;
$fps=new FC_SQL;
$fpr = new FC_SQL;
$fpl = new FC_SQL;
$fcw=new FC_SQL;
$fasz=new FC_SQL;

if( $zflag1 & $flag_zonepwcatalog ){
 $custid=(int)$pwuid;
 $fcc->query("select * from cust where custid=$custid");
 $cc = $fcc->next_record();
}elseif(isset($CookieCustID)){
 //list($custid,$cookie_email)=explode(":",base64_decode($CookieCustID));
 $custid=(int)$purchid;	// already set by cartid.php
 $fcc->query("select * from cust where custid=$custid");
 $cc = $fcc->next_record();
}else{
 $custid = 0;
}

// get the Web table
$fcw->query(
 "select webback,webtext,weblink,webvlink,webalink,webbg,webfree,websort ".
 "from web where webzid=$zid and weblid=$lid");
$fcw->next_record();
$srt=$fcw->f("websort");

// get the language templates
$fcl->query(
 "select langgeo,langshow,langproc from lang where langid=$lid");
$fcl->next_record();
$geo=$fcl->f("langgeo");
$show=$fcl->f("langshow");
$proc=$fcl->f("langproc");
$fcl->free_result();

$fasz->query(
 "select subzflag0,subztaxpern,subztaxpers,subztaxcmtn,subztaxcmts ".
 "from subzone where subzid=$zid and subzsid=$subz");
if( !$fasz->next_record() ){
 $fasz->query("update ohead set subz=0 where orderid='$cartid'");
 $fasz->commit();
 header("Location: $nsecurl$cartdir/$geo?cartid=$cartid&zid=$zid&lid=$lid");
 exit;
}

$fco->query("select aid,contrib,shipid,couponid from ohead ".
 "where orderid='$cartid'");
if( !$fco->next_record() ){
 echo fc_text('invalidorder');
 exit;
}else{
 $contamt=(double)$fco->f("contrib");
 $aid=stripslashes($fco->f("aid"));
 $curshipid=(int)$fco->f("shipid");
 $couponid=stripslashes($fco->f("couponid"));
}

$fcol->query("select * from oline where orderid='$cartid'");
if( !$fcol->next_record() ){?>
<center><p>{tr}cartempty{/tr}<p>
<a href="<?php echo $nsecurl.$cartdir ?>/index.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>">
<b>{tr}zonehome{/tr}</b></a></center>
</center>
<?php
  exit;
}

$fct->query("select
shipid,shipcalc,shipdescr,shippercent,shipitem,shipitem2,shipsvccode ".
	"from ship where shipid=$curshipid");
if( $fct->next_record() ){
 $tmp=$fct->f("shipcalc");
 $shipcalc = './' . $tmp;
 if( empty($tmp) || !file_exists($shipcalc) ){
  $shipcalc="";
 }
 $fct->free_result();
}else{
 $shipcalc="";
}


if( $zflag1 & $flag_zonetclink ){
	$smarty->assign( 'zonetclink', TRUE );
}

//if(empty($shipcalc)){
//	echo fc_text('noshipcalc').'<br />';
//}
// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE



$smarty->assign_by_ref( 'salutations', $salutearray );

$billiso= !empty( $cc ) ? $fcc->f("custsnatl") : '';
$smarty->assign( 'billCountryOptions', show_countries( $zid, $lid, $billiso, $lang_iso ) );

$shipiso= !empty( $cc ) ? $fcc->f("custsnatl") : '';
$smarty->assign( 'shipCountryOptions', show_countries( $zid, $lid, $shipiso, $lang_iso ) );

$allowupdate=0;

$fco->free_result();
$fcol->free_result();

//show CC if nonzero total
if (($ttotal || $mtotal) && ($zflag1 & $flag_zonecc) ) {
	$smarty->assign( 'displayCC', TRUE);
}

require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' );

?>