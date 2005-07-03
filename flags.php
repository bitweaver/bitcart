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

$flags_inc = 1;

// binary flags of the form $flag_xxx
//
$flag_noship = 0x1;
// do not charge sales tax if set
$flag_notax  = 0x2;
// use ESD for a product
$flag_useesd = 0x4;
// generate serial number when purchased
$flag_genesd = 0x8;
// do not charge VAT if set
$flag_novat  = 0x10;
// product is a periodic service
$flag_persvc = 0x20;
// product is a separate package
$flag_package = 0x40;
// product has related products (bvo)
$flag_hasrel = 0x80;


//prodlflag1
// product has options (bvo)
$flag_hasoption = 0x1;


// zone flags in zflag1
//
// collect CC information?
$flag_zonecc = 0x1;
// keep customer CC information?
$flag_zonekeepcc = 0x2;
// delete orders from SQL ohead/oline after completion?
$flag_zonesqldel = 0x4;
// return to product page after adding product to cart?
$flag_zonereturn = 0x8;
// use split CC delivery?
$flag_zonesplitcc = 0x10;
// use coupons on this cart?
$flag_zonecoupon = 0x20;
// use product dates on this cart?
$flag_zoneproddate = 0x40;
// use inline contribution page at checkout?
$flag_zoneinlinecontrib = 0x80;
// use authorize.net for clearing?
$flag_zoneauthorizenet = 0x100;
// use cybercash for clearing?
$flag_zonecybercash = 0x200;
// use password login for catalog?
$flag_zonepwcatalog = 0x400;
// restrict password login for catalog?
$flag_zonerstrctpwcatalog = 0x800;
// public page debug flag
$flag_zonedebug = 0x1000;
// show city,state,zip on showgeo
$flag_zonezipshowgeo = 0x2000;
// use FishNet gateway for clearing?
$flag_zonefishgate = 0x4000;
// use Payment Clearing gateway for clearing?
$flag_zonepmtclear = 0x8000;
// enable gift orders?
$flag_zonegiftorder = 0x10000;
// enable logging of access?
$flag_zonelogaccess = 0x20000;
// enable terms and conditions link
$flag_zonetclink = 0x40000;
// enable inline terms and conditions page
$flag_zonetcpage = 0x80000;
// enable sequential 7 digit cart id sequence number if true
// default operation is to randomize the last 7 digits
$flag_zoneseqcartid = 0x100000;
// enable cambist online clearing
$flag_zonecambist = 0x200000;


// web flags in webflags1
//
// show qty on order in display.php?
$flag_webshowqty = 0x1;
// show preview.php in index/display?
$flag_webshowpreview = 0x2;
// show preview.php at top (unset) or bottom (set)
$flag_webshowtopbot = 0x4;
// format prodsdescr and proddescr with nl2br when echoing to browser (bvo)
$flag_webusenlbr = 0x8;


// product option flags in flag1
//
// sku treatment options
$flag_poptskupre = 0x1;
$flag_poptskusuf = 0x2;
$flag_poptskusub = 0x4;
$flag_poptskumod = 0x8;
// price treatment -- absolute = 0, relative = 1
$flag_poptprcrel = 0x10;
// flag if a product option group is required
$flag_poptgrpreq = 0x20;
// option group is exclusive; only one can be selected
$flag_poptgrpexc = 0x40;
// ask for a quantity on this group
$flag_poptgrpqty = 0x80;
?>
