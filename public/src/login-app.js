(function(angular, module){

	module.controller('FacebookController', ['$scope', FacebookController]);

	function FacebookController($scope) {
		$scope.facebook = function() {
			alert('nope');
		}
	}

})(angular, angular.module('app.login', []));
