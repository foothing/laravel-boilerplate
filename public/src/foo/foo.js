(function(angular, module){

	module.run(function(){
		alert('Foo.');
	});

})(angular, angular.module('app.foo', []));