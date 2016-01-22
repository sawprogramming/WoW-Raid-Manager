app.controller("DashboardCtrl", function($scope, $uibModal, AttendanceSvc, toastr, PlayerSvc, DisputeSvc, OptionSvc, TimeSvc) {
	$scope.model = {
		Tab: 0,
		DailyEntities: [],
		DisputeEntities: [],
		DailyDate: new Date(),
		Options: {},
		AjaxContent: {
			Daily:   { status: 'loading', message: '' },
			Dispute: { status: 'loading', message: '' }
		}
	};
	$scope.AjaxForm = 'ready';
	var vm = $scope.model;

	$scope.RefreshDaily = function() {
		vm.AjaxContent.Daily.status   = 'loading';
		vm.AjaxContent.Dispute.status = 'loading';

		OptionSvc.GetOptions()
			.success(function(data) {
				vm.Options = data;
				vm.Options.wro_realm_time = TimeSvc.toJavaScriptTime(vm.Options.wro_realm_time);
			});

		PlayerSvc.GetPlayers()
			.success(function(data) {
				vm.DailyEntities = [];

				for(var i = 0; i < data.length; ++i) {
					if(data[i].Active) {
						vm.DailyEntities.push({
							Points: 1.00,
							ID: data[i].ID,
							Date: new Date(),
							Name: data[i].Name,
							ClassID: data[i].ClassID,
							ClassName: data[i].ClassName,
							ClassStyle: ClassIdToCss(parseInt(data[i].ClassID))
						});
					}
				}

				vm.AjaxContent.Daily.status = 'success';
			})
			.error(function(errmsg) {
				vm.AjaxContent.Daily.status = 'error';
				vm.AjaxContent.Daily.message = errmsg;
			});

		DisputeSvc.GetUnresolved()
			.success(function(data) {
				vm.DisputeEntities = [];

				for(var i = 0; i < data.length; ++i) {
					vm.DisputeEntities.push({
						Points: data[i].Points,
						ID: data[i].ID,
						Date: data[i].Date,
						Name: data[i].Name,
						Comment: data[i].Comment,
						ClassID: data[i].ClassID,
						ClassName: data[i].ClassName,
						AttendanceID: data[i].AttendanceID,
						DisputePoints: data[i].DisputePoints,
						ClassStyle: ClassIdToCss(parseInt(data[i].ClassID))
					});
				}

				vm.AjaxContent.Dispute.status = 'success';
			})
			.error(function(errmsg) {
				vm.AjaxContent.Dispute.status = 'error';
				vm.AjaxContent.Dispute.message = errmsg;
			});
	}
	$scope.RefreshDaily();

	$scope.ApproveDispute = function(entity) {
		var modalInstance = $uibModal.open({
			templateUrl: plugin_url.app + '/dispute/templates/approveDisputeModal.html',
			controller: 'ApproveDisputeModalCtrl',
			resolve: {
				entity: function() {
					return entity;
				},
				disputes: function() {
					return vm.DisputeEntities;
				}
			}
		});
	};

	$scope.RejectDispute = function(entity) {
		var modalInstance = $uibModal.open({
			templateUrl: plugin_url.app + '/dispute/templates/rejectDisputeModal.html',
			controller: 'RejectDisputeModalCtrl',
			resolve: {
				entity: function() {
					return entity;
				},
				disputes: function() {
					return vm.DisputeEntities;
				}
			}
		});
	};

	$scope.RemoveDaily = function(player) {
		vm.DailyEntities.splice(vm.DailyEntities.indexOf(player), 1);
	};

	$scope.SaveDaily = function(form) {
		if(!form.$invalid) {
			for(var i = 0; i < vm.DailyEntities.length; ++i) {
				vm.DailyEntities[i].Date = vm.DailyDate;
			}

			AttendanceSvc.SaveGroupAttnd(vm.DailyEntities).then(
				function(response) {
					$scope.RefreshDaily();
					toastr.success("Attendance saved!");
				},
				function(errmsg) {
					toastr.error(errmsg.data, errmsg.statusText, { 
						closeButton: true,
						progressBar: true,
						timeOut: 30000,
				 	});
				}
			);
		}
	};

	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened = true;
	};

	$scope.SaveSettings = function() {
		var usedOptions = [
			{ "key": "wro_realm_time",      "value": TimeSvc.toPhpTime(vm.Options.wro_realm_time) },
			{ "key": "wro_realm_frequency", "value": vm.Options.wro_realm_frequency }
		];

		$scope.AjaxForm = "processing";
		OptionSvc.UpdateOptions(usedOptions)
			.success(function(data) {
				toastr.success("Preferences updated!");
			})
			.error(function(message, status) {
				toastr.error(message, status, { 
					closeButton: true,
					progressBar: true,
					timeOut: 30000,
			 	});
			})
			.finally(function() {
				$scope.AjaxForm = 'ready';
			});
	};
});