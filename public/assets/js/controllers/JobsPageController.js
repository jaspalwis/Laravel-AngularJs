angular.module('MetronicApp').controller('JobsPageController', function($scope, $http, siteURL) {
	$scope.currentPage = 1;
	$scope.pageSize = 10;
	
	$scope.data = [];
	$scope.servicelists = [];
	$scope.totalItems = 0;
	$scope.totalPages = 0;
	$scope.currentPage = 1;
	$scope.libraryTemp = {};
	$scope.totalItemsTemp = {};
	$scope.isClicked = false;
    //retrieve employees listing from API
    $http.get(siteURL + "/jobslists")
            .success(function(response) {
				 $scope.servicelists = response;
            });
	
    //show modal form
    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;
        switch (modalstate) {
            case 'add':
				//$scope.servicelist.work_date = new Date();
				$scope.form_title = "Add New Job";
				checkallservices();
				checkalltechnicinas();
					
				$('#frmJobs').each(function(){
					this.reset();
				});
                break;
			 case 'view':
				$scope.isClicked = true;
				$scope.form_title = "Job Detail";
				$scope.id = id;
                $http.get(siteURL + '/jobslists/' + id)
                        .success(function(response) {
                            console.log('a'+response);
                            $scope.servicelist = response;
                        });
                break;
            case 'edit':
                $scope.form_title = "Update Job";
                $scope.id = id;
                $http.get(siteURL + '/jobslists/' + id)
                        .success(function(response) {
                            console.log('a'+response);
                            $scope.servicelist = response;
                        });
						checkallservices();
						checkalltechnicinas();
                break;
            default:
                break;
        }
        console.log(id);
        $('#myModal').modal('show');
    }

	$scope.viewtoggle = function(id) {       
		$scope.isClicked = true;
		$scope.form_title = "Job Detail";
		$scope.id = id;
		$http.get(siteURL + '/jobslists/' + id)
				.success(function(response) {
					$scope.servicelist = response;
				});
        $('#myModal1').modal('show');
    }
    //save new record / update existing record
    $scope.save = function(modalstate, id) {
		var url = siteURL + "/jobslists";
        //append employee id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/" + id;
        }
        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.servicelist),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
			if (modalstate === 'edit'){
				alert(response.id);
				$scope.servicelists = apiModifyTable($scope.servicelists,response.id,response);
			}else{
				$scope.servicelists.push(response);
			}
			$("#myModal").modal("hide");
        }).error(function(response) {
            console.log(response);
            alert('This is embarassing. An error has occured. Please check the log for details');
        });
    }

    //delete record
    $scope.confirmDelete = function(id, index) {
        var isConfirmDelete = confirm('Are you sure you want this record?');
        if (isConfirmDelete) {
            $http({
                method: 'DELETE',
                url: siteURL + '/jobslists/' + id
            }).
                    success(function(data) {
                        $scope.servicelists.splice(index,1);
						$("#myModal").modal("hide");
                    }).
                    error(function(data) {
                        console.log('unable'+data);
                        alert('Unable to delete');
                    });
        } else {
            return false;
        }
    }

	function checkallservices(){
		
		$http.get(siteURL + '/jobslists/allservices')
				.success(function(response) {
					return $scope.allservices = response;
				});
	}
	
	function checkalltechnicinas(){
		
		$http.get(siteURL + '/jobslists/alltechnicinas')
			.success(function(response) {
				return $scope.alltechnicinas = response;
			});
	}
	
	function apiModifyTable(originalData,id,response){
		angular.forEach(originalData, function (item,key) {
			if(item.id == id){
				originalData[key] = response;
			}
		});
		return originalData;
	}
	
	// datepicker
	$scope.open = function($event)
	{
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened = true;
	};
	
	
	
});
