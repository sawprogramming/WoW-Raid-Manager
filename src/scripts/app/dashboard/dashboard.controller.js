(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('DashboardCtrl', DashboardController);

    DashboardController.$inject = ['$uibModal', 'AttendanceSvc', 'toastr', 'PlayerSvc', 'DisputeSvc', 'OptionSvc', 'TimeSvc', 'DashboardSvc', 'Upload'];

    function DashboardController($uibModal, AttendanceSvc, toastr, PlayerSvc, DisputeSvc, OptionSvc, TimeSvc, DashboardSvc, Upload) {
        var vm = this;

        // data
        vm.DailyDate       = new Date();
        vm.DailyEntities   = [];
        vm.DisputeEntities = [];
        vm.Options         = {};
        vm.UploadFile      = {};

        // controller functions/variables
        vm.Tab            = 0;
        vm.ActiveRequests = 0;
        vm.AjaxContent    = { Daily: {}, Dispute: {}, Options: {} };
        vm.open           = open;
        vm.cancel         = function () { $uibModalInstance.dismiss('cancel'); };
        vm.ApproveDispute = ApproveDispute;
        vm.DownloadBackup = DownloadBackup;
        vm.RefreshDaily   = initialize;
        vm.RejectDispute  = RejectDispute;
        vm.RemoveDaily    = function(player) { vm.DailyEntities.splice(vm.DailyEntities.indexOf(player), 1); }
        vm.SaveDaily      = SaveDaily;
        vm.SaveSettings   = SaveSettings;
        vm.UploadBackup   = UploadBackup;

        initialize();

        ///////////////////////////////////////////////////////////////////////
        function initialize() {
            vm.AjaxContent.Daily.status   = 0;
            vm.AjaxContent.Dispute.status = 0;
            vm.AjaxContent.Options.status = 0;

            PlayerSvc.GetPlayers().then(
                function success(response) {
                    var data = response.data;
                    vm.DailyEntities = [];

                    for(var i = 0; i < data.length; ++i) {
                        if (data[i].Active) {
                            vm.DailyEntities.push({
                                ClassID    : data[i].ClassID,
                                ClassName  : data[i].ClassName,
                                ClassStyle : ClassIdToCss(parseInt(data[i].ClassID)),
                                Date       : new Date(),
                                ID         : data[i].ID,
                                Name       : data[i].Name,
                                Points     : 1.00
                            });
                        }
                    }

                    vm.AjaxContent.Daily.status = 1;
                },
                function error(response) {
                    vm.AjaxContent.Daily.status  = -1;
                    vm.AjaxContent.Daily.message = response.data;
                }
            );

            DisputeSvc.GetUnresolved().then(
                function success(response) {
                    var data = response.data;
                    vm.DisputeEntities = [];

                    for(var i = 0; i < data.length; ++i) {
                        vm.DisputeEntities.push({
                            AttendanceID  : data[i].AttendanceID,
                            ClassID       : data[i].ClassID,
                            ClassName     : data[i].ClassName,
                            ClassStyle    : ClassIdToCss(parseInt(data[i].ClassID)),
                            Comment       : data[i].Comment,
                            Date          : data[i].Date,
                            DisputePoints : data[i].DisputePoints,
                            ID            : data[i].ID,
                            Name          : data[i].Name,
                            Points        : data[i].Points
                        });
                    }

                    vm.AjaxContent.Dispute.status = 1;
                },
                function error(response) {
                    vm.AjaxContent.Dispute.status  = -1;
                    vm.AjaxContent.Dispute.message = response.data;
                }
            );

            OptionSvc.GetOptions().then(
                function success(response) {
                    vm.Options                    = response.data;
                    vm.Options.wro_realm_time     = TimeSvc.toJavaScriptTime(vm.Options.wro_realm_time);
                    vm.AjaxContent.Options.status = 1;
                },
                function error(response) {
                    vm.AjaxContent.Options.status  = -1;
                    vm.AjaxContent.Options.message = response.data;
                }
            );
        }

        function ApproveDispute(entity) {
            $uibModal.open({
                templateUrl  : plugin_url.app + '/dispute/approve-dispute-modal.html',
                controller   : 'ApproveDisputeModalCtrl',
                controllerAs : 'vm',
                resolve: {
                    entity   : function() { return entity;             },
                    disputes : function() { return vm.DisputeEntities; }
                }
            });
        }

        function DownloadBackup() {
            DashboardSvc.DownloadBackup().then(
                function success(response) {
                    var myBlob = new Blob([response.data], { type: "application/octet-stream" })
                    var blobURL = (window.URL || window.webkitURL).createObjectURL(myBlob);
                    var anchor = document.createElement("a");
                    anchor.download = "wro_backup.zip";
                    anchor.href = blobURL;
                    anchor.click();
                }
            );
        }

        function RejectDispute(entity) {
            $uibModal.open({
                templateUrl  : plugin_url.app + '/dispute/reject-dispute-modal.html',
                controller   : 'RejectDisputeModalCtrl',
                controllerAs : 'vm',
                resolve: {
                    entity   : function() { return entity;             },
                    disputes : function() { return vm.DisputeEntities; }
                }
            });
        }

        function SaveDaily(form) {
            if (!form.$invalid) {
                for (var i = 0; i < vm.DailyEntities.length; ++i) {
                    var date       = vm.DailyDate;
                    var simpleDate = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
                    vm.DailyEntities[i].Date = simpleDate;
                }

                AttendanceSvc.SaveGroupAttnd(vm.DailyEntities).then(
                    function success(response) {
                        vm.RefreshDaily();
                        toastr.success("Attendance saved!");
                    },
                    function error(response) {
                        toastr.error(response.data, response.statusText, {
                            closeButton : true,
                            progressBar : true,
                            timeOut     : 30000
                        });
                    }
                );
            }
        }

        function SaveSettings() {
            var usedOptions = [
                { "key": "wro_realm_time",      "value": TimeSvc.toPhpTime(vm.Options.wro_realm_time) },
                { "key": "wro_realm_frequency", "value": vm.Options.wro_realm_frequency               },
                { "key": "wro_drop_tables",     "value": vm.Options.wro_drop_tables                   }
            ];

            vm.ActiveRequests |= 1;
            OptionSvc.UpdateOptions(usedOptions).then(
                function success() {
                    toastr.success("Preferences updated!");
                },
                function error(response) {
                    toastr.error(response.data, response.statusText, {
                        closeButton : true,
                        progressBar : true,
                        timeOut     : 30000
                    });
                }
            ).finally(function() { vm.ActiveRequests ^= 1; });

            if (vm.UploadFile.name != null) {
                UploadBackup();
            }
        }

        function UploadBackup() {
            vm.ActiveRequests |= 2;
            vm.UploadFile.upload = Upload.upload({
                url: ajax_object.ajax_url,
                data: {
                    'action' : 'wro_restore_ul',
                    'file'   : vm.UploadFile
                }
            });

            vm.UploadFile.upload.then(
                function success(response) {
                    vm.UploadFile = {};
                    toastr.success("Database successfully restored!");
                },
                function error(response) {
                    toastr.error(response.data, response.statusText, {
                        closeButton : true,
                        progressBar : true,
                        timeOut     : 30000
                    });
                }
            ).finally(function () { vm.ActiveRequests ^= 2; });
        }

        function open($event) {
            $event.preventDefault();
            $event.stopPropagation();
            vm.opened = true;
        }
    }
})();