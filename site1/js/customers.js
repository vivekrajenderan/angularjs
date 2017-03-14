'use strict';
angular.module('customers', ['ngTable']);

//Html tag convert
myApp.filter('to_trusted', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        };
    }]);


myApp.directive("ngFileUpload", function () {
    return {
        link: function (scope, el, attrs) {
            el.bind("change", function (e) {
                var sjd = (e.srcElement || e.target).files[0];
                //console.log(sjd.type);
                var match = attrs.filetype.split(',');
                var fileindex = attrs.fileindex;
                var chktype = true;
                for (var jm = 0; jm < match.length; jm++) {
                    if (chktype == true && sjd.type == match[jm])
                        chktype = false;
                }
                if (chktype) {
                    //alert('This is not a valid file');
                    //return false;
                } else if (sjd.size > parseInt(attrs.filesize)) {
                    //alert('Allowed maximum ' + (parseInt(attrs.filesize) / 1000) + ' kb size only');
                    //return false;
                } else {
                    var upfile = {file: sjd, fileresult: (e.srcElement || e.target)};
                    if (fileindex)
                        scope.getUploadFile(upfile, fileindex);
                    else
                        scope.getUploadFile(upfile);
                }
            });
        }
    };
});


//Factories
myApp.factory('customerServices', ['$http', function ($http) {

        var factoryDefinitions = {
            getCustomers: function (params, count, page, header) {
                var promise = $http({
                    url: '/customer-list',
                    method: "POST",
                    headers: {'Content-Type': 'application/json'},
                    data: {
                        'params': params,
                        'count': count,
                        'page': page
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
                        "standing": Formvalue.standing_enum,
                        "pk_cust_id": Formvalue.pk_cust_id
                    }
                }).success(function (data) {
                    return data;
                }).error(function (data) {
                    return data;
                });
                return promise;
            },
            addcustomer: function (customerdata) {
                var promise = $http({
                    url: '/add-customer',
                    method: "POST",
                    headers: {'Content-Type': undefined},
                    data: customerdata
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
myApp.controller('customerController', function ($scope, $rootScope, $state, $filter, customerServices, NgTableParams, $timeout, $location) {

    $scope.errmessage = '';
    $scope.successmessage = '';
    $scope.customerstList = new NgTableParams({
        page: 1,
        sorting: {'fname': "asc"},
        count: 10
    },
    {
        total: 0,
        getData: function ($defer, params) {
            customerServices.getCustomers(params.filter(), params.count(), params.page(), $rootScope).then(function (result) {
                if (result.data.status) {
                    $scope.customer = result.data.response.data;
                    ;
                    params.total(result.data.response.count);
                    $scope.data = params.sorting() ? $filter('orderBy')($scope.customer, params.orderBy()) : $scope.customer;
                    $scope.data = params.filter() ? $filter('filter')($scope.data, params.filter()) : $scope.data;
                    $defer.resolve($scope.data);


                }
            });
        }
    });

    $scope.getUserData = function (indexval) {
        $scope.cust = indexval;
    };

    $scope.statusChange = function (Fromvalue) {

        Fromvalue.standing_enum = Fromvalue.standing_status ? "1" : "0";
        console.log(Fromvalue.standing_enum);

        customerServices.custStatusChange(Fromvalue).then(function (result) {
            if (result.data.status == "1") {
                $scope.successmessage = result.data.reponse;
                $timeout(function () {
                    $scope.successmessage = '';
                }, 5000);

            } else {
                $scope.errmessage = result.data.reponse;
                $timeout(function () {
                    $scope.errmessage = '';
                }, 5000);
            }
        });
    };
    $scope.addcustomer = function () {
        if ($scope.customerForm.$valid) {           
            var formData = {};             
            formData.fname = $scope.customer.fname;
            formData.lname = $scope.customer.lname;
            formData.emailid = $scope.customer.emailid;
            formData.mobileno = $scope.customer.mobileno;
            formData.vc_number = $scope.customer.vc_number;
            formData.file = $scope.tempattachment;           
            customerServices.addcustomer(formData, $rootScope).then(function (result) {
                if (result.data.status == 1) {
                    var result_data = JSON.stringify(result.data.msg);
                    var session_data = JSON.parse(result_data);
                    $location.path("/customers");
                }
                else
                {
                    console.log(result.data.msg);
                    $scope.errmessage = result.data.msg;
                    $timeout(function () {
                        $scope.errmessage = '';
                    }, 5000);
                }
            });
        }
    };


    $scope.getUploadFile = function (upfile, itemid) {
        console.log(upfile);
        console.log(itemid);
        var reader = new FileReader();
        reader.onload = function(e)  {
            if (!$scope.tempattachment)
                $scope.tempattachment = {};
            
            $scope.tempattachment.resImageDataURI = e.target.result;
            $scope.tempattachment.upfiletype = upfile.file.type;
            $scope.tempattachment.attachname = upfile.file.name;
        }
        ;
        reader.readAsDataURL(upfile.file);
    };

});

