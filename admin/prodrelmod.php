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
require('./admin.php');
require('./header.php');
require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

// if $zid or $lid are found, they should be changed
// to $zoneid or $langid, respectively. Once all
// maint files are done, $zid and $lid can probably
// be eliminated.

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$relsku = getparam('relsku');
$act    = getparam('act');
// ==========  end of variable loading  ==========


$fcp = new FC_SQL;
$fcl = new FC_SQL;

$fcp->query("select relprod from prodrel where relzone=$zoneid and relsku='$relsku' order by relseq");
?>

<h2 align="center">Reorder Related Product Sequence</h2>
<hr />

<center>
<form method="post" action="prodrelupd.php">

<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
 onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="left" colspan="2" bgcolor="#FFFFFF">

<i>Enter ascending integers in the order that you desire<br />
the related products to be shown in.  The absolute value<br />
of the integers does not matter; only relative ordering<br />
is important.</i><br />

</td></tr>

<?php
$i = 0;
$j = 0;
while( $fcp->next_record() ){
    $rsku=$fcp->f('relprod');
    $fcl->query(
        "select prodname from prodlang where prodlsku='$rsku' and prodlid=$langid");
    $fcl->next_record();
?>
    <tr><td align="left" colspan="1" bgcolor="#FFFFFF">
<?php
// remove html tags
    $pname = ereg_replace("<[^>]+>","",$fcl->f('prodname'));
    echo $rsku.': '.$pname; ?><br />
    </td><td align="left" colspan="1" bgcolor="#FFFFFF">
    <input name="seq<?php echo $i ?>" value="<?php echo $j ?>" size="3" />
    <input type="hidden" name="rsku<?php echo $i ?>" value="<?php echo $rsku ?>" />
    </td></tr>
<?php
    $fcl->free_result();
    $i++;
    $j += 5;
} ?>

<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<input type="hidden" name="relsku" value="<?php echo $relsku ?>" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="hidden" name="cnt" value="<?php echo $i ?>" />
<input type="hidden" name="act" value="update" />
<input type="submit" value="Submit Changes" />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
 onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
</table>

</form>
</center>

<?php
$fcp->free_result();
require('./footer.php');
?>
