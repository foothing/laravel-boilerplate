(function(angular, module){


	/**
	 * A wrapper around the various possible storages.
	 * Default: window.localStorage.
	 * ngstorage seems broken: https://github.com/gsklee/ngStorage/issues/39
	 */
	module.provider('defaultStorage', [function(){

		this.$get = function() {
			return {
				set: function(key, value) {
					localStorage.setItem(key, JSON.stringify(value));
				},

				get: function(key) {
					var value = localStorage.getItem(key);
					return JSON.parse(value);
				},

				clear: function(key) {
					localStorage.removeItem(key);
				}
			}
		}

	}]);

})(angular, angular.module("app.storage", []));
