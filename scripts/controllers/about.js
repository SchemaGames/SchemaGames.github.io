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
		.controller('AboutCtrl', ["$scope","$http","$location",function ($scope,$http,$location) {

			$scope.getRoster = function () {
				$http.get($location.protocol()+'://'+$location.host()+'/rosterdata.php')
					.success(function (data) {
						$scope.roster = data.rows;
					});
			};

			$scope.getRoster();
		}]);
}());
