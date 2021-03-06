{***************************************************************************}
{* ExiteCMS Content Management System                                      *}
{***************************************************************************}
{* Copyright 2006-2008 Exite BV, The Netherlands                           *}
{* for support, please visit http://www.exitecms.org                       *}
{*-------------------------------------------------------------------------*}
{* Released under the terms & conditions of v2 of the GNU General Public   *}
{* License. For details refer to the included gpl.txt file or visit        *}
{* http://gnu.org                                                          *}
{***************************************************************************}
{* $Id::                                                                  $*}
{*-------------------------------------------------------------------------*}
{* Last modified by $Author::                                             $*}
{* Revision number $Rev::                                                 $*}
{***************************************************************************}
{*                                                                         *}
{* This template generates the PLi-Fusion infusion panel:                  *}
{* forum_threads_list_panel                                                *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable_x.tpl" name=$_name title=$locale.025 state=$_state style=$_style}
<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
	<tr>
		{if $smarty.const.THEME_WIDTH == "100%" || $smarty.const.THEME_WIDTH > "990"}<td align='left' class='tbl2'><b>{$locale.030}</b></td>{/if}
		<td align='left' class='tbl2'><b>{$locale.031}</b></td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.032}</b></td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.033}</b></td>
		<td align='center' colspan='2' width='1%' class='tbl2' style='white-space:nowrap'><b>{$locale.034}</b></td>
	</tr>
	{section name=entry loop=$threadlist}	
	<tr>
		{if $smarty.const.THEME_WIDTH == "100%" || $smarty.const.THEME_WIDTH > "990"}
			<td align='left' class='{cycle values="tbl1,tbl2" advance=false}'><a href='{$smarty.const.FORUM}viewforum.php?forum_id={$threadlist[entry].forum_id}' title='{$threadlist[entry].forum_name}'>{$threadlist[entry].forum_name}</a></td>
		{/if}
		<td align='left' class='{cycle values="tbl1,tbl2" advance=false}'><a href='{$smarty.const.FORUM}viewthread.php?forum_id={$threadlist[entry].forum_id}&amp;thread_id={$threadlist[entry].thread_id}&amp;pid={$threadlist[entry].last_id}#post_{$threadlist[entry].last_id}' title='{$threadlist[entry].thread_subject}'>{$threadlist[entry].thread_subject|truncate:40}{$threadlist[entry].fpm_append}</a></td>
		<td align='center' width='1%' class='{cycle values="tbl1,tbl2" advance=false}' style='white-space:nowrap'>{$threadlist[entry].thread_views}</td>
		<td align='center' width='1%' class='{cycle values="tbl1,tbl2" advance=false}' style='white-space:nowrap'>{$threadlist[entry].count_posts}</td>
		<td align='center' width='1%' class='{cycle values="tbl1,tbl2" advance=false}' style='white-space:nowrap'>
			{if $smarty.const.iMEMBER}
				<a href='{$smarty.const.BASEDIR}profile.php?lookup={$threadlist[entry].thread_lastuser}'>{$threadlist[entry].user_name}</a>
			{else}
				{$threadlist[entry].user_name}
			{/if}
		</td>
		<td align='center' width='1%' class='{cycle values="tbl1,tbl2"}' style='white-space:nowrap'>{$threadlist[entry].thread_lastpost|date_format:'forumdate'}</td>
	</tr>
	{/section}
</table>
<div style='text-align:center;'>
{if $smarty.const.iMEMBER}
	{buttonlink name=$locale.027a link=$smarty.const.BASEDIR|cat:"forum/viewposts.php"}
	{buttonlink name=$locale.026 link=$smarty.const.MODULES|cat:"forum_threads_list_panel/my_threads.php"}&nbsp;
	{buttonlink name=$locale.027 link=$smarty.const.MODULES|cat:"forum_threads_list_panel/my_posts.php"}&nbsp;
	{buttonlink name=$locale.028|cat:" ("|cat:$locale.031|cat:")" link=$smarty.const.MODULES|cat:"forum_threads_list_panel/new_posts.php"}&nbsp;
	{buttonlink name=$locale.028 link=$smarty.const.MODULES|cat:"forum_threads_list_panel/new_posts_detail.php"}
{else}
	{if $settings.forum_guest_limit}
		<span class='small' style='font-size:90%;font-weight:bold;'>
			{if $settings.forum_guest_limit == 1}{$locale.ftlp01|sprintf:$locale.074}{else}{assign var=days value=$settings.forum_guest_limit|cat:" "|cat:$locale.075}{$locale.ftlp01|sprintf:$days}{/if}
		</span>
	{/if}
{/if}
</div>
{include file="_closetable_x.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
