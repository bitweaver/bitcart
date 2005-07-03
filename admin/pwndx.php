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

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');
?>

<h2 align=center>Catalog User Maintenance</h2>
<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">Return to Central Maintenance Page</a><br>

<form name="pform" method="post" action="pwupd.php"
 onSubmit="
	if(document.pform.pwdescr.value == ''){
		alert('Please enter a user description.');
		return false;
	}
	if(document.pform.pwemail.value == ''){
		alert('Please enter a user email address.');
		return false;
	}
	if(document.pform.pwuid.value == ''){
		alert('Please enter a user ID.');
		return false;
	}
	if(document.pform.pwpw.value == ''){
		alert('Please enter a user password.');
		return false;
	}
 ">

<h3>Add A Catalog User</h3>

<b>User Description (full name, if known: <i>size=80</i></b><br>
<input name=pwdescr size=50 maxlength=80><br>

<b>E-mail Address: <i>size=80</i></b><br>
<input name=pwemail size=50 maxlength=80><br>

<b>User ID: <i>size=16</i></b><br>
<input name=pwuid size=10 maxlength=16><br>

<b>Password: <i>size=8</i></b><br>
<input name=pwpw size=10 maxlength=8><br>

<b>Expiration: <i>MM / DD / YYYY</i></b><br>
<input name=mm size=3 maxlength=2>
<input name=dd size=3 maxlength=2>
<input name=yy size=5 maxlength=4><br>

<b>Send notice to user of password update?</b><br>
<input type=radio name=send_pw value="1">&nbsp;Yes<br>
<input type=radio name=send_pw value="0" checked>&nbsp;No<br>

<b>Activate immediately?</b><br>
<input type=radio name=pwactive value="1" checked>&nbsp;Yes<br>
<input type=radio name=pwactive value="0">&nbsp;No<br>

<b>Permitted zone for this user:</b><br>
<select name="zid" size="1">
<option value="0">[-- All Zones --]
<?php
$fcz = new FC_SQL;
$fcz->query("select zoneid,zonedescr from zone");
while( $fcz->next_record() ){
	$zid=$fcz->f('zoneid');
	$zdesc=stripslashes($fcz->f('zonedescr'));
	echo "<option value=\"$zid\">$zdesc\n";
}
?>
</select><br>

<input type="hidden" name=action value="A">
<input type="hidden" name="zoneid" value="<?php echo $zoneid ?>">
<input type="hidden" name="langid" value="<?php echo $langid ?>">
<input type="submit" value="Add User">
<input type="reset"  value="Clear Form">

</form>

<hr>

<form method="post" action="pwmod.php">

<h3>Modify A Catalog User</h3>

<select name=pwuid onChange="submit();">
<?php
$fcpw = new FC_SQL;
$fcpw->query("select * from pw order by pwuid");
while( $fcpw->next_record() ){
 $pwdescr=stripslashes($fcpw->f('pwdescr'));
 $pwuid  =$fcpw->f('pwuid');
 echo "<option value=\"$pwuid\">$pwdescr\n";
}
$fcpw->free_result();
?>
</select>

<input type="hidden" name=action value="M">
<input type="hidden" name="zoneid" value="<?php echo $zoneid ?>">
<input type="hidden" name="langid" value="<?php echo $langid ?>">
<input type="submit" value="Modify User">
<input type="reset"  value="Clear Form">

</form>

<hr>

<form method="post" action="pwupd.php">

<h3>Delete A Catalog User</h3>

<select name=pwuid>
<?php
$fcpw = new FC_SQL;
$fcpw->query("select * from pw order by pwuid");
while( $fcpw->next_record() ){
 $pwdescr=stripslashes($fcpw->f('pwdescr'));
 $pwuid   = $fcpw->f('pwuid');
 echo "<option value=\"$pwuid\">$pwdescr\n";
}
$fcpw->free_result();
?>
</select>

<input type="hidden" name=action value="D">
<input type="hidden" name="zoneid" value="<?php echo $zoneid ?>">
<input type="hidden" name="langid" value="<?php echo $langid ?>">
<input type="submit" value="Delete User">

</form>

<hr>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">Return to Central Maintenance Page</a><br>

<?php require('./footer.php'); ?>
