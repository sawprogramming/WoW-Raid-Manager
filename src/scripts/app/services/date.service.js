(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('DateSvc', DateService);

    function DateService() {
        var service = {
            toJavaScriptDate : toJavaScriptDate
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function toJavaScriptDate(dateString) {
            var date = null;
            if (dateString != null) {
                date = new Date(dateString);
                date.setMinutes(date.getMinutes() + date.getTimezoneOffset());
            }
            return date;
        }
    }
})();