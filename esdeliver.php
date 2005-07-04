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
$pwuid = stripslashes(getparam('pwuid'));
$pwpw = stripslashes(getparam('pwpw'));
// ==========  end of variable loading  ==========

require('./public.php');
require('./cartid.php');
require('./languages.php');
require('./flags.php');

$fcz = new FC_SQL;
$fcz->query("select zflag1 from zone where zoneid=$zid"); 
if($fcz->next_record()){
 $zflag1=(int)$fcz->f("zflag1");
}else{
 $zflag1=0;
}

$fcw=new FC_SQL;
$fcl=new FC_SQL;
$fcv=new FC_SQL;

$showscat=(int)$showscat;

// get the user authentication
include('./pwesd.php');

// get the Web table
$fcw->query("select * from web where webzid=$zid and weblid=$lid"); 
$fcw->next_record();

// get the language templates
$fcl->query("select langtmpl,langshow,langstmpl,langterms from lang ".
	"where langid=$lid");
$fcl->next_record('langterms');
if($showscat){
	$tmpl=$fcl->f("langstmpl");
}else{
	$tmpl=$fcl->f("langtmpl");
}
$show=$fcl->f("langshow");
$lterms=$fcl->f("langterms");
$fcl->free_result();

// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE?>

<html>
<head>
<link rel="stylesheet" href="style.css" type="text/css" />
<title> <?php echo fc_text("titletag"); ?></title></head>
<body<?php 
if($fcw->f("webback")){?> background="<?php echo $fcw->f("webback")?>"<?php }
if($fcw->f("webtext")){?> text="<?php echo $fcw->f("webtext")?>"<?php }
if($fcw->f("weblink")){?> link="<?php echo $fcw->f("weblink")?>"<?php }
if($fcw->f("webvlink")){?> vlink="<?php echo $fcw->f("webvlink")?>"<?php }
if($fcw->f("webalink")){?> alink="<?php echo $fcw->f("webalink")?>"<?php }
if($fcw->f("webbg")){?> bgcolor="<?php echo $fcw->f("webbg")?>"<?php }?>
 marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">

<?php // START OF ESSENTIAL CART DISPLAY CODE ?>
<!--BEGIN CATEGORY LINKS TABLE--> 
<table border="0" cellpadding="5" cellspacing="0" width="800">
<tr><td class="navtext" align="left" valign="top" width="135">
<?php include('fc_leftnav.php');?>
</td><td align="left" valign="top">
<!--OPEN CELL FOR FISHCART CODE--> 
<table border="0" cellpadding="0" cellspacing="0" width="600">
<tr><td>
<?php
$fcesd = new FC_SQL;
$fcesp = new FC_SQL;

if( $pwuid ){	// if they gave us a username
?>

<h3><?php echo fc_text('dlheader').$pwuid ?></h3>

<?php
$fcesp->query("select pwoid,pwdescr from pw where pwuid='$pwuid'");
$fcesp->next_record();
$pwdescr=$fcesp->f('pwdescr');
$pwoid=$fcesp->f('pwoid');
$fcesp->free_result();

$fcesd->query(
	"select esdid,esdoid,esdolid,esdpurchid,esddlexp,esddlcnt,esddlmax,".
	"esddlfile from esd where esdoid='$pwoid'");
while( $fcesd->next_record() ){
	$esdid=$fcesd->f('esdid');
	$esdoid=$fcesd->f('esdoid');
	$esdolid=(int)$fcesd->f('esdolid');
	$esddlexp=(int)$fcesd->f('esddlexp');
	$esddlcnt=(int)$fcesd->f('esddlcnt');
	$esddlmax=(int)$fcesd->f('esddlmax');
	$esddlfile=urlencode($fcesd->f('esddlfile'));
	$esdpurchid=$fcesd->f('esdpurchid');
	
	$fcesp->query("select sku from oline where orderid='$esdoid'");
	$fcesp->next_record();
	$sku=$fcesp->f('sku');
	$fcesp->free_result();

	$fcesp->query("select prodname from prodlang ".
		"where prodlsku='$sku' and prodlzid=$zid and prodlid=$lid");
	$fcesp->next_record();
	$prodname=stripslashes($fcesp->f('prodname'));
	$fcesp->free_result();

	$dlrem = $esddlmax - $esddlcnt;
	if( $esddlcnt >= $esddlmax ){
	  echo $prodname.' '.fc_text('downloadmax');
	}else{
	  echo fc_text('download')."<a href=\"esdsend.php?esdid=${esdid}\">".
		   "$prodname</a><br />(${dlrem}".fc_text('downloadrem').")";
	}
	echo "\n<p></p>\n";
}
$fcesp->free_result();
?>

</td></tr>
</table>

<?php
}else{	// no username given, collect it
?>

<table border="0" cellpadding="5">
<tr><td>
<form method="post" action="<?php echo $PHP_SELF ?>">
<?php echo fc_text('dlusername') ?>
</td><td>
<input name="pwuid" size="20" /><br />
</td></tr>
<tr><td>
<?php echo fc_text('dlusername') ?>
</td><td>
<input name="pwpw" size="20" /><br />
</td></tr>
<tr><td colspan="2">
<input type="submit" value="<?php echo fc_text('dlsubmit') ?>" />
</form>
</td></tr></table>

<?php
}
?>
</td></tr></table>
<!--CLOSE CELL/ROW/TABLE OF THE MAIN 100% WRAPPER TABLE-->
</td></tr></table>
<?php // END OF ESSENTIAL CART DISPLAY CODE ?>

<?php  /* ?>
<table border="0" cellpadding="3" cellspacing="0">
<tr><td valign="top">

<i><?php echo fc_text("contactinfo"); ?></i><br />
<?php  // display the vendor contact information
$fcv->query("select * from vend where vendzid=$zid"); 
$fcv->next_record();

if($fcv->f("vendname")){ echo stripslashes($fcv->f("vendname"))?><br /><?php }
if($fcv->f("vendaddr1")){ echo stripslashes($fcv->f("vendaddr1"))?><br /><?php }
if($fcv->f("vendaddr2")){ echo stripslashes($fcv->f("vendaddr2"))?><br /><?php }?>
<?php echo stripslashes($fcv->f("vendstate"))?> <?php echo stripslashes($fcv->f("vendzip"))?>  <?php echo stripslashes($fcv->f("vendnatl"))?><br />
<?php if($fcv->f("vendphone")){ echo stripslashes($fcv->f("vendphone"))?><br /><?php }
if($fcv->f("vendfax")){ echo stripslashes($fcv->f("vendfax"))?><br /><?php }
if($fcv->f("vendemail")){?><a href="mailto:<?php echo stripslashes($fcv->f("vendemail"))?>"><?php echo stripslashes($fcv->f("vendemail"))?></a><br />
<?php }?>

</td><td>

<i><?php echo fc_text("supportinfo"); ?></i><br />
<?php  // display the vendor service information
if($fcv->f("vsvcname")){ echo stripslashes($fcv->f("vsvcname"))?><br /><?php }
if($fcv->f("vsvcaddr1")){ echo stripslashes($fcv->f("vsvcaddr1"))?><br /><?php }
if($fcv->f("vsvcaddr1")){ echo stripslashes($fcv->f("vsvcaddr1"))?><br /><?php }?>
if($fcv->f("vsvccity")){ echo stripslashes($fcv->f("vsvccity"))?>, <?php echo stripslashes($fcv->f("vsvcstate"))?> <?php echo stripslashes($fcv->f("vsvczip"))?>  <?php echo stripslashes($fcv->f("vsvcnatl"))?><br /><?php }
if($fcv->f("vsvcphone")){ echo stripslashes($fcv->f("vsvcphone"))?><br /><?php }
if($fcv->f("vsvcfax")){ echo stripslashes($fcv->f("vsvcfax"))?><br /><?php }
if($fcv->f("vsvcemail")){?><a href="mailto:<?php echo stripslashes($fcv->f("vsvcemail"))?>"><?php echo stripslashes($fcv->f("vsvcemail"))?></a><br />
<?php }?>

</td></tr></table>
<?php  */ ?>

<?php // VENDOR INFORMATION 
include ('vendinfo.php');
//END OF VENDOR INFORMATION ?>
<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
