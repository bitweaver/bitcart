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
// ==========  end of variable loading  ==========

require('./header.php');?>

<body bgcolor="#ffffec">

<center>
<font size="+1"><b>File Maintenance</b></font><br>
</center>
<p>

<?php 
$tstamp=time();
?>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">

<tr><td colspan="3" align="center" bgcolor="#ffffff">
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>
</td></tr>
<tr><td valign=top colspan=3 bgcolor=#ffffff>

<form enctype="multipart/form-data" action="upload.php" method=post>
Browse to a file on your local machine,<br>
click &quot;Upload File&quot;.  You will be automatically returned<br>
to this page.  <i>max size 512,000 bytes</i><br>
<input name="userfile" type="file" value="<?php echo $fname ?>">
<input type="hidden" name="max_file_size" value="512000">
<input type=hidden name=fileid value="uploadmaint.php">
<input type="submit" value="Upload File"><br>
<input type=checkbox name=bodyonly value=1>
Leave only body content between &lt;body&gt;...&lt;/body&gt;<br>
<i>(not including the tags; useful for including the body portion<br>
of a Web page into another page)</i><br>
</form>
<p>

</td></tr><tr><td valign=top bgcolor=#ffffff>

Image Files:<br><i>./images directory</i><p>
<?php 
if(file_exists("BITCART_PKG_PATHimages")){
 $dp=opendir("BITCART_PKG_PATHimages");
 $fp=readdir();
 $i=0;
 while($fp){
	$fp=readdir();
	if (filetype("BITCART_PKG_PATHimages/$fp")=="file" &&
		(eregi(".gif$",$fp) || eregi(".jpg$",$fp)) ){
		$fnarray["i$i"]=$fp;
	}
	$i++;
 }
 if(count($fnarray)){
    asort($fnarray);?>
<form name=imgfedit method=post action="uploadmod.php">
<input type=submit value="Delete File"><br>
<input type=hidden name=act value="DF">
<input type=hidden name=direc value="images">
<select name=fname size=<?php echo min(20,count($fnarray))?>>
<?php 
  for(reset($fnarray); $key = key($fnarray); next($fnarray)){
	echo "<option>$fnarray[$key]\n";
  }?>
</select>
</form>
<?php 
 }
 closedir();
}
?><br>

</td><td valign=top bgcolor=#ffffff>

RealMedia Files:<br><i>./ra directory</i><p>
<?php 
if(file_exists("BITCART_PKG_PATHra")){
 unset($fnarray);
 $fnarray=array();
 $dp=opendir("BITCART_PKG_PATHra");
 $fp=readdir();
 $i=0;
 while($fp){
	$fp=readdir();
	if (filetype("BITCART_PKG_PATHra/$fp")=="file" &&
		(eregi(".ra$",$fp) || eregi(".rm$",$fp)) ){
		$fnarray["i$i"]=$fp;
	}
	$i++;
 }
 if(count($fnarray)){
    asort($fnarray);?>
<form name=rafedit method=post action="uploadmod.php">
<input type=submit value="Delete File"><br>
<input type=hidden name=act value="DF">
<input type=hidden name=direc value="ra">
<select name=fname size=<?php echo min(20,count($fnarray))?>>
<?php 
  for(reset($fnarray); $key = key($fnarray); next($fnarray)){
	echo "<option>$fnarray[$key]\n";
  }?>
</select>
</form>
<?php 
 }
 closedir();
}
?><br>

</td><td valign=top bgcolor=#ffffff>

Web/Text Files:<br><i>./files directory</i>
<?php 
if(file_exists("BITCART_PKG_PATHfiles")){
 unset($fnarray);
 $fnarray=array();
 $dp=opendir("BITCART_PKG_PATHfiles");
 $fp=readdir();
 $i=0;
 while($fp){
	$fp=readdir();
	if (filetype("BITCART_PKG_PATHfiles/$fp")=="file") {
		$fnarray["i$i"]=$fp;
	}
	$i++;
 }
 if(count($fnarray)){
  asort($fnarray);?>
<form name=fedit method=post action="uploadmod.php">
<input type=submit value="Perform Action"><br>
<input type=hidden name=direc value="files">
<input type=hidden name=filemod value=0>
<input type="radio" name=act value="MF" checked
 onClick="document.fedit.action='uploadmod.php';">Edit The File<br>
<input type="radio" name=act value="DF"
 onClick="if(showConfirm()){
            this.form.act[1].checked=1;
            document.fedit.action='uploadmod.php';
            return true;
           }else{
            this.form.act[0].checked=1;
            document.fedit.action='uploadmod.php';
            return false;
           }"
>Delete The File<br>
<select name=fname size=<?php echo min(20,count($fnarray))?>
 onChange="document.fedit.action='uploadmod.php';submit();">
<?php 
  for(reset($fnarray); $key = key($fnarray); next($fnarray)){
	echo "<option>$fnarray[$key]\n";
  }?>
</select>
</form>
<?php 
 }
 closedir();
}
?>

</td></tr>
<tr><td align=center colspan=3 bgcolor=#ffffff>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br>
</td></tr>
</table>
</center>

<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
