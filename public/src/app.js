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

	module.run(function(){
		//alert('Boilerplate.');
	});

})(angular, angular.module('app', ['ui.router', 'ui.bootstrap', 'pascalprecht.translate', 'app.foo', 'app.bar']));