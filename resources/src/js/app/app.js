(function(angular, module){

	module.config(function($stateProvider, $urlRouterProvider){

		$urlRouterProvider.otherwise('/');

		$stateProvider.state('root', {
            url: '',
            views: {
                "navbar": {
                    templateUrl: 'partials/navbar.html',
                    controller: function($scope, $state) {
                        $scope.$state = $state;
                    }
                },

                "main": {
                    template: 'App main.'
                },

                "title": {
                    template: 'App title.'
                },

                "user": {
                    templateUrl: 'partials/navbar-user.html',
                    controller: function($scope, $user) {
                        $scope.user = $user.get();
                    }
                },

                "notificationIcon": {
                    templateUrl: 'partials/notifications-icon.html',
                    controller: function($scope, $rootScope) {
                        $rootScope.$on("notifications.updated", function(event, data){
                            $scope.icon = false;
                            check(data);
                        });

                        function check(notifications) {
                            angular.forEach(notifications, function(item){
                                if (! item.read_at) {
                                    $scope.icon = true;
                                }
                            });
                        }
                    }
                },

                "notifications": {
                    templateUrl: 'partials/notifications.html',
                    controller: function($scope, $rootScope, notifications, api, $user, $state) {
                        $scope.notifications = notifications;

                        $rootScope.$emit("notifications.updated", notifications);

                        // This because notifications html sucks
                        // and doesn't allows for <a> inside the items.
                        $scope.go = function() {
                            $state.go('app.brand');
                        }

                        $scope.markAsRead = function(notification) {
                            api.call('user.readNotifications', $user.get().id, {id: notification.id}).then(function(response){
                                $scope.notifications = response;
                                $rootScope.$emit("notifications.updated", response);
                            });
                        }

                        $scope.markAllAsRead = function() {
                            api.call('user.readAllNotifications', $user.get().id).then(function(response){
                                $scope.notifications = response;
                                $rootScope.$emit("notifications.updated", response);
                            });
                        }
                    }
                }
            },

            resolve: {
                notifications: function(api, $user) {
                    var user = $user.get();
                    return user ? api.call('user.notifications', $user.get().id) : null;
                }
            }
		});
	});

	module.config(function($translateProvider){
		$translateProvider.useStaticFilesLoader({
			prefix: '/assets/locales/locale-',
			suffix: '.json'
		});

		$translateProvider.preferredLanguage('it');
	});

	module.run(function(auth){
		//alert('Boilerplate.');
	});

})(angular, angular.module('app', ['ui.router', 'ui.bootstrap', 'pascalprecht.translate', 'auth', 'app.foo', 'app.bar']));