<?php

/**
 * This class contains functions that are called using AJAX.
 */
class CRM_Generaljournal_Page_AJAX {

  /**
   * Get Journal Entries
   */
  public static function getJournalEntries() {
    if (empty($_POST)) {
      return NULL;
    }
    $sortMapper = array(
      0 => 'financial_trxn_date',
      1 => 'financial_account_debit',
      2 => 'financial_account_credit',
      3 => 'trxn_total_amount',
      4 => 'item_description',
    );
    $sEcho = CRM_Utils_Type::escape($_REQUEST['sEcho'], 'Integer');
    $offset = isset($_REQUEST['iDisplayStart']) ? CRM_Utils_Type::escape($_REQUEST['iDisplayStart'], 'Integer') : 0;
    $rowCount = isset($_REQUEST['iDisplayLength']) ? CRM_Utils_Type::escape($_REQUEST['iDisplayLength'], 'Integer') : 25;
    $sort = isset($_REQUEST['iSortCol_0']) ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
    $sortOrder = isset($_REQUEST['sSortDir_0']) ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';

    if ($sort && $sortOrder) {
      $params['sortBy'] = $sort . ' ' . $sortOrder;
    }

    $params['page'] = ($offset / $rowCount) + 1;
    $params['rp'] = $rowCount;

    // Get list of Journal Entry
    $journalEntriesLists = CRM_Generaljournal_BAO_JournalEntries::getJournalEntryListSelector($params);

    $iFilteredTotal = $iTotal = $params['total'];
    $selectorElements = array(
      'date',
      'debit',
      'credit',
      'amount',
      'description',
    );
    CRM_Utils_System::setHttpHeader('Content-Type', 'application/json');
    echo CRM_Utils_JSON::encodeDataTableSelector($journalEntriesLists, $sEcho, $iTotal, $iFilteredTotal, $selectorElements);
    CRM_Utils_System::civiExit();
  }

}
