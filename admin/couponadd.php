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
// addslashes() for non-numbers, no exceptions

//if $zid & $lid are found, they should be changed
//to $zoneid and $langid. Once all maint files
//are done, $zid and $lid can probably be eliminated.

$zoneid=(int)getparam('zoneid');
$langid=(int)getparam('langid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');
?>

<h2 align="center">Add A Coupon Profile</h2>
<hr />
<p></p>

<center>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>">
Return To Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<b>
Add A Coupon Profile<br />
</b>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
Return To Central Maintenance Page</a><br />

</td></tr>
<tr><td colspan="2" bgcolor="#FFFFFF">

<form method="post" action="couponupd.php">

<input type="hidden" name="act" value="new" />

Coupon ID:<br />
<input name="cpnid" size="50" onFocus="currfield='cpnid'" /><br />

Discount:<br />
<i>numbers less than one are percent discounts (0.nn%)<br />
numbers greater than or equal to one are absolute discount amounts</i><br />
<input name="discount" size="50" onFocus="currfield='discount'" /><br />

Coupon Start Date:<br /><i>YY MM DD</i><br />
<input name="ssy" size="5" onFocus="currfield='startdate'" />
<input name="ssm" size="2" onFocus="currfield='startdate'" />
<input name="ssd" size="2" onFocus="currfield='startdate'" />
<br />

Coupon End Date:<br /><i>YY MM DD</i><br />
<input name="sey" size="5" onFocus="currfield='stopdate'" />
<input name="sem" size="2" onFocus="currfield='stopdate'" />
<input name="sed" size="2" onFocus="currfield='stopdate'" />
<br />

Product SKU This Coupon Applies To:<br />
<input name="cpnsku" size="16" onFocus="currfield='cpnsku'" /><br />

Minimum Product Quantity For This Coupon:<br />
<input name="cpnminqty" size="16" onFocus="currfield='cpnminqty'" /><br />

Minimum Order Total Amount For This Coupon:<br />
<input name="cpnminamt" size="16" onFocus="currfield='cpnminamt'" /><br />

Maximum Redemptions For This Coupon:<br />
<input name="cpnmaximum" size="16" onFocus="currfield='cpnmaximum'" /><br />

</td></tr>
<tr><td colspan="2" align="center" valign="center" bgcolor="#FFFFFF">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Add" onClick="closehelp()" />

</form>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br />

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
