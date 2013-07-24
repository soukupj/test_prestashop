{*
* 2007-2012 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2012 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="mywishlist">
	{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My account' mod='blockwishlist'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My wishlists' mod='blockwishlist'}{/capture}
	{include file="$tpl_dir./breadcrumb.tpl"}

	<h1 class="dotted_bottom">{l s='My wishlists' mod='blockwishlist'}</h1>

	{include file="$tpl_dir./errors.tpl"}

	{if $id_customer|intval neq 0}
		<form method="post" class="std" id="form_wishlist">
			<fieldset>
				<h3 class="title">{l s='Create a new wishlist' mod='blockwishlist'}</h3>
				<p class="text">
					<input type="hidden" name="token" value="{$token|escape:'htmlall':'UTF-8'}" />
					<label class="align_right" for="name">{l s='Name' mod='blockwishlist'}</label>
					<input type="text" id="name" name="name" class="inputTxt light" value="{if isset($smarty.post.name) and $errors|@count > 0}{$smarty.post.name|escape:'htmlall':'UTF-8'}{/if}" />
				</p>
				<p class="submit">
					<input type="submit" name="submitWishlist" id="submitWishlist" value="{l s='Save' mod='blockwishlist'}" class="autumn-button" />
				</p>
			</fieldset>
		</form>
		{if $wishlists}
		<div id="block-history" class="block-center">
			<table class="autumn-table responsive">
				<thead>
					<tr>
						<th class="first_item">{l s='Name' mod='blockwishlist'}</th>
						<th class="item mywishlist_first">{l s='Qty' mod='blockwishlist'}</th>
						<th class="item mywishlist_first">{l s='Viewed' mod='blockwishlist'}</th>
						<th class="item mywishlist_second">{l s='Created' mod='blockwishlist'}</th>
						<th class="item mywishlist_second">{l s='Direct Link' mod='blockwishlist'}</th>
						<th class="last_item mywishlist_first">{l s='Delete' mod='blockwishlist'}</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$wishlists}
					<tr id="wishlist_{$wishlists[i].id_wishlist|intval}">
						<td>
                                                    <span class="table_mobile_label">{l s='Name' mod='blockwishlist'}:</span>
						    <a class="exclusive" href="javascript:;" onclick="javascript:WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">{$wishlists[i].name|truncate:30:'...'|escape:'htmlall':'UTF-8'}</a>
						</td>
						<td class="bold">
                                                        <span class="table_mobile_label">{l s='Qty' mod='blockwishlist'}:</span>
							{assign var=n value=0}
							{foreach from=$nbProducts item=nb name=i}
								{if $nb.id_wishlist eq $wishlists[i].id_wishlist}
									{assign var=n value=$nb.nbProducts|intval}
								{/if}
							{/foreach}
							{if $n}
								{$n|intval}
							{else}
								0
							{/if}
						</td>
						<td><span class="table_mobile_label">{l s='Viewed' mod='blockwishlist'}:</span>{$wishlists[i].counter|intval}</td>
						<td><span class="table_mobile_label">{l s='Created' mod='blockwishlist'}:</span>{$wishlists[i].date_add|date_format:"%Y-%m-%d"}</td>
						<td><span class="table_mobile_label">{l s='Direct Link' mod='blockwishlist'}:</span><a class="exclusive" href="javascript:;" onclick="javascript:WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">{l s='View' mod='blockwishlist'}</a></td>
						<td class="wishlist_delete">
							<a class="autumn-button" href="javascript:;"onclick="return (WishlistDelete('wishlist_{$wishlists[i].id_wishlist|intval}', '{$wishlists[i].id_wishlist|intval}', '{l s='Do you really want to delete this wishlist ?' mod='blockwishlist' js=1}'));">{l s='Delete' mod='blockwishlist'}</a>
						</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</div>
		<div id="block-order-detail">&nbsp;</div>
		{/if}
	{/if}

	<ul class="footer_links">
		<li><a class="back_account_link" href="{$link->getPageLink('my-account', true)}">{l s='Back to Your Account' mod='blockwishlist'}</a></li>
		<li class="f_right"><a class="back_home_link" href="{$base_dir}">{l s='Home' mod='blockwishlist'}</a></li>
	</ul>
</div>