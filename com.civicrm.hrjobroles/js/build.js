({
  baseUrl : 'src',
  out: 'dist/job-roles.min.js',
  name: 'job-roles',
  skipModuleInsertion: true,
  paths: {
    'common': 'empty:',
    'job-roles/vendor/angular-editable': 'job-roles/vendor/angular-xeditable/dist/js/xeditable.min',
    'job-roles/vendor/angular-filter': 'job-roles/vendor/angular-filter/dist/angular-filter.min'
  }
})
