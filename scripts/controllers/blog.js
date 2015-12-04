(function () {
	'use strict';
	
	/**
	 * @ngdoc function
	 * @name SchemaApp.controller:BlogCtrl
	 * @description
	 * # BlogCtrl
	 * Controller of the SchemaApp
	 */
	angular.module('SchemaApp')
		.controller('BlogCtrl', ["$scope","$http","$location",function ($scope,$http,$location) {
	
		$scope.articlesPerPage=3;
		$scope.noArticles="";
		$scope.newertime = null;
		$scope.oldertime = null;
		$scope.articleslist = [];
	  
		//AJAX requests
		$scope.getArticles = function() {
			$http.get($location.protocol()+'://'+$location.host()+'/blogdata.php?limit='+$scope.articlesPerPage) 
			.success(function (data) {
				$scope.articleslist = data.rows;
				//Set pagination
				if(typeof $scope.articleslist !== 'undefined' && $scope.articleslist.length > 0) {
					var topTime = parseFloat($scope.articleslist[0].post_time) + 1;
					var botTime = parseFloat($scope.articleslist[$scope.articleslist.length - 1].post_time) - 1;
					$scope.setNewerLink(topTime);
					$scope.setOlderLink(botTime);
				}
				else {
					$scope.noArticles="There are no blog entries on this date range";
				}
			})
			.error(function (data) {
					$scope.articleslist = null;
			});
		};
		$scope.getArticlesOlder = function(posttime) {
			var newestPostTime = parseFloat(posttime);
			$http.get($location.protocol()+'://'+$location.host()+'/blogdata.php?limit='+$scope.articlesPerPage+'&older='+newestPostTime)
			.success(function (data) {
				$scope.articleslist = data.rows;
				//Set pagination
				if($scope.articleslist.length > 0) {
					var topTime = parseFloat($scope.articleslist[0].post_time) + 1;
					var botTime = parseFloat($scope.articleslist[$scope.articleslist.length - 1].post_time) - 1;
					$scope.setNewerLink(topTime);
					$scope.setOlderLink(botTime);
				}
				else {
					$scope.noArticles="There are no blog entries on this date range";
				}
			})
			.error(function (data) {
				$scope.articleslist = null;
			});
		};
		$scope.getArticlesNewer = function(posttime) {
			var oldestPostTime = parseFloat(posttime);
			$http.get($location.protocol()+'://'+$location.host()+'/blogdata.php?limit='+$scope.articlesPerPage+'&newer='+oldestPostTime)
			.success(function (data) {
				$scope.articleslist = data.rows;
				//Set pagination
				if($scope.articleslist.length > 0) {
					var topTime = parseFloat($scope.articleslist[0].post_time) + 1;
					var botTime = parseFloat($scope.articleslist[$scope.articleslist.length - 1].post_time) - 1;
					$scope.setNewerLink(topTime);
					$scope.setOlderLink(botTime);
				}
				else {
					$scope.noArticles="There are no blog entries on this date range";
				}
			})
			.error(function (data) {
				$scope.articleslist = null;
			});
		};
		$scope.getArticleCount = function() {
			$http.get($location.protocol()+'://'+$location.host()+'/blogdata.php')
				.success(function (data) {
					$scope.articlecount = data.total_rows;
				})
				.error(function (data) {
					$scope.articlecount = 0;
				});
		};
		$scope.setOlderLink = function(posttime) {
			$http.get($location.protocol()+'://'+$location.host()+'/blogdata.php?older='+posttime)
				.success(function (data) {
					var articlecount;
					if(data.rows.length !== 0) {
						articlecount = data.total_rows;
					}
					else {
						articlecount = 0;
					}
					$scope.oldertime = (articlecount > 0) ?  posttime : null;
				})
				.error(function () {
					$scope.oldertime = null;
				});
		};
		$scope.setNewerLink = function(posttime) {
			$http.get($location.protocol()+'://'+$location.host()+'/blogdata.php?newer='+posttime)
				.success(function (data) {
					var articlecount;
					if(data.rows.length !== 0) {
						articlecount = data.total_rows;
					}
					else {
						articlecount = 0;
					}
					$scope.newertime = (articlecount > 0) ?  posttime : null;
				})
				.error(function () {
					$scope.newertime = null;
				});
		};
	
		//Fetch articles
		if (typeof $location.search().olderthan !== "undefined") {
			$scope.getArticlesOlder($location.search().olderthan);
		}
		else if (typeof $location.search().newerthan !== "undefined") {
			$scope.getArticlesNewer($location.search().newerthan);
		}
		else {
			$scope.getArticles();
		}
	
	}]);
})();