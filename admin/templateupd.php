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
$act = getparam('act');
$tidx = (int)getparam('tidx');
$tfile = getparam('tfile');
$ttxt = getparam('ttxt');
$tname = getparam('tname');
$tdesc = getparam('tdesc');
// ==========  end of variable loading  ==========

require('./admin.php');

$fct = new FC_SQL;
$fcn = new FC_SQL;

require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );
$tstamp=time();

if ($act=="AT" && !$tfile && !$ttxt) {?>
You must enter text in the &quot;Template Text&quot; field or choose
an HTML file to include as a template.  Please click the Back button
on your browser and enter one of these two.<p>
<a href="templateadd.php">Back to Template Maintenance</a>
<?php }

if ($act=="AT") {

 if($tfile){
  $fp=fopen("BITCART_PKG_PATHfiles/$tfile","r");
  $ttxt=fread($fp,filesize("BITCART_PKG_PATHfiles/$tfile"));
  // not sure about the wisdom of the following line...
  $ttxt=ereg_replace("\t"," ",$ttxt);
  fclose($fp);
 }
 if( $databaseeng=='odbc' && $dialect=='solid' ){
  $result=$fct->query(
  "call template_ins ($zoneid,$langid,$tstamp,'$tname','$tdesc','$ttxt')");
 }else{
  $result=$fct->query("insert into templates ".
  "(tzid,tlid,lastmod,tname,tdesc,ttxt)".
  " values ".
  "($zoneid,$langid,$tstamp,'$tname','$tdesc','$ttxt')");
 }
 $ifile="templateadd.php";

} elseif ($act=="MT") {

 $result=$fct->query(
  "update templates set tname='$tname',tdesc='$tdesc',ttxt='$ttxt',".
  "lastmod=$tstamp where tidx=$tidx");
 $ifile="templateadd.php";

} elseif ($act=="DT") {

 $result=$fct->query("delete from templates where tidx=$tidx");
 $ifile="templateadd.php";

} elseif ($act=="CLT" and $tidx) {

 $result=$fct->query(
  "select tname,tdesc,ttxt from templates where tidx=$tidx");
 $fct->next_record();
 $tname = "Copy of " . $fct->f("tname");

 if( $databaseeng=='odbc' && $dialect=='solid' ){
  $result=$fcn->query("call template_ins ($zoneid,$langid,$tstamp,".
  "'$tname','".$fct->f("tdesc").','.$fct->f("ttxt")."')");
 }else{
  $result=$fct->query("insert into templates ".
  "(tzid,tlid,lastmod,tname,tdesc,ttxt)".
  " values ".
  "($zoneid,$langid,$tstamp,'$tname','$tdesc','$ttxt')");
 }

 $ifile="templateadd.php";

} else {?>

<br>
Unspecified action; please contact Technical Support at
<a href="mailto:support@fni.com">support@fni.com</a>
<br>

<?php 
}

Header("Location: $ifile?zoneid=$zoneid&langid=$langid");
?>
