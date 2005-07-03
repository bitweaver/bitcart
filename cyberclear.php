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

//SQL INJECTION AVOIDENCE
$zid = (int) $zid;
$lid = (int) $lid;

require("class_cybercash.php");
$cybercash = new cybercash;

$ccmonth=sprintf("%02d",$ccexp_month);
$ccyear=substr($ccexp_year,2,2);

// initialize class variables for this order

$cybercash->orderNum  = "$cartid";
$cybercash->currency  = "usd";
$cybercash->price     = $ttotal;
$cybercash->ccnum     = "$cc_number";
$cybercash->ccexp     = $ccmonth.'/'.$ccyear;
$cybercash->ccname    = "$cc_name";
$cybercash->address1  = "$billing_address1";
$cybercash->address2  = "$billing_address2";
$cybercash->cccity    = "$billing_city";
$cybercash->ccstate   = "$billing_state";
$cybercash->cczip     = "$billing_zip";
$cybercash->cccountry = "USA";

// submit the charge to CyberCash and parse out the return data
if (!$cybercash->send()){
 $cybercash->errorHandler("Error: no data received back from CyberCash");
}
$POP = $cybercash->POP;
parse_str(ereg_replace("pop.","",$POP));

if( $status != 'success' ){

// get the Web table
$fcw=new FC_SQL;
$fcw->query(
 "select webback,webtext,weblink,webvlink,webalink,webbg,webfree,websort ".
 "from fishcart where webzid=$zid and weblid=$lid"); 
$fcw->next_record();
$srt=$fcw->f("websort");
$fcw->free_result();

// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE?>

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

<?php // START OF ESSENTIAL CART DISPLAY CODE ?>
<center>

<h2></h2>
<h3>Credit Card Clearing Error</h3>

<table border="3" cellpadding="5" width="550">
<tr><td align="left" valign="top" colspan="4">

We're sorry, but there has been an error clearing your credit card.
If you wish to retry this transaction please click the &quot;Back&quot;
button on your browser to enter other financial information.

<p>

Thank you!

</p>
<p align="left">
COMMENT THIS OUT BEFORE GOING LIVE!
</p>
<p align="left">
cc_name: <?php echo $cc_name ?><br />
cc_number: <?php echo $cc_number ?><br />
ccmonth: <?php echo $ccmonth ?><br />
ccyear: <?php echo $ccyear ?><br />
status: <?php echo $status ?><br />
actioncode: <?php echo $actioncode ?><br />
errorcode: <?php echo $errorcode ?><br />
errormessage: <?php echo $errormessage ?><br />
authcode: <?php echo $authcode ?><br />
avscode: <?php echo $avscode ?><br />
txnid: <?php echo $txnid ?><br />
refcode: <?php echo $refcode ?><br />
sign: <?php echo $sign ?><br />
</p>


</td></tr>
</table>
</center>

<p align="center">
<a href="index.php?<?php echo "cartid=$cartid&zid=$zid&lid=$lid"; ?>">
<b>Return to Shopping Cart Front Page</b></a>
</p>

<hr />

<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php // END OF ESSENTIAL CART DISPLAY CODE ?>

<?php
 $cybercash->deleteWorkFiles();
 exit;
}

// clean up after ourselves and keep going
$cybercash->deleteWorkFiles();
?>
