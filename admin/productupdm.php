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

require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );
header("Expires: 0");

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

// if $zid or $lid are found, they should be changed
// to $zoneid or $langid, respectively. Once all
// maint files are done, $zid and $lid can probably
// be eliminated.

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

//getparam('zcnt'); // :VESTIGIAL?: mag 030703 Passed from productaddm.php, but not used herein

// Note. $zid and $lid are used in this script to hold optional values
// of $zoneid and $langid used in <select> elements.
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcl = new FC_SQL; // language
$fcp = new FC_SQL; // check if product exists
$fcu = new FC_SQL; // update product
$fcw = new FC_SQL; // web
//$fcc = new FC_SQL; // :VESTIGIAL?: mag 030704 I don't see this used anywhere.
$fcz = new FC_SQL; // zone

$now   = time();
$droot = 'BITCART_PKG_PATH';

// figure out from the addzone* fields which zones must be processed
// accumulate a numeric indexed array $zins
$fcz->query("select zoneid from zone order by zoneid"); 
while ( $fcz->next_record() ) {
    $zid  = (int)$fcz->f('zoneid');
    $tzid = (int)getparam('addzone'.$zid);  //$tzid : this zone id, get the value of this zone flag
    if ( $tzid ) {          // if this box was checked
        $zins[] = $zid;     // process this zone (add this zone to array of zones to process)
    }
}
 
$z = 0;
while ( $zins[$z] ) {    // process all selected zones
    $zid = $zins[$z];
    $proderr = 0;

    // the fields are all in the form of name + zoneid
    // we recover the language independent fields below

// :COMMENT: mag 030704 Should cleaning a price be a function?


    $ssku = getparam('ssku'.$zid);
    $srch = getparam('srch'.$zid);
    $act  = getparam('act'.$zid);

    $setup      = (double)getparam('setup'.$zid);
    $setup      = ereg_replace(',','',$setup);   /* remove commas from setup */
    $setup      = ereg_replace('[\$]{1,}','',$setup);  /* remove $ from setup */
    $setup      = (double)$setup;

    $price      = (double)getparam('price'.$zid);
    $price      = ereg_replace(',','',$price);   /* remove commas from price */
    $price      = ereg_replace('[\$]{1,}','',$price);  /* remove $ from price */
    $price      = (double)$price;

    $rtlprice   = (double)getparam('rtlprice'.$zid);
    $rtlprice   = ereg_replace(',','',$rtlprice);   /* remove commas from price */
    $rtlprice   = ereg_replace('[\$]{1,}','',$rtlprice);  /* remove $ from price */
    $rtlprice   = (double)$rtlprice;

    $saleprice  = (double)getparam('saleprice'.$zid);
    $saleprice  = ereg_replace(',','',$saleprice);   /* remove commas from price */
    $saleprice  = ereg_replace('[\$]{1,}','',$saleprice);  /* remove $ from price */
    $saleprice  = (double)$saleprice;

    $prodweight = (double)getparam('prodweight'.$zid);
    $prodweight = ereg_replace(',','',$prodweight);   /* remove commas from weight */
    $prodweight = ereg_replace('[\$]{1,}','',$prodweight);  /* remove $ from weight */
    $prodweight = (double)$prodweight;

    $prodvat = (int)getparam('prodvat'.$zid);
    $ordmax  = (int)getparam('ordmax'.$zid);
    $invqty  = (int)getparam('invqty'.$zid);
    $useinv  = (int)getparam('useinv'.$zid);
    $useesd  = (int)getparam('useesd'.$zid);
    $prodseq = (int)getparam('prodseq'.$zid);

// :COMMENT: mag 030704 Should we put in some kind of date validation?

    $psm = (int)getparam('psm'.$zid);
    $psd = (int)getparam('psd'.$zid);
    $psy = (int)getparam('psy'.$zid);

    if ($psm && $psd && $psy) {
        $psdate = mktime(0,0,0,$psm,$psd,$psy);
    } else {
        $psdate = 0;
    }

    $pem = (int)getparam('pem'.$zid);
    $ped = (int)getparam('ped'.$zid);
    $pey = (int)getparam('pey'.$zid);

    if ($pem && $ped && $pey) {
        $pedate = mktime(23,59,59,$pem,$ped,$pey);
    } else {
        $pedate = 0;
    }

    $ssm = (int)getparam('ssm'.$zid);
    $ssd = (int)getparam('ssd'.$zid);
    $ssy = (int)getparam('ssy'.$zid);

    if ($ssm && $ssd && $ssy) {
        $ssdate = mktime(0,0,0,$ssm,$ssd,$ssy);
    } else {
        $ssdate = 0;
    }

    $sem = (int)getparam('sem'.$zid);
    $sed = (int)getparam('sed'.$zid);
    $sey = (int)getparam('sey'.$zid);

    if ($sem && $sed && $sey) {
        $sedate = mktime(23,59,59,$sem,$sed,$sey);
    } else {
        $sedate = 0;
    }

    $noship  = (int)getparam('noship'.$zid);
    $notax   = (int)getparam('notax'.$zid);
    $novat   = (int)getparam('novat'.$zid);
 
// $useesd  = $useesd .$zid; // :COMMENT: mag 030703 This appears twice. See line 120.
// $useesd  = (int)${$useesd }; // :COMMENT: mag 030703 This appears twice. See line 120.

 // build up flag1
    $flag1 = 0;
    if ($noship) {
        $flag1 |= (int)$flag_noship;
    }
    if ($notax) {
        $flag1 |= (int)$flag_notax;
    }
    if ($novat){
        $flag1 |= (int)$flag_novat;
    }
    if ($useesd) {
        $flag1 |= (int)$flag_useesd;
    }

    $prodsku = (int)(getparam('sku'.$zid));

// :VESTIGIAL?: mag 030704 $oldsku isn't posted from productaddm.php, the only page
// that calls productupdm.php. There are a couple of references in some conditionals
// and SQL queries. Right now I'm commenting it all out.

// $oldsku  = $oldsku.$zid;
// $oldsku  = $oldsku.$zid; //where is this from?
// $oldsku  = (int)${$oldsku};

    $prodcharge = (int)getparam('prodcharge'.$zid);
    $prodmcode  = (int)getparam('prodmcode'.$zid);
    $prodisbn   = (int)getparam('prodisbn'.$zid);

    $fcp->query("select prodsku from prod ".
        "where prodsku='$prodsku' and prodzid=$zid"); 
    if ( $fcp->next_record() ) {
        echo "<p>A product with SKU# $prodsku already exists.<br \>Please use ".
            "\"Product Maintenance\" to change.</p>\n";
        $proderr = 1;
    } else {
        $fcu->query("insert into prod ".
            "(prodzid,prodsku,prodsetup,prodprice,prodinvqty,produseinvq,prodsalebeg,".
            "prodsaleend,prodsaleprice,prodrtlprice,prodseq,prodordmax,prodflag1,".
    	    "prodcharge,prodmcode,prodstart,prodstop,prodisbn,prodweight,prodvat) ".
            "values ($zid,'$prodsku',$setup,$price,$invqty,$useinv,$ssdate,".
	        "$sedate,$saleprice,$rtlprice,$prodseq,$ordmax,$flag1,$prodcharge,'$prodmcode',".
            "$psdate,$pedate,'$prodisbn',$prodweight,$prodvat)");  
    }
    $fcp->free_result();

 // insert into the new products table
    $fcp->query("select count(*) as cnt from nprod ".
            "where nprodsku='$prodsku' and nzid=$zid"); 
    $fcp->next_record();
    $ncnt = (int)$fcp->f('cnt');
    $fcp->free_result();
    if ( !$ncnt ) {
        $fcw->query("select webdaysinnew from web ".
            "where webzid=$zid and weblid=$langid"); 
        $fcw->next_record();
        $days = (int)$fcw->f("webdaysinnew");
        $fcw->free_result();
        if ( $days ) { // only if there is a defined limit
            $nend = $now + (86400 * $days);
            $fcp->query("insert into nprod ".
                "(nprodsku,nstart,nend,nzid) values ('$prodsku',$now,$nend,$zid)");
        }
    }

 // INSERT PRODUCT LANGUAGE TABLE
    $i = 0;
    if ( !$proderr ) {
    
      // get the count of language table entries in this zone
        $fcl->query("select count(*) as cnt from lang where langzid=$zid");
        $fcl->next_record();
        $lt = (int)$fcl->f('cnt');
        $fcl->free_result();
    
        while ( $i<$lt ) {
            $tname    = getparam('prodname'.$i);
            $tdescr   = getparam('descr'.$i);
            $tsdescr  = getparam('sdescr'.$i);
            $tkeyword = getparam('keyword'.$i);
            $toffer   = getparam('prodoffer'.$i);
            $tpic     = getparam('pic'.$i);
            $ttpic    = getparam('tpic'.$i);
            $tbanr    = getparam('banr'.$i);
            $taudio   = getparam('audio'.$i);
            $tvideo   = getparam('video'.$i);
            $tsplash  = getparam('splash'.$i);
            $dload    = getparam('proddload'.$i);
            if ( $act == 'insert' ) {   // retain prodlid passed in by update
                $prodlid = getparam('prodlid'.$zid.$i);
            }
            $prodauth     = getparam('prodauth'.$i);
            $prodauthurl  = getparam('prodauthurl'.$i);
            $prodleadtime = getparam('prodleadtime'.$i);
        
            $tkeyword = ereg_replace('\r','',$tkeyword);
            $tkeyword = ereg_replace('\n','',$tkeyword);
    
            if ($tpic != '') {
                $imgs = getimagesize(imagepath($tpic).$tpic);
                if ($imgs[0] == 0) {
                    echo "<b>The image file $tpic was not found.</b><p>\n";
                    $tpicw = 0;
                    $tpich = 0;
                } else {
                    $tpicw = $imgs[0];
                $tpich = $imgs[1];
                }
            } else {
                $tpic  = '';
                $tpicw = 0;
                $tpich = 0;
            }
            if ( $ttpic != '' ) {
                $imgs = getimagesize(imagepath($ttpic).$ttpic);
                if ($imgs[0] == 0) {
                    echo "<b>The image file $ttpic was not found.</b><p>\n";
                    $ttpicw = 0;
                    $ttpich = 0;
                } else {
                    $ttpicw = $imgs[0];
                    $ttpich = $imgs[1];
                }
            } else {
                $ttpic  = '';
                $ttpicw = 0;
                $ttpich = 0;
            }
            if ($tbanr != '') {
                $imgs = getimagesize(imagepath($tbanr).$tbanr);
                if ($imgs[0] == 0) {
                    echo "<b>The image file $tbanr was not found.</b><p>\n";
                    $tbanrw = 0;
                    $tbanrh = 0;
                } else {
                    $tbanrw = $imgs[0];
                    $tbanrh = $imgs[1];
                }
            } else {
                $tbanr  = '';
                $tbanrw = 0;
                $tbanrh = 0;
            }
    
            $fcp->query("select prodlsku from prodlang where prodlsku='$prodsku' ".
                "and prodlzid=$zid and prodlid=$prodlid");
            $pirc = (int)$fcp->next_record();
            $fcp->free_result();
            if ($act == 'insert' && $pirc == 0) {
                $fcp->query("insert into prodlang ".
                    "(prodlid,prodlzid,prodlsku,proddescr,prodpic,prodpich,prodpicw,".
                    "prodtpic,prodtpich,prodtpicw,prodbanr,prodbanrh,prodbanrw,".
                    "prodkeywords,prodname,".
                    "prodaudio,prodvideo,prodsplash,prodsdescr,prodoffer,".
                    "proddload,prodauth,prodauthurl,prodleadtime) ".
                    "values ($prodlid,$zid,'$prodsku','$tdescr','$tpic',$tpich,$tpicw,".
                    "'$ttpic',$ttpich,$ttpicw,'$tbanr',$tbanrh,$tbanrw,".
                    "'$tkeyword','$tname',".
                    "'$taudio','$tvideo','$tsplash','$tsdescr','$toffer','$dload',".
                    "'$prodauth','$prodauthurl','$prodleadtime')");  
    
            } elseif ( $act == 'insert' && $pirc > 0 ) {
                $fcp->query("update prodlang ".
                    "set prodlsku='$prodsku', ".
                    "prodpic='$tpic',       prodpich=$tpich,     prodpicw=$tpicw, ".
                    "prodtpic='$ttpic',     prodtpich=$ttpich,   prodtpicw=$ttpicw, ".
                    "prodbanr='$tbanr',     prodbanrh=$tbanrh,   prodbanrw=$tbanrw, ".
                    "prodsplash='$tsplash', prodaudio='$taudio', prodvideo='$tvideo', ".
                    "proddescr='$tdescr',   prodkeywords='$tkeyword', ".
                    "prodname='$tname', ".
                    "prodauth='$prodauth', ".
                    "prodauthurl='$prodauthurl', ".
                    "prodsdescr='$tsdescr', prodoffer='$toffer', proddload='$dload', ".
                    "prodleadtime='$prodleadtime' ".
                    "where prodlsku='$oldsku' and prodlzid=$zid and prodlid=$prodlid"); 
    
            }
    
        // process the categories to put this product into
            $k = 0;
    // Here comes vestigial $oldsku!    
            $cloop = (int)getparam('cloop'); // hidden passed from productadd
            while ( $k < $cloop ) {
                $pc  = (int)getparam('pc'.$zid.$k.$i);
                $psq = (int)getparam('pcatseq'.$zid.$k.$i);
                if ($pc) {
                   if ($act == 'update') {
                        $fcp->query("select pcatsku from prodcat ".
                            "where pcatsku='$oldsku' and pcatzid=$zid and pcatval=$pc"); 
                    } else {
                        $fcp->query("select pcatsku from prodcat ".
                        "where pcatsku='$prodsku' and pcatzid=$zid and pcatval=$pc"); 
      
                    }
                    if ( $fcp->next_record() ) {
                        $fcp->free_result();
                    } else {
                        if ($act == 'insert') {
                            $fcp->query("insert into prodcat (pcatval,pcatsku,".
                                "pcatzid,pcatseq) values ($pc,'$prodsku',$zid,$psq)");
                        } elseif ( $act == 'update' ) {
                            $fcp->query("update prodcat set pcatval=$pc,pcatsku='$prodsku',".
                                "pcatzid=$zid,pcatseq=$psq ".
                                "where pcatsku='$oldsku' and pcatzid=$zid");
                        }
                    }
                }
                $k++;
            }
            $i++;
        } // end of language process loop
    
        $fcp->commit();
        echo "Work committed to zone $zid.<br />\n";
    } // if ( !$proderr ) 
    $z++;
} // end of zone process loop
$fcz->free_result();
?>

<p>

<form method="post" action="productaddm.php">
<input type="hidden" name="ssku" value="<?php echo $ssku?>">
<input type="hidden" name="srch" value="<?php echo $srch?>">
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>">
<input type="hidden" name="langid" value="<?php echo $langid?>">
<input type="submit" value="Return to Add Product" onclick="closehelp();">
</form>

<?php require('./footer.php'); ?>
