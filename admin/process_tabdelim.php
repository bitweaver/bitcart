#!/usr/local/bin/php -q
<?php
/*
CHANGE THE PATH ABOVE AS NEEDED TO A STANDALONE PHP INTERPRETER

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


This file is a sample of how a tab delimited file can be created.
It processes order between midnight and midnight of the prior day.
It is meant to be run from a cron job by a standalone PHP interpreter.
Customize it for your purposes as needed.
   
*/

require_once( BITCART_PKG_PATH.'functions.php');
require('./admin.php');

$now = time();
// get today, then rewind to midnight
$yr4=date("Y",$now);
$mon=date("m",$now);
$day=date("d",$now);

$today=mktime(0,0,0,$mon,$day,$yr4);

// now figure time for midnight of previous day
$ysday=$today-86400;

// now figure time for midnight tonight (testing use only)
$midnight=$today+86400;

// get info for yesterday
$yr4=date("Y",$ysday);
$mon=date("m",$ysday);
$day=date("d",$ysday);

$today_mdy = $mon.'/'.$day.'/'.$yr4;

// set up a few filename / permission variables.
$t=date("_Ymd_Hi",time());
$tperm='0400';
// line terminator \r\n = DOS, \n = Unix, \r = Mac
$lterm='\r\n';

// put the files in an 'orders' directory under the
// password protected ./maint directory
$docpath='BITCART_PKG_PATHmaint/orders';

$ohead="ohead".$t;
$oline="oline".$t;

$oheadpgp=$ohead.'.pgp';
$olinepgp=$oline.'.pgp';


// sql select string for order selection for the process below

$select="complete=1 and tstamp<$today";


chdir( $docpath );

$fcc = new FC_SQL;
$fcl = new FC_SQL;
$fcp = new FC_SQL;

$fcl->query("select count(*) as cnt from ohead where $select");
$fcl->next_record();
$oheadcnt=(int)$fcl->f("cnt");
$fcl->free_result();

$todate=date("Y/m/d H:i:s",$today);
$ysdate=date("Y/m/d H:i:s",$ysday);

//echo "\ntoday:  $today  $todate    yesterday: $ysday  $ysdate    ".
//	 "oheadcnt: $oheadcnt\n\n";
//exit;

$oh=fopen($ohead,"w");
if( !$oh ){
	$edate=date("m/d/y H:i",time());
	mail($errorEmail," Cart Error","error opening $ohead: $edate");
}

$ol=fopen($oline,"w");
if( !$ol ){
	$edate=date("m/d/y H:i",time());
	mail($errorEmail," Cart Error","error opening $oline: $edate");
}

$stax_total=0;
$ship_total=0;
$order_total=0;
$contrib_total=0;

$fcl->query("select * from ohead where $select order by tstamp");

$j=0;
while( $fcl->next_record() ){

	$ozone =	   (int)$fcl->f("zone");
	$szone =	   (int)$fcl->f("subz");
	$shipid =	   (int)$fcl->f("shipid");
	$orderid =			$fcl->f("orderid");
	$custid =			$fcl->f("purchid");
	$tstamp =			$fcl->f("tstamp");
	$couponid =			$fcl->f("couponid");
	$aid =				$fcl->f("aid");
	$contrib =	(double)$fcl->f("contrib");
	$pstotal =	(double)$fcl->f("pstotal");
	$shamt =	(double)$fcl->f("shamt");
	$nstax =	(double)$fcl->f("nstax");
	$tstax =	(double)$fcl->f("tstax");
	$discount =	(double)$fcl->f("discount");
	$ostotal =	(double)$fcl->f("ostotal");
	$ototal =	(double)$fcl->f("ototal");
	$cmt1 =				$fcl->f("cmt1");	// user data
	$cmt2 =				$fcl->f("cmt2");	// user data
	$cmt3 =				$fcl->f("cmt3");	// user data
	$cmt4 =				$fcl->f("cmt4");	// user data
	$cmt5 =				$fcl->f("cmt5");	// user data

	// sum of the taxable shipping and nontaxable shipping rates
	$stax = rnd($nstax + tstax);

	$fcc->query("select * from CUSTOMERTABLE where custid=$custid");
	$fcc->next_record();

	$billing_sal=       $fcc->f("custbsal");
	$billing_first=     $fcc->f("custbfname");
	$billing_mi=        $fcc->f("custbmname");
	$billing_last=      $fcc->f("custblname");
	$billing_addr1=     $fcc->f("custbaddr1");
	$billing_addr2=     $fcc->f("custbaddr2");
	$billing_city=      $fcc->f("custbcity");
	$billing_state=     $fcc->f("custbstate");
	$billing_zip=       $fcc->f("custbzip");
	$billing_zip4=      $fcc->f("custbzip4");
	$billing_country=   $fcc->f("custbnatl");
	$billing_acode=     $fcc->f("custbacode");
	$billing_phone=     $fcc->f("custbphone");
	$billing_email=     $fcc->f("custbemail");

	$shipping_sal=      trim($fcc->f("custssal"));
	$shipping_first=    trim($fcc->f("custsfname"));
	$shipping_mi=       trim($fcc->f("custsmname"));
	$shipping_last=     trim($fcc->f("custslname"));
	$shipping_addr1=    trim($fcc->f("custsaddr1"));
	$shipping_addr2=    trim($fcc->f("custsaddr2"));
	$shipping_city=     trim($fcc->f("custscity"));
	$shipping_state=    trim($fcc->f("custsstate"));
	$shipping_zip=      trim($fcc->f("custszip"));
	$shipping_zip4=     trim($fcc->f("custszip4"));
	$shipping_country=  trim($fcc->f("custsnatl"));
	$shipping_acode=    trim($fcc->f("custsacode"));
	$shipping_phone=    trim($fcc->f("custsphone"));
	$shipping_email=    trim($fcc->f("custsemail"));

	$cc_name	=		$fcc->f("custccname");
	$cc_number	=		$fcc->f("custccnumber");
	$cc_type	=		$fcc->f("custcctype");
	$ccexp_mon	=		$fcc->f("custccexpmo");
	$ccexp_year	=		$fcc->f("custccexpyr");

	$cc_number =		ereg_replace(" ","",$cc_number);
	$cc_number =		ereg_replace("-","",$cc_number);   

	$fcc->free_result();

	if( !$shipping_sal ){      $shipping_sal      =$billing_sal; }
	if( !$shipping_first ){    $shipping_first    =$billing_first; }
	if( !$shipping_last ){     $shipping_last     =$billing_last; }
	if( !$shipping_addr1 ){    $shipping_addr1    =$billing_addr1; }
	if( !$shipping_addr2 ){    $shipping_addr2    =$billing_addr2; }
	if( !$shipping_city ){     $shipping_city     =$billing_city; }
	if( !$shipping_state ){    $shipping_state    =$billing_state; }
	if( !$shipping_zip ){      $shipping_zip      =$billing_zip; }
	if( !$shipping_zip4 ){     $shipping_zip4     =$billing_zip4; }
	if( !$shipping_country ){  $shipping_country  =$billing_country; }
	if( !$shipping_acode ){    $shipping_acode    =$billing_acode; }
	if( !$shipping_phone ){    $shipping_phone    =$billing_phone; }
	if( !$shipping_email ){    $shipping_email    =$billing_email; }

	// strip double quotes
	$couponid =			ereg_replace("\"","",$couponid);
	$aid =				ereg_replace("\"","",$aid);
	$cmt1 =				ereg_replace("\"","",$cmt1);
	$cmt2 =				ereg_replace("\"","",$cmt1);
	$cmt3 =				ereg_replace("\"","",$cmt3);
	$cmt4 =				ereg_replace("\"","",$cmt3);
	$cmt5 =				ereg_replace("\"","",$cmt5);

	$billing_first =	ereg_replace("\"","",$billing_first);
	$billing_last =		ereg_replace("\"","",$billing_last);
	$billing_addr1 =	ereg_replace("\"","",$billing_addr1);
	$billing_addr2 =	ereg_replace("\"","",$billing_addr2);
	$billing_city =		ereg_replace("\"","",$billing_city);
	$billing_state =	ereg_replace("\"","",$billing_state);
	$billing_zip =		ereg_replace("\"","",$billing_zip);
	$billing_zip4 =		ereg_replace("\"","",$billing_zip4);
	$billing_country =	ereg_replace("\"","",$billing_country);
	$billing_email =	ereg_replace("\"","",$billing_email);
	$billing_acode =	ereg_replace("\"","",$billing_acode);
	$billing_phone =	ereg_replace("\"","",$billing_phone);
	$billing_phone =	ereg_replace("\.","",$billing_phone);
	$billing_phone =	ereg_replace("-","",$billing_phone);

	$shipping_first =	ereg_replace("\"","",$shipping_first);
	$shipping_last =	ereg_replace("\"","",$shipping_last);
	$shipping_addr1 =	ereg_replace("\"","",$shipping_addr1);
	$shipping_addr2 =	ereg_replace("\"","",$shipping_addr2);
	$shipping_city =	ereg_replace("\"","",$shipping_city);
	$shipping_state =	ereg_replace("\"","",$shipping_state);
	$shipping_zip =		ereg_replace("\"","",$shipping_zip);
	$shipping_zip4 =	ereg_replace("\"","",$shipping_zip4);
	$shipping_country =	ereg_replace("\"","",$shipping_country);
	$shipping_email =	ereg_replace("\"","",$shipping_email);
	$shipping_acode =	ereg_replace("\"","",$shipping_acode);
	$shipping_phone =	ereg_replace("\"","",$shipping_phone);
	$shipping_phone =	ereg_replace("\.","",$shipping_phone);
	$shipping_phone =	ereg_replace("-","",$shipping_phone);

	// now write the order file
	$tmp =sprintf("%s\t",$orderid);						// cartid
	$tmp.=sprintf("%04d-%02d-%02d\t",					// YYYY-MM-DD
		date("Y",$tstamp),
		date("m",$tstamp),
		date("d",$tstamp) );
	$tmp.=sprintf("%02d:%02d:%02d\t",					// HH:MM:SS
		date("H",$tstamp),
		date("i",$tstamp),
		date("s",$tstamp) );

	$tmp.=sprintf("%s\t",$billing_first);				// first name
	$tmp.=sprintf("%s\t",$billing_mi);					// middle initial
	$tmp.=sprintf("%s\t",$billing_last);				// last name
	$tmp.="\t";											// name suffix
	$tmp.=sprintf("%s\t",$billing_sal);					// salutation
	$tmp.=sprintf("%s\t",$billing_addr1);				// address1
	$tmp.=sprintf("%s\t",$billing_addr2);				// attention
	$tmp.=sprintf("%s\t",$billing_city);				// city
	$tmp.=sprintf("%s\t",$billing_state);				// state
	$tmp.=sprintf("%s\t",$billing_zip);					// postal code
	$tmp.=sprintf("%s\t",$billing_country);				// country
	$tmp.=sprintf("%s/%s-%s\t",							// area/phone
		$billing_acode, substr($billing_phone,0,3), substr($billing_phone,3));
	$tmp.=sprintf("%s\t",$billing_email);				// email

	$tmp.=sprintf("%s %s\t",							// first name
		$shipping_first,$shipping_last);
	$tmp.=sprintf("%s\t",$shipping_addr1);				// address1
	$tmp.=sprintf("%s\t",$shipping_addr2);				// attention
	$tmp.=sprintf("%s\t",$shipping_city);				// city
	$tmp.=sprintf("%s\t",$shipping_state);				// state
	$tmp.=sprintf("%s\t",$shipping_zip);				// postal code
	$tmp.=sprintf("%s\t",$shipping_country);			// country

	$tmp.=sprintf("%s\t",$cc_number);					// card number
	$tmp.=sprintf("%s\t",$cc_type);						// card type
	$tmp.=sprintf("%s\t",$cc_name);						// card name
	$tmp.=sprintf("%02d/%4d\t",							// MM/YYYY
		$ccexp_mon,$ccexp_year);

	$fcp->query("select count(*) as cnt from oline ".
				"where orderid='$orderid'");
	$fcp->next_record();
	$olinecnt=(int)$fcp->f("cnt");
	$fcp->free_result();

	$tmp.=sprintf("%d\t",$olinecnt);					// product count
	$tmp.=sprintf("%.2f\t",$pstotal);					// pre disc subtot
	$tmp.=sprintf("%.2f\t",$shamt);						// shipping
	$tmp.=sprintf("%.2f\t",$stax);						// salestax
	$tmp.=sprintf("%.2f\t",$ostotal);					// total
	$tmp.=sprintf("%.2f\t",$discount);					// discount
	$tmp.=sprintf("%.2f\t",$contrib);					// contribution
	$tmp.=sprintf("%.2f\t",$ototal);					// amount paid

	$tmp.=sprintf("%s\t",$cmt1);						// user data
	$tmp.=sprintf("%s\t",$cmt2);						// user data
	$tmp.=sprintf("%s\t",$cmt3);						// user data
	$tmp.=sprintf("%s\t",$cmt4);						// user data
	$tmp.=sprintf("%s\t",$cmt5);						// user data

	/* online check collection fields
	   ABA Routing Code (9 digits)
	   Account No (up to 12 digits)
	   Account Type (1 char - S-Savings, C-Checking)
	   Check No (up to 6 digits)
	 */
	$tmp.=sprintf("%s\t",  '');							// ABA rte code  9 dig
	$tmp.=sprintf("%s\t",  '');							// act # <= 12 dig
	$tmp.=sprintf("%s\t",  '');							// act type 1 char (S/C)
	$tmp.=sprintf("%s",    '');							// check # <= 6 dig

	$tmp.=$lterm;
	// echo $tmp."\n";

	// now escape re-escape the single quotes before writing
	// quoting magic removes the escaping one.
	$tmp=addslashes($tmp);

	fputs($oh,$tmp);

	$fcp->query("select * from oline where orderid='$orderid'");
	while( $fcp->next_record() ){

		$csku=$fcp->f("compsku");
		$qty=(int)$fcp->f("qty");
		$olprice=(double)$fcp->f("olprice");
		$ext=(double)rnd($olprice*$qty);

		$tmp =sprintf("%s\t",$orderid);					// cartid
		$tmp.=sprintf("%s\t",$csku);					// SKU
		$tmp.=sprintf("%d\t",$qty);						// quantity
		$tmp.=sprintf("%.2f\t",$olprice);				// price
		$tmp.=sprintf("%.2f",$ext);						// ext price
		$tmp.=$lterm;

		fputs($ol,$tmp);
		// echo "   ".$tmp.$lterm;
	}
	$fcp->free_result();

	// accumulate order totals
	// echo "orderid: $orderid\tcontrib: $contrib\tototal: $ototal\n";
	$prod_subtotal = rnd($prod_subtotal + $pstotal);
	$stax_total    = rnd($stax_total    + $stax);
	$ship_total    = rnd($ship_total    + $shamt);
	$contrib_total = rnd($contrib_total + $contrib);
	$order_total   = rnd($order_total   + $ototal);

	$j++;
}
$fcl->free_result();
fclose($oh);
fclose($ol);

chown($ohead,'');
chgrp($ohead,'');
chmod($ohead,$tperm);
chown($oline,'');
chgrp($oline,'');
chmod($oline,$tperm);

if( '' ){		// pgp encrypt if a key was given

	// use the PGP -w(ipe) option to delete the source file after encryption
	// the PGPPATH env variable should be set to /usr/nobody/.pgp

	$cmdh ="pgp -ew +batchmode +verbose=0 $ohead ";
	$cmdl ="pgp -ew +batchmode +verbose=0 $oline ";

	system($cmdh);
	system($cmdl);

	// echo "\nchowning $oheadpgp and $olinepgp\n\n";
	chown($oheadpgp,'');
	chgrp($oheadpgp,'');
	chmod($oheadpgp,$tperm);

	chown($olinepgp,'');
	chgrp($olinepgp,'');
	chmod($olinepgp,$tperm);

}


// figure a few order stats and report
$fcl->query(
 "select count(*) as cnt from ohead ".
 "where tstamp>=$ysday and tstamp<$today");
$fcl->next_record();
$oheadtot=(int)$fcl->f("cnt");
$fcl->free_result();

$fcl->query(
 "select count(*) as cnt from ohead ".
 "where complete=-1 and tstamp>=$ysday and tstamp<$today");
$fcl->next_record();
$oheadinit=(int)$fcl->f("cnt");
$fcl->free_result();

$fcl->query(
 "select count(*) as cnt from ohead ".
 "where complete=0 and tstamp>=$ysday and tstamp<$today");
$fcl->next_record();
$oheadaban=(int)$fcl->f("cnt");
$fcl->free_result();

$fcl->query(
 "select count(*) as cnt from ohead ".
 "where complete=1 and tstamp<$today");
$fcl->next_record();
$oheadcomp=(int)$fcl->f("cnt");
$fcl->free_result();

$prod_total = rnd($order_total - $contrib_total);

// ADD TOTALS TAX AND SHIPPING, SUBTRACT FROM TOTAL PRODUCT ORDERS
$tmp=sprintf("\n".
	 "Initialized Orders:    %4d\n".
	 "Incomplete Orders:     %4d\n".
	 "Completed Orders:      %4d\n".
	 "Total All Orders:      %4d\n".
	 "\n".
	 "Total Sales:           %10.2f\n".
	 "Total Sales Tax:       %10.2f\n".
	 "Total Shipping:        %10.2f\n".
	 "Total Product Orders:  %10.2f\n".
	 "Total Contributions:   %10.2f\n".
	 "Total Amount:          %10.2f  (products and contributions)\n",
	 $oheadinit,$oheadaban,$oheadcomp,$oheadtot,
	 $prod_subtotal,
	 $stax_total,
	 $ship_total,
	 $prod_total,
	 $contrib_total,
	 $order_total);

mail($errorEmail," FishCart Summary for $today_mdy",$tmp);

// check for stranded orders, i.e. orders that are older than 
// yesterday that have not been processed
$fcl->query("select count(*) as cnt from ohead ".
	"where complete=1 and tstamp < $ysday");
$fcl->next_record();
$oheadcnt=(int)$fcl->f("cnt");
$fcl->free_result();

if( $oheadcnt > 0 ){
	mail($errorEmail,
		 " STRANDED ORDERS: $oheadcnt",
		 "Found $oheadcnt stranded orders prior to $today_mdy, timeval $ysday");
}

// update just processed orders to complete = 2 status
$fcl->query("update ohead set complete=2 where $select");
$fcl->commit();


// clear the order file of all abandoned orders over 2 days old
$num_days = 2;
$create=(int)((time())-(86400*$num_days));	// subtract # of days
$fcl->query("select ohead.orderid as orderid from ohead,oline ".
 "where ohead.tstamp < $create and ohead.orderid=oline.orderid ".
 "and complete < 1");
while( $fcl->next_record() ){
	$oid=$fcl->f('orderid');
	$fcp->query("delete from oline where orderid='$oid'");
}
$fcl->query("delete from ohead where tstamp < $create and complete < 1");
$fcl->commit();
?>
