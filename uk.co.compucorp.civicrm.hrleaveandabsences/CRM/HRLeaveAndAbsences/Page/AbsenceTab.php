<?php

/**
 * CRM_HRLeaveAndAbsences_Page_AbsenceTab
 */
class CRM_HRLeaveAndAbsences_Page_AbsenceTab extends  CRM_Core_Page {
  public function run() {
    CRM_Utils_System::setTitle(ts('Absence'));

    CRM_Core_Resources::singleton()->addPermissions([
      'access leave and absences',
      'administer leave and absences',
      'access leave and absences in ssp',
      'manage leave and absences in ssp',
    ]);

    CRM_Core_Resources::singleton()
      ->addStyleFile('uk.co.compucorp.civicrm.hrleaveandabsences',
        'css/leaveandabsence.css')
      ->addScriptFile('uk.co.compucorp.civicrm.hrleaveandabsences',
        'js/dist/absence-tab.min.js', CRM_HRCore_Helper_ScriptResource::AMD_MODULE_LOAD_WEIGHT);

    parent::run();
  }
}
