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
?>
<h2 align="center">Add A Closeout Product</h2>
<hr />
<p>

<center>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#ffffff">

<a href="oldprodndx.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Closeout Product Maintenance Page</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#ffffff">

<b />
Add A Product To The Closeout Product List<br />
</b>

</td></tr>
<tr><td align="left" colspan="2" bgcolor="#ffffff">

<form method="post" action="oldprodupd.php">

<input type="hidden" name="act" value="insert" />

Product SKU: <i>20 characters max</i><br />
<input name="sku" size="20" onfocus="currfield='sku'" /><br />

</td></tr>
<tr><td valign="top" colspan="2" bgcolor="#ffffff">

Start Date:<br /><i>YYYY MM DD</i><br />
<input name="nsy" size="4" onfocus="currfield='nstart'" />
<input name="nsm" size="2" onfocus="currfield='nstart'" />
<input name="nsd" size="2" onfocus="currfield='nstart'" />
<br />

</td></tr>
<tr><td valign="top" colspan="2" bgcolor="#ffffff">

End Date:<br /><i>YYYY MM DD</i><br />
<input name="ney" size="4" onfocus="currfield='nend'" />
<input name="nem" size="2" onfocus="currfield='nend'" />
<input name="ned" size="2" onfocus="currfield='nend'" />
<br />

</td></tr>
<tr><td colspan="2" align="center" bgcolor="#ffffff">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Add Product" />
<input type="reset" value="Clear Field" />

</form>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#ffffff">

<a href="oldprodndx.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Closeout Product Maintenance Page</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
