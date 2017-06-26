angular.element(document).ready(function() {
	var auth = angular.injector(['auth', 'ng']).get('auth');

	// i.e.
	// auth.user().then(function(response){
	// 	console.log(response);
	//	angular.bootstrap(document, ['app']);
	// });

	// @TODO set app name.
	angular.bootstrap(document, ['app']);
});
