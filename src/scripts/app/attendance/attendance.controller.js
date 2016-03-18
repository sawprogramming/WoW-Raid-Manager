(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('AttendanceCtrl', AttendanceController);

    AttendanceController.$inject = ['$uibModal', 'AttendanceSvc', 'PlayerSvc', 'DateSvc'];

    function AttendanceController($uibModal, AttendanceSvc, PlayerSvc, DateSvc) {
        var vm = this;

        // data
        vm.AttendanceEntities = [];

        // controller functions/variables
        vm.AjaxContent  = { Attendance: {} };
        vm.Refresh      = initialize;
        vm.open         = open;
        vm.AddRecord    = AddRecord;
        vm.DeleteRecord = DeleteRecord;
        vm.EditRecord   = EditRecord;

        initialize();

        ///////////////////////////////////////////////////////////////////////
        function initialize() {
            vm.AjaxContent.Attendance.status = 0;

            AttendanceSvc.GetAll().then(
			    function success(response) {
				    vm.AttendanceEntities = response.data;
				
				    // transform ClassID to ClassStyle
				    for(var i = 0; i < vm.AttendanceEntities.length; ++i) {
				        vm.AttendanceEntities[i].ClassStyle = ClassIdToCss(parseInt(vm.AttendanceEntities[i].ClassID));
				        vm.AttendanceEntities[i].Date       = DateSvc.toJavaScriptDate(vm.AttendanceEntities[i].Date);
				    }

				    vm.AjaxContent.Attendance.status = 1;
			    },
			    function error(response) {
				    vm.AjaxContent.Attendance.status  = -1;
				    vm.AjaxContent.Attendance.message = response.data;
			    }
            );
        }

        function AddRecord() {
            $uibModal.open({
                templateUrl  : plugin_url.app + '/attendance/add-attendance-modal.html',
                controller   : 'AddAttendanceModalCtrl',
                controllerAs : 'vm',
                resolve: {
                    entities: function () { return vm.AttendanceEntities; }
                }
            });
        }

        function DeleteRecord(record) {
            $uibModal.open({
                templateUrl  : plugin_url.app + '/attendance/delete-attendance-modal.html',
                controller   : 'DeleteAttendanceModalCtrl',
                controllerAs : 'vm',
                resolve: {
                    entity   : function () { return record;                },
                    entities : function () { return vm.AttendanceEntities; }
                }
            });
        }

        function EditRecord(record) {
            $uibModal.open({
                templateUrl  : plugin_url.app + '/attendance/edit-attendance-modal.html',
                controller   : 'EditAttendanceModalCtrl',
                controllerAs : 'vm',
                resolve: {
                    entity: function () { return record; }
                }
            });
        }

        function open($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened = true;
        };
    }
})();