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

		$scope.prepareText = function() {
			var re_newline = /[\n\r]/g;
			for(var i = 0, len = $scope.articleslist.length; i < len; i++)
			{
				$scope.articleslist[i].article_text = $scope.articleslist[i].article_text.replace(re_newline,"<br />");
			}
		};
		$scope.nil = {};
		$scope.takeWhile = function(f, [x = nil, ...xs]) {
			return (x === nil || !f(x)) ? [] : [x, ...$scope.takeWhile(f, xs)];
		};
		$scope.dropWhile = function(f, [x = nil, ...xs]) {
			return (x === nil) ? [] : 
				(f(x) ? $scope.dropWhile(f, xs) : [x, ...xs]);
		};
		//AJAX requests
		$scope.getArticles = function() {
			//$http.get('https://api.schemagames.com/article?limit='+$scope.articlesPerPage)
			$http.get('db/articles.json')
			.success(function (data) {
				let sortedData = data.sort((a, b) => b.post_time - a.post_time).slice(0, $scope.articlesPerPage);
				$scope.articleslist = sortedData;
				//Set pagination
				if(typeof $scope.articleslist !== 'undefined' && $scope.articleslist.length > 0) {
					var topTime = parseFloat($scope.articleslist[0].post_time) + 1;
					var botTime = parseFloat($scope.articleslist[$scope.articleslist.length - 1].post_time) - 1;
					$scope.setNewerLink(topTime, sortedData);
					$scope.setOlderLink(botTime, sortedData);
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
			$http.get('db/articles.json')
			.success(function (data) {
				let sortedData = data.sort((a, b) => b.post_time - a.post_time);
				let takenData = $scope.dropWhile((x) => x.post_time >= newestPostTime, sortedData).slice(0, $scope.articlesPerPage);
				$scope.articleslist = takenData;
				//Set pagination
				if($scope.articleslist.length > 0) {
					var topTime = parseFloat($scope.articleslist[0].post_time) + 1;
					var botTime = parseFloat($scope.articleslist[$scope.articleslist.length - 1].post_time) - 1;
					$scope.setNewerLink(topTime, sortedData);
					$scope.setOlderLink(botTime, sortedData);
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
			$http.get('db/articles.json')
			.success(function (data) {
				let sortedData = data.sort((a, b) => b.post_time - a.post_time);
				let takenData = $scope.takeWhile((x) => x.post_time >= oldestPostTime, sortedData).slice(0, $scope.articlesPerPage);
				$scope.articleslist = takenData;
				//Set pagination
				if($scope.articleslist.length > 0) {
					var topTime = parseFloat($scope.articleslist[0].post_time) + 1;
					var botTime = parseFloat($scope.articleslist[$scope.articleslist.length - 1].post_time) - 1;
					$scope.setNewerLink(topTime, sortedData);
					$scope.setOlderLink(botTime, sortedData);
				}
				else {
					$scope.noArticles="There are no blog entries on this date range";
				}
			})
			.error(function (data) {
				$scope.articleslist = null;
			});
		};
		$scope.setOlderLink = function(posttime, sortedData) {
			if(sortedData.some(data => data.post_time <= posttime)) {
				$scope.oldertime = posttime;
			} else {
				$scope.oldertime = null;
			}
		};
		$scope.setNewerLink = function(posttime, sortedData) {
			if(sortedData.some(data => data.post_time >= posttime)) {
				$scope.newertime = posttime;
			} else {
				$scope.newertime = null;
			}
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