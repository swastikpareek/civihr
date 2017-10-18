define([
    'contact-summary/modules/controllers',
    'contact-summary/modules/settings'
], function (controllers) {
    'use strict';

    /**
     * @ngdoc controller
     * @name ContactSummaryCtrl
     * @param $log
     * @param settings
     * @constructor
     */
    function ContactSummaryCtrl($log, settings) {
        $log.debug('Controller: ContactSummaryCtrl');

        var templateDir = settings.pathBaseUrl + settings.pathTpl;

        this.partials = {
            keyDetails: templateDir + '/include/keyDetails.html',
            keyDates: templateDir + '/include/keyDates.html'
        };

        this.ready = false;
    }

    controllers.controller('ContactSummaryCtrl', ['$log', 'settings', ContactSummaryCtrl]);
});
