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

header("Expires: 0");
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
// ==========  end of variable loading  ==========

require('./admin.php');

$fic = new FC_SQL;
$fiu = new FC_SQL;
$debug = 0;
$bad = 0;

?>
<html>
<head>
<title>FishCart Maintenance: Import Inventory Quantities</title>
</head>
<body bgcolor="#FFFFFF">
<center>
<font face="Arial,Helvetica" size="3"><b>Import Inventory Quantities</b></font>
<br><br>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">

<tr>
<td align="center" bgcolor="#000000">
<font face="Arial,Helvetica" size="2" color="#FFFFFF">
<b>Parse Local File</b>
</font>
</td>
</tr>

<tr>
<td align="left" bgcolor="#FFFFFF" valign="top">
<font face="Arial,Helvetica" size="2">
<p>
This utility allows you to upload a two column tab delimited file containing
the SKU in column 1 and its current inventory quantity in column 2.  The 
inventory quantities in the product table are updated and the use inventory
quantity flag set for each product updated.  A list of products in the file 
but not found in the FishCart database is displayed.
</p>
<p>
To parse a file from your computer, browse for the file and click <i>Parse</i>.
</p>
</font>
</td>
</tr><tr>

<td align="center" bgcolor="#FFFFFF">
<form enctype="multipart/form-data" method="post" action="invcontrol.php">
<input type="file" name="userfile">
<input type="hidden" name="act" value="parse">
<input type="submit" value="Parse">
</form>
</td>
</tr>
<tr><td align="center" bgcolor="#000000" colspan="1">
<a href="./index.php"><font face="Arial,Helvetica" size="2" color="#FFFFFF"><b>
Return to Central Maintenance Page
</b></font></a>
</td></tr>

</table>
</center>
<?php
if ($act=="parse"){
	if ( !eregi(".*\.txt$",$userfile_name) ) {
		$bad=1;
	}
	if($bad){
		print "Please choose a file with an extension of *.txt.";
		  unlink($userfile);
		  exit;
	}
	move_uploaded_file($userfile, 'BITCART_PKG_PATHmaint/maintupl/'.$userfile_name);
	$sfilename = $userfile_name;

$row_cnt = 0;
$updated = 0;
$notupdated = 0;
$if = fopen('BITCART_PKG_PATHmaint/maintupl/'.$sfilename,'r');
while( $buf = fgets($if,4096) ){
	// qty with leading - or wrapped in parens is negative
	if ( ereg("^([0-9]+)	([\(-]*)([0-9]+)",$buf,$reg) ){
		$sku = (int)$reg[1];
		$neg = (int)$reg[2];
		$qty = (int)$reg[3];
		if( $neg ){
			$qty = 0;
		}
		$fic->query(
			"select count(*) as cnt from prod where prodsku=$sku;");
		$fic->next_record();
		$found=(int)$fic->f("cnt");
		if($found==1){
		$fiu->query(
			"update prod set produseinvq=1,prodinvqty=$qty ".
			"where prodsku=$sku;");
		$updated++;
		$fiu->free_result();
		}else{
		if ($notupdated==0){
		echo"<center><p><table border=\"1\" cellpadding=\"3\" cellspacing=\"0\"".
		"width=\"550\"><tr><td colspan=\"3\" align=\"center\" bgcolor=\"#000000\" class=\"text\">".
		"<font face=\"Arial,Helvetica\" size=\"2\" color=\"#FFFFFF\"><b>No Matches ".
		"for the following records:</b></font></td></tr>";
		}
		echo sprintf(
		"<tr><td align=\"left\"><b>Sku:</b> %6d</td><td align=\"left\">".
		"<b>Qty:</b> %3d</td><td align=\"left\"><b>Name:</b> %s</td></tr>",
			$sku,$qty,$name);
			$notupdated++;
		}
		$fic->free_result();
	}
	$row_cnt++;
}
echo "<tr><td colspan=\"3\" align=\"center\">$updated Record(s) ".
"updated successfully</td></tr><tr><td align=\"center\" ".
"bgcolor=\"#000000\" colspan=\"3\"><a href=\"./index.php\">".
"<font face=\"Arial,Helvetica\" size=\"2\" color=\"#FFFFFF\"><b>".
"Return to Central Maintenance Page</b></font></a></td></tr></table></p>";
}//end parse

?>
<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
