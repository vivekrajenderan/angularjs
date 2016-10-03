'use strict';
angular.module('customers1', ['ngTable']);

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
            }
        }

        return factoryDefinitions;
    }
]);

//Controllers
//myApp.controller('customerController', ['$scope','customerServices', '$location', '$rootScope','$timeout','ngTableParams', function ($scope, customerServices, $location, $rootScope,$timeout,ngTableParams) {
myApp.controller('customerController', function($scope, $rootScope, $state, $filter, customerServices, NgTableParams) {

    //customerController.$inject = ["ngTableParams", "ngTableSimpleList", "$scope"];

    
                  
    

    $scope.simpleList={};var originalData ='';
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
                    $scope.customer = result.data.response.data;                                  
                    params.total(result.data.response.count);
                    $scope.data = params.sorting() ? $filter('orderBy')($scope.customer, params.orderBy()) : $scope.customer;
                    $scope.data = params.filter() ? $filter('filter')($scope.data, params.filter()) : $scope.data;
                    $defer.resolve($scope.data);
                    originalData = angular.copy($scope.customer);
               }
            });
        },
        
    });
    
    console.log(getData);
   var self = this;

    
    console.log(originalData);
    /*self.tableParams = new NgTableParams({}, {
      filterDelay: 0,
      dataset: angular.copy($scope.customerstList)
    });*/

    $scope.getUserData= function(indexval) {
        $scope.cust = indexval;        
    };

    $scope.del=function(row) {
        console.log("Agagag");
      _.remove(self.tableParams.settings().dataset, function(item) {
        return row === item;
      });
      self.tableParams.reload().then(function(data) {
        if (data.length === 0 && self.tableParams.total() > 0) {
          self.tableParams.page(self.tableParams.page() - 1);
          self.tableParams.reload();
        }
      });
    }
    $scope.cancel=function(row, rowForm) {
        console.log(rowForm);
      var originalRow = $scope.resetRow(row, rowForm);
      angular.extend(row, originalRow);
    }

    $scope.resetRow=function(row, rowForm){
        console.log(rowForm);
        row.isEditing = false;
        rowForm.$setPristine();

        for ( let i in originalData){
        if(originalData[i].id === row.id){
        return originalData[i]
        }
        }
      /*self.tableTracker.untrack(row);
      return _.findWhere(originalData, function(r){
        return r.id === row.id;
      });*/
    }

    });

