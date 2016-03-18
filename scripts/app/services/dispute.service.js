(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('DisputeSvc', DisputeService);

    DisputeService.$inject = ['$http'];

    function DisputeService($http) {
        var service = {
            AddRecord     : AddRecord,
            GetAll        : GetAll,
            GetResolved   : GetResolved,
            GetUnresolved : GetUnresolved,
            UpdateRecord  : UpdateRecord
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function AddRecord(entity) {
            return $http({
                method: 'POST',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_dispute',
                    'entity': JSON.stringify(entity)
                }
            });
        }

        function GetAll() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_dispute',
                    'func': 'all'
                }
            });
        }

        function GetResolved() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_dispute',
                    'func': 'resolved',
                    'id': id
                }
            });
        }

        function GetUnresolved() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_dispute',
                    'func': 'unresolved'
                }
            });
        }

        function UpdateRecord(entity) {
            return $http({
                method: 'PUT',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_dispute',
                    'entity': JSON.stringify(entity)
                }
            });
        }
    }
})();