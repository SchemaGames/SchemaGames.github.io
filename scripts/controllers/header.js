'use strict';

/**
 * @ngdoc function
 * @name SchemaApp.controller:HeaderCtrl
 * @description
 * # HeaderCtrl
 * Controller of the SchemaApp
 */
angular.module('SchemaApp')
	.controller('HeaderCtrl', ['$scope',function ($scope) {
		$scope.imgheader = 'images/schemabanner.png';
		$scope.numcaptions = 15;

		$scope.caption = Math.floor(Math.random()*$scope.numcaptions);
}]);
