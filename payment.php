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

// This file is for a payment order; no products are shown,
// and other in progress orders are ignored.

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
// ==========  end of variable loading  ==========

require('./public.php');
require('./flags.php');
require('./cartid.php');
require('./languages.php');

// get the language information
$fcl=new FC_SQL;
$fcl->query("select langtdsp,langshow,langterr,langcopy,langterms from lang ".
	"where langid=$lid");
$fcl->next_record('langterms');
$show=$fcl->f("langshow");
$copy=$fcl->f("langcopy");
$lterms=$fcl->f("langterms");
$fcl->free_result();

$fcc = new FC_SQL;
$fcw = new FC_SQL;
$ccexp_years = 8;
$cc = 0;

if(isset($CookieCustID)){
    list($custid,$cookie_email)=explode(":",base64_decode($CookieCustID));
	$custid=(int)$custid;
	if( $custid ){
	  $fcc->query("select * from cust where custid=$custid");
	  $cc = $fcc->next_record();
	}
}

$fcz=new FC_SQL;
$fcz->query("select zonecurrsym,zflag1 from zone where zoneid=$zid"); 
if($fcz->next_record()){
 $csym=stripslashes($fcz->f("zonecurrsym"));
 $csym=trim($csym);
 $zflag1=$fcz->f("zflag1");
}else{
 $csym="";
 $zflag1=0;
}
$fcz->free_result();

if( $zflag1 & $flag_zonepwcatalog ){
	// password controlled access
	require('./pw.php');
}

// get the language templates
$fcl=new FC_SQL;
$fcl->query(
 "select langproc,langcopy,langterms from lang where langid=$lid");
$fcl->next_record();
$proc=$fcl->f("langproc");
$copy=$fcl->f("langcopy");
$lterms=$fcl->f("langterms");
$fcl->free_result();
// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE ?>
<html>
<head>
<link rel="stylesheet" ID href="style.css" type="text/css" />
<title>
</title>
</head>

<body<?php 
 if($fcw->f("webback")){?> background="<?php echo $fcw->f("webback")?>"<?php }
 if($fcw->f("webtext")){?> text="<?php echo $fcw->f("webtext")?>"<?php }
 if($fcw->f("weblink")){?> link="<?php echo $fcw->f("weblink")?>"<?php }
 if($fcw->f("webvlink")){?> link="<?php echo $fcw->f("webvlink")?>"<?php }
 if($fcw->f("webalink")){?> link="<?php echo $fcw->f("webalink")?>"<?php }
 if($fcw->f("webbg")){?> bgcolor="<?php echo $fcw->f("webbg")?>"<?php }
?>>
<h2 align="center"></h2>
<h3 align="center"><?php echo fc_text('payment'); ?></h3>

<center>

<?php // START OF ESSENTIAL CART DISPLAY CODE ?>
<!--BEGIN CATEGORY LINKS TABLE--> 
<table border="0" cellpadding="5" cellspacing="0" width="780" class="text">
<tr><td class="navtext" align="left" valign="top" width="135">
<?php include('fc_leftnav.php');?>
</td><td align="left" valign="top">
<!--OPEN CELL FOR FISHCART CODE--> 
<table class="text" border="0" cellpadding="0" cellspacing="0" width="580">

<!-- FIRST COLUMN -->
<tr><td align="left" valign="top" width="10">
<img src="clearpixel.gif" width="10" height="1" /></td>
<!-- SECOND COLUMN -->
<td valign="top">

<table border="0" cellpadding="5" cellspacing="1" width="580" bgcolor="#666666" class="text">
<tr><td class="divrow" colspan="2" align="left" valign="middle" bgcolor="#FFFFFF">
<b><?php echo fc_text('paymentamount'); ?></b><br />
</td><td class="paymentcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">

<form method="post" name="oform" action="<?php echo $securl.$secdir.'/'.$proc ?>"
 onSubmit="
  if( document.oform.payment.value == '' ){
   alert('<?php echo fc_text('jspayment'); ?>');
   return false;
  }else if( document.oform.payment.value > 99999.00 ){
   alert('<?php echo fc_text('jspaymentlim'); ?>');
   return false;
  } else if( document.oform.billing_country.options.selectedIndex == 0 ){
   alert('<?php echo fc_text('jscountry'); ?>');
   return false;
  } else if( document.oform.ccexp_month.options.selectedIndex == 0 ){
   alert('<?php echo fc_text('jsccexp'); ?>');
   return false;
  } else if( document.oform.ccexp_year.options.selectedIndex == 0 ){
   alert('<?php echo fc_text('jsccexp'); ?>');
   return false;
  } else if( document.oform.cc_name.value == '' ){
   alert('<?php echo fc_text('jsccname'); ?>');
   return false;
  } else if( document.oform.cc_number.value == '' ){
   alert('<?php echo fc_text('jsccnum'); ?>');
   return false;
  } else if( document.oform.billing_email.value == '' ){
   alert('<?php echo fc_text('jsbemail'); ?>');
   return false;
  } else if( document.oform.billing_first.value == '' ){
   alert('<?php echo fc_text('jsbfname'); ?>');
   return false;
  } else if( document.oform.billing_last.value == '' ){
   alert('<?php echo fc_text('jsblname'); ?>');
   return false;
  } else if( document.oform.billing_address1.value == '' ){
   alert('<?php echo fc_text('jsbaddr'); ?>');
   return false;
  } else if( document.oform.billing_city.value == '' ){
   alert('<?php echo fc_text('jsbcity'); ?>');
   return false;
  } else if( document.oform.billing_state.value == '' ){
   alert('<?php echo fc_text('jsbstate'); ?>');
   return false;
  } else if( document.oform.billing_zip.value == '' ){
   alert('<?php echo fc_text('jsbzip'); ?>');
   return false;
  } else if( !document.oform.cctype[0].checked &&
             !document.oform.cctype[1].checked &&
             !document.oform.cctype[2].checked &&
             !document.oform.cctype[3].checked ){
   alert('<?php echo fc_text('jscctype'); ?>');
   return false;
  }else{
   alert('<?php echo fc_text('jsplaced'); ?>');
   return true;
  }
 ">
<?php echo $csym?><input type="text" name="payment" size="8" />

</td></tr>
<tr><td class="paymentcell" colspan="4" bgcolor="#FFFFFF">
<?php echo fc_text('paymentinv') ?><br>
<textarea name="payinv" rows="4" cols="50" wrap="physical"></textarea>
</td></tr>
<tr><td class="divrow" class="divrow" colspan="4" align="center" bgcolor="#CCCCCC">
<b><?php echo fc_text('billinfo'); ?></b><br />
</td></tr>
<tr><td class="paymentcell" colspan="4" bgcolor="#FFFFFF">
<?php echo fc_text('reqtext'); ?>
<p>

<?php echo fc_text('emailaddr') . fc_text('reqflag'); ?><br />
<input type="text" name="billing_email" size="40" maxsize="30"
 value="<?php if($cc){echo $fcc->f("custbemail");}?>" /><br />

<table border="0" cellpadding="0" class="text">
<tr><td class="paymentcell" bgcolor="#FFFFFF">
<?php echo fc_text('firstname') . fc_text('reqflag'); ?><br />
</td><td class="paymentcell" bgcolor="#FFFFFF">
<?php echo fc_text('lastname') . fc_text('reqflag'); ?><br />
</td></tr>
<tr><td class="paymentcell" bgcolor="#FFFFFF">
<input type="text" name="billing_first" size="15" maxsize="15"
 value="<?php if($cc){echo $fcc->f("custbfname");}?>" />
</td><td class="paymentcell" bgcolor="#FFFFFF">
<input type="text" name="billing_last" size="20" maxsize="20"
 value="<?php if($cc){echo $fcc->f("custblname");}?>" /><br />
</td></tr>
</table>

<?php echo fc_text('address') . fc_text('reqflag'); ?><br />
<input type="text" name="billing_address1" size="40" maxsize="30"
 value="<?php if($cc){echo $fcc->f("custbaddr1");}?>" /><br />
<input type="text" name="billing_address2" size="40" maxsize="30"
 value="<?php if($cc){echo $fcc->f("custbaddr2");}?>" /><br />

<table border="0" cellpadding="0" class="text">
<tr><td class="paymentcell" bgcolor="#FFFFFF">
<?php echo fc_text('city') . fc_text('reqflag'); ?><br />
</td><td class="paymentcell" bgcolor="#FFFFFF">
<?php echo fc_text('state') . fc_text('reqflag'); ?><br />
</td><td class="paymentcell" bgcolor="#FFFFFF">
<?php echo fc_text('zip') . fc_text('reqflag'); ?><br />
</td><td class="paymentcell" bgcolor="#FFFFFF">
<?php echo fc_text('country') . fc_text('reqflag'); ?><br />
</td></tr>
<tr><td class="paymentcell" bgcolor="#FFFFFF">
<input type="text" name="billing_city" size="30" maxsize="30"
 value="<?php if($cc){echo $fcc->f("custbcity");}?>" />
</td><td class="paymentcell" bgcolor="#FFFFFF">
<input type="text" name="billing_state" size="3" maxsize="2"
 value="<?php if($cc){echo $fcc->f("custbstate");}?>" />
</td><td class="paymentcell" bgcolor="#FFFFFF">
<input type="text" name="billing_zip" size="11" maxsize="10"
 value="<?php if($cc){echo $fcc->f("custbzip");}?>" />
</td><td class="paymentcell" bgcolor="#FFFFFF">
<select name="billing_country" size="1">
<option value="">[  <?php echo fc_text('selectctry');?>  ]</option>
<?php
if($cc){ $billiso=$fcc->f("custbnatl"); }else{ $billiso=''; }
show_countries( $zid, $lid, $billiso, $lang_iso );
?>
</select>
</td></tr>
</table>

<table border="0" cellpadding="0" class="text">
<tr><td class="paymentcell" bgcolor="#FFFFFF">
<?php echo fc_text('dayphone') . fc_text('reqflag'); ?><br />
<input type="text" name="billing_acode" size="3" maxsize="3"
 value="<?php if($cc){echo $fcc->f("custbacode");}?>" />
<input type="text" name="billing_phone" size="8" maxsize="7"
 value="<?php if($cc){echo $fcc->f("custbphone");}?>" /><br />
</td></tr>
</table>

</td></tr>
<tr><td class="divrow" colspan="4" align="center" bgcolor="#CCCCCC">
<b><?php echo fc_text('creditinfo'); ?></b><br />
</td></tr>
<tr><td class="paymentcell" align="left" valign="middle" colspan="2" bgcolor="#FFFFFF">

<?php echo fc_text('ccname') . fc_text('reqflag'); ?><br />
<input type="text" name="cc_name" size="40" maxsize="30" /><br />

</td><td class="paymentcell" colspan="2" valign="middle" align="center" bgcolor="#FFFFFF">

<?php echo fc_text('ccnumber') . fc_text('reqflag'); ?><br />
<input type="text" name="cc_number" size="21" maxsize="19" /><br />

</td></tr>
<tr><td class="paymentcell" align="left" valign="middle" colspan="2" bgcolor="#FFFFFF">

<?php // ADD ALL CREDIT CARD TYPES HERE ?>
<?php echo fc_text('cctype') . fc_text('reqflag'); ?><br />
<input type="radio" name="cctype" value="Visa" />VISA<br />
<input type="radio" name="cctype" value="Mastercard" />Mastercard<br />
<input type="radio" name="cctype" value="Discover" />Discover<br />
<input type="radio" name="cctype" value="American Express" />American Express<br />

</td><td class="paymentcell" align="center" valign="middle" colspan="2" bgcolor="#FFFFFF">

<?php echo fc_text('ccexpire'); ?><br />
<table border="0" class="text">
<tr><td class="paymentcell" align="center "valign="top" bgcolor="#FFFFFF">
<?php echo fc_text('month') . fc_text('reqflag'); ?><br />
</td><td class="paymentcell" align="center" valign="top" bgcolor="#FFFFFF">
<?php echo fc_text('year') . fc_text('reqflag'); ?><br />
</td></tr><tr><td class="paymentcell" align="center" valign="top" bgcolor="#FFFFFF">
<select name="ccexp_month" size="1">
<option value="0">[month]</option>
<?php
$i=1;
while($i<13){
 $mn=sprintf("%02d",$i);
 echo '<option value="'.$mn.'">'.$mn."\n";?>
 </option>
 <?php
 $i++;
} ?>
</select>
<br />
</td><td class="paymentcell" align="center" valign="top" bgcolor="#FFFFFF">
<select name="ccexp_year" size="1">
<option value="0">[year]</option>
<?php
$i=1;
$thisyr=date("Y",time());
while($i<10){
 echo '<option value="'.$thisyr.'">'.$thisyr."\n";?>
 </option>
 <?php
 $thisyr++;
 $i++;
} ?>
</select>
</td></tr>
</table>

</td></tr>

<tr><td class="paymentcell" align="center" colspan="4" bgcolor="#FFFFFF">
<?php
if( isset($CookieCustID) ){
 $checked = ' checked';
}else{
 $checked = '';
}
?>
<input type="checkbox" name="retain_addr" value="1"<?php echo $checked; ?> />
<?php echo fc_text('retain_addr'); ?><br />
</td></tr>

<tr><td class="paymentcell" colspan="4" align="center" bgcolor="#FFFFFF">

<input type="hidden" name="shipping_first" 
 value="<?php if($cc){echo $fcc->f("custsfname");}?>" />
<input type="hidden" name="shipping_last"  
 value="<?php if($cc){echo $fcc->f("custslname");}?>" />
<input type="hidden" name="shipping_address1" 
 value="<?php if($cc){echo $fcc->f("custsaddr1");}?>" />
<input type="hidden" name="shipping_address2" 
 value="<?php if($cc){echo $fcc->f("custbsaddr2");}?>" />
<input type="hidden" name="shipping_city" 
 value="<?php if($cc){echo $fcc->f("custscity");}?>" />
<input type="hidden" name="shipping_state" 
 value="<?php if($cc){echo $fcc->f("custsstate");}?>" />
<input type="hidden" name="shipping_zip" 
 value="<?php if($cc){echo $fcc->f("custszip");}?>" />
<input type="hidden" name="shipping_country" 
 value="<?php if($cc){echo $fcc->f("custsnatl");}?>" />

<input type="hidden" name="shipping_acode" 
 value="<?php if($cc){echo $fcc->f("custsacode");}?>" />
<input type="hidden" name="shipping_phone" 
 value="<?php if($cc){echo $fcc->f("custsphone");}?>" />

<input type="hidden" name="onoff"   value="on" />
<input type="hidden" name="stotal"  value="0.0" />
<input type="hidden" name="shamt"   value="0.0" />
<input type="hidden" name="stax"    value="0.0" />
<input type="hidden" name="ototal"  value="0.0" />
<input type="hidden" name="ttotal"  value="0.0" />
<input type="hidden" name="custid"  value="<?php echo $custid?>" />
<input type="hidden" name="itot"    value="0" />
<input type="hidden" name="cartid"  value="<?php echo $cartid?>" />
<input type="hidden" name="zid"     value="<?php echo $zid?>" />
<input type="hidden" name="lid"     value="<?php echo $lid?>" />
<input type="hidden" name="referer" value="<?php echo $REMOTE_ADDR?>" />
<input type="hidden" name="ccexp_years" value="<?php echo $ccexp_years?>" />
<input type="hidden" name="payment_only" value="1" />

<input type="submit" value="<?php echo fc_text('paymentsubmit'); ?>" />
</form>

</td></tr></table>
<!--CLOSE CELL/ROW/TABLE OF THE MAIN 100% WRAPPER TABLE-->
</td></tr></table>
</td></tr></table>

<?php // VENDOR INFORMATION 
include ('vendinfo.php');
//END OF VENDOR INFORMATION ?>

<?php // END OF ESSENTIAL CART DISPLAY CODE ?>

</center>
<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
