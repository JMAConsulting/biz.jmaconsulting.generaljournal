<div class="crm-block crm-content-block crm-journalentry-view-form-block">
  <div class="crm-submit-buttons">
    <a class="button" href="{crmURL p='civicrm/contribute/journalentry' q='reset=1&action=add'}"><span>
      <i class="crm-i fa-trash"></i> {ts}Add General Journal Entry{/ts}</span>
    </a>
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>
  <table class="crm-info-panel">
    <tr>
      <td class="label">{ts}Name{/ts}</td>
      <td>
        {ts}General Journal Entry Debit{/ts}
	<br>
	{$trxnDetails.to_financial_account}
	<br>
	<br>
	{ts}Credit{/ts}
	<br>
	{$trxnDetails.from_financial_account}
      </td>
    </tr>
    <tr>
      <td class="label">{ts}Amount{/ts}</td>
      <td>{$trxnDetails.total_amount}</td>
    </tr>
    <tr>
      <td class="label">{ts}Received{/ts}</td>
      <td>{$trxnDetails.trxn_date}</td>
    </tr>
    <tr>
      <td class="label">{ts}Status{/ts}</td>
      <td>{$trxnDetails.status_id}</td>
    </tr>
  </table>
  <div class="crm-submit-buttons">
    <a class="button" href="{crmURL p='civicrm/contribute/journalentry' q='reset=1&action=add'}"><span>
      <i class="crm-i fa-trash"></i> {ts}Add General Journal Entry{/ts}</span>
    </a>
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>
</div>