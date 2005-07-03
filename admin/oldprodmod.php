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

$sku = getparam('sku');

// ==========  end of variable loading  ==========

$fco = new FC_SQL;
?>
<h2 align="center">Closeout Product List Maintenance</h2>
<hr />

<p>
<?php 
$fco->query("select * from oprod where oprodsku='$sku'"); 
if ( !$fco->next_record() ) {
    echo 'The selected product could not be found.  This may reflect an '.
        'inconsistent database; please check with your system '.
        'administrator.';
    exit;
}?>
</p>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="oldprod.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Closeout Product Maintenance Page</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<b>
Modify An Existing Closeout Product Listing<br />
</b>

</td></tr>
<tr><td colspan="2" bgcolor="#FFFFFF">

Modify the text as you wish in any of the fields below;<br />
when you have finished, click <i>Modify Selected Product.</i>

<form method="post" action="oldprodupd.php">

<input type="hidden" name="act" value="update" />

Product SKU: <i>20 characters max</i><br />
<input name="sku" size="20"
    value="<?php echo $fco->f('oprodsku')?>"
    onfocus="currfield='sku'" /><br />
<input type="hidden" name="oldsku" value="<?php echo $fco->f('oprodsku')?>" />

</td></tr>
<tr><td valign="top" colspan="2" bgcolor="#FFFFFF">

<?php $sd = $fco->f('ostart')?>
Start Date:<br /><i>YYYY MM DD</i><br />
<input name="nsy" size="4" value="<?php if($sd!=0){echo date('Y',$sd);}?>"
    onfocus="currfield='ostart'" />
<input name="nsm" size="2" value="<?php if($sd!=0){echo date('m',$sd);}?>"
    onfocus="currfield='ostart'" />
<input name="nsd" size="2" maxsize="2" value="<?php if($sd!=0){echo date('d',$sd);}?>"
    onfocus="currfield='ostart'" />
<br />

</td></tr>
<tr><td valign="top" colspan="2" bgcolor="#FFFFFF">

<?php $sd = $fco->f('oend')?>
End Date:<br /><i>YYYY MM DD</i><br />
<input name="ney" size="4" value="<?php if($sd!=0){echo date('Y',$sd);}?>"
    onfocus="currfield='oend'" />
<input name="nem" size="2" value="<?php if($sd!=0){echo date('m',$sd);}?>"
    onfocus="currfield='oend'" />
<input name="ned" size="2" value="<?php if($sd!=0){echo date('d',$sd);}?>"
    onfocus="currfield='oend'" />
<br />

</td></tr>
<tr><td colspan="2" align="center" bgcolor="#FFFFFF">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify Product" />
<input type="reset" value="Clear Field" />

</form>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="oldprod.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Closeout Product Maintenance Page</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
