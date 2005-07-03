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

$numkeys = (int)getparam('numkeys');

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcl = new FC_SQL;
$fcp = new FC_SQL;

if(!$zoneid || !$langid){?>
	Please click Back and select a zone and/or language.  Thank you.
<?php exit;}?>

<h2 align=center>Order History Query Results</h2>
<hr>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" class="text">
<tr><td align=center valign=middle colspan=3 bgcolor="#FFFFFF">

<a href="keyquery.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Keyword Query Page</a>
<br>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Central Maintenance Page</a><br>

</td></tr>
<tr>
<td align=left bgcolor="#FFFFFF"><b>Keyword</b></td>
<td align=left bgcolor="#FFFFFF"><b>Total Searches</b></td>
<td align=left bgcolor="#FFFFFF"><b>Total Products Found</b></td>
</tr>

<?php
$fckey = new FC_SQL;

$numkeys=(int)$numkeys;

if( $numkeys ){
 if ( $databaseeng == 'odbc' && $dialect == 'solid' ){
  $numkeys++;
  $fckey->query("select distinct * from keyword where rownum < $numkeys ".
  				"order by keycnt,keyres desc");
 }elseif ( $databaseeng == 'postgres' ){
  $numkeys++;
  $fckey->query("select distinct * from keyword order by keycnt,keyres desc limit $numkeys,1");
 }elseif ( $databaseeng == 'mssql' ){
  $fckey->query("select distinct top $numkeys * from keyword order by keycnt,keyres desc");
 }elseif ( $databaseeng == 'oracle' ){
  $fckey->query("select distinct * from keyword where rownum < $numkeys ".
  				"order by keycnt,keyres desc");
 }elseif ( $databaseeng == 'mysql' ){
  $fckey->query("select distinct * from keyword order by keycnt,keyres desc limit 0,$numkeys");
 }
}else{
 $fckey->query("select distinct * from keyword order by keycnt,keyres desc");
}
while( $fckey->next_record() ){
 $keyval = $fckey->f("keyval");
 $keycnt = $fckey->f("keycnt");
 $keyres = $fckey->f("keyres");
 echo
 '<tr><td bgcolor="#FFFFFF">'.$keyval.'</td><td bgcolor="#FFFFFF">'.$keycnt.'</td><td bgcolor="#FFFFFF">'.$keyres.'</td></tr>'."\n";
}
$fckey->free_result();
?>

</td></tr>
<tr><td align=center valign=top colspan=3 bgcolor="#FFFFFF">

<a href="keyquery.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Keyword Query Page</a>
<br>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp()">Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
