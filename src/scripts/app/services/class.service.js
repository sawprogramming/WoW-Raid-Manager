(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('ClassSvc', ClassService);

    ClassService.$inject = ['$http'];

    function ClassService($http) {
        var service = {
            GetClasses : GetClasses
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function GetClasses() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_class'
                }
            });
        }
    }
})();