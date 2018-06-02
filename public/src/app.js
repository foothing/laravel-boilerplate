(function(angular, module){

	module.config(function($stateProvider, $urlRouterProvider){

		$urlRouterProvider.otherwise('/');

		$stateProvider.state('root', {
			url: '',
			template: 'root state'
		});
	});

	module.config(function($translateProvider){
		$translateProvider.useStaticFilesLoader({
			prefix: '/assets/locales/locale-',
			suffix: '.json'
		});

		$translateProvider.preferredLanguage('it');
	});

	module.run(function(auth, PermPermissionStore){
		//alert('Boilerplate.');
		// Do something with permissions.
	});

})(angular, angular.module('app', ['ui.router', 'ui.bootstrap', 'pascalprecht.translate', 'auth', 'app.foo', 'app.bar']));