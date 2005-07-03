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
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );
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

$fcp = new FC_SQL;

if ($zoneid == '' || $langid == '') {
	echo 'Please click Back and select a zone and/or language.  Thank you.';
    exit;
}
?>

<h2 align="center">Add A Related Product</h2>
<hr />

<center>
<form method="post" action="prodrelupd.php">
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="3" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="3" bgcolor="#ffffff">

<b>
Add A Related Product</b><br />

</td></tr>
<tr><td valign="top" align="left" colspan="1" bgcolor="#ffffff">

Base Product:<br />
<input name="relsku" size="10" onfocus="currfield='relsku'" />
<br />

</td><td valign="top" align="left" colspan="1" bgcolor="#ffffff">

Related Product:<br />
<input name="relprod" size="10" onfocus="currfield='relprod'" />
<br />

</td><td valign="top" align="left" colspan="1" bgcolor="#ffffff">

Ordering Sequence:<br />
<i>(ascending integers of any value)</i><br />
<input name="relseq" size="10" onfocus="currfield='relseq'" />
<br />

</td></tr>
<tr><td align="center" colspan="3" bgcolor="#ffffff">

<input type="hidden" name="act" value="insert" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Add Related Product" onclick="closehelp()" /><br />

</td></tr>
<tr><td align="center" colspan="3" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>

</table>
</form>
</center>

<?php  require('./footer.php'); ?>
