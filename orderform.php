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
$subz = (int)getparam('subz');
$custid = (int)getparam('custid');
// ==========  end of variable loading  ==========

require('./public.php');
require('./flags.php');
require('./cartid.php');
require('./languages.php');

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
<a href="<?php echo $nsecurl ?>/"> <?php echo fc_text('homepage'); ?></a>
</p>
<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php
  exit;
}

$fao=new FC_SQL;
$fai=new FC_SQL;
$fcc=new FC_SQL;
$fcl=new FC_SQL;
$fco = new FC_SQL;
$fcol = new FC_SQL;
$fpo=new FC_SQL;
$fps=new FC_SQL;
$fpr = new FC_SQL;
$fpl = new FC_SQL;
$fct = new FC_SQL;
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
<center><p><?php echo fc_text('cartempty'); ?><p>
<a href="<?php echo $nsecurl.$cartdir ?>/index.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>">
<b><?php echo fc_text('zonehome'); ?></b></a></center>
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
//if(empty($shipcalc)){
//	echo fc_text('noshipcalc').'<br />';
//}
// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE?>

<html>
<head>
<link rel="stylesheet" ID href="style.css" type="text/css" />
<title><?php echo fc_text('titletag'); ?></title>
</head>

<body<?php 
 if($fcw->f("webback")){?> background="<?php echo $fcw->f("webback")?>"<?php }
 if($fcw->f("webtext")){?> text="<?php echo $fcw->f("webtext")?>"<?php }
 if($fcw->f("weblink")){?> link="<?php echo $fcw->f("weblink")?>"<?php }
 if($fcw->f("webvlink")){?> link="<?php echo $fcw->f("webvlink")?>"<?php }
 if($fcw->f("webalink")){?> link="<?php echo $fcw->f("webalink")?>"<?php }
 if($fcw->f("webbg")){?> bgcolor="<?php echo $fcw->f("webbg")?>"<?php }
?>
 marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">

<?php // START OF ESSENTIAL CART DISPLAY CODE ?>
<script src="js_orderform.js"></script>
<div class="header" align="center"><br />
<?php echo fc_text('orderinfo'); ?></div>

<center>
<form method="post" name="oform" action="<?php echo $securl.$secdir.'/'.$proc?>"
 onSubmit="
  if( document.oform.onoff[0].checked==false &&
      document.oform.onoff[1].checked==false ){
   alert('<?php echo fc_text('jsonoff'); ?>');
   return false;
<?php if( $zflag1 & $flag_zonetclink ){ ?>
  }else if( document.oform.approvetc.checked==false ){
   	alert('<?php echo fc_text('jsapprovetc'); ?>');
	return false;
<?php } ?>
  }else if( document.oform.onoff[0].checked==true ){
   // only check cc info if online is checked
   if( document.oform.ccexp_month.options.selectedIndex  == 0 ){
    alert('<?php echo fc_text('jsccexp'); ?>');
    return false;
   }else if( document.oform.ccexp_year.options.selectedIndex  == 0 ){
    alert('<?php echo fc_text('jsccexp'); ?>');
    return false;
   }else if( document.oform.cc_name.value == '' ){
    alert('<?php echo fc_text('jsccname'); ?>');
    return false;
   }else if( document.oform.cc_number.value == '' ||
			 !mod10_verify( document.oform.cc_number.value ) ){
    alert('<?php echo fc_text('jsccnum'); ?>');
    return false;
   }else if( !document.oform.cctype[0].checked &&
             !document.oform.cctype[1].checked &&
             !document.oform.cctype[2].checked &&
             !document.oform.cctype[3].checked ){
    alert('<?php echo fc_text('jscctype'); ?>');
    return false;
   }
  }
  if( document.oform.billing_email.value == '' ){
   alert('<?php echo fc_text('jsbemail'); ?>');
   return false;
  }else if( document.oform.billing_first.value == '' ){
   alert('<?php echo fc_text('jsbfname'); ?>');
   return false;
  }else if( document.oform.billing_last.value == '' ){
   alert('<?php echo fc_text('jsblname'); ?>');
   return false;
  }else if( document.oform.billing_address1.value == '' ){
   alert('<?php echo fc_text('jsbaddr'); ?>');
   return false;
  }else if( document.oform.billing_city.value == '' ){
   alert('<?php echo fc_text('jsbcity'); ?>');
   return false;
  }else if( document.oform.billing_state.value == '' ){
   alert('<?php echo fc_text('jsbstate'); ?>');
   return false;
  }else if( document.oform.billing_zip.value == '' ){
   alert('<?php echo fc_text('jsbzip'); ?>');
   return false;
  }else if( document.oform.billing_country.options.selectedIndex == 0 ){
   alert('<?php echo fc_text('jsbcountry'); ?>');
   return false;
  }else if( document.oform.shipping_address1.value != ''  &&
             document.oform.shipping_country.options.selectedIndex == 0 ){
   alert('<?php echo fc_text('jsscountry'); ?>');
   return false;
  }else{
   alert('<?php echo fc_text('jsplaced'); ?>');
   return true;
  }
">

<table class="text" border="0" cellpadding="4" cellspacing="1" width="600" bgcolor="#666666">
<tr><td class="divrow" align="center" valign="top" colspan="4" bgcolor="#CCCCCC">
<b><?php echo fc_text('prodinfo'); ?></b>
</td></tr>

<?php // now show the product display table
$allowupdate=0;
require('proddisp.php');
$fco->free_result();
$fcol->free_result();
?>

<tr><td class="divrow" colspan="4" align="center" bgcolor="#CCCCCC">
<b><?php echo fc_text('billinfo'); ?></b><br />
</td></tr>
<tr><td class="orderformcell" colspan="4" bgcolor="#FFFFFF">
<?php echo fc_text('reqtext'); ?>
<p></p>

<?php echo fc_text('emailaddr') . fc_text('reqflag'); ?><br />
<input type="text" name="billing_email" size="40"
 value="<?php if($cc){echo stripslashes($fcc->f("custbemail"));}?>" /><br />

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('salutation'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('firstname') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('miname'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('lastname') . fc_text('reqflag'); ?><br />
</td></tr>

<tr><td class="orderformcell" bgcolor="#FFFFFF">
<select name="billing_sal" size="1">
<option value=""><?php echo fc_text('saluteopt'); ?></option>
<?php
$i=0;
$cnt=count($salutearray);
while( $i < $cnt ){
	$tl=$salutearray[$i];
	echo "<option value=\"$tl\">$tl\n";?>
	</option>
<?php
	$i++;
}
?>
</select>
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_first" size="15"
 value="<?php if($cc){echo stripslashes($fcc->f("custbfname"));}?>" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_mi" size="2"
 value="<?php if($cc){echo stripslashes($fcc->f("custbmname"));}?>" /><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_last" size="20"
 value="<?php if($cc){echo stripslashes($fcc->f("custblname"));}?>" /><br />
</td></tr>

</table>

<?php echo fc_text('address') . fc_text('reqflag'); ?><br />
<input type="text" name="billing_address1" size="40"
 value="<?php if($cc){echo stripslashes($fcc->f("custbaddr1"));}?>" /><br />
<input type="text" name="billing_address2" size="40"
 value="<?php if($cc){echo stripslashes($fcc->f("custbaddr2"));}?>" /><br />

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('city') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('state') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('zip') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('country') . fc_text('reqflag'); ?><br />
</td></tr>
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_city" size="15"
 value="<?php if($cc){echo stripslashes($fcc->f("custbcity"));}?>" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_state" size="3"
 value="<?php if($cc){echo stripslashes($fcc->f("custbstate"));}?>" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_zip" size="5"
 value="<?php if($cc){echo stripslashes($fcc->f("custbzip"));}?>" />-
<input type="text" name="billing_zip4" size="4"
 value="<?php if($cc){echo stripslashes($fcc->f("custbzip4"));}?>" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php /*
<input type="text" name="billing_country" size="3"
 value="<?php if($cc){echo stripslashes($fcc->f("custbnatl"));}?>" /><br />
 */ ?>
<select name="billing_country" size="1">
<option value="">[  <?php echo fc_text('selectctry');?>  ]</option>
<?php
if($cc){ $billiso=$fcc->f("custbnatl"); }else{ $billiso=''; }
show_countries( $zid, $lid, $billiso, $lang_iso );
?>
</select>
</td></tr>
</table>

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('dayphone') . fc_text('reqflag'); ?><br />
<input type="text" name="billing_acode" size="3"
 value="<?php if($cc){echo stripslashes($fcc->f("custbacode"));}?>" />
<input type="text" name="billing_phone" size="8"
 value="<?php if($cc){echo stripslashes($fcc->f("custbphone"));}?>" /><br />
</td></tr>
</table>

</td></tr>

<?php if (($ttotal || $mtotal) && ($zflag1 & $flag_zonecc) ) {
  //show CC if nonzero total ?>
<tr><td class="divrow" colspan="4" align="center" bgcolor="#CCCCCC">
<b><?php echo fc_text('creditinfo'); ?></b><br />
</td></tr>

<tr><td class="orderformcell" align="left" valign="middle" colspan="1" bgcolor="#FFFFFF">
<?php echo fc_text('ccname') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" align="left" valign="middle" colspan="3" bgcolor="#FFFFFF">
<input type="text" name="cc_name" size="40" /><br />
</td></tr>

<tr><td class="orderformcell" valign="middle" align="left" colspan="1" bgcolor="#FFFFFF">
<?php echo fc_text('ccnumber') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" align="left" valign="middle" colspan="3" bgcolor="#FFFFFF">
<input type="text" name="cc_number" size="21" /><br />
</td></tr>

<tr><td class="orderformcell" valign="middle" align="left" colspan="1" bgcolor="#FFFFFF">
<a href="javascript:rs('pab','cvvtext.php?lid=<?php echo $lid;?>', 325, 225, 0)" target="_top" border="0"><?php echo fc_text('cvvnumber');?></a><br />
</td><td class="orderformcell" align="left" valign="middle" colspan="3" bgcolor="#FFFFFF">
<input type="text" name="cc_cvv" size="4" /><br />
</td></tr>

<tr><td class="orderformcell" align="left" valign="middle" colspan="2" bgcolor="#FFFFFF">

<?php // ADD ALL CREDIT CARD TYPES HERE
echo fc_text('cctype') . fc_text('reqflag'); ?><br />
<input type="radio" name="cctype" value="Visa" />VISA<br />
<input type="radio" name="cctype" value="Mastercard" />Mastercard<br />
<input type="radio" name="cctype" value="Discover" />Discover<br />
<input type="radio" name="cctype" value="American Express" />American Express<br />

</td><td class="orderformcell" align="center" valign="middle" colspan="2" bgcolor="#FFFFFF">
<?php echo fc_text('ccexpire'); ?><br />

<table class="text" border="0">
<tr><td class="orderformcell" align="center" valign="top" bgcolor="#FFFFFF">
<?php echo fc_text('month') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" align="center" valign="top" bgcolor="#FFFFFF">
<?php echo fc_text('year') . fc_text('reqflag'); ?><br />
</td></tr><tr><td class="orderformcell" align="center" valign="top" bgcolor="#FFFFFF">
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
</td><td class="orderformcell" align="center" valign="top" bgcolor="#FFFFFF">
<select name="ccexp_year" size="1">
<option value="0">[year]</option>
<?php
$i=0;
$thisyr=date("Y",time());
while($i<$ccexp_years){
 echo '<option value="'.$thisyr.'">'.$thisyr."\n";?>
 </option>
 <?php
 $thisyr++;
 $i++;
} ?>
</select>
<br />
</td></tr>
</table>

</td></tr>
<?php } ?>

<tr><td class="divrow" colspan="4" align="center" bgcolor="#CCCCCC">
<b><?php echo fc_text('shipinfo'); ?></b>
<br />
</td></tr>

<tr><td class="orderformcell" colspan="4" bgcolor="#FFFFFF">

<?php echo fc_text('emailaddr') . fc_text('reqflag'); ?><br />
<input type="text" name="shipping_email" size="40"
 value="<?php if($cc){echo stripslashes($fcc->f("custsemail"));}?>" /><br />

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('salutation'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('firstname') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('miname'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('lastname') . fc_text('reqflag'); ?><br />
</td></tr>
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<select name="shipping_sal" size="1">
<option value=""><?php echo fc_text('saluteopt'); ?>
</option>
<?php
$i=0;
$cnt=count($salutearray);
while( $i < $cnt ){
	$tl=$salutearray[$i];
	echo "<option value=\"$tl\">$tl\n";?>
	</option>
	<?php
	$i++;
}
?>
</select>
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_first" size="15"
 value="<?php if($cc){echo stripslashes($fcc->f("custsfname"));}?>" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_mi" size="2"
 value="<?php if($cc){echo stripslashes($fcc->f("custsmname"));}?>" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_last" size="20"
 value="<?php if($cc){echo stripslashes($fcc->f("custslname"));}?>" /><br />
</td></tr>
</table>

<?php echo fc_text('address') ?><br />
<input type="text" name="shipping_address1" size="40"
 value="<?php if($cc){echo stripslashes($fcc->f("custsaddr1"));}?>" /><br />
<input type="text" name="shipping_address2" size="40"
 value="<?php if($cc){echo stripslashes($fcc->f("custsaddr2"));}?>" /><br />

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('city') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('state') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('zip') . fc_text('reqflag'); ?><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<?php echo fc_text('country') . fc_text('reqflag'); ?><br />
</td></tr>
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_city" size="15"
 value="<?php if($cc){echo stripslashes($fcc->f("custscity"));}?>" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_state" size="3"
 value="<?php if($cc){echo stripslashes($fcc->f("custsstate"));}?>" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_zip" size="5"
 value="<?php if($cc){echo stripslashes($fcc->f("custszip"));}?>" />-<input
 type="text" name="shipping_zip4" size="4"
 value="<?php if($cc){echo stripslashes($fcc->f("custszip4"));}?>" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<select name="shipping_country" size="1">
<option value="">[  <?php echo fc_text('selectctry');?>  ]</option>
<?php
if($cc){ $shipiso=$fcc->f("custsnatl"); }else{ $shipiso=''; }
show_countries( $zid, $lid, $shipiso, $lang_iso );
?>
</select>
</td></tr>
</table>

<?php echo fc_text('dayphone') ?><br />
<input type="text" name="shipping_acode" size="3"
 value="<?php if($cc){echo stripslashes($fcc->f("custsacode"));}?>" />
<input type="text" name="shipping_phone" size="8"
 value="<?php if($cc){echo stripslashes($fcc->f("custsphone"));}?>" /><br />

</td></tr>
<tr><td class="divrow" align="center" colspan="4" bgcolor="#CCCCCC">
<b><?php echo fc_text('ordermethod') . fc_text('reqflag'); ?></b><br />
</td></tr>
<tr><td class="orderformcell" align="center" valign="middle" colspan="4" bgcolor="#FFFFFF">

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" align="center" valign="middle" colspan="1" width="25%" bgcolor="#FFFFFF">

<input type="radio"  name="onoff" value="on" />
<b><?php echo fc_text('online'); ?></b><br />

</td><td class="orderformcell" align="left" valign="top" colspan="1" width="25%" bgcolor="#FFFFFF">

<?php echo fc_text('onlinetext'); ?><br />

</td><td class="orderformcell" align="center" valign="middle" colspan="1" width="25%" bgcolor="#FFFFFF">

<input type="radio"  name="onoff" value="off" />
<b><?php echo fc_text('offline'); ?></b><br />

</td><td class="orderformcell" align="left" valign="top" colspan="1" width="25%" bgcolor="#FFFFFF">

<?php echo fc_text('offlinetext'); ?><br />

</td></tr>
</table>

</td></tr>

<tr><td class="orderformcell" align="center" colspan="4" bgcolor="#FFFFFF">
<input type="checkbox" name="promoemail" value="1" checked />
<?php echo fc_text('promoemail'); ?><br />
</td></tr>

<tr><td class="orderformcell" align="center" colspan="4" bgcolor="#FFFFFF">
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

<?php if( $zflag1 & $flag_zonetclink ){ ?>
<tr><td class="orderformcell" align="center" colspan="4" bgcolor="#FFFFFF">
<a href="terms.php" target="_blank"><?php echo fc_text('termscon'); ?></a><br />
<input type="checkbox" name="approvetc" value="1" />
<?php echo fc_text('approvetc'); ?><br />
</td></tr>
<?php } // end of zonelinktc ?>

<tr><td class="orderformcell" align="center" valign="top" colspan="2" bgcolor="#FFFFFF">

<input type="hidden" name="custid" value="<?php echo $custid?>" />
<input type="hidden" name="itot" value="<?php echo $itot?>" />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
<input type="hidden" name="zflag1" value="<?php echo $zflag1?>" />
<input type="hidden" name="zid" value="<?php echo $zid?>" />
<input type="hidden" name="lid" value="<?php echo $lid?>" />
<input type="hidden" name="aid" value="<?php echo $aid?>" />
<input type="hidden" name="ttotal" value="<?php echo $ttotal?>" />
<input type="hidden" name="ptotal" value="<?php echo $ptotal?>" />
<input type="hidden" name="ccexp_years" value="<?php echo $ccexp_years?>" />

<input type="hidden" name="referer" value="<?php echo $REMOTE_ADDR?>" />
<input type="submit" value="<?php echo fc_text('ordersubmit'); ?>" />

</td><td class="orderformcell" align="center" valign="top" colspan="2" bgcolor="#FFFFFF">

<input type="reset"  value="<?php echo fc_text('clearform'); ?>" />

</td></tr>
</table>
</form>
</center>
<center>
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td class="navtext">
<div id="button" align="center">
<ul>
<li><a href="<?php echo $nsecurl.$cartdir ?>/index.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><?php echo fc_text('zonehome'); ?></a></li>
</ul>
</div>
</td></tr></table>
</center>

<?php // END OF ESSENTIAL CART DISPLAY CODE ?>

<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
