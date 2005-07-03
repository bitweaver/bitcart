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
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );
header("Expires: 0");
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$webid  = (int)getparam('webid');

$webshowqty = getparam('webshowqty');
$webshowpreview = getparam('webshowpreview');
$webshowtopbot = getparam('webshowtopbot');
$flag_webshowqty = getparam('flag_webshowqty');
$webusenlbr = getparam('webusenlbr');

$act=getparam('act');
$logo=getparam('logo');
$logoh=(int)getparam('logoh');
$logow=(int)getparam('logow');
$cattext=getparam('cattext');
$headsku=getparam('headsku');
$headtext=getparam('headtext');
$headgraph=getparam('headgraph');
$headgraphh=(int)getparam('headgraphh');
$headgraphw=(int)getparam('headgraphw');
$footsku=getparam('footsku');
$foottext=getparam('foottext');
$footgraph=getparam('footgraph');
$footgraphh=(int)getparam('footgraphh');
$footgraphw=(int)getparam('footgraphw');
$newmast=getparam('newmast');
$newmasth=(int)getparam('newmasth');
$newmastw=(int)getparam('newmastw');
$newlogo=getparam('newlogo');
$newlogoh=(int)getparam('newlogoh');
$newlogow=(int)getparam('newlogow');
$specmast=getparam('specmast');
$specmasth=(int)getparam('specmasth');
$specmastw=(int)getparam('specmastw');
$speclogo=getparam('speclogo');
$speclogoh=(int)getparam('speclogoh');
$speclogow=(int)getparam('speclogow');
$viewlogo=getparam('viewlogo');
$viewlogoh=(int)getparam('viewlogoh');
$viewlogow=(int)getparam('viewlogow');

$daysinnew=(int)getparam('daysinnew');
$autodom=getparam('autodom');
$prodpage=getparam('prodpage');
$back=getparam('back');

$webbg=getparam('webbg');
$webtext=getparam('webtext');
$weblink=getparam('weblink');
$webvlink=getparam('webvlink');
$webalink=getparam('webalink');
$websort=getparam('websort');
$webfree=getparam('webfree');
$webdescr=getparam('webdescr');
$webdesctmpl=getparam('webdesctmpl');
$webtitle=getparam('webtitle');
$realhome=getparam('realhome');
$carthome=getparam('carthome');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$droot="BITCART_PKG_PATH";

$headtext=ereg_replace("\n","",$headtext);
$headtext=ereg_replace("\r","",$headtext);

$foottext=ereg_replace("\n","",$foottext);
$foottext=ereg_replace("\r","",$foottext);

$cattext=ereg_replace("\n","",$cattext);
$cattext=ereg_replace("\r","",$cattext);

// $webfree=addslashes($webfree);

if($zoneid==""){?>
  A zone was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.
    <?php exit;
}
if($langid==""){?>
  A language was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.
    <?php exit;
}

// build up webflags1
$webflags1=0;
if($webshowqty){
  $webflags1 |= (int)$flag_webshowqty;
}
if($webshowpreview){
  $webflags1 |= (int)$flag_webshowpreview;
}
if($webshowtopbot){
  $webflags1 |= (int)$flag_webshowtopbot;
}
if($webusenlbr){
  $webflags1 |= (int)$flag_webusenlbr;
}


$logow=0;
$logoh=0;
if( $logo ){
 if ( file_exists(imagepath($logo).$logo) ){
  $imgs=getimagesize(imagepath($logo).$logo);
  $logow=(int)$imgs[0];
  $logoh=(int)$imgs[1];
 }
}
$headgraphw=0;
$headgraphh=0;
if( $headgraph ){
 if ( file_exists(imagepath($headgraph).$headgraph) ){
  $imgs=getimagesize(imagepath($headgraph).$headgraph);
  $headgraphw=(int)$imgs[0];
  $headgraphh=(int)$imgs[1];
 }
}
$footgraphw=0;
$footgraphh=0;
if( $footgraph ){
 if ( file_exists(imagepath($footgraph).$footgraph) ){
  $imgs=getimagesize(imagepath($footgraph).$footgraph);
  $footgraphw=(int)$imgs[0];
  $footgraphh=(int)$imgs[1];
 }
}
$newlogow=0;
$newlogoh=0;
if( $newlogo ){
 if ( file_exists(imagepath($newlogo).$newlogo) ){
  $imgs=getimagesize(imagepath($newlogo).$newlogo);
  $newlogow=(int)$imgs[0];
  $newlogoh=(int)$imgs[1];
 }
}
$newmastw=0;
$newmasth=0;
if( $newmast ){
 if ( file_exists(imagepath($newmast).$newmast) ){
  $imgs=getimagesize(imagepath($newmast).$newmast);
  $newmastw=(int)$imgs[0];
  $newmasth=(int)$imgs[1];
 }
}
$speclogow=0;
$speclogoh=0;
if( $speclogo ){
 if ( file_exists(imagepath($speclogo).$speclogo) ){
  $imgs=getimagesize(imagepath($speclogo).$speclogo);
  $speclogow=(int)$imgs[0];
  $speclogoh=(int)$imgs[1];
 }
}
$specmastw=0;
$specmasth=0;
if( $specmast ){
 if ( file_exists(imagepath($specmast).$specmast) ){
  $imgs=getimagesize(imagepath($specmast).$specmast);
  $specmastw=(int)$imgs[0];
  $specmasth=(int)$imgs[1];
 }
}
$viewlogow=0;
$viewlogoh=0;
if( $viewlogo ){
 if ( file_exists(imagepath($viewlogo).$viewlogo) ){
  $imgs=getimagesize(imagepath($viewlogo).$viewlogo);
  $viewlogow=(int)$imgs[0];
  $viewlogoh=(int)$imgs[1];
 }
}

if((strlen($webbg)>0      && strlen($webbg)!=6)   ||
   (strlen($webtext)>0    && strlen($webtext)!=6) ||
   (strlen($weblink)>0    && strlen($weblink)!=6)   ||
   (strlen($webvlink)>0   && strlen($webvlink)!=6)   ||
   (strlen($webalink)>0   && strlen($webalink)!=6) ) {?>
	One of the hex value fields is not equal to 6 characters; they
	may be empty, but if given they should be exactly 6 hex digits.
	<p>Please click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.<p>
<?php exit;}
  
$fcw = new FC_SQL;

if($act=="update"){

$res=$fcw->query("update web set ".
	"webautodom='$autodom',".
	"realhome='$realhome',   carthome='$carthome',".
	"webback='$back',        webtitle='$webtitle', ".
	"weblogo='$logo',        weblogow=$logow, weblogoh=$logoh,".
	"webbg='$webbg',         webtext='$webtext', weblink='$weblink',".
	"webvlink='$webvlink',   webalink='$webalink',".
	"webhdsku='$headsku',    webhdtext='$headtext',".
	"webhdgraph='$headgraph',webhdgraphh=$headgraphh,".
	"webhdgraphw=$headgraphw,webftsku='$footsku', ".
	"webfttext='$foottext',  webftgraph='$footgraph',".
	"webftgraphh=$footgraphh,webftgraphw=$footgraphw,".
	"webdaysinnew=$daysinnew,webcattext='$cattext',".
	"webnewlogo='$newlogo',  webnewlogoh=$newlogoh,".
	"webnewlogow=$newlogow,  webnewmast='$newmast',".
	"webnewmasth=$newmasth,  webnewmastw=$newmastw,".
	"webspeclogo='$speclogo',webspeclogoh=$speclogoh,".
	"webspeclogow=$speclogow,webspecmast='$specmast',".
	"webspecmasth=$specmasth,webspecmastw=$specmastw,".
	"webviewlogo='$viewlogo',webviewlogow=$viewlogow,".
	"webviewlogoh=$viewlogoh,websort='$websort',".
	"webdescr='$webdescr',   webzid=$zoneid, ".
	"weblid=$langid,         webfree='$webfree', ".
	"webdesctmpl='$webdesctmpl',".
	"webflags1=$webflags1,   webprodpage=$prodpage ".
	"where webid=$webid and webzid=$zoneid and weblid=$langid");

} elseif($act=="new"){

 if( $databaseeng=='odbc' && $dialect=='solid' ){
	$res=$fcw->query("call web_ins ($zoneid,$langid,".
	"'$webdescr','$realhome', '$carthome', '$webtitle',".
	"'$back',    '$logo',      $logow,      $logoh,     '$webbg',".
	"'$webtext', '$weblink',  '$webvlink', '$webalink', '$headsku',".
	"'$headtext','$headgraph', $headgraphw, $headgraphh,'$footsku',".
	"'$foottext','$footgraph', $footgraphw, $footgraphh, $daysinnew,".
	"'$newlogo',  $newlogow,   $newlogoh,  '$newmast',   $newmastw,".
	" $newmasth, '$speclogo',  $speclogow,  $speclogoh, '$specmast',".
	" $specmastw, $specmasth, '$viewlogo',  $viewlogow,  $viewlogoh,".
	"'$cattext', '$autodom',  '$websort',  '$webfree',".
	"'$webdesctmpl',$webflags1,$prodpage)"); 
 }else{
	$res=$fcw->query("insert into web (".
	"webzid,weblid,webdescr,realhome,carthome,webtitle,webback,".
	"weblogo,weblogow,weblogoh,webbg,webtext,weblink,webvlink,".
	"webalink,".
	"webhdsku,webhdtext,webhdgraph,webhdgraphw,webhdgraphh,".
	"webftsku,webfttext,webftgraph,webftgraphw,webftgraphh,".
	"webdaysinnew,".
	"webnewlogo,webnewlogow,webnewlogoh,".
	"webnewmast,webnewmastw,webnewmasth,".
	"webspeclogo,webspeclogow,webspeclogoh,".
	"webspecmast,webspecmastw,webspecmasth,".
	"webviewlogo,webviewlogow,webviewlogoh,".
	"webcattext,webautodom,websort,webfree,".
	"webdesctmpl,webflags1,webprodpage)".
	" values ".
	"($zoneid,$langid,".
	"'$webdescr','$realhome', '$carthome', '$webtitle',".
	"'$back',    '$logo',      $logow,      $logoh,     '$webbg',".
	"'$webtext', '$weblink',  '$webvlink', '$webalink', '$headsku',".
	"'$headtext','$headgraph', $headgraphw, $headgraphh,'$footsku',".
	"'$foottext','$footgraph', $footgraphw, $footgraphh, $daysinnew,".
	"'$newlogo',  $newlogow,   $newlogoh,  '$newmast',   $newmastw,".
	" $newmasth, '$speclogo',  $speclogow,  $speclogoh, '$specmast',".
	" $specmastw, $specmasth, '$viewlogo',  $viewlogow,  $viewlogoh,".
	"'$cattext', '$autodom',  '$websort',  '$webfree',".
	"'$webdesctmpl',$webflags1,$prodpage)"); 
 }

} elseif($act=="delete"){

	$res=$fcw->query("delete from web ".
		"where webid=$webid and webzid=$zoneid and weblid=$langid");

}
if(!$res){
	$fcw->rollback();
	echo "<b>failure updating web: $res</b><br>\n";
}else{
	$fcw->commit();
	echo "Work committed.<br>\n";
}
?>

<p>

<form method=post action="index.php">
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=submit value="Return to Maintenance">
</form>

<?php require('./footer.php');?>
