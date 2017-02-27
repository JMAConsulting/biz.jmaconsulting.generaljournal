<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Generaljournal_Form_JournalEntry extends CRM_Core_Form {

  /**
   * List of open Batches
   * @var array
   */
  public $_openBatches;

  /**
   * Set variables up before form is built.
   */
  public function preProcess() {
    if (!($this->_action & CRM_Core_Action::ADD)) {
      CRM_Core_Error::fatal(ts('This form cannot be used to edit/delete General Journal entry.'));
    }
    // TODO: include only open Batches
    $this->_openBatches = CRM_Batch_BAO_Batch::getBatches();
    parent::preProcess();
  }

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    parent::buildQuickForm();

    $this->addDate('date', ts('Date'), TRUE, array('formatType' => 'activityDate'));

    $this->addSelect('to_financial_account_id',
      array('entity' => 'financialTrxn', 'label' => ts('Debit account'), 'option_url' => NULL, 'placeholder' => ts('- any -')), TRUE
    );
    $this->addSelect('from_financial_account_id',
      array('entity' => 'financialTrxn', 'label' => ts('Credit account'), 'option_url' => NULL, 'placeholder' => ts('- any -')), TRUE
    );

    $this->addMoney('amount',
      ts('Amount'),
      TRUE,
      array('size' => 6, 'maxlength' => 14),
      TRUE, 'currency'
    );

    $this->add('text', 'description', ts('Description'));

    if (Civi::settings()->get('display_financial_batch')) {
      $this->add('select', 'batch_id',
        ts('Financial Batch'),
        $this->_openBatches
      );
      if (Civi::settings()->get('financial_batch_required')) {
        $this->addRule('batch_id', ts('Select an open Financial Batch as required. Create one if necessary first before creating general journal entry.'), 'required');
      }
    }
    $this->addButtons(array(
        array(
          'type' => 'upload',
          'name' => ts('Save'),
          'isDefault' => TRUE,
        ),
        array(
          'type' => 'upload',
          'name' => ts('Save and New'),
          'subName' => 'new',
        ),
        array(
          'type' => 'cancel',
          'name' => ts('Cancel'),
        ),
      )
    );
    $this->addFormRule(array('CRM_Generaljournal_Form_JournalEntry', 'formRule'), $this);
  }

  /**
   * Set default values.
   *
   * @return array
   */
  public function setDefaultValues() {
    $defaults = array();
    if (count($this->_openBatches)) {
      $defaults['batch_id'] = key($this->_openBatches);
    }
    return $defaults;
  }
  /**
   * Global form rule.
   *
   * @param array $fields
   *   The input form values.
   * @param array $files
   *   The uploaded files if any.
   * @param $self
   *
   * @return bool|array
   *   true if no errors, else array of errors
   */
  public static function formRule($fields, $files, $self) {
    $error = array();
    $financialPriorPeriod = Civi::settings()->get('prior_financial_period');
    if ($fields['date'] && Civi::settings()->get('financial_account_balance_enabled')
      && $financialPriorPeriod
    ) {
      $financialPriorPeriod = date("Y-m-t", strtotime($financialPriorPeriod));
      if (strtotime($fields['date']) <= $financialPriorPeriod) {
        $error['date'] = ts('Date should be greater then Prior Financial Period.');
      }
    }
    return $error;
  }

  /**
   * Process the form submission.
   */
  public function postProcess() {
    parent::postProcess();
  }
}
