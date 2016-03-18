(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('DeletePlayerModalCtrl', DeletePlayerModalController);

    DeletePlayerModalController.$inject = ['$uibModalInstance', 'toastr', 'entity', 'players', 'PlayerSvc'];

    function DeletePlayerModalController($uibModalInstance, toastr, entity, players, PlayerSvc) {
        var vm = this;

        // player model
        vm.ClassStyle = entity.ClassStyle;
        vm.ID         = entity.ID;
        vm.Name       = entity.Name;

        // controller functions/variables
        vm.ActiveRequests = 0;
        vm.delete         = DeletePlayer;
        vm.cancel         = function () { $uibModalInstance.dismiss('cancel'); };

        ///////////////////////////////////////////////////////////////////////
        function DeletePlayer() {
            vm.ActiveRequests = 1;
            PlayerSvc.DeletePlayer(vm.ID).then(
                function success() {
                    toastr.success("Player deleted!");
                    players.splice(players.indexOf(entity), 1);
                    vm.cancel();
                },
                function error(response) {
                    toastr.error(response.data, response.status, {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 30000,
                    });
                }
            ).finally(function() { vm.ActiveRequests = 0; });
        }
    }
})();