(function () {
    'use strict';

    angular
        .module('WRO')
        .controller('RaidLootCtrl', RaidLootController);

    RaidLootController.$inject = ['$uibModal', 'toastr', 'RaidLootSvc', 'OptionSvc', 'TimeSvc'];

    function RaidLootController($uibModal, toastr, RaidLootSvc, OptionSvc, TimeSvc) {
        var vm = this;

        // data
        vm.Options  = {};
        vm.RaidLoot = [];

        // controller functions/variables
        vm.ActiveRequests   = 0;
        vm.AjaxContent      = { RaidLoot: {}, Options: {} };
        vm.RefreshLootLinks = RefreshLootLinks;
        vm.RefreshTable     = initialize;
        vm.Remove           = RemoveRow;
        vm.SaveSettings     = SaveSettings;

        initialize();

        ///////////////////////////////////////////////////////////////////////
        function initialize() {
            vm.AjaxContent.RaidLoot.status = 0;
            vm.AjaxContent.Options.status  = 0;

            RaidLootSvc.GetAll().then(
                function success(response) {
                    vm.RaidLoot = response.data;

                    // transform data into proper stuff for view
                    angular.forEach(vm.RaidLoot, function (value, key) {
                        value.ClassStyle = ClassIdToCss(parseInt(value.ClassID));
                        value.Item       = BuildLootURL(value.Item);
                    });

                    vm.AjaxContent.RaidLoot.status = 1;
                    RefreshLootLinks();
                },
                function error(response) {
                    vm.AjaxContent.RaidLoot.status  = -1;
                    vm.AjaxContent.RaidLoot.message = response.data;
                }
            );

            OptionSvc.GetOptions().then(
                function success(response) {
                    vm.Options                    = response.data;
                    vm.Options.wro_loot_time      = TimeSvc.toJavaScriptTime(response.data.wro_loot_time);
                    vm.AjaxContent.Options.status = 1;
                },
                function error(response) {
                    vm.AjaxContent.Options.status  = -1;
                    vm.AjaxContent.Options.message = response.data;
                }
            );
        }

        function RemoveRow(row) {
            $uibModal.open({
                templateUrl  : plugin_url.app + '/raid-loot/delete-raid-loot-modal.html',
                controller   : 'DeleteRaidLootModalCtrl',
                controllerAs : 'vm',
                resolve: {
                    entity  : function () { return row;         },
                    records : function () { return vm.RaidLoot; }
                }
            });
        }

        function SaveSettings() {
            var usedOptions = [
			    { "key": "wro_loot_time",      "value": TimeSvc.toPhpTime(vm.Options.wro_loot_time) },
			    { "key": "wro_loot_frequency", "value": vm.Options.wro_loot_frequency }
            ];
            vm.ActiveRequests = 1;

		    OptionSvc.UpdateOptions(usedOptions).then(
			    function success() {
				    toastr.success("Preferences updated!");
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