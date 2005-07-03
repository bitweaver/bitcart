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

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$show   = (int)getparam('show');
$srch   = (int)getparam('srch');
$langzid = (int)getparam('langzid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');
?>

<h2 align=center>Language Profile Maintenance</h2>
<hr>
<p>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br>

</td></tr>
<tr><td align=center bgcolor=#ffffff>

<b>Modify An Existing Language Profile</b>

</td><td align=center bgcolor=#ffffff>

<b>Delete An Existing Language Profile</b>

</td></tr>
<tr><td align=center bgcolor=#ffffff>

<form name=langmod method="post" action="langmod.php">

To modify an existing language profile,<br>
select its name from the list and<br>
click <i>Modify Selected Profile</i>.
<br>

<?php 
$fcl = new FC_SQL;
$fcl->query(
	"select count(*) as cnt from lang ".
	"where langzid=$zoneid and langid=$langid"); 
$fcl->next_record();
$len=$fcl->f("cnt");
$fcl->free_result();
?>

<select name=lngid size="<?php echo $len+1; ?>"
 onChange="document.langmod.action='langmod.php';submit();">
<option value="" selected>[no change]

<?php 
$fcl->query(
	"select * from lang ".
	"where langzid=$zoneid and langid=$langid order by langid"); 
while( $fcl->next_record() ) {?>
 <option value="<?php echo $fcl->f("langid")?>"><?php echo $fcl->f("langdescr")?>
<?php 
}
$fcl->free_result();
?>
</select>
<p>

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=hidden name=act value=update>
<input type="submit" value="Modify Selected Profile"><br>

</form>

</td><td align=center bgcolor=#ffffff>

<form method="post" action="langupd.php">

To delete an existing language profile,<br>
select its name from the list and<br>
click <i>Delete Selected Profile.</i>
<br>

<select name=lngid size="<?php echo $len+1; ?>">
<option value="" selected>[no change]
<?php 
$fcl->query(
	"select * from lang ".
	"where langzid=$zoneid and langid=$langid order by langid"); 
while( $fcl->next_record() ){?>
<option value="<?php echo $fcl->f("langid")?>"><?php echo $fcl->f("langdescr")?>
<?php 
}
$fcl->free_result();
?>
</select>
<p>

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=hidden name=langzid value="<?php echo $zoneid?>">
<input type=hidden name=act value=delete>
<input type="submit" value="Delete Selected Profile"><br>

</form>
</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br>

</td></tr>

</table>
</center>

<?php require('./footer.php');?>
