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
require('./admin.php');
require('./header.php');
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

$act = getparam('act');
$sku = getparam('sku');

$nsy = (int)getparam('nsy');
$nsm = (int)getparam('nsm');
$nsd = (int)getparam('nsd');
$ney = (int)getparam('ney');
$nem = (int)getparam('nem');
$ned = (int)getparam('ned');


// ==========  end of variable loading  ==========

  if (strlen($sku)>20) {?>
	The SKU description field exceeds 20 characters.
	<p>Please click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.
    <?php exit;
  }

if($act=="insert" || $act=="update"){
 if($nsm!=""&&$nsd!=""&&$nsy!=""&&$nem!=""&&$ned!=""&&$ney!=""){
  $sdate=mktime(0,0,0,$nsm,$nsd,$nsy);
  $ndate=mktime(0,0,0,$nem,$ned,$ney);
 }else{
  $sdate=0;
  $ndate=0;
 }
}

$fco = new FC_SQL;
$fcu = new FC_SQL;

if($act=="insert") {

  $fcu->query("select count(*) as cnt from oprod where oprodsku='$sku'");
  $fcu->next_record();
  if ( $fcu->f('cnt') == 0 ) {
   $fco->query("insert into oprod (oprodsku,ostart,oend,ozid) ".
	"values ('$sku',$sdate,$ndate,$zoneid)"); 
  }
  $fcu->free_result();

} elseif($act=="update") {

  $fco->query("update oprod ".
  "set oprodsku='$sku',ostart=$sdate,oend=$ndate where oprodsku='$sku'"); 

} elseif($act=="delete"){

  $fco->query("delete from oprod where oprodsku='$sku'"); 

}
$fco->commit();
?>

<p>

The <?php echo $act?> action is complete.

</p>
<p>
<?php if($act=="insert") {?>
<a href="oldprodadd.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>">
Return to Closeout Product Add Page
<?php }else{?>
<a href="oldprodndx.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>">
Return to Closeout Product Maintenance Page
<?php }?></a>
</p>
<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return to Central Maintenance Page
</a>

<?php require('./footer.php');?>
