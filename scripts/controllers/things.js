(function() {
'use strict';

/**
 * @ngdoc function
 * @name SchemaApp.controller:ThingsCtrl
 * @description
 * # ThingsCtrl
 * Controller of the SchemaApp
 */
angular.module('SchemaApp')
.controller('ThingsCtrl', ["$scope","$http","$location","$routeParams","$sce",function ($scope,$http,$location,$routeParams,$sce) {

	$scope.thinglinks = [
        {name:'All',link:'/things/all'},
		{name:'Writings',link:'/things/writings'},
		{name:'Music',link:'/things/music'},
		{name:'Comics',link:'/things/comics'},
		{name:'Art',link:'/things/art'},
		{name:'Tutorials',link:'/things/tutorials'}
	];

    $scope.showLinks = false;
    $scope.showFilter = false;
    $scope.showList = false;
    $scope.showThing = false;

    $scope.thingslist = [];
    $scope.thing = {};
    $scope.thingType = "things";
    $scope.maxThingsPerPage = 20;

    $scope.getThingsList = function () {
        //No type specified, get all the things
        if($routeParams.thingType === undefined || $routeParams.thingType === "all")
        {
            $http.get('https://api.schemagames.com/thing?limit='+$scope.maxThingsPerPage)
            .success(function (data) {
                $scope.thingslist = data;
                $scope.showList = true;
            });
        }
        //Type specified, get things of that type
        else
        {
            $http.get('https://api.schemagames.com/thing?type='+$routeParams.thingType+'&limit='+$scope.maxThingsPerPage)
            .success(function (data) {
                $scope.thingslist = data;
                $scope.showList = true;
            });
        }
    };

    $scope.getThingContent = function() {
        $http.get('https://api.schemagames.com/thing?id='+$location.search().id)
            .success(function (data) {
                $scope.thing = data;
            });
    };

    if(typeof $location.search().id !== "undefined")
    {
        // A thing ID is set, use it to display that thing
        $scope.showThing = true;
        $scope.getThingContent();
        $scope.thing.content_url = $sce.trustAsResourceUrl($scope.thing.content_url);
    }
    else if (typeof $routeParams.thingType === "undefined") {
        // At the top level of things, simply display links as usual
        $scope.showLinks = true;
        $scope.getThingsList();
    }
    else
    {
        // A thing category is set, display a list of things of the specified type
        $scope.showFilter = true;
        $scope.thingType = $routeParams.thingType;
        $scope.getThingsList();
    }

}]);
})();