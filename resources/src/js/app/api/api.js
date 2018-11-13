(function(angular, module){

	module.provider('api', function() {
		var token = null;

		this.setToken = function(_token) {
			token = _token;
		}

		this.$get = ['$http', 'repository', function($http, repository){
			return {
				token: null,

				version: 'v1',

				/**
				 * Cached modules to limit injector calls.
				 */
				modules: {},

				/**
				 * Request parameters.
				 */
				params: {},

				/**
				 * User token.
				 *
				 * @param token
				 */
				setToken: function(token) {
					this.token = token;
				},

				/**
				 * Set request parameter.
				 *
				 * @param key
				 * @param value
				 * @returns {this.$get}
				 */
				set: function(key, value) {
					this.params[key] = value;
					return this;
				},

				/**
				 * Repository wrapper.
				 * @returns {*}
				 */
				data: function() {
					return repository;
				},

				/**
				 * Return the api url.
				 * Example usage: api.url("node.upload", node.id)
				 * Will return the final api url without invoking it.
				 *
				 * @param method
				 * @param resourceId
				 * @returns {*}
				 */
				url: function(method, resourceId) {
					var api = this.getConfig(method);
					return this.getEndpoint(api, method, resourceId);
				},

				/**
				 * Entry point to an api call.
				 *
				 * @param method
				 * @param resourceId
				 * @param params
				 * @returns {*}
				 */
				call: function(method, resourceId, params) {
					// Guess input: if 2nd argument is an object,
					// then it is meant to be params.
					if (typeof resourceId === 'object' && resourceId !== null) {
						params = resourceId;
					}

					var api = this.getConfig(method);

					//if (api.call && typeof api.call === 'function') {
					//	return api.call();
					//}

					return this.callApi(api, method, resourceId, params);
				},

				/**
				 * Private method to call an api.
				 *
				 * @param api The api config object.
				 * @param method
				 * @param resourceId
				 * @param params
				 * @returns {*}
				 */
				callApi: function(api, method, resourceId, params) {
					var verb = api.verb;
					var url = this.getEndpoint(api, method, resourceId);
					var payload = params ? params : this.flush();

					if (verb.toLowerCase() === "get") {
						return this.callGet(url, payload);
					}

					if (verb.toLowerCase() === "delete") {
						return this.callDelete(url, payload);
					}

					return this.callAny(url, verb, payload);
				},

				callGet: function(url, params) {
					return $http.get(url, {params: params}).then(
						function(response) {
							return response.data;
						},

						function(response) {
							console.log('api error', response);
							return null;
						}
					);
				},

				callDelete: function(url, params) {
					return $http.delete(url, {params: params}).then(
							function(response) {
								return response.data;
							},

							function(response) {
								console.log('api error', response);
								return null;
							}
					);
				},

				callAny: function(url, verb, params) {
					return $http[verb](url, params).then(
						function(response) {
							return response.data;
						},

						function(response) {
							console.log('api error', response);
							return null;
						}
					);
				},

				/**
				 * Inject and return an api object.
				 *
				 * @param string method In the form "apiname.method".
				 * @returns {*}
				 */
				getConfig: function(method) {
					// Split api name, method.
					var split = method.split(".");
					var api = split[0];
					var method = split[1];

					// Cache lookup.
					if (module = this.getModule(api)) {
						return module[method];
					}

					// Api module name.
					var moduleName = "api." + api;

					// Inject module.
					var module = angular.injector([moduleName]).get(moduleName);

					// Cache module.
					var cachedModule = this.setModule(api, module);

					// Return the api method.
					return cachedModule[method];
				},

				setModule: function(moduleName, module) {
					this.modules[ moduleName ] = module;
					return module;
				},

				getModule: function(moduleName) {
					return this.modules[ moduleName ];
				},

				getEndpoint: function(api, method, resourceId) {
					var url = api.url.replace(/\{[a-zA-Z]+\}/gi, resourceId);
					return this._url(url);
				},

				_url: function(url) {
					return "/api/" + this.version + "/" + url;
				},

				flush: function() {
					var params = this.params;
					this.params = {};
					return params;
				}
			}
		}];
	});

	module.provider('apiInterceptor', function() {
		this.$get = ['user', 'toastr', '$q', function(user, toastr, $q) {
			return {
				request: function(config) {
					config.headers['Authorization'] = 'Bearer ' + user.token();
					return config;
				},

				response: function(config) {
					var message = config.headers('X-Status-Message');

					if (message) {
						toastr.success(message);
					}

					return config;
				},

				responseError: function(rejection) {
					var message = rejection.headers('X-Status-Message');
					if (message) {
						toastr.error(message);
					}
					return $q.reject(rejection);
				}
			}
		}]
	});

	module.factory('xRequestedWith', function(){
		return {
			request: function(config) {
				config.headers['X-Requested-With'] = 'XMLHttpRequest';
				return config;
			}
		}
	});

	module.config(function($httpProvider){
		$httpProvider.interceptors.push('apiInterceptor');
		$httpProvider.interceptors.push('xRequestedWith');
	});

})(angular, angular.module('api', ['auth.user', 'laravel.client', 'toastr']));
