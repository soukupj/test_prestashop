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
<script type="text/javascript">
// <![CDATA[
    var imageShape = '{$image_shape}';
//]]>
</script>

{include file="$tpl_dir./breadcrumb.tpl"}
{include file="$tpl_dir./errors.tpl"}

{if isset($category)}
	{if $category->id AND $category->active}
            
                {if (isset($show_cat_names) && $show_cat_names)}
                    <div class="category-title">
                        <h1>
                                {strip}
                                        {$category->name|escape:'htmlall':'UTF-8'}
                                        {if isset($categoryNameComplement)}
                                                {$categoryNameComplement|escape:'htmlall':'UTF-8'}
                                        {/if}
                                {/strip}
                        </h1>
                    </div>
                {/if}
                
		{if $scenes || $category->id_image || $category->description}
		<div class="content_scene_cat">
			{if $scenes}
				<!-- Scenes -->
				{include file="$tpl_dir./scenes.tpl" scenes=$scenes}
			{else}
				<!-- Category image -->
				{if $category->id_image}
					<img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')}" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage"/>
				{/if}
			{/if}
                        
                        {if ($category->description && isset($show_cat_desc) && $show_cat_desc)}
				<div class="cat_desc">
                                    <p>{$category->description}</p>
				</div>
			{/if}
                        
		</div>
		{/if}
		
                {if (isset($subcategories) && isset($show_subcategories) && $show_subcategories)}
		<!-- Subcategories -->
		<div id="subcategories">
			<h3>{l s='Subcategories'}</h3>
			<ul class="inline_list grid">
			{foreach from=$subcategories item=subcategory}
				<li class="clearfix">
					<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$subcategory.name|escape:'htmlall':'UTF-8'}" class="img">
						{if $subcategory.id_image}
							<img src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'medium_default')}" alt="" width="{$mediumSize.width}" height="{$mediumSize.height}" />
						{else}
							<img src="{$img_cat_dir}default-medium_default.jpg" alt="" width="{$mediumSize.width}" height="{$mediumSize.height}" />
						{/if}
					</a>
					<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}" class="cat_name">{$subcategory.name|escape:'htmlall':'UTF-8'}</a>
					{if $subcategory.description}
						<p class="cat_desc">{$subcategory.description|truncate:50:'...'}</p>
					{/if}
				</li>
			{/foreach}
			</ul>
			<br class="clear"/>
		</div>
		{/if}
                
		{if $products}	
                    <div class="content_sortPagiBar">
                        <div class="sortPagiBar group">
                            {include file="./product-compare.tpl"}
                            {include file="./product-sort.tpl"}
                            {include file="./nbr-product-page.tpl"}
                            {include file="./product-view.tpl"}
                        </div>
                    </div>
                        
                    <div id="category_view_type" class="{$category_view_type}">
                        <div id="category_image_type" class="{$image_shape}">
                            {include file="./product-list.tpl" products=$products}
                        </div>
                    </div>
                    
                    {include file="./pagination.tpl"}
                        
                {else if $products == NULL}
                    {include file="./no-product.tpl"}
		{/if}
                
	{elseif $category->id}
		<p class="warning">{l s='This category is currently unavailable.'}</p>
	{/if}
{/if}