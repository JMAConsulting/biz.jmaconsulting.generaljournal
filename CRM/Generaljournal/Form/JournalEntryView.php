<?php

require_once 'CRM/Core/Page.php';

class CRM_Generaljournal_Form_JournalEntryView extends CRM_Core_Form {

  /**
   * Set variables up before form is built.
   */
  public function preProcess() {
    $trxnId = CRM_Utils_Request::retrieve('trxnid', 'String', $this);
    $trxnDetails = civicrm_api3('EntityFinancialTrxn', 'getsingle', array(
      'return' => array(
        "financial_trxn_id.to_financial_account_id.name",
        "financial_trxn_id.trxn_date",
        "financial_trxn_id.status_id",
        "financial_trxn_id.total_amount",
        "financial_trxn_id.currency",
        'entity_id',
      ),
      'financial_trxn_id.id' => $trxnId,
      'entity_table' => "civicrm_financial_item",
    ));
    $result = civicrm_api3('FinancialItem', 'getsingle', array(
      'return' => array("financial_account_id.name"),
      'id' => $trxnDetails['entity_id'],
    ));
    $dateFormat = Civi::settings()->get('dateformatFinancialBatch');
    $trxnDetails = array(
      'to_financial_account' => $trxnDetails['financial_trxn_id.to_financial_account_id.name'],
      'from_financial_account' => $result['financial_account_id.name'],
      'trxn_date' => CRM_Utils_Date::customFormat($trxnDetails['financial_trxn_id.trxn_date'], $dateFormat),
      'total_amount' => CRM_Utils_Money::format($trxnDetails['financial_trxn_id.total_amount'],$trxnDetails['financial_trxn_id.currency']),
      'status_id' => CRM_Core_PseudoConstant::getLabel('CRM_Contribution_BAO_Contribution', 'contribution_status_id', $trxnDetails['financial_trxn_id.status_id']),
    );
    $this->assign('trxnDetails', $trxnDetails);
  }

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    $this->addButtons(array(
      array(
        'type' => 'cancel',
        'name' => ts('Done'),
        'spacing' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
        'isDefault' => TRUE,
      ),
    ));
  }

}
