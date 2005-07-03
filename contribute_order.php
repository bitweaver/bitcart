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
$aid = (int)getparam('aid');
$itot = (int)getparam('itot');
$szid = (int)getparam('szid');
$zflag1 = (int)getparam('zflag1');
$custid = (int)getparam('custid');
$quantity = (int)getparam('quantity');
$ccexp_years = (int)getparam('ccexp_years');
$contrib = (double)getparam('contrib');
$doit = (int)getparam('doit');
// ==========  end of variable loading  ==========

require('./public.php');
require('./flags.php');
require('./cartid.php');
require('./languages.php');

$fcclnks = new FC_SQL;
$fcview = new FC_SQL;
$fcal = new FC_SQL;

$fcz=new FC_SQL;
$fcz->query("select zonecurrsym,zflag1 from zone where zoneid=$zid");
if($fcz->next_record()){
 $csym=stripslashes($fcz->f("zonecurrsym"));
 $csym=trim($csym);
 $zflag1=(int)$fcz->f('zflag1');
}else{
 $csym='';
 $zflag1=0;
}

$now=time();

// see if this order exists
$fco=new FC_SQL;
$fco->query("select subz,contrib from ohead where orderid='$cartid'");
if( !$fco->next_record() ){
	header("Location: $nsecurl/?cartid=$cartid&zid=$zid&lid=$lid");
}

// if subz=0, no products in the cart
$subz=(int)$fco->f("subz");

// get the language templates
$fcl=new FC_SQL;
$fcl->query(
 "select langgeo,langshow,langordr,langcopy,langterms from lang where langid=$lid");
$fcl->next_record('langterms');
$geo=$fcl->f("langgeo");
$show=$fcl->f("langshow");
$ordr=$fcl->f("langordr");
$copy=$fcl->f("langcopy");
$lterms=$fcl->f("langterms");
$fcl->free_result();

if ( !empty($doit) ) {
	if( $zflag1 & $flag_zonetcpage ){
		// set for inline terms and conditions
		$ordr='terms.php';
	}
	$contrib=str_replace("$","",$contrib);
	$contrib=str_replace(",","",$contrib);

	$c=(double)$contrib;
	if($c<0){ $c=(double)0; }

	$fch = new FC_SQL;
	$fch->query("update ohead set contrib=$c where orderid='$cartid'");
	$fch->commit();
  
	header("Location: $securl$secdir/${ordr}?".
			"cartid=$cartid&zid=$zid&lid=$lid&subz=$subz");
	exit;
}else{
	$contamt=(double)$fco->f("contrib");
}

// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE ?>
<html>
<head>
<link rel="stylesheet" ID href="style.css" type="text/css" />
<title></title></head>
<body bgcolor="#FFFFFF" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">

<?php // START OF ESSENTIAL CART DISPLAY CODE ?>
<!--BEGIN CATEGORY LINKS TABLE--> 
<table border="0" cellpadding="5" cellspacing="0" width="780">
<tr><td class="navtext" align="left" valign="top" width="135">
<?php include('fc_leftnav.php');?>
</td><td align="left" valign="top">
<!--OPEN CELL FOR FISHCART CODE--> 

<table class="text" border="0" cellpadding="0" width="100%">
<tr><td align="left" valign="top" colspan="1">

<form name="showcart" action="contribute_order.php"
 onSubmit="
 if( document.showcart.contrib.value > 99999.00 ){
  alert('<?php echo fc_text('jscontriblim'); ?>');
  return false;
 }
 ">

<?php echo fc_text('donatetext'); ?>

<p>
<?php echo fc_text('donatequote1'); ?>
<input name=contrib size=8 value="<?php printf("%.2f",$contamt); ?>" />
<?php echo fc_text('donatequote2'); ?><br />
</p>
</td></tr><tr><td valign="top" align="left" colspan="1">

<input type="hidden" name="custid" value="<?php echo $custid?>" />
<input type="hidden" name="itot" value="<?php echo $itot?>" />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
<input type="hidden" name="zflag1" value="<?php echo $zflag1?>" />
<input type="hidden" name="zid" value="<?php echo $zid?>" />
<input type="hidden" name="lid" value="<?php echo $lid?>" />
<input type="hidden" name="aid" value="<?php echo $aid?>" />
<input type="hidden" name="ccexp_years" value="<?php echo $ccexp_years?>" />

<input type="hidden" name="referer" value="<?php echo $REMOTE_ADDR?>" />
<input type="hidden" name="doit" value="1" />
<input type="submit" value="<?php echo fc_text('ordersubmit'); ?>" />
</form>

</td></tr>
<tr><td colspan="1">

<?php echo fc_text('sslorder'); ?>
<p>
<a href="<?php echo $nsecurl.$cartdir ?>/index.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><b><?php echo fc_text('zonehome'); ?></b></a>

</p>
</td></tr>
</table>
<!--CLOSE CELL/ROW/TABLE OF THE MAIN 100% WRAPPER TABLE-->
</td></tr></table>

<?php // VENDOR INFORMATION 
include ('vendinfo.php');
//END OF VENDOR INFORMATION ?>

<?php // END OF ESSENTIAL CART DISPLAY CODE ?>

<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
