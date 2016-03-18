(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('EditPlayerModalCtrl', EditPlayerModalController);

    EditPlayerModalController.$inject = ['$scope', '$uibModalInstance', 'toastr', 'region', 'entity', 'PlayerSvc'];

    function EditPlayerModalController($scope, $uibModalInstance, toastr, region, entity, PlayerSvc) {
        var vm = this;

        // player model
        vm.Active     = null;
        vm.ClassID    = null;
        vm.ClassName  = null;
        vm.ClassStyle = null;
        vm.ID         = null;
        vm.Name       = null;
        vm.Realm      = null;
        vm.region     = region;
        vm.UserID     = null;

        // controller functions/variables
        vm.HasUserID      = null;
        vm.ActiveRequests = 0;
        vm.reset          = initialize;
        vm.save           = EditPlayer;
        vm.cancel         = function() { $uibModalInstance.dismiss('cancel'); };

        $scope.$watch(
            function () {
                return vm.HasUserID;
            },
            function (newValue, oldValue) {
                if (newValue == false) {
                    vm.UserID = null;
            }
        });

        initialize();

        ///////////////////////////////////////////////////////////////////////
        function initialize() {
            vm.Active     = entity.Active;
            vm.ClassID    = entity.ClassID;
            vm.ClassName  = entity.ClassName;
            vm.ClassStyle = entity.ClassStyle;
            vm.ID         = entity.ID;
            vm.Name       = entity.Name;
            vm.Realm      = entity.Realm;
            vm.UserID     = entity.UserID;
            vm.HasUserID  = entity.UserID == null ? false : true;
        }

        function EditPlayer(form) {
            if (!form.$invalid) {
                var Player = {
                    Active  : vm.Active,
                    ClassID : vm.ClassID,
                    ID      : vm.ID,
                    Name    : vm.Name,
                    Realm   : vm.Realm,
                    UserID  : vm.UserID
                };
                vm.ActiveRequests = 1;
                Player.Name = Player.Name.charAt(0).toUpperCase() + Player.Name.slice(1).toLowerCase();

                PlayerSvc.EditPlayer(Player).then(
                    function success(response) {
                        var data = response.data;

                        entity.Name       = data.Name;
                        entity.Realm      = data.Realm;
                        entity.Active     = data.Active;
                        entity.UserID     = data.UserID;
                        entity.ClassID    = data.ClassID;
                        entity.Username   = data.Username;
                        entity.ClassName  = data.ClassName;
                        entity.RealmName  = data.RealmName;
                        entity.ClassStyle = ClassIdToCss(parseInt(data.ClassID));

                        toastr.success("Player updated!");
                        vm.cancel();
                    },
                    function error(respone) {
                        toastr.error(respone.data, respone.status, {
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