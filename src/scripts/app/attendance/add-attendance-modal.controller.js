(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('AddAttendanceModalCtrl', AddAttendanceModalController);

    AddAttendanceModalController.$inject = ['$uibModalInstance', 'toastr', 'entities', 'AttendanceSvc'];

    function AddAttendanceModalController($uibModalInstance, toastr, entities, AttendanceSvc) {
        var vm = this;

        // attendance model
        vm.Date     = new Date();
        vm.PlayerID = null;
        vm.Points   = null;

        // controller functions/variables
        vm.ActiveRequests = 0;
        vm.save           = AddRecord;
        vm.cancel         = function () { $uibModalInstance.dismiss('cancel'); };
        vm.open           = open;

        ///////////////////////////////////////////////////////////////////////
        function AddRecord(form) {
            if (!form.$invalid && vm.Points != null) {
                var Entity = {
                    Date     : vm.Date,
                    PlayerID : vm.PlayerID,
                    Points   : vm.Points
                };
                vm.ActiveRequests = 1;

                // take the timezone out of the JavaScript date to prevent issues
                var date       = vm.Date;
                var simpleDate = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
                Entity.Date = simpleDate;

                AttendanceSvc.AddRecord(Entity).then(
                    function success(response) {
                        var data = response.data;
                        entities.unshift({
                            ClassID    : data.ClassID,
                            ClassName  : data.ClassName,
                            ClassStyle : ClassIdToCss(parseInt(data.ClassID)),
                            Date       : data.Date,
                            ID         : data.ID,
                            Name       : data.Name,
                            PlayerID   : data.PlayerID,
                            Points     : data.Points
                        });

                        toastr.success("Record added!");
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