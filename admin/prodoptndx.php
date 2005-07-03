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

header("Expires: ".GMDate("D, d M Y H:i:s")." GMT");
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( BITCART_PKG_PATH.'functions.php');
// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$ssku   = getparam('ssku');
$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');
require_once( BITCART_PKG_PATH.'flags.php');

$fcp = new FC_SQL;

if(!$zoneid || !$langid){?>
  A zone or language ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and select a zone.  Thank you.
<?php exit;
}
?>

<h2 align=center>Product Option Maintenance</h2>
<hr>
<p>

<?php if(!$ssku){?>

Please return to the product page and select a SKU to maintain.<p>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
Return to Central Maintenance</a><br>

<?php exit;}

// SKU in $ssku

$fcp->query(
	"select count(*) as cnt from prodopt where poptsku='$ssku' and ".
	"poptlid=$langid and poptzid=$zoneid"); 
$fcp->next_record();
$pt=(int)$fcp->f("cnt");
$fcp->free_result();
?>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
<tr><td valign=top align=center colspan=2 bgcolor="#FFFFFF">

Base Product: <?php echo $ssku ?>

</td></tr>
<tr><td align=center bgcolor="#FFFFFF">
<b>Modify Product Option</b>
</td>
<td align=center bgcolor="#FFFFFF">
<b>Delete Product Option</b>
</td></tr>
<tr><td align=center bgcolor="#FFFFFF">
<i>Option Group Number: Description</i><br>

<form method="post" name=poptmod action="prodoptmod.php">

<select name=poptid size=<?php echo $pt ?>
 onChange="document.poptmod.action='prodoptmod.php';submit();return true;">
<?php
$fcp->query(
	"select poptid,poptgrp,poptname from prodopt where poptsku='$ssku' ".
	"and poptlid=$langid and poptzid=$zoneid order by poptseq"); 
while ( $fcp->next_record() ){
?>
<option value="<?php echo $fcp->f('poptid')?>"><?php
 echo $fcp->f('poptgrp').': '.$fcp->f('poptname');
}
$fcp->free_result();
?>
</select><br>

<input type=hidden name=ssku value="<?php echo $ssku?>">
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Modify Product Option"><br>

</form>

</td><td align=center bgcolor="#FFFFFF">
<br>

<form method="post" action="prodoptupd.php">

<select name=poptid size=<?php echo $pt ?>>
<?php
$fcp->query("select poptid,poptname from prodopt where poptsku='$ssku' ".
	"and poptlid=$langid and poptzid=$zoneid order by poptseq"); 
while ( $fcp->next_record() ){
?>
<option value="<?php echo $fcp->f("poptid")?>"><?php echo $fcp->f("poptname")?>
<?php }
$fcp->free_result();
?>
</select><br>

<input type=hidden name=act value=delete>
<input type=hidden name=ssku value="<?php echo $ssku?>">
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Delete Product Option"><br>

</form>

</td></tr>
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
