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

require_once( BITCART_PKG_PATH.'functions.php');

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
$ccexp_years = (int)getparam('ccexp_years');

$mode = getparam('mode');
// ==========  end of variable loading  ==========

require_once( BITCART_PKG_PATH.'public.php');
require_once( BITCART_PKG_PATH.'cartid.php');
require_once( BITCART_PKG_PATH.'languages.php');
require_once( BITCART_PKG_PATH.'flags.php');

$fcl=new FC_SQL;

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

// get the language templates
$fcl->query("select langterms,langcopy,langordr from lang where langid=$lid");
$fcl->next_record('langterms');
if( !empty( $showscat ) ){
	$tmpl=stripslashes($fcl->f('langstmpl'));
}else{
	$tmpl=stripslashes($fcl->f('langtmpl'));
}
$lterms=$fcl->f('langterms');
$copy=$fcl->f('langcopy');
$ordr=$fcl->f('langordr');
$fcl->free_result();

// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE ?>

<html>
 <head>
 <meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
 <title><?php echo fc_text('termscon');?></title>
 <link ID href="style.css" type="text/css" rel="StyleSheet">
 </head>
 <body bgcolor="#FFFFFF" link="#990000" alink="#990000" vlink="#990000" leftmargin="0" marginheight="0" marginwidth="0" topmargin="0">

<?php // START OF ESSENTIAL CART DISPLAY CODE ?>
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
 <table width="100%" align="center" cellpadding="5" cellspacing="0">

<?php
if( $zflag1 & $flag_zonetcpage ){
?>
<form method="post" name="terms" action="<?php echo $securl.$secdir.'/'.$ordr?>"
 onSubmit="
  if( document.terms.approvetc.checked==false ){
   	alert('<?php echo fc_text('jsapprovetc'); ?>');
	return false;
  }
 ">
<?php
}
?>

  <tr>
   <td class="text" align="left">

<?php echo "$lterms";?>

   </td>
  </tr>

<?php if( $zflag1 & $flag_zonetcpage ){ ?>
  <tr><td align="left">
   <div class="text">
   <input type="checkbox" name="approvetc" value="1" /> <?php echo fc_text('approvetc'); ?><br />
   <input type="hidden" name="zid" value="<?php echo $zid; ?>" />
   <input type="hidden" name="lid" value="<?php echo $lid; ?>" />
   <input type="hidden" name="subz" value="<?php echo $subz; ?>" />
   <input type="hidden" name="cartid" value="<?php echo $cartid; ?>" />
   <input type="hidden" name="ccexp_years" value="<?php echo $ccexp_years; ?>" />
   <input type="submit" value="<?php echo fc_text('ordersubmit'); ?>" /><br />
   </form>
   </div>
  </td></tr>
<?php } // end of zonetcpage ?>

 </table>
<!--CLOSE CELL/ROW/TABLE OF THE MAIN 100% WRAPPER TABLE-->
</td></tr></table>
</td></tr></table>

<?php // VENDOR INFORMATION 
include ('vendinfo.php');
//END OF VENDOR INFORMATION ?>

<?php // END OF ESSENTIAL CART DISPLAY CODE ?>


<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
