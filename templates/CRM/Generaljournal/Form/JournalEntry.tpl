{*
 +--------------------------------------------------------------------+
 | General Journal Entry Extension                                    |
 +--------------------------------------------------------------------+
 | Copyright JMA Consulting LLC (c) 2004-2017                         |
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
<div class="crm-block crm-form-block crm-general-journal-entry-form-block">
  <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
  <table class="form-layout-compressed">
    <tr>
      <td class="label">{$form.date.label}</td>
      <td>{include file="CRM/common/jcalendar.tpl" elementName=date}</td>
    </tr>
    <tr>
      <td class="label">{$form.to_financial_account_id.label}</td>
      <td>{$form.to_financial_account_id.html}</td>
    </tr>
    <tr>
      <td class="label">{$form.from_financial_account_id.label}</td>
      <td>{$form.from_financial_account_id.html}</td>
    </tr>
    <tr>
      <td class="label">{$form.amount.label}</td>
      <td>{$form.currency.html|crmAddClass:eight}&nbsp;{$form.amount.html}</td>
    </tr>
    <tr>
      <td class="label">{$form.description.label}</td>
      <td>{$form.description.html}</td>
    </tr>
    {if $form.batch_id}
      <tr>
        <td class="label">{$form.batch_id.label}</td>
      	<td>{$form.batch_id.html}</td>
      </tr>
    {/if}
  </table>
  <div class="spacer"></div>
  <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
</div>