(function() {
    'use strict';

    angular
        .module('WRO')
        .controller('AddPlayerModalCtrl', AddPlayerModalController);

    AddPlayerModalController.$inject = ['$uibModalInstance', 'toastr', 'region', 'players', 'PlayerSvc'];

    function AddPlayerModalController($uibModalInstance, toastr, region, players, PlayerSvc) {
        var vm = this;

        // data
        vm.ClassID = null;
        vm.Name    = null;
        vm.Realm   = null;
        vm.region  = region;
        vm.UserID  = null;

        // controller functions/variables
        vm.ActiveRequests = 0;
        vm.add            = AddPlayer;
        vm.cancel         = function () { $uibModalInstance.dismiss('cancel'); };

        ///////////////////////////////////////////////////////////////////////
        function AddPlayer(form) {
            if (!form.$invalid) {
                var NewPlayer = {
                    ClassID : vm.ClassID,
                    Name    : vm.Name,
                    Realm   : vm.Realm,
                    UserID  : vm.UserID
                };
                vm.ActiveRequests = 1;
                NewPlayer.Name = NewPlayer.Name.charAt(0).toUpperCase() + NewPlayer.Name.slice(1).toLowerCase();

                PlayerSvc.AddPlayer(NewPlayer).then(
                    function success(response) {
                        var data = response.data;

                        players.push({
                            ID         : data.ID,
                            Name       : data.Name,
                            Realm      : data.Realm,
                            UserID     : data.UserID,
                            ClassID    : data.ClassID,
                            Username   : data.Username,
                            ClassName  : data.ClassName,
                            RealmName  : data.RealmName,
                            ClassStyle : ClassIdToCss(parseInt(data.ClassID))
                        });

                        toastr.success('Player added!');
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
    }
})();