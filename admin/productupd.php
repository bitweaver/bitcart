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
require('./admin.php');
require('./header.php');
require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

// if $zid or $lid are found, they should be changed
// to $zoneid or $langid, respectively. Once all
// maint files are done, $zid and $lid can probably
// be eliminated.
// Note. $lid is used in this script in while-loop, though
// not as a global variable.

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$ssku   = getparam('ssku');
$srch   = getparam('srch');

$sku    = getparam('sku');
$act    = getparam('act');
$oldsku = getparam('oldsku');

$prodisbn = getparam('prodisbn');
$prodmcode = getparam('prodmcode');

$setup      = (double)getparam('setup');
$price      = (double)getparam('price');
$rtlprice   = (double)getparam('rtlprice');
$saleprice  = (double)getparam('saleprice');
$stsaleprice = (double)getparam('stsaleprice');
$prodweight = (double)getparam('prodweight');
$prodwidth  = (double)getparam('prodwidth');
$prodheight = (double)getparam('prodheight');
$prodlength = (double)getparam('prodlength');

$novat       = (int)getparam('novat');
$notax       = (int)getparam('notax');
$prodcharge  = (int)getparam('prodcharge');
$noship      = (int)getparam('noship');
$ordmax      = (int)getparam('ordmax');
$invqty      = (int)getparam('invqty');
$useinv      = (int)getparam('useinv');
$useesd      = (int)getparam('useesd');
$genesd      = (int)getparam('genesd');
$persvc      = (int)getparam('persvc');
$prodseq     = (int)getparam('prodseq');
$prodpackage = (int)getparam('prodpackage');

$psy = (int)getparam('psy');
$psm = (int)getparam('psm');
$psd = (int)getparam('psd');
$pey = (int)getparam('pey');
$pem = (int)getparam('pem');
$ped = (int)getparam('ped');

$ssy = (int)getparam('ssy');
$ssm = (int)getparam('ssm');
$ssd = (int)getparam('ssd');
$sey = (int)getparam('sey');
$sem = (int)getparam('sem');
$sed = (int)getparam('sed');

$stssy = (int)getparam('stssy');
$stssm = (int)getparam('stssm');
$stssd = (int)getparam('stssd');
$stsey = (int)getparam('stsey');
$stsem = (int)getparam('stsem');
$stsed = (int)getparam('stsed');

$cat_count = (int)getparam('cat_count');
$add_lang_only = (int)getparam('add_lang_only');

// ==========  end of variable loading  ==========


$fcl = new FC_SQL;
$fcp = new FC_SQL;
$fcu = new FC_SQL;
$fcw = new FC_SQL;
$fcc = new FC_SQL;

if ( !$zoneid || !$langid ) {
    echo 'A zone or language ID was not selected.<br />';
    echo 'Please click the &quot;Back&quot; button on your browser '.
        'and select a zone.  Thank you.';
    exit;
}

if (!$sku) {
    echo 'A Product SKU was not added.<br />';
    echo 'Please click the &quot;Back&quot; button on your '.
        'browser and insert a Product SKU.  Thank you.';
    exit;
}

$droot = 'BITCART_PKG_PATH';
$setup      = ereg_replace(',','',$setup);            /* remove commas from setup */
$setup      = ereg_replace('[\$]{1,}','',$setup);     /* remove $ from setup */
$price      = ereg_replace(',','',$price);            /* remove commas from price */
$price      = ereg_replace('[\$]{1,}','',$price);     /* remove $ from price */
$rtlprice   = ereg_replace(',','',$rtlprice);         /* remove commas from price */
$rtlprice   = ereg_replace('[\$]{1,}','',$rtlprice);  /* remove $ from price */
$saleprice  = ereg_replace(',','',$saleprice);        /* remove commas from price */
$saleprice  = ereg_replace('[\$]{1,}','',$saleprice); /* remove $ from price */
$stsaleprice  = ereg_replace(',','',$stsaleprice);        /* remove commas from price */
$stsaleprice  = ereg_replace('[\$]{1,}','',$stsaleprice); /* remove $ from price */
$prodweight = ereg_replace(',','',$prodweight);       /* remove commas from weight */
$prodweight = ereg_replace('[\$]{1,}','',$prodweight);/* remove $ from weight */

$setup      = (double)$setup;       // redundant
$price      = (double)$price;       // redundant
$rtlprice   = (double)$rtlprice;    // redundant
$saleprice  = (double)$saleprice;   // redundant
$stsaleprice = (double)$stsaleprice;   // redundant
$prodweight = (double)$prodweight;  // redundant
$prodwidth  = (double)$prodwidth;   // redundant
$prodheight = (double)$prodheight;  // redundant
$prodlength = (double)$prodlength;  // redundant

$novat       = (int)$novat;         // redundant
$notax       = (int)$notax;         // redundant
$noship      = (int)$noship;        // redundant
$ordmax      = (int)$ordmax;        // redundant
$invqty      = (int)$invqty;        // redundant
$useinv      = (int)$useinv;        // redundant
$useesd      = (int)$useesd;        // redundant
$genesd      = (int)$genesd;        // redundant
$persvc      = (int)$persvc;        // redundant
$prodseq     = (int)$prodseq;       // redundant
$prodpackage = (int)$prodpackage;   // redundant

if ( $psm && $psd && $psy ) {
	$psdate = mktime(0,0,0,$psm,$psd,$psy);
} else {
	$psdate = 0;
}
if ( $pem && $ped && $pey ) {
	$pedate = mktime(23,59,59,$pem,$ped,$pey);
} else {
	$pedate = 0;
}

if ( $ssm && $ssd && $ssy ) {
	$ssdate = mktime(0,0,0,$ssm,$ssd,$ssy);
} else {
	$ssdate = 0;
}
if ( $sem && $sed && $sey ) {
	$sedate = mktime(23,59,59,$sem,$sed,$sey);
} else {
	$sedate = 0;
}

if ( $stssm && $stssd && $stssy ) {
	$stssdate = mktime(0,0,0,$stssm,$stssd,$stssy);
} else {
	$stssdate = 0;
}
if ( $stsem && $stsed && $stsey ) {
	$stsedate = mktime(23,59,59,$stsem,$stsed,$stsey);
} else {
	$stsedate = 0;
}

// build up flag1
$flag1 = 0;
if ($noship) {
  $flag1 |= (int)$flag_noship;
}
if ($notax) {
  $flag1 |= (int)$flag_notax;
}
if ($novat) {
  $flag1 |= (int)$flag_novat;
}
if ($useesd) {
  $flag1 |= (int)$flag_useesd;
}
if ($genesd) {
  $flag1 |= (int)$flag_genesd;
}
if ($persvc) {
  $flag1 |= (int)$flag_persvc;
}
if ($prodpackage) {
  $flag1 |= (int)$flag_package;
}

/*
here we fetch prodflag1 from the database to add anything not
used in productmod.php to flag1 again otherwise some of the 
current content of any other flag will be removed
we only need this query if the product database is updated

any other prodflag1 flag not echoed into productmod should be added here
to keep prodflag1 complete.

(bvo)
*/
if ($act=='update'){
$fccb = new FC_SQL;
$fccb -> query("select prodflag1 from prod where prodsku='$oldsku' ".
              "and prodzid=$zoneid");
$fccb  -> next_record();
$prodfl1=(int)$fccb->f("prodflag1");
$fccb -> free_result();
if ($prodfl1 & $flag_hasrel) { $flag1 |= (int)$flag_hasrel;}
}


$proderr = 0;
if ($act == 'insert') {

    /* get the count of language table entries */
    $fcl->query("select count(*) as cnt from lang where langzid=$zoneid");
    $fcl->next_record();
    $lt=(int)$fcl->f('cnt');
    $fcl->free_result();

    $fcp->query("select prodsku from prod ".
	    "where prodsku='$sku' and prodzid=$zoneid"); 
    if( $fcp->next_record() ){
        echo "<p>A product with SKU# $sku already exists.<br />Please use ".
            "\"Product Maintenance\" to change.</p>\n";
        $proderr = 1;
    } else  {
  	    $fcu->query("insert into prod ".
	        "(prodzid,prodsku,prodsetup,prodprice,prodinvqty,produseinvq,prodsalebeg,".
	        "prodsaleend,prodsaleprice,prodstsalebeg,prodstsaleend,prodstsaleprice,".
			"prodrtlprice,prodseq,prodordmax,prodflag1,".
	        "prodcharge,prodmcode,prodstart,prodstop,prodisbn,prodweight,".
	        "prodwidth,prodheight,prodlength) ".
	        "values ($zoneid,'$sku',$setup,$price,$invqty,$useinv,$ssdate,".
        	"$sedate,$saleprice,$stssdate,$stsedate,$stsaleprice,$rtlprice,".
			"$prodseq,$ordmax,$flag1,$prodcharge,".
	        "'$prodmcode',$psdate,$pedate,'$prodisbn',$prodweight,".
	        "$prodwidth,$prodheight,$prodlength)");  
    }
    $fcp->free_result();

    $fcp->query("select nprodsku from nprod ".
        "where nprodsku='$sku' and nzid=$zoneid"); 
    if ( $fcp->next_record() ) {
        echo 'Product already listed in New Products<br />';
    } else {
        $fcw->query("select webdaysinnew from web ".
            "where webzid=$zoneid and weblid=$langid"); 
        if ( !$fcw->next_record() ) {
            echo '<p>Internal error: web table not found for zone: '.$zoneid.
                'and language: '.$langid.'.  Contact FishNet support '.
                'at <a href="mailto:support@fni.com">support@fni.com</a>.</p>';
        } else {
	        $now  = time();
	        $days = $fcw->f('webdaysinnew');
	        $nend = $now + (86400 * $days);
	        $fcp->query("insert into nprod ".
		        "(nprodsku,nstart,nend,nzid) values ('$sku',$now,$nend,$zoneid)");
	    }
    $fcw->free_result();
    }
    $fcp->free_result();

} elseif ( $act == 'update' ) {

  // update just one product
  $lt = 1;

  if( !$add_lang_only ){

    $fcp->query("update prod ".
        "set prodsku='$sku',".
		"prodsetup=$setup,".
		"prodprice=$price,".
		"prodinvqty=$invqty,".
        "produseinvq=$useinv,".
		"prodsalebeg=$ssdate,".
		"prodsaleend=$sedate, ".
        "prodsaleprice=$saleprice,".
		"prodstsalebeg=$stssdate,".
		"prodstsaleend=$stsedate,".
		"prodstsaleprice=$stsaleprice,".
		"prodseq=$prodseq,".
		"prodrtlprice=$rtlprice,".
        "prodordmax=$ordmax,".
		"prodflag1=$flag1,".
		"prodcharge=$prodcharge,".
		"prodmcode='$prodmcode',".
        "prodstart=$psdate,".
		"prodstop=$pedate,".
		"prodisbn='$prodisbn',".
        "prodweight=$prodweight,".
        "prodwidth=$prodwidth,".
		"prodheight=$prodheight,".
		"prodlength=$prodlength ".
        "where prodsku='$oldsku' and prodzid=$zoneid"); 

	// modify this SKU in the product/category database if needed

    if ( $sku != $oldsku ) {
	    $res = $fcp->query("update prodcat ".
		    "set pcatsku='$sku' where pcatsku='$oldsku' and pcatzid=$zoneid"); 
    }

  } // !$add_lang_only

} elseif ( $act == 'copy') {

 // copy a sku into a new sku

} elseif ( $act == 'delete' ) {

    $fcp->query("delete from prod where prodsku='$sku' ".
                "and prodzid=$zoneid"); 
    $fcp->query("delete from prodlang where prodlsku='$sku' ".
                "and prodlzid=$zoneid"); 

    /* Delete this product from product/category and new items database */

    $fcp->query("delete from prodcat ".
 	    "where pcatsku='$sku' and pcatzid=$zoneid"); 

    $fcp->query("delete from nprod where nprodsku='$sku' ".
                "and nzid=$zoneid"); 

    // categories can contain SKUs; see if this is referenced

    $fcc->query("select catval from cat where catsku='$sku' ".
                "and catzid=$zoneid"); 
    while ( $fcc->next_record() ) {
        $fcu->query("update cat set catsku='' where catval=$val ".
                "and catzid=$zoneid"); 
    }
    $fcc->free_result();

    // delete from prodrel table
    $fca = new FC_SQL;
    $fca->query("delete from prodrel where relzone=$zoneid and ".
                "relsku='$sku' or relprod='$sku'");

    // delete from prodopt table
    $fcb = new FC_SQL;
    $fcb->query("delete from prodopt where poptsku='$sku' and ".
                "poptzid=$zoneid");

    $fca->free_result();
    $fcb->free_result();

}

/* PRODUCT LANGUAGE TABLES */
$i = 0;
if ( !$proderr ) {
    while ($i < $lt) {
        $tname        = getparam('prodname'.$i);
        $tdescr       = getparam('descr'.$i);
        $tsdescr      = getparam('sdescr'.$i);
        $tinstallinst = getparam('installinst'.$i);
        $tkeyword     = getparam('keyword'.$i);
        $toffer       = getparam('prodoffer'.$i);
        $tpic         = getparam('pic'.$i);
        $ttpic        = getparam('tpic'.$i);
        $tbanr        = getparam('banr'.$i);
        $taudio       = getparam('audio'.$i);
        $tvideo       = getparam('video'.$i);
        $tpersvc      = getparam('prodpersvc'.$i);
        $tsplash      = getparam('splash'.$i);
        $dload        = getparam('proddload'.$i);
        if ( $act == 'insert' || $act == 'update' ) {
	    	// retain prodlid passed in by update
            $prodlid  = getparam('prodlid'.$i);
        }
        $prodauth     = getparam('prodauth'.$i);
        $prodauthurl  = getparam('prodauthurl'.$i);
        $prodleadtime = getparam('prodleadtime'.$i);

		$tkeyword = ereg_replace("\r",'',$tkeyword);
		$tkeyword = ereg_replace("\n",'',$tkeyword);

	    if ($tpic != '') {
	        $imgs = getimagesize(imagepath($tpic).$tpic);
            if ($imgs[0] == 0){
                echo "<p><b>The image file $tpic was not found.</b></p>\n";
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
	    if ($ttpic != '') {
	        $imgs = getimagesize(imagepath($ttpic).$ttpic);
            if ($imgs[0] == 0) {
                echo "<p><b>The image file $ttpic was not found.</b></p>\n";
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
                echo "<p><b>The image file $tbanr was not found.</b></p>\n";
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

	    $fcp->query(
			"select count(*) as cnt from prodlang where prodlsku='$sku' ".
		    "and prodlzid=$zoneid and prodlid=$prodlid");
	    $fcp->next_record();
	    $pirc = (int)$fcp->f('cnt');
	    $fcp->free_result();
	    if ($pirc == 0) {
			// insert in all cases where the record does not exist

		    $fcp->query("insert into prodlang ".
		        "(prodlid,prodlzid,prodlsku,proddescr,installinst,".
				"prodpic,prodpich,prodpicw,".
		        "prodtpic,prodtpich,prodtpicw,prodbanr,prodbanrh,prodbanrw,".
		        "prodkeywords,prodname,".
		        "prodaudio,prodvideo,prodsplash,prodsdescr,prodoffer,".
		        "proddload,prodauth,prodauthurl,prodleadtime,prodpersvc) ".
		        "values ($prodlid,$zoneid,'$sku','$tdescr','$tinstallinst',".
				"'$tpic',$tpich,$tpicw,".
		        "'$ttpic',$ttpich,$ttpicw,'$tbanr',$tbanrh,$tbanrw,".
		        "'$tkeyword','$tname',".
		        "'$taudio','$tvideo','$tsplash','$tsdescr','$toffer','$dload',".
		        "'$prodauth','$prodauthurl','$prodleadtime','$tpersvc')");  

	    } elseif ($act == 'update' || ($act == 'insert' && $pirc > 0) ) {

		    $fcp->query("update prodlang ".
		        "set prodlsku='$sku', ".
		        "prodpic='$tpic',".
				"prodpich=$tpich,".
				"prodpicw=$tpicw,".
		        "prodtpic='$ttpic',".
				"prodtpich=$ttpich,".
				"prodtpicw=$ttpicw,".
		        "prodbanr='$tbanr',".
				"prodbanrh=$tbanrh,".
				"prodbanrw=$tbanrw,".
		        "prodsplash='$tsplash',".
				"prodaudio='$taudio',".
				"prodvideo='$tvideo',".
		        "proddescr='$tdescr',".
				"prodkeywords='$tkeyword',".
				"installinst='$tinstallinst',".
		        "prodname='$tname',".
		        "prodauth='$prodauth',".
		        "prodauthurl='$prodauthurl',".
		        "prodsdescr='$tsdescr',".
				"prodoffer='$toffer',".
				"proddload='$dload', ".
		        "prodleadtime='$prodleadtime',".
		        "prodpersvc='$tpersvc' ".
		        "where prodlsku='$oldsku' and prodlzid=$zoneid ".
				" and prodlid=$prodlid"); 

	    }

	    $k = 0;
	    while ($k < $cat_count) {
            $pc  = (int)getparam('pc'.$k.$i);
            $psq = (int)getparam('pcatseq'.$k.$i);
	        if($pc != 0){
	            if ($act == 'update'){
	                $fcp->query("select pcatsku from prodcat ".
	                    "where pcatsku='$oldsku' and pcatzid=$zoneid ".
						"and pcatval=$pc"); 
	            }else{
	                $fcp->query("select pcatsku from prodcat ".
	                    "where pcatsku='$sku' and pcatzid=$zoneid ".
						"and pcatval=$pc"); 
	            }
	            if( $fcp->next_record() ){
                    echo 'Product/category association for '.$sku.'/'.$pc.
						 ' already exists.<br />';
	                $fcp->free_result();
	            } else {
	                if( $act == 'insert' ||
						$act == 'update' && $add_lang_only ){
	                    $fcp->query("insert into prodcat ".
							"(pcatval,pcatsku,pcatzid,pcatseq)".
							" values ".
							"($pc,'$sku',$zoneid,$psq)");
	                }elseif( $act == 'update' && !$add_lang_only ){
	                    $fcp->query("update prodcat set ".
							"pcatval=$pc,pcatsku='$sku',".
		                    "pcatzid=$zoneid,pcatseq=$psq ".
	                        "where pcatsku='$oldsku' and pcatzid=$zoneid");
	  	            }
	            }
	        } // if ($pc != 0) 
	        $k++;
	    } // while ($k < 3) 
	    $i++;
    } // while ($i < $lt) 

    $fcp->commit();
    echo "Work committed.<br />\n";
} // if ( !$proderr ) ?>

<p>

<?php if ($act == 'insert' || $act == 'copy') {?>
 <form method=post action="productadd.php">
<?php } else {?>
 <form method=post action="productndx.php">
<?php }?>
<input type="hidden" name="ssku" value="<?php echo $ssku?>">
<input type="hidden" name="srch" value="<?php echo $srch?>">
<input type="hidden" name="show" value="<?php echo $show?>">
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>">
<input type="hidden" name="langid" value="<?php echo $langid?>">

<?php if ($act == 'insert') {?>
<input type="submit" value="Return to Add Product"
 onclick="closehelp();">
<?php } else {?>
<input type="submit" value="Return to Product Maintenance"
 onclick="closehelp();">
<?php }?>
</form>

<?php require('./footer.php');?>
