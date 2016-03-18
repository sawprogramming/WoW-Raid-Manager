(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('DeleteAttendanceModalCtrl', DeleteAttendanceModalController);

    DeleteAttendanceModalController.$inject = ['$uibModalInstance', 'toastr', 'entity', 'entities', 'AttendanceSvc'];

    function DeleteAttendanceModalController($uibModalInstance, toastr, entity, entities, AttendanceSvc) {
        var vm = this;

        // attendance model
        vm.ClassStyle = entity.ClassStyle;
        vm.Date       = entity.Date;
        vm.ID         = entity.ID;
        vm.Name       = entity.Name;

        // controller functions/variables
        vm.ActiveRequests = 0;
        vm.delete         = DeleteRecord;
        vm.cancel         = function () { $uibModalInstance.dismiss('cancel'); };

        ///////////////////////////////////////////////////////////////////////
        function DeleteRecord() {
            vm.ActiveRequests = 1;

            AttendanceSvc.DeleteRecord(vm.ID).then(
                function success() {
                    toastr.success("Record deleted!");
                    entities.splice(entities.indexOf(entity), 1);
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
})();