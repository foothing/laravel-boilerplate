(function(angular, module){

	module.provider('auth', [function(){
		this.$get = ['$http', 'user', function($http, user){
			return {
				user: function(){
					return $http.get('/auth/user').then(function(response){
						// Clear user storage.
						user.clear();

						// Set new user.
						user.set(response.data);

						// Return.
						return response.data;
					});
				}
			}
		}]
	}]);

})(angular, angular.module('auth', ['auth.user']));