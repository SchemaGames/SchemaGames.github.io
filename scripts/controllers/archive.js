(function () {
	'use strict';

	/**
	 * @ngdoc function
	 * @name SchemaApp.controller:ArchiveCtrl
	 * @description
	 * # ArchiveCtrl
	 * Controller of the SchemaApp
	 */
	angular.module('SchemaApp')
		.controller('ArchiveCtrl', ["$scope", "$http","$location","$filter", function ($scope, $http,$location) {

			$scope.noArticles="No articles match the search criteria. Dang!";
			$scope.articleslist = [];
			$scope.maxArticlesPerPage = 20; // Currently unused, deprecate or rework
			$scope.searchText = '';

			//DB requests
			$scope.getArticles = function () {
				$http.get('https://api.schemagames.com/article?limit='+$scope.maxArticlesPerPage)
					.success(function (data) {
						$scope.articleslist = data;
						if ($scope.articleslist.length === 0) {
							$scope.noArticles = "There are no blog entries in the archive";
						}
					})
					.error(function () {
						$scope.articleslist = null;
					});
			};

			$scope.getArticles();
		}]);
})();