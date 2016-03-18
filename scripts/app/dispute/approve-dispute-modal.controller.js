(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('ApproveDisputeModalCtrl', ApproveDisputeModalController);

    ApproveDisputeModalController.$inject = ['$uibModalInstance', 'entity', 'disputes', 'DisputeSvc', 'toastr'];

    function ApproveDisputeModalController($uibModalInstance, entity, disputes, DisputeSvc, toastr) {
        var vm = this;

        // dispute model
        vm.AttendanceID  = entity.AttendanceID;
        vm.ClassStyle    = entity.ClassStyle;
        vm.Comment       = entity.Comment;
        vm.Date          = entity.Date;
        vm.DisputePoints = entity.DisputePoints;
        vm.ID            = entity.ID;
        vm.Name          = entity.Name
        vm.Points        = entity.Points;
        vm.Verdict       = true;

        // controller functions/variables
        vm.ActiveRequests = 0;
        vm.approve        = ApproveDispute;
        vm.cancel         = function () { $uibModalInstance.dismiss('cancel'); };

        ///////////////////////////////////////////////////////////////////////
        function ApproveDispute() {
            var Dispute = {
                AttendanceID : vm.AttendanceID,
                ID           : vm.ID,
                Points       : vm.DisputePoints,
                Verdict      : vm.Verdict
            };
            vm.ActiveRequests = 1;

            DisputeSvc.UpdateRecord(Dispute).then(
                function success(response) {
                    disputes.splice(disputes.indexOf(entity), 1);
                    toastr.success("Dispute approved!");
                    vm.cancel();
                },
                function error(response) {
                    toastr.error(response.data, response.status, {
                        closeButton : true,
                        progressBar : true,
                        timeOut     : 30000,
                    });
                },
                function notify() {
                    vm.ActiveRequests = 0;
                }
            );
        }
    }
})();