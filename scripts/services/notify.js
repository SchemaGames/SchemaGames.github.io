(function () {
	'use strict';

	/**
	 * @ngdoc function
	 * @name SchemaApp.service.Notify
	 * @description
	 * # Notify
	 * Service of the SchemaApp
	 */
	angular.module('SchemaApp')
		.service('notify', function () {
			this.notifications = [];

			this.createNotification = function(message) {
				this.notifications.push({'message':message,'hide':false});
			};

			this.removeNotification = function(id) {
				this.notifications.splice(id,1);
			};

		});
}());
