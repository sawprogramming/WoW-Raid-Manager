(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('PlayerBreakdownModalCtrl', PlayerBreakdownModalCtrl);

    PlayerBreakdownModalCtrl.$inject = ['$uibModalInstance', 'toastr', 'entity', 'AttendanceSvc', 'DisputeSvc'];

    function PlayerBreakdownModalCtrl($uibModalInstance, toastr, entity, AttendanceSvc, DisputeSvc) {
        var vm = this;

        // data
        vm.AttendanceEntities = [];
        vm.BreakdownEntity    = {};
        vm.ChartData          = {};

        // controller functions/variables
        vm.ActiveRequests = 0;
        vm.AjaxContent    = { Attendance: {}, Breakdown: {}, Chart: {} };
        vm.cancel         = function () { $uibModalInstance.dismiss('cancel'); }
        vm.SubmitDispute  = SubmitDispute;

        initialize();

        ///////////////////////////////////////////////////////////////////////
        function initialize() {
            var chart, chartOptions;
            vm.AjaxContent.Attendance.status = 0;
            vm.AjaxContent.Breakdown.status  = 0;
            vm.AjaxContent.Chart.status      = 0;

            AttendanceSvc.GetAllById(entity.ID).then(
                function success(response) {
                    vm.AttendanceEntities = response.data;

                    for(var i = 0; i < vm.AttendanceEntities.length; ++i) {
                        vm.AttendanceEntities[i].Dispute = {
                            Points  : null,
                            Comment : null
                        }
                    }

                    vm.AjaxContent.Attendance.status = 1;
                },
                function error(response) {
                    vm.AjaxContent.Attendance.status  = -1;
                    vm.AjaxContent.Attendance.message = response.data;
                }
            );

            AttendanceSvc.GetBreakdown(entity.ID).then(
                function success(response) {
                    vm.BreakdownEntity              = response.data;
                    vm.BreakdownEntity.ClassID      = entity.ClassID;
                    vm.AjaxContent.Breakdown.status = 1;
                },
                function error(response) {
                    vm.AjaxContent.Breakdown.status  = -1;
                    vm.AjaxContent.Breakdown.message = response.data;
                }
            );
            
            AttendanceSvc.GetChart(entity.ID).then(
                function success(response) {
                    var sqlDate;
                    var temp = response.data;

                    // setup chart data
                    vm.ChartData = new google.visualization.DataTable();
                    vm.ChartData.addColumn('date', 'Date');
                    vm.ChartData.addColumn('number', 'Your Attendance');
                    vm.ChartData.addColumn('number', 'Your Average Attendance');
                    vm.ChartData.addColumn('number', 'Raid Attendance');

                    // add chart data
                    angular.forEach(temp, function (value, key) {
                        sqlDate = value.Date.split(/[- :]/);
                        vm.ChartData.addRow([new Date(sqlDate[0], sqlDate[1] - 1, sqlDate[2]), parseInt(value.Points), parseInt(value.PlayerAverage), parseInt(value.RaidAverage)]);
                    });

                    // draw chart
                    setTimeout(function () {
                        chart = new google.charts.Line(document.getElementById('calendar_basic'));
                        chartOptions = {
                            width: '100%',
                            height: 255,
                            legend: {
                                position: 'none'
                            },
                            vAxis: {
                                viewWindowMode: 'explicit',
                                viewWindow: {
                                    max: 100,
                                    min: 0
                                }
                            },
                            axisTitlesPosition: 'none'
                        };
                        chart.draw(vm.ChartData, chartOptions);
                    }, 100);

                    vm.AjaxContent.Chart.status = 1;
                },
                function error(response) {
                    vm.AjaxContent.Chart.status  = -1;
                    vm.AjaxContent.Chart.message = response.data;
                }
            );
        }

        function SubmitDispute(form, entity) {
            var disputeEntity = {
                AttendanceID : entity.ID,
                Comment      : entity.Dispute.Comment,
                Points       : entity.Dispute.Points
            };

            if (!form.$invalid && entity.Dispute.Points != null) {
                vm.ActiveRequests = 1;

                DisputeSvc.AddRecord(disputeEntity).then(
                    function success() {
                        entity.Dispute = {
                            Points  : null,
                            Comment : null
                        };

                        form.$setPristine();
                        toastr.success("Dispute submitted!");
                    },
                    function error(response) {
                        toastr.error(response.data, response.status, {
                            closeButton : true,
                            progressBar : true,
                            timeOut     : 30000
                        });
                    },
                    function notify() {
                        vm.ActiveRequests = 0;
                    }
                );
            }
        }
    }
})();