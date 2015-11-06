<?php

require_once 'floatingcases.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function floatingcases_civicrm_config(&$config) {
  _floatingcases_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function floatingcases_civicrm_xmlMenu(&$files) {
  _floatingcases_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function floatingcases_civicrm_install() {
  _floatingcases_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function floatingcases_civicrm_uninstall() {
  _floatingcases_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function floatingcases_civicrm_enable() {
  _floatingcases_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function floatingcases_civicrm_disable() {
  _floatingcases_civix_civicrm_disable();
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
function floatingcases_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _floatingcases_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function floatingcases_civicrm_managed(&$entities) {
  _floatingcases_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function floatingcases_civicrm_caseTypes(&$caseTypes) {
  _floatingcases_civix_civicrm_caseTypes($caseTypes);
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
function floatingcases_civicrm_angularModules(&$angularModules) {
_floatingcases_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function floatingcases_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _floatingcases_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implementation of hook civicrm_navigationMenu
 * to add a floating cases menu item to the Administer menu
 *
 * @param array $params
 */
function floatingcases_civicrm_navigationMenu( &$params ) {
  $maxKey = _floatingcases_getMaxMenuKey($params);
  $menuParentId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Administer', 'id', 'name');
  $params[$menuParentId]['child'][$maxKey+1] = array (
    'attributes' => array (
      'label'      => ts('PUM Floating Cases'),
      'name'       => ts('PUM Floating Cases'),
      'url'        => CRM_Utils_System::url('civicrm/floatingcaselist', 'reset=1', true),
      'permission' => 'administer CiviCRM',
      'operator'   => null,
      'separator'  => null,
      'parentID'   => $menuParentId,
      'navID'      => $maxKey+1,
      'active'     => 1
    ));
}

/**
 * Function to determine max key in navigation menu (core solutions do not cater for child keys!)
 *
 * @param array $menuItems
 * @return int $maxKey
 */
function _floatingcases_getMaxMenuKey($menuItems) {
  $maxKey = 0;
  foreach ($menuItems as $menuKey => $menuItem) {
    if ($menuKey > $maxKey) {
      $maxKey = $menuKey;
    }
    if (isset($menuItem['child'])) {
      foreach ($menuItem['child'] as $childKey => $child) {
        if ($childKey > $maxKey) {
          $maxKey = $childKey;
        }
      }
    }
  }
  return $maxKey;
}


