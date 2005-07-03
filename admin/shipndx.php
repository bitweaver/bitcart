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
require('./header.php');

$fcs = new FC_SQL;
?>

<h2 align=center>Shipping Table Maintenance</h2>
<hr>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td colspan="2" align="center" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
Return To Central Maintenance Page</a>

</td></tr>
<tr><td align="center" bgcolor="#ffffff">
<b>Modify Shipping Table</b>
<td align="center" bgcolor="#ffffff">
<b>Delete Shipping Table</b>
</td></tr>
<tr><td align=center bgcolor=#ffffff>

<form method="post" name=shipmod action="shipmod.php">

<input type=hidden name=act value=update>
<input type=hidden name=show value=<?php echo $show?>>
<input type=hidden name=ssku value=<?php echo $ssku?>>
<input type=hidden name=srch value=<?php echo $srch?>>

To modify an existing shipping table,<br>
select its name from the list and<br>
click <i>Modify Shipping Table.</i>
<br>

<?php 
$fcs->query(
	"select count(*) as cnt from ship ".
	"where shipzid=$zoneid and shiplid=$langid");
$fcs->next_record();
$st=(int)$fcs->f('cnt');
$fcs->free_result();

$fcs->query(
	"select * from ship ".
	"where shipzid=$zoneid and shiplid=$langid order by shipid");
?>
<select name=shipid size="<?php echo $st+1?>"
 onChange="document.shipmod.action='shipmod.php';submit();"
 onFocus="currfield='shipid';">
<option value="" selected>[no change]
<?php while( $fcs->next_record() ){?>
<option value="<?php echo $fcs->f("shipid")?>"><?php echo substr($fcs->f("shipdescr"),0,50)?>
<?php }?>
</select>

<p>

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Modify Shipping Table"
 onClick="closehelp()"><br>

</form>

</td><td align=center bgcolor=#ffffff>

<form method="post" name=shipdel action="shipupd.php">

<input type=hidden name=act value=delete>
<input type=hidden name=show value=<?php echo $show?>>
<input type=hidden name=ssku value=<?php echo $ssku?>>
<input type=hidden name=srch value=<?php echo $srch?>>

To delete an existing shipping table,<br>
select its name from the list and<br>
click <i>Delete Shipping Table.</i>
<br>

<?php 
$fcs->query(
	"select * from ship ".
	"where shipzid=$zoneid and shiplid=$langid order by shipid"); 
?>

<select name=shipid size="<?php echo $st+1?>"
 onFocus="currfield='shipid'">
<option value="" selected>[no change]
<?php 
while( $fcs->next_record() ){?>
<option value="<?php echo $fcs->f("shipid")?>"><?php echo substr($fcs->f("shipdescr"),0,15)?>
<?php }?>
</select>
<p>

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Delete Shipping Table"
 onClick="closehelp()"><br>

</form>

</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
