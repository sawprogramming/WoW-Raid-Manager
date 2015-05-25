app.directive('playerSelect', function(PlayerSvc) {
	return {
		restrict: 'E',
		require: "?ngModel",
		replace: true,
		template: '<select ng-options="player.ID as player.Name for player in PlayerSelect"></select>',
		link: function(scope, elem, attrs) {
			PlayerSvc.GetPlayers().then(
				function(response) {
					// sort the players alphabetically
					scope.PlayerSelect = response.data.sort(function(a, b) {
						if(a.Name < b.Name) return -1;
						if(b.Name < a.Name) return 1;
						return 0;
					});

					// color the options once they're set
					var unwatch = scope.$watch(
						function() {
							return elem[0].childNodes.length;
						},
						function (newValue, oldValue) {
							if(oldValue != newValue) {
								angular.forEach(elem.children(), function(value, key) {
									elem.children().eq(key).addClass(ClassIdToCss(parseInt(scope.PlayerSelect[key].ClassID)));
								});

								unwatch();
							}
						},
						true
					);

					// change the view model when select changes
					scope.$watch(
						function() {
							return elem[0].selectedIndex;
						},
						function(newValue, oldValue) {
							if(newValue != oldValue) {
								var vm = scope.row;

								// change the vm to that info
								angular.forEach(scope.PlayerSelect, function(value, key) {
									if(newValue == key) {
										vm.Name = value.Name;
										vm.ClassName = value.ClassName;
										vm.ClassID = ClassIdToCss(parseInt(value.ClassID));
									}
								});
							}
						},
						true
					);
				}
			);
		}
	};
});