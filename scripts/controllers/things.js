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
		{name:'Writings',link:'/things/writings',id:0,tName:'writings'},
		{name:'Music',link:'/things/music',id:1,tName:'music'},
		{name:'Comics',link:'/things/comics',id:2,tName:'comics'},
		{name:'Art',link:'/things/art',id:3,tName:'art'},
		{name:'Tutorials',link:'/things/tutorials',id:4,tName:'tutorials'}
	];

    $scope.showLinks = false;
    $scope.showFilter = false;
    $scope.showList = false;
    $scope.showThing = false;

    $scope.thingslist = [];
    $scope.thing = {};
    $scope.thingType = "things";
    $scope.maxThingsPerPage = 10;

    $scope.getThingsList = function () {
        //No type specified, get all the things
        if($routeParams.thingType === undefined || $routeParams.thingType === "all")
        {
            $http.get('http://schemagames.com/thingdata.php?limit='+$scope.maxThingsPerPage)
            .success(function (data) {
                $scope.thingslist = data.rows;
                var listIter = data.total_rows;
                var linksIter;
                while(listIter--)
                {
                    //Assign the correct type name to replace the id
                    linksIter = $scope.thinglinks.length;
                    while(linksIter--)
                    {
                        if($scope.thinglinks[linksIter].id === $scope.thingslist[listIter].thing_type)
                        {
                            $scope.thingslist[listIter].thing_type = $scope.thinglinks[linksIter].tName;
                            break;
                        }
                    }
                }
                $scope.showList = true;
            });
        }
        //Type specified, get things of that type
        else
        {
            $http.get('http://schemagames.com/thingdata.php?type='+$routeParams.thingType+'&limit='+$scope.maxThingsPerPage)
            .success(function (data) {
                $scope.thingslist = data.rows;
                var listIter = $scope.thingslist.length;
                var linksIter;
                while(listIter--)
                {
                    //Assign the correct type name to replace the id
                    linksIter = $scope.thinglinks.length;
                    while(linksIter--)
                    {
                        if($scope.thinglinks[linksIter].id === $scope.thingslist[listIter].thing_type)
                        {
                            $scope.thingslist[listIter].thing_type = $scope.thinglinks[linksIter].tName;
                            break;
                        }
                    }
                }
                $scope.showList = true;
            });
        }
    };

    $scope.getThingContent = function() {
        $http.get('http://schemagames.com/thingdata.php?id='+$location.search().id)
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
