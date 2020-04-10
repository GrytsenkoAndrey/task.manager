{if count($rsPag) > 0}
<ul class="shop__paginator paginator">
{foreach $rsPag as $item}
<li>
    <a class="paginator__item" href="{$item['url']}">{$item['title']}</a>
</li>
{/foreach}
</ul>
{/if}