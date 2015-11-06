<?php
/**
 * Class following Singleton pattern for specific extension configuration
 * for Floating Cases PUM
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 5 Nov 2015
 * @license AGPL-3.0
 */

class CRM_Floatingcases_Config {
  /*
   * singleton pattern
   */
  static private $_singleton = NULL;

  protected $_validCaseTypes = array();

  /**
   * Constructor method
   *
   * @access public
   */
  function __construct() {
    $this->setValidCaseTypes();
  }

  /**
   * Method to get the valid case types
   *
   * @return string
   * @access public
   */
  public function getValidCaseTypes() {
    return $this->_validCaseTypes;
  }

  /**
   * Method to set the case type id for projectintake
   *
   * @throws Exception when API OptionValue Getvalue throws error
   */
  private function setValidCaseTypes() {
    $optionGroupId = CRM_Threepeas_Utils::getCaseTypeOptionGroupId();
    $validCaseTypes = array("Advice", "Business", "Grant", "Seminar", "RemoteCoaching");
    foreach ($validCaseTypes as $validCaseTypeName) {
      $params = array(
        'option_group_id' => $optionGroupId,
        'name' => $validCaseTypeName,
        'return' => 'value');
      try {
        $validCaseTypeId = civicrm_api3("OptionValue", 'Getvalue', $params);
        $this->_validCaseTypes[$validCaseTypeId] = $validCaseTypeName;
      } catch (CiviCRM_API3_Exception $ex) {
        throw new Exception("Could not find case type ".$validCaseTypeName.", error from API OptionValue Getvalue: "
          .$ex->getMessage());
      }
    }
  }

  /**
   * Function to return singleton object
   *
   * @return object $_singleton
   * @access public
   * @static
   */
  public static function &singleton() {
    if (self::$_singleton === NULL) {
      self::$_singleton = new CRM_Floatingcases_Config();
    }
    return self::$_singleton;
  }

}