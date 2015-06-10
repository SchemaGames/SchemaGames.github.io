(function () {
    'use strict';

    /* Modified form of
    * angular-markdown-directive v0.3.1
    * (c) 2013-2014 Brian Ford http://briantford.com
    * License: MIT
    */

    /**
     * @ngdoc function
     * @name SchemaApp.directive:showdown
     * @description
     * # ShowDown
     * Displays Markdown using the Showdown library
     */
    angular.module('SchemaApp')
    .provider('markdownConverter', function () {
        var opts = {};
        return {
            config: function (newOpts) {
                opts = newOpts;
            },
            $get: function () {
                return new Showdown.converter(opts);
            }
        };
    })
    .directive('showdown', ['$sanitize','markdownConverter',function ($sanitize, markdownConverter) {
        return {
            restrict: 'E',
            link: function (scope, element, attrs) {
                if (attrs.showdown) {
                    scope.$watch(attrs.showdown, function (newVal) {
                        var html = newVal ? $sanitize(markdownConverter.makeHtml(newVal)) : '';
                        element.html(html);
                    });
                } else {
                    var html = $sanitize(markdownConverter.makeHtml(element.text()));
                    element.html(html);
                }
            }
        };
    }]);
}());
