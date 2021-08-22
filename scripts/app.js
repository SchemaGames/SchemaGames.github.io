(function () {
    'use strict';

    /**
     * @ngdoc overview
     * @name SchemaApp
     * @description
     * # SchemaApp
     *
     * Main module of the application.
     */

    angular
        .module('SchemaApp', [
            'ngAnimate',
            'ngCookies',
		    'ngMessages',
            'ngResource',
            'ngRoute',
            'ngSanitize',
            'ngTouch',
		    'vcRecaptcha'
        ])
        .config(function ($routeProvider, $locationProvider) {
            $locationProvider.html5Mode(true);
            $routeProvider
                .when('/', {
                    templateUrl: 'views/main.html',
                    controller: 'MainCtrl'
                })
                .when('/games', {
                    templateUrl: 'views/games.html',
                    controller: 'GamesCtrl'
                })
                .when('/about', {
                    templateUrl: 'views/about.html',
                    controller: 'AboutCtrl'
                })
                .when('/blog', {
                    templateUrl: 'views/blog.html',
                    controller: 'BlogCtrl'
                })
                .when('/things', {
                    templateUrl: 'views/things.html',
                    controller: 'ThingsCtrl'
                })
	            .when('/things/:thingType', {
		            templateUrl: 'views/things.html',
		            controller: 'ThingsCtrl'
	            })
	            .when('/privacy', {
		            templateUrl: 'views/privacy.html',
		            controller: 'ThingsCtrl'
	            })
                .otherwise({
                    redirectTo: '/'
                });
        });
}());