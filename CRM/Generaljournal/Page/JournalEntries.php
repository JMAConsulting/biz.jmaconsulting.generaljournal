<?php

require_once 'CRM/Core/Page.php';

class CRM_Generaljournal_Page_JournalEntries extends CRM_Core_Page {

  /**
   * the main function that is called when the page
   * loads, it decides the which action has to be taken for the page.
   *
   * @return null
   */
  public function run() {
    $this->browse();
    parent::run();
  }

  /**
   * Browse all entities.
   */
  public function browse() {
    $headers = array(
      'date' => ts('Date'),
      'debit' => ts('Debit account'),
      'credit' => ts('Credit account'),
      'amount' => ts('Amount'),
      'description' => ts('Description'),
    );
    $this->assign('headers', $headers);
  }
  
}
