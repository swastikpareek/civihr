<?php

use CRM_HRLeaveAndAbsences_BAO_LeaveRequest as LeaveRequest;
use CRM_HRLeaveAndAbsences_BAO_TOILRequest as TOILRequest;
use CRM_HRLeaveAndAbsences_Test_Fabricator_LeaveBalanceChange as LeaveBalanceChangeFabricator;

class CRM_HRLeaveAndAbsences_Test_Fabricator_TOILRequest {

  private static $leaveRequestStatuses;
  private static $toilAmounts;

  public static function fabricate($params, $withBalanceChanges = false) {
    $params = self::mergeDefaultParams($params);
    $toilRequest =  TOILRequest::create($params);

    if($withBalanceChanges) {
     self::createBalanceChange($toilRequest, $params);
    }

    return $toilRequest;
  }

  /**
   * Creates a new TOIL Request without running any validation
   *
   * @param array $params
   * @param boolean $withBalanceChanges
   *
   * @return \CRM_HRLeaveAndAbsences_BAO_TOILRequest
   */
  public static function fabricateWithoutValidation($params = [], $withBalanceChanges = false) {
    $params = self::mergeDefaultParams($params);
    $toilRequest =  TOILRequest::create($params, false);

    if($withBalanceChanges) {
      self::createBalanceChange($toilRequest, $params);
    }

    return $toilRequest;

  }

  private static function mergeDefaultParams($params) {
    $defaultParams = [
      'status_id' => self::getStatusValue('Approved'),
      'toil_to_accrue' => self::getToilToAccrueValue('1 Day')
    ];

    return array_merge($defaultParams, $params);
  }

  private static function getStatusValue($statusLabel) {
    if(is_null(self::$leaveRequestStatuses)) {
      self::$leaveRequestStatuses = array_flip(LeaveRequest::buildOptions('status_id'));
    }

    return self::$leaveRequestStatuses[$statusLabel];
  }

  private static function getToilToAccrueValue($reasonLabel) {
    if(is_null(self::$toilAmounts)) {
      $result = civicrm_api3('OptionValue', 'get', ['option_group_id' => 'hrleaveandabsences_toil_amounts']);
      foreach($result['values'] as $value) {
        self::$toilAmounts[$value['label']] = $value['value'];
      }
    }

    return self::$toilAmounts[$reasonLabel];
  }

  private static function createBalanceChange(TOILRequest $toilRequest, $params) {
    $expiryDate = null;
    if(!empty($params['expiry_date'])) {
      $expiryDate = new DateTime($params['expiry_date']);
    }
    LeaveBalanceChangeFabricator::fabricateForTOIL($toilRequest, $params['toil_to_accrue'], $expiryDate);
  }
}