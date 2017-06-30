var app = angular.module("myApp", ["ngTable"]);

/*
angular.module('myApp',[]).config(function($httpProvider){
   $httpProvider.defaults.headers.common.Accept="applcation/json";
});


angular.module('myApp').configure(function($httpProvider){
     $httpProvider.defaults.headers.common.Accept="applcation/json";
});
*/

app.controller("dpiRulesListController", function ($http,$scope, $filter,$q, $window, ngTableParams) {
/*
        $http.get('/sw-frontend-dpi-api.php?action=protocol' ,{headers: {'Content-Type': 'application/json'}}).success(function(response) {
          $scope.protocols=angular.fromJson(angular.toJson(response));
          console.log($scope.protocols);
        });

*/
/*
   $categories = dbFetchRows("select file  as 'id',file as 'title' from dpi_rules group by file");
   print("\n\$scope.categories=" .  json_encode($categories). ";\n");
*/

    $scope.categories = $q.defer();
    $http.get("/sw-frontend-dpi-api.php?action=file").then(function(resp){
        var data=resp.data;
        //console.log("scope.categories2");
        //console.log(JSON.stringify(data));
        $scope.categories.resolve(resp.data);
    });

   $scope.deleteEntity = function (rule) {
      console.log("delete");
      $scope.aws=$window.confirm("Are you sure to delete this rule?\n\n" + rule.options );

      if ($scope.aws) {
         console.log(rule["id"]);
         var data = {
           "action": "delete",
           "id": rule["id"],
           "token": "york1234",
           "api_key": "1123232"
         };


        $http({
           url: '/sw-frontend-dpi-api.php?action=delete',
           dataType: 'json',
            method: 'POST',
            data: JSON.stringify(data),
            headers: {
               "Content-Type": "application/json"
            }

       }).success(function(response){
           //console.log(response);
           $scope.tableParams.reload();
           $window.location.reload();
           alert("Deleted");

       }).error(function(error){
           $scope.error = error;
       });
     }

     return false;
   }
/*
    //deleteUser = $window.confirm('Are you sure you want to delete this dpi rule ?');
    //if(deleteUser){
    bootbox.confirm("Are you sure you want to delete this dpi rule ?", function (confirmation) {
            if (confirmation) {
                $http.delete("/sw-frontend-dpi-api.php?sid=?url/" + entity.sid)
                    .success(function (data) {
                        if (data.status == 1) {
                            var index = _.indexOf($scope.data, entity);
                            $scope.data.splice(index, 1);
                            $scope.tableParams.reload();
                        } else {
                        }
                    });
               console.log("deleted");
            }
        }
    );
};
*/

console.log("init OK");

        $scope.protocols=[
            {id:'',title:''},
            {id:'IP',title:'IP'},
                {id:'ICMP',title:'ICMP'},
                {id:'TCP',title:'TCP'},
                {id:'UDP',title:'UDP'}
        ];

        $http.get('/sw-frontend-dpi-api.php',{headers: {'Content-Type': 'application/json'}}).success(function(response) {

                $scope.users=response;
                console.log($scope);
                $scope.filter= {
                        SID: undefined,
                        file: undefined,
                        protocol: undefined
                };
                $scope.tableParams = new ngTableParams({
                        page: 1,
                        count: 25,
                        sorting: { file: 'asc' },
                        filter: $scope.filter
                        }, {
                                total: $scope.users.length,
                                getData: function ($defer, params) {
                                                        $scope.data = params.sorting() ? $filter('orderBy')($scope.users, params.orderBy()) : $scope.users;
                                                        $scope.data = params.filter() ? $filter('filter')($scope.data, params.filter()) : $scope.data;
                                                        $scope.data = $scope.data.slice((params.page() - 1) * params.count(), params.page() * params.count());
                                                        $defer.resolve($scope.data);
                                                }
                });
        });
});


   

