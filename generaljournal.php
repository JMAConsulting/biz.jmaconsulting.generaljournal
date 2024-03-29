<?php

require_once 'generaljournal.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function generaljournal_civicrm_config(&$config) {
  _generaljournal_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function generaljournal_civicrm_install() {
  _generaljournal_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function generaljournal_civicrm_enable() {
  _generaljournal_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function generaljournal_civicrm_managed(&$entities) {
  $result = civicrm_api3('OptionValue', 'get', array(
    'option_group_id' => 'financial_item_status',
    'name' => 'Other',
    'options' => array('limit' => 1),
  ));
  $apiParams = array(
    'version' => 3,
    'label' => ts('Other'),
    'name' => 'Other',
    'description' => ts('Other'),
    'option_group_id' => 'financial_item_status',
    'component_id' => 'CiviContribute',
    'is_reserved' => TRUE,
  );
  if ($result['values']) {
    $apiParams['id'] = $result['id'];
  }
  $entities[] = array(
    'module' => 'biz.jmaconsulting.generaljournal',
    'name' => 'general_journal_entry',
    'entity' => 'OptionValue',
    'params' => $apiParams,
  );
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *

 // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 */
function generaljournal_civicrm_navigationMenu(&$menu) {
  _generaljournal_civix_insert_navigation_menu($menu, 'Contributions', array(
    'label' => ts('New General Journal Entry', array('domain' => 'biz.jmaconsulting.generaljournal')),
    'name' => 'new_general_journal_entry',
    'url' => 'civicrm/contribute/journalentry?reset=1&action=add',
    'permission' => 'access CiviContribute,administer Accounting',
    'operator' => 'AND',
    'separator' => 0,
  ));
  _generaljournal_civix_insert_navigation_menu($menu, 'Contributions', array(
    'label' => ts('Manage General Journal Entries', array('domain' => 'biz.jmaconsulting.generaljournal')),
    'name' => 'manage_general_journal_entries',
    'url' => 'civicrm/contribute/journalentries?reset=1',
    'permission' => 'access CiviContribute,administer Accounting',
    'operator' => 'AND',
    'separator' => 0,
  ));
  _generaljournal_civix_navigationMenu($menu);
}

/**
 * Implements hook_civicrm_alterBatchTransactionListQuery().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterBatchTransactionListQuery
 *
 */
function generaljournal_civicrm_alterBatchTransactionListQuery(&$query, $batchId, $params, $batchTransactions) {
  if ($query['orderBy']) {
    $query['orderBy'] = " ORDER BY id ";
    if (!empty($params['sort'])) {
      $query['orderBy']  = ' ORDER BY ' . CRM_Utils_Type::escape($params['sort'], 'String');
    }
  }
  if (!$batchTransactions) {
    $where = " civicrm_entity_batch.batch_id = {$batchId} ";
  }
  else {
    $where = " civicrm_entity_batch.batch_id IS NULL ";
  }
  $query['groupBy'] .= "
    UNION
    SELECT 
    ft.id, 
    ft.payment_instrument_id AS payment_method, 
    NULL AS contact_id, 
    NULL AS contributionID, 
    CONCAT('JE: Debit ', IFNULL(fa_debit.accounting_code,'Acct Code missing'), ', Credit ', IFNULL(fa_credit.accounting_code,'Acct Code missing')) AS sort_name, 
    ft.total_amount AS amount, 
    ft.trxn_id AS trxn_id, 
    NULL AS contact_type, 
    NULL AS contact_sub_type, 
    ft.trxn_date AS transaction_date,
    NULL as receive_date,
    NULL AS financial_type, 
    ft.currency AS currency, 
    ft.status_id AS status, 
    ft.check_number AS check_number
    FROM civicrm_financial_trxn ft
    INNER JOIN civicrm_entity_financial_trxn eft ON (eft.financial_trxn_id = ft.id AND eft.entity_table='civicrm_financial_item')
    INNER JOIN civicrm_financial_item fi ON eft.entity_id=fi.id
    INNER JOIN civicrm_option_value ov ON (fi.status_id=ov.value AND ov.name='Other')
    INNER JOIN civicrm_option_group og ON (ov.option_group_id=og.id AND og.name='financial_item_status')
    INNER JOIN civicrm_financial_account fa_debit ON fi.financial_account_id=fa_debit.id
    INNER JOIN civicrm_financial_account fa_credit ON ft.to_financial_account_id=fa_credit.id
    LEFT JOIN civicrm_entity_batch ON civicrm_entity_batch.entity_table = 'civicrm_financial_trxn' AND civicrm_entity_batch.entity_id = ft.id
    WHERE ($where)";
}

/**
 * Implements hook_civicrm_links().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_links
 *
 */
function generaljournal_civicrm_links($op, $objectName, &$objectId, &$links, &$mask = NULL, &$values = array()) {
  if ($objectName == 'FinancialItem' && 'financialItem.batch.row' == $op
    && empty($values['contid'])
  ) {
    foreach ($links as $id => $link) {
      if (in_array(strtolower($link['bit']), array('view'))) {
        $links[$id] = array(
          'name' => ts('View'),
          'url' => 'civicrm/contribute/journalentry/view',
          'qs' => 'reset=1&action=view&trxnid=%%id%%',
          'title' => ts('View General Entry'),
          'bit' => 'View',
        );
      }
    }
  }
}
