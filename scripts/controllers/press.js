(function () {
    'use strict';

    /**
     * @ngdoc function
     * @name SchemaApp.controller:PressCtrl
     * @description
     * # PressCtrl
     * Controller of the SchemaApp
     */
    angular.module('SchemaApp')
        .controller('PressCtrl', ["$scope","$http",function ($scope,$http) {

            $scope.getRoster = function () {
                $http.get('http://schemagames.com/rosterdata.php')
                    .success(function (data) {
                        $scope.roster = data.rows;
                    });
            };

        }]);
}());
