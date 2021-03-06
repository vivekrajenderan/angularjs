(function () {
    angular.module('validation.rule', ['validation'])
            .config(['$validationProvider',
                function ($validationProvider) {

                    var expression = {
                        required: function (value) {
                            return !!value;
                        },
                        url: /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/,
                        email: /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/,
                        number: /^\d+$/,
                        validateAlphaspecial: /^[a-zA-Z.,]+$/,
                        validateNotEmpty: function (value) {
                            if (value.length == 0) {
                                return false;
                            } else {
                                return true;
                            }
                        },
                        minlength: function (value, scope, element, attrs, param) {
                            return value.length >= attrs.minlength;
                        },
                        maxlength: function (value, scope, element, attrs, param) {
                            return value.length <= attrs.maxlength;
                        },
                        validateNumberWithDollar: function (text) {
                            firstCharName = text.substring(0, 1);
                            totalCharName = text.substring(1, text.length);
                            //firstCharName = firstCharName.ReplaceAll("$","");
                            firstCharName = firstCharName.replace(/$/g, "");
                            text = firstCharName + totalCharName;
                            text = text.replace(/[,]/g, "");
                            if (text.indexOf("(") != -1) {
                                if (text.indexOf(")") != -1) {
                                    text = text.replace(/[(]/g, "-");
                                } else {
                                    return true;
                                }
                            }
                            text = text.replace(/[)]/g, "");
                            var dot = text.indexOf(".");
                            if (dot != -1) {
                                var dotArr = text.split(".");
                                if (dotArr.length >= 3) {
                                    text = dotArr[0] + "." + dotArr[1];
                                }
                            }
                            if (text.match(/-/)) {
                                return true;
                            }
                            if (!text.match(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/)) {
                                return true;
                            }
                            return false;
                        },
                        validimage: function (value, scope, element, attrs, param) {
                            var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
                            if ($.inArray(value.split('.').pop().toLowerCase(), fileExtension) == -1) {
                                return false;
                            }
                            else
                            {
                                return true;
                            }
                        },
                        filesize:function (value, scope, element, attrs, param) {
                            //var fsize = element[0].files[0].size;
                            //alert('File size:' + fsize);
                        return element[0].files[0].size <= attrs.filesize;    
                        }
                    };
                    var defaultMsg = {
                        required: {
                            error: 'This should be Required!!',
                            success: 'It\'s Required'
                        },
                        url: {
                            error: 'This should be Url',
                            success: 'It\'s Url'
                        },
                        email: {
                            error: 'This should be Email',
                            success: 'It\'s Email'
                        },
                        number: {
                            error: 'This should be Number',
                            success: 'It\'s Number'
                        },
                        minlength: {
                            error: 'This should be longer',
                            success: 'It\'s Number'
                        },
                        maxlength: {
                            error: 'This should be shorter',
                            success: 'It\'s Number'
                        },
                        validimage: {
                            error: 'This should be image',
                            success: 'It\'s Image'
                        },
                        filesize: {
                            error: 'This should be allowed maximum size',
                            success: 'It\'s Image'
                        }
                    };
                    $validationProvider.setExpression(expression).setDefaultMsg(defaultMsg);
                    $validationProvider.showSuccessMessage = false; // or true(default)
                    $validationProvider.showErrorMessage = true; // or true(default)


                }
            ]);
}).call(this);

