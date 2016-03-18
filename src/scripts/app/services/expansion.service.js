(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('ExpansionSvc', ExpansionService);

    ExpansionService.$inject = ['$http'];

    function ExpansionService($http) {
        var service = {
            GetAll : GetAll
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function GetAll() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_expansion'
                }
            });
        }
    }
})();