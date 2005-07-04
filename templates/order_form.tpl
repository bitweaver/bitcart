<div class="display bitcart">

<script src="js_orderform.js"></script>

<div class="header"><h1>{tr}Customer Order Information{/tr}</h1></div>

<form method="post" name="oform" action="<?php echo $securl.$secdir.'/'.$proc?>" onSubmit="validate_order()">
<script>
{literal}
function validate_order() {
  if( document.oform.onoff[0].checked==false &&
      document.oform.onoff[1].checked==false ){
   alert('<?php echo fc_text('jsonoff'); ?>');
   return false;
{/literal}{if $flag_zonetclink }{literal}
  }else if( document.oform.approvetc.checked==false ){
   	alert('<?php echo fc_text('jsapprovetc'); ?>');
	return false;
{/literal}{/if}{literal}
  }else if( document.oform.onoff[0].checked==true ){
   // only check cc info if online is checked
   if( document.oform.ccexp_month.options.selectedIndex  == 0 ){
    alert('<?php echo fc_text('jsccexp'); ?>');
    return false;
   }else if( document.oform.ccexp_year.options.selectedIndex  == 0 ){
    alert('<?php echo fc_text('jsccexp'); ?>');
    return false;
   }else if( document.oform.cc_name.value == '' ){
    alert('<?php echo fc_text('jsccname'); ?>');
    return false;
   }else if( document.oform.cc_number.value == '' ||
			 !mod10_verify( document.oform.cc_number.value ) ){
    alert('<?php echo fc_text('jsccnum'); ?>');
    return false;
   }else if( !document.oform.cctype[0].checked &&
             !document.oform.cctype[1].checked &&
             !document.oform.cctype[2].checked &&
             !document.oform.cctype[3].checked ){
    alert('<?php echo fc_text('jscctype'); ?>');
    return false;
   }
  }
  if( document.oform.billing_email.value == '' ){
   alert('<?php echo fc_text('jsbemail'); ?>');
   return false;
  }else if( document.oform.billing_first.value == '' ){
   alert('<?php echo fc_text('jsbfname'); ?>');
   return false;
  }else if( document.oform.billing_last.value == '' ){
   alert('<?php echo fc_text('jsblname'); ?>');
   return false;
  }else if( document.oform.billing_address1.value == '' ){
   alert('<?php echo fc_text('jsbaddr'); ?>');
   return false;
  }else if( document.oform.billing_city.value == '' ){
   alert('<?php echo fc_text('jsbcity'); ?>');
   return false;
  }else if( document.oform.billing_state.value == '' ){
   alert('<?php echo fc_text('jsbstate'); ?>');
   return false;
  }else if( document.oform.billing_zip.value == '' ){
   alert('<?php echo fc_text('jsbzip'); ?>');
   return false;
  }else if( document.oform.billing_country.options.selectedIndex == 0 ){
   alert('<?php echo fc_text('jsbcountry'); ?>');
   return false;
  }else if( document.oform.shipping_address1.value != ''  &&
             document.oform.shipping_country.options.selectedIndex == 0 ){
   alert('<?php echo fc_text('jsscountry'); ?>');
   return false;
  }else{
   alert('<?php echo fc_text('jsplaced'); ?>');
   return true;
  }
}
{/literal}
</script>

<table class="text" border="0" cellpadding="4" cellspacing="1" width="600" bgcolor="#666666">
<tr><td class="divrow" align="center" valign="top" colspan="4" bgcolor="#CCCCCC">
<b>{tr}Product Purchases{/tr}</b>
</td></tr>


{php} require( BITCART_PKG_PATH.'proddisp.php' ); {/php}


<tr><td class="divrow" colspan="4" align="center" bgcolor="#CCCCCC">
<b>{tr}billinfo{/tr}</b><br />
</td></tr>
<tr><td class="orderformcell" colspan="4" bgcolor="#FFFFFF">
{tr}reqtext{/tr}
<p></p>

{tr}emailaddr{/tr}{tr}reqflag{/tr}<br />
<input type="text" name="billing_email" size="40"
 value="{$customerHash.custbemail}" /><br />

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" bgcolor="#FFFFFF">
{tr}salutation{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}firstname{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}miname{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}lastname{/tr}{tr}reqflag{/tr}<br />
</td></tr>

<tr><td class="orderformcell" bgcolor="#FFFFFF">
<select name="billing_sal" size="1">
	<option value="">{tr}[optional title]{/tr}</option>
	{foreach from=$salutations key=salNumber item=sal}
	<option value="{$sal}">{$sal}</option>
	{/foreach}
</select>
</td>
<td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_first" size="15"
 value="{$customerHash.custbfname}" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_mi" size="2"
 value="{$customerHash.custbmname}" /><br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_last" size="20"
 value="{$customerHash.custblname}" /><br />
</td></tr>

</table>

{tr}address{/tr}{tr}reqflag{/tr}<br />
<input type="text" name="billing_address1" size="40"
 value="{$customerHash.custbaddr1}" /><br />
<input type="text" name="billing_address2" size="40"
 value="{$customerHash.custbaddr2}" /><br />

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" bgcolor="#FFFFFF">
{tr}city{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}state{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}zip{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}country{/tr}{tr}reqflag{/tr}<br />
</td></tr>
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_city" size="15"
 value="{$customerHash.custbcity}" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_state" size="3"
 value="{$customerHash.custbstate}" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="billing_zip" size="5"
 value="{$customerHash.custbzip}" />-
<input type="text" name="billing_zip4" size="4"
 value="{$customerHash.custbzip4}" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<select name="billing_country" size="1">
<option value="">[  {tr}selectctry{/tr}  ]</option>
{$billCountryOptions}
</select>
</td></tr>
</table>

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" bgcolor="#FFFFFF">
{tr}dayphone{/tr}{tr}reqflag{/tr}<br />
<input type="text" name="billing_acode" size="3"
 value="{$customerHash.custbacode}" />
<input type="text" name="billing_phone" size="8"
 value="{$customerHash.custbphone}" /><br />
</td></tr>
</table>

</td></tr>

{if $displayCC}
<tr><td class="divrow" colspan="4" align="center" bgcolor="#CCCCCC">
<b>{tr}creditinfo{/tr}</b><br />
</td></tr>

<tr><td class="orderformcell" align="left" valign="middle" colspan="1" bgcolor="#FFFFFF">
{tr}ccname{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" align="left" valign="middle" colspan="3" bgcolor="#FFFFFF">
<input type="text" name="cc_name" size="40" /><br />
</td></tr>

<tr><td class="orderformcell" valign="middle" align="left" colspan="1" bgcolor="#FFFFFF">
{tr}ccnumber{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" align="left" valign="middle" colspan="3" bgcolor="#FFFFFF">
<input type="text" name="cc_number" size="21" /><br />
</td></tr>

<tr><td class="orderformcell" valign="middle" align="left" colspan="1" bgcolor="#FFFFFF">
<a href="javascript:rs('pab','cvvtext.php?lid=<?php echo $lid;?>', 325, 225, 0)" target="_top" border="0">{tr}cvvnumber{/tr};?></a><br />
</td><td class="orderformcell" align="left" valign="middle" colspan="3" bgcolor="#FFFFFF">
<input type="text" name="cc_cvv" size="4" /><br />
</td></tr>

<tr><td class="orderformcell" align="left" valign="middle" colspan="2" bgcolor="#FFFFFF">

{tr}cctype{/tr}{tr}reqflag{/tr}<br />
<input type="radio" name="cctype" value="Visa" />VISA<br />
<input type="radio" name="cctype" value="Mastercard" />Mastercard<br />
<input type="radio" name="cctype" value="Discover" />Discover<br />
<input type="radio" name="cctype" value="American Express" />American Express<br />

</td><td class="orderformcell" align="center" valign="middle" colspan="2" bgcolor="#FFFFFF">
{tr}ccexpire{/tr}<br />

<table class="text" border="0">
<tr><td class="orderformcell" align="center" valign="top" bgcolor="#FFFFFF">
{tr}month{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" align="center" valign="top" bgcolor="#FFFFFF">
{tr}year{/tr}{tr}reqflag{/tr}<br />
</td></tr><tr><td class="orderformcell" align="center" valign="top" bgcolor="#FFFFFF">
<select name="ccexp_month" size="1">
<option value="0">[month]</option>
{php}
$i=1;
while($i<13){
 $mn=sprintf("%02d",$i);
 print '<option value="'.$mn.'">'.$mn."</option>\n";
 $i++;
}
{/php}
</select>
<br />
</td><td class="orderformcell" align="center" valign="top" bgcolor="#FFFFFF">
<select name="ccexp_year" size="1">
<option value="0">[year]</option>
{php}
$i=0;
$thisyr=date("Y",time());
while($i<$ccexp_years){
 print '<option value="'.$thisyr.'">'.$thisyr."</option>\n";
 $thisyr++;
 $i++;
}
{/php}
</select>
<br />
</td></tr>
</table>

</td></tr>
{/if}



<tr><td class="divrow" colspan="4" align="center" bgcolor="#CCCCCC">
<b>{tr}shipinfo{/tr}</b>
<br />
</td></tr>

<tr><td class="orderformcell" colspan="4" bgcolor="#FFFFFF">

{tr}emailaddr{/tr}{tr}reqflag{/tr}<br />
<input type="text" name="shipping_email" size="40"
 value="{$customerHash.custsemail}" /><br />

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" bgcolor="#FFFFFF">
{tr}salutation{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}firstname{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}miname{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}lastname{/tr}{tr}reqflag{/tr}<br />
</td></tr>
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<select name="shipping_sal" size="1">
	<option value="">{tr}saluteopt{/tr}</option>
	{foreach from=$salutations key=salNumber item=sal}
	<option value="{$sal}">{$sal}</option>
	{/foreach}
</select>
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_first" size="15"
 value="{$customerHash.custsfname}" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_mi" size="2"
 value="{$customerHash.custsmname}" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_last" size="20"
 value="{$customerHash.custslname}" /><br />
</td></tr>
</table>

{tr}address{/tr} ?><br />
<input type="text" name="shipping_address1" size="40"
 value="{$customerHash.custsaddr1}" /><br />
<input type="text" name="shipping_address2" size="40"
 value="{$customerHash.custsaddr2}" /><br />

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" bgcolor="#FFFFFF">
{tr}city{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}state{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}zip{/tr}{tr}reqflag{/tr}<br />
</td><td class="orderformcell" bgcolor="#FFFFFF">
{tr}country{/tr}{tr}reqflag{/tr}<br />
</td></tr>
<tr><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_city" size="15"
 value="{$customerHash.custscity}" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_state" size="3"
 value="{$customerHash.custsstate}" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<input type="text" name="shipping_zip" size="5"
 value="{$customerHash.custszip}" />-<input
 type="text" name="shipping_zip4" size="4"
 value="{$customerHash.custszip4}" />
</td><td class="orderformcell" bgcolor="#FFFFFF">
<select name="shipping_country" size="1">
<option value="">[  {tr}selectctry{/tr};?>  ]</option>
{$shipCountryOptions}
</select>
</td></tr>
</table>

{tr}dayphone{/tr}<br />
<input type="text" name="shipping_acode" size="3"
 value="{$customerHash.custsacode}" />
<input type="text" name="shipping_phone" size="8"
 value="{$customerHash.custsphone}" /><br />

</td></tr>
<tr><td class="divrow" align="center" colspan="4" bgcolor="#CCCCCC">
<b>{tr}ordermethod{/tr}{tr}reqflag{/tr}</b><br />
</td></tr>
<tr><td class="orderformcell" align="center" valign="middle" colspan="4" bgcolor="#FFFFFF">

<table class="text" border="0" cellpadding="0">
<tr><td class="orderformcell" align="center" valign="middle" colspan="1" width="25%" bgcolor="#FFFFFF">

<input type="radio"  name="onoff" value="on" />
<b>{tr}online{/tr}</b><br />

</td><td class="orderformcell" align="left" valign="top" colspan="1" width="25%" bgcolor="#FFFFFF">

{tr}onlinetext{/tr}<br />

</td><td class="orderformcell" align="center" valign="middle" colspan="1" width="25%" bgcolor="#FFFFFF">

<input type="radio"  name="onoff" value="off" />
<b>{tr}offline{/tr}</b><br />

</td><td class="orderformcell" align="left" valign="top" colspan="1" width="25%" bgcolor="#FFFFFF">

{tr}offlinetext{/tr}<br />

</td></tr>
</table>

</td></tr>

<tr><td class="orderformcell" align="center" colspan="4" bgcolor="#FFFFFF">
<input type="checkbox" name="promoemail" value="1" checked />
{tr}promoemail{/tr}<br />
</td></tr>

<tr><td class="orderformcell" align="center" colspan="4" bgcolor="#FFFFFF">
<input type="checkbox" name="retain_addr" value="1"
{php}
global $CookieCustID;
if( isset($CookieCustID) ){
 print ' checked';
}
{/php}
/>
{tr}retain_addr{/tr}<br />
</td></tr>

{if $zonetclink }
<tr><td class="orderformcell" align="center" colspan="4" bgcolor="#FFFFFF">
<a href="terms.php" target="_blank">{tr}termscon{/tr}</a><br />
<input type="checkbox" name="approvetc" value="1" />
{tr}approvetc{/tr}<br />
</td></tr>
{/if}

<tr><td class="orderformcell" align="center" valign="top" colspan="2" bgcolor="#FFFFFF">

<input type="hidden" name="custid" value="<?php echo $custid?>" />
<input type="hidden" name="itot" value="<?php echo $itot?>" />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
<input type="hidden" name="zflag1" value="<?php echo $zflag1?>" />
<input type="hidden" name="zid" value="<?php echo $zid?>" />
<input type="hidden" name="lid" value="<?php echo $lid?>" />
<input type="hidden" name="aid" value="<?php echo $aid?>" />
<input type="hidden" name="ttotal" value="<?php echo $ttotal?>" />
<input type="hidden" name="ptotal" value="<?php echo $ptotal?>" />
<input type="hidden" name="ccexp_years" value="<?php echo $ccexp_years?>" />

<input type="hidden" name="referer" value="<?php echo $REMOTE_ADDR?>" />
<input type="submit" value="{tr}ordersubmit{/tr}" />

</td><td class="orderformcell" align="center" valign="top" colspan="2" bgcolor="#FFFFFF">

<input type="reset"  value="{tr}clearform{/tr}" />

</td></tr>
</table>
</form>
</center>
<center>
<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td class="navtext">
<div id="button" align="center">
<ul>
<li><a href="<?php echo $nsecurl.$cartdir ?>/index.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>">{tr}zonehome{/tr}</a></li>
</ul>
</div>
</td></tr></table>
</center>


