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
header('Pragma: no-cache');

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
$cpnid=(int)getparam('cpnid');
$act=getparam('act');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');
?>

<h2 align="center">Coupon Maintenance</h2>
<hr />
<p></p>

<center>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">

<b>Modify An Existing Coupon Profile<br />
<i>(total redemptions shown in parentheses)</i><br />
</b>

</td><td align="center" bgcolor="#FFFFFF">

<b>Delete An Existing Coupon Profile</b>

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">

<form name="couponmod" method="post" action="couponmod.php">

To modify an existing coupon profile,<br />
select its name from the list and<br />
click <i>Modify Selected Profile</i>.
<br />

<?php 
$coupon = new FC_SQL;
$coupon->query("select count(*) as cnt from coupon"); 
$coupon->next_record();
$len=(int)$coupon->f("cnt");
$coupon->free_result();
?>

<select name="cpnid" size="<?php echo $len+1?>"
 onChange="document.couponmod.action='couponmod.php';submit();">
<option name=cpnid value="" selected>[no change]</option>
<?php  // query the coupon
$coupon->query("select cpnid,cpnredeem from coupon order by cpnid"); 
while( $coupon->next_record() ) {?>
<option value="<?php echo $coupon->f("cpnid")?>"><?php echo $coupon->f("cpnid").' ('.$coupon->f("cpnredeem").')'; ?></option>
<?php 
} // end while loop
$coupon->free_result();
?>
</select>
<p>

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="hidden" name="act" value="update" />
<input type="submit" value="Modify Selected Profile" /><br />
</p>

</form>

</td><td align="center" bgcolor="#FFFFFF">

<form method="post" action="couponupd.php">

To delete an existing coupon profile,<br />
select its name from the list and<br />
click <i>Delete Selected Profile.</i><br />

<select name="cpnid" size="<?php echo $len+1?>">
<option value="" selected>[no change]</option>
<?php  // query the coupon
$coupon->query("select cpnid,cpnredeem from coupon order by cpnid"); 
while( $coupon->next_record() ){?>
<option value="<?php echo $coupon->f("cpnid")?>"><?php echo $coupon->f("cpnid")?></option>
<?php 
} // end while loop
$coupon->free_result();
?>
</select>
<p>

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="hidden" name="act" value="delete" />
<input type="submit" value="Delete Selected Profile" /><br />
</p>

</form>
</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>

</table>
</center>

<?php require('./footer.php');?>
