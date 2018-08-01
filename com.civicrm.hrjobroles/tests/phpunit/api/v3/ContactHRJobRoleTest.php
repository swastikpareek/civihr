<?php

use CRM_HRCore_Test_Fabricator_Contact as ContactFabricator;
use CRM_Hrjobcontract_Test_Fabricator_HRJobContract as HRJobContractFabricator;
use CRM_Hrjobroles_BAO_HrJobRoles as HrJobRoles;
use CRM_Hrjobroles_Test_Fabricator_HrJobRoles as HRJobRolesFabricator;

/**
 * Class api_v3_ContactHRJobRoleTest
 *
 * @group headless
 */
class api_v3_ContactHRJobRoleTest extends CRM_Hrjobroles_Test_BaseHeadlessTest {

  private $entity = 'ContactHrJobRoles';
  private $action = 'get';

  public function testAccessAjaxPermissionIsRequiredToAccessTheGetAction() {
    $contactID = 1;
    $this->registerCurrentLoggedInContactInSession($contactID);

    CRM_Core_Config::singleton()->userPermissionClass->permissions = [];

    $this->setExpectedApiPermissionException('access AJAX API');
    civicrm_api3($this->entity, $this->action, ['check_permissions' => true]);
  }

  public function testTheGetActionReturnsOnlyTheAllowedFields() {
    $departments = HrJobRoles::buildOptions('department', 'validate');
    $locations = HrJobRoles::buildOptions('location', 'validate');
    $levelTypes = HrJobRoles::buildOptions('level_type', 'validate');

    $contact = ContactFabricator::fabricate();
    $contract = HRJobContractFabricator::fabricate(['contact_id' => $contact['id']]);
    $jobRole = HRJobRolesFabricator::fabricate([
      'title' => 'Title',
      'description' => 'Description',
      'start_date' => date('Y-m-d'),
      'funder' => 'asddsa',
      'job_contract_id' => $contract['id'],
      'department' => $departments['IT'],
      'location' => $locations['Home'],
      'level_type' => $levelTypes['Senior Manager']
    ]);

    $contactJobRoles = civicrm_api3($this->entity, $this->action)['values'];

    // Even though the description, start_date and funder were set,
    // they won't be returned here, as they are not part of the allowed fields
    $expected = [
      $jobRole['id'] => [
        'id' => $jobRole['id'],
        'title' => $jobRole['title'],
        'department' => $jobRole['department'],
        'location' => $jobRole['location'],
        'level_type' => $jobRole['level_type'],
        'contact_id' => $contact['id'],
      ]
    ];
    $this->assertEquals($expected, $contactJobRoles);
  }

  public function testTheGetActionDoesntReturnsNotAllowedFieldsEvenWhenSpecified() {
    $contact = ContactFabricator::fabricate();
    $contract = HRJobContractFabricator::fabricate(['contact_id' => $contact['id']]);
    $jobRole = HRJobRolesFabricator::fabricate([
      'title' => 'Title',
      'description' => 'Description',
      'start_date' => date('Y-m-d'),
      'funder' => 'asddsa',
      'job_contract_id' => $contract['id'],
    ]);

    $contactJobRoles = civicrm_api3(
      $this->entity,
      $this->action,
      [
        'return' => [
          'description',
          'start_date',
          'funder',
          'cost_center'
        ]
      ]
    )['values'];

    // Since all the fields we asked are not allowed, the response will be empty,
    // except for the ID, which is always returned
    $expected = [
      $jobRole['id'] => [
        'id' => $jobRole['id'],
      ]
    ];
    $this->assertEquals($expected, $contactJobRoles);
  }

  public function testTheGetActionReturnsMultipleJobRoles() {
    $contact1 = ContactFabricator::fabricate();
    $contact2 = ContactFabricator::fabricate();
    $contract1 = HRJobContractFabricator::fabricate(['contact_id' => $contact1['id']]);
    $contract2 = HRJobContractFabricator::fabricate(['contact_id' => $contact2['id']]);
    $jobRole1 = HRJobRolesFabricator::fabricate(['job_contract_id' => $contract1['id'], 'start_date' => date('Y-m-d H:i:s'),]);
    $jobRole2 = HRJobRolesFabricator::fabricate(['job_contract_id' => $contract1['id'], 'start_date' => date('Y-m-d H:i:s'),]);
    $jobRole3 = HRJobRolesFabricator::fabricate(['job_contract_id' => $contract2['id'], 'start_date' => date('Y-m-d H:i:s'),]);

    $contactJobRoles = civicrm_api3($this->entity, $this->action)['values'];

    $this->assertCount(3, $contactJobRoles);

    // The CiviCRM only returns non-empty fields. Since we only set the job
    // contract id, only the ID and the contact_id fields will be returned
    $expectedJobRole1 = ['id' => $jobRole1['id'], 'contact_id' => $contact1['id']];
    $expectedJobRole2 = ['id' => $jobRole2['id'], 'contact_id' => $contact1['id']];
    $expectedJobRole3 = ['id' => $jobRole3['id'], 'contact_id' => $contact2['id']];

    $this->assertEquals($expectedJobRole1, $contactJobRoles[$jobRole1['id']]);
    $this->assertEquals($expectedJobRole2, $contactJobRoles[$jobRole2['id']]);
    $this->assertEquals($expectedJobRole3, $contactJobRoles[$jobRole3['id']]);
  }

  /**
   * @expectedException CiviCRM_API3_Exception
   * @expectedExceptionMessage The contact_id parameter only supports the IN operator
   *
   * @dataProvider invalidGetContactIdOperators
   */
  public function testGetThrowsExceptionForOperatorThatIsNotTheINOperatorForContactIDParameter($operator) {
    civicrm_api3('ContactHrJobRoles', 'get', ['contact_id' => [$operator => [1]]]);
  }

  public function testGetDoesNotThrowExceptionWhenUsingTheINOperatorForTheContactIdParameter() {
    $values = civicrm_api3('ContactHrJobRoles', 'get', ['contact_id' => ['IN' => [1]]]);

    $this->assertEquals(0, $values['is_error']);
  }

  public function testGetDoesNotThrowExceptionWhenUsingTheEqualsToOperatorForTheContactIdParameter() {
    $values = civicrm_api3('ContactHrJobRoles', 'get', ['contact_id' => 1]);

    $this->assertEquals(0, $values['is_error']);
  }

  public function testGetReturnsJobRolesForPassedInContacts() {
    $contact1 = ContactFabricator::fabricate();
    $contact2 = ContactFabricator::fabricate();
    $contact3 = ContactFabricator::fabricate();
    $contract1 = HRJobContractFabricator::fabricate(['contact_id' => $contact1['id']]);
    $contract2 = HRJobContractFabricator::fabricate(['contact_id' => $contact2['id']]);
    $contract3 = HRJobContractFabricator::fabricate(['contact_id' => $contact3['id']]);
    $jobRole1 = HRJobRolesFabricator::fabricate(['job_contract_id' => $contract1['id'], 'start_date' => date('Y-m-d H:i:s'),]);
    $jobRole2 = HRJobRolesFabricator::fabricate(['job_contract_id' => $contract2['id'], 'start_date' => date('Y-m-d H:i:s'),]);
    $jobRole3 = HRJobRolesFabricator::fabricate(['job_contract_id' => $contract3['id'], 'start_date' => date('Y-m-d H:i:s'),]);

    //Get Jobroles for contact1 and contact3
    $contactJobRoles = civicrm_api3($this->entity, $this->action, [
      'contact_id' => ['IN' => [$contact1['id'], $contact3['id']]]
    ])['values'];

    $this->assertCount(2, $contactJobRoles);

    // The CiviCRM only returns non-empty fields. Since we only set the job
    // contract id, only the ID and the contact_id fields will be returned
    $expectedJobRole1 = ['id' => $jobRole1['id'], 'contact_id' => $contact1['id']];
    $expectedJobRole3 = ['id' => $jobRole3['id'], 'contact_id' => $contact3['id']];

    $this->assertEquals($expectedJobRole1, $contactJobRoles[$jobRole1['id']]);
    $this->assertEquals($expectedJobRole3, $contactJobRoles[$jobRole3['id']]);

    //Get Jobroles for contact2 only
    $contactJobRoles = civicrm_api3($this->entity, $this->action, [
      'contact_id' => $contact2['id']
    ])['values'];

    $this->assertCount(1, $contactJobRoles);
    $expectedJobRole2 = ['id' => $jobRole2['id'], 'contact_id' => $contact2['id']];
    $this->assertEquals($expectedJobRole2, $contactJobRoles[$jobRole2['id']]);
  }

  private function setExpectedApiPermissionException($permission) {
    $message = "API permission check failed for {$this->entity}/{$this->action} call; insufficient permission: require {$permission}";
    $this->setExpectedException('CiviCRM_API3_Exception', $message);
  }

  private function registerCurrentLoggedInContactInSession($contactID) {
    $session = CRM_Core_Session::singleton();
    $session->set('userID', $contactID);
  }

  public function invalidGetContactIdOperators() {
    return [
      ['>'],
      ['>='],
      ['<='],
      ['<'],
      ['<>'],
      ['!='],
      ['BETWEEN'],
      ['NOT BETWEEN'],
      ['LIKE'],
      ['NOT LIKE'],
      ['NOT IN'],
      ['IS NULL'],
      ['IS NOT NULL'],
    ];
  }
}
