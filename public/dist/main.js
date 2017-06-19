(function(angular, module){

	module.run(function(){
		alert('Boilerplate.');
	});

})(angular, angular.module('app', ['app.foo', 'app.bar']));
(function(angular, module){

	module.run(function(){
		alert('Bar.');
	});

})(angular, angular.module('app.bar', []));
(function(angular, module){

	module.run(function(){
		alert('Foo.');
	});

})(angular, angular.module('app.foo', []));