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

header("Expires: 0");
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );
require_once( BITCART_PKG_PATH.'functions.php');
require('./admin.php');
require('./header.php');

$fcl = new FC_SQL;
$fcp = new FC_SQL;

$now = time();
// get today, then rewind to midnight
$yr4=date("Y",$now);
$mon=date("m",$now);
$day=date("d",$now);

$today_mdy = $mon.'/'.$day.'/'.$yr4;

$today=mktime(0,0,0,$mon,$day,$yr4);

// now figure time for midnight
$midnight=$today+86400;

$fcl = new FC_SQL;

// figure a few order stats and report
$fcl->query("select count(*) as cnt from ohead ".
	"where $today<=tstamp and tstamp<$midnight");
$fcl->next_record();
$oheadtot=(int)$fcl->f("cnt");
$fcl->free_result();

$fcl->query("select count(*) as cnt from ohead ".
 	"where complete=-1 and $today<=tstamp and tstamp<$midnight");
$fcl->next_record();
$oheadinit=(int)$fcl->f("cnt");
$fcl->free_result();

$fcl->query("select count(*) as cnt from ohead ".
	"where complete=0 and $today<=tstamp and tstamp<$midnight");
$fcl->next_record();
$oheadaban=(int)$fcl->f("cnt");
$fcl->free_result();

$fcl->query("select count(*) as cnt from ohead ".
	"where complete=1 and $today<=tstamp and tstamp<$midnight");
$fcl->next_record();
$oheadcomp=(int)$fcl->f("cnt");
$fcl->free_result();

$fcl->query("select sum(contrib) as cnt from ohead ".
	"where complete=1 and $today<=tstamp and tstamp<$midnight");
$fcl->next_record();
$contrib_total=(int)$fcl->f("cnt");
$fcl->free_result();

$fcl->query("select sum(ototal) as cnt from ohead ".
	"where complete=1 and $today<=tstamp and tstamp<$midnight");
$fcl->next_record();
$order_total=(int)$fcl->f("cnt");
$fcl->free_result();

$prod_total = rnd($order_total - $contrib_total);

?>
<h2 align=center>Real-Time Order Statistics for <?php echo $today_mdy ?></h2>
<hr>
<center>
<table width=650 border=0 bgcolor=#666666 cellpadding=3 cellspacing=1 class="text">
<tr><td colspan=2 bgcolor=#FFFFFF align=center>
<a href="ordertoday.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Refresh Results</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Central Maintenance Page</a><br>
</td></tr>
<tr><td bgcolor=#FFFFFF>
Initialized Orders:
</td><td align=right bgcolor=#FFFFFF>
<?php echo sprintf("%4d",$oheadinit); ?>
</td></tr>
<tr><td bgcolor=#FFFFFF>
Incomplete Orders:
</td><td align=right bgcolor=#FFFFFF>
<?php echo sprintf("%4d",$oheadaban); ?>
</td></tr>
<tr><td bgcolor=#FFFFFF>
Completed Orders:
</td><td align=right bgcolor=#FFFFFF>
<?php echo sprintf("%4d",$oheadcomp); ?>
</td></tr>
<tr><td bgcolor=#FFFFFF>
Total All Orders:
</td><td align=right bgcolor=#FFFFFF>
<?php echo sprintf("%4d",$oheadtot); ?>
</td></tr>
<tr><td bgcolor=#FFFFFF>
Total Contributions:
</td><td align=right bgcolor=#FFFFFF>
<?php echo sprintf("%10.2f",$contrib_total); ?>
</td></tr>
<tr><td bgcolor=#FFFFFF>
Total Product Orders:
</td><td align=right bgcolor=#FFFFFF>
<?php echo sprintf("%10.2f",$prod_total); ?>
</td></tr>
<tr><td bgcolor=#FFFFFF>
Total Amount:<br>
(products and contributions)
</td><td align=right bgcolor=#FFFFFF>
<?php echo sprintf("%10.2f",$order_total); ?>
</td></tr>
<tr><td colspan=2 bgcolor=#FFFFFF align=center>
<a href="ordertoday.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Refresh Results</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Central Maintenance Page</a><br>
</td></tr>
</table>
</center>
<?php require('./footer.php');?>
