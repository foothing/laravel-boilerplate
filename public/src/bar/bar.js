(function(angular, module){

	module.run(function(){
		alert('Bar.');
	});

})(angular, angular.module('app.bar', []));