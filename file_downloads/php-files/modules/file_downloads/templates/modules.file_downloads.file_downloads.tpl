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
{* Template for the user panel of the 'file_downloads' module              *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$locale.400 state=$_state style=$_style}
	{section name=id loop=$cats}
		<table align='center' cellpadding='0' cellspacing='1' width='600' class='tbl-border'>
		<tr>
			<td colspan='3' class='infobar'>
				<b>{$cats[id].fd_name}</b>
			</td>
		</tr>
		{if $cats[id].fd_prev_dir != ""}
			<tr>
				<td colspan='3' class='tbl2'>
					<img src='{$smarty.const.THEME}images/directory.gif' title='' alt='' />&nbsp;
					<a href='{$smarty.const.FUSION_SELF}?fd_id={$cats[id].fd_id}&amp;dir=-1'>..</a>
				</td>
			</tr>
		{/if}
		{if $cats[id].directories|@count == 0 && $cats[id].files|@count == 0}
			<tr>
				<td colspan='3' class='tbl1'>
					<b>{$locale.450}</b>
				</td>
			</tr>
		{else}
			{foreach from=$cats[id].directories item=dir name=dir}
				<tr>
					<td colspan='3' class='tbl2'>
						<img src='{$smarty.const.THEME}images/directory.gif' title='' alt='' />&nbsp;
						<a href='{$smarty.const.FUSION_SELF}?fd_id={$cats[id].fd_id}&amp;dir={$smarty.foreach.dir.index}'>{$dir}</a>
					</td>
				</tr>
			{/foreach}
			{section name=file loop=$cats[id].files}
				<tr>
					<td class='tbl1'>
						<img src='{$smarty.const.THEME}images/file.gif' title='' alt='' />&nbsp;
						<a href='{$smarty.const.BASEDIR}getfile.php?type=fd&amp;fd_id={$cats[id].fd_id}&amp;file_id={$smarty.section.file.index}'>{$cats[id].files[file].name|truncate:90:" &hellip; ":true:true}</a>
					</td>
					<td width='1%' class='tbl1' style='white-space:nowrap;'>
						{$cats[id].files[file].date|date_format:"forumdate"}
					</td>
					<td width='1%' class='tbl1' style='white-space:nowrap;'>
						{$cats[id].files[file].size}
					</td>
				</tr>
			{/section}
		{/if}
		</table>
		<br />
	{sectionelse}
		<div style='text-align:center'>
			<br />
			{$locale.408}
			<br /><br />
		</div>
	{/section}
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}