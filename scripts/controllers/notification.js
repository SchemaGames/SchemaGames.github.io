(function () {
	'use strict';

	/**
	 * @ngdoc function
	 * @name SchemaApp.controller:NotifyCtrl
	 * @description
	 * # NotifyCtrl
	 * Controller of the SchemaApp
	 */
	angular.module('SchemaApp')
		.controller('NotifyCtrl', ["$scope","notify",function ($scope,notify) {
			$scope.notifications = notify.notifications;

			$scope.closeNotification = function (index){
				//Do this to avoid delay in closing
				$scope.notifications[index].hide = true;
				notify.removeNotification(index);
			};
		}]);
}());
