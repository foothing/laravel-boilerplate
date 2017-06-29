angular.element(document).ready(function() {
	var auth = angular.injector(['auth', 'ng']).get('auth');

	// i.e.
	//auth.user().then(function(response){
	// 	if (response.data && response.data.id) {
	//		console.log(response);
	//		return angular.bootstrap(document, ['app']);
	//	}
	//});

	angular.bootstrap(document, ['app']);
});
