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
$action = getparam('action');
$pwuid  = getparam('pwuid');
// ==========  end of variable loading  ==========

require('./admin.php');

$fcpw = new FC_SQL;
$fcpw->query("select * from pw where pwuid = '$pwuid'");
if( !$fcpw->next_record() ){
	header("Location: pwndx.php?zoneid=$zoneid&langid=$langid");
	exit;
}

$pwdescr  = $fcpw->f('pwdescr');
$pwemail  = $fcpw->f('pwemail');
$pwuid    = $fcpw->f('pwuid');
$pwpw     = $fcpw->f('pwpw');
$pwactive = $fcpw->f('pwactive');
$pwexp    = (int)$fcpw->f('pwexp');
$pwzone   = (int)$fcpw->f('pwzone');

require('./header.php');
?>

<h2 align=center>Catalog User Maintenance</h2>

Update the following items for the user; click Update User when all
data is correct.

<p>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br>


<form method="post" action="pwupd.php">

<b>User Description (full name, if known: <i>size=80</i></b><br>
<input name=pwdescr size=50 maxlength=80
 value="<?php echo $pwdescr ?>"><br>

<b>E-Mail Address: <i>size=80</i></b><br>
<input name=pwemail size=50 maxlength=80
 value="<?php echo $pwemail ?>"><br>

<b>Login Name: <i>size=16</i></b><br>
<input name=pwuid size=10 maxlength=16
 value="<?php echo $pwuid ?>"><br>

<b>Password: <i>size=8</i></b><br>
<input name=pwpw size=10 maxlength=8
 value="<?php echo $pwpw ?>"><br>

<b>Expiration: <i>MM / DD / YYYY</i></b><br>
<b><i>(does not expire if blank)</i></b><br>
<?php if($pwexp==0 || $pwexp==-1){?>
 <input name=mm size=3 maxlength=2 value="">
 <input name=dd size=3 maxlength=2 value="">
 <input name=yy size=5 maxlength=4 value=""><br>
<?php }else{?>
 <input name=mm size=3 maxlength=2 value="<?php echo date("m",$pwexp) ?>">
 <input name=dd size=3 maxlength=2 value="<?php echo date("d",$pwexp) ?>">
 <input name=yy size=5 maxlength=4 value="<?php echo date("y",$pwexp) ?>"><br>
<?php }?>

<table cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td valign=top align=left bgcolor="#FFFFFF">

<b>January: <i>size=8</i></b><br>
<input name=pwjan size=8 maxlength=8
 value="<?php echo $fcpw->f('pwjan') ?>"><br>

</td><td valign=top align=left bgcolor="#FFFFFF">

<b>February: <i>size=8</i></b><br>
<input name=pwfeb size=8 maxlength=8
 value="<?php echo $fcpw->f('pwfeb') ?>"><br>

</td></tr>
<tr><td valign=top align=left bgcolor="#FFFFFF">

<b>March: <i>size=8</i></b><br>
<input name=pwmar size=8 maxlength=8
 value="<?php echo $fcpw->f('pwmar') ?>"><br>

</td><td valign=top align=left bgcolor="#FFFFFF">

<b>April: <i>size=8</i></b><br>
<input name=pwapr size=8 maxlength=8
 value="<?php echo $fcpw->f('pwapr') ?>"><br>

</td></tr>
<tr><td valign=top align=left bgcolor="#FFFFFF">

<b>May: <i>size=8</i></b><br>
<input name=pwmay size=8 maxlength=8
 value="<?php echo $fcpw->f('pwmay') ?>"><br>

</td><td valign=top align=left bgcolor="#FFFFFF">

<b>June: <i>size=8</i></b><br>
<input name=pwjun size=8 maxlength=8
 value="<?php echo $fcpw->f('pwjun') ?>"><br>

</td></tr>
<tr><td valign=top align=left bgcolor="#FFFFFF">

<b>July: <i>size=8</i></b><br>
<input name=pwjul size=8 maxlength=8
 value="<?php echo $fcpw->f('pwjul') ?>"><br>

</td><td valign=top align=left bgcolor="#FFFFFF">

<b>August: <i>size=8</i></b><br>
<input name=pwaug size=8 maxlength=8
 value="<?php echo $fcpw->f('pwaug') ?>"><br>

</td></tr>
<tr><td valign=top align=left bgcolor="#FFFFFF">

<b>September: <i>size=8</i></b><br>
<input name=pwsep size=8 maxlength=8
 value="<?php echo $fcpw->f('pwsep') ?>"><br>

</td><td valign=top align=left bgcolor="#FFFFFF">

<b>October: <i>size=8</i></b><br>
<input name=pwoct size=8 maxlength=8
 value="<?php echo $fcpw->f('pwoct') ?>"><br>

</td></tr>
<tr><td valign=top align=left bgcolor="#FFFFFF">

<b>November: <i>size=8</i></b><br>
<input name=pwnov size=8 maxlength=8
 value="<?php echo $fcpw->f('pwnov') ?>"><br>

</td><td valign=top align=left bgcolor="#FFFFFF">

<b>December: <i>size=8</i></b><br>
<input name=pwdec size=8 maxlength=8
 value="<?php echo $fcpw->f('pwdec') ?>"><br>

</td></tr>
</table>

<b>Send notice to user of password update?</b><br>
<input type=radio name=send_pw value="1">&nbsp;Yes<br>
<input type=radio name=send_pw value="0" checked>&nbsp;No<br>

<b>Active?</b><br>
<input type=radio name=pwactive value="1"<?php if( $pwactive){?> checked<?php }?>>&nbsp;Yes<br>
<input type=radio name=pwactive value="0"<?php if(!$pwactive){?> checked<?php }?>>&nbsp;No<br>

<b>Permitted zone for this user:</b><br>
<select name="pwzone" size="1">
<option value="0">[-- All Zones --]
<?php
$fcz = new FC_SQL;
$fcz->query("select zoneid,zonedescr from zone");
while( $fcz->next_record() ){
	$zid=$fcz->f('zoneid');
	$zdesc=stripslashes($fcz->f('zonedescr'));
	if( $zid == $pwzone ){
		echo "<option value=\"$zid\" selected>$zdesc\n";
	}else{
		echo "<option value=\"$zid\">$zdesc\n";
	}
}
?>
</select><br>

<input type="hidden" name="action" value="M">
<input type="hidden" name="oldpwuid" value="<?php echo $pwuid?>">
<input type="hidden" name="zoneid" value="<?php echo $zoneid ?>">
<input type="hidden" name="langid" value="<?php echo $langid ?>">
<input type="submit" value="Update User">
<input type="reset"  value="Previous Values">

</form>
<p>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br>

<?php $fcpw->free_result(); ?>

<?php require('./footer.php') ?>
