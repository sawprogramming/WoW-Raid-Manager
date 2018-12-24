(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('AttendanceSvc', AttendanceService);

    AttendanceService.$inject = ['$http'];

    function AttendanceService($http) {
        var service = {
            AddRecord                  : AddRecord,
            DeleteRecord               : DeleteRecord,
            GetAll                     : GetAll,
            GetAllById                 : GetAllById,
            GetAbsoluteAveragesInRange : GetAbsoluteAveragesInRange,
            GetAveragesInRange         : GetAveragesInRange,
            GetBreakdown               : GetBreakdown,
            GetBreakdownCount          : GetBreakdownCount,
            GetChart                   : GetChart,
            SaveGroupAttnd             : SaveGroupAttnd,
            UpdateRecord               : UpdateRecord
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function AddRecord(entity) {
            return $http({
                method: 'POST',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance'
                },
                data: {
                    'entity': JSON.stringify(entity)
                }
            });
        }

        function DeleteRecord(id) {
            return $http({
                method: 'DELETE',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance',
                    'id': id
                }
            });
        }

        function GetAll() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance',
                    'func': 'all'
                }
            });
        }

        function GetAllById(id) {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance',
                    'func': 'all',
                    'id': id
                }
            });
        }

        function GetAbsoluteAveragesInRange(startDate, endDate) {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance',
                    'func': 'absolute',
                    'startDate': startDate,
                    'endDate': endDate
                }
            });
        }

        function GetAveragesInRange(startDate, endDate) {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance',
                    'func': 'range',
                    'startDate': startDate,
                    'endDate': endDate
                }
            });
        }

        function GetBreakdown(id) {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance',
                    'func': 'breakdown',
                    'id': id
                }
            });
        }

        function GetBreakdownCount(id) {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance',
                    'func': 'breakdowncount',
                    'id': id
                }
            });
        }

        function GetChart(id) {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance',
                    'func': 'chart',
                    'id': id
                }
            });
        }

        function SaveGroupAttnd(entities) {
            return $http({
                method: 'POST',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance'
                },
                data: {
                    'dailyEntities': JSON.stringify(entities)
                }
            });
        }

        function UpdateRecord(entity) {
            return $http({
                method: 'PUT',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_attendance',
                    'entity': JSON.stringify(entity)
                }
            });
        }
    }
})();