(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('UserSvc', UserService);

    UserService.$inject = ['$http'];

    function UserService($http) {
        var service = {
            GetUsers: GetUsers
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function GetUsers() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_user'
                }
            });
        }
    }
})();