<?php

require_once 'CRM/Core/Form.php';
/**
 * Form controller class CRM_Floatingcase_Form_LinkCase
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Floatingcases_Form_LinkCase extends CRM_Core_Form {

  protected $_caseId = NULL;
  protected $_customerId = NULL;
  protected $_projectList = array();

  /**
   * Overridden parent method to build the form
   *
   * @access public
   */
  public function buildQuickForm() {
    $this->setIncomingIds();
    CRM_Utils_System::setTitle('Link Floating Case');
    $this->getProjectList();
    $this->assign('formHeader', ts('Link Floating Case to Existing Customer Project'));
    $this->add('hidden', 'caseId', $this->_caseId);
    $this->add('hidden', 'customerId', $this->_customerId);
    $this->add('text', 'case_type', ts('Case Type'));
    $this->add('text', 'case_status', ts('Case Status'));
    $this->add('text', 'case_subject', ts('Subject'));
    $this->add('text', 'case_customer', ts('Customer'));
    $this->add('text', 'case_country', ts('Country'));
    $this->add('text', 'case_representative', ts('Representative'));
    $this->add('text', 'case_project_officer', ts('Project Officer'));
    $this->add('select', 'case_project_id', ts('Project to Link to'), $this->_projectList);

    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Link'), 'isDefault' => true,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));

    parent::buildQuickForm();
  }

  /**
   * Overridden parent method to set the case values
   */
  public function setDefaultValues() {
    $defaults = array();
    $defaults['case_id'] = $this->_caseId;
    $defaults['customer_id'] = $this->_customerId;
    try {
      $caseData = civicrm_api3('Case', 'Getsingle', array('id' => $this->_caseId));
      $config = CRM_Threepeas_Config::singleton();
      $defaults['case_type'] = $config->caseTypes[$caseData['case_type_id']];
      $defaults['case_status'] = $config->caseStatus[$caseData['status_id']];
      $defaults['case_subject'] = $caseData['subject'];
      $defaults['case_customer'] = CRM_Threepeas_Utils::getContactName($this->_customerId);
      $representativeId = CRM_Threepeas_BAO_PumCaseRelation::getCaseRepresentative($this->_caseId);
      $defaults['case_representative'] = CRM_Threepeas_Utils::getContactName($representativeId);
      $projectOfficerId = CRM_Threepeas_BAO_PumCaseRelation::getRelationContactIdByCaseId($this->_caseId, 'project_officer');
      $defaults['case_project_officer'] = CRM_Threepeas_Utils::getContactName($projectOfficerId);
      $defaults['case_country'] = $this->getCaseCountry();
    } catch (CiviCRM_API3_Exception $ex) {}
    return $defaults;
  }

  /**
   * Method to get the country for the customer of the case
   *
   * @return string
   * @access private
   */
  private function getCaseCountry() {
    try {
      $contactData = civicrm_api3('Contact', 'Getsingle', array('id' => $this->_customerId));
      if (isset($contactData['country_id'])) {
        $countryParams = array(
          'id' => $contactData['country_id'],
          'return' => 'name'
        );
        try {
          return civicrm_api3('Country', 'Getvalue', $countryParams);
        } catch (CiviCRM_API3_Exception $ex) {}
      }
    } catch (CiviCRM_API3_Exception $ex) {}
    return "";
  }
  /**
   * Method to get project select list for customer
   *
   * @access private
   */
  private function getProjectList() {
    $projects = CRM_Threepeas_BAO_PumProject::getContactProjects($this->_customerId);
    foreach ($projects as $projectId => $projectData) {
      $this->_projectList[$projectId] = $projectData['title'];
    }
    asort($this->_projectList);
  }

  /**
   * Overridden parent method before form is built
   */
  public function preProcess() {
    if ($this->_action != CRM_Core_Action::UPDATE) {
      $session = CRM_Core_Session::singleton();
      $session->setStatus(ts('Invalid action'), 'Invalid Action', 'error');
      CRM_Utils_System::redirect($session->readUserContext());
    }
  }

  /**
   * Overridden parent method to set validation rules
   */
  public function addRules() {
    $this->addFormRule(array('CRM_Floatingcases_Form_LinkCase', 'validateEmpty'));
  }

  /**
   * Method to validate that project is not empty
   *
   * @param array $fields
   * @return array $errors or TRUE
   * @access public
   * @static
   */
  static function validateEmpty($fields) {
    $errors = array();
    if (empty($fields['case_project_id'])) {
      $errors['case_project_id'] = ts('You have to select a project if you want to link a case');
      return $errors;
    }
    if (!empty($errors)) {
      return $errors;
    } else {
      return TRUE;
    }
  }

  /**
   * Method to set the case_id and customer_id property
   *
   * @access private
   */
  private function setIncomingIds() {
    if (!isset($this->_submitValues['caseId']) || empty($this->_submitValues['caseId'])) {
      $this->_caseId = CRM_Utils_Request::retrieve('id', 'Integer');
    } else {
      $this->_caseId = $this->_submitValues['caseId'];
    }
    if (!isset($this->_submitValues['customerId']) || empty($this->_submitValues['customerId'])) {
      $this->_customerId = CRM_Utils_Request::retrieve('cid', 'Integer');
    } else {
      $this->_customerId = $this->_submitValues['customerId'];
    }
  }
  /**
   * Overridden parent method to process form values after submit
   *
   * @access public
   */
  public function postProcess() {
    $this->linkCaseProject();
    $session = CRM_Core_Session::singleton();
    $session->setStatus("Case ".$this->_caseId."- (".$this->_defaultValues['case_subject'].
      ") has been linked to project ".$this->_projectList[$this->_submitValues['case_project_id']]."", "Case Linked", "success");
    parent::postProcess();
  }

  /**
   * Method to link case to project
   *
   * @access private
   */
  private function linkCaseProject() {
    $params = array(
      'case_id' => $this->_caseId,
      'is_active' => 1,
      'project_id' => $this->_submitValues['case_project_id']
    );
    CRM_Threepeas_BAO_PumCaseProject::add($params);
  }
}
