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
require('./admin.php');
require('header.php');
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

// ==========  end of variable loading  ==========

$fcl = new FC_SQL;
$fcp = new FC_SQL;

if (!$zoneid || !$langid) {
	echo 'Please click Back and select a zone and/or language.  Thank you.';
    exit;
}
?>

<h2 align="center">Related Product Maintenance</h2>
<hr />

<?php
$fcp->query("select count(*) as cnt from prodrel where relzone=$zoneid"); 
$fcp->next_record();
$pt = $fcp->f('cnt');
$fcp->free_result();
?>

<center>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td colspan="2" align="center" valign="middle" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp()">
Return To Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" valign="top" bgcolor="#ffffff">

<b>Resequence Related Products</b>

</td><td valign="top" bgcolor="#ffffff">

<b>Delete A Related Product</b>

</td></tr>
<tr><td align="center" valign="top" bgcolor="#ffffff">

<form name="prodmod" method="post" action="prodrelmod.php">

<?php 
if ( $pt <= 200 ) {
    echo 'To change the display order for the related<br />'.
        'products for a product, select its name<br />'.
        'from the list and click <i>Modify Product.</i><br />';
    $fcp->query("select distinct relsku from prodrel  where relzone=$zoneid order by relsku"); 
?>

    <select name="relsku" size="<?php echo min(20,$pt)?>"
        onchange="document.prodmod.action='prodrelmod.php';submit();"
        onfocus="helpfocus('relsku');">
<?php
    while ( $fcp->next_record() ) {
        $sku = $fcp->f('relsku');
        $fcl->query(
            "select distinct prodname from prodlang where prodlsku='$sku' and prodlid=$langid");
        $fcl->next_record();
        $pname = ereg_replace('<[^>]+>','',$fcl->f('prodname'));
        $fcl->free_result();
?>
        <option value="<?php echo $sku; ?>"><?php echo $sku.': '.$pname;?></option>
<?php
    }?>
    </select>
    
<?php 
} else {?>
    <p>
    Enter the SKU to modify:<br />
    <input name="relsku" size="10" onfocus="currfield='relsku';" />
    </p>
<?php
} // if ( $pt <= 200 ) ?>
<br />
<input type="hidden" name="act" value="update" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify Product" onclick="closehelp()" /><br />
</form>

</td><td align="center" valign="top" bgcolor="#ffffff">

<form method="post" action="prodrelupd.php">

<?php
if ( $pt <= 200 ) {
    echo 'To delete all related products from<br />'.
        'an existing product, select its name<br />'.
        'from the list and click <i>Delete Product.</i><br />';
    $fcp->query("select distinct relsku from prodrel  where relzone=$zoneid order by relsku"); 
?>

    <select name="relsku" size="<?php echo min(20,$pt)?>"
        onfocus="helpfocus('relsku');">
<?php 
    while( $fcp->next_record() ){
        $sku=$fcp->f('relsku');
        $fcl->query(
            "select distinct prodname from prodlang where prodlsku='$sku' and prodlid=$langid");
        $fcl->next_record();
        $pname = ereg_replace("<[^>]+>","",$fcl->f('prodname'));
        $fcl->free_result();
?>
        <option value="<?php echo $sku; ?>"><?php echo $sku.': '.$pname;?></option>
<?php
    }?>
    </select>

<?php 
} else {?>
    <p>
    Enter the base SKU:<br />
    <input name="relsku" size="10" onfocus="currfield='relsku'"/>
    </p>
    <?php 
} // if ( $pt <= 200 ) ?>

<p>
Enter the related SKU to delete:<br />
<i>leave blank to remove all related<br />
products for above product</i><br />
<input name="relprod" size="10" onfocus="currfield='relprod'" />
</p>

<input type="hidden" name="act" value="delete" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Delete Related Product" onclick="closehelp()" />

</form>

</td></tr>
<tr><td align="center" valign="top" colspan="2" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
 onclick="closehelp()">
Return To Central Maintenance Page</a><br />

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
