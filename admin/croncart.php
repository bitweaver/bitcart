#!/usr/local/bin/php -q
<?php /*

// the path above may need to change...

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

// this module is for any cron based work on a particular cart
// it is designed to be run at midnight to process the previous
// day

// the path on this require line may change depending on how
// your cgi version of php is compiled.
require('./admin.php');

$now=time();

// get today, then rewind to midnight
$yr4=date("Y",$now);
$mon=date("m",$now);
$day=date("d",$now);

// midnight of the present day
$midnight=mktime(0,0,0,$mon,$day,$yr4);

// now figure time for midnight of previous day
$ysday=$midnight-86400;

// get alpha date
$ysday_mdy=date("m / d / Y",$ysday);

$fct = new FC_SQL;

if ( $databaseeng == 'odbc' && $dialect == 'solid' ){
	// reset the daily sequence number
	// this assumes the cron file is run once a day at midnight

	// IF THIS FILE RUNS AT OTHER THAN MIDNIGHT, YOU MUST MOVE
	// THIS SEQUENCE NUMBER RESET CODE INTO A SEPARATE FILE THAT
	// DOES RUN AT MIDNIGHT!

	$fct->query("call reset_ins");
	$fct->query("commit work");
}

$fct->query("delete from nprod where nend < $midnight");
if ( $databaseeng == 'odbc' && $dialect == 'solid' ){
	$fct->query("commit work");
}

// figure a few order stats and report
$fct->query("select count(*) as cnt from ohead ".
    "where tstamp>=$ysday and tstamp<$midnight ".
	"and complete >= -1 and complete <= 2");
$fct->next_record();
$oheadtot=(int)$fct->f("cnt");
$fct->free_result();

$fct->query("select count(*) as cnt from ohead ".
    "where complete=-1 and tstamp>=$ysday and tstamp<$midnight");
$fct->next_record();
$oheadinit=(int)$fct->f("cnt");
$fct->free_result();

$fct->query("select count(*) as cnt from ohead ".
    "where complete=0 and tstamp>=$ysday and tstamp<$midnight");
$fct->next_record();
$oheadaban=(int)$fct->f("cnt");
$fct->free_result();

$fct->query("select count(*) as cnt from ohead ".
    "where complete=1 and tstamp>=$ysday and tstamp<$midnight");
$fct->next_record();
$oheadcomp=(int)$fct->f("cnt");
$fct->free_result();

$fct->query("select sum(contrib) as ctot, sum(ototal) as otot from ohead ".
    "where complete=1 and tstamp>=$ysday and tstamp<$midnight");
$fct->next_record();
$contrib_total=(double)$fct->f("ctot");
$order_total=(double)$fct->f("otot");
$fct->free_result();

$fct->query("select count(*) as cnt from ohead ".
    "where complete=3 and tstamp>=$ysday and tstamp<$midnight");
$fct->next_record();
$offoheadcomp=(int)$fct->f("cnt");
$fct->free_result();

$fct->query("select sum(contrib) as ctot, sum(ototal) as otot from ohead ".
    "where complete=3 and tstamp>=$ysday and tstamp<$midnight");
$fct->next_record();
$offcontrib_total=(double)$fct->f("ctot");
$offorder_total=(double)$fct->f("otot");
$fct->free_result();

$prod_total = $order_total - $contrib_total;

$tmp=sprintf("\n".
	"Initialized Orders:           %5d\n".
	"Incomplete Orders:            %5d\n".
	"Completed Orders:             %5d\n".
	"Offline Orders:               %5d\n".
	"Total All Orders:             %5d\n".
	"\n".
	"Total Products:               %10.2f\n".
	"Total Contributions:          %10.2f\n".
	"Total Offline:                %10.2f\n".
	"Total Offline Contributions:  %10.2f\n".
	"Total Order Amount:           %10.2f\n",
	$oheadinit,$oheadaban,$oheadcomp,$offoheadcomp,$oheadtot,
	$prod_total,$contrib_total,$offorder_total,$offcontrib_total,$order_total);

// get default zone
$fm = new FC_SQL;
$fm->query("select zoneid from master");
$fm->next_record();
$zid=(int)$fm->f("zoneid");
$fm->free_result();

// get order email address(es)
$fcv = new FC_SQL;
$fcv->query("select vendoemail from vend where vendzid=$zid"); 
$fcv->next_record();
$oemail=$fcv->f('vendoemail');
$fcv->free_result();

if( $oemail ){
	mail($oemail,"FishCart Summary for $ysday_mdy",$tmp);
}

//
// clear uncomplete orders over 48 hours old
//

$oldord=(int)time()-172800;   // subtract two days

$fch = new FC_SQL;
$fcl = new FC_SQL;

$fch->query("select ohead.orderid as orderid from ohead,oline ".
 "where ohead.tstamp<$oldord and ohead.orderid=oline.orderid ".
 "and ohead.complete<1");
$i=0;
while( $fch->next_record() ){
 $i++;
 $oid=$fch->f("orderid");
 $fcl->query("delete from oline where orderid='$oid'");
 if(25<$i){
  // commit every 25 rows
  $fch->commit();
  $i=0;
 }
}
$fch->query("delete from ohead where tstamp<$oldord and complete<1");
$fch->commit();

// delete all stored CC numbers over 10 days old
$d2d = $now - (86400 * 10);
$fct->query("update cust set custccnumber='' where custlodate < $d2d");
$fct->commit();
$fct->query("update ohead set oheadccnumber='' where tstamp < $d2d");
$fct->commit();
?>
