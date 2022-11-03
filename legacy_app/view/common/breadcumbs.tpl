
<span class="page-title" style="float: left;">{$title}</span>
<i class="fa fa-bar"></i>
<ul class="page-breadcrumb">    
    <li class="ms-hover">
        <a href="/merchant/dashboard"><i class="fa fa-home"></i></a>
        <i class="fa fa-angle-right"></i>
    </li>
    {if isset($links)}
        {foreach from=$links key=$key item=link}
            <li class="ms-hover">
                {if ($link.url) !=''}
                    <a href="{$link.url}">{$link.title}</a>
                {else}
                    {$link.title}
                {/if}
                {if $key < ($links|count - 1)}
                    <i class="fa fa-angle-right"></i>
                {/if}
            </li>
        {/foreach}
    {/if}
</ul>
