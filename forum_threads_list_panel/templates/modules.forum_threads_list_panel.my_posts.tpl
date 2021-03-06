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
{* This template generates the panel:                                      *}
{* forum_threads_list_panel/my_posts                                       *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
{if $rows > 0}
<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
	<tr>
		<td class='tbl2'>
			<span class='small'>
				<b>{$locale.030}</b>
			</span>
		</td>
		<td class='tbl2'>
			<span class='small'>
				<b>{$locale.035}</b>
			</span>
		</td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>
			<span class='small'>
				<b>{$locale.036}</b>
			</span>
		</td>
	</tr>
	{section name=id loop=$posts}
	<tr>
		<td class='{cycle values='tbl1,tbl2' advance=no}'>
			<span class='small'>
				<a href='{$smarty.const.FORUM}viewforum.php?forum_id={$posts[id].forum_id}' title='{$posts[id].forum_name}'>{$posts[id].forum_name}</a>
			</span>
		</td>
		<td class='{cycle values='tbl1,tbl2' advance=no}'>
			<span class='small'>
				<a href='{$smarty.const.FORUM}viewthread.php?{$posts[id].rstart}forum_id={$posts[id].forum_id}&amp;thread_id={$posts[id].thread_id}&amp;pid={$posts[id].post_id}#post_{$posts[id].post_id}' title='{$posts[id].post_subject}'>{$posts[id].post_subject|truncate:40:"..."}{if $posts[id].poll} <b>{$locale.112}</b>{/if}</a>
			</span>
		</td>
		<td align='center' width='1%' class='{cycle values='tbl1,tbl2'}' style='white-space:nowrap'>
			<span class='small'>
				{$posts[id].post_datestamp|date_format:"forumdate"}
			</span>
		</td>
	</tr>
	{/section}
</table>
{else}
<center>
	<br />
	{$locale.038}
	<br /><br />
</center>
{/if}
{include file="_closetable.tpl"}
{if $rows > $smarty.const.ITEMS_PER_PAGE}
	{makepagenav start=$rowstart count=$smarty.const.ITEMS_PER_PAGE total=$rows range=3 link=$pagenav_url}
{/if}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
