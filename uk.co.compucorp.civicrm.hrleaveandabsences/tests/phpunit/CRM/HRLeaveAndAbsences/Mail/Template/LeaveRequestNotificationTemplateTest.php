<?php

use CRM_HRLeaveAndAbsences_Mail_Template_LeaveRequestNotification as LeaveRequestNotificationTemplate;
use CRM_HRLeaveAndAbsences_Service_LeaveRequestComment as LeaveRequestCommentService;
use CRM_HRLeaveAndAbsences_BAO_LeaveRequest as LeaveRequest;
use CRM_HRLeaveAndAbsences_Test_Fabricator_LeaveRequest as LeaveRequestFabricator;
use CRM_HRLeaveAndAbsences_Test_Fabricator_AbsenceType as AbsenceTypeFabricator;

/**
 * Class CRM_HRLeaveAndAbsences_Mail_LeaveRequestNotificationTemplateTest
 *
 * @group headless
 */
class CRM_HRLeaveAndAbsences_Mail_Template_LeaveRequestNotificationTemplateTest extends BaseHeadlessTest {

  use CRM_HRLeaveAndAbsences_LeaveRequestHelpersTrait;
  use CRM_HRLeaveAndAbsences_LeaveManagerHelpersTrait;
  use CRM_HRLeaveAndAbsences_MailHelpersTrait;

  private $leaveRequestNotificationTemplate;

  public function setUp() {
    CRM_Core_DAO::executeQuery('SET foreign_key_checks = 0;');
    $leaveRequestCommentService = new LeaveRequestCommentService();
    $this->leaveRequestNotificationTemplate = new LeaveRequestNotificationTemplate($leaveRequestCommentService);
  }

  public function testGetTemplateIDReturnsTheCorrectID() {
    $templateDetails = $this->getTemplateDetails(['msg_title' => 'CiviHR Leave Request Notification']);
    $templateID = $this->leaveRequestNotificationTemplate->getTemplateID();
    $this->assertEquals($templateID, $templateDetails['id']);
  }

  public function testGetTemplateParametersReturnsTheExpectedParametersForTheTemplate() {
    $absenceType = AbsenceTypeFabricator::fabricate(['title' => 'Vacation/Holiday']);
    $leaveRequest = LeaveRequestFabricator::fabricateWithoutValidation([
      'type_id' => $absenceType->id,
      'contact_id' =>2,
      'from_date' => CRM_Utils_Date::processDate('tomorrow'),
      'to_date' => CRM_Utils_Date::processDate('tomorrow'),
    ], false);

    //create 2 attachments for leaveRequest
    $attachment1 = $this->createAttachmentForLeaveRequest([
      'entity_id' => $leaveRequest->id,
      'name' => 'LeaveRequestSampleFile1.txt'
    ]);

    $attachment2 = $this->createAttachmentForLeaveRequest([
      'entity_id' => $leaveRequest->id,
      'name' => 'LeaveRequestSampleFile2.txt'
    ]);

    //add two comments for the leave request
    $params = [
      'leave_request_id' => $leaveRequest->id,
      'text' => 'Random Commenter',
      'contact_id' => $leaveRequest->contact_id,
      'sequential' => 1
    ];

    $this->createCommentForLeaveRequest($params);
    $this->createCommentForLeaveRequest(array_merge($params, ['text' => 'Sample text']));

    $tplParams = $this->leaveRequestNotificationTemplate->getTemplateParameters($leaveRequest);

    $leaveRequestDayTypes = LeaveRequest::buildOptions('from_date_type');
    $leaveRequestStatuses = LeaveRequest::buildOptions('status_id');
    $dateTimeNow = new DateTime('now');

    //validate template parameters
    $this->assertEquals($tplParams['toDate'], $leaveRequest->to_date);
    $this->assertEquals($tplParams['fromDate'], $leaveRequest->from_date);
    $this->assertEquals($tplParams['leaveRequest'], $leaveRequest);
    $this->assertEquals($tplParams['fromDateType'], $leaveRequestDayTypes[$leaveRequest->from_date_type]);
    $this->assertEquals($tplParams['toDateType'], $leaveRequestDayTypes[$leaveRequest->to_date_type]);
    $this->assertEquals($tplParams['leaveStatus'], $leaveRequestStatuses[$leaveRequest->status_id]);
    $this->assertEquals($tplParams['currentDateTime'], $dateTimeNow, '', 10);
    $this->assertEquals($tplParams['absenceTypeName'], $absenceType->title);

    //There are two attachments for the leave request
    $this->assertCount(2, $tplParams['leaveFiles']);
    $leaveFileNames = array_column($tplParams['leaveFiles'], 'name');
    sort($leaveFileNames);

    $this->assertEquals($leaveFileNames, ['LeaveRequestSampleFile1.txt', 'LeaveRequestSampleFile2.txt']);

    //there are two comments for the leave request
    $this->assertCount(2, $tplParams['leaveComments']);
    $leaveCommentText = array_column($tplParams['leaveComments'], 'text');
    sort($leaveCommentText);

    $this->assertEquals($leaveCommentText, ['Random Commenter', 'Sample text']);
  }
}
