(function () {
	'use strict';

	/**
	 * @ngdoc function
	 * @name SchemaApp.controller:AboutCtrl
	 * @description
	 * # AboutCtrl
	 * Controller of the SchemaApp
	 */
	angular.module('SchemaApp')
		.controller('AboutCtrl', ["$scope","$http",function ($scope,$http) {

			$scope.getRoster = function () {
				$http.get('db/roster.json')
					.success(function (data) {
						$scope.roster = data;
					});
			};

			$scope.getRoster();
		}]);
}());
