(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('DeleteRaidLootModalCtrl', DeleteRaidLootModalController);

    DeleteRaidLootModalController.$inject = ['$uibModalInstance', 'toastr', 'entity', 'records', 'RaidLootSvc'];

    function DeleteRaidLootModalController($uibModalInstance, toastr, entity, records, RaidLootSvc) {
        var vm = this;

        // raid loot model
        vm.ClassStyle = entity.ClassStyle;
        vm.Date       = entity.Date;
        vm.ID         = entity.ID;
        vm.Item       = entity.Item;
        vm.PlayerName = entity.PlayerName;

        // controller functions/variables
        vm.ActiveRequests = 0;
        vm.delete         = DeleteRow;
        vm.cancel         = function() { $uibModalInstance.dismiss('cancel'); };

        RefreshLootLinks();

        ///////////////////////////////////////////////////////////////////////
        function DeleteRow() {
            vm.ActiveRequests = 1;

            RaidLootSvc.Delete(vm.ID).then(
                function success() {
                    toastr.success("Record deleted!");
                    records.splice(records.indexOf(entity), 1);
                    vm.cancel();
                    RefreshLootLinks();
                },
                function error(response) {
                    toastr.error(response.data, response.status, {
                        closeButton : true,
                        progressBar : true,
                        timeOut     : 30000
                    });
                }
            ).finally(function() { vm.ActiveRequests = 0; });
        }
    }
})();