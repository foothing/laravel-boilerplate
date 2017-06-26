(function(angular, module){

	module.run(function(){
		//alert('Foo.');
	});

	module.factory('foo', function(){
		return {
			echo: function(input) {
				return input;
			}
		}
	});

})(angular, angular.module('app.foo', []));