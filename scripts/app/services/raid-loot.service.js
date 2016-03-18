(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('RaidLootSvc', RaidLootService);

    RaidLootService.$inject = ['$http'];

    function RaidLootService($http) {
        var service = {
            Delete     : Delete,
            GetAll     : GetAll,
            GetInRange : GetInRange
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function Delete(id) {
            return $http({
                method: 'DELETE',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_raidloot',
                    'id': id
                }
            });
        }

        function GetAll() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_raidloot',
                    'func': 'all'
                }
            });
        }

        function GetInRange(startDate, endDate) {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_raidloot',
                    'func': 'range',
                    'startDate': startDate,
                    'endDate': endDate
                }
            });
        }
    }
})();