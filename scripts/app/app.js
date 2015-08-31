var app = angular.module("WRO", ['ui.bootstrap', 'angularUtils.directives.dirPagination', 'ngMessages', 'toastr']);

app.config(function(paginationTemplateProvider) {
    paginationTemplateProvider.setPath(plugin_url.libs + '/dirPagination.tpl.html');
});