{strip}
{if $packageMenuTitle}<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>{/if}
<ul class="{$packageMenuClass}">
	<li><a class="item" href="{$gBitLoc.BITCART_PKG_URL}index.php?main_page=shopping_cart">{tr}My Cart{/tr}</a></li>
	<li><a class="item" href="{$gBitLoc.BITCART_PKG_URL}index.php?main_page=products_new">{tr}New Products{/tr}</a></li>
{if $user}
	<li><a class="item" href="{$gBitLoc.BITCART_PKG_URL}index.php?main_page=account">{tr}Shopping Account{/tr}</a></li>
	<li><a class="item" href="{$gBitLoc.BITCART_PKG_URL}index.php?main_page=checkout_shipping">{tr}Checkout{/tr}</a></li>
{/if}
</ul>
{/strip}
