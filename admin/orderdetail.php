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

header("Last-Modified: ". gmdate("D, d M Y H:i:s",time()) . " GMT");

require_once( BITCART_PKG_PATH.'functions.php');
// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcl = new FC_SQL;
$fcp = new FC_SQL;

if(!$zoneid || !$langid){?>
	Please click Back and select a zone and/or language.  Thank you.
<?php exit;}?>

<h2 align=center>Order Record Query</h2>
<hr>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center valign=middle colspan=1 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Central Maintenance Page</a><br>

</td></tr>

<?php
$fcord = new FC_SQL;
$fcord->query("select count(*) as cnt from ohead");
$fcord->next_record();
$ototal = $fcord->f("cnt");
$fcord->free_result();
?>

<tr><td align=left valign=top colspan=1 bgcolor=#ffffff>
<i>Total Orders in Database: <?php echo $ototal; ?></i>
<p>

<form method=post action="orderdetailrslt.php">
Order ID to View:<br>
<input name=orderid size=20>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=hidden name=ototal value=<?php echo $ototal?>>
<input type=submit value="Show Selected Order">
</form>

<form method=post action="orderdetailrslt.php">
Order ID to View:<br>
<SELECT NAME=orderid size=1>
<option value="">select an order</option>
<?php
$fcods = new FC_SQL;
$fcods->query("select tstamp,orderid from ohead where ".
              "complete > 0 order by tstamp desc limit 25");
while ($fcods->next_record()){
$isnu=getdate($fcods->f("tstamp"));
?>
<option value="<?php echo $fcods->f("orderid")?>"><?php echo $fcods->f("orderid")?> <?php echo
$isnu['year']."-".$isnu['mon']."-".$isnu['mday']?></option>
<?php
}
$fcods->free_result();
/*
In orde to change the way the date is displayed you can change
mday   = day
mon    = month
year   = year
Change the number behind limit to get more or less orders displayed.

This is just the first version, more to come later.
bvo
*/
?>
</SELECT>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=hidden name=ototal value=<?php echo $ototal?>>
<input type=submit value="Show Selected Order">
</form>


</td></tr>
<tr><td align=center valign=top colspan=1 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
