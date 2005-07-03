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
$act    = getparam('act');

$title = getparam('title');
$url   = getparam('url');
$loc   = (int)getparam('loc');

$link  =  (int)getparam('link');
$scount = (int)getparam('scount');

//array lseq and the array link are both handled below

// ==========  end of variable loading  ==========


require('./admin.php');
require('./header.php');

$fcc = new FC_SQL;

if($act=="add") {

if ((strlen($title)==0) ||
      (strlen($url)==0) ) {?>
	<p>One of the fields was left blank. Both fields must be filled
	in to have a functioning link.<p>Please click the
	&quot;Back&quot; button on your browser	and correct the
	errors.  Thank you.</p>
<?php exit;}

 $max_seq = new FC_SQL;
 if ( $databaseeng == 'odbc' && $dialect == 'solid' ){
  $max_seq->query("select seq from auxlinks where rownum < 2 order by seq desc");
 }elseif ( $databaseeng == 'postgres' ){
  $max_seq->query("select seq from auxlinks order by seq desc limit 1,0");
 }elseif ( $databaseeng == 'mssql' ){
  $max_seq->query("select top 1 seq from auxlinks order by seq desc");
 }elseif ( $databaseeng == 'oracle' ){
  $max_seq->query("select seq from auxlinks where rownum < 2 order by seq desc");
 }elseif ( $databaseeng == 'mysql' ){
  $max_seq->query("select seq from auxlinks order by seq desc limit 0,1");
 }
 $max_seq->next_record();	
 $seq=$max_seq->f("seq")+1;
 $max_seq->free_result();

 $res = $fcc->query("insert into auxlinks (".
 "seq,loc,title,url) values ($seq,$loc,'$title','$url')");
	
} elseif($act=="mod") {

  $res = $fcc->query("update auxlinks ".
  "set loc=$loc,title='$title', url='$url' where rid=$link"); 

} elseif($act=="del"){

  $res = $fcc->query("delete from auxlinks ".
  	"where rid=$link"); 
} elseif($act=="seq"){

  $i=0;
  while ( $i < $scount ) {
   $seq=(int)getparam('lseq'.$i);
   $rid=(int)getparam('link'.$i);
   $res = $fcc->query(
	"update auxlinks set seq=$seq where rid='$rid'");
   $i++;
  }
}
if(!$res){
	$fcc->rollback();
	echo "<b>failure updating auxlinks: $res</b><br />\n";
}else{
	$fcc->commit();
	echo "Work committed.<br />\n";
}
?>

<p>

<?php if($act=="add") {?>
<a href="auxlinkadd.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>">
Return to Add Link Page
<?php }elseif($act!="seq"){?>
<a href="auxlinkndx.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>">
Return to Dynamic Link Maintenance Page
<?php }?></a>
<p>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page
</a>
</p>

<?php require('./footer.php');?>
