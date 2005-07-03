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

$begyear = (int)getparam('begyear');
$begmo   = (int)getparam('begmo');
$begday  = (int)getparam('begday');

$endyear = (int)getparam('endyear');
$endmo   = (int)getparam('endmo');
$endday  = (int)getparam('endday');

// ==========  end of variable loading  ==========


require('./admin.php');
require('./header.php');

$fcl = new FC_SQL;
$fcp = new FC_SQL;

if(!$zoneid || !$langid){?>
	Please click Back and select a zone and/or language.  Thank you.
<?php exit;}?>

<h2 align=center>Order Summary Statistics</h2>
<hr>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align=center valign=middle colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Central Maintenance Page</a><br>

</td></tr>

<?php
if($begyear){
 $datequal=" where ".
			mktime(0,0,0,$begmo,$begday,$begyear).
			" < tstamp and tstamp < ".
			mktime(23,59,0,$endmo,$endday,$endyear);
}else{
 $datequal = '';
}
//echo "datequal:$datequal<br>";

$fcc = new FC_SQL;
$fcc->query("select count(*) as cnt from cust");
$fcc->next_record();
$ctotal = $fcc->f("cnt");
$fcc->free_result();

$fcc = new FC_SQL;
// $fcc->query("select sum(custbtotal) as tot from cust${datequal}");
$fcc->query("select sum(ototal) as tot from ohead${datequal}");
$fcc->next_record();
$btotal = (int)$fcc->f("tot");
$fcc->free_result();

if($datequal){
 $minqual = $datequal . " and ototal > 0";
}else{
 $minqual = " where ototal > 0";
}

$fcc = new FC_SQL;
$fcc->query("select count(*) as cnt from ohead${minqual}");
$fcc->next_record();
$ototal = (int)$fcc->f("cnt");
$fcc->free_result();

$fcc = new FC_SQL;
$fcc->query("select min(ototal) as minimo from ohead${minqual}");
$fcc->next_record();
$min = (int)$fcc->f("minimo");
$fcc->free_result();

$fcc = new FC_SQL;
$fcc->query("select max(ototal) as massimo from ohead${minqual}");
$fcc->next_record();
$max = (int)$fcc->f("massimo");
$fcc->free_result();
?>

<?php if($datequal){ ?>
<tr><td align=left valign=top colspan=2 bgcolor=#ffffff>
<i>
Totals between
<?php echo $begyear ?>/
<?php echo $begmo ?>/
<?php echo $begday ?> 00:00:00 and 
<?php echo $endyear ?>/
<?php echo $endmo ?>/
<?php echo $endday ?> 23:59:00
</i>
</td></tr>
<?php } ?>

<tr><td align=left valign=top bgcolor=#ffffff>
Total Customers:
</td><td align=right bgcolor=#ffffff>
<?php echo printf("%8d",$ctotal) ?><br>
</td></tr>

<tr><td align=left valign=top bgcolor=#ffffff>
Total Orders: <i>(order total &gt; 0)</i>
</td><td align=right bgcolor=#ffffff>
<?php echo printf("%8d",$ototal) ?><br>
</td></tr>

<tr><td align=left valign=top bgcolor=#ffffff>
Total Revenue:
</td><td align=right bgcolor=#ffffff>
<?php echo printf("$%8.2f",$btotal) ?><br>
</td></tr>

<tr><td align=left valign=top bgcolor=#ffffff>
Average Order Amount:
</td><td align=right bgcolor=#ffffff>
<?php
if( $ototal ){
 $tmptotal = $btotal/$ototal;
}else{
 $tmptotal = 0;
}
echo sprintf("$%8.2f",$tmptotal);
?><br>
</td></tr>

<tr><td align=left valign=top bgcolor=#ffffff>
Minimum Order Amount:
</td><td align=right bgcolor=#ffffff>
<?php echo printf("$%8.2f",$min) ?><br>
</td></tr>

<tr><td align=left valign=top bgcolor=#ffffff>
Maximum Order Amount:
</td><td align=right bgcolor=#ffffff>
<?php echo printf("$%8.2f",$max) ?><br>
</td></tr>

<tr><td align=left valign=top colspan=2 bgcolor=#ffffff>

<center>Optional Report Date Range:<br>
<i>all date values must be entered</i></center>
<p>
<form method=post action="<?php echo $PHP_SELF ?>">
Beginning 4 Digit <b>Y</b>ear / <b>M</b>onth / <b>D</b>ay:<br>
<input name=begyear size=4> / <input name=begmo size=2> / <input name=begday size=2>
<p>
Ending 4 Digit <b>Y</b>ear / <b>M</b>onth / <b>D</b>ay:<br>
<input name=endyear size=4> / <input name=endmo size=2> / <input name=endday size=2><br>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=submit value="Search With These Dates">
</form>

</td></tr>

<tr><td align=center valign=top colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
