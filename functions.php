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

NB: this library passes global variables between subroutines;
	yes, it is ugly, but it is about the only way to avoid
	massive input parameter strings and large returned arrays.

ONLY FUNCTIONS AND GLOBAL VARIABLES SHOULD BE DEFINED IN THIS FILE.
*/

require_once( BITCART_PKG_PATH.'bitcart_header_inc.php' );

$functions_inc=1;

// global variables
$instid = '';

// force magic quoting off
set_magic_quotes_runtime(0);

// retrieve the requested server variable

function getserver( $name ){
	$param = '';
	$curver = (int)str_replace('.', '', phpversion());
	if( $curver >= 410 ){		// superglobals available from ver. 4.1.0
		$param = $_SERVER["$name"];
	}else{						// superglobals aren't available
		global $HTTP_COOKIE_VARS;
		$param = $HTTP_COOKIE_VARS["$name"];
	}
	return( addslashes($param) );
}

// retrieve the requested cookie

function getcookie( $name ){
	$param = '';
	$curver = (int)str_replace('.', '', phpversion());
	if( !empty( $_COOKIE[$name] ) ) {
		$param = $_COOKIE[$name];
	}
	return( addslashes($param) );
}

// retrieve the requested POST/GET parameter passed to a page

function getparam( $name ){
	$param = '';
	$curver = (int)str_replace('.', '', phpversion());
	if( $curver >= 410 ){		// superglobals available from ver. 4.1.0
		if( @$_POST["$name"] ){	// POST before GET
			$param = $_POST["$name"];
		}elseif( @$_GET["$name"] ){
			$param = $_GET["$name"];
		}
	}else{						// superglobals aren't available
		global $HTTP_POST_VARS, $HTTP_GET_VARS;
		if( @$HTTP_POST_VARS["$name"] ){
			$param = $HTTP_POST_VARS["$name"];
		}elseif( @$HTTP_GET_VARS["$name"] ){
			$param = $HTTP_GET_VARS["$name"];
		}
	}
	if (is_array($param)) {
	    foreach($param as $element) {$element = addslashes($element);}
	} else {
	    $param = addslashes($param);
	}
	return($param);
}

// round a floating point number to 2 decimals
function rnd ($n) {
	return round($n,2);
}

// re-enterable function to fetch product options one by one
// this function must be called at least once to zero out option totals
// csku: composite SKU after options applied
// global $poptprice, $poptsetup, $poptqty, $poptname, $popttotal, $poptsettot
// global $poptextension is set if $poptqty, is popt price * popt qty
// NB: zeroes and recalculates $poptsettot, $popttotal

function get_prodopts ( $csku ) {
	global $cartid, $poptprice, $poptsetup, $poptqty, $poptname;
	global $popttotal, $poptsettot, $poptflag1, $flag_poptprcrel;
	global $poptextension;
	static $prodoptid, $fpo, $fps, $ccnt=-1;
	$now=time();

	if($ccnt==-1){		// first pass
		$poptsettot=0.0;
		$popttotal=0.0;
		$fpo = new FC_SQL;
		$fps = new FC_SQL;
		// get the number of product options
		$fpo->query("select count(*) as ccnt from olineopt ".
					"where orderid='$cartid' and compsku='$csku'");
		$fpo->next_record();
		$ccnt=$fpo->f("ccnt");
	 	$fpo->free_result();
		// now get the product options
		$fpo->query("select poptid,qty from olineopt ".
					"where orderid='$cartid' and compsku='$csku'");
	}elseif($ccnt==0){	// final pass
		$fpo->free_result();
		$ccnt=-1;
		return 0;
	}

	$fpo->next_record();
	$prodoptid=(int)$fpo->f("poptid");
	$poptqty=(int)$fpo->f("qty");

	$fps->query(
	 "select poptname,poptskumod,poptskusub,poptsetup,poptprice,poptflag1,".
	 "poptssalebeg,poptssaleend,poptssaleprice,poptsalebeg,poptsaleend,poptsaleprice ".
	 "from prodopt where poptid=$prodoptid");
	if( $fps->next_record() ){
		$poptname =$fps->f("poptname");
		$poptflag1=$fps->f("poptflag1");
   		if( $fps->f("poptssalebeg")<$now && $now<$fps->f("poptssaleend") ){
		$poptsetup=(double)$fps->f("poptssaleprice");
		}else{
		$poptsetup=(double)$fps->f("poptsetup");
		}
   		if( $fps->f("poptsalebeg")<$now && $now<$fps->f("poptsaleend") ){
		$poptprice=(double)$fps->f("poptsaleprice");
		}else{
		$poptprice=(double)$fps->f("poptprice");
		}
	}else{
		$poptname ='';
		$poptflag1=0;
		$poptprice=0;
		$poptsetup=0;
	}
	$fps->free_result();

	if( $poptqty ){
		$poptextension = rnd($poptprice * $poptqty);
	}else{
		$poptextension = (double)$poptprice;
	}
	// Accumulate composite option price, setup total
	$poptsettot=rnd($poptsettot+$poptsetup);
	if( $poptflag1 & $flag_poptprcrel ){
		$popttotal+=$poptextension;
	}else{
		$popttotal =$poptextension;
	}

	return $ccnt--;
}


// calculate the price with options for a single product
// NB: assumes that $popttotal, $poptsettot have been set by get_prodopts()
// returns $prodprice, sets product base price in $prodbaseprice

function prod_price ( $sku ) {
	global $now, $zid, $prodsetup, $slprc, $stslprc, $prodsettot, $prodbaseprice;
	global $popttotal, $poptsettot, $poptflag1, $flag_poptprcrel, $flag1;

	$fpr = new FC_SQL;
	$fpr->query("select prodsetup,prodprice,prodsaleprice,prodsalebeg,".
		"prodsaleend,prodstsalebeg,prodstsaleend,prodstsaleprice,".
		"prodflag1 from prod where prodzid=$zid and ".
		"prodsku='$sku'");
	$fpr->next_record();
	$stslb=(int)$fpr->f("prodstsalebeg");
	$stsle=(int)$fpr->f("prodstsaleend");
	if( $stslb < $now && $now < $stsle ){
		$prodsetup=(double)$fpr->f("prodstsaleprice");
		$stslprc=1;
	}else{
		$prodsetup=(double)$fpr->f("prodsetup");
		$stslprc=0;
	}
	$slb=(int)$fpr->f("prodsalebeg");
	$sle=(int)$fpr->f("prodsaleend");
	$flag1=(int)$fpr->f("prodflag1");
	if( $slb < $now && $now < $sle ){
		$prodprice=(double)$fpr->f("prodsaleprice");
		$slprc=1;
	}else{
		$prodprice=(double)$fpr->f("prodprice");
		$slprc=0;
	}
	$fpr->free_result();
	$prodbaseprice=$prodprice;

	// if mixed absolute and relative option prices, we keep the last
	if( $popttotal || $poptsettot ){
		if( $poptflag1 & $flag_poptprcrel ){
			// option prices are relative
			$prodprice=rnd($prodprice+$popttotal);
			$prodsetup=rnd($prodsetup+$poptsettot);
		}else{
			// option prices are absolute
			$prodprice=$popttotal;
			$prodsetup=$poptsettot;
		}
	}
	return $prodprice;
}


// calculate the line item total for a single product
// NB: assumes that prod_price() has already been called

function line_total ( $qty, $prodprice ) {
	global $shipsubtotal, $taxsubtotal, $ptaxsubtotal, $prodsettot;
	global $flag1, $flag_noship, $flag_notax, $flag_persvc;
	global $first_items, $second_items;
	static $ltotal;

	$ltotal=rnd($prodprice*$qty);

	if( !($flag1 & $flag_noship) && !($flag1 & $flag_persvc) ){
		$shipsubtotal=rnd((double)$ltotal+$shipsubtotal);
		// for line item shipping
		if($qty==1){
			$first_items++;
		}
		elseif($qty>1){
			$first_items++;
			$second_items += ($qty-1);
		}
	}elseif ( empty($shipsubtotal) ){
		$shipsubtotal=0;
	}
	if( !($flag1 & $flag_notax) ){
	 	if( $flag1 & $flag_persvc ){
			$ptaxsubtotal=rnd((double)$ltotal+$ptaxsubtotal);
		}else{
			$taxsubtotal=rnd((double)$ltotal+$taxsubtotal);
		}
	}elseif ( empty($taxsubtotal) ){
		$taxsubtotal=0;
	}
	return $ltotal;
}

// calculate the coupon discount

function coupon_discount ( $couponid, $stotal, $tqty ) {
	global $now;
	$fccp = new FC_SQL;
	$fccp->query(
	 "select discount,cpnredeem from coupon ".
	 "where cpnid = '$couponid' and ".
	 "(cpnstart = 0 or (cpnstart <> 0 and $now > cpnstart)) and ".
	 "(cpnstart = 0 or (cpnstop  <> 0 and $now < cpnstop)) and ".
	 "(cpnminqty = 0 or ($tqty >= cpnminqty)) and ".
	 "(cpnminamt = 0 or ($stotal >= cpnminamt)) and ".
	 "(cpnredeem < cpnmaximum)");
	if( !$fccp->next_record() ) {
	 $cpndisc=0;
	}else{
	 $cpndisc=(double)$fccp->f("discount");
	 if( $cpndisc > 0 && $cpndisc < 1 ){
	  $cpndisc = rnd($stotal * $cpndisc);
	 }elseif( $cpndisc < 0 ){
	  $cpndisc = 0;
	 }
	 $fccp->free_result();
	}
	return $cpndisc;
}

// show errors

function showerr() {
	global $zid,$lid,$cartid,$fcw;
$mln=256;?>
<table class="text" cellpadding="0" width="580" border="0">
<tr><td align="left" valign="top" colspan="3">
 <table class="text" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr><td align="left" valign="top" colspan="3">
  <br /><br />
 <?php echo fc_text('emptysearch'); ?>
  <p>
   <a href="index.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><?php echo fc_text("back2select"); ?></a>
  </p>
  <br /><br />
  </td></tr>
 </table>
</td></tr>
<?php 
}

// do ESD

function do_esd () {
	global $cartid, $zid, $lid, $custid, $flag_useesd, $flag_genesd;

	global $billing_first, $billing_mi, $billing_last, $billing_email;
	global $download_pw, $download_user;
	$fcesd = new FC_SQL;
	$fcesn = new FC_SQL;
	$fcesp = new FC_SQL;

	$dlmax=10;				// 10 maximum downloads
	$dlmaxdays=30;			// 30 days before download expiration

	$now=time();
	$end=$now+($dlmaxdays*86400);

	// I don't think we care about compsku, but pull for possible reference
	$fcesd->query(
		"select olid,sku,compsku from oline where orderid='$cartid'"); 

	$esd_count=0;
	while( $fcesd->next_record() ){
		$olid=(int)$fcesd->f("olid");
		$sku=$fcesd->f("sku");
		$compsku=$fcesd->f("compsku");
		$fcesp->query("select prodflag1 from prod where prodzid=$zid and ".
			"prodsku='$sku'");
		$fcesp->next_record();
		$flag1=(int)$fcesp->f("prodflag1");
		$fcesp->free_result();
		$fcesp->query("select proddload from prodlang where prodlzid=$zid and ".
			"prodlid=$lid and prodlsku='$sku'");
		$fcesp->next_record();
		$proddload=$fcesp->f("proddload");
		$fcesp->free_result();
		if( $flag1 & $flag_useesd && $flag1 & $flag_genesd ){
			$esd_count++;
			// use fishcart internal ESD process
			$fcesp->query("insert into esd ( ".
				"esdoid,esdact,esdolid,esdpurchid,esddlact,esddlexp,".
				"esddlcnt,esddlmax,esdsernum,esddlfile".
				") values (".
				"'$cartid',1,$olid,$custid,$now,$end,".
				"0,$dlmax,'','$proddload')");
			$esdid=(int)$fcesp->insert_id('esdid','kmtesd','esdolid');
			$fcesn->query(
				"update oline set olesd=$esdid where olid=$olid"); 

		}elseif( $flag1 & $flag_useesd ){
			$esd_count++;
			// use external process for ESD items
			// must set $esdid for the ESD table ID upon exit
			require('./esd_external.php');
		}
	}
	$fcesd->free_result();

	if( $esd_count ){
		$cname=$billing_first.' '.$billing_mi.' '.$billing_last;

		// use the cart id as the download username
		$download_user = $cartid;

		// create a random number as a password
		// check for random number collisions; start by assuming a collision
		$i=0;
		$collision = 1;
		while ( $collision ){
			srand((double)microtime()*1000000);
			$download_pw=(int)rand()+1;
			$fcesn->query(
			"select count(*) as cnt from pw where pwpw='$download_pw'");
			$fcesn->next_record();
				// collision is the count of rows found, leave loop when 0
			$collision = (int)$fcesn->f('cnt');
			$fcesn->free_result();
			$i++;
			if( ($i % 10) == 0 ){
				global $gBitSystem;
				mail($gBitSystem->getErrorEmail(),
				" SERIAL NUMBER COLLISION LOOP",
				"Serial Number: $download_pw\nLoop count: $i\n");
				sleep(1);       // try a 1 second sleep
			}
   		}
		$fcesp->query("insert into pw ( ".
		"pwactive,pwzone,".
		"pwjan,pwfeb,pwmar,pwapr,pwmay, pwjun,".
		"pwjul,pwaug,pwsep,pwoct,pwnov,pwdec,".
		"pwexp,pwdescr,pwemail,pwuid,pwpw,pwoid".
		") values (".
		"1,$zid,".
		"0,0,0,0,0,0,".
		"0,0,0,0,0,0,".
		"$end,'$cname','$billing_email','$download_user','$download_pw',".
		"'$cartid')");
		$fcesd->commit();
	}
	return $esd_count;
}

function show_countries( $zid, $lid, $matchiso, $lang_iso ){
	$fct = new FC_SQL;
	$fct->query(
     "select ctrylangciso,ctrylangname from country,countrylang ".
	 "where ctryzid=$zid and ctrylid=$lid and ctryactive=1 and ".
	 "ctryiso=ctrylangciso and ctrylangliso='$lang_iso' order by ctryseq,ctrylangname");
	while( $fct->next_record() ){
		$iso=$fct->f('ctrylangciso');
		$name=$fct->f('ctrylangname');
		if( $matchiso == $iso ){
			$chk = ' selected';
		}else{
			$chk = '';
		}
		echo "<option value=\"$iso\"$chk>$name</option>\n";
	}
	$fct->free_result();
}

function parse_sql_file($filename) {
	// do not escape the file while reading it
	set_magic_quotes_runtime(0);
	$fcz = new FC_SQL;
	$fcz->connect();
	if (file_exists($filename)){
		$fp = fopen($filename,"r");
		while ($buffer = fgets($fp, 4096)) {
			$dres = null;
			$res = null;
			if ((strchr($buffer,'#')) && (eregi("\"#[0-9a-z]{6}\"", $buffer))) {
				// html color code, typically in inserted html
				$sql .= $buffer;
			} else {
				if(ereg("(^[ 	]*[#;/-]+)",$buffer,$res) ||
				   ereg("^\n",$buffer)){
					// comment line, skip it
					// oracle has lines with a single leading /, skip them
					continue;
				}elseif( ereg("([^#]+);[	 ]*[-]{0,2}.*",$buffer,$res) ){
					// ; terminated sql line
					// chop off double dash comments
					$sql .= $res[1];
					// echo "second: $sql<br>br>\n";
					if( ereg(" end[ 	]*$",$res[1]) ){
						// wierd hack for oracle triggers/procedures
						$sql .= ';';
					}
					$fcz->query($sql);
					unset($sql);
				}elseif(ereg("(^[^#;]+)[	 ]*[-]{0,2}[^;]*",$buffer,$res)){
					// unterminated sql line
					// chop off double dash comments
					$sql .= $res[1];
					// echo "first: $sql<br><br>\n";
				}else{
					// echo "third: $buffer<br><br>\n";
				}
			}
		}
		fclose ($fp);
	}
}
?>
