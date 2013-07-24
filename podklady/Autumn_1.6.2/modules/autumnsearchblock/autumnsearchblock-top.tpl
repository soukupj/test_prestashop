{if isset($hook_mobile)}
<div class="input_search" data-role="fieldcontain">
	<form method="get" action="{$link->getPageLink('search')}" id="searchbox">
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		<input class="search_query" type="search" id="search_query_top" name="search_query" placeholder="{l s='Search' mod='autumnsearchblock'}" value="{if isset($smarty.get.search_query)}{$smarty.get.search_query|htmlentities:$ENT_QUOTES:'utf-8'|stripslashes}{/if}" />
	</form>
</div>
{else}

<div id="search_block_top">

	<form method="get" action="{$link->getPageLink('search')}" id="searchbox">
		<div class="search_block_top_form">
			<label for="search_query_top"><!-- image on background --></label>
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query light" type="text" id="search_query_top" name="search_query" value="{if isset($smarty.get.search_query)}{$smarty.get.search_query|htmlentities:$ENT_QUOTES:'utf-8'|stripslashes}{else}{l s='Search' mod='autumnsearchblock'}{/if}" onfocus="if(this.value=='{l s='Search' mod='autumnsearchblock'}')this.value='';" onblur="if (this.value=='')this.value='{l s='Search' mod='autumnsearchblock'}'" />
			<input type="submit" name="submit_search" value="" class="search_button" />
                </div>
	</form>
</div>
{include file="$self/autumnsearchblock-instantsearch.tpl"}
{/if}

