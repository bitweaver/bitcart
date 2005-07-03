<?php /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-1999  FishNet, Inc.

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


Don't use auth cookies for download; they goof things up.

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

if( !isset($PHP_AUTH_USER) ){
  header("WWW-authenticate: basic realm=\" $esdid\"");
  header('HTTP/1.0 401 Unauthorized');
  echo 'You must be a registered user to enter this area\n';
  exit;
}

if(isset($PHP_AUTH_USER)) {
	$pwuid=$PHP_AUTH_USER;
	$pwpw=$PHP_AUTH_PW;
}

$fcpw = new FC_SQL;
$fcpw->query("select * from pw ".
			 "where pwuid='$pwuid' and pwpw='$pwpw' and pwactive=1");
if( !$fcpw->next_record() ){
  header("WWW-authenticate: basic realm=\" $esdid\"");
  Header('HTTP/1.0 401 Unauthorized');
  echo 'You must be a registered user to enter this area\n';
  exit;
}else{
 $pwzone=(int)$fcpw->f('pwzone');
 $pwexp=(int)$fcpw->f('pwexp');
 if( $pwexp && $pwexp < time() ){
  $pwexp=$fcpw->f('pwexp');
  header("WWW-authenticate: basic realm=\" $esdid\"");
  header('HTTP/1.0 401 Unauthorized');
  echo 'Your password has expired; contact site support for assistance.\n';
  exit;
 }elseif( !$pwzone || $pwzone == $zoneid ){
  $mnth='pw'.strtolower(date("M"));
  $val=$fcpw->f($mnth) + 1;
  $fcpw->query(
  "update pw set $mnth=$val where pwuid='$pwuid'");
 }
}?>
