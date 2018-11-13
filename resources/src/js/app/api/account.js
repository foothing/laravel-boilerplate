(function(angular, module){

	module.factory('api.account', function() {
		return api = {
			password: {
				verb: "put",
				url: "account/password"
			}
		}

		return {
			"$get": function() {
				return api;
			}
		}
	})

})(angular, angular.module('api.account', []));
