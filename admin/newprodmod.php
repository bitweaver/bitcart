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
$sku    = getparam('sku');
$act    = getparam('act');

// ==========  end of variable loading  ==========

$fcn = new FC_SQL;
$fcn->query("select * from nprod ".
    "where nprodsku='$sku' and nzid=$zoneid"); 
if ( !$fcn->next_record() ) {
    echo 'The selected product could not be found.  This may reflect an '.
        'inconsistent database; please check with your system '.
        'administrator.'; 
    exit;
}?>

<h2 align="center">New Product List Maintenance</h2>
<hr />
<br />


<center>
<form method="post" action="newprodupd.php">
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="newprodndx.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to New Product Maintenance Page</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<b>
Modify An Existing New Product Listing<br />
</b>

</td></tr>
<tr><td colspan="2" bgcolor="#FFFFFF">

Modify the text as you wish in any of the fields below;<br />
when you have finished, click <i>Modify Selected Product.</i>


<input type="hidden" name="act" value="update" />

Product SKU: <i>20 characters max</i><br />
<input name="sku" size="20"
    value="<?php echo $fcn->f('nprodsku')?>"
    onfocus="currfield='sku'" /><br />
<input type="hidden" name="oldsku" value="<?php echo $fcn->f('nprodsku')?>" />

</td></tr>
<tr><td valign="top" colspan="2" bgcolor="#FFFFFF">

<?php $sd = $fcn->f('nstart')?>
Start Date:<br /><i>YYYY MM DD</i><br />
<input type="text" name="nsy" size="4"
    value="<?php if ($sd != 0) { echo date('Y',$sd); } ?>"
    onfocus="currfield='nstart'" />
<input type="text" name="nsm" size="2"
    value="<?php if ($sd != 0) { echo date('m',$sd); } ?>"
    onfocus="currfield='nstart'" />
<input type="text" name="nsd" size="2"
    value="<?php if ($sd != 0) { echo date('d',$sd); } ?>"
    onfocus="currfield='nstart'" />
<br />

</td></tr>
<tr><td valign="top" colspan="2" bgcolor="#FFFFFF">

<?php $sd = $fcn->f('nend')?>
End Date:<br /><i>YYYY MM DD</i><br />
<input type="text" name="ney" size="4"
    value="<?php if ($sd != 0) { echo date('Y',$sd); } ?>"
    onfocus="currfield='nend'" />
<input type="text" name="nem" size="2"
    value="<?php if ($sd != 0) { echo date('m',$sd); } ?>"
    onfocus="currfield='nend'" />
<input type="text" name="ned" size="2"
    value="<?php if ($sd != 0) { echo date('d',$sd); } ?>"
    onfocus="currfield='nend'" />
<br />

</td></tr>
<tr><td colspan="2" align="center" bgcolor="#FFFFFF">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify Product" />
<input type="reset" value="Clear Field" />


</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="newprodndx.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to New Product Maintenance Page</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
</table>
</form>
</center>

<?php require('./footer.php');?>
