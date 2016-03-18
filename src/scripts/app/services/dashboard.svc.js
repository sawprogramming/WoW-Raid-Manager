(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('DashboardSvc', DashboardService);

    DashboardService.$inject = ['$http'];

    function DashboardService($http) {
        var service = {
            DownloadBackup : DownloadBackup
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function DownloadBackup() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                responseType: 'arraybuffer',
                cache: false,
                params: {
                    'action': 'wro_backup_dl'
                }
            });
        }
    }
})();