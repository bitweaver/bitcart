<?php /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2003  FishNet, Inc.

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
require_once( BITCART_PKG_PATH.'functions.php');
// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
$zoneid   = (int)getparam('zoneid');
$langid   = (int)getparam('langid');

$action   = getparam('action');

$pwuid    = getparam('pwuid');
$oldpwuid = getparam('oldpwuid');
$pwdescr  = getparam('pwdescr');
$pwemail  = getparam('pwemail');
$pwpw     = getparam('pwpw');
$zid      = (int)getparam('zid');
$mm       = (int)getparam('mm');
$dd       = (int)getparam('dd');
$yy       = (int)getparam('yy');
$send_pw  = (int)getparam('send_pw');
$pwactive = (int)getparam('pwactive');
$pwzone = (int)getparam('pwzone');

$pwjan = (int)getparam('pwjan');
$pwfeb = (int)getparam('pwfeb');
$pwmar = (int)getparam('pwmar');
$pwapr = (int)getparam('pwapr');
$pwmay = (int)getparam('pwmay');
$pwjun = (int)getparam('pwjun');
$pwjul = (int)getparam('pwjul');
$pwaug = (int)getparam('pwaug');
$pwsep = (int)getparam('pwsep');
$pwoct = (int)getparam('pwoct');
$pwnov = (int)getparam('pwnov');
$pwdec = (int)getparam('pwdec');
// ==========  end of variable loading  ==========


require('./admin.php');

$fcpw = new FC_SQL;

if ( $action=='A' || $action=='M' ){
 if( $pwdescr=='' || $pwuid=='' || $pwpw=='' || $pwemail=='' ){
?>
<p>A field has been left blank; please return to the password
maintenance page and enter valid values in the Description, E-Mail, User ID
and Password fields.
<p>
<a href="pwndx.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">Return to Password Maintenance Page</a><br>
<p>
<?php
  exit;
 }
}

if ($action=="A" || $action=="M") {
 if($yy && $mm && $dd ){
  $pwexpdate=mktime(0,0,0,$mm,$dd,$yy);
 }else{
  $pwexpdate=0;
 }
}

if ($action=="A") {

	$fcpw->query("insert into pw ".
	"(pwdescr,pwemail,pwuid,pwpw,".
	"pwjan,pwfeb,pwmar,pwapr,pwmay,pwjun,".
	"pwjul,pwaug,pwsep,pwoct,pwnov,pwdec,".
	"pwexp,pwactive,pwzone) ".
	"values (".
	"'$pwdescr','$pwemail','$pwuid','$pwpw',".
	"0,0,0,0,0,0,0,0,0,0,0,0,".
	"$pwexpdate,$pwactive,$pwzone)");

} elseif ($action=="M") {

	$fcpw->query("update pw set ".
		"pwdescr='$pwdescr',pwemail='$pwemail',pwuid='$pwuid',pwpw='$pwpw',".
		"pwjan=$pwjan,pwfeb=$pwfeb,pwmar=$pwmar,".
		"pwapr=$pwapr,pwmay=$pwmay,pwjun=$pwjun,".
		"pwjul=$pwjul,pwaug=$pwaug,pwsep=$pwsep,".
		"pwoct=$pwoct,pwnov=$pwnov,pwdec=$pwdec,".
		"pwexp=$pwexpdate,pwactive=$pwactive,pwzone=$pwzone ".
		"where pwuid='$oldpwuid'");

} elseif ($action=="D") {

	$send_pw=0;
	$fcpw->query("delete from pw where pwuid='$pwuid'");

}
global $gBitSystem;
// send password notice only if the user is active
if( $send_pw && $pwactive ){
	mail("$pwemail",
		 " Catalog User Password Update",
		 "\n".
		 "Your new  catalog username and password are below.\n\n".
		 "   username: $pwuid\n".
		 "   password: $pwpw\n",
		 "From: ".$gBitSystem->getPreference('sender_email'));
}

header("Location: $nsecurl/$maintdir/pwndx.php?zoneid=$zoneid&langid=$langid");
?>
