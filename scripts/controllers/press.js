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
        .controller('PressCtrl', ["$scope","$http","$location",function ($scope,$http,$location) {

            $scope.getRoster = function () {
                $http.get($location.protocol()+'://'+$location.host()+'/rosterdata.php')
                    .success(function (data) {
                        $scope.roster = data.rows;
                    });
            };

        }]);
}());
