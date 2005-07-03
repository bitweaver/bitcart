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
$fct = new FC_SQL;
$fcw = new FC_SQL;
$fcz = new FC_SQL;
?>

<h2 align=center>Add A Shipping Profile</h2>
<hr>
<p>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center colspan=3 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>

</td></tr>
<tr><td colspan=3 align=center bgcolor=#ffffff>

<form method="post" name="shipadd" action="shipupd.php">

<input type=hidden name=act value=new>
<input type=hidden name=ssku value=<?php echo $ssku?>>
<input type=hidden name=srch value=<?php echo $srch?>>
<input type=hidden name=show value=<?php echo $show?>>
<input type=hidden name=oldsku value="">

Selected Zone: <?php 
$fcz->query("select zonedescr from zone ".
	"where zoneid=$zoneid order by zoneid"); 
$fcz->next_record();
echo $fcz->f("zonedescr");
$fcz->free_result();
?><br>

Selected Language: <?php 
$fcz->query("select langdescr from lang ".
	"where langzid=$zoneid and langid=$langid order by langid"); 
$fcz->next_record();
echo $fcz->f("langdescr");
$fcz->free_result();
?><br>

</td></tr>
<tr><td valign=top align=left colspan=1 bgcolor=#ffffff>

Shipping Profile Description: <i>max 80 characters</i><br>
<input name="shipdescr" size=40 onFocus="currfield='shipdescr'"><br>

</td><td colspan=2 bgcolor=#ffffff align=left valign=top>

Service Code:<br>
<input name="shipsvccode" size=40 onFocus="currfield='shipsvccode'"><br>

</td></tr>
<tr><td valign=top align=center bgcolor=#ffffff>

Shipping Method:<br>
<i><b>change the script below also</b></i><br>
<select name="shipmeth" size=4 onFocus="currfield='shipmeth'"
 onChange="
  if( document.shipadd.shipmeth.options.selectedIndex == 1 ){
   document.shipadd.shipcalc.value = 'shipthreshper.php';
  }else if( document.shipadd.shipmeth.options.selectedIndex == 2 ){
   document.shipadd.shipcalc.value = 'shippercent.php';
  }else if( document.shipadd.shipmeth.options.selectedIndex == 3 ){
   document.shipadd.shipcalc.value = 'shiplineitem.php';
  }else if( document.shipadd.shipmeth.options.selectedIndex == 4 ){
   document.shipadd.shipcalc.value = 'shiplineall.php';
  }else if( document.shipadd.shipmeth.options.selectedIndex == 5 ){
   document.shipadd.shipcalc.value = 'shipthreshweight.php';
  }
 ">
<option value="">[select a shipping method]
<option value="1">Order Price Threshold
<option value="2">Order Total Percent
<option value="3">Line Item Cost (per item)
<option value="4">Line Item Cost (all items)
<option value="5">Order Weight Threshold
</select><br>

</td><td valign=top align=center bgcolor=#ffffff>

Shipping Charge Percent:<br>
<i>if percent method used<br>format: 0.NNNN</i><br>
<input name="shippercent" size=10 onFocus="currfield='shippercent'"><br>

</td><td valign=top align=center bgcolor=#ffffff>

First Line Item Cost:<br>
<i>if per line item is used</i><br>
<input name="shipitem" size=10 onFocus="currfield='shipitem'"><br>

Subsequent Line Item Cost:<br>
<input name="shipitem2" size=10 onFocus="currfield='shipitem'"><br>

</td></tr>
<tr><td valign=top align=center bgcolor=#ffffff>

Ship Charge Calculation Script:<br>
<input name="shipcalc" size=24 onFocus="currfield='shipcalc'"><br>

</td><td valign=top align=center bgcolor=#ffffff>

<?php /* unused
Auxiliary SQL Data Table 1:<br>
<input name="shipaux1" size=16 onFocus="currfield='shipaux'"><br>
 */ ?>

</td><td valign=top align=center bgcolor=#ffffff>

<?php /* unused
Auxiliary SQL Data Table 2:<br>
<input name="shipaux2" size=16 onFocus="currfield='shipaux'"><br>
 */ ?>

</td></tr>
<tr><td valign="top" align="left" colspan="3" bgcolor="#ffffff">
<b>Shipping Threshold Chart</b><br>
<i>to set a maximum shipping value, on the last entry, leave the
Order Total To: column blank and put the maximum shipping price
in the Shipping Price column.
<p>
If the shipping price is greater than or equal to 1, that price
is taken as is.  If the shipping prices is greater than 0 and less
than 1, it is a percent value to multiply against the order total
for items eligible for shipping charges.</i><br>
</td></tr>

<tr><td valign=top align=center colspan=3 bgcolor=#ffffff>
<table border=0 cellpadding=4 cellspacing=0 bgcolor=#666666 width="100%" class="text">
<tr><td valign=top align=center bgcolor=#ffffff>Shipping Threshold<br></td>
<td valign=top align=center bgcolor=#ffffff>Weight Threshold<br></td></tr>
<tr><td valign=top align=center bgcolor=#ffffff>
<table border=0 cellpadding=4 cellspacing=0 bgcolor=#666666 width="100%" class="text">

<tr><td valign=top align=left bgcolor=#ffffff>
Order Total To:<br>
<i>enter in ascending order</i><br>
</td><td valign=top align=left bgcolor=#ffffff>
Price/Percent:<br>
<i>price: #.##</i><br>
<i>percent: 0.##</i><br>
</td></tr>

<?php 
$i=0;
$maxship=10;
/* $i will be the total number of shipping levels; to increase the number of
   shipping levels, just add more interations of the stuff below.  This
   must be duplicated on the shipmod.html page */

// BEGINNING OF SHIPPING STEP ZONES

while($i<$maxship){?>
<tr><td valign=top align=left bgcolor=#ffffff>
<input name="shiphi[]" size=10 onFocus="currfield='shipthresh'"><br>
</td><td valign=top align=left bgcolor=#ffffff>
<input name="shippr[]" size=10 onFocus="currfield='shipthresh'"><br>
<?php $i++;?>
</td></tr>
<?php }

// END OF SHIPPING STEP ZONES ?>
</table>
</td><td valign=top align=center bgcolor=#ffffff>

<table border=0 cellpadding=4 cellspacing=0 width="100%" bgcolor=#666666 class="text">
<tr><td valign=top align=left bgcolor=#ffffff>
Weight Total To:<br>
<i>enter in ascending order</i><br><br>
</td><td valign=top align=left bgcolor=#ffffff>
Price:<br>
</td></tr>

<?php 
$i=0;
$maxship=10;
/* $i will be the total number of shipping levels; to increase the number of
   shipping levels, just add more interations of the stuff below.  This
   must be duplicated on the shipmod.php page */

// BEGINNING OF WEIGHT STEP ZONES

while($i<$maxship){?>
<tr><td valign=top align=left bgcolor=#ffffff>
<input name="weighthi[]" size=10 onFocus="currfield='shipthresh'"><br>
</td><td valign=top align=left bgcolor=#ffffff>
<input name="weightpr[]" size=10 onFocus="currfield='shipthresh'"><br>
<?php $i++;?>
</td></tr>
<?php }

// END OF WEIGHT STEP ZONES ?>
</table></td></tr></table></td></tr>


<tr><td colspan="3" align="center" bgcolor="#ffffff">

<input type=hidden name=numlvl value="<?php echo $i?>">
<input type=hidden name=zonewhsid value="0">
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Add Profile" onClick="closehelp()">
<input type="reset" value="Clear Form"><br>

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
