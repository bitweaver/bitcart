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


This script processes orders into an XML file per the standard 
FishCart orders DTD.  It processes order between midnight and
midnight of the prior day.  It is meant to be run from a cron job
by a standalone PHP interpreter.  Customize it for your purposes
as needed.

NOTE: this script requires the latest DOM extensions to PHP.
The DOM method names have changed since their early implementation
in PHP 4.0.x; this script uses the new method names of PHP 4.2.x.
 
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

$oheadpgp=$ohead.'.pgp';


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


// create the xml document
$doc = domxml_new_doc("1.0");

$node = $doc->create_element("fishcart_orders");
$work = $doc->append_child($node);

// create the order summary node
$node = $doc->create_element("order_summary");
$xhead = $work->append_child($node);


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

	$billing_sal=       trim($fcc->f("custbsal"));
	$billing_first=     trim($fcc->f("custbfname"));
	$billing_mi=        trim($fcc->f("custbmname"));
	$billing_last=      trim($fcc->f("custblname"));
	$billing_addr1=     trim($fcc->f("custbaddr1"));
	$billing_addr2=     trim($fcc->f("custbaddr2"));
	$billing_city=      trim($fcc->f("custbcity"));
	$billing_state=     trim($fcc->f("custbstate"));
	$billing_zip=       trim($fcc->f("custbzip"));
	$billing_zip4=      trim($fcc->f("custbzip4"));
	$billing_country=   trim($fcc->f("custbnatl"));
	$billing_acode=     trim($fcc->f("custbacode"));
	$billing_phone=     trim($fcc->f("custbphone"));
	$billing_email=     trim($fcc->f("custbemail"));

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
	$billing_phone =	ereg_replace("-", "",$billing_phone);

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
	$shipping_phone =	ereg_replace("-", "",$shipping_phone);

	$tmp=$doc->create_element("order_id");
	$tmp->set_content( $orderid );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("date_year");
	$tmp->set_content( sprintf("%04d",date("Y",$tstamp)) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("date_month");
	$tmp->set_content( sprintf("%02d",date("m",$tstamp)) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("date_day");
	$tmp->set_content( sprintf("%02d",date("d",$tstamp)) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("date_hour");
	$tmp->set_content( sprintf("%02d",date("H",$tstamp)) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("date_minute");
	$tmp->set_content( sprintf("%02d",date("m",$tstamp)) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("date_second");
	$tmp->set_content( sprintf("%02d",date("s",$tstamp)) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_salutation");
	$tmp->set_content( $billing_sal );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_firstname");
	$tmp->set_content( $billing_first );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_mi");
	$tmp->set_content( $billing_mi );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_lastname");
	$tmp->set_content( $billing_last );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_address_1");
	$tmp->set_content( $billing_addr1 );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_address_2");
	$tmp->set_content( $billing_addr2 );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_city");
	$tmp->set_content( $billing_city );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_state");
	$tmp->set_content( $billing_state );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_postcode");
	$tmp->set_content( sprintf("%s %s",$billing_zip.$billing_zip4) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_country");
	$tmp->set_content( $billing_country );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_areacode");
	$tmp->set_content( $billing_acode );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_phone");
	$tmp->set_content( $billing_phone );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("billing_email");
	$tmp->set_content( $billing_email );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_salutation");
	$tmp->set_content( $shipping_sal );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_firstname");
	$tmp->set_content( $shipping_first );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_mi");
	$tmp->set_content( $shipping_mi );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_lastname");
	$tmp->set_content( $shipping_last );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_attention");
	$tmp->set_content( '' );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_address_1");
	$tmp->set_content( $shipping_addr1 );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_address_2");
	$tmp->set_content( $shipping_addr2 );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_city");
	$tmp->set_content( $shipping_city );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_state");
	$tmp->set_content( $shipping_state );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_postcode");
	$tmp->set_content( sprintf("%s %s",$shipping_zip.$shipping_zip4) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_country");
	$tmp->set_content( $shipping_country );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_areacode");
	$tmp->set_content( $shipping_acode );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_phone");
	$tmp->set_content( $shipping_phone );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("shipping_email");
	$tmp->set_content( $shipping_email );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("cc_name");
	$tmp->set_content( $cc_name );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("cc_type");
	$tmp->set_content( $cc_type );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("cc_number");
	$tmp->set_content( $cc_number );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("cc_exp_year");
	$tmp->set_content( $ccexp_year );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("cc_exp_month");
	$tmp->set_content( $ccexp_mon );
	$xhead->append_child($tmp);

	$fcp->query("select count(*) as cnt from oline ".
				"where orderid='$orderid'");
	$fcp->next_record();
	$olinecnt=(int)$fcp->f("cnt");
	$fcp->free_result();

	$tmp=$doc->create_element("order_line_count");
	$tmp->set_content( $olinecnt );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_subtotal");
	$tmp->set_content( sprintf("%.2f\t",$pstotal) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_shipping");
	$tmp->set_content( sprintf("%.2f\t",$shamt) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_tax");
	$tmp->set_content( sprintf("%.2f\t",$stax) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_total");
	$tmp->set_content( sprintf("%.2f\t",$ostotal) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_discount");
	$tmp->set_content( sprintf("%.2f\t",$discount) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_contribution");
	$tmp->set_content( sprintf("%.2f\t",$contrib) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_grand_total");
	$tmp->set_content( sprintf("%.2f\t",$ototal) );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_comment_1");
	$tmp->set_content( $cmt1 );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_comment_2");
	$tmp->set_content( $cmt2 );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_comment_3");
	$tmp->set_content( $cmt3 );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_comment_4");
	$tmp->set_content( $cmt1 );
	$xhead->append_child($tmp);

	$tmp=$doc->create_element("order_comment_5");
	$tmp->set_content( $cmt5 );
	$xhead->append_child($tmp);


	$fcp->query("select * from oline where orderid='$orderid'");
	while( $fcp->next_record() ){

		$csku=$fcp->f("compsku");
		$qty=(int)$fcp->f("qty");
		$olprice=(double)$fcp->f("olprice");
		$ext=(double)rnd($olprice*$qty);

		// create an order detail node
		$node = $doc->create_element("order_details");
		$xline = $work->append_child($node);

		$tmp=$doc->create_element("detail_order_id");
		$tmp->set_content( $orderid );
		$xline->append_child($tmp);

		$tmp=$doc->create_element("detail_sku");
		$tmp->set_content( $csku );
		$xline->append_child($tmp);

		$tmp=$doc->create_element("detail_quantity");
		$tmp->set_content( $qty );
		$xline->append_child($tmp);

		$tmp=$doc->create_element("detail_price");
		$tmp->set_content( $olprice );
		$xline->append_child($tmp);

		$tmp=$doc->create_element("detail_extension");
		$tmp->set_content( $ext );
		$xline->append_child($tmp);

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

if( '' ){		// pgp encrypt if a key was given

	// use the PGP -w(ipe) option to delete the source file after encryption
	// the PGPPATH env variable should be set to /usr/nobody/.pgp

	$cmdh ="pgp -ew +batchmode +verbose=0 $ohead ";

	system($cmdh);
	system($cmdl);

	// echo "\nchowning $oheadpgp\n\n";
	chown($oheadpgp,'');
	chgrp($oheadpgp,'');
	chmod($oheadpgp,$tperm);

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
