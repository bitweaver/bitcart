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

header("Pragma: no-cache");
Header("Expires: 0");
header("Cache-control: No-Cache");

require_once( '../bit_setup_inc.php' );

require('./functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// custid cookie is mime encoded, don't escape
$CookieCustID = getcookie("Cookie${instid}CustID");
$CookieCart   = getcookie("Cookie${instid}Cart");
$zid = (int)getparam('zid');
$lid = (int)getparam('lid');
// ==========  end of variable loading  ==========

if( empty($pub_inc) ) {
 require('./public.php');
}
require('./cartid.php');
require('./languages.php');

// FC_SQLs for category links and aux links
$fcclnks = new FC_SQL; // category links
$fcal = new FC_SQL; // aux links
$fcl = new FC_SQL; // lang table

// get the language templates
$fcl->query("select langcopy,langterms from lang where langid=$lid");
$fcl->next_record('langterms');
$copy=$fcl->f("langcopy");
$lterms=$fcl->f("langterms");
$fcl->free_result();

// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE ?>

<html>
<head>
<link rel="stylesheet" ID href="style.css" type="text/css" />
</head>
<body<?php 
 $fcw = new FC_SQL;
 $fcw->query("select * from web where webzid=$zid and weblid=$lid"); 
 $fcw->next_record();
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
<table border="0" cellpadding="5" cellspacing="0" width="800">
<tr><td class="navtext" align="left" valign="top" width="135">
<?php include('fc_leftnav.php');?>
</td><td align="left" valign="top">
<!--OPEN CELL FOR FISHCART CODE--> 

<table class="text" border="0" cellpadding="3" width="500">
<tr><td align="left">

<center>
<b><?php echo fc_text('thankyou'); ?></b>
</center>
<p>
<?php echo fc_text('orderfinal'); ?>
</p>

</td></tr>
<tr><td align="center">

<a href="<?php echo $nsecurl.$cartdir ?>/"> <?php echo fc_text('homepage'); ?></a>

</td></tr>
</table>
<!--CLOSE CELL/ROW/TABLE OF THE MAIN 100% WRAPPER TABLE-->
</td></tr></table>

<?php // VENDOR INFORMATION 
include ('vendinfo.php');
//END OF VENDOR INFORMATION ?>

<?php // END OF ESSENTIAL CART DISPLAY CODE ?>

<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
