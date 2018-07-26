{*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2018                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*}
{if call_user_func(array('CRM_Core_Permission','check'), 'access CiviCRM')}
  {include file="CRM/common/accesskeys.tpl"}
  {if !empty($contactId)}
    {include file="CRM/common/contactFooter.tpl"}
  {/if}

  <div class="crm-footer" id="civicrm-footer">
    {* PCHR-1323 - Display CiviHR version info. *}
    {ts}Powered by CiviHR {/ts} {civihrVersion}.
    {if call_user_func(array('CRM_Core_Permission','check'), 'view system status on footer')}
      {if !empty($footer_status_severity)}
        <span class="status{if $footer_status_severity gt 3} crm-error{elseif $footer_status_severity gt 2} crm-warning{else} crm-ok{/if}">
          <a target="_blank" href="{crmURL p='civicrm/a/#/status'}">{$footer_status_message}</a>.
        </span>
      &nbsp;
      {/if}
    {/if}
    CiviHR is openly available under Version 3 of the <a target="_blank" href="http://www.gnu.org/licenses/agpl-3.0.html">GNU AGPL License</a> and can be downloaded from <a target="_blank" href="https://civihr.org">www.civihr.org</a>.
    <div class="text-center">
      <div class="footer-logo">
        <span class="chr_logo chr_logo--full chr_logo--default-size"><i></i></span>
      </div>
    </div>
  </div>
  {include file="CRM/common/notifications.tpl"}
{/if}
