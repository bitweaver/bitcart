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

require_once( BITCART_PKG_PATH.'flags.php');
?>
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

function toggleESD( onoff ) {
 if( onoff == 'on' ){
  if( document.prodform.genesd[0].checked == false &&
      document.prodform.genesd[1].checked == false ){
    document.prodform.genesd[0].checked = true;
  }
 }else if( onoff = 'off' ){
  document.prodform.genesd[0].checked = false;
  document.prodform.genesd[1].checked = false;
 }
}

// stop hiding -->
</script>

</head>

<body text="#000000" link="#" vlink="#" alink="#" bgcolor="#FFFFFF" background="">
 <span class="text">
