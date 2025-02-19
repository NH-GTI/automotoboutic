{assign var=_counter value=0}
{function name="menu" nodes=[] depth=0 parent=null}
  {if $nodes|count}
    <ul class="top-menu" {if $depth == 0}id="top-menu" {/if} data-depth="{$depth}">
      {foreach from=$nodes item=node}
        <li class="{$node.type}{if $node.current} current {/if}" id="{$node.page_identifier}">
          {assign var=_counter value=$_counter+1}
          {assign var=_expand_id value=10|mt_rand:100000}
          <a class="{if $depth >= 0}dropdown-item{/if}{if $depth === 1} dropdown-submenu{/if}"
            {if $depth >= 0}data-target="top_sub_menu_{$_expand_id}" {/if}
            {if $depth > 0 || $node.page_identifier == 'lnk-tapis-sur-mesure'}href="{$node.url}" {/if} data-depth="{$depth}"
            {if $node.open_in_new_window} target="_blank" {/if}>
            {if $node.children|count}
              {* Cannot use page identifier as we can have the same page several times *}
              <span class="float-xs-right hidden-md-up">
                <span data-target="#top_sub_menu_{$_expand_id}" data-toggle="collapse" class="navbar-toggler collapse-icons">
                  <i class="material-icons add">&#xE313;</i>
                  <i class="material-icons remove">&#xE316;</i>
                </span>
              </span>
            {/if}
            {$node.label}
            {if $node.children|count && $depth == 0}
              <svg class="svg-inline--fa fa-chevron-down h-4 w-4 top-menu-arrow" aria-hidden="true" focusable="false"
                data-prefix="fas" data-icon="chevron-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                data-fa-i2svg="">
                <path fill="currentColor"
                  d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z">
                </path>
              </svg>
            {/if}
          </a>
          {if $node.children|count}
            <div {if $depth === 0} class="popover sub-menu js-sub-menu collapse" {else} class="" {/if}
              id="top_sub_menu_{$_expand_id}">
              {menu nodes=$node.children depth=$node.depth parent=$node}
            </div>
          {/if}
        </li>
      {/foreach}
    </ul>
  {/if}
{/function}

<div class="menu js-top-menu position-static hidden-sm-down" id="_desktop_top_menu">
  {menu nodes=$menu.children}
  <div class="clearfix"></div>
</div>