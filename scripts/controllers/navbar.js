(function() {
	'use strict';
	
	/**
	 * @ngdoc function
	 * @name SchemaApp.controller:NavbarCtrl
	 * @description
	 * # NavbarCtrl
	 * Controller of the SchemaApp
	 */
	 var getIndexIfObjWithOwnAttr = function(array, attr, value) {
	    for(var i = 0; i < array.length; i++) {
	        if(array[i].hasOwnProperty(attr) && array[i][attr] === value) {
	            return i;
	        }
	    }
	    return -1;
	};
	 
	angular.module('SchemaApp')
	  .controller('NavbarCtrl', ['$scope','$route','$location',function ($scope,$route,$location) {
		
		$scope.navlinks = [
		{name:'Home',link:'/'},
		{name:'Games',link:'/games',},
		{name:'About',link:'/about'},
		{name:'Blog',link:'/blog'},
		{name:'Things',link:'/things'},
		];
		
		$scope.offsetPercent = 5;
		
		$scope.navSelect = 'images/navselect.png';
		
		$scope.routeIndex = function() {
			return getIndexIfObjWithOwnAttr($scope.navlinks,'link',$location.path());
		};
		
		$scope.setNav = function($index) {
			var vPercent = $index*25;
			return {
				'width': '180px',
				'padding': '30px',
				'background': 'url(\'images/navsprite.png\') no-repeat',
				'background-size': '100% '+$scope.navlinks.length*100+'%',
				'background-position': '0 '+vPercent+'%'
			};
		};
		
		$scope.setDot = function($index) {
			if($scope.routeIndex() === $index){
				return {'opacity':'1'};
			}
			else {
				return {'opacity':'0'};
			}
		};
	
		$scope.setTopNav = function($index) {
			var vPercent = $index*25;
			return {
				'display': 'inline-block',
				'padding': '30px 90px',
				'background': 'url(\'images/navsprite.png\') no-repeat',
				'background-size': '100% '+$scope.navlinks.length*100+'%',
				'background-position': '0 '+vPercent+'%'
			};
		};
		
	}]);
})();