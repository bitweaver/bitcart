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
// addslashes() for non-numbers, no exceptions

//if $zid & $lid are found, they should be changed
//to $zoneid and $langid. Once all maint files
//are done, $zid and $lid can probably be eliminated.

//$zid        =   (int)getparam('zid');
//$lid        =   (int)getparam('lid');
$zoneid     =   (int)getparam('zoneid');
$langid     =   (int)getparam('langid');
$act		=	getparam('act');
$logo		=	getparam('logo');
$logoh		=	(int)getparam('logoh');
$logow		=	(int)getparam('logow');
$button		=	getparam('button');
$buttonh		=	(int)getparam('buttonh');
$buttonw		=	(int)getparam('buttonw');
$descr		=	getparam('descr');
$back		=	getparam('back');
$banr		=	getparam('banr');
$banrh		=	(int)getparam('banrh');
$banrw		=	(int)getparam('banrw');
$sku		=	getparam('sku');
$cat		=	(int)getparam('cat');
$catact		=	(int)getparam('catact');
$catunder	=	(int)getparam('catunder');
$prodpage	=	(int)getparam('prodpage');
$catcols	=	(int)getparam('catcols');
$catmast	=	getparam('catmast');
$cattmpl	=	getparam('cattmpl');
$catsort	=	getparam('catsort');
$catfree	=	getparam('catfree');
$webbg		=	getparam('webbg');
$weblink	=	getparam('weblink');
$webvlink	=	getparam('webvlink');
$webalink	=	getparam('webalink');
$webtext	=	getparam('webtext');


// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

// don't let this underflow...
if( $catcols <= 0 ){
	$catcols = 1;
}

$droot="BITCART_PKG_PATH";

// make sure these values are hex
if ((strlen($webbg)>0      && strlen($webbg)!=6)   ||
      (strlen($webtext)>0    && strlen($webtext)!=6) ||
      (strlen($weblink)>0    && strlen($weblink)!=6)   ||
      (strlen($webvlink)>0   && strlen($webvlink)!=6)   ||
      (strlen($webalink)>0   && strlen($webalink)!=6) ) {?>
	<p>One of the hex value fields is not equal to 6 characters; they
	may be empty, but if given they should be exactly 6 hex digits.
	</p><p>Please click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.</p>
<?php exit;} // end error check if statement

// if image size is not provided, calculate it
if($logo&&($logoh==0||$logow==0)){
 $imgs=getimagesize(imagepath($logo).$logo);
 $logow=(int)$imgs[0];
 $logoh=(int)$imgs[1];
} // end logo if statement
if($banr&&($banrh==0||$banrw==0)){
 $imgs=getimagesize(imagepath($banr).$banr);
 $banrw=(int)$imgs[0];
 $banrh=(int)$imgs[1];
} // end banner if statement
if($button&&($buttonh==0||$buttonw==0)){
 $imgs=getimagesize(imagepath($button).$button);
 $buttonw=(int)$imgs[0];
 $buttonh=(int)$imgs[1];
} // end banner if statement

$fcc = new FC_SQL;
$fdc = new FC_SQL;
$fdcu = new FC_SQL;

// get the path to cat from the upline cat	
if( $catunder ){
  $path= new FC_SQL;
  $path->query("select catpath from cat where catval=$catunder");
  $path->next_record();
  $pathto=$path->f("catpath");
  $path->free_result();
}else{
  $pathto = ':';
}

if($act=="insert") {

 // find out what the new category catval is
 $max_ref = new FC_SQL;
 if ( $databaseeng == 'odbc' && $dialect == 'solid' ){
  $max_ref->query("select catval from cat where rownum < 2 order by catval desc");
 }elseif ( $databaseeng == 'postgres' ){
  $max_ref->query("select catval from cat order by catval desc limit 1 offset 0");
 }elseif ( $databaseeng == 'mssql' ){
  $max_ref->query("select top 1 catval from cat order by catval desc");
 }elseif ( $databaseeng == 'oracle' ){
  $max_ref->query("select catval from cat where rownum < 2 order by catval desc");
 }elseif ( $databaseeng == 'mysql' ){
  $max_ref->query("select catval from cat order by catval desc limit 0,1");
 } // end what category if statement

 $max_ref->next_record();	
 $ref=(int)$max_ref->f("catval")+1;
 $max_ref->free_result();

 $res = $fcc->query("insert into cat (".
 "catval,catzid,catlid,catlogo,catlogoh,catlogow, ".
 "catbutton,catbuttonh,catbuttonw,catdescr,catback,catbg,".
 "catlink,catvlink,catalink,catbanr,catbanrh,catbanrw,".
 "catsku,cattext,catmast,cattmpl,catsort,catfree,".
 "catact,catunder,catpath,catprodpage,catcols".
 ") values (".
 "$ref,$zoneid,$langid,'$logo',$logoh,$logow, ".
 "'$button',$buttonh,$buttonw,'$descr','$back','$webbg',".
 "'$weblink','$webvlink','$webalink','$banr',$banrh,$banrw,".
 "'$sku','$webtext','$catmast','$cattmpl','$catsort','$catfree',".
 "$catact,$catunder,'$pathto$ref:',$prodpage,$catcols)");
 // the 0 above sets it to a master category
	
} elseif($act=="update") {

  $res = $fcc->query("update cat ".
  "set catlogo='$logo', catlogoh=$logoh, catlogow=$logow, ".
  "catbutton='$button', catbuttonh=$buttonh, catbuttonw=$buttonw, catdescr='$descr',".
  "catback='$back', catbg='$webbg', cattext='$webtext', catlink='$weblink', ".
  "catvlink='$webvlink', catalink='$webalink', catbanr='$banr', ".
  "catbanrh=$banrh, catbanrw=$banrw, catsku='$sku', catact=$catact, ".
  "catmast='$catmast', cattmpl='$cattmpl', catsort='$catsort', ".
  "catfree='$catfree', catprodpage=$prodpage, catunder=$catunder, ".
  "catpath='$pathto$cat:', catcols=$catcols ".
  "where catzid=$zoneid and catlid=$langid and catval=$cat"); 

  // they can't change the 'val' field, no need to touch prodcat

} elseif($act=="delete"){

  $res = $fcc->query("delete from cat ".
  	"where catzid=$zoneid and catlid=$langid and catval=$cat"); 

  // delete references to this cat from others
  $dc = $fdc->query("select catval,catpath from cat ".
  	"where catzid=$zoneid and catlid=$langid and catpath like '%:$cat:%'"); 
  while( $fdc->next_record ){
  	$cv = (int)$fdc->f('catval');
  	$cp = (int)$fdc->f('catpath');
	$cp = ereg_replace(".*:$cat:.*", ":", $cp);
	$fdcu->query("update cat set catpath='$cp' where catval=$cv"); 
  } // end delete while loop

  // Delete this category from the product/category database

  $fcc->query("delete from prodcat ".
	"where pcatzid=$zoneid and pcatval=$cat"); 

} // end insert/update/delete if statement
if(!$res){
	$fcc->rollback();
	echo "<b>failure updating web: $res</b><br />\n";
}else{
	$fcc->commit();
	echo "Work committed.<br />\n";
} // end rollback
?>

<p>

<?php if($act=="insert") {?>
<a href="categoryadd.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>">
Return to Add Category Page
<?php }else{?>
<a href="categoryndx.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>">
Return to Category Maintenance Page
<?php }?></a>
<p></p>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page
</a>

<?php require('./footer.php');?>
