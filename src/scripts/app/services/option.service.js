(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('OptionSvc', OptionService);

    OptionService.$inject = ['$http'];

    function OptionService($http) {
        var service = {
            GetOption     : GetOption,
            GetOptions    : GetOptions,
            UpdateOptions : UpdateOptions
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function GetOption(key) {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_option',
                    'key': key
                }
            });
        }

        function GetOptions() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_option'
                }
            });
        }

        function UpdateOptions(pairs) {
            return $http({
                method: 'PUT',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_option',
                    'pairs': JSON.stringify(pairs)
                }
            });
        }
    }
})();