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
{* Template for the admin content module 'advertising'. This template      *}
{* generates a panel to add a new advertising client                       *}
{*                                                                         *}
{***************************************************************************}
{include file="_opentable.tpl" name=$_name title=$_title state=$_state style=$_style}
<form name='addsponsor' method='post' action='{$smarty.const.FUSION_SELF}{$aidlink}&amp;action=add'>
	<table align='center' cellpadding='0' cellspacing='0'>
	<tr>
		<td class='tbl'>{$locale.ads410}:</td>
		<td class='tbl'>
			<select class='textbox' name='new_sponsor' onkeydown='incrementalSelect(this,event)'>
			{html_options options=$users}
			</select>
		</td>
	</tr>
	<tr>
		<td align='center' colspan='2' class='tbl'>
			<br />
			<input type='submit' name='cancel' value='{$locale.ads441}' class='button'>&nbsp;
			<input type='submit' name='save' value='{$locale.ads440}' class='button'>
		</td>
	</tr>
	</table>
</form>
{include file="_closetable.tpl"}
{***************************************************************************}
{* End of template                                                         *}
{***************************************************************************}
