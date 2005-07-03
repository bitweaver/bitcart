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
header("Expires: 0");
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');   // includes flags.php

$fcv = new FC_SQL;
$fcz = new FC_SQL;

if($zoneid==""){?>
	Please click Back and select a zone.  Thank you.
<?php exit;}
?>

<h2 align=center>Add A Vendor Profile</h2>
<hr>
<p>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

<b>
Add A Vendor Profile<br>
</b>

</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

Selected Zone: <?php 
$fcz->query("select zonedescr from zone ".
	"where zoneid=$zoneid order by zoneid"); 
$fcz->next_record();?>
<?php echo $fcz->f("zonedescr")?><br>
<?php $fcz->free_result();?>

</td></tr>
<tr><td colspan=2 bgcolor=#ffffff>

<form method="post" action="vendorupd.php">

<input type=hidden name=act value=new>

<input type=hidden name=zoneid value="<?php echo $zoneid?>">
<input type=hidden name=langid value="<?php echo $langid?>">

Vendor Name: <i>max 80 characters</i><br>
<input name="vendname" size=50 onFocus="currfield='vendor'"><br>

Vendor Address 1: <i>max 80 characters</i><br>
<input name="vendaddr1" size=50 onFocus="currfield='vendor'"><br>

Vendor Address 2: <i>max 80 characters</i><br>
<input name="vendaddr2" size=50 onFocus="currfield='vendor'"><br>

Vendor City, State, ZIP, Country: <i>max [40,3,12] characters</i><br>
<input name="vendcity" size=30 onFocus="currfield='vendor'">
<input name="vendstate" size=3 onFocus="currfield='vendor'">
<input name="vendzip" size=12 onFocus="currfield='vendor'">
<input name="vendnatl" size=3 onFocus="currfield='vendor'"><br>

<table border=0 cellpadding=4 cellspacing=0 bgcolor=#666666 width=100% class="text">
<tr><td bgcolor=#ffffff>

Vendor E-Mail: <i>max 40 characters</i><br>
<input name="vendemail" size=40 onFocus="currfield='vendor'"><br>

Vendor Phone: <i>max 20 characters</i><br>
<input name="vendphone" size=20 onFocus="currfield='vendor'"><br>

Vendor Fax: <i>max 20 characters</i><br>
<input name="vendfax" size=20 onFocus="currfield='vendor'"><br>

</td></tr>
</table>

Vendor Service Name: <i>max 80 characters</i><br>
<input name="vsvcname" size=50 onFocus="currfield='vendsvc'"><br>

Vendor Service Address 1: <i>max 80 characters</i><br>
<input name="vsvcaddr1" size=50 onFocus="currfield='vendsvc'"><br>

Vendor Service Address 2: <i>max 80 characters</i><br>
<input name="vsvcaddr2" size=50 onFocus="currfield='vendsvc'"><br>

Vendor Service City, State, ZIP: <i>max [40,3,12] characters</i><br>
<input name="vsvccity" size=30 onFocus="currfield='vendsvc'">
<input name="vsvcstate" size=3 onFocus="currfield='vendsvc'">
<input name="vsvczip" size=12 onFocus="currfield='vendsvc'">
<input name="vsvcnatl" size=3 onFocus="currfield='vendor'"><br>

Vendor Service E-Mail: <i>max 40 characters</i><br>
<input name="vsvcemail" size=40 onFocus="currfield='vendsvc'"><br>

Vendor Service Phone: <i>max 20 characters</i><br>
<input name="vsvcphone" size=20 onFocus="currfield='vendsvc'"><br>

Vendor Service Fax: <i>max 20 characters</i><br>
<input name="vsvcfax" size=20 onFocus="currfield='vendsvc'">

<p>

Online Order Script: <i>max 40 characters</i><br>
<input name="vendonline" size=40 value="emailorder.php"
 onFocus="currfield='vendonline'"><br>

Offline Order Script: <i>max 40 characters</i><br>
<input name="vendofline" size=40 value="offline.php"
 onFocus="currfield='vendofline'"><br>

Order Confirmation Script: <i>max 40 characters</i><br>
<input name="vendconfirm" size=40 value="emailconfirm.php"
 onFocus="currfield='vendconfirm'"><br>

Order Email Address: <i>max 40 characters</i><br>
<input name="vendoemail" size=40 
 onFocus="currfield='vendoemail'"><br>

</td></tr>
<tr><td colspan="2" align="center" valign="center" bgcolor="#ffffff">

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Add" onClick="closehelp()">
<input type="reset" value="Clear Form">

</form>

</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
