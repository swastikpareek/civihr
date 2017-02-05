({
  baseUrl : 'src',
  out: 'dist/reqangular.min.js',
  uglify: {
    no_mangle: true,
    max_line_length: 1000
  },
  paths: {
    'common/angular': 'common/vendor/angular/angular',
    'common/angularAnimate': 'common/vendor/angular-animate/angular-animate',
    'common/angularBootstrap': 'common/vendor/angular-bootstrap/ui-bootstrap',
    'common/angularFileUpload': 'common/vendor/angular-file-upload/angular-file-upload',
    'common/angularMocks': 'common/vendor/angular-mocks/angular-mocks',
    'common/angularResource': 'common/vendor/angular-resource/angular-resource',
    'common/angularRoute': 'common/vendor/angular-route/angular-route',
    'common/angularUiRouter': 'common/vendor/angular-ui-router/release/angular-ui-router',
    'common/angularXeditable': 'common/vendor/angular-xeditable/dist/js/xeditable',
    'common/d3': 'common/vendor/d3/d3',
    'common/lodash': 'common/vendor/lodash/lodash',
    'common/moment': 'common/vendor/moment/min/moment.min',
    'common/rangy-core': 'common/vendor-custom/rangy-core',
    'common/rangy-selectionsaverestore': 'common/vendor-custom/rangy-selectionsaverestore',
    'common/require': 'common/vendor/requirejs/require',
    'common/text-angular': 'common/vendor-custom/textAngular',
    'common/text-angular-sanitize': 'common/vendor/textAngular/src/textAngular-sanitize',
    'common/text-angular-setup': 'common/vendor/textAngular/src/textAngularSetup',
    'common/ui-select': 'common/vendor/ui-select/dist/select',
    'common/vendor/perfect-scrollbar': 'common/vendor/perfect-scrollbar/js/perfect-scrollbar',
    'common/mocks': '../test/mocks'
  },
  shim: {
    'common/angular': {
      exports: 'angular'
    },
    'common/angularAnimate': {
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
    'common/angularXeditable': {
      deps: ['common/angular']
    },
    'common/text-angular': {
      deps: [
        'common/rangy-core',
        'common/rangy-selectionsaverestore',
        'common/text-angular-sanitize',
        'common/text-angular-setup'
      ]
    },
    'common/ui-select': {
      deps: ['common/angular']
    }
  },
  include: [
    'common/bundles/vendors',
    'common/bundles/apis',
    'common/bundles/services',
    'common/bundles/directives',
    'common/bundles/angular-date',
    'common/bundles/routers',
    'common/bundles/models',
    'common/modules/dialog',
    'common/modules/templates',
    'common/modules/xeditable-civi'
  ]
})
