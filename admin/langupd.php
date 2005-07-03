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
$lngid  = (int)getparam('lngid');
$langzid = (int)getparam('langzid');
$langfppromo = (int)getparam('langfppromo');

$act = getparam('act');

$langdescr = getparam('langdescr');
$langwelcome = trim(getparam('langwelcome'));
$langcopy = trim(getparam('langcopy'));
$langterms = trim(getparam('langterms'));
$langtmpl = getparam('langtmpl');
$langstmpl = getparam('langstmpl');
$langtdsp = getparam('langtdsp');
$langterr = getparam('langterr');
$langshow = getparam('langshow');
$langgeo = getparam('langgeo');
$langordr = getparam('langordr');
$langproc = getparam('langproc');
$langfinl = getparam('langfinl');
$lngiso = getparam('lngiso');
$langiso = getparam('langiso');
$fmtwelcome = (int)getparam('fmtwelcome');
$fmtcopy = (int)getparam('fmtcopy');
$fmtterms = (int)getparam('fmtterms');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$droot="BITCART_PKG_PATH";

$langdescr=ereg_replace("\r","",$langdescr);
$langdescr=ereg_replace("\n","",$langdescr);

if( $fmtwelcome ){
 $langwelcome = nl2br( $langwelcome );
}
if( $fmtcopy ){
 $langcopy = nl2br( $langcopy );
}
if( $fmtterms ){
 $langterms = nl2br( $langterms );
}

if($langzid==""){?>
  A Zone ID was not selected.
	<p>Please click the &quot;Back&quot; button on your browser
	and correct the errors.  Thank you.
    <?php exit;
}

$fcl = new FC_SQL;
$fcm = new FC_SQL;
$fct = new FC_SQL;
$fcta = new FC_SQL;
$fctb = new FC_SQL;
$fctc = new FC_SQL;


if($act=="update"){

   //get the old langfppromo
   $fcta->query("select langfppromo from lang where langid=$lngid and ".
   "langzid=$zoneid");
   if($fcta->next_record()){
   $oldfppromo=(int)$fcta->f('langfppromo');
   }
   $fcta->free_result();


  // oracle clob
  if( $databaseeng=="oracle" ){
	$res = $fcl->query("update lang set ".
	"langzid=$langzid,     langdescr='$langdescr',langtmpl='$langtmpl', ".
	"langtdsp='$langtdsp', langterr='$langterr',  langshow='$langshow', ".
	"langgeo='$langgeo',   langordr='$langordr',  langfinl='$langfinl', ".
	"langproc='$langproc', langstmpl='$langstmpl',langiso ='$langiso', ".
	"langwelcome='$langwelcome',langcopy='$langcopy',langterms=empty_clob(),".
	"langfppromo=$langfppromo ".
	"where langid=$lngid",
	langterms, $langterms ); 
  }else{
	$res = $fcl->query("update lang set ".
	"langzid=$langzid,     langdescr='$langdescr',langtmpl='$langtmpl', ".
	"langtdsp='$langtdsp', langterr='$langterr',  langshow='$langshow', ".
	"langgeo='$langgeo',   langordr='$langordr',  langfinl='$langfinl', ".
	"langproc='$langproc', langstmpl='$langstmpl',langiso ='$langiso', ".
	"langwelcome='$langwelcome',langcopy='$langcopy',langterms='$langterms',".
	"langfppromo=$langfppromo ".
	"where langid=$lngid"); 
  }

} elseif($act=="new"){

  $fcm->query('select numlang from master');
  $fcm->next_record();
  $numl=(int)$fcm->f('numlang');
  $fcm->free_result();
  $numl+=1;
  $fcm->query("update master set numlang=$numl");

  // oracle clob
  if( $databaseeng=="oracle" ){
   $res = $fcl->query("insert into lang (langzid,langdescr,langtmpl,".
    "langtdsp,langterr,langshow,langgeo,langordr,langproc,langfinl,langstmpl,".
	"langiso,langwelcome,langcopy,langterms,langfppromo)".
	" values ".
	"($langzid,'$langdescr','$langtmpl','$langtdsp','$langterr',".
	"'$langshow','$langgeo','$langordr','$langproc','$langfinl','$langstmpl',".
	"'$langiso','$langwelcome','$langcopy','$langterms',$langfppromo)",
	langterms, $langterms ); 
  }elseif( $databaseeng=='odbc' && $dialect=='solid' ){
   $res = $fcl->query("call lang_ins ".
	"($langzid,0,'$langdescr','$langtmpl','$landtdsp','$langterr',".
	"'$langshow','$langgeo','$langordr','$langproc','$langfinl','$langstmpl',".
	"'langiso','$langwelcome','$langcopy','$langterms',$langfppromo)");
  }else{
   $res = $fcl->query("insert into lang (langzid,langdescr,langtmpl,".
    "langtdsp,langterr,langshow,langgeo,langordr,langproc,langfinl,langstmpl,".
	"langiso,langwelcome,langcopy,langterms,langfppromo)".
	" values ".
	"($langzid,'$langdescr','$langtmpl','$langtdsp','$langterr',".
	"'$langshow','$langgeo','$langordr','$langproc','$langfinl','$langstmpl',".
	"'$langiso','$langwelcome','$langcopy','$langterms',$langfppromo)");
  }

  // get the row id just inserted
  $newlid=(int)$fcl->insert_id('langid','lang',
						"langzid=$zoneid and langiso='$langiso'");

  // if this zone does not have a default language yet, autoselect this one
  $fct->query("select zonedeflid from zone where zoneid=$zoneid"); 
  $fct->next_record();
  $zonedeflid = (int)$fct->f('zonedeflid');
  $fct->free_result();
  if( $zonedeflid == 0 ){
   $fct->query("update zone set zonedeflid=$newlid where zoneid=$zoneid"); 
   $deflangiso = $langiso;
   $zonedeflid = $newlid;
  }else{
   $fct->query(
     "select langiso from lang ".
	 "where langzid=$zoneid and langid=$zonedeflid");
   $fct->next_record();
   $deflangiso=$fct->f('langiso');
   $fct->free_result();
  }

  // insert countrytable entries for this ISO code
  $fct->query(
    "select count(*) as cnt from country ".
    "where ctryzid=$zoneid and ctrylid=$newlid");
  $fct->next_record();
  $cnt = (int)$fct->f('cnt');
  $fct->free_result();
  $i=1;	// start sequence at 1
  if( $cnt == 0 ){
	$fcl->query(
      "select ctrylangciso from countrylang where ctrylangliso='$langiso'");
	while( $fcl->next_record() ){
	 $langciso = $fcl->f('ctrylangciso');
	 if( $deflangiso ){
	  // for additional language profiles start with the same sequence
	  // and active state as the existing default language
	  $fcm->query("select ctryseq,ctryactive from country ".
	     "where ctryzid=$zoneid and ctrylid=$zonedeflid and ctryiso='$langciso'");
	  $fcm->next_record();
	  $clseq=(int)$fcm->f('ctryseq');
	  $clactive=(int)$fcm->f('ctryactive');
      $fcm->free_result();
	 }else{
	  $clseq = $i;
	  $clactive = 1;
	 }
     $fct->query(
	  "insert into country (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive) ".
	  "values ($zoneid,$newlid,'$langciso',$clseq,$clactive)");
	 $i++;
	}
  }

  // duplicate shipping profiles from the default language
  // to the new one just defined; they can translate things later as needed
  $fct->query("select * from ship ".
      "where shipzid=$zoneid and shiplid=$zonedeflid"); 
  while( $fct->next_record() ){

    // extract the current data
    $active = (int)$fct->f('active');
    $shipid = (int)$fct->f('shipid');
    $shipzid = (int)$fct->f('shipzid');
    $shipmeth = (int)$fct->f('shipmeth');
    $shippercent = (double)$fct->f('shippercent');
    $shipitem = (double)$fct->f('shipitem');
    $shipitem2 = (double)$fct->f('shipitem2');
    $shipdescr = $fct->f('shipdescr');
    $shipcalc = $fct->f('shipcalc');
    $shipadd = $fct->f('shipadd');
    $shipmaint = $fct->f('shipmaint');
    $shipupdate = $fct->f('shipupdate');
    $shipaux1 = $fct->f('shipaux1');
    $shipaux2 = $fct->f('shipaux2');
    $shipsvccode = $fct->f('shipsvccode');

    // now insert the record
    if( $databaseeng=='odbc' and $dialect=='solid' ){
      $fcm->query("call ship_ins (".
 	  " $shipzid,    $newlid,     '$shipdescr', ".
	  " $shipmeth,   $shippercent, $shipitem,     $shipitem2, '$shipcalc', ".
	  "'$shipadd',  '$shipmaint', '$shipupdate', '$shipaux1', '$shipaux2', ".
	  "'$shipsvccode, $active)"); 
    }else{
      $fcm->query("insert into ship (shipzid,shiplid,".
      "shipdescr,shipmeth,shippercent,shipitem,shipitem2,shipcalc,shipadd,".
	  "shipmaint,shipupdate,shipaux1,shipaux2,shipsvccode,active) values ".
 	  "($shipzid,    $newlid,     '$shipdescr', ".
	  " $shipmeth,   $shippercent, $shipitem,     $shipitem2, '$shipcalc', ".
	  "'$shipadd',  '$shipmaint', '$shipupdate', '$shipaux1', '$shipaux2', ".
	  "'$shipsvccode', $active)"); 
    }

    // get the new ship profile id just inserted
    $new_shipid=(int)$fcm->insert_id('shipid','ship',
	  "shipzid=$zoneid and shiplid='$newlid' and shipdescr='$shipdescr'");

    // duplicate the subzone ship table entries
    // get the entry for this ship profile in the default language
	if( $active ){	// if this ship profile is active
    	$fcta->query("select * from subzship ".
			"where shipid=$shipid and shiplid=$zonedeflid"); 
		while( $fcta->next_record() ){
			$shipszid = (int)$fcta->f('shipszid');
			$shipdef  = (int)$fcta->f('shipdef');
			$res = $fcm->query("insert into subzship ".
          	  "(shipszid,shipid,shipdef,shiplid)".
          	  " values ".
			  "($shipszid,$new_shipid,$shipdef,$newlid)");
		}
	}

    // duplicate the price threshold entries to the new ship id
    $fcta->query("select * from shipthresh ".
	  "where shipid=$shipid"); 
	  //"where shipzid=$zoneid and shiplid=$zonedeflid"); 
    while( $fcta->next_record() ){
      $shipzid = (int)$fcta->f('shipzid');
      $shiplid = (int)$fcta->f('shiplid');
      $shipseq = (int)$fcta->f('shipseq');
      $shipamt = (double)$fcta->f('shipamt');
      $shiplo = (double)$fcta->f('shiplow');
      $shiphi = (double)$fcta->f('shiphigh');

      $fcm->query("insert into shipthresh ".
      "(shipid,shipzid,shiplid,shipseq,shipamt,shiplow,shiphigh)".
      " values ".
	  "($new_shipid,$shipzid,$newlid,$shipseq,$shipamt,$shiplo,$shiphi)");
    }

    // duplicate the weight threshold entries to the new ship id
    $fcta->query("select * from weightthresh ".
	  "where shipid=$shipid"); 
	  //"where shipzid=$zoneid and shiplid=$zonedeflid"); 
    while( $fcta->next_record() ){
      $shipzid = (int)$fcta->f('shipzid');
      $shiplid = (int)$fcta->f('shiplid');
      $shipseq = (int)$fcta->f('shipseq');
      $shipamt = (double)$fcta->f('shipamt');
      $shiplo = (double)$fcta->f('shiplow');
      $shiphi = (double)$fcta->f('shiphigh');

      $fcm->query("insert into weightthresh ".
      "(shipid,shipzid,shiplid,shipseq,shipamt,shiplow,shiphigh)".
      " values ".
	  "($new_shipid,$shipzid,$newlid,$shipseq,$shipamt,$shiplo,$shiphi)");
    }
  }
  $fct->free_result();

} elseif($act=="delete"){

  $fcm->query('select numlang from master');
  $fcm->next_record();
  $numl=(int)$fcm->f('numlang');
  $fcm->free_result();
  $numl-=1;
  $fcm->query("update master set numlang=$numl");

  $res=$fcl->query( "delete from lang where langid=$lngid");
  $fcl->query(
    "delete from country where ctryzid=$zoneid and ctrylid=$lngid");

}
if(!$res){
	$fcl->rollback();
	echo "<b>failure updating lang: $res</b><br>\n";
}else{
	$fcl->commit();
	echo "Work committed.<br>\n";
}


if($res && ($act=="update") && ($langfppromo!=$oldfppromo||$oldfppromo=="")){
//update cattable first set catact to 0   (bvo)
     $fctb->query("update cat set catact=0 where catval=$langfppromo");
     $fctb->commit();

//set the oldfppromo cat back to active if it existed
if($oldfppromo){
     $fctc->query("update cat set catact=1 where catval=$oldfppromo");
     $fctc->commit();
}
}


?>

<p>
<?php if( $act=="new" ){ ?>
You have added a new language to this zone.  When you return to the 
central maintenance page, you will immediately be prompted for a 
minimal set of support profiles for this new language.  This will 
include a new web profile, at least one new category profile, and so forth.
Please fill these in as they are presented.
<p>
Categories are language dependent, and new categories for this language
should be set up as you desire before proceeding to the product updates.
<p>
Each product that presently exists in the cart must now have a new 
set of language dependent descriptions added to it.  After the new set
of minimum profiles is created, from the central maintenance page 
select this new language, then modify each product to add its new
language profile.  As part of this, you can associate the products
with the proper new categories you have set up.
<p>
<?php } ?>

<form method=post action="index.php">
<input type=hidden name=srch value=<?php echo $srch?>>
<input type=hidden name=show value=<?php echo $show?>>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=submit value="Return to Maintenance">
</form>

<?php require('./footer.php');?>
