'use strict';

angular.module('SchemaApp').directive('datebifilter', function() {
	return {
		require: 'ngModel',
		link: function(scope, element, attrs, ngModelController) {
			ngModelController.$parsers.push(function(data) {
				//convert data from view format to model format
				return Date.parse(data); //converted
			});

			ngModelController.$formatters.push(function(data) {
				//convert data from model format to view format
				return (new Date(data)).toDateString(); //converted
			});
		}
	};
});