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
require('./header.php');

$fcz = new FC_SQL;
?>

<h2 align=center>Zone Maintenance</h2>
<hr>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td colspan="2" align="center" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
Return To Central Maintenance Page</a><br>

</td></tr>
<tr><td align="center" bgcolor="#ffffff">
<b>Modify Zone</b>
</td><td align="center" bgcolor="#ffffff">
<b>Delete Zone</b>
</td></tr>
<tr><td align=center bgcolor=#ffffff>

<form name=zonemod method="post" action="zonemod.php">

<input type=hidden name=act value=update>
<input type=hidden name=langid value=<?php echo $langid?>

<input type=hidden name=subzid value=<?php if( isset( $subzid ) ) { echo $subzid }?>>

To modify an existing zone,<br>
select its name from the list and<br>
click <i>Modify Zone.</i>
<br>

<?php 
$fcz->query("select count(*) as cnt from zone"); 
$fcz->next_record();
$zct=(int)( $fcz->f("cnt") );
$fcz->free_result();
$fcz->query("select * from zone order by zoneid"); 
?>

<select name=zoneid size="<?php echo $zct+1?>"
 onChange="document.zonemod.action='zonemod.php';submit();"
 onFocus="currfield='zoneid';">
<option value="" selected>[no change]
<?php while( $fcz->next_record() ){?>
<option value="<?php echo $fcz->f("zoneid")?>">
<?php 
echo substr($fcz->f("zonedescr"),0,20);
}
$fcz->free_result();
?>
</select>

<p>

<input type="submit" value="Modify Zone"
 onClick="closehelp()"><br>

</form>

</td><td align=center bgcolor=#ffffff>

<form METHOD="POST" action="zoneupd.php">

<input type=hidden name=act value=delete>

To delete an existing zone,<br>
select its name from the list and<br>
click <i>Delete Zone.</i>
<br>

<?php 
$fcz->query("select * from zone order by zoneid"); 
?>

<select name=zoneid size="<?php echo $zct+1?>"
 onFocus="currfield='zoneid'">
<option value="" selected>[no change]
<?php 
while( $fcz->next_record() ){?>
<option value="<?php echo $fcz->f("zoneid")?>">
<?php 
echo substr($fcz->f("zonedescr"),0,15);
}?>
</select>
<p>

<input type="submit" value="Delete Zone"
 onClick="closehelp()"><br>

</form>

<?php  $fcz->free_result(); ?>

</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
