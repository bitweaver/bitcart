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
require('./header.php');?>

<h2 align=center>Add A Zone Profile</h2>
<hr>
<p>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center colspan=3 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
<tr><td colspan=3 bgcolor=#ffffff>

<form method="post" action="zoneupd.php">

<input type=hidden name=act value=new>

Zone Description:<br>
<input name="zonedescr" size=50 onFocus="currfield='descr'"><br>

</td></tr>
<tr><td align=left valign=top bgcolor=#ffffff>

Zone Currency Symbol:<br>
<input name="zonecurrsym" size=10 onFocus="currfield='currsym'"><br>

</td><td align=center valign=top bgcolor=#ffffff>

Zone Active?<br>
<i>check if yes</i><br>
<input type=checkbox name=zoneact value=1 checked><br>

</td><td align=center valign=top bgcolor=#ffffff>

<?php
$fcl = new FC_SQL;
$fcl->query("select count(*) as cnt from lang where langid=$zoneid");
$fcl->next_record();
$lt=(int)$fcl->f("cnt");
$fcl->free_result();
?>

Default Language:<br>
<select name=zonedeflid size=<?php echo $lt?>>
<?php
$fcl->query("select langid,langdescr from lang ".
			"where langzid=$zoneid order by langid");
while( $fcl->next_record() ){
 $lid=$fcl->f("langid");
 echo "<option value=\"$lid\">";
 echo substr($fcl->f("langdescr"),0,20)."\n";
}
?>
</select>
<br>

</td></tr>
<tr><td align=left valign=top bgcolor=#ffffff>

<input type=checkbox name=zonecc value=1 checked>
Collect Credit Card Information?<br>

<input type=checkbox name=zonekeepcc value=1 checked>
Keep CC Information In Customer Record?<br>
<i>(do <b>NOT</b> do this unless you<br>
understand the security implications!)</i><br>

<input type=checkbox name=zonesqldel value=1 checked>
Delete orders from SQL d/b once placed?<br>

<input type=checkbox name=zonesplitcc value=1 checked>
Use split CC number delivery?<br>

<input type=checkbox name=zonefishgate value=1>
Use FishNet clearing?<br>

<input type=checkbox name=zoneauthorizenet value=1>
Use Authorize.Net clearing?<br>

<input type=checkbox name=zonecybercash value=1>
Use CyberCash clearing?<br>

<input type=checkbox name=zonepmtclear value=1>
Use Payment Clearing gateway?<br>

<input type=checkbox name=zonecambist value=1>
Use Cambist clearing gateway?<br>

</td><td align=left valign=top bgcolor=#ffffff>

<input type=checkbox name=zonedebug value=1>
Debug public page operation?<br>
<i>Do <b>NOT</b> do this on a live cart!  This is for<br>
troubleshooting during develpment only.  See the file<br>
./docs/DEBUGGING file in the FishCart distribution for<br>
more information.</i><br>
<p>
<input type=checkbox name=zoneseqcartid value=1>
Enable daily rotating sequential 7 digit cart id operation; otherwise the
7 digit sequence number will be randomized to help prevent possible
information leakage regarding products on order and traffic levels.
<p>
<input type=checkbox name=zonereturn value=1>
Return to the product page immediately after adding product to the cart?
Default is no, and the current cart contents are shown.
<p>
<input type=checkbox name=zonegiftorder value=1>
Enable gift orders? <i>(future expansion)</i>
<p>
<input type=checkbox name=zonecoupon value=1>
Use coupons in this zone?
<p>
<input type=checkbox name=zoneproddate value=1>
Use product start/stop dates on this zone?
<p>
<input type=checkbox name=zoneinlinecontrib value=1>
Enable inline contribution page at checkout?
<p>
<input type=checkbox name=zonepwcatalog value=1>
Enable password access to the catalog?
<p>
<input type=checkbox name=zonerstrctpwcatalog value=1>
Restrict password access to the catalog (no unauthorized users)?
<p>
<input type=checkbox name=zonezipshowgeo value=1>
Request city, state, zip, and country information in showgeo?
<p>
<input type=checkbox name=zonelogaccess value=1>
Log public user access to the cart? <i>(no reporting tool yet)</i>
<p>
<input type=checkbox name=zonetclink value=1>
Approve T&amp;C link at foot of final checkout page?
<p>
<input type=checkbox name=zonetcpage value=1>
Approve T&amp;C inline page just prior to final checkout page?

</td><td align=center valign=top bgcolor=#ffffff>

</td></tr>
<tr><td colspan="3" align="center" bgcolor="#ffffff">

<input type=hidden name=zonewhsid value="0">
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Add Profile" onClick="closehelp()">
<input type="reset" value="Clear Form">

</form>

</td></tr>
<tr><td align=center colspan=3 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
