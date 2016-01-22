app.controller("RaidLootCtrl", function($scope, $uibModal, toastr, RaidLootSvc, OptionSvc, TimeSvc) {
	$scope.model = {
		Options: {},
		RaidLoot: [],
		AjaxContent: {
			RaidLoot: { status: 'loading', message: '' }
		}
	};
	$scope.AjaxForm = 'ready';
	var vm = $scope.model;
    $scope.RefreshLootLinks = RefreshLootLinks;

	function Refresh() {
		vm.AjaxContent.RaidLoot.status = 'loading';

		OptionSvc.GetOptions()
			.success(function(data) {
				vm.Options = data;
				vm.Options.wro_loot_time = TimeSvc.toJavaScriptTime(data.wro_loot_time);
			});

		RaidLootSvc.GetAll()
			.success(function(data) {
				vm.RaidLoot = data;

				// transform data into proper stuff for view
				angular.forEach(vm.RaidLoot, function(value, key) {
					value.ClassStyle = ClassIdToCss(parseInt(value.ClassID));
					value.Item = BuildLootURL(value.Item);
				});

				vm.AjaxContent.RaidLoot.status = 'success';
			    RefreshLootLinks();
			})
			.error(function(errmsg) {
				vm.AjaxContent.RaidLoot.status = 'error';
				vm.AjaxContent.RaidLoot.message = errmsg;
			});
	}

	$scope.populate = function() {
		Refresh();
	};
	$scope.populate();

	$scope.Remove = function(row) {
		var modalInstance = $uibModal.open({
			templateUrl: plugin_url.app + '/raid-loot/templates/deleteLootModal.html',
			controller: 'DeleteRaidLootModalCtrl',
			resolve: {
				entity: function() {
					return row;
				},
				records: function() {
					return vm.RaidLoot;
				}
			}
		});
	};

	$scope.SaveSettings = function() {
		var usedOptions = [
			{ "key": "wro_loot_time",      "value": TimeSvc.toPhpTime(vm.Options.wro_loot_time) },
			{ "key": "wro_loot_frequency", "value": vm.Options.wro_loot_frequency }
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

	$scope.RefreshTable = Refresh;
});