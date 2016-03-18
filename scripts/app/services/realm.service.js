(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('RealmSvc', RealmService);

    RealmService.$inject = ['$http'];

    function RealmService($http) {
        var service = {
            GetRealms : GetRealms
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function GetRealms(region) {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action' : 'wro_realm',
                    'region' :  region
                }
            });
        }
    }
})();