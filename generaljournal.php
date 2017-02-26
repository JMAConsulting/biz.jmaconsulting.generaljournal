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
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function generaljournal_civicrm_xmlMenu(&$files) {
  _generaljournal_civix_civicrm_xmlMenu($files);
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
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function generaljournal_civicrm_uninstall() {
  _generaljournal_civix_civicrm_uninstall();
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
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function generaljournal_civicrm_disable() {
  _generaljournal_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function generaljournal_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _generaljournal_civix_civicrm_upgrade($op, $queue);
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
  _generaljournal_civix_civicrm_managed($entities);
  $result = civicrm_api3('OptionValue', 'getcount', array(
    'option_group_id' => 'financial_item_status',
    'name' => 'Other',
  ));
  if (!$result) {
    $apiParams = array(
      'version' => 3,
      'label' => ts('Other'),
      'name' => 'Other',
      'description' => ts('Other'),
      'option_group_id' => 'financial_item_status',
      'component_id' => 'CiviContribute',
      'is_reserved' => TRUE,
    );
    $entities[] = array(
      'module' => 'biz.jmaconsulting.generaljournal',
      'name' => 'general_journal_entry',
      'entity' => 'OptionValue',
      'params' => $apiParams,
    );
  }
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function generaljournal_civicrm_caseTypes(&$caseTypes) {
  _generaljournal_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function generaljournal_civicrm_angularModules(&$angularModules) {
_generaljournal_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function generaljournal_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _generaljournal_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function generaljournal_civicrm_preProcess($formName, &$form) {

} // */

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
    'url' => 'civicrm/contribute/journalentry?reset=1',
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
