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
$show   = (int)getparam('show');
$srch   = (int)getparam('srch');

$numlvl      =  getparam('numlvl');
$zonewhsid   =  getparam('zonewhsid');
$shipid      =  (int)getparam('shipid');

$act         = getparam('act');

$shipdescr   = getparam('shipdescr');
$shipsvccode = getparam('shipsvccode');
$shipmeth    = (int)getparam('shipmeth');
$shippercent = (double)getparam('shippercent');
$shipitem    = (double)getparam('shipitem');
$shipitem2   = (double)getparam('shipitem2');
$shipcalc    = getparam('shipcalc');
$shipaux1    = getparam('shipaux1');
$shipaux2    = getparam('shipaux2');

$shiphi    = getparam('shiphi'.$i);
$shippr    = getparam('shippr'.$i);
$weighthi  = getparam('weighthi'.$i);
$weightpr  = getparam('weightpr'.$i);

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcs = new FC_SQL;

$droot="BITCART_PKG_PATH";

if($act!="delete"){

if(($shipmeth=="")&&($shipcalc=="")){?>
  Please select a shipping method or calculation script.
	<p>Click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.
    <?php exit;
}
if($shipmeth==2 && empty($shippercent)){?>
  Please enter a shipping percentage.
	<p>Click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.
    <?php exit;
}
if(($shipmeth==3 || $shipmeth == 4) && strlen($shipitem)==0){?>
  Please enter the line item shipping costs.
	<p>Click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.
    <?php exit;
}

} // end of $act != delete

$shippercent=(double)$shippercent;
$shipitem=(double)$shipitem;
$shipitem2=(double)$shipitem2;
$active=(int)$active;

if($act=="update"){

 $fcs->query("update ship set ".
	"shipdescr='$shipdescr',   shipmeth=$shipmeth,".
	"shippercent=$shippercent,".
	"shipitem='$shipitem',     shipitem2=$shipitem2,".
	"shipcalc='$shipcalc',     shipadd='$shipadd',".
	"shipmaint='$shipmaint',   shipupdate='$shipupdate',".
	"shipaux1='$shipaux1',     shipaux2='$shipaux2', ".
	"shipsvccode='$shipsvccode' ".
	"where shipid=$shipid");
 if($shipmeth==1){
  $fcs->query("delete from shipthresh where shipid=$shipid");

  // numlvl comes in from the Web form
  $i=0; // loop counter, array index
  $j=0; // lags i by one to reference last index
  $l=0; // end of loop flag
  $hi=(double)1000000.; // in case they don't enter shipping charges
  while($i<$numlvl){
   if(!$i){ $lo=0; }
   else   { $lo=(double)$shiphi[$j]; } // lower range, previous array entry
   $hi=(double)$shiphi[$i]; // upper shipping range
   $pr=(double)$shippr[$i]; // shipping price
   if(($hi==1000000.) || ($hi==0 && strlen($shippr[$i]))){
    $l=1;
    $hi=1000000.;
   }elseif($hi==0 && $pr==0){ // end of filled in shipping table
    $l=1;
    $i--;
    $pr=(double)$shippr[$i];
    $hi=1000000.;
   }
   $fcs->query("insert into shipthresh ".
	 "(shipid,shipzid,shiplid,shipseq,shipamt,shiplow,shiphigh) ".
	 "values ($shipid,$zoneid,$langid,$i,$pr,$lo,$hi)");
   if($i>0){ $j++; }      // incr the one back index
   if($l){ $i=$numlvl; }  // if last loop iteration
   else  { $i++; }
  }
 }elseif($shipmeth==5){
  $fcs->query("delete from weightthresh where shipid=$shipid");
 
  // numlvl comes in from the Web form
  $i=0; // loop counter, array index
  $j=0; // lags i by one to reference last index
  $l=0; // end of loop flag
  $hi=(double)1000000.; // in case they don't enter shipping charges
  while($i<$numlvl){
   if(!$i){ $lo=0; }
   else   { $lo=(double)$weighthi[$j]; } // lower range, previous array entry
   $hi=(double)$weighthi[$i]; // upper weight range
   $pr=(double)$weightpr[$i]; // shipping price
   if(($hi==1000000.) || ($hi==0 && strlen($weightpr[$i]))){
    $l=1;
    $hi=1000000.;
   }elseif($hi==0 && $pr==0){ // end of filled in weight table
    $l=1;
    $i--;
    $pr=(double)$weightpr[$i];
    $hi=1000000.;
   }
   $fcs->query("insert into weightthresh ".
        "(shipid,shipzid,shiplid,shipseq,shipamt,shiplow,shiphigh) ".
        "values ($shipid,$zoneid,$langid,$i,$pr,$lo,$hi)");
   if($i>0){ $j++; }      // incr the one back index
   if($l){ $i=$numlvl; }  // if last loop iteration
   else  { $i++; }
  }
 }
} elseif($act=="new") {

 if( $databaseeng=='odbc' && $dialect=='solid' ){
  $fcs->query("call ship_ins (".
 	" $zoneid,     $langid,      '$shipdescr', ".
	" $shipmeth,   $shippercent, $shipitem,     $shipitem2, '$shipcalc', ".
	"'$shipadd',  '$shipmaint', '$shipupdate', '$shipaux1', '$shipaux2', ".
	"'$shipsvccode, 1)"); 
 }else{
  $fcs->query("insert into ship (shipzid,shiplid,".
    "shipdescr,shipmeth,shippercent,shipitem,shipitem2,shipcalc,shipadd,".
	"shipmaint,shipupdate,shipaux1,shipaux2,shipsvccode,active) values ".
 	"($zoneid,     $langid,       '$shipdescr', ".
	" $shipmeth,   $shippercent, $shipitem,     $shipitem2, '$shipcalc', ".
	"'$shipadd',  '$shipmaint', '$shipupdate', '$shipaux1', '$shipaux2', ".
	"'$shipsvccode', 1)"); 
 }

 // get the ID of the shipping profile just added
 $shipid=$fcs->insert_id("shipid","ship","shipdescr='$shipdescr'");

 if($shipmeth==1){	//threshold shipping

  // numlvl comes in from the Web form
  $i=0; // loop counter, array index
  $j=0; // lags i by one to reference last index
  $l=0; // end of loop flag
  while($i<$numlvl){
   if(!$i){ $lo=(double)0; }
   else   { $lo=(double)$shiphi[$j]; } // lower range, previous array entry
   $hi=(double)$shiphi[$i]; // upper shipping range
   $pr=(double)$shippr[$i]; // shipping price
   if(($hi==1000000.) || ($hi==0 && $pr)){
    $l=1;
    $hi=1000000.;
   }elseif($hi==0 && $pr==0){ // end of filled in shipping blanks
    $l=1;
    $i--;
    $pr=(double)$shippr[$i];
    $hi=1000000.;
   }
   $fcs->query("insert into shipthresh ".
	 "(shipid,shipzid,shiplid,shipseq,shipamt,shiplow,shiphigh) ".
	 "values ($shipid,$zoneid,$langid,$i,$pr,$lo,$hi)");
   if($i>0){ $j++; }      // incr the one back index
   if($l){ $i=$numlvl; }  // if last loop iteration
   else  { $i++; }
  }
 
 }elseif($shipmeth==5){
 
  // numlvl comes in from the Web form
  $i=0; // loop counter, array index
  $j=0; // lags i by one to reference last index
  $l=0; // end of loop flag
  while($i<$numlvl){
   if(!$i){ $lo=(double)0; }
   else   { $lo=(double)$weighthi[$j]; } // lower range, previous array entry
   $hi=(double)$weighthi[$i]; // upper weight range
   $pr=(double)$weightpr[$i]; // shipping price
   if(($hi==1000000.) || ($hi==0 && $pr)){
    $l=1;
    $hi=1000000.;
   }elseif($hi==0 && $pr==0){ // end of filled in shipping blanks
    $l=1;
    $i--;
    $pr=(double)$weightpr[$i];
    $hi=1000000.;
   }
   $fcs->query("insert into weightthresh ".
        "(shipzid,shiplid,shipseq,shipamt,shiplow,shiphigh) ".
        "values ($zoneid,$langid,$i,$pr,$lo,$hi)");
   if($i>0){ $j++; }      // incr the one back index
   if($l){ $i=$numlvl; }  // if last loop iteration
   else  { $i++; }
  }
 }

} elseif($act=="delete") {
 $fcs->query("delete from ship where shipid=$shipid");
 $fcs->query("delete from shipthresh where shipid=$shipid");
 $fcs->query("delete from weightthresh where shipid=$shipid");
}

$fcs->commit();
?>
Work committed.

<p>

<?php if($act=="new") { ?>
<form method=post action="shipadd.php">
<?php }else{ ?>
<form method=post action="shipndx.php">
<?php } ?>
<input type=hidden name=ssku value=<?php echo $ssku?>>
<input type=hidden name=srch value=<?php echo $srch?>>
<input type=hidden name=show value=<?php echo $show?>>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=submit value="Return to Shipping Maintenance">
</form>

<?php require('./footer.php');?>
