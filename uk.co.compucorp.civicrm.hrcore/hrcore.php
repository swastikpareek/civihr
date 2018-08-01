<?php

require_once 'hrcore.civix.php';

use CRM_HRCore_Helper_ExtensionHelper as ExtensionHelper;
use CRM_HRCore_SearchTask_ContactFormSearchTaskAdder as ContactFormSearchTaskAdder;
use Symfony\Component\Config\FileLocator;
use CRM_HRCore_Helper_ContactActionsMenu_WorkflowActionGroup as WorkflowActionGroupHelper;
use CRM_HRCore_Service_Manager as ManagerService;
use CRM_HRContactActionsMenu_Component_Menu as ActionsMenu;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function hrcore_civicrm_config(&$config) {
  _hrcore_civix_civicrm_config($config);
  $smarty = CRM_Core_Smarty::singleton();
  array_push($smarty->plugins_dir, __DIR__ . '/CRM/Smarty/plugins');
}

/**
 * Implements hook_civicrm_searchTasks().
 *
 * @param string $objectName
 * @param array $tasks
 */
function hrcore_civicrm_searchTasks($objectName, &$tasks) {
  $taskAdders = [
    ContactFormSearchTaskAdder::class
  ];

  foreach ($taskAdders as $adder) {
    if ($adder::shouldAdd($objectName)) {
      $adder::add($tasks);
    }
  }
}

/**
 * Implements hook_civicrm_tabset().
 *
 * @param string $tabsetName
 * @param array $tabs
 * @param array $context
 */
function hrcore_civicrm_tabset($tabsetName, &$tabs, $context) {
  $listeners = [
    new CRM_HRCore_Hook_Tabset_ActivityTabModifier(),
  ];

  foreach ($listeners as $currentListener) {
    $currentListener->handle($tabsetName, $tabs, $context);
  }
}


function hrcore_civicrm_summaryActions( &$actions, $contactID ) {
  $otherActions = CRM_Utils_Array::value('otherActions', $actions, []);
  $userAdd = CRM_Utils_Array::value('user-add', $otherActions, []);

  if (empty($userAdd)) {
    return;
  }

  // replace default action with link to custom form
  $userAdd['title'] = ts('Create User Account');
  $userAdd['description'] = ts('Create User Account');
  $link = '/civicrm/user/create-account?cid=%d';
  $userAdd['href'] = sprintf($link, $contactID);
  $actions['otherActions']['user-add'] = $userAdd;
}

/**
 * Implementation of hook_addContactMenuActions to add the
 * Workflow menu group to the contact actions menu.
 *
 * @param ActionsMenu $menu
 */
function hrcore_addContactMenuActions(ActionsMenu $menu) {
  //We need to make sure that the T&A extension is enabled
  if (ExtensionHelper::isExtensionEnabled('uk.co.compucorp.civicrm.tasksassignments')) {
    $contactID = empty($_GET['cid']) ? '' : $_GET['cid'];
    if (!$contactID) {
      return;
    }

    $managerService = new ManagerService();
    $workflowActionGroup = new WorkflowActionGroupHelper($managerService, $contactID);
    $workflowActionGroup = $workflowActionGroup->get();
    $workflowActionGroup->setWeight(2);
    $menu->addToMainPanel($workflowActionGroup);
  }
}

/**
 * Implements hook_civicrm_container().
 *
 * @param ContainerBuilder $container
 */
function hrcore_civicrm_container($container) {
  $loader = new XmlFileLoader($container, new FileLocator(__DIR__));
  $loader->load('config/container/container.xml');
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function hrcore_civicrm_buildForm($formName, &$form) {
  $listeners = [
    new CRM_HRCore_Hook_BuildForm_ActivityFilterSelectFieldsModifier(),
    new CRM_HRCore_Hook_BuildForm_ActivityLinksFilter(),
    new CRM_HRCore_Hook_BuildForm_ContactAdvancedSearch(),
    new CRM_HRCore_Hook_BuildForm_LocalisationPageFilter(),
    new CRM_HRCore_Hook_BuildForm_OptionEditPathFilter(),
  ];

  foreach ($listeners as $currentListener) {
    $currentListener->handle($formName, $form);
  }
}

/**
 * Implements hook_civicrm_validateForm().
 *
 * @param string $formName
 * @param array $fields
 * @param mixed $files
 * @param object $form
 * @param array $errors
 */
function hrcore_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  $listeners = [
    new CRM_HRCore_Hook_ValidateForm_AdminFormOptionsValidation(),
  ];

  foreach ($listeners as $currentListener) {
    $currentListener->handle($formName, $fields, $files, $form, $errors);
  }
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function hrcore_civicrm_xmlMenu(&$files) {
  _hrcore_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function hrcore_civicrm_install() {
  _hrcore_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function hrcore_civicrm_postInstall() {
  _hrcore_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function hrcore_civicrm_uninstall() {
  $listeners = [
    new CRM_HRCore_Hook_Uninstall_CustomGroupRemover()
  ];

  foreach ($listeners as $listener) {
    $listener->handle();
  }

  _hrcore_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function hrcore_civicrm_enable() {
  // _hrcore_civix_civicrm_config() will add this extension to the include path.
  // We need to do this here because before extension is enabled the config
  // hook is not called for it
  _hrcore_civix_civicrm_config();

  $listeners = [
    new CRM_HRCore_Hook_Enable_CustomGroupEnabler()
  ];

  foreach ($listeners as $listener) {
    $listener->handle();
  }

  _hrcore_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function hrcore_civicrm_disable() {
  $listeners = [
    new CRM_HRCore_Hook_Disable_CustomGroupDisabler()
  ];

  foreach ($listeners as $listener) {
    $listener->handle();
  }

  _hrcore_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function hrcore_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _hrcore_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function hrcore_civicrm_managed(&$entities) {
  _hrcore_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function hrcore_civicrm_caseTypes(&$caseTypes) {
  _hrcore_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function hrcore_civicrm_angularModules(&$angularModules) {
  _hrcore_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function hrcore_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _hrcore_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_apiWrappers
 */
function hrcore_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  if ($apiRequest['action'] === 'get') {
    $wrappers[] = new CRM_HRCore_APIWrapper_DefaultLimitRemover();
  }
}

/**
 * Implements hrcore_civicrm_pageRun.
 *
 * @link https://docs.civicrm.org/dev/en/master/hooks/hook_civicrm_pageRun/
 */
function hrcore_civicrm_pageRun($page) {
  _hrcore_add_js_session_vars();

  $hooks = [
    new CRM_HRCore_Hook_PageRun_LocationTypeFilter(),
    new CRM_HRCore_Hook_PageRun_RelationshipTypesFilter(),
  ];

  foreach ($hooks as $hook) {
    $hook->handle($page);
  }
}

/**
 * Implements hook_civicrm_coreResourceList().
 */
function hrcore_civicrm_coreResourceList(&$items, $region) {
  CRM_Core_Resources::singleton()->addScriptFile('uk.co.compucorp.civicrm.hrcore', 'js/dist/hrcore.min.js', 1010);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu/
 */
function hrcore_civicrm_navigationMenu(&$params) {
  _hrcore_renameMenuLabel($params, 'Contacts', 'Staff');
  _hrcore_renameMenuLabel($params, 'Administer', 'Configure');

  _hrcore_coreMenuChanges($params);
  _hrcore_createHelpMenu($params);
  _hrcore_createDeveloperMenu($params);
  _hrcore_setDynamicMenuIcons($params);
  _hrcore_createSelfServicePortalMenu($params);
}

/**
 * Implements hook_civicrm_alterMenu().
 *
 * @param array $items
 *   List of http routes
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterMenu
 */
function hrcore_civicrm_alterMenu(&$items) {
  $items['civicrm/api']['access_arguments'] = [['access CiviCRM', 'access CiviCRM developer menu and tools'], "and"];
  $items['civicrm/styleguide']['access_arguments'] = [['access CiviCRM', 'access CiviCRM developer menu and tools'], "and"];
}

/**
 * Implements hook_civicrm_permission().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_permission/
 *
 * @param array $permissions
 */
function hrcore_civicrm_permission(&$permissions) {
  $prefix = ts('CiviHR') . ': ';
  $permissions['access CiviCRM developer menu and tools'] = ts('Access CiviCRM developer menu and tools');
  $permissions['access root menu items and configurations'] = $prefix . ts('Access root menu items and configurations');
  $permissions['view system notifications'] = $prefix . ts('View System Notifications');
}

/**
 * Removes system notifications for users without permission
 *
 * @param array $messages
 */
function hrcore_civicrm_check(&$messages) {
  $canViewSystemNotifications = CRM_Core_Permission::check('view system notifications');
  if (!$canViewSystemNotifications) {
    $messages = [];
  }
}

/**
 * Renames a menu with the given new label
 *
 * @param array $params
 * @param string $menuName
 * @param string $newLabel
 */
function _hrcore_renameMenuLabel(&$params, $menuName, $newLabel) {
  $menuItemID = _hrcore_getNavigationIdByName($menuName);
  $params[$menuItemID]['attributes']['label'] = $newLabel;
}

/**
 * This function adds the session variable to CRM.vars object.
 */
function _hrcore_add_js_session_vars() {
  CRM_Core_Resources::singleton()->addVars('session', [
    'contact_id' => CRM_Core_Session::getLoggedInContactID()
  ]);
}

/**
 * Changes to some core menu items
 *
 * @param array $params
 *  List of available menu items
 */
function _hrcore_coreMenuChanges(&$params) {
  // remove search items
  $searchNavId = _hrcore_getNavigationIdByName('Search...');
  $toRemove = [
    'Full-text search',
    'Search builder',
    'Custom searches',
    'Find Cases',
    'Find Activities',
  ];
  foreach($toRemove as $item) {
    if (
      in_array($item, ['Find Cases', 'Find Activities'])
      && !(ExtensionHelper::isExtensionEnabled('uk.co.compucorp.civicrm.tasksassignments'))
    ) {
      continue;
    }
    $itemId = _hrcore_getNavigationIdByName($item);
    unset($params[$searchNavId]['child'][$itemId]);
  }

  // remove contact items
  $searchNavId = _hrcore_getNavigationIdByName('Contacts');
  $toRemove = [
    'New Tag',
    'Manage Tags (Categories)',
    'New Activity',
    'Import Activities',
    'Contact Reports',
  ];
  foreach($toRemove as $item) {
    if (
      in_array($item, ['New Activity', 'Import Activities'])
      && !(ExtensionHelper::isExtensionEnabled('uk.co.compucorp.civicrm.tasksassignments'))
    ) {
      continue;
    }
    $itemId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', $item , 'id', 'name');
    unset($params[$searchNavId]['child'][$itemId]);
  }

  _hrcore_removeItemFromMainNavigationMenuByName('Reports', $params);
  _hrcore_removeItemFromMainNavigationMenuByName('Mailings', $params);
  _hrcore_removeItemFromMainNavigationMenuByName('Support', $params);

  // Remove Admin items
  $adminNavId = _hrcore_getNavigationIdByName('Administer');
  _hrcore_removeItemFromMainNavigationMenuByName('CiviReport', $params[$adminNavId]['child']);

  $civiCaseNavId = _hrcore_getNavigationIdByName('CiviCase');
  _hrcore_removeItemFromMainNavigationMenuByName('Redaction Rules',$params[$adminNavId]['child'][$civiCaseNavId]['child']);
}

/**
 * Removes the item with the given $name from the root of the given $menu
 *
 * @todo Either use the NavigationMenuHelper methods instead or save the navigation menu to the database.
 *
 * @param string $name
 * @param array $menu
 *   An array like the one used in hook_civicrm_navigationMenu
 */
function _hrcore_removeItemFromMainNavigationMenuByName($name, &$menu) {
  $id = _hrcore_getNavigationIdByName($name);
  unset($menu[$id]);
}

/**
 * Returns the ID of the Navigation item with the given $name
 *
 * @todo Either use the NavigationMenuHelper methods instead or save the navigation menu to the database.
 *
 * @param string $name
 *
 * @return null|int
 */
function _hrcore_getNavigationIdByName($name) {
  return CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', $name, 'id', 'name');
}

/**
 * Creates Help Menu in navigation bar
 *
 * @param array $menu
 *  List of available menu items
 */
function _hrcore_createHelpMenu(&$menu) {
  _hrcore_civix_insert_navigation_menu($menu, '', [
    'name' => ts('Help'),
    'permission' => 'access CiviCRM',
    'weight' => _hrcore_getMaxMenuWeight($menu) + 1,
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Help', [
    'name' => ts('User Guide'),
    'url' => 'http://civihr-documentation.readthedocs.io/en/latest/',
    'target' => '_blank',
    'permission' => 'access CiviCRM'
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Help', [
    'name' => ts('CiviHR website'),
    'url' => 'https://www.civihr.org/',
    'target' => '_blank',
    'permission' => 'access root menu items and configurations'
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Help', [
    'name' => ts('Get support'),
    'url' => 'https://www.civihr.org/support',
    'target' => '_blank',
    'permission' => 'access CiviCRM'
  ]);
}

/**
 * Creates Developer Menu in navigation bar
 *
 * @param array $menu
 *   List of available menu items
 */
function _hrcore_createDeveloperMenu(&$menu) {
  _hrcore_civix_insert_navigation_menu($menu, '', [
    'name' => ts('Developer'),
    'permission' => 'access CiviCRM,access CiviCRM developer menu and tools',
    'operator' => 'AND',
    'weight' => _hrcore_getMaxMenuWeight($menu) + 1,
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Developer', [
    'name' => ts('API Explorer'),
    'url' => 'civicrm/api',
    'target' => '_blank',
    'permission' => 'access CiviCRM,access CiviCRM developer menu and tools',
    'operator' => 'AND'
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Developer', [
    'name' => ts('Developer Docs'),
    'target' => '_blank',
    'url' => 'https://civihr.atlassian.net/wiki/spaces/CIV/pages',
    'permission' => 'access CiviCRM,access CiviCRM developer menu and tools',
    'operator' => 'AND'
  ]);

  _hrcore_civix_insert_navigation_menu($menu, 'Developer', [
    'name' => ts('Style Guide'),
    'target' => '_blank',
    'url' => 'https://www.civihr.org/support',
    'permission' => 'access CiviCRM,access CiviCRM developer menu and tools',
    'operator' => 'AND'
  ]);

  // Adds sub menu under Style Guide menu
  if(!ExtensionHelper::isExtensionEnabled('org.civicrm.styleguide')) {
    return;
  }

  foreach (Civi::service('style_guides')->getAll() as $styleGuide) {
    _hrcore_civix_insert_navigation_menu($menu, 'Developer/Style Guide', [
      'label' => $styleGuide['label'],
      'name' => $styleGuide['name'],
      'url' => 'civicrm/styleguide/' . $styleGuide['name'],
      'permission' => 'access CiviCRM,access CiviCRM developer menu and tools',
      'operator' => 'AND'
    ]);
  }
}

/**
 * Adds icons to dynamically defined menu items
 *
 * @param array $menu
 *   List of available menu items
 */
function _hrcore_setDynamicMenuIcons(&$menu) {
  $menuToIcons = [
    'Help' => 'crm-i fa-question-circle',
    'Developer'=> 'crm-i fa-code',
  ];

  foreach ($menu as $key => $item) {
    $menuName = $item['attributes']['name'];
    if (array_key_exists($menuName, $menuToIcons)) {
      $menu[$key]['attributes']['icon'] = $menuToIcons[$menuName];
    }
  }
}

/**
 * Creates the Self Service Portal menu item and add it to the main navigation
 *
 * @param array $menu
 *   List of available menu items
 */
function _hrcore_createSelfServicePortalMenu(&$menu) {
  _hrcore_civix_insert_navigation_menu($menu, '', [
    'name' => ts('ssp'),
    'label' => ts('Self Service Portal'),
    'url' => 'dashboard',
    'weight' => _hrcore_getMaxMenuWeight($menu) + 1,
  ]);
}

/**
 * Returns the maximum weight among all the menu items in the given
 * $menu array.
 *
 * @param array $menu
 *   An array in the same format as the one used by hook_civicrm_navigationMenu
 *
 * @return int
 */
function _hrcore_getMaxMenuWeight($menu) {
  $maxWeight = 0;

  foreach ($menu as $item) {
    $itemWeight = !empty($item['attributes']['weight']) ? $item['attributes']['weight'] : 0;
    if ($itemWeight > $maxWeight) {
      $maxWeight = $itemWeight;
    }
  }

  return $maxWeight;
}
