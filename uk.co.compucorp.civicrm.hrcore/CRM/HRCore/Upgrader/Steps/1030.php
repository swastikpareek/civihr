<?php

trait CRM_HRCore_Upgrader_Steps_1030 {

  /**
   * Relabel Line Manager Relationship
   */
  public function upgrade_1030() {
    $this->up1030_reLabelLineManagerRelationship();

    return TRUE;
  }

  /**
   * ReLabeling Line Manager Relationship
   */
  private function up1030_reLabelLineManagerRelationship() {
    $relationshipType = civicrm_api3('RelationshipType', 'get', [
      'name_b_a' => 'Line Manager',
    ]);
    if (empty($relationshipType['id'])) {
      return;
    }

    civicrm_api3('RelationshipType', 'create', [
      'id' => $relationshipType['id'],
      'label_a_b' => 'Is Line Managed by',
      'label_b_a' => 'Is Line Manager of',
    ]);
  }

}
