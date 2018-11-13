(function(angular, module){

	module.factory('api.user', function() {
		return api = {
			create: {
				verb: "post",
				url: "user"
			},

			update: {
				verb: "put",
				url: "user/{id}"
			},

			setRole: {
				verb: "put",
				url: "user/{id}/role"
			},

			updatePassword: {
				verb: "put",
				url: "user/{id}/password"
			},

			notifications: {
				verb: "get",
				url: "user/{id}/notifications"
			},

			readNotifications: {
				verb: "put",
				url: "user/{id}/notifications"
			},

			readAllNotifications: {
				verb: "put",
				url: "user/{id}/notifications/all"
			}
		}

		return {
			"$get": function() {
				return api;
			}
		}
	})

})(angular, angular.module('api.user', []));
