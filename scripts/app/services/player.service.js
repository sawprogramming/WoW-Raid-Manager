(function () {
    'use strict';

    angular
        .module('WRO')
        .factory('PlayerSvc', PlayerService);

    PlayerService.$inject = ['$http'];

    function PlayerService($http) {
        var service = {
            AddPlayer    : AddPlayer,
            DeletePlayer : DeletePlayer,
            EditPlayer   : EditPlayer,
            GetPlayers   : GetPlayers
        };
        return service;

        ///////////////////////////////////////////////////////////////////////
        function AddPlayer(obj) {
            return $http({
                method: 'POST',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_player',
                    'entity': JSON.stringify(obj)
                }
            });
        }

        function DeletePlayer(id) {
            return $http({
                method: 'DELETE',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_player',
                    'id': id
                }
            });
        }

        function EditPlayer(entity) {
            return $http({
                method: 'PUT',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_player',
                    'entity': JSON.stringify(entity)
                }
            });
        }

        function GetPlayers() {
            return $http({
                method: 'GET',
                url: ajax_object.ajax_url,
                params: {
                    'action': 'wro_player'
                }
            });
        }
    }
})();