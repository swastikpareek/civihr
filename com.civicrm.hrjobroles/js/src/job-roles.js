(function (CRM, require) {
  var extPath = CRM.vars.hrjobroles.baseURL + '/js/src/job-roles';

  require.config({
    paths: {
      'job-roles': extPath,
      'job-roles/vendor/angular-editable': extPath + '/vendor/angular-xeditable/dist/js/xeditable.min',
      'job-roles/vendor/angular-filter': extPath + '/vendor/angular-filter/dist/angular-filter.min'
    }
  });

  require(['job-roles/app'], function (app) {
    'use strict';

    document.dispatchEvent(typeof window.CustomEvent == "function" ? new CustomEvent('hrjobrolesReady') : (function () {
      var e = document.createEvent('Event');
      e.initEvent('hrjobrolesReady', true, true);
      return e;
    })());
  });
})(CRM, require);
