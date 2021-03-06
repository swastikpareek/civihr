<?php
/*
+--------------------------------------------------------------------+
| CiviCRM version 4.7                                                |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2017                                |
+--------------------------------------------------------------------+
| This file is a part of CiviCRM.                                    |
|                                                                    |
| CiviCRM is free software; you can copy, modify, and distribute it  |
| under the terms of the GNU Affero General Public License           |
| Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
|                                                                    |
| CiviCRM is distributed in the hope that it will be useful, but     |
| WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
| See the GNU Affero General Public License for more details.        |
|                                                                    |
| You should have received a copy of the GNU Affero General Public   |
| License and the CiviCRM Licensing Exception along                  |
| with this program; if not, contact CiviCRM LLC                     |
| at info[AT]civicrm[DOT]org. If you have questions about the        |
| GNU Affero General Public License or the licensing of CiviCRM,     |
| see the CiviCRM license FAQ at http://civicrm.org/licensing        |
+--------------------------------------------------------------------+
*/
/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2017
 *
 * Generated from xml/schema/CRM/HRLeaveAndAbsences/LeaveRequestDate.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:d4315e49575fd1012ebc663d1fcc58c3)
 */
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
/**
 * CRM_HRLeaveAndAbsences_DAO_LeaveRequestDate constructor.
 */
class CRM_HRLeaveAndAbsences_DAO_LeaveRequestDate extends CRM_Core_DAO {
  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_hrleaveandabsences_leave_request_date';
  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var boolean
   */
  static $_log = true;
  /**
   * Unique LeaveRequestDate ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * A date part of the Leave Request.
   *
   * @var date
   */
  public $date;
  /**
   * FK to LeaveRequest
   *
   * @var int unsigned
   */
  public $leave_request_id;
  /**
   * The type of this day, according to the values on the Leave Request Day Types Option Group
   *
   * @var string
   */
  public $type;
  /**
   * Class constructor.
   */
  function __construct() {
    $this->__table = 'civicrm_hrleaveandabsences_leave_request_date';
    parent::__construct();
  }
  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static ::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'leave_request_id', 'civicrm_hrleaveandabsences_leave_request', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }
  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = array(
        'id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'Unique LeaveRequestDate ID',
          'required' => true,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request_date',
          'entity' => 'LeaveRequestDate',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequestDate',
          'localizable' => 0,
        ) ,
        'date' => array(
          'name' => 'date',
          'type' => CRM_Utils_Type::T_DATE,
          'title' => ts('Date') ,
          'description' => 'A date part of the Leave Request.',
          'required' => true,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request_date',
          'entity' => 'LeaveRequestDate',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequestDate',
          'localizable' => 0,
        ) ,
        'leave_request_id' => array(
          'name' => 'leave_request_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => 'FK to LeaveRequest',
          'required' => true,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request_date',
          'entity' => 'LeaveRequestDate',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequestDate',
          'localizable' => 0,
          'FKClassName' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequest',
        ) ,
        'type' => array(
          'name' => 'type',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Type') ,
          'description' => 'The type of this day, according to the values on the Leave Request Day Types Option Group',
          'maxlength' => 512,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_hrleaveandabsences_leave_request_date',
          'entity' => 'LeaveRequestDate',
          'bao' => 'CRM_HRLeaveAndAbsences_DAO_LeaveRequestDate',
          'localizable' => 0,
          'pseudoconstant' => array(
            'optionGroupName' => 'hrleaveandabsences_leave_request_day_type',
            'optionEditPath' => 'civicrm/admin/options/hrleaveandabsences_leave_request_day_type',
          )
        ) ,
      );
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }
  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }
  /**
   * Returns the names of this table
   *
   * @return string
   */
  static function getTableName() {
    return self::$_tableName;
  }
  /**
   * Returns if this table needs to be logged
   *
   * @return boolean
   */
  function getLog() {
    return self::$_log;
  }
  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  static function &import($prefix = false) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'hrleaveandabsences_leave_request_date', $prefix, array());
    return $r;
  }
  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  static function &export($prefix = false) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'hrleaveandabsences_leave_request_date', $prefix, array());
    return $r;
  }
  /**
   * Returns the list of indices
   */
  public static function indices($localize = TRUE) {
    $indices = array(
      'unique_leave_request_date' => array(
        'name' => 'unique_leave_request_date',
        'field' => array(
          0 => 'date',
          1 => 'leave_request_id',
        ) ,
        'localizable' => false,
        'unique' => true,
        'sig' => 'civicrm_hrleaveandabsences_leave_request_date::1::date::leave_request_id',
      ) ,
    );
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }
}
