{* Autumn Theme - Frontpage Ads Module - 2012 - Sercan YEMEN - twitter.com/sercan *}

<div id="autumn-ads" class="group">
    {foreach $ads as $ad}
    <div class="ad {if $ad@total == 1}full{else if $ad@total == 2}half-nocolumn{else if $ad@total == 3}one-third-nocolumn{/if} {if $ad@last}last{/if}">
        {if ($ad.url) != ""}<a href="{$ad.url}">{/if}
            <img class="autumn-ads-ad-image" src="{$modules_dir}autumnads/ads/{$ad.image}"/>
        {if ($ad.url) != ""}</a>{/if}
    </div>
    {/foreach}
</div>