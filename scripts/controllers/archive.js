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
		.controller('ArchiveCtrl', ["$scope", "$http","$filter", function ($scope, $http) {

			$scope.noArticles="No articles match the search criteria. Dang!";
			$scope.articleslist = [];
			$scope.maxArticlesPerPage = 20;
			$scope.searchText = '';

			//DB requests
			$scope.getArticles = function () {
				$http.get('http://schemagames.com/blogdata.php?limit='+$scope.maxArticlesPerPage)
					.success(function (data) {
						$scope.articleslist = data.rows;
						if ($scope.articleslist.length === 0) {
							$scope.noArticles = "There are no blog entries in the archive";
						}
						else if($scope.articleslist.length > $scope.maxArticlesPerPage)
						{
							$scope.articleslist.push({"value":{"title":"...","post_time":0}});
						}
					})
					.error(function () {
						$scope.articleslist = null;
					});
			};

			$scope.getArticles();
		}]);
})();