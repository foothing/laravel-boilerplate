(function(angular, module){

	module.provider('user', [function(){

		this.$get = ['defaultStorage', function(storage){
			return {

				token: function() {
					var user = this.get();
					return user ? user.api_token : null;
				},

				set: function(user) {
					storage.set("user", JSON.stringify(user));
				},

				get: function() {
					var user = storage.get("user");
					return JSON.parse(user);
				},

				clear: function() {
					storage.clear("user");
				}
			}
		}]

	}]);

})(angular, angular.module("auth.user", ['app.storage']));
