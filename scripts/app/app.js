var app = angular.module("WRO", ['ui.bootstrap', 'angularUtils.directives.dirPagination', 'ngMessages', 'toastr']);

app.config(function(paginationTemplateProvider) {
    paginationTemplateProvider.setPath(plugin_url.libs + '/dirPagination.tpl.html');
});

app.config(function(uibDatepickerConfig) {
	uibDatepickerConfig.showWeeks = false;
});

app.config(function(uibDatepickerPopupConfig) {
	uibDatepickerPopupConfig.datepickerPopup = "MM/dd/yyyy";
});

app.config(function(uibTimepickerConfig) {
	uibTimepickerConfig.showSpinners = false;
	uibTimepickerConfig.minuteStep = 15;
});