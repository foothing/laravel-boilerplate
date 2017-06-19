/*
Sample:
var $http = angular.injector(['ng']).get('$http');

$http.get('/').then(function(response){
	angular.element(document).ready(function() {
		 // @TODO set app name.
		 angular.bootstrap(document, ['app']);
	});
});
*/

angular.element(document).ready(function() {
	// @TODO set app name.
	angular.bootstrap(document, ['app']);
});
