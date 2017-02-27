<div class="crm-submit-buttons">
  {crmButton accesskey="N" p="civicrm/contribute/journalentry" q="reset=1&action=add" id="newGeneralJournalEntry" icon="crm-i fa-plus-circle"}{ts}Add General Journal Entry{/ts}{/crmButton}<br/>
</div>
<table class="crm-journalentry-selector">
  <thead>
  <tr>
    {foreach item=header from=$headers key=id}
      <th class="crm-{$id}">{$header}</th>
    {/foreach}
  </tr>
  </thead>
</table>

{literal}
<script type="text/javascript">
CRM.$(function($) {
  buildJournalEntrySelector();

  function buildJournalEntrySelector( filterSearch ) {
    var ZeroRecordText = {/literal}'<div class="status messages">{ts escape="js"}No General Journal entry have been created for this site.{/ts}</div>'{literal};

    var columns = '';
    var sourceUrl = {/literal}'{crmURL p="civicrm/ajax/journalentries" h=0 q="snippet=4"}'{literal};
    var $context = $('#crm-main-content-wrapper');

    crmJournalEntrySelector = $('table.crm-journalentry-selector', $context).dataTable({
      "bFilter"    : false,
      "bAutoWidth" : false,
      "aaSorting"  : [],
      "aoColumns"  : [
      {/literal}{foreach item=header from=$headers key=id}
        {literal}{sClass:'crm-{/literal}{$id}{literal}'},{/literal}
      {/foreach}{literal}
      ],
      "bProcessing": true,
      "asStripClasses" : [ "odd-row", "even-row" ],
      "sPaginationType": "full_numbers",
      "sDom"       : '<"crm-datatable-pager-top"lfp>rt<"crm-datatable-pager-bottom"ip>',
      "bServerSide": true,
      "bJQueryUI": true,
      "sAjaxSource": sourceUrl,
      "iDisplayLength": 25,
      "oLanguage": { "sZeroRecords":  ZeroRecordText,
        "sProcessing":    {/literal}"{ts escape='js'}Processing...{/ts}"{literal},
        "sLengthMenu":    {/literal}"{ts escape='js'}Show _MENU_ entries{/ts}"{literal},
        "sInfo":          {/literal}"{ts escape='js'}Showing _START_ to _END_ of _TOTAL_ entries{/ts}"{literal},
        "sInfoEmpty":     {/literal}"{ts escape='js'}Showing 0 to 0 of 0 entries{/ts}"{literal},
        "sInfoFiltered":  {/literal}"{ts escape='js'}(filtered from _MAX_ total entries){/ts}"{literal},
        "sSearch":        {/literal}"{ts escape='js'}Search:{/ts}"{literal},
        "oPaginate": {
          "sFirst":    {/literal}"{ts escape='js'}First{/ts}"{literal},
          "sPrevious": {/literal}"{ts escape='js'}Previous{/ts}"{literal},
          "sNext":     {/literal}"{ts escape='js'}Next{/ts}"{literal},
          "sLast":     {/literal}"{ts escape='js'}Last{/ts}"{literal}
        }
      },
      "fnServerData": function ( sSource, aoData, fnCallback ) {
        $.ajax({
          "dataType": 'json',
          "type": "POST",
          "url": sSource,
          "data": aoData,
          "success": fnCallback
        });
      }
    });
  }
});

</script>
{/literal}