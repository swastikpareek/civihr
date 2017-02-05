var TEST_REGEXP = /(spec|test)\.js$/i;
var allTestFiles = [];
var extPath = '/base/tools/extensions/civihr/org.civicrm.reqangular';
var mocksPath = extPath + '/test/mocks';
var srcPath = extPath + '/src/common';

Object.keys(window.__karma__.files).forEach(function(file) {
  if (TEST_REGEXP.test(file)) {
    allTestFiles.push(file);
  }
});

require.config({
  deps: allTestFiles,
  waitSeconds: 60,
  shim: {
    'common/angular': {
      exports: 'angular'
    },
    'common/angularAnimate': {
      deps: ['common/angular']
    },
    'common/angular-date': {
      deps: ['common/angular']
    },
    'common/angularBootstrap': {
      deps: ['common/angular']
    },
    'common/angularMocks': {
      deps: ['common/angular']
    },
    'common/angularResource': {
      deps: ['common/angular']
    },
    'common/angularRoute': {
      deps: ['common/angular']
    },
    'common/angularUiRouter': {
      deps: ['common/angular']
    },
    'common/angularUiRouter': {
      deps: ['common/angular']
    },
    'common/ui-select': {
      deps: [
        'common/angular',
        'common/text-angular-sanitize'
      ]
    }
  },
  paths: {
    'common': srcPath,
    'common/mocks': mocksPath,
    'common/angular': srcPath + '/vendor/angular/angular.min',
    'common/angularRoute': srcPath + '/vendor/angular-route/angular-route.min',
    'common/angularUiRouter': srcPath + '/vendor/angular-ui-router/release/angular-ui-router.min',
    'common/angularMocks': srcPath + '/vendor/angular-mocks/angular-mocks',
    'common/angularBootstrap': srcPath + '/vendor/angular-bootstrap/ui-bootstrap-tpls.min',
    'common/lodash': srcPath + '/vendor/lodash/lodash.min',
    'common/moment': srcPath + '/vendor/moment/min/moment.min',
    'common/text-angular-sanitize': srcPath + '/vendor/textAngular/dist/textAngular-sanitize.min',
    'common/ui-select': srcPath + '/vendor/ui-select/dist/select.min',
    'common/vendor/perfect-scrollbar': srcPath + '/vendor/perfect-scrollbar/js/perfect-scrollbar.min',
  },
  callback: function () {
    // Simple hack to provide value to CRM.vars.reqangular.baseURL
    CRM.vars = { reqangular: { baseURL: extPath } };

    window.__karma__.start();
  }
});
