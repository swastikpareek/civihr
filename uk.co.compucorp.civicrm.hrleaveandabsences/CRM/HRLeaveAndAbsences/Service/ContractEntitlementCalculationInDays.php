<?php

use CRM_HRLeaveAndAbsences_Service_BaseContractEntitlementCalculation as BaseContractEntitlementCalculation;

/**
 * Class CRM_HRLeaveAndAbsences_Service_ContractEntitlementCalculationInDays
 */
class CRM_HRLeaveAndAbsences_Service_ContractEntitlementCalculationInDays
  extends BaseContractEntitlementCalculation {

  /**
   * Calculates the Pro Rata for the contract, which is given by:
   *
   * (CE * (WDTW / WD)) + Number of Public Holidays
   *
   * Where:
   * CE: Contractual Entitlement
   * WDTW: No. Working Days to Work
   * WD: No. Working Days
   *
   * @return float
   */
  public function getProRata() {
    $numberOfWorkingDaysToWork = $this->getNumberOfWorkingDaysToWork();
    $numberOfWorkingDays = $this->getNumberOfWorkingDays();

    $contractualEntitlement = $this->getContractualEntitlement();

    $proRata = ($numberOfWorkingDaysToWork / $numberOfWorkingDays) * $contractualEntitlement;

    return $proRata + $this->getNumberOfPublicHolidaysInEntitlement();
  }

  /**
   * Returns the number of working days for this contract in this calculation's
   * period
   *
   * @return int
   */
  public function getNumberOfWorkingDaysToWork() {
    return $this->period->getNumberOfWorkingDaysToWork(
      $this->contract['period_start_date'],
      $this->contract['period_end_date']
    );
  }

  /**
   * Returns the number of working days for this calculation's period
   *
   * @return int
   */
  public function getNumberOfWorkingDays() {
    return $this->period->getNumberOfWorkingDays();
  }

  /**
   * Returns the amount of working time (days, hours, etc) available during this
   * calculation's Absence Period
   *
   * @return int
   */
  public function getAmountOfWorkingTime() {
    // TODO: Implement getAmountOfWorkingTime() method.
  }

  /**
   * Returns the amount of working time (days, hours, etc) that will be worked
   * within this contract period
   *
   * @return int
   */
  public function getAmountOfWorkingTimeToWork() {
    // TODO: Implement getAmountOfWorkingTimeToWork() method.
  }
}
