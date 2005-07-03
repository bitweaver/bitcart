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

$fm = new FC_SQL;

$cartid_inc=1;

// $CookieCustID and $CookieCart must already be defined

if(!empty($CookieCustID)){
 list($purchid,$purch_email)=explode(':',base64_decode($CookieCustID));
}else{
 $purchid=0;
}
if(!empty($CookieCart)){
 @list($ccartid,$czid,$clid,$ciso)=explode(':',$CookieCart);
 if(empty($cartid)){
  $cartid=$ccartid;
 }
 if(empty($zid)){
  $zid=(int)$czid;
 }elseif( $zid != $czid ){
  // if changing zones, get default language from the new zone
  $lid=0;
  $lang_iso='';
 }
}

// SQL INJECTION AVOIDENCE

$zid=(int)$zid;
$lid=(int)$lid;
$purchid=(int)$purchid;
$orderproc_flag=!empty( $orderproc_flag ) ? (int)$orderproc_flag : 0;


// remove anything but a-z, A-Z, 0-9 and _
$cartid=eregi_replace('[^a-z0-9_]','',$cartid);

//uncomment below if ciso ever used, keep only first three a-z
//$ciso=substr(ereg_replace('[^a-z]','',$ciso),0,3);

if(empty($zid)){
 // get the default zone if not given in cookie
 $fm->query('select zoneid from master');
 $fm->next_record();
 $zid=(int)$fm->f('zoneid');
 $fm->free_result();
}

// make sure a zone exists for the value in the cookie
// take the first we get if no such zone
$fm->query("select zonedeflid,zflag1 from zone where zoneid=$zid");
if( $fm->next_record() ){
 $deflid=(int)$fm->f('zonedeflid');
 $zflag1=(int)$fm->f('zflag1');
 $fm->free_result();
}else{
 $fm->query('select zoneid,zonedeflid,zflag1 from zone');
 $fm->next_record();
 $zid=(int)$fm->f('zoneid');
 $lid=(int)$fm->f('zonedeflid');
 $zflag1=(int)$fm->f('zflag1');
 $fm->free_result();
}

if(empty($lid)){
 // get default language from zone record
 // don't use the lid from the cookie, might not match zone
 $lid=$deflid;
 $lang_iso='';
}

// make sure a language exists for this zone
$fm->query(
 "select langid,langiso from lang where langid=$lid and langzid=$zid");
if( !$fm->next_record() ){
 // no such lang, take the first we get
 $fm->query("select langid,langiso from lang where langzid=$zid");
 $fm->next_record();
 $lid=(int)$fm->f('langid');
 $lang_iso=$fm->f('langiso');
 $fm->free_result();
}else{
 // get ISO 639/2 code for this language
 $lang_iso=$fm->f('langiso');
 $fm->free_result();
}

if(!empty($cartid)){ // see if this order is still valid 
 if( $orderproc_flag ){
   // in orderproc we cannot check order complete status or
   // we will create a new cartid and submit a zero order
  $fm->query(
   'select aid,count(*) as ocnt from ohead '.
   "where orderid='$cartid' group by aid");
 }else{
  $fm->query(
   'select aid,count(*) as ocnt from ohead '.
   "where orderid='$cartid' and complete < 1 group by aid");
 }
 $fm->next_record();
 $ocnt=(int)$fm->f('ocnt');
 if( $ocnt != 1 ){ // order no longer exists or is duplicated
  unset($cartid);
  unset($contrib_only);
  unset($payment_only);
  setcookie("Cookie${instid}Cart",'',time()-1000,'/');
 }else{ // the order still exists
  $tmp_aid=str_replace(' ','',$fm->f('aid'));
 }
 $fm->free_result();
}
if( empty($aid) ){
 $aid='';
}
if(empty($cartid)){
 $tstamp=time();
 $currday=(int)date('d',$tstamp);

 if( $zflag1 & $flag_zoneseqcartid ){

  // sequentially assign the 7 digit cart id sequence number
  $forupd='';
  if ( $databaseeng == 'mysql' ){
   $fm->query('lock tables seq write');
  }elseif ( $databaseeng == 'pgsql' ){
   $forupd='';
   // http://www.postgresql.org/idocs/index.php?sql-lock.html
   // rollback/commit breaks the lock
   $fm->query('begin work');
   $fm->query('lock table seq in row exclusive mode');
  }elseif ( $databaseeng == 'mssql' ){
   $forupd=' with (XLOCK)';
   $fm->query('begin transaction');
  }elseif ( $databaseeng == 'oracle' ){
   $fm->query('lock table seq in row exclusive mode');
  }elseif ( $databaseeng == 'odbc' && $dialect == 'solid' ){
   // in pessimistic mode, set at table creation, should be exclusive lock
   // rollback/commit breaks the lock
   $forupd=' for update';
   $fm->query('set transaction read write');
  }

  $fm->query("select cartseq,lastday from seq $forupd");
  $fm->next_record();
  $seq=(int)$fm->f('cartseq') + 1;
  $lastday=(int)$fm->f('lastday');
  $fm->free_result();
  if( $currday != $lastday ){
   $seq = 1;   // reset the sequence upon day turnover
  }
  $fm->query("update seq set cartseq=$seq,lastday=$currday");

  if ( $databaseeng == 'mysql' ){
   $fm->query('unlock tables');
  }elseif ( $databaseeng == 'pgsql' ){
   $fm->query('commit work');
  }elseif ( $databaseeng=='mssql' || $databaseeng=='oracle' ){
   $fm->commit();
  }elseif ( $databaseeng == 'odbc' && $dialect == 'solid' ){
   $fm->commit();
  }

 }else{

  // randomly assign the 7 digit cart id sequence number
  $i=0;  
  $collision = 1; 
  srand((double)microtime()*1000000);
  while ( $collision ){
   // limit the sequence number to 7 digits, 0-9999999
   $seq=rand() % 10000000;
   // uncomment to use alphanumeric sequence; the cartid
   // sprintf format string below must be changed also
   //$seq=substr(0,7,uniqid());
   $fm->query(
    "select count(*) as cnt from ohead where orderid='$cartid'");
   $fm->next_record();
   // collision is the count of rows found, leave loop when 0 
   $collision = (int)$fm->f('cnt');
   $fm->free_result();
   $i++;         
   if( ($i % 10) == 0 ){
	global $gBitSystem;
    // notify every 10th pass through if we get stuck
    mail( $gBitSystem->getErrorEmail(),
    ' UNIQUE CART ID COLLISION LOOP',
    "Sequence: $seq\nLoop count: $i\n");
    sleep(1);       // try a 1 second sleep
   }
  }

 }

 $cartid = sprintf('%08d%07d',date('Ymd',$tstamp),$seq);

 $fm->query('insert into ohead '.
  '(orderid,zone,subz,tstamp,contrib,trans1,aid,purchid,complete) '.
  ' values '.
  "('$cartid',$zid,0,$tstamp,0,'$trans1','$aid',$purchid,-1)");
 $fm->commit();
}elseif( !$tmp_aid ){
 // order AID was null, update with the current one
 $fm->query("update ohead set aid='$aid' where orderid='$cartid'");
}
// keep the cookie 48 hours
setcookie("Cookie${instid}Cart",$cartid.':'.$zid.':'.$lid,time()+172800,'/');
?>
