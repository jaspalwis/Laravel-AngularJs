/* Setup general page controller */
angular.module('MetronicApp').controller('LogsPageController', ['$rootScope', '$scope', 'settings', 'siteURL', '$http', function($rootScope, $scope, settings, siteURL, $http) {
    $scope.$on('$viewContentLoaded', function() {   
    	// initialize core components
    	App.initAjax();

    	// set default layout mode
    	$rootScope.settings.layout.pageContentWhite = true;
        $rootScope.settings.layout.pageBodySolid = false;
        $rootScope.settings.layout.pageSidebarClosed = false;
    });
	
	$scope.currentPage = 1;
	$scope.pageSize = 10;
	
	$scope.logs = [];
	//retrieve users listing from API
    $http.get(siteURL+"/logs")
	.success(function(response) {
		$scope.logs = response;
	}); 
	
	
}]);
