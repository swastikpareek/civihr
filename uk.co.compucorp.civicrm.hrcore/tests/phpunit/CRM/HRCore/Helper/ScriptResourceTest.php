<?php

use CRM_HRCore_Helper_ScriptResource as ScriptResource;

/**
 * @group headless
 */
class CRM_HRCore_Helper_ScriptResourceTest extends CRM_HRCore_Test_BaseHeadlessTest {

  public function testReqangularScriptIsLoadedAfterPrimaryScripts() {
    $this->assertTrue(ScriptResource::REQANGULAR_LOAD_WEIGHT > CRM_Core_Resources::DEFAULT_WEIGHT);
  }

  public function testAMDScriptsAreLoadedAfterReqangular() {
    $this->assertTrue(ScriptResource::AMD_MODULE_LOAD_WEIGHT > ScriptResource::REQANGULAR_LOAD_WEIGHT);
  }
}
