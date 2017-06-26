angular.element(document).ready(function() {
	var auth = angular.injector(['auth', 'ng']).get('auth');

	// i.e.
	//auth.user().then(function(response){
	// 	if (response && response.id) {
	//		console.log(response);
	//		return angular.bootstrap(document, ['app']);
	//	}
	//});

	angular.bootstrap(document, ['app']);
});
