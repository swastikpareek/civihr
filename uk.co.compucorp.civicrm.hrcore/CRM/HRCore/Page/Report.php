<?php

class CRM_HRCore_Page_Report extends CRM_Core_Page {

  /**
   * {@inheritdoc}
   */
  public function run() {
    CRM_Core_Resources::singleton()->addScriptUrl('https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.5.15/ie8.polyfils.min.js');
    CRM_Core_Resources::singleton()->addScriptUrl('https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.5.15/iframeResizer.min.js');

    return parent::run();
  }
}
