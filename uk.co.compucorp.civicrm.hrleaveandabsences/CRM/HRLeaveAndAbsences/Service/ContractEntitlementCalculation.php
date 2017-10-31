<?php

/**
 * Interface CRM_HRLeaveAndAbsences_Service_ContractEntitlementCalculation
 */
interface CRM_HRLeaveAndAbsences_Service_ContractEntitlementCalculation {

  /**
   * Calculates the Pro Rata for the contract.
   *
   * The Pro Rata is the portion of time this contract will add to total period
   * entitlement
   *
   * @return float
   */
  public function getProRata();

  /**
   * Returns the amount of working time (days, hours, etc) available during this
   * calculation's Absence Period
   *
   * @return int
   */
  public function getAmountOfWorkingTime();

  /**
   * Returns the amount of working time (days, hours, etc) that will be worked
   * within this contract period
   *
   * @return int
   */
  public function getAmountOfWorkingTimeToWork();

  /**
   * Returns the contractual entitlement for this calculation's contract,
   * based on the Job Leave settings
   *
   * @return float
   */
  public function getContractualEntitlement();

  /**
   * Returns the number of Public Holidays added to the entitlement because of
   * contract with "Add Public Holiday?" set.
   *
   * @return int
   */
  public function getNumberOfPublicHolidaysInEntitlement();

  /**
   * Returns a list of PublicHolidays instances representing the Public Holidays
   * added to the entitlement.
   *
   * @return \CRM_HRLeaveAndAbsences_BAO_PublicHoliday[]
   */
  public function getPublicHolidaysInEntitlement();

  /**
   * Returns the start date of this calculation's contract
   *
   * @return string
   */
  public function getContractStartDate();

  /**
   * Returns the end date of this calculation's contract
   *
   * @return string
   */
  public function getContractEndDate();
}
