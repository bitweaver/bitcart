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
$fileid = getparam('fileid');
$fpath = getparam('fpath');
$fname = getparam('fname');
$max_file_size = getparam('max_file_size');

$userfile      = $_FILES['userfile']['tmp_name'];
$userfile_name = $_FILES['userfile']['name'];
$userfile_size = $_FILES['userfile']['size'];
$userfile_type = $_FILES['userfile']['type'];

// ==========  end of variable loading  ==========


// process an uploaded file
// $userfile: temporary uploaded file name
// $userfile_name: original name of the file
// $userfile_size: size of the uploaded file in bytes
// $userfile_type: MIME type of the uploaded file

$bad = 0;

// MIME types turn out to be a bit undependable for some types,
// depending on what is installed on the client machine
// if ( $userfile_type == "image/gif" ) {
// } elseif ($userfile_type == "image/jpeg" ) {
// } elseif ($userfile_type == "audio/x-pn-realaudio" ||
// 		  $userfile_type == "application/vnd.rn-realmedia" ) {

if ( eregi(".*\.gif$",$userfile_name) ) {
	$ext = "gif";
} elseif ( eregi(".*\.jpg$",$userfile_name) ) {
	$ext = "jpg";
} elseif ( eregi(".*\.htm$",$userfile_name) ) {
	$ext = "html";
} elseif ( eregi(".*\.html$",$userfile_name) ) {
	$ext = "html";
} elseif ( eregi(".*\.txt$",$userfile_name) ) {
	$ext = "txt";
} elseif ( eregi(".*\.ra$",$userfile_name) ) {
	$ext = "ra";
} elseif ( eregi(".*\.rm$",$userfile_name) ) {
	$ext = "rm";
//} else {
//	$bad=1;
}

if ( $userfile_size > $max_file_size ) {
	$bad = 1;
}

if($bad){?>
The MIME type ("<?php echo $userfile_type?>") is invalid or the file is too large.
The file must be either a .gif, .jpg, .ra or .rm file and no larger than
<?php echo $max_file_size?> bytes; this file is <?php echo $userfile_size?> bytes.
<p>
<a href="uploadmaint.php">Upload Maintenance Page</a><br>
<?php 
  unlink($userfile);
  Header("Location: $fileid?fname=$fname");
  exit;
}
$bodyonly=(int)$bodyonly;
if( $ext=="ra" || $ext=="rm" || $ext=="RA" || $ext=="RM" ){
 $fpath="BITCART_PKG_PATHra";
}elseif( $ext=="gif" || $ext=="jpg" || $ext=="GIF" || $ext=="JPG" ){
 $fpath="BITCART_PKG_PATHimages";
}else{
 $fpath="BITCART_PKG_PATHfiles";
}

// For PHP safe mode operation, you will need to comment the
// move_uploaded_file() line and uncomment the exec() line.
// See the documentation for more details.
// You may need to edit line 1 of mvupload to set the path to
// the CGI version of php (required for this to work).
// exec("mvupload $bodyonly $userfile $fpath/$userfile_name");

move_uploaded_file($userfile, $fpath."/".$userfile_name);

Header("Location: $fileid?fname=$fname");
?>
