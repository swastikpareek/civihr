/* eslint-env amd */

define([
  'common/moment'
], function (moment) {
  'use strict';

  ModalChangeReasonCtrl.__name = 'ModalChangeReasonCtrl';
  ModalChangeReasonCtrl.$inject = [
    '$scope', '$log', '$uibModalInstance', 'content', 'date', 'reasonId',
    'settings', 'ContractRevisionService'
  ];

  function ModalChangeReasonCtrl ($scope, $log, $modalInstance, content, date,
    reasonId, settings, ContractRevisionService) {
    var copy;

    $log.debug('Controller: ModalChangeReasonCtrl');

    content = content || {};

    copy = content.copy || {};
    copy.title = copy.title || 'Revision data';

    $scope.change_reason = reasonId || '';
    $scope.copy = copy;
    $scope.effective_date = date || '';
    $scope.isPast = false;

    $scope.dpOpen = function ($event, opened) {
      $event.preventDefault();
      $event.stopPropagation();

      $scope[opened] = true;
    };

    $scope.save = function () {
      ContractRevisionService.validateEffectiveDate({
        contact_id: settings.contactId,
        effective_date: $scope.effective_date
      })
        .then(function (result) {
          if (result.success) {
            $modalInstance.close({
              reasonId: $scope.change_reason,
              date: $scope.effective_date ? moment($scope.effective_date).format('YYYY-MM-DD') : ''
            });
          } else {
            CRM.alert(result.message, 'Error', 'error');
            $scope.$broadcast('hrjc-loader-hide');
          }
        });
    };

    $scope.cancel = function () {
      $modalInstance.dismiss('cancel');
    };

    $scope.$watch('effective_date', function (dateSelected) {
      $scope.isPast = (new Date(dateSelected).setHours(0, 0, 0, 0) < new Date().setHours(0, 0, 0, 0));
    });
  }

  return ModalChangeReasonCtrl;
});
