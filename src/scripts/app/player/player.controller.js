(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('PlayerCtrl', PlayerController);

    PlayerController.$inject = ['$uibModal', 'toastr', 'PlayerSvc', 'OptionSvc'];

    function PlayerController($uibModal, toastr, PlayerSvc, OptionSvc) {
        var vm = this;

        // data
        vm.Options = {};
        vm.Players = [];

        // controller functions/variables
        vm.ActiveRequests = 0;
        vm.AjaxContent    = { Players: {}, Options: {} };
        vm.Refresh        = initialize;
        vm.Add            = Add;
        vm.Edit           = Edit;
        vm.Remove         = Remove;
        vm.SaveSettings   = SaveSettings;

        initialize();

        ///////////////////////////////////////////////////////////////////////
        function initialize() {
            vm.AjaxContent.Players.status = 0;
            vm.AjaxContent.Options.status = 0;

            PlayerSvc.GetPlayers().then(
                function success(response) {
                    vm.Players = response.data;

                    // transform ClassID to ClassStyle
                    for (var i = 0; i < vm.Players.length; ++i) {
                        vm.Players[i].ClassStyle = ClassIdToCss(parseInt(vm.Players[i].ClassID));
                    }

                    vm.AjaxContent.Players.status = 1;
                },
                function error(response) {
                    vm.AjaxContent.Players.status  = -1;
                    vm.AjaxContent.Players.message = response.data;
                }
            );

            OptionSvc.GetOptions().then(
                function success(response) {
                    vm.Options = response.data;
                    vm.AjaxContent.Options.status = 1;
                },
                function error(response) {
                    vm.AjaxContent.Options.status  = -1;
                    vm.AjaxContent.Options.message = response.data;
                }
            );
        }

        function Add() {
            $uibModal.open({
                templateUrl: plugin_url.app + '/player/add-player-modal.html',
                controller: 'AddPlayerModalCtrl',
                controllerAs: 'vm',
                resolve: {
                    players : function() { return vm.Players;            },
                    region  : function() { return vm.Options.wro_region; }
                }
            });
        }

        function Edit(record) {
            $uibModal.open({
                templateUrl: plugin_url.app + '/player/edit-player-modal.html',
                controller: 'EditPlayerModalCtrl',
                controllerAs: 'vm',
                resolve: {
                    entity : function() { return record;                },
                    region : function() { return vm.Options.wro_region; }
                }
            });
        }

        function Remove(record) {
            $uibModal.open({
                templateUrl: plugin_url.app + '/player/delete-player-modal.html',
                controller: 'DeletePlayerModalCtrl',
                controllerAs: 'vm',
                resolve: {
                    entity  : function() { return record;     },
                    players : function() { return vm.Players; }
                }
            });
        }

        function SaveSettings() {
            var usedOptions = [
                { "key": "wro_region",        "value": vm.Options.wro_region        },
                { "key": "wro_default_realm", "value": vm.Options.wro_default_realm },
                { "key": "wro_faction",       "value": vm.Options.wro_faction       }
            ];

            vm.ActiveRequests = 1;
            OptionSvc.UpdateOptions(usedOptions).then(
                function success() {
                    toastr.success("Preferences updated!");
                },
                function error(response) {
                    toastr.error(response.data, response.status, {
                        closeButton : true,
                        progressBar : true,
                        timeOut     : 30000,
                    });
                }
            ).finally(function() { vm.ActiveRequests = 0; });
        }
    }
})();