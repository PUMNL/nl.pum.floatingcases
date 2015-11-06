<?php
/**
 * Page Floating Cases List to list all floating cases (PUM)
 *
 * @author Erik Hommel <erik.hommel@civicoop.org>
 * @date 15 June 2015
 *
 * Copyright (C) 2015 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to PUM <http://www.pum.nl> under the AGPL-3.0
 */

require_once 'CRM/Core/Page.php';

class CRM_Floatingcases_Page_FloatingCase extends CRM_Core_Page {

  function run() {
    $this->setPageConfiguration();
    $floatingCases = array();
    $daoFloatingCases = $this->getDaoFloatingCases();
    while($daoFloatingCases->fetch()) {
      $floatingCases[] = $this->buildRow($daoFloatingCases);
    }
    $this->assign('floatingCases', $floatingCases);
    parent::run();
  }
  /**
   * Function to build the display row
   *
   * @param object $dao
   * @return array
   * @access protected
   */
  protected function buildRow($dao) {
    $displayRow = array();
    $displayRow['case_id'] = $dao->id;
    if (!empty($dao->customer_id)) {
      $displayRow['customer_id'] = $dao->customer_id;
      $displayRow['customer'] = CRM_Threepeas_Utils::getContactName($dao->customer_id);
    } else {
      $displayRow['customer'] = "???";
    }
    $displayRow['case_type'] = $this->getCaseTypeName($dao->case_type_id);
    $displayRow['reprentative_id'] = CRM_Threepeas_BAO_PumCaseRelation::getCaseRepresentative($dao->id);
    $displayRow['representative'] = CRM_Threepeas_Utils::getContactName($displayRow['reprentative_id']);
    $displayRow['project_officer_id'] = CRM_Threepeas_BAO_PumCaseRelation::getRelationContactIdByCaseId($dao->id, 'project_officer');
    $displayRow['project_officer'] = CRM_Threepeas_Utils::getContactName($displayRow['project_officer_id']);
    $displayRow['subject'] = $dao->subject;
    $displayRow['actions'] = $this->setRowActions($dao);
    return $displayRow;
  }

  /**
   * Method to get case type name from id with value separators around id
   *
   * @param string $caseTypeIdString
   * @return string
   * @access protected
   */
  protected function getCaseTypeName($caseTypeIdString) {
    $config = CRM_Floatingcases_Config::singleton();
    $validCaseTypes = $config->getValidCaseTypes();
    $caseTypeIdParts = explode(CRM_Core_DAO::VALUE_SEPARATOR, $caseTypeIdString);
    if (isset($caseTypeIdParts[1])) {
      return $validCaseTypes[$caseTypeIdParts[1]];
    } else {
      return "";
    }
  }

  /**
   * Function to set urls for row
   *
   * @param int $caseId
   * @param int $customerId
   * @return array $urls
   * @access protected
   */
  protected function setRowUrls($caseId, $customerId) {
    $urls = array();
    $urls['manage'] = CRM_Utils_System::url("civicrm/contact/view/case", "reset=1&action=view&cid=".$customerId."&id=".$caseId, true);
    $urls['link'] = CRM_Utils_System::url('civicrm/floatingcase', "action=link&cid=".$caseId, true);
    return $urls;
  }
  /**
   * Function to set actions for row
   *
   * @param object $dao
   * @return array
   * @access protected
   */
  protected function setRowActions($dao) {
    $urls = $this->setRowUrls($dao->id, $dao->customer_id);
    $pageActions = array();
    $pageActions[] = '<a class="action-item" title="Manage case" href="'.$urls['manage'].'">Manage</a>';
    $pageActions[] = '<a class="action-item" title="Link case" href="'.$urls['link'].'">Link</a>';
    return $pageActions;
  }
  /**
   * Function to set the page configuration initially
   *
   * @access protected
   */
  protected function setPageConfiguration() {
    CRM_Utils_System::setTitle(ts('List of Floating Cases'));
    $config = CRM_Floatingcases_Config::singleton();
    $configCaseTypes = array();
    foreach ($config->getValidCaseTypes() as $caseTypeId => $caseTypeName) {
      $configCaseTypes[] = $caseTypeName;
    }
    $this->assign('configCaseTypes', implode(", ", $configCaseTypes));
    $session = CRM_Core_Session::singleton();
    $url = CRM_Utils_System::url('civicrm/floatingcaselist', 'reset=1', true);
    $session->pushUserContext($url);
  }
  /**
   * Function to get floating cases with as much data as possible
   *
   * @return object DAO
   * @access protected
   */
  protected function getDaoFloatingCases() {
    $config = CRM_Floatingcases_Config::singleton();
    $validCaseTypeParams = array();
    foreach ($config->getValidCaseTypes() as $validCaseTypeId => $validCaseTypeName) {
      $validCaseTypeParams[] = "'".CRM_Core_DAO::VALUE_SEPARATOR.$validCaseTypeId.CRM_Core_DAO::VALUE_SEPARATOR."'";
    }
    $query = "SELECT civicase.*, casecontact.contact_id AS customer_id
      FROM civicrm_case civicase LEFT JOIN civicrm_case_contact casecontact ON civicase.id = casecontact.case_id
      WHERE civicase.id NOT IN(SELECT DISTINCT(case_id) FROM civicrm_case_project)
        AND civicase.is_deleted = 0 AND civicase.case_type_id IN (".implode(", ", $validCaseTypeParams).")";
    return CRM_Core_DAO::executeQuery($query);
  }
}
