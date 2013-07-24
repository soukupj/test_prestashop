{*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My account'}</a><span class="navigation-pipe"></span>{l s='Order history'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
{include file="$tpl_dir./errors.tpl"}

<h1 class="dotted_bottom">{l s='Order history'}</h1>
<h4>{l s='Here are the orders you\'ve placed since your account was created.'}</h4>

{if $slowValidation}<p class="warning">{l s='If you have just placed an order, it may take a few minutes for it to be validated. Please refresh this page if your order is missing.'}</p>{/if}

<div class="block-center" id="block-history">
	{if $orders && count($orders)}
	<table id="order-list" class="autumn-table responsive">
		<thead>
			<tr>
				<th class="first_item">{l s='Order reference'}</th>
				<th class="item">{l s='Date'}</th>
				<th class="item">{l s='Total price'}</th>
				<th class="item">{l s='Payment'}</th>
				<th class="item">{l s='Status'}</th>
				<th class="item">{l s='Invoice'}</th>
				<th class="last_item">{l s='Actions'}</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$orders item=order name=myLoop}
			<tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{/if}">
				
                                <td class="history_link">
                                    <span class="table_mobile_label">{l s='Order Reference'}:</span>
                                    {if isset($order.invoice) && $order.invoice && isset($order.virtual) && $order.virtual}<img src="{$img_dir}icon/download_product.gif" class="icon" alt="{l s='Products to download'}" title="{l s='Products to download'}" />{/if}
                                    <a class="color-myaccount exclusive" href="javascript:showOrder(1, {$order.id_order|intval}, '{$link->getPageLink('order-detail', true)}');">{Order::getUniqReferenceOf($order.id_order)}</a>
				</td>
				
                                <td class="history_date">
                                    <span class="table_mobile_label">{l s='Date'}:</span>
                                    {dateFormat date=$order.date_add full=0}
                                </td>
				
                                <td class="history_price">
                                    <span class="table_mobile_label">{l s='Total price'}:</span>
                                    <span class="price">{displayPrice price=$order.total_paid currency=$order.id_currency no_utf8=false convert=false}</span>
                                </td>
				
                                <td class="history_method">
                                    <span class="table_mobile_label">{l s='Payment'}:</span>
                                    {$order.payment|escape:'htmlall':'UTF-8'}
                                </td>
                                
				<td class="history_state">
                                    <span class="table_mobile_label">{l s='Status'}:</span>
                                    {if isset($order.order_state)}{$order.order_state|escape:'htmlall':'UTF-8'}{/if}
                                </td>
                                
				<td class="history_invoice">
                                    <span class="table_mobile_label">{l s='Invoice'}:</span>
                                    {if (isset($order.invoice) && $order.invoice && isset($order.invoice_number) && $order.invoice_number) && isset($invoiceAllowed) && $invoiceAllowed == true}
					<a href="{$link->getPageLink('pdf-invoice', true, NULL, "id_order={$order.id_order}")}" title="{l s='Invoice'}" target="_blank"><img src="{$img_dir}icon/pdf.gif" alt="{l s='Invoice'}" class="icon" /></a>
					<a href="{$link->getPageLink('pdf-invoice', true, NULL, "id_order={$order.id_order}")}" title="{l s='Invoice'}" target="_blank">{l s='PDF'}</a>
                                    {else}-{/if}
				</td>
                                
				<td class="history_detail actions">
                                        <span class="table_mobile_label">{l s='Actions'}:</span>
					<a class="color-myaccount autumn-button" href="javascript:showOrder(1, {$order.id_order|intval}, '{$link->getPageLink('order-detail', true)}');">{l s='Details'}</a>
                                        {if isset($opc) && $opc}
                                            <a href="{$link->getPageLink('order-opc', true, NULL, "submitReorder&id_order={$order.id_order}")}" title="{l s='Reorder'}" class="autumn-button">
					{else}
                                            <a href="{$link->getPageLink('order', true, NULL, "submitReorder&id_order={$order.id_order}")}" title="{l s='Reorder'}" class="autumn-button">
					{/if}
                                            {l s='Reorder'}</a>
				</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
	<div id="block-order-detail" class="hidden">&nbsp;</div>
	{else}
		<p class="warning">{l s='You have not placed any orders.'}</p>
	{/if}
</div>

<ul class="footer_links clearfix">
	<li><a class="back_account_link" href="{$link->getPageLink('my-account', true)}">{l s='Back to Your Account'}</a></li>
	<li class="f_right"><a class="back_home_link" href="{$base_dir}">{l s='Home'}</a></li>
</ul>
