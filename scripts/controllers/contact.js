(function () {
	'use strict';

	/**
	 * @ngdoc function
	 * @name SchemaApp.controller:ContactCtrl
	 * @description
	 * # ContactCtrl
	 * Controller of the SchemaApp
	 */
	angular.module('SchemaApp')
		.controller('ContactCtrl', ["$scope", "$http", "$location", "vcRecaptchaService","notify", function ($scope, $http, $location, vcRecaptchaService, notify) {
			$scope.formInfo = {};
			$scope.recaptchaKey = "6LfNnAETAAAAAPuK8eXXA6WgmZexg3-rNcMH9TtK";

			$scope.sendForm = function() {
				//Only send form if required fields are filled
				if($scope.formInfo.email && $scope.formInfo.message && $scope.formInfo.recaptchaResponse){
					$http.get($location.protocol()+'://'+$location.host()+'/checkrecaptcha.php?recaptcha='+$scope.formInfo.recaptchaResponse).
						success(function(data){
							//Check whether the validation step succeeded
							if(data.success === true){
								$http({
									method: 'POST',
									url: $location.protocol()+'://'+$location.host()+'/sendmail.php',
									headers: {'Content-Type': 'application/x-www-form-urlencoded'},
									transformRequest: function(obj) {
										var str = [];
										for(var p in obj) {
											if (obj.hasOwnProperty(p)) {
												str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
											}
										}
										return str.join("&");
									},
									data: {from: $scope.formInfo.email,subject: '[Contact Form] '+$scope.formInfo.email,message: $scope.formInfo.message}
								}).success(function(){
										notify.createNotification('Form sent successfully!');
										$scope.cleanForm();
									}).error(function(){
										notify.createNotification('Form send failed - refresh and try again');
									});
							}
						}).error(function(){
							notify.createNotification("Recaptcha validation failed");
						});
				}
				else
				{
					notify.createNotification("Some form fields are not filled!");
					return;
				}
			};

			$scope.sendMail = function() {
				$http.post($location.protocol()+'://'+$location.host()+'/sendmail.php');
			};

			$scope.setResponse = function(res){
				$scope.formInfo.recaptchaResponse = res;
			};

			$scope.cleanForm = function() {
				$scope.formInfo.email = null;
				$scope.formInfo.message = null;
				$scope.formInfo.recaptchaResponse = null;
				grecaptcha.reset();
				$scope.contactSchema.$setPristine();
				$scope.contactSchema.$setUntouched();
			};
		}]);
})();