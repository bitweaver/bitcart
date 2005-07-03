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

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$show   = (int)getparam('show');
$srch   = (int)getparam('srch');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');?>

<h2 align=center>Modify The Master Profile</h2>
<hr>
<p>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>">
Return To Central Maintenance Page</a><br>

</td></tr>
<tr><td valign=top align=center bgcolor=#ffffff>

<?php 
$fcl = new FC_SQL;
$fcm = new FC_SQL;
$fcz = new FC_SQL;

$fcm->query("select zoneid from master"); 
$fcm->next_record();
?>

<form method="post" action="masterupd.php">

<input type=hidden name=act value=update>
<input type=hidden name=srch value=<?php echo $srch?>>
<input type=hidden name=show value="<?php echo $show?>">
<input type=hidden name=oldsku value="">

Default Zone:<br>

</td>
</tr>
<tr><td valign=top align=center bgcolor=#ffffff>

<?php 
$currz=$fcm->f("zoneid");

$fcz->query("select count(*) as cnt from zone");
$fcz->next_record();
$zt=(int)$fcz->f('cnt');
$fcz->free_result();

$fcz->query("select zoneid,zonedescr from zone order by zoneid"); 
?>
<select name=zoneid size="<?php echo $zt+1?>" onFocus="currfield='zoneid';">
<option value="">[select a zone]
<?php 
while( $fcz->next_record() ){
 $zid=$fcz->f("zoneid");
 if($currz==$zid){
	echo "<option value=\"$zid\" selected>";
	$oldzid=$zid;
 }else{
	echo "<option value=\"$zid\">";
 }
 echo substr($fcz->f("zonedescr"),0,20);
 echo "\n";
}?>
</select>
<input type=hidden name=oldzid value=<?php echo $oldzid?>>
<?php 
$fcz->free_result();
?>

</td>
<?php 
/* nmb moved to zone table
$currl=$fcm->f("langid");

$fcl->query("select count(*) as cnt from lang where langid=$currz");
$fcl->next_record();
$lt=(int)$fcl->f("cnt");
$fcl->free_result();

$fcl->query(
"select langid,langdescr from lang where langzid=$currz order by langid"); 
?>
<select name=langid size="<?php echo $lt+1?>"
 onFocus="currfield='langid';">
<option value="">[select a language]
<?php 
while( $fcl->next_record() ){
 $lid=$fcl->f("langid");
 if($currl==$lid){
	echo "<option value=\"$lid\" selected>";
	$oldlid=$lid;
 }else{
	echo "<option value=\"$lid\">";
 }
 echo substr($fcl->f("langdescr"),0,20);
 echo "\n";
}?>
</select>
<input type=hidden name=oldlid value=$oldlid>
<?php 
$fcl->free_result();
 */
?>

</td></tr>
<tr><td align=center valign=center bgcolor=#ffffff>

<input type="submit" value="Modify Profile" onClick="closehelp()">
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

<?php  require('./footer.php'); ?>
