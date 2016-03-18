(function () {
    'use strict';

    angular
        .module('WRO')
        .config(configurePagination)
        .config(configureDatepicker)
        .config(configureDatepickerPopup)
        .config(configureTimepicker);

    ///////////////////////////////////////////////////////////////////////////
    function configurePagination(paginationTemplateProvider) {
        paginationTemplateProvider.setPath(plugin_url.libs + '/dirPagination.tpl.html');
    }

    function configureDatepicker(uibDatepickerConfig) {
        uibDatepickerConfig.showWeeks = false;
    }

    function configureDatepickerPopup(uibDatepickerPopupConfig) {
        uibDatepickerPopupConfig.datepickerPopup = 'MM/dd/yyyy';
    }

    function configureTimepicker(uibTimepickerConfig) {
        uibTimepickerConfig.showSpinners = false;
        uibTimepickerConfig.minuteStep = 15;
    }
})();