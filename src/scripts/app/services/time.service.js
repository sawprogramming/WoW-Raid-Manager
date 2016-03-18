(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('TimeSvc', TimeService);

    TimeService.$inject = ['$http'];

    function TimeService($http) {
        var service = {
            toJavaScriptTime : toJavaScriptTime,
            toPhpTime        : toPhpTime
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function toJavaScriptTime(phpTime) {
            return phpTime * 1000;
        }

        function toPhpTime(javaScriptTime) {
            return javaScriptTime / 1000;
        }
    }
})();