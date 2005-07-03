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

// ==========  end of variable loading  ==========

$fcn = new FC_SQL;
$fcp = new FC_SQL;

?>

<h2 align="center">Modify/Delete Products</h2>
<hr />
<br />

<center>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" bgcolor="#ffffff">

<b>
Modify An Existing Listing</b><br />

</td><td align="center" bgcolor="#ffffff">

<b>
Delete An Existing Listing<br />
</b>

</td></tr>
<tr><td align="center" valign="top" bgcolor="#ffffff">

<form method="post" action="newprodmod.php">

To modify an existing New Products<br />
listing, select it from the list<br />
and click <i>Modify Selected Product.</i>
<br />

<select name="sku" size="1">
<option value="" selected="selected">[no change]</option>
<?php 
$fcn->query("select * from nprod where nzid=$zoneid order by nprodsku"); 
while ( $fcn->next_record() ) {
    $sku = $fcn->f('nprodsku');
    echo '<option value="'.$sku.'">';
    $fcp->query("select prodname from prodlang where prodlsku='$sku'"); 
    if ( !$fcp->next_record() ) {
        echo 'SKU not found: '.$sku;
    } else {
        echo "$sku: ";
        $tmp = $fcp->f('prodname');
        $tmp = stripslashes($tmp);
        $tmp = ereg_replace('<[A-Za-z0-9/\=\"\+ ]*>',' ',$tmp);
        echo substr($tmp,0,30);
    }
    $fcp->free_result();
    echo '</option>';
}
$fcn->free_result();?>
</select>
<br />

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify Selected Product" /><br />

</form>

</td><td align="center" valign="top" bgcolor="#ffffff">

<form method="post" action="newprodupd.php">

<input type="hidden" name="act" value="delete" />

To delete an existing New Products<br />
listing, select it from the list and<br />
click <i>Delete Selected Product.</i>
<br />

<select name="sku" size="1">
<option value="" selected="selected">[no change]</option>
<?php 
$fcn->query("select * from nprod where nzid=$zoneid order by nprodsku"); 
while ( $fcn->next_record() ) {
    $sku = $fcn->f('nprodsku');
    echo '<option value="'.$sku.'">'; 
    $fcp->query("select prodname from prodlang where prodlsku='$sku'"); 
    if ( !$fcp->next_record() ) {
        echo 'SKU not found: '.$sku;
    } else {
        echo "$sku: ";
        $tmp = $fcp->f('prodname');
        $tmp = stripslashes($tmp);
        $tmp = ereg_replace('<[A-Za-z0-9/\=\"\+ ]*>',' ',$tmp);
        echo substr($tmp,0,30);
    }
    $fcp->free_result();
    echo '</option>';
}
$fcn->free_result();?>
</select>
<br />

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Delete Selected Product" /><br />

</form>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>

</table>
</center>

<?php require('./footer.php');?>
