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

// flags.php and functions.php must be included before this

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// addslashes() for non-numbers, no exceptions
// only exception: custid cookie is mime encoded, don't escape
$PHP_AUTH_USER = getserver('PHP_AUTH_USER');
$PHP_AUTH_PW = getserver('PHP_AUTH_PW');
// $pwzone = (int)getparam('pwzone');
// ==========  end of variable loading  ==========

if( !isset($fc_cfowler_pw) && !isset($PHP_AUTH_USER) ){
 if( $zflag1 & $flag_zonepwcatalog ) {
  Header('WWW-authenticate: basic realm=" Registered User"');
  header('HTTP/1.0 401 Unauthorized');
  echo 'You must be a registered user to enter this area';
//bvo hooking pwretrieve up with pw.php start
/*
  outcomment this to use pwretrieve.php
  currently this doesn't work very well with ESD
  echo '<br />If you lost your password you might be able to ';
  echo 'retrieve it by entering your email address below and hit ';
  echo 'the button.<br />';
  echo '<form method="post" action="pwretrieve.php"><br />';
  echo '<input name=pwlostmail size=50 maxlength=80 value=""><br />';
  echo '<input type="submit" value="send me my password">';
  echo '</form>';
*/
//bvo hooking pwretrieve up with pw.php stop
  exit;
 }
}

// keep the cookie for over 3 years
if(isset($PHP_AUTH_USER)) {
	$pwuid=$PHP_AUTH_USER;
	$pwpw=$PHP_AUTH_PW;
} else {
	ereg("(.*)_(.*)",base64_decode($fc_cfowler_pw),$cr);
	$pwuid=$cr[1];
	$pwpw=$cr[2];
}
//setcookie("fc_${instid}_pw",base64_encode($pwuid."_".$pwpw),time()+99000000);

$fcpw = new FC_SQL;
$fcpw->query("select * from pw ".
			 "where pwuid='$pwuid' and pwpw='$pwpw' and pwactive=1 and ".
			 "( pwzone=0 or pwzone=$zid )");
if( !$fcpw->next_record() ){
 if( $zflag1 & $flag_zonepwcatalog ) {
  Header('WWW-authenticate: basic realm=" Registered User"');
  Header('HTTP/1.0 401 Unauthorized');
  echo 'You must be a registered user to enter this area';
  //bvo hooking pwretrieve up with pw.php start
/*
  outcomment this to use pwretrieve.php
  currently this doesn't work very well with ESD
  echo '<br />If you lost your password you might be able to ';
  echo 'retrieve it by entering your email address below and hit ';
  echo 'the button.<br />';
  echo '<form method="post" action="pwretrieve.php"><br />';
  echo '<input name=pwlostmail size=50 maxlength=80 value=""><br />';
  echo '<input type="submit" value="send me my password">';
  echo '</form>';
*/
 //bvo hooking pwretrieve up with pw.php stop
  exit;
 }
}else{
 $pwzone=$fcpw->f('pwzone');
 $pwexp=$fcpw->f('pwexp');
 $mnth='pw'.strtolower(date("M"));
 $accesses=$fcpw->f($mnth) + 1;
 $fcpw->free_result();
 if( $pwexp && $pwexp < time() ){
  header('WWW-authenticate: basic realm="Company Registered User"');
  header('HTTP/1.0 401 Unauthorized');
  echo 'Your password has expired; contact site support for assistance.\n';
  exit;
 }else{
  $fcpw->query(
  "update pw set $mnth=$accesses where pwuid='$pwuid'");
 }
}?>
