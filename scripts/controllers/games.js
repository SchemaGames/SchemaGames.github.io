(function () {
	'use strict';

	/**
	 * @ngdoc function
	 * @name SchemaApp.controller:GamesCtrl
	 * @description
	 * # GamesCtrl
	 * Controller of the SchemaApp
	 */
	angular.module('SchemaApp')
		.controller('GamesCtrl', ["$scope","$http","$location","$sce",function ($scope, $http,$location,$sce) {

			$scope.gameslist = [];

			$scope.getGamesPreview = function () {
				$http.get('https://api.schemagames.com/game')
					.success(function (data) {
						$scope.gameslist = data;
					});
			};
			$scope.getGame = function (gamename) {
				$http.get('https://api.schemagames.com/game?id='+gamename)
					.success(function (data) {
						$scope.gamedata = data[0];
						$scope.aspectRatio = (data[0].aspect_height / data[0].aspect_width)*100;
						$scope.setUrl(data[0].game_link);
					});
			};

			$scope.manyGames = function (){
				return (typeof $scope.gameslist !== 'undefined');
			};
			$scope.focusGame = function (){
				return (typeof $scope.gamedata !== 'undefined');
			};
			$scope.isEmbedded = function (){
				return ($scope.gamedata === 1);
			};
			$scope.gameTypePresent = function(gType){
				return $scope.gameslist.some(function(game){
					return (game.game_type == gType);
				});
			}

			$scope.setUrl = function (url) {
				$scope.trustedUrl = $sce.trustAsResourceUrl(url);
			};

			if (typeof $location.search().game !== "undefined") {
				$scope.getGame($location.search().game);
			}
			else {
				$scope.getGamesPreview();
			}
		}]);
}());