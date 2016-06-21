/* Setup general page controller */
angular.module('MetronicApp').controller('UsersPageController', ['$rootScope', '$scope', 'settings', 'siteURL', '$http', function($rootScope, $scope, settings, siteURL, $http) {
	
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
	
					
	$scope.allusers = [];
	//retrieve users listing from API
    $http.get(siteURL+"/users")
	.success(function(response) {
		$scope.allusers = response;
	}); 
	
	//show modal form
    $scope.usertoggle = function(modalstate, id) {
        $scope.modalstate = modalstate;
        switch (modalstate) {
            case 'add':
                $scope.form_title = "Add New User";
				$('#frmUsers').each(function(){
					this.reset();
				});
                break;
            case 'edit':
                $scope.form_title = "User Detail";
                $scope.id = id;
                $http.get(siteURL + '/users/edit/' + id)
                        .success(function(response) {
                            //console.log(response[0]);
							$scope.user = response;							
                        });
				checkUserGroup(id);
                break;
            default:
                break;
        }
		
       // console.log(id);
        $('#myModal').modal('show');
    }
	
	//save new record / update existing record
    $scope.saveuser = function(modalstate, id) {
		var url = siteURL + "/users";
        
        //append employee id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/" + id;
        }
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.user),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            if(response){
				console.log('smart'+response.id);
				if (modalstate === 'edit'){
					$scope.allusers = apiModifyTable($scope.allusers,response.id,response);
			   }else{
					$scope.allusers.push(response);
					$('#frmUsers').each(function(){
						this.reset();
					});				
				}
				$("#myModal").modal("hide");
			
			}
        }).error(function(response) {
            //console.log(response);
            alert('This is embarassing. An error has occured. Please check the log for details');
        });
    }

    //delete record
    $scope.confirmDeleteuser = function(id) {
        var isConfirmDelete = confirm('Are you sure you want this record?');
        if (isConfirmDelete) {
            $http({
                method: 'DELETE',
                url: siteURL + '/users/' + id
            }).
                    success(function(data) {
						if(data){
							$("#deleteuser"+id).remove();
						}
                        //console.log(data);
                        //location.reload();
                    }).
                    error(function(data) {
                        //console.log(data);
                        alert('Unable to delete');
                    });
        } else {
            return false;
        }
    }
	function apiModifyTable(originalData,id,response){
		angular.forEach(originalData, function (item,key) {
			if(item.id == id){
				originalData[key] = response;
			}
		});
		return originalData;
	}
	
	function checkUserGroup(id){
		
		$http.get(siteURL + '/users/checkusergroup/'+id)
				.success(function(response) {
					$scope.user.userrole = response[0].role;
					$scope.userrole1 = response[0].role;
				});
	}
	
	
	
}]);
