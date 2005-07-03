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

if(empty($functions_inc)){
 require('./functions.php');
}

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// custid cookie is mime encoded, don't escape
$CookieCustID = getcookie("Cookie${instid}CustID");
$CookieCart   = getcookie("Cookie${instid}Cart");
$cartid = getparam('cartid');
$key1 = getparam('key1');
$zid = (int)getparam('zid');
$lid = (int)getparam('lid');
$cat = (int)getparam('cat');
$szid = (int)getparam('szid');
$nlst = (int)getparam('nlst');
$olst = (int)getparam('olst');
$oszid = (int)getparam('oszid');
$olimit = (int)getparam('olimit');
//$subzparent = (int)getparam('subzparent');
$return_product = getparam('return_product');
$option_violation = (int)getparam('option_violation');
// ==========  end of variable loading  ==========

// subzparent is never defined in a post or get submit
// is only predefined when showgeo is included from showcart
$subzparent = !empty( $subzparent ) ? (int)$subzparent : 0;

if(empty($pub_inc)){
 require('./public.php');
}
if(empty($cartid_inc)){
 require('./cartid.php');
}
if(empty($lang_inc)){
 require('./languages.php');
}
if(empty($flags_inc)){
 require('./flags.php');
}

if(empty($zflag1)){
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
}

// SQL INJECTION AVOIDENCE
$zid = (int) $zid;
$lid = (int) $lid;

// Causes preview.php to not be shown
$nukepreview=1;

// get the language information
$fcl=new FC_SQL;
$fcl->query("select langtdsp,langshow,langterr,langcopy,langterms from lang ".
	"where langid=$lid");
$fcl->next_record('langterms');
$show=$fcl->f("langshow");
$copy=$fcl->f("langcopy");
$lterms=$fcl->f("langterms");
$fcl->free_result();

$fcsz=new FC_SQL;
$fcsz->query(
	"select count(*) as cnt from subzone ".
	"where subzid=$zid and subzparent=$subzparent");
$fcsz->next_record();
$zt=$fcsz->f("cnt");
if(($zt==1)&& !($zflag1 & $flag_zonezipshowgeo)){
	$fcsz->free_result();
	// if only one shipping zone, default it.
	$fcsz->query(
		"select subzid,subzsid,subzdescr from subzone ".
		"where subzid=$zid and subzparent=$subzparent"); 
	$fcsz->next_record();
	$cat=(string)$cat;
	$szid=(int)$fcsz->f("subzsid");
	$fcsz->free_result();
	$fcsz->query(
		"update ohead set subz=$szid,shipid=0 where orderid='$cartid'");
	$fcsz->commit();
	header("Location: $nsecurl$cartdir/$show?cartid=$cartid&zid=$zid".
		"&lid=$lid&olimit=$olimit&nlst=$nlst&olst=$olst&key1=$key1".
		"&cat=$cat&szid=$szid&oszid=$szid&option_violation=$option_violation&".
		"return_product=$return_product");
	exit;
}

if ($zflag1 & $flag_zonezipshowgeo){
  $fcsz->query("select scity,sstate,szip,scountry from ohead where
	orderid='$cartid'");
  if( $fcsz->next_record() ){
	$city=stripslashes($fcsz->f("scity"));
	$state=stripslashes($fcsz->f("sstate"));
	$zip=stripslashes($fcsz->f("szip"));
	$country=stripslashes($fcsz->f("scountry"));
	$fcsz->free_result();
  }
  if( (!$city && !$state && !$zip &&!$country) && isset($CookieCustID) ){
	@list($custid,$cookie_email)=explode(":",base64_decode($CookieCustID));
	$custid=(int)$custid;
  	$fcsz->query(
  		"select custscity,custsstate,custszip,custsnatl ".
  		"from cust where custid=$custid");
	if( $fcsz->next_record() ){
		$city = stripslashes($fcsz->f("custscity"));
		$state = stripslashes($fcsz->f("custsstate"));
		$zip = stripslashes($fcsz->f("custszip"));
		$country = stripslashes($fcsz->f("custsnatl"));
	}
	$fcsz->free_result();
  }
}

// get the Web table
$fcw=new FC_SQL;
$fcw->query("select * from web where webzid=$zid and weblid=$lid"); 
$fcw->next_record();

/* unused for now
$fcv = new FC_SQL;
$fcv->query("select * from vend where vendzid=$zid"); 
*/

// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE?>

<html>
<head>
<link rel="stylesheet" ID href="style.css" type="text/css" />
</head>
<body<?
 if($fcw->f("webback")){?> background="<?php echo $fcw->f("webback")?>"<?php }
 if($fcw->f("webtext")){?> text="<?php echo $fcw->f("webtext")?>"<?php }
 if($fcw->f("weblink")){?> link="<?php echo $fcw->f("weblink")?>"<?php }
 if($fcw->f("webvlink")){?> link="<?php echo $fcw->f("webvlink")?>"<?php }
 if($fcw->f("webalink")){?> link="<?php echo $fcw->f("webalink")?>"<?php }
 if($fcw->f("webbg")){?> bgcolor="<?php echo $fcw->f("webbg")?>"<?php }
?>
 marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">

<?php // START OF ESSENTIAL CART DISPLAY CODE ?>
<!--BEGIN CATEGORY LINKS TABLE--> 
<table border="0" cellpadding="5" cellspacing="0" width="780">
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
<?php 
if($zt>0){?>
<table border="0" cellpadding="3" cellspacing="1" width="580" bgcolor="#666666">
<tr><td class="divrow" align="center" bgcolor="#333333">
<font color="#FFFFFF">
<?php echo fc_text('choosegeo'); ?>
</font>
</td></tr>
<tr><td class="showcartcell" align="center" valign="top" bgcolor="#FFFFFF">
<form name="showgeo" action="<?php echo $show?>"
 onSubmit="
 if( document.showgeo.szid.options.selectedIndex < 0 ){
   alert('<?php echo fc_text('selectsubz'); ?>');
      return false;
   }
">
<?php if ($zflag1 & $flag_zonezipshowgeo) {?>
	<?php echo fc_text('city'); ?><input name="city" size="15" maxlength="25" value="<?php echo $city  ?>" /><p></p>
	<?php echo fc_text('state'); ?><input name="state" size="15" maxlength="25" value="<?php echo $state  ?>" /><p></p>
	<?php echo fc_text('zip'); ?><input name="zip" size="5" maxlength="15" value="<?php echo  $zip ?>" /><p></p>
	<?php echo fc_text('country'); ?>
<select name="country" size="1">
<option value="">[select a country]</option>
<?php
show_countries( $zid, $lid, $country, $lang_iso );
?>
</select><p></p>
<?php } ?>
<select name="szid" size="<?php echo $zt?>" onChange="submit(); return false;">
<?php 
$fcsz->query(
 "select subzid,subzsid,subzdescr from subzone ".
 "where subzid=$zid and subzparent=$subzparent order by subzseq"); 
 while($fcsz->next_record()){?>
<option value="<?php echo $fcsz->f("subzsid")?>"><?php echo stripslashes($fcsz->f("subzdescr"))?>
</option>
<?php }?>
</select><br />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
<input type="hidden" name="zid" value="<?php echo $zid?>" />
<input type="hidden" name="lid" value="<?php echo $lid?>" />
<input type="hidden" name="oszid" value="<?php echo (int)$szid?>" />
<input type="hidden" name="olimit" value="<?php echo $olimit?>" />
<input type="hidden" name="nlst" value="<?php echo $nlst?>" />
<input type="hidden" name="olst" value="<?php echo $olst?>" />
<input type="hidden" name="key1" value="<?php echo $key1?>" />
<input type="hidden" name="cat" value="<?php echo $cat?>" />
<input type="hidden" name="option_violation" value="<?php echo $option_violation?>" />
<input type="hidden" name="return_product" value="<?php echo $return_product?>" />
<input type="submit" value="<?php echo fc_text('submitgeo'); ?>" />
</form>
</td></tr></table>
<?php }?>
<!--CLOSE CELL/ROW/TABLE OF THE MAIN 100% WRAPPER TABLE-->
</td></tr></table>
</td></tr></table>

<?php // VENDOR INFORMATION 
include ('vendinfo.php');
//END OF VENDOR INFORMATION ?>

<? // END OF ESSENTIAL CART DISPLAY CODE ?>

<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
