(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('EditAttendanceModalCtrl', EditAttendanceModalController);

    EditAttendanceModalController.$inject = ['$uibModalInstance', 'toastr', 'entity', 'AttendanceSvc'];

    function EditAttendanceModalController($uibModalInstance, toastr, entity, AttendanceSvc) {
        var vm = this;

        // attendance model
        vm.Date     = null;
        vm.ID       = null;
        vm.PlayerID = null;
        vm.Points   = null;

        // controller functions/variables
        vm.ActiveRequests = 0;
        vm.cancel         = function () { $uibModalInstance.dismiss('cancel'); };
        vm.open           = open;
        vm.reset          = initialize;
        vm.save           = EditRecord;

        initialize();

        ///////////////////////////////////////////////////////////////////////
        function initialize() {
            vm.ClassID    = entity.ClassID;
            vm.ClassName  = entity.ClassName;
            vm.ClassStyle = entity.ClassStyle;
            vm.Date       = entity.Date;
            vm.ID         = entity.ID;
            vm.Name       = entity.Name;
            vm.PlayerID   = entity.PlayerID;
            vm.Points = entity.Points;

            // take the timezone out of the JavaScript date to prevent issues
            var date       = vm.Date;
            var simpleDate = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            vm.Date = simpleDate;
        }

        function EditRecord(form) {
            if (!form.$invalid) {
                var Entity = {
                    Date     : vm.Date,
                    ID       : vm.ID,
                    PlayerID : vm.PlayerID,
                    Points   : vm.Points
                };
                vm.ActiveRequests = 1;

                // take the timezone out of the JavaScript date to prevent issues
                var date       = vm.Date;
                var simpleDate = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
                Entity.Date = simpleDate;

                AttendanceSvc.UpdateRecord(Entity).then(
                    function success(response) {
                        var data = response.data;

                        entity.ClassID    = data.ClassID;
                        entity.ClassName  = data.ClassName;
                        entity.ClassStyle = ClassIdToCss(parseInt(data.ClassID));
                        entity.Date       = data.Date;
                        entity.Name       = data.Name;
                        entity.PlayerID   = data.PlayerID;
                        entity.Points     = data.Points;

                        toastr.success("Record updated!");
                        vm.cancel();
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

        function open($event) {
            $event.preventDefault();
            $event.stopPropagation();
            vm.opened = true;
        };
    }
})();