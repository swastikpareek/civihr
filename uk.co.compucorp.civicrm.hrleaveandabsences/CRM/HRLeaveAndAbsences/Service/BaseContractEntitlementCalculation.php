<?php

use CRM_HRLeaveAndAbsences_BAO_AbsencePeriod as AbsencePeriod;
use CRM_HRLeaveAndAbsences_BAO_AbsenceType as AbsenceType;
use CRM_HRLeaveAndAbsences_BAO_PublicHoliday as PublicHoliday;
use CRM_HRLeaveAndAbsences_Service_ContractEntitlementCalculation as ContractEntitlementCalculation;

abstract class CRM_HRLeaveAndAbsences_Service_BaseContractEntitlementCalculation
  implements ContractEntitlementCalculation {

  /**
   * @var bool|array
   *   Caches the value returned by the getJobLeave method()
   *   When false, it means the value hasn't been loaded yet
   */
  private $jobLeave = false;

  /**
   * @var int
   *   Caches the value returned by the getNumberOfPublicHolidaysInEntitlement()
   *   method
   */
  private $numberOfPublicHolidaysInEntitlement = null;

  /**
   * @var array
   *   This calculation's Contract
   */
  protected $contract;

  /**
   * @var \CRM_HRLeaveAndAbsences_BAO_AbsencePeriod
   *  This calculation's Absence Period
   */
  protected $period;

  /**
   * @var \CRM_HRLeaveAndAbsences_BAO_AbsenceType
   *  This calculation's Absence Type
   */
  protected $absenceType;

  /**
   * Creates a new Contract Entitlement Calculation based on the give Absence
   * Period, Contract and AbsenceType
   *
   * @param \CRM_HRLeaveAndAbsences_BAO_AbsencePeriod $period
   * @param array $contract
   *  An array representing the contract. The contract must have the
   *  period_start_date and period_end_date
   * @param \CRM_HRLeaveAndAbsences_BAO_AbsenceType $type
   */
  public function __construct(AbsencePeriod $period, array $contract, AbsenceType $type) {
    $this->contract = $contract;
    $this->period = $period;
    $this->absenceType = $type;
  }

  /**
   * @inheritdoc
   */
  public function getContractualEntitlement() {
    $jobLeave = $this->getJobLeave();

    if(!$jobLeave) {
      return 0;
    }

    return (float)$jobLeave['leave_amount'];
  }

  /**
   * @inheritdoc
   */
  public function getNumberOfPublicHolidaysInEntitlement() {
    if(is_null($this->numberOfPublicHolidaysInEntitlement)) {
      $this->numberOfPublicHolidaysInEntitlement = 0;

      $jobLeave = $this->getJobLeave();

      if(!empty($jobLeave['add_public_holidays'])) {
        $this->numberOfPublicHolidaysInEntitlement = PublicHoliday::getCountForPeriod(
          $this->contract['period_start_date'],
          $this->contract['period_end_date']
        );
      }
    }

    return $this->numberOfPublicHolidaysInEntitlement;
  }

  /**
   * @inheritdoc
   */
  public function getPublicHolidaysInEntitlement() {
    $jobLeave = $this->getJobLeave();

    if(!empty($jobLeave['add_public_holidays'])) {
      return PublicHoliday::getAllForPeriod(
        $this->contract['period_start_date'],
        $this->contract['period_end_date']
      );
    }

    return [];
  }

  /**
   * @inheritdoc
   */
  public function getContractStartDate() {
    return $this->contract['period_start_date'];
  }

  /**
   * @inheritdoc
   */
  public function getContractEndDate() {
    return $this->contract['period_end_date'];
  }

  /**
   * Returns an array with the values of the JobLeave for the given contract and
   * the calculation's  absence type.
   *
   * @return array|null
   *   An array with the JobLeave fields or null if there's no JobLeave for this AbsenceType
   */
  private function getJobLeave() {
    if($this->jobLeave === false) {
      try {
        $this->jobLeave = civicrm_api3('HRJobLeave', 'getsingle', array(
          'jobcontract_id' => (int)$this->contract['id'],
          'leave_type' => (int)$this->absenceType->id
        ));
      } catch(CiviCRM_API3_Exception $ex) {
        $this->jobLeave = null;
      }
    }

    return $this->jobLeave;
  }
}
