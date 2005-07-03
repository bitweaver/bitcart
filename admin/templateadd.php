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

$ssku = getparam('ssku');
$srch = getparam('srch');
$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fct = new FC_SQL;

$tstamp=time();
?>
<body bgcolor="#ffffec">

<center>
<font size="+1"><b>Template Maintenance</b></font><br>
</center>
<p>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td colspan=2 bgcolor="#FFFFFF">

<form name=moddel method="post" action="templatemod.php">
<b>Modify / Delete A Template</b><br>
<select name=tidx>
<?php 
$fct->query("select tidx,tname from templates order by tidx");
while ( $fct->next_record() ) {?>
<option value="<?php echo $fct->f("tidx")?>"><?php echo $fct->f("tname")?>
<?php }
$fct->free_result();
?>
</select><br>
<input type="radio" name=act value="MT" checked
 onClick="document.moddel.action='templatemod.php';">Modify The Template<br>
<input type="radio" name=act value="DT"
 onClick="if(showConfirm()){
            this.form.act[1].checked=1;
            document.moddel.action='templateupd.php';
            return true;
           }else{
            this.form.act[0].checked=1;
            document.moddel.action='templatemod.php';
            return false;
           }"
>Delete The Template
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Submit Action">
<input type="reset"  value="Clear Form">
</form>

</td></tr>
<tr><td colspan=2 bgcolor="#FFFFFF">

<form METHOD="POST" action="templateupd.php">

<b>Add A New Template:</b><br>

<b>Template Name:</b> <i>maxsize=128</i><br>
<input name=tname size=20><br>

<b>Template Description:</b> <i>maxsize=255</i><br>
<input name=tdesc size=65><br>

<b>Template Text:</b> <i>maxsize=65,000 bytes</i><br>
<i>text substitution formula:</i> &lt;?eval ( '?&gt;' . $txt . '&lt;?' );?&gt;<br>
<textarea name=ttxt rows=6 cols=65 wrap=virtual></textarea><br>

<?php 
unset($fnarray);
$fnarray=array();
$dp=@opendir("BITCART_PKG_PATHfiles");
if($dp){?>
<b>OR</b>
<select name=tfile>
<option value="">select a Web page
<?php 
 $fp=readdir($dp);
 $i=0;
 while($fp){
	$fp=readdir($dp);
	if (filetype("BITCART_PKG_PATHfiles/$fp")=="file" && eregi(".html$",$fp) ){
		$fnarray["i$i"]=$fp;
	}
	$i++;
 }
 if(count($fnarray)){
  ksort($fnarray);
  for(reset($fnarray); $key = key($fnarray); next($fnarray)){
   echo "<option>$fnarray[$key]\n";
  }
 }
 closedir($dp);?>
</select>
<?php }?>

</td></tr><tr><td colspan=2 bgcolor="#FFFFFF">

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="hidden" name=act value="AT">
<input type="submit" value="Add Template">
<input type="reset"  value="Clear Form">
</form>

</td></tr>
<tr><td colspan=2 bgcolor="#FFFFFF">

<form name=coplt method="post" action="templateupd.php">
<b>Copy A Local Template To A Local Template</b><br>
<select name=tidx>
<option value="">[select a local template]
<?php 
$fct->query("select tidx,tname from templates order by tidx");
while ( $fct->next_record() ) {?>
<option value="<?php echo $fct->f("tidx")?>"><?php echo $fct->f("tname")?>
<?php }
$fct->free_result();
?>
</select>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=hidden name=act value="CLT">
<input type=submit value="Submit Action">
</form>

</td></tr>
<?php //mcnt}?>

<tr><td align=center bgcolor="#FFFFFF">
<a href="index.php">Central Maintenance Page</a>
</td></tr>
</table>
</center>


<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
