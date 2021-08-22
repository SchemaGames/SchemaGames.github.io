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
				$http.get('db/games.json')
					.success(function (data) {
						$scope.gameslist = data;
					});
			};
			$scope.getGame = function (gameid) {
				$http.get('db/games.json')
					.success(function (data) {
						let chosen_game = data.find(game => game.game_id == gameid);
						if(chosen_game) {
							$scope.gamedata = chosen_game;
							$scope.aspectRatio = (chosen_game.aspect_height / chosen_game.aspect_width)*100;
							$scope.setUrl(chosen_game.game_link);
						} else {
							console.log("Couldn't find game with id in data:", gameid, data);
						}
					});
			};

			$scope.manyGames = function (){
				return ($scope.gameslist.length > 0);
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

			if (typeof $location.search().id !== "undefined") {
				$scope.getGame($location.search().id);
			}
			else {
				$scope.getGamesPreview();
			}
		}]);
}());