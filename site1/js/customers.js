'use strict';
angular.module('customers', ['ngTable']);

//Factories
myApp.factory('customerServices', ['$http', function ($http) {

        var factoryDefinitions = {
            getCustomers: function (params,count,page,header) {
                var promise = $http({
                    url: 'services/customer-list',
                    method: "POST",
                    headers: {'Content-Type': 'application/json'},
                    data :{
                    'params':params,
                    'count':count,
                    'page':page
                }
                }).success(function (data) {
                    return data;
                }).error(function (data) {
                    return data;
                });
                return promise;                
            },
            custStatusChange: function (Formvalue) {
                console.log(Formvalue);
                var promise = $http({
                    url: 'services/customer-status-change',
                    method: "POST",
                    headers: {'Content-Type': 'application/json'},
                   data: {
                    "standing":Formvalue.standing_enum,
                    "pk_cust_id": Formvalue.pk_cust_id
                }
                }).success(function (data) {
                    return data;
                }).error(function (data) {
                    return data;
                });
                return promise;                
            }
        }

        return factoryDefinitions;
    }
]);

//Controllers
//myApp.controller('customerController', ['$scope','customerServices', '$location', '$rootScope','$timeout','ngTableParams', function ($scope, customerServices, $location, $rootScope,$timeout,ngTableParams) {
myApp.controller('customerController', function($scope, $rootScope, $state, $filter, customerServices, NgTableParams,$timeout) {

    $scope.errmessage='';
    $scope.successmessage='';
    $scope.customerstList = new NgTableParams({
        page: 1,
        sorting: {'fname': "asc"},
        count: 10
    },
    {
        total: 0,
        getData: function($defer, params) {
            customerServices.getCustomers(params.filter(),params.count(),params.page(),$rootScope).then(function(result) {
                if(result.data.status){
                    $scope.customer = result.data.response.data;;
                    params.total(result.data.response.count);
                    $scope.data = params.sorting() ? $filter('orderBy')($scope.customer, params.orderBy()) : $scope.customer;
                    $scope.data = params.filter() ? $filter('filter')($scope.data, params.filter()) : $scope.data;
                    $defer.resolve($scope.data);
                   
                     
               }
            });
        }
    });
   
    $scope.getUserData= function(indexval) {
        $scope.cust = indexval;        
    };
  
    $scope.statusChange=function(Fromvalue){
               
        /*if(Fromvalue.standing=="1")
        {
            console.log("Inactive");
            Fromvalue.standing_enum="0";
        }
        else
        {
            console.log("Active");
            Fromvalue.standing_enum="1";
        }*/
        Fromvalue.standing_enum = Fromvalue.standing_status? "1" :"0";
        console.log(Fromvalue.standing_enum);
        
        customerServices.custStatusChange(Fromvalue).then(function(result) {
            if (result.data.status=="1") {
                $scope.successmessage =result.data.reponse;    
                $timeout(function() {
                    $scope.successmessage ='';    
                }, 5000); 
                
            } else {
                $scope.errmessage =result.data.reponse;    
                $timeout(function() {
                    $scope.errmessage ='';    
                }, 5000); 
            }
        });
    }


    });

