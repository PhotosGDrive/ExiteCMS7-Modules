{***************************************************************************}
{*                                                                         *}
{* ExiteCMS template: download_statiscs.side_panel.tpl                     *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* Author: WanWizard <wanwizard@gmail.com>                                 *}
{*                                                                         *}
{* Revision History:                                                       *}
{* 2007-07-02 - WW - Initial version                                       *}
{*                                                                         *}
{***************************************************************************}
{*                                                                         *}
{* This template generates a side panel with a bar graph depicting the     *}
{* number of files downloaded as percentage of the total                   *}
{*                                                                         *}
{***************************************************************************}
{section name=bar loop=$counters}
{if $smarty.section.bar.first}
{include file="_openside_x.tpl" name=$_name title=$_title|cat:" ("|cat:$total|cat:")" state=$_state style=$_style}
<table cellpadding='0' cellspacing='0'>
{/if}
	<tr>
		<td style='padding-right: 4px; height: 16px;'>
			{if $counters[bar].dlsc_download_id && $counters[bar].download_cat}
			<a href='{$settings.siteurl}downloads.php?cat_id={$counters[bar].download_cat}&amp;download_id={$counters[bar].dlsc_download_id}' title='{$counters[bar].description}'>{$counters[bar].dlsc_name}</a>
			{else}
				{$counters[bar].dlsc_name}
			{/if}
		</td>
		<td style='border-left: 1px solid #cccccc;'>
			<div style='width:background-color: #f6f6f6;{$barwidth}'>
				<div style='height:12px;width:{$counters[bar].width}px;background-color:rgb({$counters[bar].red},{$counters[bar].green},{$counters[bar].blue});' title='{$counters[bar].description}'></div>
			</div>
		</td>
	</tr>
{if $smarty.section.bar.last}
</table>
{include file="_closeside_x.tpl"}
{/if}
{/section}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}