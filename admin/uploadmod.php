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
$filemod = (int)getparam('filemod');
$act = getparam('act');
$fname = getparam('fname');
$ftext = getparam('ftext');
$direc = getparam('direc');
// ==========  end of variable loading  ==========

if($act=="DF"){
 if($fname){
  unlink("BITCART_PKG_PATH$direc/$fname");
 }
 header("Location: uploadmaint.php");
 exit;
}
if($filemod){
 $od=fopen("BITCART_PKG_PATH$direc/$fname","w");
 fwrite($od,$ftext);
 fclose($od);
 header("Location: uploadmaint.php");
 exit;
}else{
 require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );
 require('./header.php')
?>
<body bgcolor="#ffffec">

<center>
<font size="+1"><b>File Maintenance</b></font><br>
</center>
<p>

<?php 
$tstamp=time();
?>

<center>
<table border="0" cellpadding="2" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td bgcolor="#FFFFFF">

<center>
<a href="uploadmaint.php">Upload Maintenance Page</a><br>
</center>
<?php 
$id=fopen("BITCART_PKG_PATH$direc/$fname","r");
$ftext=stripslashes(fread($id,65535));
fclose($id);
$ftext=ereg_replace("\t"," ",$ftext);
?>
<form method=post action="uploadmod.php">
<center><input type=submit value="Update File"></center><br>
<font size="-1">
<textarea name=ftext rows=20 cols=80 wrap=virtual><?php echo $ftext?></textarea>
</font>
<input type=hidden name=filemod value=1>
<input type=hidden name=direc value="<?php echo $direc?>">
<input type=hidden name=fname value="<?php echo $fname?>">
</form>

</td></tr><tr><td align=center colspan=3 bgcolor="#FFFFFF">

<a href="uploadmaint.php">Upload Maintenance Page</a>

</td></tr>
</table>
</center>

<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php }?>
