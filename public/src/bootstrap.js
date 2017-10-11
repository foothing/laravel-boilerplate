angular.element(document).ready(function() {
	var auth = angular.injector(['auth', 'app.storage', 'auth.user', 'ng']).get('auth');

	// i.e.
	// uncomment to enable app bootstrap after login.
	// auth.user().then(function(response){
	// 	if (response.data && response.data.id) {
	//		console.log(response);
	//		return angular.bootstrap(document, ['app']);
	//	}

//		else {
//			console.log("Not logged in buddy.")
//		}
//	});

	angular.bootstrap(document, ['app']);
});
