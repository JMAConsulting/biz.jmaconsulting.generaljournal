<?php

class CRM_Generaljournal_BAO_JournalEntries extends CRM_Core_DAO {

  /**
   * wrapper for ajax Journal Entry selector.
   *
   * @param array $params
   *   Associated array for params record id.
   *
   * @return array
   *   associated array of Journal Entry list
   */
  public static function getJournalEntryListSelector(&$params) {
    // format the params
    $params['offset'] = ($params['page'] - 1) * $params['rp'];
    $params['rowCount'] = $params['rp'];
    $params['sort'] = CRM_Utils_Array::value('sortBy', $params);

    // get journal Entries
    list($journalEntriesList, $total) = self::getJournalEntryList($params);
    $params['total'] = $total;
    return $journalEntriesList;
  }

  /**
   * Get list of Journal Entry.
   *
   * @param array $params
   *   Associated array for params.
   *
   * @return array
   */
  public static function getJournalEntryList(&$params) {

    if (!empty($params['rowCount']) && is_numeric($params['rowCount'])
      && is_numeric($params['offset']) && $params['rowCount'] > 0
    ) {
      $limit = " LIMIT {$params['offset']}, {$params['rowCount']} ";
    }

    $orderBy = ' ORDER BY cft.id';
    if (!empty($params['sort'])) {
      $orderBy = ' ORDER BY ' . CRM_Utils_Type::escape($params['sort'], 'String');
    }
    $query = "SELECT 
        cft.id AS financial_trxn_id,
        cft.trxn_date AS financial_trxn_date,
        CONCAT(cfa_debit.accounting_code, ' - ', cfa_debit.name) as financial_account_debit,
        CONCAT(cfa_credit.accounting_code, ' - ', cfa_credit.name) as financial_account_credit,
        cft.total_amount AS trxn_total_amount,
        cft.currency,
        cfi.description AS item_description
      FROM civicrm_financial_trxn cft
        INNER JOIN civicrm_entity_financial_trxn ceft ON ceft.financial_trxn_id = cft.id
          AND ceft.entity_table = 'civicrm_financial_item'
        LEFT JOIN civicrm_financial_item cfi ON cfi.id = ceft.entity_id
        LEFT JOIN civicrm_entity_financial_trxn ceft_contribution ON ceft_contribution.financial_trxn_id = cft.id
          AND ceft_contribution.entity_table = 'civicrm_contribution'
        LEFT JOIN civicrm_financial_account cfa_debit ON cfa_debit.id = cft.to_financial_account_id
        LEFT JOIN civicrm_financial_account cfa_credit ON cfa_credit.id = cfi.financial_account_id
      WHERE ceft_contribution.id IS NULL
      {$orderBy}
      {$limit}";
    $object = CRM_Core_DAO::executeQuery($query);
    $results = array();
    $dateFormat = Civi::settings()->get('dateformatFinancialBatch');
    while ($object->fetch()) {
      $results[$object->financial_trxn_id] = array(
        'date' => CRM_Utils_Date::customFormat($object->financial_trxn_date, $dateFormat),
        'debit' => $object->financial_account_debit,
        'credit' => $object->financial_account_credit,
        'amount' => CRM_Utils_Money::format($object->trxn_total_amount, $object->currency),
        'description' => $object->item_description,
      );
    }    
    return array($results, $object->N);
  }

  /**
   * create Journal Entry.
   *
   * @param array $params
   *   Associated array for params.
   *
   */
  public static function createJournalEntry($params) {
    $params['amount'] = CRM_Utils_Rule::cleanMoney($params['amount']);
    // create financial item entry
    $financialItemStatusID = civicrm_api3('OptionValue', 'getvalue', array(
      'return' => 'value',
      'option_group_id' => 'financial_item_status',
      'name' => 'Other',
    ));
    $financialItemParams = array(
      'created_date' => date('Ymd'),
      'transaction_date' => date('Ymd', strtotime($params['date'])),
      'contact_id' => CRM_Core_Session::singleton()->get('userID'),
      'description' => $params['description'],
      'amount' => $params['amount'],
      'currency' => $params['currency'],
      'financial_account_id' => $params['from_financial_account_id'],
      'status_id' => $financialItemStatusID,
    );
    $financialItem = civicrm_api3('financialItem', 'create', $financialItemParams);

    // create financial trxn entry
    $trxnStatusID = civicrm_api3('OptionValue', 'getvalue', array(
      'return' => 'value',
      'option_group_id' => 'contribution_status',
      'name' => 'Completed',
    ));
    $financialTrxnParams = array(
      'to_financial_account_id' => $params['to_financial_account_id'],
      'trxn_date' => date('Ymd', strtotime($params['date'])),
      'total_amount' => $params['amount'],
      'fee_amount' => 0,
      'net_amount' => $params['amount'],
      'currency' => $params['currency'],
      'status_id' => $trxnStatusID,
    );
    $trxnEntityTable = array(
      'entity_table' => "civicrm_financial_item",
      'entity_id' => $financialItem['id'],
    );
    CRM_Core_BAO_FinancialTrxn::create($financialTrxnParams, $trxnEntityTable);
  }

}
