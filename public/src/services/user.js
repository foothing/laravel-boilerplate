(function(angular, module){

	module.provider('$user', [function(){

		this.$get = ['defaultStorage', function(storage){
			return {

				token: function() {
					var user = this.get();
					return user ? user.api_token : null;
				},

				set: function(user) {
					storage.set("user", user);
				},

				get: function() {
					return storage.get("user");
				},

				clear: function() {
					storage.clear("user");
				}
			}
		}]

	}]);

})(angular, angular.module("auth.user", ['app.storage']));
