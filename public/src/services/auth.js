(function(angular, module){

	module.provider('auth', [function(){
		this.$get = ['$http', function($http){
			return {
				user: function(){
					return $http.get('/auth/user');
				}
			}
		}]
	}]);

})(angular, angular.module('auth', []));