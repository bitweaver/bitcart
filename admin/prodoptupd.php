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

require_once( BITCART_PKG_PATH.'functions.php');
// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

$act = getparam('act');

$ssku = getparam('ssku');
$poptid = (int)getparam('poptid');
$poptseq = (int)getparam('poptseq');
$poptgrp = (int)getparam('poptgrp');
$poptsetup = (double)getparam('poptsetup');
$poptprice = (double)getparam('poptprice');

//product option sale vars
$osaleprice  = (double)getparam('osaleprice');
$ossy = (int)getparam('ossy');
$ossm = (int)getparam('ossm');
$ossd = (int)getparam('ossd');
$osey = (int)getparam('osey');
$osem = (int)getparam('osem');
$osed = (int)getparam('osed');

//product option setup sale vars
$ossaleprice  = (double)getparam('ossaleprice');
$osssy = (int)getparam('osssy');
$osssm = (int)getparam('osssm');
$osssd = (int)getparam('osssd');
$ossey = (int)getparam('ossey');
$ossem = (int)getparam('ossem');
$ossed = (int)getparam('ossed');

$poptpic   = getparam('poptpic');
$poptpich = (int)getparam('poptpich');
$poptpicw = (int)getparam('poptpicw');
$popttpic = getparam('popttpic');
$popttpich = (int)getparam('popttpich');
$popttpicw = (int)getparam('popttpicw');
$poptname = getparam('poptname');
$poptsdescr = getparam('poptsdescr');
$poptdescr  = getparam('poptdescr');
$poptskusub = getparam('poptskusub');
$poptskumod = getparam('poptskumod');
$pgrpname = getparam('pgrpname');

//do not remove or change the 2 lines below
$poptflag1=0;
$poptflag2=0;
$poptflag1 |= (int)getparam('skuact');
$poptflag1 |= (int)getparam('prcrel');
$poptflag1 |= (int)getparam('grpreq');
$poptflag1 |= (int)getparam('grpexc');
$poptflag1 |= (int)getparam('grpqty');

/*
3 additional vars can come in from prodoptadd or prodoptmod
if you need them just outcomment the values below and add the fields
to prodoptadd and prodoptmod
in the database they are varchar.
*/
//$popttext1 = getparam('popttext1');
//$popttext1 = getparam('popttext2');
//$popttext1 = getparam('popttext3');

// ==========  end of variable loading  ==========

//Option sale price
$osaleprice  = ereg_replace(',','',$osaleprice);        /* remove commas from price */
$osaleprice  = ereg_replace('[\$]{1,}','',$osaleprice); /* remove $ from price */
$osaleprice  = (double)$osaleprice;   // redundant

//Option setup sale price
$ossaleprice = ereg_replace(',','',$ossaleprice);        /* remove commas from price */
$ossaleprice = ereg_replace('[\$]{1,}','',$ossaleprice); /* remove $ from price */
$ossaleprice = (double)$ossaleprice;   // redundant

//Option sale price dates
if ( $ossm && $ossd && $ossy ) {
	$ossdate = mktime(0,0,0,$ossm,$ossd,$ossy);
} else {
	$ossdate = 0;
}
if ( $osem && $osed && $osey ) {
	$osedate = mktime(23,59,59,$osem,$osed,$osey);
} else {
	$osedate = 0;
}

//Option setup sale price dates
if ( $osssm && $osssd && $osssy ) {
	$osssdate = mktime(0,0,0,$osssm,$osssd,$osssy);
} else {
	$osssdate = 0;
}
if ( $ossem && $ossed && $ossey ) {
	$ossedate = mktime(23,59,59,$ossem,$ossed,$ossey);
} else {
	$ossedate = 0;
}

require('./admin.php');
require('./header.php');
require_once( BITCART_PKG_PATH.'flags.php');

$fcp = new FC_SQL;
$fpo = new FC_SQL;
$fcg = new FC_SQL;

if( $act == 'add' || ($act == 'update' && !(empty($pgrpname))) ){
  // update group name only if not null
  $fcg->query(
    "select count(*) as cnt from prodoptgrp ".
	"where pgrpzid=$zoneid and pgrplid=$langid and pgrpgrp=$poptgrp");
  $fcg->next_record();
  $cnt=$fcg->f('cnt');
  $fcg->free_result();
   // name/rename the option group with the value given
  if( $cnt ){
   $fcg->query(
  	"update prodoptgrp set pgrpname='$pgrpname' ".
	"where pgrpzid=$zoneid and pgrplid=$langid and pgrpgrp=$poptgrp");
  }else{ 
   $fcg->query(
  	"insert into prodoptgrp (pgrpzid,pgrplid,pgrpgrp,pgrpname) ".
	"values ($zoneid,$langid,$poptgrp,'$pgrpname')");
  }
}

if($act=='add'){
 if ( $poptgrp ){
  // get flag values from group if it exists
  $fpo->query(
    "select poptflag1,poptflag2 from prodopt ".
    "where poptsku='$poptsku' and poptgrp=$poptgrp");
  if($fpo->next_record()){
   $poptflag1=$fpo->f("poptflag1");
   $poptflag2=$fpo->f("poptflag2");
   $fpo->free_result();
  }
 }
 // add the product option
 if( $databaseeng=='odbc' && $dialect=='solid' ){
  $pr=$fcp->query(
	"call popt_ins (".
	"$zoneid,$langid,$poptgrp,$poptseq,'$ssku','$poptskumod','$poptskusub',".
	"$poptsetup,$poptprice,$osssdate,$ossedate,$ossaleprice,$ossdate,".
	"$osedate,$osaleprice,".
	"'$poptpic',$poptpich,$poptpicw,".
	"'$popttpic',$popttpich,$popttpicw,".
	"'$poptname','$poptsdescr','$poptdescr',".
	"'$popttext1','$popttext2','$popttext3',".
	"$poptflag1,$poptflag2)"); 
 }else{
  $pr=$fcp->query("insert into prodopt (".
	"poptzid,poptlid,poptgrp,poptseq,poptsku,poptskumod,poptskusub,poptsetup,".
	"poptprice,poptssalebeg,poptssaleend,poptssaleprice,".
	"poptsalebeg,poptsaleend,".
	"poptsaleprice,poptpic,poptpich,poptpicw,".
	"popttpic,popttpich,popttpicw,poptname,poptsdescr,poptdescr,popttext1,".
	"popttext2,popttext3,poptflag1,poptflag2".
	") values (".
	"$zoneid,$langid,$poptgrp,$poptseq,'$ssku','$poptskumod','$poptskusub',".
	"$poptsetup,$poptprice,$osssdate,$ossedate,$ossaleprice,$ossdate,".
	"$osedate,$osaleprice,'$poptpic',$poptpich,$poptpicw,".
	"'$popttpic',$popttpich,$popttpicw,".
	"'$poptname','$poptsdescr','$poptdescr',".
	"'$popttext1','$popttext2','$popttext3',".
	"$poptflag1,$poptflag2".
	")"); 
 }

}elseif($act=='update'){

  $pr=$fcp->query("update prodopt set ".
	"poptgrp=$poptgrp,poptseq=$poptseq,".
	"poptskumod='$poptskumod',poptskusub='$poptskusub',poptsetup=$poptsetup,".
	"poptprice=$poptprice,poptssalebeg=$osssdate,poptssaleend=$ossedate,".
	"poptssaleprice=$ossaleprice,poptsalebeg=$ossdate,poptsaleend=$osedate,".
	"poptsaleprice=$osaleprice,poptpic='$poptpic',poptpich=$poptpich,poptpicw=$poptpicw,".
	"popttpic='$popttpic',popttpich=$popttpich,popttpicw=$popttpicw,".
	"poptname='$poptname',poptsdescr='$poptsdescr',poptdescr='$poptdescr',".
	"popttext1='$popttext1',popttext2='$popttext2',popttext3='$popttext3',".
	"poptflag1=$poptflag1,poptflag2=$poptflag2 ".
	"where poptid=$poptid");

}elseif($act=='delete'){
  $pr=$fcp->query("delete from prodopt where poptid=$poptid");
}

if($pr){
	$fcp->commit();
  	echo "Work committed.\n";
}else{
	$fcp->rollback();
  	echo "Error: work rolled back.\n";
}

if( $act=='update' ){
 // make all group member flags look like this one
 $pr=$fcp->query(
  "update prodopt set poptflag1=$poptflag1,poptflag2=$poptflag2 ".
  "where poptsku='$ssku' and poptgrp=$poptgrp");
 $fcp->commit();
}

//set the prodlflag correctly        bvo
/*
First the options after updating all tables are fetched again
if there is an option in the current language defined it is
inserted into prodlflag1
(bvo)
*/
$fcco = new FC_SQL;
$fcco ->query("select * from prodopt where poptzid=$zoneid and poptlid=$langid".
              " and poptsku='$ssku'");
               if($fcco->next_record()){
               $poptf=1;
               }
$fcco -> free_result();

$plflag1=0;
if($poptf){$plflag1 |=(int)$flag_hasoption;}

$fccr = new FC_SQL;
$fccr -> query("update prodlang set prodlflag1=$plflag1 where prodlsku='$ssku'".
               " and prodlzid=$zoneid and prodlid=$langid");
$fccr -> commit();
//set the prodlflag correctly   bvo

?>

<p>

<?php if( $act=='update' || $act == 'delete' ){ ?>
<form method=post action="prodoptndx.php">
<?php }else{ ?>
<form method=post action="productmod.php">
<?php } ?>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=hidden name=ssku value="<?php echo $ssku?>">
<input type=hidden name=sku value="<?php echo $ssku?>">
<input type=submit value="Return to Product Option Maintenance">
</form>

<?php require('./footer.php'); ?>
