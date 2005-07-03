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

// a sometimes useful utility program to run through the pending
// orders in the SQL database and show what is there.

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

// if $zid or $lid are found, they should be changed
// to $zoneid or $langid, respectively. Once all
// maint files are done, $zid and $lid can probably
// be eliminated.

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

// ==========  end of variable loading  ==========

require('./admin.php');

$oh = new FC_SQL;
$ol = new FC_SQL;
$op = new FC_SQL;

$oh->query("select count(*) as cnt from ohead");
$oh->next_record();
$cnt=$oh->f("cnt");
echo "Count:$cnt\n\n";
$oh->free_result();

$oh->query("select orderid,tstamp from ohead");
$tprod=0;
while( $oh->next_record() ){
  $ts=$oh->f("tstamp");
  $oi=$oh->f("orderid");
  $ds=(int)date("d",$ts);

  $ol->query("select * from oline where orderid='$oi'");
  $j=0;
  while( $ol->next_record() ){
    if(!$j){ echo "\ncustid:$oi  date: $ds\n"; $tord++; }
    $qt=(int)$ol->f("qty");
    $sk=$ol->f("sku");

    $op->query("select prodprice from prod where prodsku='$sk'");
    if( $op->next_record() ){
      $prc=(double)$op->f("prodprice");
    } else {
      $prc = 0;
    }
    $op->free_result();

    $ltot = $prc * $qt;
    $tamt += (double)$ltot;
    echo "   sku:$sk   qty: $qt   price: $prc  ltot: $ltot\n";

    $tprod++;
    $j++;
  }
  $ol->free_result();
}
echo "\ntotal orders: $tord   total products: $tprod  pending: $tamt\n";
?>
