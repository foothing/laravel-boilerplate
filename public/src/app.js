(function(angular, module){

	module.config(function($stateProvider, $urlRouterProvider){

		$urlRouterProvider.otherwise('/');

		$stateProvider.state('root', {
			url: '',
			template: 'root state'
		});
	});

	module.run(function(){
		//alert('Boilerplate.');
	});

})(angular, angular.module('app', ['ui.router', 'ui.bootstrap', 'app.foo', 'app.bar']));