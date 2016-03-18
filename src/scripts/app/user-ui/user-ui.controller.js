(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('UserUICtrl', UserUIController);

    UserUIController.$inject = ['$uibModal', 'AttendanceSvc', 'RaidLootSvc'];

    function UserUIController($uibModal, AttendanceSvc, RaidLootSvc) {
        var vm = this;

        // data
        vm.BreakdownEntities = [];
        vm.RaidLoot          = [];
        vm.Range             = {};

        // controller functions/variables
        vm.AjaxContent            = { RaidLoot: {}, Breakdown: {} };
        vm.OrderMode              = 'Name';
        vm.ShowInactivePlayers    = true;
        vm.ShowAbsoluteAttendance = false;
        vm.RefreshLootLinks       = RefreshLootLinks;
        vm.ShowDetails            = ShowDetails;
        vm.ChangeRange            = GetAllData;
        vm.ChangeAbsolute         = GetPlayerData;

        ///////////////////////////////////////////////////////////////////////
        function GetAllData() {
            vm.AjaxContent.RaidLoot.status = 0;

            GetPlayerData();

            RaidLootSvc.GetInRange(vm.Range.StartDate, vm.Range.EndDate).then(
                function success(response) {
                    vm.RaidLoot = response.data;

                    // transform data into proper stuff for view
                    angular.forEach(vm.RaidLoot, function (value, key) {
                        value.ClassID = ClassIdToCss(parseInt(value.ClassID));
                        value.Item = BuildLootURL(value.Item);
                    });

                    vm.AjaxContent.RaidLoot.status = 1;
                    RefreshLootLinks();
                },
                function error(response) {
                    vm.AjaxContent.RaidLoot.status  = -1;
                    vm.AjaxContent.RaidLoot.message = response.data;
                }
            );
        }

        function GetPlayerData() {
            var promise;
            vm.AjaxContent.Breakdown.status = 0;

            if (vm.ShowAbsoluteAttendance == true) promise = AttendanceSvc.GetAbsoluteAveragesInRange(vm.Range.StartDate, vm.Range.EndDate);
            else                                   promise = AttendanceSvc.GetAveragesInRange(vm.Range.StartDate, vm.Range.EndDate);

            promise.then(
                function success(response) {
                    vm.BreakdownEntities = response.data;

                    // transform ClassID to CSS class name
                    angular.forEach(vm.BreakdownEntities, function (value, key) {
                        value.ClassID = ClassIdToCss(parseInt(value.ClassID));
                    });

                    vm.AjaxContent.Breakdown.status = 1;
                },
                function error(response) {
                    vm.AjaxContent.Breakdown.status  = -1;
                    vm.AjaxContent.Breakdown.message = response.data;
                }
            );
        }

        function ShowDetails(item) {
            $uibModal.open({
                templateUrl: plugin_url.app + '/user-ui/player-breakdown-modal.html',
                controller: 'PlayerBreakdownModalCtrl',
                controllerAs: 'vm',
                size: 'lg',
                resolve: {
                    entity: function() { return item; }
                }
            });
        }
    }
})();