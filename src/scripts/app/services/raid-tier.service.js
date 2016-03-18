(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('RaidTierSvc', RaidTierService);

    RaidTierService.$inject = ['$http'];

    function RaidTierService($http) {
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
                    'action': 'wro_raidtier',
                }
            });
        }
    }
})();