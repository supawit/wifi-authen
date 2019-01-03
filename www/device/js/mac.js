// JavaScript Document
var macApp = angular.module('macApp',['ui.bootstrap']);

macApp.controller('signinController', function($scope,$http){
	$scope.user = {}
	$scope.signin = function(){
		$http.post('controls/login.php',$scope.user).
		success(function(data){
			eval(data);
		});
	};	
});

macApp.controller('mainController',function($scope,$http,$modal){
	$http.get('controls/check_login.php').
	success(function(data){
		eval(data);
	});
	
	$scope.macList = [];
	$http.get('controls/getMac.php').
	success(function(data){
		$scope.macList = data;
	});
	
	$scope.deleteMac = function(mac){
		if(confirm("Are you sure?")){
			$http.post('controls/delMac.php',mac).
			success(function(){
				var index = $scope.macList.indexOf(mac);
				$scope.macList.splice(index,1);
			});
		}
	};
	
	$scope.mac = {};
	$scope.openMacModal = function(){
		var modalInstant =$modal.open({
			templateUrl: 'macModal.html',
			controller: 'macModalInstanceCtrl',
			scope: $scope
		});
		
		modalInstant.result.then(
			function(result){
				var data = $scope.mac;
				$http.post('controls/addMac.php',data).
				success(function(data){
					eval(data);
				});
			},
			function(){
				
			}
		);
	};	
	
});

macApp.controller('macModalInstanceCtrl',function($scope,$modalInstance){
	$scope.macModalOK = function(){
		$modalInstance.close('OK');
	};	
	$scope.macModalCancel = function(){
		$modalInstance.dismiss('Cancel');
	}
});
