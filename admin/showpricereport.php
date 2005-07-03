<?php
 /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2001  FishNet, Inc.

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

############################################################################
             Reporting Program for Fishcart by Web Synergy
                     Change Log
2001/04/07 - Initial Public Beta Release Version 1.0
             This software has been tested on a Unix server running Apache and php 3.0.15
             Although, it should work in php 3.0.9+
             Created By Wayne T. Ethier, Web Synergy Internet Services.  Feel free to modify,
             please forward any changes to wte@websynergyinternet.com

2001/05/18 - Modified index.php, printquery.php and showpricereport.php
			 so that all files are loaded to the cart at build time.
			 Modified by Glenn Antoine eSystems Design, Inc.
			 Email - rantoine@esysdesign.com

2002/02/09 - Integrated into the FishCart source.
             Michael Brennen
############################################################################
*/

require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );
Header("Expires: 0");
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

$order = getparam('order');
$descend = (int)getparam('descend');
$chkProdBegin = (int)getparam('chkProdBegin');
$chkProdEnd =(int)getparam('chkProdEnd');
$chkSaleBegin = (int)getparam('chkSaleBegin');
$chkSaleEnd = (int)getparam('chkSaleEnd');
$chkCost = (int)getparam('chkCost');
$chkProdPrice = (int)getparam('chkProdPrice');
$chkSalePrice = (int)getparam('chkSalePrice');
$chkSetupPrice = (int)getparam('chkSetupPrice');
$chkMargin = (int)getparam('chkMargin');
$chkInvQty = (int)getparam('chkInvQty');
$chkCogs = (int)getparam('chkCogs');

// ==========  end of variable loading  ==========

require('./admin.php');

$fcp = new FC_SQL;
$fcp->query("select zonedescr from zone where zoneid='$zoneid' and zonedeflid='$langid'");
if($fcp->next_record()){
  $zonetitle = $fcp->f("zonedescr");
  } $fcp->free_result();
?>
<html>
<head>
<title><?php echo $zonetitle; ?> | Product Price Report</title>

<noscript>
Your browser has JavaScript disabled in the preferences. You will be unable
to configure your FishCart installation.
</noscript>

<script language=javascript>
<!-- hide from dumb browsers

var currfield="unselected";
var helpopen;

browserName = navigator.appName;
browserVer  = parseInt(navigator.appVersion);

if ((browserName == "Netscape" || browserName == "Internet Explorer") &&
     browserVer >= 3) {
  version = "n3";
  if ((browserName == "Internet Explorer") && browserVer >= 3) {
    iev = "ie3";
  } else {
    iev = "";
  }
}
else version = "n2";

if (version == "n3") {
  xhelpon         = new Image();
  xhelpon.src     = "sandhelp.gif";
  xhelpoff        = new Image();
  xhelpoff.src    = "sandhelpdim.gif";
}

function img_act(imgName) {
  if (version == "n3") {
    imgOn = eval(imgName+"on.src");
    document [imgName].src = imgOn;
  }
}

function img_inact(imgName) {
  if (version == "n3") {
    imgOff = eval(imgName+"off.src");
    document [imgName].src = imgOff;
  }
}

function openhelp(url) {
  helpWin=window.open(url+'#'+currfield,'Help','scrollbars,resizable,width=450,height=200');
  if (version == "n3") { helpWin.focus(); }
  helpopen=1;
}

function blurhelp() {
  if (version == "n3") { helpWin.blur(); }
}

function closehelp() {
 if ( helpopen ) {
  // helpWin.focus();
  helpWin.close();
  helpopen=0;
 }
}

function gothere(fname,url) {
  var docfunc;
  if (version == "n3") {
    docfunc='document.'+fname+'.action=\''+url+'\';';
    eval(docfunc);
    docfunc='document.'+fname+'.submit();';
    eval(docfunc);
  }
}

function helpfocus(filetag) {
  if (version == "n3") {
    currfield=filetag;
  }
}

function showConfirm(){
 if(confirm("Confirm delete by clicking OK")){return true;}return false;}

function showErase() {
 if(confirm("Confirm article text replacement by clicking OK")){return true;}return false;}

function DelExpSet(form) {
 if(!form.act[1].checked){
  form.delexp.checked=false;
 }
}

// stop hiding -->
</script>
 <script language="JavaScript">
<!--
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
<style>
.menulines{
border:1px solid white;
}

.menulines a{
text-decoration:none;
color:black;
}
</style>

<script language="JavaScript1.2">

/*
Highlight menu effect script: By Dynamicdrive.com
For full source, Terms of service, and 100s DTHML scripts
Visit http://www.dynamicdrive.com
*/

function borderize(what,color){
what.style.borderColor=color
}

function borderize_on(e){
if (document.all)
source3=event.srcElement
else if (document.getElementById)
source3=e.target
if (source3.className=="menulines"){
borderize(source3,"yellow")
}
else{
while(source3.tagName!="TABLE"){
source3=document.getElementById? source3.parentNode :

source3.parentElement
if (source3.className=="menulines")
borderize(source3,"yellow")
}
}
}

function borderize_off(e){
if (document.all)
source4=event.srcElement
else if (document.getElementById)
source4=e.target
if (source4.className=="menulines")
borderize(source4,"white")
else{
while(source4.tagName!="TABLE"){
source4=document.getElementById? source4.parentNode :

source4.parentElement
if (source4.className=="menulines")
borderize(source4,"white")
}
}
}
</script>
</head>


<body bgcolor="#FFFFFF" link='' alink='' vlink='' text="#000000">
<center><font face='georgia, times new roman' size=3> Product Price Report</font></center>
<form method=post name=priceDisplay action='./pricequery.php'>
<input type=hidden name=langid value=<?php echo $langid ?>>
<input type=hidden name=zoneid value=<?php echo $zoneid ?>>
<?php
$fcp = new FC_SQL;
$now=time();
?>
  <table align=center class="text">
  <tr>
  <td>
  <a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp();">
   Return To Central Maintenance Page</a><br>
  </td>
  </tr>
  </table>
<p><center><input type=button name=return value='Change Sort Criteria' onClick="javascript: priceDisplay.action='./printquery.php'; priceDisplay.submit();">&nbsp;&nbsp;&nbsp;
<input type=button name=quit value='Quit' onClick="javascript: priceDisplay.action='./index.php'; priceDisplay.submit();">
</center>
</p>
<p><center><font size=1 face='verdana, arial, helvetica'><b>Click <u>any</u> field</b> to modify the products.
<b>Click any Header Column</b> to re-sort records.
</font></center></p>
<?php

?>

<table width=900 align=center bordercolorlight="#EEEEFF" bordercolordark="#000033" bgcolor="#000099" border="3" class="text">  <tr>
 <td align=center onClick="self.location='./showpricereport.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&order=prodlsku&descend=<?php echo $descend; ?>'" width="70" <?php if($order=='prodlsku'){echo 'bgcolor=#0000FF';}?>><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Sku</b></font></u></font></td>
 <td align=center onClick="self.location='./showpricereport.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&order=prodname&descend=<?php echo $descend; ?>'" width="180" <?php if($order=='prodname'){echo 'bgcolor=#0000FF';}?>><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Product
      Name</b></font></u></font></td>
 <?php if($chkProdBegin){?><td align=center onClick="self.location='./showpricereport.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&order=prodstart&descend=<?php echo $descend; ?>'" width="70" <?php if($order=='prodstart'){echo 'bgcolor=#0000FF';}?>><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Begin</b></font></u></font></td><?php }
if($chkProdEnd){?><td align=center onClick="self.location='./showpricereport.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&order=prodstop&descend=<?php echo $descend; ?>'" width="70" <?php if($order=='prodstop'){echo 'bgcolor=#0000FF';}?>><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>End</b></font></u></font></td><?php }
if($chkSaleBegin){?><td align=center onClick="self.location='./showpricereport.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&order=prodsalebeg&descend=<?php echo $descend; ?>'" width="70" <?php if($order=='prodsalebeg'){echo 'bgcolor=#0000FF';}?>><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Sale<br>
      Begin</b></font></u></font></td><?php }
if($chkSaleEnd){?><td align=center onClick="self.location='./showpricereport.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&order=prodsaleend&descend=<?php echo $descend; ?>'" width="70" <?php if($order=='prodsaleend'){echo 'bgcolor=#0000FF';}?>><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Sale<br>
      End</b></font></u></font></td><?php }
if($chkCost){?><td align=center onClick="self.location='./showpricereport.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&order=prodrtlprice&descend=<?php echo $descend; ?>'" width="50" <?php if($order=='prodrtlprice'){echo 'bgcolor=#0000FF';}?>><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Cost<br>
      $</b></font></u></font></td><?php }
if($chkProdPrice){?><td align=center onClick="self.location='./showpricereport.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&order=prodprice&descend=<?php echo $descend; ?>'" width="50" <?php if($order=='prodprice'){echo 'bgcolor=#0000FF';}?>><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Product<br>
      $</b></font></u></font></td><?php }
if($chkSalePrice){?><td align=center onClick="self.location='./showpricereport.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&order=prodsaleprice&descend=<?php echo $descend; ?>'" width="50" <?php if($order=='prodsaleprice'){echo 'bgcolor=#0000FF';}?>><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Sale<br>
      $</b></font></u></font></td><?php }
if($chkSetupPrice){?><td align=center width="50"><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Setup<br>
      $</b></font></u></font></td><?php }
if($chkMargin){?><td align=center width="50"><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Margin<br>
      $</b></font></u></font></td><?php }
if($chkInvQty){?><td align=center onClick="self.location='./showpricereport.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&order=invqty&descend=<?php echo $descend; ?>'" width="50"><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>Inv.<br>Qty<br>
      </b></font></u></font></td><?php }
if($chkCogs){?><td align=center width="50"><font color="#FFFFFF"><u><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b>COGS<br>Inv.<br>
      </b></font></u></font></td><?php }?>
  </tr>
 </table>

<?php 
if($order){
  $query .= " order by ";
  //switch for sort options
  switch ($order){
           case "prodname":
                $query .= "prodname";

                $name_sku = 1;
                break;
           case "prodstart":
                $query .= "prodstart";
                $else_loop = 1;
                break;
           case "prodstop":
                $query .= "prodstop";
                $else_loop=1;
                break;
           case "prodsalebeg":
                $query .= "prodsalebeg";
                $else_loop=1;
                break;
           case "prodsaleend":
                $query .= "prodsaleend";
                $else_loop=1;
                break;
           case "prodsaleprice":
                $query .= "prodsaleprice";
                $else_loop=1;
                break;
           case "prodrtlprice":
                $query .= "prodrtlprice";
                $else_loop=1;
                break;
           case "prodprice":
                $query .= "prodprice";
                $else_loop=1;
                break;
           case "invqty":
                $query .= "prodinvqty";
                $order = "prodinvqty";
                $else_loop=1;
                break;
           default:   //sku
                $query .= "prodlsku";
                $name_sku=1;
  }
}




if($name_sku) {
   // start looping through products
$sku = new FC_SQL;
$fcp = new FC_SQL;
$fco = new FC_SQL;

$now=time();



$cnt = new FC_SQL;
$cnt->query("select count(*) as cnt from prodlang where prodlzid=$zoneid and prodlid=$langid");
$cnt->next_record();
$count = $cnt->f("cnt");
$cnt->free_result();


if($count < 1){print "<br><br><center>Language files were not found for language \"$langid\".  Please back up and choose the appropriate language.</center>";}
?>

<table width="900" cellspacing=2 border="3" align="center" bordercolorlight="#FEFEFE" bordercolordark="#333333" bgcolor="#CCCCCC" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" class="text">
<?php 

$tmp="select prodname,prodlsku from prodlang where prodlzid=$zoneid and prodlid=$langid order by $order";
if($descend ==1){
   $tmp .= " DESC";
   }

$sku->query($tmp);
while($sku->next_record() ){
        $loopsku=$sku->f("prodlsku");

       $query1= "select prodinvqty,prodsetup,prodsku,prodsalebeg,prodsaleend,prodsaleprice,prodprice,prodrtlprice,prodstart,prodstop ".
        "from prod,prodlang where prodsku='$loopsku' and (prodzid=$zoneid | prodlzid=$zoneid)";


   $fcp->query($query1);

    if ( $fcp->next_record() ) {
     $prodinvqty = $fcp->f("prodinvqty");
     $prodsetup = $fcp->f("prodsetup");
     if ($prodsetup){
       $prodsetup=sprintf("%.2f",$fcp->f("prodsetup"));
     }else{
       $prodsetup='-';
     }
     $retail = $fcp->f("prodrtlprice");
     $intRetail = doubleval($retail);
     if ($retail){
       $retail=sprintf("%.2f",$fcp->f("prodrtlprice"));
     }else{
       $retail='-';
     }

     if($fcp->f("prodsaleprice")){ // get the sale price
       $intSale=doubleval($fcp->f("prodsaleprice"));
       $sale=sprintf("%.2f",$fcp->f("prodsaleprice"));
     }else{
       $sale=0;
     }


     $salebegin=$fcp->f("prodsalebeg");
     $saleend=$fcp->f("prodsaleend");
     $prodprice=sprintf("%.2f",$fcp->f("prodprice"));
     $intProdPrice = doubleval($fcp->f("prodprice"));
     $prodstart=$fcp->f("prodstart");
     $prodstop=$fcp->f("prodstop");
     $prodname = $sku->f("prodname");

     if( $salebegin < $now && $now < $saleend ){    // if on sale, bold price.
       $onsale=1;
       $margin = $intSale - $intRetail;
       $sale="<b>\$".$sale."</b>";

     }else{
       $onsale=0;
       $margin = $intProdPrice - $intRetail;
       $sale = "<font color=#333333><s>\$$sale</s></font>";
       $prodprice = "<b>\$".$prodprice."</b>";      // else bold product price.
     }
     $cogs = $intRetail * $prodinvqty;

     if($salebegin){$salebegin=date("m-d-y",$salebegin);}else{$salebegin='-';}
     if($saleend){$saleend=date("m-d-y",$saleend);}else{$saleend='-';}
     if($onsale==0){
        $salebegin = "<font color=#333333><s>$salebegin</s></font>";
        $saleend = "<font color=#333333><s>$saleend</s></font>";

        }
     if($prodstart < $now && $now < $prodend){
       if(!$showdates){  // don't show product dates unless specified.
         $prodstart ='';
         $prodend = '';
         }
     }
     if($prodstart){$prodstart=date("m-d-y",$prodstart);}else{$prodstart='-';}
     if($prodstop){$prodstop=date("m-d-y",$prodstop);}else{$prodstop='-';}


     $margin = sprintf("$%.2f",$margin);
     ?>

        <tr align="center" class="menulines" onClick="self.location='./productmod.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&sku=<?php echo $loopsku; ?>&order=<?php echo $order; ?>&descend=<?php echo $descend; ?>'">
        <td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $loopsku; ?></font></td>
       <td width="180" class="menulines" align=left><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodname; ?></font></td>
<?php 
if($chkProdBegin){?><td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodstart; ?></font></td><?php }
if($chkProdEnd){?><td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodstop; ?></font></td><?php }
if($chkSaleBegin){?><td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $salebegin; ?></font></td><?php }
if($chkSaleEnd){?><td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $saleend; ?></font></td><?php }
if($chkCost){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo "$".$retail; ?></font></td><?php }
if($chkProdPrice){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodprice; ?></font></td><?php }
if($chkSalePrice){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $sale; ?></font></td><?php }
if($chkSetupPrice){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodsetup; ?></font></td><?php }
if($chkMargin){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $margin; ?></font></td><?php }
if($chkInvQty){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodinvqty; ?></font></td><?php }
if($chkCogs){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $cogs; ?></font></td><?php }
?></tr><?php 
   }  // end while fcp->next_record()
      // show the product options; see showcart for a detailed description

     $poptqty=0;
     $poptgrp=0;        // nmb
     $poptflag1=0;        // nmb
     $poptogrp=-1;                // -1 is initial value
     $poptgrpcnt=0;         // # of options per group
     $poptgrplst='';        // : separated list of all represented groups

     $fco->query("select poptid,poptname,poptsdescr,poptsetup,poptprice,poptgrp,".
         "poptflag1 from prodopt where poptsku='$loopsku' order by poptgrp,poptseq");
 if( $fco->next_record() ){
 do{

  $poptid =intval($fco->f("poptid"));
  $poptgrp=intval($fco->f("poptgrp"));
  $poptflag1=intval($fco->f("poptflag1"));
  $poptsetup=doubleval($fco->f("poptsetup"));
  $poptprice=doubleval($fco->f("poptprice"));
  $poptname=stripslashes($fco->f("poptname"));
  $poptname.=stripslashes($fco->f("poptsdescr"));


  if( $poptsetup ){
   $poptsetup = sprintf("%s%.2f<br>\n",$csym,$poptsetup);
  }
  if( $poptprice ){
        $poptprice = sprintf("%s%.2f<br>\n",$csym,$poptprice);
  }else{
        $poptprice = '-';
  }

  $poptgrpcnt++;                // incr count of options per group
  $poptogrp=$poptgrp;        // keep the current group ID
  $poptoflg=$poptflag1;        // keep the current group flag set

 }while( $fco->next_record() );

 $fco->free_result();

  ?>

       <tr align="center" class="menulines" onClick="self.location='./productmod.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&sku=<?php echo $loopsku; ?>&order=<?php echo $order; ?>&descend=<?php echo $descend; ?>'">
        <td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1">-</font></td>
       <td colspan=6 width="210" class="menulines" align=left><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $poptname; ?></font></td>

       <td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $poptprice; ?></font></td>
       <td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $poptsetup; ?></font></td>

       </tr> <?php 

 } // if product options


} // end while sku->next_record()


} // end $name_sku

############################################################################################

if($else_loop) {

   // start looping through products
$sku = new FC_SQL;
$fcp = new FC_SQL;
$fcc = new FC_SQL;
$fco = new FC_SQL;
$now=time();

?><table width="900" cellspacing=2 border="3" align="center" bordercolorlight="#FEFEFE" bordercolordark="#333333" bgcolor="#CCCCCC" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" class="text">
  <tr align="center">

  <?php 


$cnt = new FC_SQL;
$cnt->query("select count(*) as cnt from prodlang where prodlzid=$zoneid and prodlid=$langid");
$cnt->next_record();
$count = $cnt->f("cnt");
$cnt->free_result();

$tmp="select prodsku from prod where prodzid=$zoneid order by $order";
if($descend =='1'){
   $tmp .= " DESC";
   }

$sku->query($tmp);

while($sku->next_record() ){
        $loopsku=$sku->f("prodsku");

       $query1= "select prodinvqty,prodsku,prodsalebeg,prodsaleend,prodsaleprice,prodprice,prodrtlprice,prodstart,prodstop,prodsetup ".
        "from prod where prodsku='$loopsku' and prodzid=$zoneid";


   $fcp->query($query1);
   $fcc->query("select prodname from prodlang where prodlzid=$zoneid and prodlid=$langid and prodlsku=$loopsku");
    if ( $fcp->next_record() ) {
     if ($fcc->next_record() ) {$prodname=$fcc->f("prodname");}
     $retail = $fcp->f("prodrtlprice");
     $prodinvqty = $fcp->f("prodinvqty");
     $intRetail = doubleval($retail);

     if ($retail){
       $retail=sprintf("%.2f",$fcp->f("prodrtlprice"));
     }else{
       $retail='-';
     }
     if($fcp->f("prodsaleprice")){
       $intSale=doubleval($fcp->f("prodsaleprice"));
       $sale=sprintf("%.2f",$fcp->f("prodsaleprice"));
     }else{
       $sale=0;
     }
     $salebegin=$fcp->f("prodsalebeg");
     $saleend=$fcp->f("prodsaleend");
     $prodprice=sprintf("%.2f",$fcp->f("prodprice"));
     $intProdPrice = doubleval($fcp->f("prodprice"));
     $prodstart=$fcp->f("prodstart");
     $prodstop=$fcp->f("prodstop");
     $prodsetupprice=sprintf("%.2f",$fcp->f("prodsetup"));

     if( $salebegin < $now && $now < $saleend ){    // if on sale, bold price.
       $onsale=1;
       $margin = $intSale - $intRetail;
       $sale="<b>\$".$sale."</b>";
     }else{
       $onsale=0;
       $margin = $intProdPrice - $intRetail;
       $sale = "<font color=#333333><s>\$$sale</s></font>";
       $prodprice = "<b>\$".$prodprice."</b>";      // else bold product price.
     }

     if($salebegin){$salebegin=date("m-d-y",$salebegin);}else{$salebegin='-';}
     if($saleend){$saleend=date("m-d-y",$saleend);}else{$saleend='-';}
     if($onsale==0){
        $salebegin = "<font color=#333333><s>$salebegin</s></font>";
        $saleend = "<font color=#333333><s>$saleend</s></font>";
        }
     if($prodstart < $now && $now < $prodend){
       if(!$showdates){  // don't show product dates unless specified.
         $prodstart ='';
         $prodend = '';
         }
     }
     if($prodstart){$prodstart=date("m-d-y",$prodstart);}else{$prodstart='-';}
     if($prodstop){$prodstop=date("m-d-y",$prodstop);}else{$prodstop='-';}

      $cogs = $prodinvqty * $intRetail;
          $margin = sprintf("$%.2f",$margin);
     ?>
       <tr align="center" class="menulines" onClick="self.location='./productmod.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&sku=<?php echo $loopsku; ?>&order=<?php echo $order; ?>&descend=<?php echo $descend; ?>'">
        <td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $loopsku; ?></font></td>
       <td width="180" class="menulines" align=left><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodname; ?></font></td>
<?php if($chkProdBegin){?><td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodstart; ?></font></td><?php }
if($chkProdEnd){?><td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodstop; ?></font></td><?php }
if($chkSaleBegin){?><td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $salebegin; ?></font></td><?php }
if($chkSaleEnd){?><td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $saleend; ?></font></td><?php }
if($chkCost){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo "$".$retail; ?></font></td><?php }
if($chkProdPrice){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodprice; ?></font></td><?php }
if($chkSalePrice){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $sale; ?></font></td><?php }
if($chkSetupPrice){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodsetupprice; ?></font></td><?php }
if($chkMargin){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $margin; ?></font></td><?php }
if($chkInvQty){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $prodinvqty; ?></font></td><?php }
if($chkCogs){?><td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $cogs; ?></font></td><?php }
?></tr><?php 
   }  // end while fcp->next_record()
      // show the product options; see showcart for a detailed description

     $poptqty=0;
     $poptgrp=0;        // nmb
     $poptflag1=0;        // nmb
     $poptogrp=-1;                // -1 is initial value
     $poptgrpcnt=0;         // # of options per group
     $poptgrplst='';        // : separated list of all represented groups

     $fco->query("select poptid,poptname,poptsdescr,poptsetup,poptprice,poptgrp,".
         "poptflag1 from prodopt where poptsku='$loopsku' order by poptgrp,poptseq");
 if( $fco->next_record() ){
 do{

  $poptid =intval($fco->f("poptid"));
  $poptgrp=intval($fco->f("poptgrp"));
  $poptflag1=intval($fco->f("poptflag1"));
  $poptsetup=doubleval($fco->f("poptsetup"));
  $poptprice=doubleval($fco->f("poptprice"));
  $poptname=stripslashes($fco->f("poptname"));
  $poptname.=stripslashes($fco->f("poptsdescr"));


  if( $poptsetup ){
   $poptsetup = sprintf("%s%.2f<br>\n",$csym,$poptsetup);
  }
  if( $poptprice ){
        $poptprice = sprintf("%s%.2f<br>\n",$csym,$poptprice);
  }else{
        $poptprice = '-';
  }

  $poptgrpcnt++;                // incr count of options per group
  $poptogrp=$poptgrp;        // keep the current group ID
  $poptoflg=$poptflag1;        // keep the current group flag set

 }while( $fco->next_record() );

 $fco->free_result();

  ?>

       <tr align="center" class="menulines" onClick="self.location='./productmod.php?zoneid=<?php echo $zoneid; ?>&langid=<?php echo $langid; ?>&sku=<?php echo $loopsku; ?>&order=<?php echo $order; ?>&descend=<?php echo $descend; ?>'">
        <td width="70" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1">-</font></td>
       <td colspan=6 width="210" class="menulines" align=left><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $poptname; ?></font></td>

       <td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $poptprice; ?></font></td>
       <td width="50" class="menulines" align=center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php  echo $poptsetup; ?></font></td>

       </tr> <?php 

 } // if product options

} // end while sku->next_record()

} // end $else_loop)

?></table>
<p><center><input type=button name=return value='Change Sort Criteria' onClick="priceDisplay.action='./printquery.php'; priceDisplay.submit();">&nbsp;&nbsp;&nbsp;
<input type=button name=quit value='Quit' onClick="javascript: priceDisplay.action='./index.php'; priceDisplay.submit();">
</center>
</p>
  <table align=center class="text">
  <tr>
  <td>
  <a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>" onClick="closehelp();">
   Return To Central Maintenance Page</a><br>
  </td>
  </tr>
  </table>
</form>
<?php include('./footer.php'); ?>
