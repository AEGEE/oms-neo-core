/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 2.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v2.0/admin/angularjs/
*/

/* Global Setting
------------------------------------------------ */

omsApp.factory('setting', ['$rootScope', function($rootScope) {
    var setting = {
        layout: {
            pageSidebarMinified: false,
            pageFixedFooter: false,
            pageRightSidebar: false,
            pageTwoSidebar: false,
            pageTopMenu: false,
            pageBoxedLayout: false,
            pageWithoutSidebar: false,
            pageContentFullHeight: false,
            pageContentFullWidth: false,
            pageContentInverseMode: false,
            pageSidebarTransparent: false,
            pageWithFooter: false,
            pageLightSidebar: false,
            pageMegaMenu: false,
            pageBgWhite: false,
            pageWithoutHeader: false,
            paceTop: false,
            notifications: false
        }
    };
    
    return setting;
}]);
/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 2.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v2.0/admin/angularjs/
    ----------------------------
        APPS CONTROLLER TABLE
    ----------------------------
    1.0 CONTROLLER - App
    2.0 CONTROLLER - Sidebar
    3.0 CONTROLLER - Right Sidebar
    4.0 CONTROLLER - Header
    5.0 CONTROLLER - Top Menu
    6.0 CONTROLLER - Page Loader
    7.0 CONTROLLER - Theme Panel
    8.0 CONTROLLER - Dashboard v1
    9.0 CONTROLLER - Dashboard v2
   10.0 CONTROLLER - Email Inbox v1
   11.0 CONTROLLER - Email Inbox v2
   12.0 CONTROLLER - Email Compose
   13.0 CONTROLLER - Email Detail
   14.0 CONTROLLER - UI Modal & Notifications
   15.0 CONTROLLER - UI Tree
   16.0 CONTROLLER - UI Language Bar
   17.0 CONTROLLER - Form Plugins
   18.0 CONTROLLER - Form Slider + Switcher
   19.0 CONTROLLER - Form Validation
   20.0 CONTROLLER - Table Manage Default
   21.0 CONTROLLER - Table Manage Autofill
   22.0 CONTROLLER - Table Manage Buttons
   23.0 CONTROLLER - Table Manage ColReorder
   24.0 CONTROLLER - Table Manage Fixed Columns
   25.0 CONTROLLER - Table Manage Fixed Header
   26.0 CONTROLLER - Table Manage KeyTable
   27.0 CONTROLLER - Table Manage Responsive
   28.0 CONTROLLER - Table Manage RowReorder
   29.0 CONTROLLER - Table Manage Scroller
   30.0 CONTROLLER - Table Manage Select
   31.0 CONTROLLER - Table Manage Extension Combination
   32.0 CONTROLLER - Flot Chart
   33.0 CONTROLLER - Morris Chart
   34.0 CONTROLLER - Chart JS
   35.0 CONTROLLER - Chart d3
   36.0 CONTROLLER - Calendar
   37.0 CONTROLLER - Vector Map
   38.0 CONTROLLER - Google Map
   39.0 CONTROLLER - Gallery V1
   40.0 CONTROLLER - Gallery V2
   41.0 CONTROLLER - Page with Footer
   42.0 CONTROLLER - Page without Sidebar
   43.0 CONTROLLER - Page with Right Sidebar
   44.0 CONTROLLER - Page with Minified Sidebar
   45.0 CONTROLLER - Page with Two Sidebar
   46.0 CONTROLLER - Full Height Content
   47.0 CONTROLLER - Page with Wide Sidebar
   48.0 CONTROLLER - Page with Light Sidebar
   49.0 CONTROLLER - Page with Mega Menu
   50.0 CONTROLLER - Page with Top Menu
   51.0 CONTROLLER - Page with Boxed Layout
   52.0 CONTROLLER - Page with Mixed Menu
   53.0 CONTROLLER - Page Boxed Layout with Mixed Menu
   54.0 CONTROLLER - Page with Transparent Sidebar
   55.0 CONTROLLER - Timeline
   56.0 CONTROLLER - Coming Soon
   57.0 CONTROLLER - 404 Error
   58.0 CONTROLLER - Login V1
   59.0 CONTROLLER - Login V2
   60.0 CONTROLLER - Login V3
   61.0 CONTROLLER - Register V3
    <!-- ======== GLOBAL SCRIPT SETTING ======== -->
*/


var blue		= '#348fe2',
    blueLight	= '#5da5e8',
    blueDark	= '#1993E4',
    aqua		= '#49b6d6',
    aquaLight	= '#6dc5de',
    aquaDark	= '#3a92ab',
    green		= '#00acac',
    greenLight	= '#33bdbd',
    greenDark	= '#008a8a',
    orange		= '#f59c1a',
    orangeLight	= '#f7b048',
    orangeDark	= '#c47d15',
    dark		= '#2d353c',
    grey		= '#b6c2c9',
    purple		= '#727cb6',
    purpleLight	= '#8e96c5',
    purpleDark	= '#5b6392',
    red         = '#ff5b57';


/* -------------------------------
   1.0 CONTROLLER - App
------------------------------- */
omsApp.controller('appController', ['$rootScope', '$scope', function($rootScope, $scope) {
    $scope.$on('$includeContentLoaded', function() {
        handleSlimScroll();
    });
    $scope.$on('$viewContentLoaded', function() {
    });
    $scope.$on('$stateChangeStart', function() {
        // reset layout setting
        $rootScope.setting.layout.pageSidebarMinified = false;
        $rootScope.setting.layout.pageFixedFooter = false;
        $rootScope.setting.layout.pageRightSidebar = false;
        $rootScope.setting.layout.pageTwoSidebar = false;
        $rootScope.setting.layout.pageTopMenu = false;
        $rootScope.setting.layout.pageBoxedLayout = false;
        $rootScope.setting.layout.pageWithoutSidebar = false;
        $rootScope.setting.layout.pageContentFullHeight = false;
        $rootScope.setting.layout.pageContentFullWidth = false;
        $rootScope.setting.layout.paceTop = false;
        $rootScope.setting.layout.pageLanguageBar = false;
        $rootScope.setting.layout.pageSidebarTransparent = false;
        $rootScope.setting.layout.pageWideSidebar = false;
        $rootScope.setting.layout.pageLightSidebar = false;
        $rootScope.setting.layout.pageFooter = false;
        $rootScope.setting.layout.pageMegaMenu = false;
        $rootScope.setting.layout.pageWithoutHeader = false;
        $rootScope.setting.layout.pageBgWhite = false;
        $rootScope.setting.layout.pageContentInverseMode = false;
        $rootScope.setting.layout.notifications = false;
        
        App.scrollTop();
        $('.pace .pace-progress').addClass('hide');
        $('.pace').removeClass('pace-inactive');
    });
    $scope.$on('$stateChangeSuccess', function() {
        Pace.restart();
        App.initPageLoad();
        App.initSidebarSelection();
        App.initSidebarMobileSelection();
        setTimeout(function() {
            App.initLocalStorage();
            App.initComponent();
        },0);
    });
    $scope.$on('$stateNotFound', function() {
        Pace.stop();
    });
    $scope.$on('$stateChangeError', function() {
        Pace.stop();
    });
}]);



/* -------------------------------
   2.0 CONTROLLER - Sidebar
------------------------------- */
omsApp.controller('sidebarController', function($scope, $rootScope, $state) {
    App.initSidebar();
});



/* -------------------------------
   3.0 CONTROLLER - Right Sidebar
------------------------------- */
omsApp.controller('rightSidebarController', function($scope, $rootScope, $state) {
    var getRandomValue = function() {
        var value = [];
        for (var i = 0; i<= 19; i++) {
            value.push(Math.floor((Math.random() * 10) + 1));
        }
        return value;
    };

    $('.knob').knob();

    var blue		= '#348fe2', green		= '#00acac', purple		= '#727cb6', red         = '#ff5b57';
    var options = { height: '50px', width: '100%', fillColor: 'transparent', type: 'bar', barWidth: 8, barColor: green };

    var value = getRandomValue();
    $('#sidebar-sparkline-1').sparkline(value, options);

    value = getRandomValue();
    options.barColor = blue;
    $('#sidebar-sparkline-2').sparkline(value, options);

    value = getRandomValue();
    options.barColor = purple;
    $('#sidebar-sparkline-3').sparkline(value, options);

    value = getRandomValue();
    options.barColor = red;
    $('#sidebar-sparkline-4').sparkline(value, options);
});



/* -------------------------------
   4.0 CONTROLLER - Header
------------------------------- */
omsApp.controller('headerController', function($scope, $rootScope, $state) {
    var vm = this;
    vm.logout = function() {
        console.log('here');
        location.href = '/logout';
    }
});



/* -------------------------------
   5.0 CONTROLLER - Top Menu
------------------------------- */
omsApp.controller('topMenuController', function($scope, $rootScope, $state) {
    setTimeout(function() {
        App.initTopMenu();
    }, 0);
});



/* -------------------------------
   6.0 CONTROLLER - Page Loader
------------------------------- */
omsApp.controller('pageLoaderController', function($scope, $rootScope, $state) {
    App.initPageLoad();
});



/* -------------------------------
   7.0 CONTROLLER - Theme Panel
------------------------------- */
omsApp.controller('themePanelController', function($scope, $rootScope, $state) {
    App.initThemePanel();
});



/* -------------------------------
   8.0 CONTROLLER - Dashboard v1
------------------------------- */
omsApp.controller('dashboardController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        /* Vector Map
        ------------------------- */
        $('#world-map').vectorMap({
        map: 'world_mill_en',
        scaleColors: ['#e74c3c', '#0071a4'],
        normalizeFunction: 'polynomial',
        hoverOpacity: 0.5,
        hoverColor: false,
        markerStyle: {
            initial: {
                fill: '#4cabc7',
                stroke: 'transparent',
                r: 3
            }
        },
        regionStyle: {
            initial: {
                fill: 'rgb(97,109,125)',
                "fill-opacity": 1,
                stroke: 'none',
                "stroke-width": 0.4,
                "stroke-opacity": 1
            },
            hover: { "fill-opacity": 0.8 },
            selected: { fill: 'yellow' }
        },
        focusOn: { x: 0.5, y: 0.5, scale: 0 },
        backgroundColor: '#2d353c',
        markers: [
            {latLng: [41.90, 12.45], name: 'Vatican City'},
            {latLng: [43.73, 7.41], name: 'Monaco'},
            {latLng: [-0.52, 166.93], name: 'Nauru'},
            {latLng: [-8.51, 179.21], name: 'Tuvalu'},
            {latLng: [43.93, 12.46], name: 'San Marino'},
            {latLng: [47.14, 9.52], name: 'Liechtenstein'},
            {latLng: [7.11, 171.06], name: 'Marshall Islands'},
            {latLng: [17.3, -62.73], name: 'Saint Kitts and Nevis'},
            {latLng: [3.2, 73.22], name: 'Maldives'},
            {latLng: [35.88, 14.5], name: 'Malta'},
            {latLng: [12.05, -61.75], name: 'Grenada'},
            {latLng: [13.16, -61.23], name: 'Saint Vincent and the Grenadines'},
            {latLng: [13.16, -59.55], name: 'Barbados'},
            {latLng: [17.11, -61.85], name: 'Antigua and Barbuda'},
            {latLng: [-4.61, 55.45], name: 'Seychelles'},
            {latLng: [7.35, 134.46], name: 'Palau'},
            {latLng: [42.5, 1.51], name: 'Andorra'},
            {latLng: [14.01, -60.98], name: 'Saint Lucia'},
            {latLng: [6.91, 158.18], name: 'Federated States of Micronesia'},
            {latLng: [1.3, 103.8], name: 'Singapore'},
            {latLng: [1.46, 173.03], name: 'Kiribati'},
            {latLng: [-21.13, -175.2], name: 'Tonga'},
            {latLng: [15.3, -61.38], name: 'Dominica'},
            {latLng: [-20.2, 57.5], name: 'Mauritius'},
            {latLng: [26.02, 50.55], name: 'Bahrain'},
            {latLng: [0.33, 6.73], name: 'São Tomé and Príncipe'}
        ]
        });
    
    
        /* Line Chart
        ------------------------- */
        var data1 = [ 
            [1, 40], [2, 50], [3, 60], [4, 60], [5, 60], [6, 65], [7, 75], [8, 90], [9, 100], [10, 105], 
            [11, 110], [12, 110], [13, 120], [14, 130], [15, 135],[16, 145], [17, 132], [18, 123], [19, 135], [20, 150] 
        ];
        var data2 = [
            [1, 10],  [2, 6], [3, 10], [4, 12], [5, 18], [6, 20], [7, 25], [8, 23], [9, 24], [10, 25], 
            [11, 18], [12, 30], [13, 25], [14, 25], [15, 30], [16, 27], [17, 20], [18, 18], [19, 31], [20, 23]
        ];
        var xLabel = [
            [1,''],[2,''],[3,'May&nbsp;15'],[4,''],[5,''],[6,'May&nbsp;19'],[7,''],[8,''],[9,'May&nbsp;22'],[10,''],
            [11,''],[12,'May&nbsp;25'],[13,''],[14,''],[15,'May&nbsp;28'],[16,''],[17,''],[18,'May&nbsp;31'],[19,''],[20,'']
        ];
        $.plot($("#interactive-chart"), [{
            data: data1, 
            label: "Page Views", 
            color: blue,
            lines: { show: true, fill:false, lineWidth: 2 },
            points: { show: true, radius: 3, fillColor: '#fff' },
            shadowSize: 0
        }, {
            data: data2,
            label: 'Visitors',
            color: green,
            lines: { show: true, fill:false, lineWidth: 2 },
            points: { show: true, radius: 3, fillColor: '#fff' },
            shadowSize: 0
        }], {
            xaxis: {  ticks:xLabel, tickDecimals: 0, tickColor: '#ddd' },
            yaxis: {  ticks: 10, tickColor: '#ddd', min: 0, max: 200 },
            grid: { 
                hoverable: true, 
                clickable: true,
                tickColor: "#ddd",
                borderWidth: 1,
                backgroundColor: '#fff',
                borderColor: '#ddd'
            },
            legend: {
                labelBoxBorderColor: '#ddd',
                margin: 10,
                noColumns: 1,
                show: true
            }
        });
        var previousPoint = null;
        $("#interactive-chart").bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));
            if (item) {
                if (previousPoint !== item.dataIndex) {
                    previousPoint = item.dataIndex;
                    $("#tooltip").remove();
                    var y = item.datapoint[1].toFixed(2);
                    var content = item.series.label + " " + y;
                    $('<div id="tooltip" class="flot-tooltip">' + content + '</div>').css({ top: item.pageY - 45, left: item.pageX - 55 }).appendTo("body").fadeIn(200);
                }
            } else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
            event.preventDefault();
        });
    
    
        /* Donut Chart
        ------------------------- */
        var donutData = [
            { label: "Chrome",  data: 35, color: purpleDark},
            { label: "Firefox",  data: 30, color: purple},
            { label: "Safari",  data: 15, color: purpleLight},
            { label: "Opera",  data: 10, color: blue},
            { label: "IE",  data: 5, color: blueDark}
        ];
        $.plot('#donut-chart', donutData, {
            series: {
                pie: {
                    innerRadius: 0.5,
                    show: true,
                    label: { show: true }
                }
            },
            legend: { show: true }
        });
    

        /* Sparkline
        ------------------------- */
        var options = {
            height: '50px',
            width: '100%',
            fillColor: 'transparent',
            lineWidth: 2,
            spotRadius: '4',
            highlightLineColor: blue,
            highlightSpotColor: blue,
            spotColor: false,
            minSpotColor: false,
            maxSpotColor: false
        };
        function renderDashboardSparkline() {
            var value = [50,30,45,40,50,20,35,40,50,70,90,40];
            options.type = 'line';
            options.height = '23px';
            options.lineColor = red;
            options.highlightLineColor = red;
            options.highlightSpotColor = red;
    
            var countWidth = $('#sparkline-unique-visitor').width();
            if (countWidth >= 200) {
                options.width = '200px';
            } else {
                options.width = '100%';
            }
    
            $('#sparkline-unique-visitor').sparkline(value, options);
            options.lineColor = orange;
            options.highlightLineColor = orange;
            options.highlightSpotColor = orange;
            $('#sparkline-bounce-rate').sparkline(value, options);
            options.lineColor = green;
            options.highlightLineColor = green;
            options.highlightSpotColor = green;
            $('#sparkline-total-page-views').sparkline(value, options);
            options.lineColor = blue;
            options.highlightLineColor = blue;
            options.highlightSpotColor = blue;
            $('#sparkline-avg-time-on-site').sparkline(value, options);
            options.lineColor = grey;
            options.highlightLineColor = grey;
            options.highlightSpotColor = grey;
            $('#sparkline-new-visits').sparkline(value, options);
            options.lineColor = dark;
            options.highlightLineColor = dark;
            options.highlightSpotColor = grey;
            $('#sparkline-return-visitors').sparkline(value, options);
        }
        renderDashboardSparkline();
    
        $(window).on('resize', function() {
            $('#sparkline-unique-visitor').empty();
            $('#sparkline-bounce-rate').empty();
            $('#sparkline-total-page-views').empty();
            $('#sparkline-avg-time-on-site').empty();
            $('#sparkline-new-visits').empty();
            $('#sparkline-return-visitors').empty();
            renderDashboardSparkline();
        });


        /* Datepicker
        ------------------------- */
        $('#datepicker-inline').datepicker({ todayHighlight: true });


    
        /* Todolist
        ------------------------- */
        $('[data-click=todolist]').click(function() {
            var targetList = $(this).closest('li');
            if ($(targetList).hasClass('active')) {
                $(targetList).removeClass('active');
            } else {
                $(targetList).addClass('active');
            }
        });
    

    
        /* Gritter Notification
        ------------------------- */
        setTimeout(function() {
            $.gritter.add({
                title: 'Welcome back, Admin!',
                text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus lacus ut lectus rutrum placerat.',
                image: 'assets/img/user-2.jpg',
                sticky: true,
                time: '',
                class_name: 'my-sticky-class'
            });
        }, 1000);
    });
});



/* -------------------------------
   9.0 CONTROLLER - Dashboard v2
------------------------------- */
omsApp.controller('dashboardV2Controller', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        /* Line Chart
        ------------------------- */
        var green = '#0D888B';
        var greenLight = '#00ACAC';
        var blue = '#3273B1';
        var blueLight = '#348FE2';
        var blackTransparent = 'rgba(0,0,0,0.6)';
        var whiteTransparent = 'rgba(255,255,255,0.4)';
        var month = [];
            month[0] = "January";
            month[1] = "February";
            month[2] = "March";
            month[3] = "April";
            month[4] = "May";
            month[5] = "Jun";
            month[6] = "July";
            month[7] = "August";
            month[8] = "September";
            month[9] = "October";
            month[10] = "November";
            month[11] = "December";

        Morris.Line({
            element: 'visitors-line-chart',
            data: [
                {x: '2014-02-01', y: 60, z: 30},
                {x: '2014-03-01', y: 70, z: 40},
                {x: '2014-04-01', y: 40, z: 10},
                {x: '2014-05-01', y: 100, z: 70},
                {x: '2014-06-01', y: 40, z: 10},
                {x: '2014-07-01', y: 80, z: 50},
                {x: '2014-08-01', y: 70, z: 40}
            ],
            xkey: 'x',
            ykeys: ['y', 'z'],
            xLabelFormat: function(x) {
                x = month[x.getMonth()];
                return x.toString();
            },
            labels: ['Page Views', 'Unique Visitors'],
            lineColors: [green, blue],
            pointFillColors: [greenLight, blueLight],
            lineWidth: '2px',
            pointStrokeColors: [blackTransparent, blackTransparent],
            resize: true,
            gridTextFamily: 'Open Sans',
            gridTextColor: whiteTransparent,
            gridTextWeight: 'normal',
            gridTextSize: '11px',
            gridLineColor: 'rgba(0,0,0,0.5)',
            hideHover: 'auto',
        });

        /* Donut Chart
        ------------------------- */
        var green = '#00acac';
        var blue = '#348fe2';
        Morris.Donut({
            element: 'visitors-donut-chart',
            data: [
                {label: "New Visitors", value: 900},
                {label: "Return Visitors", value: 1200}
            ],
            colors: [green, blue],
            labelFamily: 'Open Sans',
            labelColor: 'rgba(255,255,255,0.4)',
            labelTextSize: '12px',
            backgroundColor: '#242a30'
        });


        /* Vector Map
        ------------------------- */
        map = new jvm.WorldMap({
            map: 'world_merc_en',
            scaleColors: ['#e74c3c', '#0071a4'],
            container: $('#visitors-map'),
            normalizeFunction: 'linear',
            hoverOpacity: 0.5,
            hoverColor: false,
            markerStyle: {
                initial: {
                    fill: '#4cabc7',
                    stroke: 'transparent',
                    r: 3
                }
            },
            regions: [{ attribute: 'fill' }],
            regionStyle: {
                initial: {
                    fill: 'rgb(97,109,125)',
                    "fill-opacity": 1,
                    stroke: 'none',
                    "stroke-width": 0.4,
                    "stroke-opacity": 1
                },
                hover: { "fill-opacity": 0.8 },
                selected: { fill: 'yellow' }
            },
            series: {
                regions: [{
                    values: {
                        IN:'#00acac',
                        US:'#00acac',
                        KR:'#00acac'
                    }
                }]
            },
            focusOn: { x: 0.5, y: 0.5, scale: 2 },
            backgroundColor: '#2d353c'
        });
    

        /* Calendar
        ------------------------- */
        var monthNames = ["January", "February", "March", "April", "May", "June",  "July", "August", "September", "October", "November", "December"];
        var dayNames = ["S", "M", "T", "W", "T", "F", "S"];
        var now = new Date(),
            month = now.getMonth() + 1,
            year = now.getFullYear();
        var events = [[
            '2/' + month + '/' + year,
            'Popover Title',
            '#',
            '#00acac',
            'Some contents here'
        ], [
            '5/' + month + '/' + year,
            'Tooltip with link',
            'http://www.seantheme.com/color-admin-v1.3',
            '#2d353c'
        ], [
            '18/' + month + '/' + year,
            'Popover with HTML Content',
            '#',
            '#2d353c',
            'Some contents here <div class="text-right"><a href="http://www.google.com">view more >>></a></div>'
        ], [
            '28/' + month + '/' + year,
            'Color Admin V1.3 Launched',
            'http://www.seantheme.com/color-admin-v1.3',
            '#2d353c',
        ]];
        var calendarTarget = $('#schedule-calendar');
        $(calendarTarget).calendar({
            months: monthNames,
            days: dayNames,
            events: events,
            popover_options:{
                placement: 'top',
                html: true
            }
        });
        $(calendarTarget).find('td.event').each(function() {
            var backgroundColor = $(this).css('background-color');
            $(this).removeAttr('style');
            $(this).find('a').css('background-color', backgroundColor);
        });
        $(calendarTarget).find('.icon-arrow-left, .icon-arrow-right').parent().on('click', function() {
            $(calendarTarget).find('td.event').each(function() {
                var backgroundColor = $(this).css('background-color');
                $(this).removeAttr('style');
                $(this).find('a').css('background-color', backgroundColor);
            });
        });


        /* Gritter Notification
        ------------------------- */
        setTimeout(function() {
            $.gritter.add({
                title: 'Welcome back, Admin!',
                text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus lacus ut lectus rutrum placerat.',
                image: 'assets/img/user-14.jpg',
                sticky: true,
                time: '',
                class_name: 'my-sticky-class'
            });
        }, 1000);
    });
});



/* -------------------------------
   10.0 CONTROLLER - Email Inbox v1
------------------------------- */
omsApp.controller('emailInboxController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageContentFullWidth = true;
    
    angular.element(document).ready(function () {
        /* Email Select All
        ------------------------- */
        $('[data-click=email-select-all]').click(function(e) {
            e.preventDefault();
            if ($(this).closest('tr').hasClass('active')) {
                $('.table-email tr').removeClass('active');
            } else {
                $('.table-email tr').addClass('active');
            }
        });
    
    
        /* Email Select Single
        ------------------------- */
        $('[data-click=email-select-single]').click(function(e) { 
            e.preventDefault();
            var targetRow = $(this).closest('tr');
            if ($(targetRow).hasClass('active')) {
                $(targetRow).removeClass('active');
            } else {
                $(targetRow).addClass('active');
            }
        });
    
    
        /* Email Remove
        ------------------------- */
        $('[data-click=email-remove]').click(function(e) { 
            e.preventDefault();
            var targetRow = $(this).closest('tr');
            $(targetRow).fadeOut().remove();
        });
    
    
        /* Email Highlight
        ------------------------- */
        $('[data-click=email-highlight]').click(function(e) { 
            e.preventDefault();
            if ($(this).hasClass('text-danger')) {
                $(this).removeClass('text-danger');
            } else {
                $(this).addClass('text-danger');
            }
        });
    });
});



/* -------------------------------
   11.0 CONTROLLER - Email Inbox v2
------------------------------- */
omsApp.controller('emailInboxV2Controller', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageContentFullWidth = true;
    
    angular.element(document).ready(function () {
        /* Email Checkbox
        ------------------------- */
        $('[data-checked=email-checkbox]').live('click', function() {
            var targetLabel = $(this).closest('label');
            var targetEmailList = $(this).closest('li');
            if ($(this).prop('checked')) {
                $(targetLabel).addClass('active');
                $(targetEmailList).addClass('selected');
            } else {
                $(targetLabel).removeClass('active');
                $(targetEmailList).removeClass('selected');
            }
            if ($('[data-checked=email-checkbox]:checked').length !== 0) {
                $('[data-email-action]').removeClass('hide');
            } else {
                $('[data-email-action]').addClass('hide');
            }
        });
    
    
        /* Email Action
        ------------------------- */
        $('[data-email-action]').live('click', function() {
            var targetEmailList = '[data-checked=email-checkbox]:checked';
            if ($(targetEmailList).length !== 0) {
                $(targetEmailList).closest('li').slideToggle(function() {
                    $(this).remove();
                    if ($('[data-checked=email-checkbox]:checked').length !== 0) {
                        $('[data-email-action]').removeClass('hide');
                    } else {
                        $('[data-email-action]').addClass('hide');
                    }

                });
            }
        });
    });
});



/* -------------------------------
   12.0 CONTROLLER - Email Compose
------------------------------- */
omsApp.controller('emailComposeController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageContentFullWidth = true;
    
    angular.element(document).ready(function () {
        /* jQuery TagIt
        ------------------------- */
        $('#email-to').tagit({
            availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"]
        });
    
    
        /* WYSIHTML5
        ------------------------- */
        $('#wysihtml5').wysihtml5();
    });
});



/* -------------------------------
   13.0 CONTROLLER - Email Detail
------------------------------- */
omsApp.controller('emailDetailController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageContentFullWidth = true;
});



/* -------------------------------
   14.0 CONTROLLER - UI Modal & Notifications
------------------------------- */
omsApp.controller('uiModalNotificationController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        /* Gritter Notification
        ------------------------- */
        $('#add-sticky').click( function() {
            $.gritter.add({
                title: 'This is a sticky notice!',
                text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempus lacus ut lectus rutrum placerat. ',
                image: 'assets/img/user-2.jpg',
                sticky: true,
                time: '',
                class_name: 'my-sticky-class'
            });
            return false;
        });
        $('#add-regular').click( function() {
            $.gritter.add({
                title: 'This is a regular notice!',
                text: 'This will fade out after a certain amount of time. Sed tempus lacus ut lectus rutrum placerat. ',
                image: 'assets/img/user-3.jpg',
                sticky: false,
                time: ''
            });
            return false;
        });
        $('#add-max').click( function() {
            $.gritter.add({
                title: 'This is a notice with a max of 3 on screen at one time!',
                text: 'This will fade out after a certain amount of time. Sed tempus lacus ut lectus rutrum placerat. ',
                sticky: false,
                image: 'assets/img/user-4.jpg',
                before_open: function() {
                    if($('.gritter-item-wrapper').length === 3) {
                        return false;
                    }
                }
            });
            return false;
        });
        $('#add-without-image').click(function(){
            $.gritter.add({
                title: 'This is a notice without an image!',
                text: 'This will fade out after a certain amount of time.'
            });
            return false;
        });
        $('#add-gritter-light').click(function(){
            $.gritter.add({
                title: 'This is a light notification',
                text: 'Just add a "gritter-light" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
                class_name: 'gritter-light'
            });
            return false;
        });
        $('#add-with-callbacks').click(function(){
            $.gritter.add({
                title: 'This is a notice with callbacks!',
                text: 'The callback is...',
                before_open: function(){
                    alert('I am called before it opens');
                },
                after_open: function(e){
                    alert("I am called after it opens: \nI am passed the jQuery object for the created Gritter element...\n" + e);
                },
                before_close: function(manual_close) {
                    var manually = (manual_close) ? 'The "X" was clicked to close me!' : '';
                    alert("I am called before it closes: I am passed the jQuery object for the Gritter element... \n" + manually);
                },
                after_close: function(manual_close){
                    var manually = (manual_close) ? 'The "X" was clicked to close me!' : '';
                    alert('I am called after it closes. ' + manually);
                }
            });
            return false;
        });
        $('#add-sticky-with-callbacks').click(function(){
            $.gritter.add({
                title: 'This is a sticky notice with callbacks!',
                text: 'Sticky sticky notice.. sticky sticky notice...',
                sticky: true,
                before_open: function(){
                    alert('I am a sticky called before it opens');
                },
                after_open: function(e){
                    alert("I am a sticky called after it opens: \nI am passed the jQuery object for the created Gritter element...\n" + e);
                },
                before_close: function(e){
                    alert("I am a sticky called before it closes: I am passed the jQuery object for the Gritter element... \n" + e);
                },
                after_close: function(){
                    alert('I am a sticky called after it closes');
                }
            });
            return false;
        });
        $("#remove-all").click(function(){
            $.gritter.removeAll();
            return false;
        });
        $("#remove-all-with-callbacks").click(function(){
            $.gritter.removeAll({
                before_close: function(e){
                    alert("I am called before all notifications are closed.  I am passed the jQuery object containing all  of Gritter notifications.\n" + e);
                },
                after_close: function(){
                    alert('I am called after everything has been closed.');
                }
            });
            return false;
        });
    });
});



/* -------------------------------
   15.0 CONTROLLER - UI Tree
------------------------------- */
omsApp.controller('uiTreeController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        $('#jstree-default').jstree({
            "core": {
                "themes": {
                    "responsive": false
                }            
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder text-warning fa-lg"
                },
                "file": {
                    "icon": "fa fa-file text-inverse fa-lg"
                }
            },
            "plugins": ["types"]
        });

        $('#jstree-default').on('select_node.jstree', function(e,data) { 
            var link = $('#' + data.selected).find('a');
            if (link.attr("href") != "#" && link.attr("href") != "javascript:;" && link.attr("href") != "") {
                if (link.attr("target") == "_blank") {
                    link.attr("href").target = "_blank";
                }
                document.location.href = link.attr("href");
                return false;
            }
        });
    
        $('#jstree-checkable').jstree({
            'plugins': ["wholerow", "checkbox", "types"],
            'core': {
                "themes": {
                    "responsive": false
                },    
                'data': [{
                    "text": "Same but with checkboxes",
                    "children": [{
                        "text": "initially selected",
                        "state": { "selected": true }
                    }, {
                        "text": "Folder 1"
                    }, {
                        "text": "Folder 2"
                    }, {
                        "text": "Folder 3"
                    }, {
                        "text": "initially open",
                        "icon": "fa fa-folder fa-lg",
                        "state": {
                            "opened": true
                        },
                        "children": [{
                            "text": "Another node"
                        }, {
                            "text": "disabled node",
                            "state": {
                                "disabled": true
                            }
                        }]
                    }, {
                        "text": "custom icon",
                        "icon": "fa fa-cloud-download fa-lg text-inverse"
                    }, {
                        "text": "disabled node",
                        "state": {
                            "disabled": true
                        }
                    }
                ]},
                "Root node 2"
            ]},
            "types": {
                "default": {
                    "icon": "fa fa-folder text-primary fa-lg"
                },
                "file": {
                    "icon": "fa fa-file text-success fa-lg"
                }
            }
        });
    
        $("#jstree-drag-and-drop").jstree({
            "core": {
                "themes": {
                    "responsive": false
                }, 
                "check_callback": true,
                'data': [{
                        "text": "Parent Node",
                        "children": [{
                            "text": "Initially selected",
                            "state": {
                                "selected": true
                            }
                        }, {
                            "text": "Folder 1"
                        }, {
                            "text": "Folder 2"
                        }, {
                            "text": "Folder 3"
                        }, {
                            "text": "Initially open",
                            "icon": "fa fa-folder text-success fa-lg",
                            "state": {
                                "opened": true
                            },
                            "children": [
                                {"text": "Disabled node", "disabled": true},
                                {"text": "Another node"}
                            ]
                        }, {
                            "text": "Another Custom Icon",
                            "icon": "fa fa-cog text-inverse fa-lg"
                        }, {
                            "text": "Disabled Node",
                            "state": {
                                "disabled": true
                            }
                        }, {
                            "text": "Sub Nodes",
                            "icon": "fa fa-folder text-danger fa-lg",
                            "children": [
                                {"text": "Item 1", "icon": "fa fa-file fa-lg"},
                                {"text": "Item 2", "icon": "fa fa-file fa-lg"},
                                {"text": "Item 3", "icon": "fa fa-file fa-lg"},
                                {"text": "Item 4", "icon": "fa fa-file fa-lg"},
                                {"text": "Item 5", "icon": "fa fa-file fa-lg"}
                            ]
                        }]
                    },
                    "Another Node"
                ]
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder text-warning fa-lg"
                },
                "file": {
                    "icon": "fa fa-file text-warning fa-lg"
                }
            },
            "state": { "key": "demo2" },
            "plugins": [ "contextmenu", "dnd", "state", "types" ]
        });
    
        $('#jstree-ajax').jstree({
            "core": {
                "themes": { "responsive": false },
                "check_callback": true,
                'data': {
                    'url': function (node) {
                        return node.id === '#' ? 'assets/plugins/jstree/demo/data_root.json': 'assets/plugins/jstree/demo/' + node.original.file;
                    },
                    'data': function (node) {
                        return { 'id': node.id };
                    },
                    "dataType": "json"
                }
            },
            "types": {
                "default": { "icon": "fa fa-folder text-warning fa-lg" },
                "file": { "icon": "fa fa-file text-warning fa-lg" }
            },
            "plugins": [ "dnd", "state", "types" ]
        });
    });
});



/* -------------------------------
   16.0 CONTROLLER - UI Language Bar
------------------------------- */
omsApp.controller('uiLanguageBarIconController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageLanguageBar = true;
});



/* -------------------------------
   17.0 CONTROLLER - Form Plugins
------------------------------- */
omsApp.controller('formPluginsController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        /* Datepicker
        ------------------------- */
        $('#datepicker-default').datepicker({
            todayHighlight: true
        });
        $('#datepicker-inline').datepicker({
            todayHighlight: true
        });
        $('.input-daterange').datepicker({
            todayHighlight: true
        });
        $('#datepicker-disabled-past').datepicker({
            todayHighlight: true
        });
        $('#datepicker-autoClose').datepicker({
            todayHighlight: true,
            autoclose: true
        });
    
    
        /* Ion Range Slider
        ------------------------- */
        $('#default_rangeSlider').ionRangeSlider({
            min: 0,
            max: 5000,
            type: 'double',
            prefix: "$",
            maxPostfix: "+",
            prettify: false,
            hasGrid: true
        });
        $('#customRange_rangeSlider').ionRangeSlider({
            min: 1000,
            max: 100000,
            from: 30000,
            to: 90000,
            type: 'double',
            step: 500,
            postfix: " €",
            hasGrid: true
        });
        $('#customValue_rangeSlider').ionRangeSlider({
            values: [
                'January', 'February', 'March',
                'April', 'May', 'June',
                'July', 'August', 'September',
                'October', 'November', 'December'
            ],
            type: 'single',
            hasGrid: true
        });
    
    
        /* Masked Input
        ------------------------- */
        $("#masked-input-date").mask("99/99/9999");
        $("#masked-input-phone").mask("(999) 999-9999");
        $("#masked-input-tid").mask("99-9999999");
        $("#masked-input-ssn").mask("999-99-9999");
        $("#masked-input-pno").mask("aaa-9999-a");
        $("#masked-input-pkey").mask("a*-999-a999");
    
    
        /* Colorpicker
        ------------------------- */
        $('#colorpicker').colorpicker({format: 'hex'});
        $('#colorpicker-prepend').colorpicker({format: 'hex'});
        $('#colorpicker-rgba').colorpicker();
    
    
        /* Timepicker
        ------------------------- */
        $('#timepicker').timepicker();
    
    
        /* Password Indicator
        ------------------------- */
        $('#password-indicator-default').passwordStrength();
        $('#password-indicator-visible').passwordStrength({targetDiv: '#passwordStrengthDiv2'});
    
    
        /* jQuery Autocomplete
        ------------------------- */
        var availableTags = [
            'ActionScript',
            'AppleScript',
            'Asp',
            'BASIC',
            'C',
            'C++',
            'Clojure',
            'COBOL',
            'ColdFusion',
            'Erlang',
            'Fortran',
            'Groovy',
            'Haskell',
            'Java',
            'JavaScript',
            'Lisp',
            'Perl',
            'PHP',
            'Python',
            'Ruby',
            'Scala',
            'Scheme'
        ];
        $('#jquery-autocomplete').autocomplete({
            source: availableTags
        });
    
    
        /* Combobox
        ------------------------- */
        $('.combobox').combobox();
    
    
        /* Bootstrap TagsInput
        ------------------------- */
        $('.bootstrap-tagsinput input').focus(function() {
            $(this).closest('.bootstrap-tagsinput').addClass('bootstrap-tagsinput-focus');
        });
        $('.bootstrap-tagsinput input').focusout(function() {
            $(this).closest('.bootstrap-tagsinput').removeClass('bootstrap-tagsinput-focus');
        });
    
    
        /* Selectpicker
        ------------------------- */
        $('.selectpicker').selectpicker('render');
    
    
        /* jQuery Tagit
        ------------------------- */
        $('#jquery-tagIt-default').tagit({
            availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"]
        });
        $('#jquery-tagIt-inverse').tagit({
            availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"]
        });
        $('#jquery-tagIt-white').tagit({
            availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"]
        });
        $('#jquery-tagIt-primary').tagit({
            availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"]
        });
        $('#jquery-tagIt-info').tagit({
            availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"]
        });
        $('#jquery-tagIt-success').tagit({
            availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"]
        });
        $('#jquery-tagIt-warning').tagit({
            availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"]
        });
        $('#jquery-tagIt-danger').tagit({
            availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"]
        });
    
    
        /* Date Range Picker
        ------------------------- */
        $('#default-daterange').daterangepicker({
            opens: 'right',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            startDate: moment().subtract('days', 29),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2018',
        },
        function (start, end) {
            $('#default-daterange input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        });
        $('#advance-daterange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
        $('#advance-daterange').daterangepicker({
            format: 'MM/DD/YYYY',
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2015',
            dateLimit: { days: 60 },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'right',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-default',
            separator: ' to ',
            locale: {
                applyLabel: 'Submit',
                cancelLabel: 'Cancel',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        }, function(start, end, label) {
            $('#advance-daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        });
    
    
        /* Select2
        ------------------------- */
        $(".default-select2").select2();
        $(".multiple-select2").select2({ placeholder: "Select a state" });
    
    
        /* DateTimepicker
        ------------------------- */
        $('#datetimepicker1').datetimepicker();
        $('#datetimepicker2').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker3').datetimepicker();
        $('#datetimepicker4').datetimepicker();
        $("#datetimepicker3").on("dp.change", function (e) {
            $('#datetimepicker4').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker4").on("dp.change", function (e) {
            $('#datetimepicker3').data("DateTimePicker").maxDate(e.date);
        });
    });
});



/* -------------------------------
   18.0 CONTROLLER - Form Slider + Switcher
------------------------------- */
omsApp.controller('formSliderSwitcherController', function($scope, $rootScope, $state) {   
    angular.element(document).ready(function () {
        var green = '#00acac',
        red = '#ff5b57',
        blue = '#348fe2',
        purple = '#727cb6',
        orange = '#f59c1a',
        black = '#2d353c';

        if ($('[data-render=switchery]').length !== 0) {
            $('[data-render=switchery]').each(function() {
                var themeColor = green;
                if ($(this).attr('data-theme')) {
                    switch ($(this).attr('data-theme')) {
                        case 'red':
                            themeColor = red;
                            break;
                        case 'blue':
                            themeColor = blue;
                            break;
                        case 'purple':
                            themeColor = purple;
                            break;
                        case 'orange':
                            themeColor = orange;
                            break;
                        case 'black':
                            themeColor = black;
                            break;
                    }
                }
                var option = {};
                    option.color = themeColor;
                    option.secondaryColor = ($(this).attr('data-secondary-color')) ? $(this).attr('data-secondary-color') : '#dfdfdf';
                    option.className = ($(this).attr('data-classname')) ? $(this).attr('data-classname') : 'switchery';
                    option.disabled = ($(this).attr('data-disabled')) ? true : false;
                    option.disabledOpacity = ($(this).attr('data-disabled-opacity')) ? parseFloat($(this).attr('data-disabled-opacity')) : 0.5;
                    option.speed = ($(this).attr('data-speed')) ? $(this).attr('data-speed') : '0.5s';
                var switchery = new Switchery(this, option);
            });
        }
    
        $('[data-click="check-switchery-state"]').live('click', function() {
            alert($('[data-id="switchery-state"]').prop('checked'));
        });
        $('[data-change="check-switchery-state-text"]').live('change', function() {
            $('[data-id="switchery-state-text"]').text($(this).prop('checked'));
        });
    
        if ($('[data-render="powerange-slider"]').length !== 0) {
            $('[data-render="powerange-slider"]').each(function() {
                var option = {};
                    option.decimal = ($(this).attr('data-decimal')) ? $(this).attr('data-decimal') : false;
                    option.disable = ($(this).attr('data-disable')) ? $(this).attr('data-disable') : false;
                    option.disableOpacity = ($(this).attr('data-disable-opacity')) ? parseFloat($(this).attr('data-disable-opacity')) : 0.5;
                    option.hideRange = ($(this).attr('data-hide-range')) ? $(this).attr('data-hide-range') : false;
                    option.klass = ($(this).attr('data-class')) ? $(this).attr('data-class') : '';
                    option.min = ($(this).attr('data-min')) ? parseInt($(this).attr('data-min')) : 0;
                    option.max = ($(this).attr('data-max')) ? parseInt($(this).attr('data-max')) : 100;
                    option.start = ($(this).attr('data-start')) ? parseInt($(this).attr('data-start')) : null;
                    option.step = ($(this).attr('data-step')) ? parseInt($(this).attr('data-step')) : null;
                    option.vertical = ($(this).attr('data-vertical')) ? $(this).attr('data-vertical') : false;
                if ($(this).attr('data-height')) {
                    $(this).closest('.slider-wrapper').height($(this).attr('data-height'));
                }
                var switchery = new Switchery(this, option);
                var powerange = new Powerange(this, option);
            });
        }
    });
});



/* -------------------------------
   19.0 CONTROLLER - Form Validation
------------------------------- */
omsApp.controller('formValidationController', function($scope, $rootScope, $state) {
    $scope.submitForm = function(form) {
        if (!form.$valid) {
            $('form[name="'+ form.$name +'"] *').tooltip('destroy');
            angular.forEach(form.$error, function(field) {
                angular.forEach(field, function(errorField) {
                    errorField.$setTouched();
                    var targetContainer = 'form[name="'+ form.$name +'"] [name="'+ errorField.$name +'"]';
                    var targetMessage = (errorField.$error.required) ? 'This is required' : '';
                        targetMessage = (errorField.$error.email) ? 'Invalid email' : targetMessage;
                        targetMessage = (errorField.$error.url) ? 'Invalid website url' : targetMessage;
                        targetMessage = (errorField.$error.number) ? 'Only number is allowed' : targetMessage;
                        targetMessage = (errorField.$name == 'alphabets') ? 'Only alphabets is allowed' : targetMessage;
                        targetMessage = (errorField.$error.minlength) ? 'You must provide at least 20 characters for this field' : targetMessage;
                        targetMessage = (errorField.$error.maxlength) ? 'You must not exceed the maximum of 200 characters for this field' : targetMessage;
                        
                    $(targetContainer).first().tooltip({
                        placement: 'top',
                        trigger: 'normal',
                        title: targetMessage,
                        container: 'body',
                        animation: false
                    });
                    $(targetContainer).first().tooltip('show');
                });
            });
        }
    };
});



/* -------------------------------
   20.0 CONTROLLER - Table Manage Default
------------------------------- */
omsApp.controller('tableManageDefaultController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                responsive: true
            });
        }
    });
});



/* -------------------------------
   21.0 CONTROLLER - Table Manage Autofill
------------------------------- */
omsApp.controller('tableManageAutofillController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                autoFill: true,
                responsive: true
            });
        }
    });
});



/* -------------------------------
   22.0 CONTROLLER - Table Manage Buttons
------------------------------- */
omsApp.controller('tableManageButtonsController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy', className: 'btn-sm' },
                    { extend: 'csv', className: 'btn-sm' },
                    { extend: 'excel', className: 'btn-sm' },
                    { extend: 'pdf', className: 'btn-sm' },
                    { extend: 'print', className: 'btn-sm' }
                ],
                responsive: true
            });
        }
    });
});



/* -------------------------------
   23.0 CONTROLLER - Table Manage ColReorder
------------------------------- */
omsApp.controller('tableManageColReorderController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                colReorder: true,
                responsive: true
            });
        }
    });
});



/* -------------------------------
   24.0 CONTROLLER - Table Manage Fixed Columns
------------------------------- */
omsApp.controller('tableManageFixedColumnsController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                scrollY:        300,
                scrollX:        true,
                scrollCollapse: true,
                paging:         false,
                fixedColumns:   true
            });
        }
    });
});



/* -------------------------------
   25.0 CONTROLLER - Table Manage Fixed Header
------------------------------- */
omsApp.controller('tableManageFixedHeaderController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                lengthMenu: [20, 40, 60],
                fixedHeader: {
                    header: true,
                    headerOffset: $('#header').height()
                },
                responsive: true
            });
        }
    });
});



/* -------------------------------
   26.0 CONTROLLER - Table Manage KeyTable
------------------------------- */
omsApp.controller('tableManageKeyTableController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                scrollY: 300,
                paging: false,
                autoWidth: true,
                keys: true,
                responsive: true
            });
        }
    });
});



/* -------------------------------
   27.0 CONTROLLER - Table Manage Responsive
------------------------------- */
omsApp.controller('tableManageResponsiveController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                responsive: true
            });
        }
    });
});



/* -------------------------------
   28.0 CONTROLLER - Table Manage RowReorder
------------------------------- */
omsApp.controller('tableManageRowReorderController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                responsive: true,
                rowReorder: true
            });
        }
    });
});



/* -------------------------------
   29.0 CONTROLLER - Table Manage Scroller
------------------------------- */
omsApp.controller('tableManageScrollerController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                ajax:           "assets/plugins/DataTables/json/scroller-demo.json",
                deferRender:    true,
                scrollY:        300,
                scrollCollapse: true,
                scroller:       true,
                responsive: true
            });
        }
    });
});



/* -------------------------------
   30.0 CONTROLLER - Table Manage Select
------------------------------- */
omsApp.controller('tableManageSelectController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                select: true,
                responsive: true
            });
        }
    });
});



/* -------------------------------
   31.0 CONTROLLER - Table Manage Extension Combination
------------------------------- */
omsApp.controller('tableManageCombineController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        if ($('#data-table').length !== 0) {
            $('#data-table').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    { extend: 'copy', className: 'btn-sm' },
                    { extend: 'csv', className: 'btn-sm' },
                    { extend: 'excel', className: 'btn-sm' },
                    { extend: 'pdf', className: 'btn-sm' },
                    { extend: 'print', className: 'btn-sm' }
                ],
                responsive: true,
                autoFill: true,
                colReorder: true,
                keys: true,
                rowReorder: true,
                select: true
            });
        }
    });
});



/* -------------------------------
   32.0 CONTROLLER - Flot Chart
------------------------------- */
omsApp.controller('chartFlotController', function($scope, $rootScope, $state) {
    /* Basic Chart
    ------------------------- */
    var d1 = [], d2 = [], d3 = [];
    for (var x = 0; x < Math.PI * 2; x += 0.25) {
        d1.push([x, Math.sin(x)]);
        d2.push([x, Math.cos(x)]);
        var z = x - 0.15;
        d3.push([z, Math.tan(z)]);
    }

    var basicChartData = [
        { label: "data 1",  data: d1, color: purple, shadowSize: 0 },
        { label: "data 2",  data: d2, color: green, shadowSize: 0 },
        { label: "data 3",  data: d3, color: dark, shadowSize: 0 }
    ];
    var basicChartOptions = {
        series: {
            lines: { show: true },
            points: { show: false }
        },
        xaxis: {
            tickColor: '#ddd'
        },
        yaxis: {
            min: -2,
            max: 2,
            tickColor: '#ddd'
        },
        grid: {
            borderColor: '#ddd',
            borderWidth: 1
        }
    }
    this.basicChartData = basicChartData;
    this.basicChartOptions = basicChartOptions;


    /* Stacked Chart
    ------------------------- */
    var d1 = [], d2 = [], d3 = [], d4 = [], d5 = [], d6 = [];
    for (var a = 0; a <= 5; a += 1) {
        d1.push([a, parseInt(Math.random() * 5)]);
        d2.push([a, parseInt(Math.random() * 5 + 5)]);
        d3.push([a, parseInt(Math.random() * 5 + 5)]);
        d4.push([a, parseInt(Math.random() * 5 + 5)]);
        d5.push([a, parseInt(Math.random() * 5 + 5)]);
        d6.push([a, parseInt(Math.random() * 5 + 5)]);
    }
    var ticksLabel = [[0, "Monday"], [1, "Tuesday"], [2, "Wednesday"], [3, "Thursday"], [4, "Friday"], [5, "Saturday"]];
    var stackedChartOptions = { 
        xaxis: {  tickColor: 'transparent',  ticks: ticksLabel},
        yaxis: {  tickColor: '#ddd', ticksLength: 10},
        grid: {  hoverable: true,  tickColor: "#ccc", borderWidth: 0, borderColor: 'rgba(0,0,0,0.2)' },
        series: {
            stack: true,
            lines: { show: false, fill: false, steps: false },
            bars: { show: true, barWidth: 0.5, align: 'center', fillColor: null },
            highlightColor: 'rgba(0,0,0,0.8)'
        },
        legend: { show: true, labelBoxBorderColor: '#ccc', position: 'ne', noColumns: 1 }
    };
    var stackedChartData = [
        { data:d1, color: purpleDark, label: 'China', bars: { fillColor: purpleDark } }, 
        { data:d2, color: purple, label: 'Russia', bars: { fillColor: purple } }, 
        { data:d3, color: purpleLight, label: 'Canada', bars: { fillColor: purpleLight } }, 
        { data:d4, color: blueDark, label: 'Japan', bars: { fillColor: blueDark } }, 
        { data:d5, color: blue, label: 'USA', bars: { fillColor: blue } }, 
        { data:d6, color: blueLight, label: 'Others', bars: { fillColor: blueLight } }
    ];

    var previousXValue = null;
    var previousYValue = null;
    $("#stacked-chart").bind("plothover", function (event, pos, item) {
        if (item) {
            var y = item.datapoint[1] - item.datapoint[2];
            if (previousXValue != item.series.label || y != previousYValue) {
                previousXValue = item.series.label;
                previousYValue = y;
                $("#tooltip").remove();
                $('<div id="tooltip" class="flot-tooltip">' + item.series.label + '</div>').css({ top: item.pageY, left: item.pageX + 35 }).appendTo("body").fadeIn(200);
            }
        } else {
            $("#tooltip").remove();
            previousXValue = null;
            previousYValue = null;       
        }
    });

    this.stackedChartOptions = stackedChartOptions;
    this.stackedChartData = stackedChartData;


    /* Tracking Chart
    ------------------------- */
    var sin = [], cos = [];
    for (var i = 0; i < 14; i += 0.1) {
        sin.push([i, Math.sin(i)]);
        cos.push([i, Math.cos(i)]);
    }

    var trackingChartData = [ 
        { data: sin, label: "Series1", color: dark, shadowSize: 0},
        { data: cos, label: "Series2", color: red, shadowSize: 0} 
    ];
    var trackingChartOptions = {
        series: { lines: { show: true } },
        crosshair: { mode: "x", color: grey },
        grid: { hoverable: true, autoHighlight: false, borderColor: '#ccc', borderWidth: 0 },
        xaxis: {  tickLength: 0 },
        yaxis: {  tickColor: '#ddd' },
        legend: {
            labelBoxBorderColor: '#ddd',
            backgroundOpacity: 0.4,
            color:'#fff',
            show: true
        }
    };
    this.trackingChartData = trackingChartData;
    this.trackingChartOptions = trackingChartOptions;


    /* Bar Chart
    ------------------------- */
    var barChartData = [{
        data: [ ["January", 10], ["February", 8], ["March", 4], ["April", 13], ["May", 17], ["June", 9] ],
        color: purple
    }];
    var barChartOptions = {
        series: {
            bars: {
                show: true, barWidth: 0.4, align: 'center', fill: true, fillColor: purple, zero: true
            }
        },
        xaxis: { mode: "categories", tickColor: '#ddd', tickLength: 0 },
        grid: { borderWidth: 0 }
    };
    this.barChartData = barChartData;
    this.barChartOptions = barChartOptions;


    /* Pie Chart
    ------------------------- */
    var pieChartData = [];
    var series = 3;
    var colorArray = [purple, dark, grey];
    for (var i=0; i<series; i++) {
        pieChartData[i] = { label: "Series"+(i+1), data: Math.floor(Math.random()*100)+1, color: colorArray[i] };
    }
    var pieChartOptions = {
        series: {
            pie: { 
                show: true
            }
        },
        grid: { hoverable: true, clickable: true },
        legend: { labelBoxBorderColor: '#ddd', backgroundColor: 'none' }
    };
    this.pieChartData = pieChartData;
    this.pieChartOptions = pieChartOptions;


    /* Donut Chart
    ------------------------- */
    var donutChartData = [];
    var donutChartOptions = {
        series: {
            pie: { 
                innerRadius: 0.5,
                show: true,
                combine: { color: '#999', threshold: 0.1 }
            }
        },
        grid:{ borderWidth:0, hoverable: true, clickable: true },
        legend: { show: false }
    };
    var colorArray = [dark, green, purple];
    var nameArray = ['Unique Visitor', 'Bounce Rate', 'Total Page Views', 'Avg Time On Site'];
    var dataArray = [20,14,12,31];
    for( var i = 0; i<3; i++) {
        donutChartData[i] = { label: nameArray[i], data: dataArray[i], color: colorArray[i] };
    }

    this.donutChartData = donutChartData;
    this.donutChartOptions = donutChartOptions;


    /* Interactive Chart
    ------------------------- */
    var interactiveChartOptions = {
        xaxis: {  tickColor: '#ddd',tickSize: 2 },
        yaxis: {  tickColor: '#ddd', tickSize: 20 },
        grid: {  hoverable: true,  clickable: true, tickColor: "#ccc", borderWidth: 1, borderColor: '#ddd' },
        legend: { labelBoxBorderColor: '#ddd', margin: 0, noColumns: 1, show: true }
    };
    var d1 = [[0, 42], [1, 53], [2,66], [3, 60], [4, 68], [5, 66], [6,71],[7, 75], [8, 69], [9,70], [10, 68], [11, 72], [12, 78], [13, 86]];
    var d2 = [[0, 12], [1, 26], [2,13], [3, 18], [4, 35], [5, 23], [6, 18],[7, 35], [8, 24], [9,14], [10, 14], [11, 29], [12, 30], [13, 43]];
    var interactiveChartData = [{
        data: d1, 
        label: "Page Views", 
        color: purple,
        lines: { show: true, fill:false, lineWidth: 2 },
        points: { show: false, radius: 5, fillColor: '#fff' },
        shadowSize: 0
    }, {
        data: d2,
        label: 'Visitors',
        color: green,
        lines: { show: true, fill:false, lineWidth: 2, fillColor: '' },
        points: { show: false, radius: 3, fillColor: '#fff' },
        shadowSize: 0
    }];

    this.interactiveChartOptions = interactiveChartOptions;
    this.interactiveChartData = interactiveChartData;

    var previousPoint = null;

    $("#interactive-chart").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));
        if (item) {
            if (previousPoint !== item.dataIndex) {
                previousPoint = item.dataIndex;
                $("#tooltip").remove();
                var y = item.datapoint[1].toFixed(2);
                var content = item.series.label + " " + y;
                $('<div id="tooltip" class="flot-tooltip">' + content + '</div>').css({ top: item.pageY - 45, left: item.pageX - 55 }).appendTo("body").fadeIn(200);
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;            
        }
        event.preventDefault();
    });
    
    
    /* Live Updated Chart 
    ------------------------- */
    function update() {
        plot.setData([ getRandomData() ]);
        plot.draw();
        setTimeout(update, updateInterval);
    }
    function getRandomData() {
        if (data.length > 0) {
            data = data.slice(1);
        }
        while (data.length < totalPoints) {
            var prev = data.length > 0 ? data[data.length - 1] : 50;
            var y = prev + Math.random() * 10 - 5;
            y = (y < 0) ? 0 : y;
            y = (y > 100) ? 100 : y;
            data.push(y);
        }
        var res = [];
        for (var i = 0; i < data.length; ++i) {
            res.push([i, data[i]]);
        }
        return res;
    }

    var data = [], totalPoints = 150;
    var updateInterval = 1000;

    $("#updateInterval").val(updateInterval).change(function () {
        var v = $(this).val();
        if (v && !isNaN(+v)) {
            updateInterval = +v;
            updateInterval = (updateInterval < 1) ? 1 : updateInterval;
            updateInterval = (updateInterval > 2000) ? 2000 : updateInterval;
            $(this).val("" + updateInterval);
        }
    });
    var options = {
        series: { shadowSize: 0, color: purple, lines: { show: true, fill:true } }, // drawing is faster without shadows
        yaxis: { min: 0, max: 100, tickColor: '#ddd' },
        xaxis: { show: true, tickColor: '#ddd' },
        grid: { borderWidth: 1, borderColor: '#ddd' }
    };
    var plot = $.plot($("#live-updated-chart"), [ getRandomData() ], options);
    update();
});



/* -------------------------------
   33.0 CONTROLLER - Morris Chart
------------------------------- */
omsApp.controller('chartMorrisController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        /* Morris Line Chart
        ------------------------- */
        var tax_data = [
            {"period": "2011 Q3", "licensed": 3407, "sorned": 660},
            {"period": "2011 Q2", "licensed": 3351, "sorned": 629},
            {"period": "2011 Q1", "licensed": 3269, "sorned": 618},
            {"period": "2010 Q4", "licensed": 3246, "sorned": 661},
            {"period": "2009 Q4", "licensed": 3171, "sorned": 676},
            {"period": "2008 Q4", "licensed": 3155, "sorned": 681},
            {"period": "2007 Q4", "licensed": 3226, "sorned": 620},
            {"period": "2006 Q4", "licensed": 3245, "sorned": null},
            {"period": "2005 Q4", "licensed": 3289, "sorned": null}
        ];
        Morris.Line({
            element: 'morris-line-chart',
            data: tax_data,
            xkey: 'period',
            ykeys: ['licensed', 'sorned'],
            labels: ['Licensed', 'Off the road'],
            resize: true,
            lineColors: [dark, blue]
        });
    
    
        /* Morris Bar Chart
        ------------------------- */
        Morris.Bar({
            element: 'morris-bar-chart',
            data: [
                {device: 'iPhone', geekbench: 136},
                {device: 'iPhone 3G', geekbench: 137},
                {device: 'iPhone 3GS', geekbench: 275},
                {device: 'iPhone 4', geekbench: 380},
                {device: 'iPhone 4S', geekbench: 655},
                {device: 'iPhone 5', geekbench: 1571}
            ],
            xkey: 'device',
            ykeys: ['geekbench'],
            labels: ['Geekbench'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            resize: true,
            barColors: [dark]
        });
    
    
        /* Morris Area Chart
        ------------------------- */
        Morris.Area({
            element: 'morris-area-chart',
            data: [
                {period: '2010 Q1', iphone: 2666, ipad: null, itouch: 2647},
                {period: '2010 Q2', iphone: 2778, ipad: 2294, itouch: 2441},
                {period: '2010 Q3', iphone: 4912, ipad: 1969, itouch: 2501},
                {period: '2010 Q4', iphone: 3767, ipad: 3597, itouch: 5689},
                {period: '2011 Q1', iphone: 6810, ipad: 1914, itouch: 2293},
                {period: '2011 Q2', iphone: 5670, ipad: 4293, itouch: 1881},
                {period: '2011 Q3', iphone: 4820, ipad: 3795, itouch: 1588},
                {period: '2011 Q4', iphone: 15073, ipad: 5967, itouch: 5175},
                {period: '2012 Q1', iphone: 10687, ipad: 4460, itouch: 2028},
                {period: '2012 Q2', iphone: 8432, ipad: 5713, itouch: 1791}
            ],
            xkey: 'period',
            ykeys: ['iphone', 'ipad', 'itouch'],
            labels: ['iPhone', 'iPad', 'iPod Touch'],
            pointSize: 2,
            hideHover: 'auto',
            resize: true,
            lineColors: [red, orange, dark]
        });
    
    
        /* Morris Area Chart
        ------------------------- */
        Morris.Donut({
            element: 'morris-donut-chart',
            data: [
                {label: 'Jam', value: 25 },
                {label: 'Frosted', value: 40 },
                {label: 'Custard', value: 25 },
                {label: 'Sugar', value: 10 }
            ],
            formatter: function (y) { return y + "%" },
            resize: true,
            colors: [dark, orange, red, grey]
        });
    });
});



/* -------------------------------
   34.0 CONTROLLER - Chart JS
------------------------------- */
omsApp.controller('chartJsController', function($scope, $rootScope, $state) {
    // white
    var white = 'rgba(255,255,255,1.0)';
    var fillBlack = 'rgba(45, 53, 60, 0.6)';
    var fillBlackLight = 'rgba(45, 53, 60, 0.2)';
    var strokeBlack = 'rgba(45, 53, 60, 0.8)';
    var highlightFillBlack = 'rgba(45, 53, 60, 0.8)';
    var highlightStrokeBlack = 'rgba(45, 53, 60, 1)';

    // blue
    var fillBlue = 'rgba(52, 143, 226, 0.6)';
    var fillBlueLight = 'rgba(52, 143, 226, 0.2)';
    var strokeBlue = 'rgba(52, 143, 226, 0.8)';
    var highlightFillBlue = 'rgba(52, 143, 226, 0.8)';
    var highlightStrokeBlue = 'rgba(52, 143, 226, 1)';

    // grey
    var fillGrey = 'rgba(182, 194, 201, 0.6)';
    var fillGreyLight = 'rgba(182, 194, 201, 0.2)';
    var strokeGrey = 'rgba(182, 194, 201, 0.8)';
    var highlightFillGrey = 'rgba(182, 194, 201, 0.8)';
    var highlightStrokeGrey = 'rgba(182, 194, 201, 1)';

    // green
    var fillGreen = 'rgba(0, 172, 172, 0.6)';
    var fillGreenLight = 'rgba(0, 172, 172, 0.2)';
    var strokeGreen = 'rgba(0, 172, 172, 0.8)';
    var highlightFillGreen = 'rgba(0, 172, 172, 0.8)';
    var highlightStrokeGreen = 'rgba(0, 172, 172, 1)';

    // purple
    var fillPurple = 'rgba(114, 124, 182, 0.6)';
    var fillPurpleLight = 'rgba(114, 124, 182, 0.2)';
    var strokePurple = 'rgba(114, 124, 182, 0.8)';
    var highlightFillPurple = 'rgba(114, 124, 182, 0.8)';
    var highlightStrokePurple = 'rgba(114, 124, 182, 1)';


    /* ChartJS Bar Chart
    ------------------------- */
    var randomScalingFactor = function() { 
        return Math.round(Math.random()*100)
    };

    var barChartData = {
        labels : ['January','February','March','April','May','June','July'],
        datasets : [{
            fillColor : fillBlackLight,
            strokeColor : strokeBlack,
            highlightFill: highlightFillBlack,
            highlightStroke: highlightStrokeBlack,
            data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
        }, {
            fillColor : fillBlueLight,
            strokeColor : strokeBlue,
            highlightFill: highlightFillBlue,
            highlightStroke: highlightStrokeBlue,
            data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
        }]
    };
    this.barChartData = barChartData;


    /* ChartJS Doughnut Chart
    ------------------------- */
    var doughnutChartData = [
        { value: 300, color: fillGrey, highlight: highlightFillGrey, label: 'Grey' },
        { value: 50, color: fillGreen, highlight: highlightFillGreen, label: 'Green' },
        { value: 100, color: fillBlue, highlight: highlightFillBlue, label: 'Blue' },
        { value: 40, color: fillPurple, highlight: highlightFillPurple, label: 'Purple' },
        { value: 120, color: fillBlack, highlight: highlightFillBlack, label: 'Black' }
    ];
    this.doughnutChartData = doughnutChartData;


    /* ChartJS Line Chart
    ------------------------- */
    var lineChartData = {
        labels : ['January','February','March','April','May','June','July'],
        datasets : [{
            label: 'My First dataset',
            fillColor : fillBlackLight,
            strokeColor : strokeBlack,
            pointColor : strokeBlack,
            pointStrokeColor : white,
            pointHighlightFill : white,
            pointHighlightStroke : strokeBlack,
            data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
        }, {
            label: 'My Second dataset',
            fillColor : 'rgba(52,143,226,0.2)',
            strokeColor : 'rgba(52,143,226,1)',
            pointColor : 'rgba(52,143,226,1)',
            pointStrokeColor : '#fff',
            pointHighlightFill : '#fff',
            pointHighlightStroke : 'rgba(52,143,226,1)',
            data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
        }]
    };
    this.lineChartData = lineChartData;


    /* ChartJS Pie Chart
    ------------------------- */
    var pieChartData = [
        { value: 300, color: strokePurple, highlight: highlightStrokePurple, label: 'Purple' },
        { value: 50, color: strokeBlue, highlight: highlightStrokeBlue, label: 'Blue' },
        { value: 100, color: strokeGreen, highlight: highlightStrokeGreen, label: 'Green' },
        { value: 40, color: strokeGrey, highlight: highlightStrokeGrey, label: 'Grey' },
        { value: 120, color: strokeBlack, highlight: highlightStrokeBlack, label: 'Black' }
    ];
    this.pieChartData = pieChartData;


    /* ChartJS Polar Chart
    ------------------------- */
    var polarChartData = [
        { value: 300, color: strokePurple, highlight: highlightStrokePurple, label: 'Purple' },
        { value: 50, color: strokeBlue, highlight: highlightStrokeBlue, label: 'Blue' },
        { value: 100, color: strokeGreen, highlight: highlightStrokeGreen, label: 'Green' },
        { value: 40, color: strokeGrey, highlight: highlightStrokeGrey, label: 'Grey' },
        { value: 120, color: strokeBlack, highlight: highlightStrokeBlack, label: 'Black' }
    ];
    this.polarChartData = polarChartData;


    /* ChartJS Radar Chart
    ------------------------- */
    var radarChartData = {
        labels: ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'],
        datasets: [{
            label: 'My First dataset',
            fillColor: 'rgba(45,53,60,0.2)',
            strokeColor: 'rgba(45,53,60,1)',
            pointColor: 'rgba(45,53,60,1)',
            pointStrokeColor: '#fff',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(45,53,60,1)',
            data: [65,59,90,81,56,55,40]
        }, {
            label: 'My Second dataset',
            fillColor: 'rgba(52,143,226,0.2)',
            strokeColor: 'rgba(52,143,226,1)',
            pointColor: 'rgba(52,143,226,1)',
            pointStrokeColor: '#fff',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(52,143,226,1)',
            data: [28,48,40,19,96,27,100]
        }]
    };
    this.radarChartData = radarChartData;


    /* ChartJS Chart Options
    ------------------------- */
    var chartOptions = {
        animation: true,
        animationSteps: 60,
        animationEasing: 'easeOutQuart',
        showScale: true,
        scaleOverride: false,
        scaleSteps: null,
        scaleStepWidth: null,
        scaleStartValue: null,
        scaleLineColor: 'rgba(0,0,0,.1)',
        scaleLineWidth: 1,
        scaleShowLabels: true,
        scaleLabel: '<%=value%>',
        scaleIntegersOnly: true,
        scaleBeginAtZero: false,
        scaleFontFamily: '"Open Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif',
        scaleFontSize: 12,
        scaleFontStyle: 'normal',
        scaleFontColor: '#707478',
        responsive: true,
        maintainAspectRatio: true,
        showTooltips: true,
        customTooltips: false,
        tooltipEvents: ['mousemove', 'touchstart', 'touchmove'],
        tooltipFillColor: 'rgba(0,0,0,0.8)',
        tooltipFontFamily: '"Open Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif',
        tooltipFontSize: 12,
        tooltipFontStyle: 'normal',
        tooltipFontColor: '#ccc',
        tooltipTitleFontFamily: '"Open Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif',
        tooltipTitleFontSize: 12,
        tooltipTitleFontStyle: 'bold',
        tooltipTitleFontColor: '#fff',
        tooltipYPadding: 10,
        tooltipXPadding: 10,
        tooltipCaretSize: 8,
        tooltipCornerRadius: 3,
        tooltipXOffset: 10,
        tooltipTemplate: '<%if (label){%><%=label%>: <%}%><%= value %>',
        multiTooltipTemplate: '<%= value %>',
        onAnimationProgress: function(){},
        onAnimationComplete: function(){}
    }
    this.chartOptions = chartOptions;
});



/* -------------------------------
   35.0 CONTROLLER - Chart d3
------------------------------- */
omsApp.controller('chartD3Controller', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
    
        /* d3 Line Chart
        ------------------------- */
        nv.addGraph(function() {
            var sin = [], cos = [];
            for (var i = 0; i < 100; i++) {
                sin.push({x: i, y:  Math.sin(i/10) });
                cos.push({x: i, y: .5 * Math.cos(i/10)});
            }
            var lineChartData = [
                { values: sin, key: 'Sine Wave', color: green }, 
                { values: cos, key: 'Cosine Wave', color: blue }
            ];
            var lineChart = nv.models.lineChart().options({ transitionDuration: 300, useInteractiveGuideline: true });
                lineChart.xAxis.axisLabel('Time (s)').tickFormat(d3.format(',.1f'));
                lineChart.yAxis.axisLabel('Voltage (v)').tickFormat(function(d) {
                    if (d == null) {
                        return 'N/A';
                    }
                    return d3.format(',.2f')(d);
                });

            d3.select('#nv-line-chart').append('svg').datum(lineChartData).call(lineChart);
            nv.utils.windowResize(lineChart.update);
            return lineChart;
        });


        /* d3 Bar Chart
        ------------------------- */
        nv.addGraph(function() {
            var barChartData = [{
                key: 'Cumulative Return',
                values: [
                    { 'label' : 'A', 'value' : 29, 'color' : red }, 
                    { 'label' : 'B', 'value' : 15, 'color' : orange }, 
                    { 'label' : 'C', 'value' : 32, 'color' : green }, 
                    { 'label' : 'D', 'value' : 196, 'color' : aqua },  
                    { 'label' : 'E', 'value' : 44, 'color' : blue },  
                    { 'label' : 'F', 'value' : 98, 'color' : purple },  
                    { 'label' : 'G', 'value' : 13, 'color' : grey },  
                    { 'label' : 'H', 'value' : 5, 'color' : dark }
                ]
            }];
            var barChart = nv.models.discreteBarChart()
                .x(function(d) { return d.label })
                .y(function(d) { return d.value })
                .showValues(true)
                .duration(250);
            
                barChart.yAxis.axisLabel("Total Sales");
                barChart.xAxis.axisLabel('Product');
    
            d3.select('#nv-bar-chart').append('svg').datum(barChartData).call(barChart);
            nv.utils.windowResize(barChart.update);
            return barChart;
        });


        /* d3 Pie Chart
        ------------------------- */
        nv.addGraph(function() {
            var pieChartData = [
                { 'label': 'One', 'value' : 29, 'color': red }, 
                { 'label': 'Two', 'value' : 12, 'color': orange }, 
                { 'label': 'Three', 'value' : 32, 'color': green }, 
                { 'label': 'Four', 'value' : 196, 'color': aqua }, 
                { 'label': 'Five', 'value' : 17, 'color': blue }, 
                { 'label': 'Six', 'value' : 98, 'color': purple }, 
                { 'label': 'Seven', 'value' : 13, 'color': grey }, 
                { 'label': 'Eight', 'value' : 5, 'color': dark }
            ];
        
            var pieChart = nv.models.pieChart()
              .x(function(d) { return d.label })
              .y(function(d) { return d.value })
              .showLabels(true)
              .labelThreshold(.05);

            d3.select('#nv-pie-chart').append('svg').datum(pieChartData).transition().duration(350).call(pieChart);
            return pieChart;
        });


        /* d3 Donut Chart
        ------------------------- */
        nv.addGraph(function() {
            var donutChartData = [
                { 'label': 'One', 'value' : 29, 'color': red }, 
                { 'label': 'Two', 'value' : 12, 'color': orange }, 
                { 'label': 'Three', 'value' : 32, 'color': green }, 
                { 'label': 'Four', 'value' : 196, 'color': aqua }, 
                { 'label': 'Five', 'value' : 17, 'color': blue }, 
                { 'label': 'Six', 'value' : 98, 'color': purple }, 
                { 'label': 'Seven', 'value' : 13, 'color': grey }, 
                { 'label': 'Eight', 'value' : 5, 'color': dark }
            ];
            var chart = nv.models.pieChart()
                .x(function(d) { return d.label })
                .y(function(d) { return d.value })
                .showLabels(true)
                .labelThreshold(.05)
                .labelType("percent")
                .donut(true) 
                .donutRatio(0.35);

            d3.select('#nv-donut-chart').append('svg')
                .datum(donutChartData)
                .transition().duration(350)
                .call(chart);
            return chart;
        });


        /* d3 Stacked Area Chart
        ------------------------- */
        nv.addGraph(function() {
            var stackedAreaChartData = [{
                'key' : 'Financials',
                'color' : red,
                'values' : [ [ 1138683600000 , 13.356778764352] , [ 1141102800000 , 13.611196863271] , [ 1143781200000 , 6.895903006119] , [ 1146369600000 , 6.9939633271352] , [ 1149048000000 , 6.7241510257675] , [ 1151640000000 , 5.5611293669516] , [ 1154318400000 , 5.6086488714041] , [ 1156996800000 , 5.4962849907033] , [ 1159588800000 , 6.9193153169279] , [ 1162270800000 , 7.0016334389777] , [ 1164862800000 , 6.7865422443273] , [ 1167541200000 , 9.0006454225383] , [ 1170219600000 , 9.2233916171431] , [ 1172638800000 , 8.8929316009479] , [ 1175313600000 , 10.345937520404] , [ 1177905600000 , 10.075914677026] , [ 1180584000000 , 10.089006188111] , [ 1183176000000 , 10.598330295008] , [ 1185854400000 , 9.968954653301] , [ 1188532800000 , 9.7740580198146] , [ 1191124800000 , 10.558483060626] , [ 1193803200000 , 9.9314651823603] , [ 1196398800000 , 9.3997715873769] , [ 1199077200000 , 8.4086493387262] , [ 1201755600000 , 8.9698309085926] , [ 1204261200000 , 8.2778357995396] , [ 1206936000000 , 8.8585045600123] , [ 1209528000000 , 8.7013756413322] , [ 1212206400000 , 7.7933605469443] , [ 1214798400000 , 7.0236183483064] , [ 1217476800000 , 6.9873088186829] , [ 1220155200000 , 6.8031713070097] , [ 1222747200000 , 6.6869531315723] , [ 1225425600000 , 6.138256993963] , [ 1228021200000 , 5.6434994016354] , [ 1230699600000 , 5.495220262512] , [ 1233378000000 , 4.6885326869846] , [ 1235797200000 , 4.4524349883438] , [ 1238472000000 , 5.6766520778185] , [ 1241064000000 , 5.7675774480752] , [ 1243742400000 , 5.7882863168337] , [ 1246334400000 , 7.2666010034924] , [ 1249012800000 , 7.519182132226] , [ 1251691200000 , 7.849651451445] , [ 1254283200000 , 10.383992037985] , [ 1256961600000 , 9.0653691861818] , [ 1259557200000 , 9.6705248324159] , [ 1262235600000 , 10.856380561349] , [ 1264914000000 , 11.27452370892] , [ 1267333200000 , 11.754156529088] , [ 1270008000000 , 8.2870811422456] , [ 1272600000000 , 8.0210264360699] , [ 1275278400000 , 7.5375074474865] , [ 1277870400000 , 8.3419527338039] , [ 1280548800000 , 9.4197471818443] , [ 1283227200000 , 8.7321733185797] , [ 1285819200000 , 9.6627062648126] , [ 1288497600000 , 10.187962234549] , [ 1291093200000 , 9.8144201733476] , [ 1293771600000 , 10.275723361713] , [ 1296450000000 , 16.796066079353] , [ 1298869200000 , 17.543254984075] , [ 1301544000000 , 16.673660675084] , [ 1304136000000 , 17.963944353609] , [ 1306814400000 , 16.637740867211] , [ 1309406400000 , 15.84857094609] , [ 1312084800000 , 14.767303362182] , [ 1314763200000 , 24.778452182432] , [ 1317355200000 , 18.370353229999] , [ 1320033600000 , 15.2531374291] , [ 1322629200000 , 14.989600840649] , [ 1325307600000 , 16.052539160125] , [ 1327986000000 , 16.424390322793] , [ 1330491600000 , 17.884020741105] , [ 1333166400000 , 7.1424929577921] , [ 1335758400000 , 7.8076213051482] , [ 1338436800000 , 7.2462684949232]]
            }, {
                'key' : 'Health Care',
                'color' : orange,
                'values' : [ [ 1138683600000 , 14.212410956029] , [ 1141102800000 , 13.973193618249] , [ 1143781200000 , 15.218233920665] , [ 1146369600000 , 14.38210972745] , [ 1149048000000 , 13.894310878491] , [ 1151640000000 , 15.593086090032] , [ 1154318400000 , 16.244839695188] , [ 1156996800000 , 16.017088850646] , [ 1159588800000 , 14.183951830055] , [ 1162270800000 , 14.148523245697] , [ 1164862800000 , 13.424326059972] , [ 1167541200000 , 12.974450435753] , [ 1170219600000 , 13.23247041802] , [ 1172638800000 , 13.318762655574] , [ 1175313600000 , 15.961407746104] , [ 1177905600000 , 16.287714639805] , [ 1180584000000 , 16.246590583889] , [ 1183176000000 , 17.564505594809] , [ 1185854400000 , 17.872725373165] , [ 1188532800000 , 18.018998508757] , [ 1191124800000 , 15.584518016603] , [ 1193803200000 , 15.480850647181] , [ 1196398800000 , 15.699120036984] , [ 1199077200000 , 19.184281817226] , [ 1201755600000 , 19.691226605207] , [ 1204261200000 , 18.982314051295] , [ 1206936000000 , 18.707820309008] , [ 1209528000000 , 17.459630929761] , [ 1212206400000 , 16.500616076782] , [ 1214798400000 , 18.086324003979] , [ 1217476800000 , 18.929464156258] , [ 1220155200000 , 18.233728682084] , [ 1222747200000 , 16.315776297325] , [ 1225425600000 , 14.63289219025] , [ 1228021200000 , 14.667835024478] , [ 1230699600000 , 13.946993947308] , [ 1233378000000 , 14.394304684397] , [ 1235797200000 , 13.724462792967] , [ 1238472000000 , 10.930879035806] , [ 1241064000000 , 9.8339915513708] , [ 1243742400000 , 10.053858541872] , [ 1246334400000 , 11.786998438287] , [ 1249012800000 , 11.780994901769] , [ 1251691200000 , 11.305889670276] , [ 1254283200000 , 10.918452290083] , [ 1256961600000 , 9.6811395055706] , [ 1259557200000 , 10.971529744038] , [ 1262235600000 , 13.330210480209] , [ 1264914000000 , 14.592637568961] , [ 1267333200000 , 14.605329141157] , [ 1270008000000 , 13.936853794037] , [ 1272600000000 , 12.189480759072] , [ 1275278400000 , 11.676151385046] , [ 1277870400000 , 13.058852800017] , [ 1280548800000 , 13.62891543203] , [ 1283227200000 , 13.811107569918] , [ 1285819200000 , 13.786494560787] , [ 1288497600000 , 14.04516285753] , [ 1291093200000 , 13.697412447288] , [ 1293771600000 , 13.677681376221] , [ 1296450000000 , 19.961511864531] , [ 1298869200000 , 21.049198298158] , [ 1301544000000 , 22.687631094008] , [ 1304136000000 , 25.469010617433] , [ 1306814400000 , 24.883799437121] , [ 1309406400000 , 24.203843814248] , [ 1312084800000 , 22.138760964038] , [ 1314763200000 , 16.034636966228] , [ 1317355200000 , 15.394958944556] , [ 1320033600000 , 12.625642461969] , [ 1322629200000 , 12.973735699739] , [ 1325307600000 , 15.786018336149] , [ 1327986000000 , 15.227368020134] , [ 1330491600000 , 15.899752650734] , [ 1333166400000 , 18.994731295388] , [ 1335758400000 , 18.450055817702] , [ 1338436800000 , 17.863719889669]]
            }, {
                'key' : 'Information Technology',
                'color' : dark,
                'values' : [ [ 1138683600000 , 13.242301508051] , [ 1141102800000 , 12.863536342042] , [ 1143781200000 , 21.034044171629] , [ 1146369600000 , 21.419084618803] , [ 1149048000000 , 21.142678863691] , [ 1151640000000 , 26.568489677529] , [ 1154318400000 , 24.839144939905] , [ 1156996800000 , 25.456187462167] , [ 1159588800000 , 26.350164502826] , [ 1162270800000 , 26.47833320519] , [ 1164862800000 , 26.425979547847] , [ 1167541200000 , 28.191461582256] , [ 1170219600000 , 28.930307448808] , [ 1172638800000 , 29.521413891117] , [ 1175313600000 , 28.188285966466] , [ 1177905600000 , 27.704619625832] , [ 1180584000000 , 27.490862424829] , [ 1183176000000 , 28.770679721286] , [ 1185854400000 , 29.060480671449] , [ 1188532800000 , 28.240998844973] , [ 1191124800000 , 33.004893194127] , [ 1193803200000 , 34.075180359928] , [ 1196398800000 , 32.548560664833] , [ 1199077200000 , 30.629727432728] , [ 1201755600000 , 28.642858788159] , [ 1204261200000 , 27.973575227842] , [ 1206936000000 , 27.393351882726] , [ 1209528000000 , 28.476095288523] , [ 1212206400000 , 29.29667866426] , [ 1214798400000 , 29.222333802896] , [ 1217476800000 , 28.092966093843] , [ 1220155200000 , 28.107159262922] , [ 1222747200000 , 25.482974832098] , [ 1225425600000 , 21.208115993834] , [ 1228021200000 , 20.295043095268] , [ 1230699600000 , 15.925754618401] , [ 1233378000000 , 17.162864628346] , [ 1235797200000 , 17.084345773174] , [ 1238472000000 , 22.246007102281] , [ 1241064000000 , 24.530543998509] , [ 1243742400000 , 25.084184918242] , [ 1246334400000 , 16.606166527358] , [ 1249012800000 , 17.239620011628] , [ 1251691200000 , 17.336739127379] , [ 1254283200000 , 25.478492475753] , [ 1256961600000 , 23.017152085245] , [ 1259557200000 , 25.617745423683] , [ 1262235600000 , 24.061133998642] , [ 1264914000000 , 23.223933318644] , [ 1267333200000 , 24.425887263937] , [ 1270008000000 , 35.501471156693] , [ 1272600000000 , 33.775013878676] , [ 1275278400000 , 30.417993630285] , [ 1277870400000 , 30.023598978467] , [ 1280548800000 , 33.327519522436] , [ 1283227200000 , 31.963388450371] , [ 1285819200000 , 30.498967232092] , [ 1288497600000 , 32.403696817912] , [ 1291093200000 , 31.47736071922] , [ 1293771600000 , 31.53259666241] , [ 1296450000000 , 41.760282761548] , [ 1298869200000 , 45.605771243237] , [ 1301544000000 , 39.986557966215] , [ 1304136000000 , 43.846330510051] , [ 1306814400000 , 39.857316881857] , [ 1309406400000 , 37.675127768208] , [ 1312084800000 , 35.775077970313] , [ 1314763200000 , 48.631009702577] , [ 1317355200000 , 42.830831754505] , [ 1320033600000 , 35.611502589362] , [ 1322629200000 , 35.320136981738] , [ 1325307600000 , 31.564136901516] , [ 1327986000000 , 32.074407502433] , [ 1330491600000 , 35.053013769976] , [ 1333166400000 , 26.434568573937] , [ 1335758400000 , 25.305617871002] , [ 1338436800000 , 24.520919418236]]
            }];
        
            var stackedAreaChart = nv.models.stackedAreaChart()
                .useInteractiveGuideline(true)
                .x(function(d) { return d[0] })
                .y(function(d) { return d[1] })
                .controlLabels({stacked: 'Stacked'})
                .showControls(false)
                .duration(300);

            stackedAreaChart.xAxis.tickFormat(function(d) { return d3.time.format('%x')(new Date(d)) });
            stackedAreaChart.yAxis.tickFormat(d3.format(',.4f'));

            d3.select('#nv-stacked-area-chart')
                .append('svg')
                .datum(stackedAreaChartData)
                .transition().duration(1000)
                .call(stackedAreaChart)
                .each('start', function() {
                    setTimeout(function() {
                        d3.selectAll('#nv-stacked-area-chart *').each(function() {
                            if(this.__transition__)
                                this.__transition__.duration = 1;
                        })
                    }, 0)
                });
            nv.utils.windowResize(stackedAreaChart.update);
            return stackedAreaChart;
        });


        /* d3 Stacked Bar Chart
        ------------------------- */
        var stackedBarChartData = [{
            key: 'Stream 1',
            'color' : red,
            values: [
                { x:1, y: 10}, { x:2, y: 15}, { x:3, y: 16}, { x:4, y: 20}, { x:5, y: 57}, { x:6, y: 42}, { x:7, y: 12}, { x:8, y: 65}, { x:9, y: 34}, { x:10, y: 52}, 
                { x:11, y: 23}, { x:12, y: 12}, { x:13, y: 22}, { x:14, y: 22}, { x:15, y: 48}, { x:16, y: 54}, { x:17, y: 32}, { x:18, y: 13}, { x:19, y: 21}, { x:20, y: 12}
            ]
        },{
            key: 'Stream 2',
            'color' : orange,
            values: [
                { x:1, y: 10}, { x:2, y: 15}, { x:3, y: 16}, { x:4, y: 45}, { x:5, y: 67}, { x:6, y: 34}, { x:7, y: 43}, { x:8, y: 65}, { x:9, y: 32}, { x:10, y: 12}, 
                { x:11, y: 43}, { x:12, y: 45}, { x:13, y: 32}, { x:14, y: 32}, { x:15, y: 38}, { x:16, y: 64}, { x:17, y: 42}, { x:18, y: 23}, { x:19, y: 31}, { x:20, y: 22}
            ]
        },{
            key: 'Stream 2',
            'color' : dark,
            values: [
                { x:1, y: 20}, { x:2, y: 25}, { x:3, y: 26}, { x:4, y: 30}, { x:5, y: 57}, { x:6, y: 52}, { x:7, y: 22}, { x:8, y: 75}, { x:9, y: 44}, { x:10, y: 62}, 
                { x:11, y: 35}, { x:12, y: 15}, { x:13, y: 25}, { x:14, y: 25}, { x:15, y: 45}, { x:16, y: 55}, { x:17, y: 35}, { x:18, y: 15}, { x:19, y: 25}, { x:20, y: 15}
            ]
        }];
        nv.addGraph({
            generate: function() {
                var stackedBarChart = nv.models.multiBarChart()
                    .stacked(true)
                    .showControls(false);
            
                var svg = d3.select('#nv-stacked-bar-chart').append('svg').datum(stackedBarChartData);
                svg.transition().duration(0).call(stackedBarChart);
                return stackedBarChart;
            }
        });
    });
});



/* -------------------------------
   36.0 CONTROLLER - Calendar
------------------------------- */
omsApp.controller('calendarController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        $('#external-events .fc-event').each(function() {
    
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true, // maintain when user navigates (see docs on the renderEvent method)
                color: ($(this).attr('data-color')) ? $(this).attr('data-color') : ''
            });
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        });
    
        var date = new Date();
        var currentYear = date.getFullYear();
        var currentMonth = date.getMonth() + 1;
            currentMonth = (currentMonth < 10) ? '0' + currentMonth : currentMonth;
    
        $('#calendar').fullCalendar({
            header: {
                left: 'month,agendaWeek,agendaDay',
                center: 'title',
                right: 'prev,today,next '
            },
            droppable: true, // this allows things to be dropped onto the calendar
            drop: function() {
                $(this).remove();
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end) {
                var title = prompt('Event Title:');
                var eventData;
                if (title) {
                    eventData = {
                        title: title,
                        start: start,
                        end: end
                    };
                    $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                }
                $('#calendar').fullCalendar('unselect');
            },
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: [{
                title: 'All Day Event',
                start: currentYear + '-'+ currentMonth +'-01',
                color: '#00acac'
            }, {
                title: 'Long Event',
                start: currentYear + '-'+ currentMonth +'-07',
                end: currentYear + '-'+ currentMonth +'-10'
            }, {
                id: 999,
                title: 'Repeating Event',
                start: currentYear + '-'+ currentMonth +'-09T16:00:00',
                color: '#00acac'
            }, {
                id: 999,
                title: 'Repeating Event',
                start: currentYear + '-'+ currentMonth +'-16T16:00:00'
            }, {
                title: 'Conference',
                start: currentYear + '-'+ currentMonth +'-11',
                end: currentYear + '-'+ currentMonth +'-13'
            }, {
                title: 'Meeting',
                start: currentYear + '-'+ currentMonth +'-12T10:30:00',
                end: currentYear + '-'+ currentMonth +'-12T12:30:00',
                color: '#00acac'
            }, {
                title: 'Lunch',
                start: currentYear + '-'+ currentMonth +'-12T12:00:00',
                color: '#348fe2'
            }, {
                title: 'Meeting',
                start: currentYear + '-'+ currentMonth +'-12T14:30:00'
            }, {
                title: 'Happy Hour',
                start: currentYear + '-'+ currentMonth +'-12T17:30:00'
            }, {
                title: 'Dinner',
                start: currentYear + '-'+ currentMonth +'-12T20:00:00'
            }, {
                title: 'Birthday Party',
                start: currentYear + '-'+ currentMonth +'-13T07:00:00'
            }, {
                title: 'Click for Google',
                url: 'http://google.com/',
                start: currentYear + '-'+ currentMonth +'-28',
                color: '#ff5b57'
            }]

        });
    });
});



/* -------------------------------
   37.0 CONTROLLER - Vector Map
------------------------------- */
omsApp.controller('mapVectorController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageContentFullWidth = true;
    $rootScope.setting.layout.pageContentInverseMode = true;
    
    angular.element(document).ready(function () {
        var wHeight = $(window).height();
        $('#world-map').css('height', wHeight);
        $('#world-map').vectorMap({
            map: 'world_mill_en',
            scaleColors: ['#e74c3c', '#0071a4'],
            normalizeFunction: 'polynomial',
            hoverOpacity: 0.5,
            hoverColor: false,
            markerStyle: {
                initial: {
                    fill: '#4cabc7',
                    stroke: 'transparent',
                    r: 3
                }
            },
            regionStyle: {
                initial: {
                    fill: 'rgb(97,109,125)',
                    "fill-opacity": 1,
                    stroke: 'none',
                    "stroke-width": 0.4,
                    "stroke-opacity": 1
                },
                hover: { "fill-opacity": 0.8 },
                selected: { fill: 'yellow' }
            },
            focusOn: { x: 0.5, y: 0.5, scale: 2 },
            backgroundColor: '#242a30',
            markers: [
                {latLng: [41.90, 12.45], name: 'Vatican City'},
                {latLng: [43.73, 7.41], name: 'Monaco'},
                {latLng: [-0.52, 166.93], name: 'Nauru'},
                {latLng: [-8.51, 179.21], name: 'Tuvalu'},
                {latLng: [43.93, 12.46], name: 'San Marino'},
                {latLng: [47.14, 9.52], name: 'Liechtenstein'},
                {latLng: [7.11, 171.06], name: 'Marshall Islands'},
                {latLng: [17.3, -62.73], name: 'Saint Kitts and Nevis'},
                {latLng: [3.2, 73.22], name: 'Maldives'},
                {latLng: [35.88, 14.5], name: 'Malta'},
                {latLng: [12.05, -61.75], name: 'Grenada'},
                {latLng: [13.16, -61.23], name: 'Saint Vincent and the Grenadines'},
                {latLng: [13.16, -59.55], name: 'Barbados'},
                {latLng: [17.11, -61.85], name: 'Antigua and Barbuda'},
                {latLng: [-4.61, 55.45], name: 'Seychelles'},
                {latLng: [7.35, 134.46], name: 'Palau'},
                {latLng: [42.5, 1.51], name: 'Andorra'},
                {latLng: [14.01, -60.98], name: 'Saint Lucia'},
                {latLng: [6.91, 158.18], name: 'Federated States of Micronesia'},
                {latLng: [1.3, 103.8], name: 'Singapore'},
                {latLng: [1.46, 173.03], name: 'Kiribati'},
                {latLng: [-21.13, -175.2], name: 'Tonga'},
                {latLng: [15.3, -61.38], name: 'Dominica'},
                {latLng: [-20.2, 57.5], name: 'Mauritius'},
                {latLng: [26.02, 50.55], name: 'Bahrain'},
                {latLng: [0.33, 6.73], name: 'São Tomé and Príncipe'}
            ]
        });
    });
});



/* -------------------------------
   38.0 CONTROLLER - Google Map
------------------------------- */
function handleGoogleMapLoaded() {
    $(window).trigger('googleMapLoaded');
}
omsApp.controller('mapGoogleController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageContentFullWidth = true;
    
    angular.element(document).ready(function () {
        var mapDefault;
    
        function initialize() {
            var mapOptions = {
                zoom: 6,
                center: new google.maps.LatLng(-33.397, 145.644),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
            };
            mapDefault = new google.maps.Map(document.getElementById('google-map-default'), mapOptions);
        }
    
        $(window).unbind('googleMapLoaded');
        $(window).bind('googleMapLoaded', initialize);
        $.getScript("http://maps.google.com/maps/api/js?sensor=false&callback=handleGoogleMapLoaded");
    
        $(window).resize(function() {
            google.maps.event.trigger(mapDefault, "resize");
        });
    
        var defaultMapStyles = [];
        var flatMapStyles = [{"stylers":[{"visibility":"off"}]},{"featureType":"road","stylers":[{"visibility":"on"},{"color":"#ffffff"}]},{"featureType":"road.arterial","stylers":[{"visibility":"on"},{"color":"#fee379"}]},{"featureType":"road.highway","stylers":[{"visibility":"on"},{"color":"#fee379"}]},{"featureType":"landscape","stylers":[{"visibility":"on"},{"color":"#f3f4f4"}]},{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#7fc8ed"}]},{},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#83cead"}]},{"elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"weight":0.9},{"visibility":"off"}]}]; 
        var turquoiseWaterStyles = [{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#e0efef"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#1900ff"},{"color":"#c0e8e8"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill"},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"color":"#7dcdcd"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":700}]}];
        var icyBlueStyles = [{"stylers":[{"hue":"#2c3e50"},{"saturation":250}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":50},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]}];
        var oldDryMudStyles = [{"featureType":"landscape","stylers":[{"hue":"#FFAD00"},{"saturation":50.2},{"lightness":-34.8},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#FFAD00"},{"saturation":-19.8},{"lightness":-1.8},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#FFAD00"},{"saturation":72.4},{"lightness":-32.6},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#FFAD00"},{"saturation":74.4},{"lightness":-18},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#00FFA6"},{"saturation":-63.2},{"lightness":38},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#FFC300"},{"saturation":54.2},{"lightness":-14.4},{"gamma":1}]}];
        var cobaltStyles  = [{"featureType":"all","elementType":"all","stylers":[{"invert_lightness":true},{"saturation":10},{"lightness":10},{"gamma":0.8},{"hue":"#293036"}]},{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#293036"}]}];
        var darkRedStyles   = [{"featureType":"all","elementType":"all","stylers":[{"invert_lightness":true},{"saturation":10},{"lightness":10},{"gamma":0.8},{"hue":"#000000"}]},{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#293036"}]}];
    
        $('[data-map-theme]').click(function() {
            var targetTheme = $(this).attr('data-map-theme');
            var targetLi = $(this).closest('li');
            var targetText = $(this).text();
            var inverseContentMode = false;
            $('#map-theme-selection li').not(targetLi).removeClass('active');
            $('#map-theme-text').text(targetText);
            $(targetLi).addClass('active');
            switch(targetTheme) {
                case 'flat':
                    mapDefault.setOptions({styles: flatMapStyles});
                    break;
                case 'turquoise-water':
                    mapDefault.setOptions({styles: turquoiseWaterStyles});
                    break;
                case 'icy-blue':
                    mapDefault.setOptions({styles: icyBlueStyles});
                    break;
                case 'cobalt':
                    mapDefault.setOptions({styles: cobaltStyles});
                    inverseContentMode = true;
                    break;
                case 'old-dry-mud':
                    mapDefault.setOptions({styles: oldDryMudStyles});
                    break;
                case 'dark-red':
                    mapDefault.setOptions({styles: darkRedStyles});
                    inverseContentMode = true;
                    break;
                default:
                    mapDefault.setOptions({styles: defaultMapStyles});
                    break;
            }

            if (inverseContentMode === true) {
                $('#content').addClass('content-inverse-mode');
            } else {
                $('#content').removeClass('content-inverse-mode');
            }
        });
    });
});



/* -------------------------------
   39.0 CONTROLLER - Gallery V1
------------------------------- */
omsApp.controller('galleryController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {

        function calculateDivider() {
            var dividerValue = 4;
            if ($(this).width() <= 480) {
                dividerValue = 1;
            } else if ($(this).width() <= 767) {
                dividerValue = 2;
            } else if ($(this).width() <= 980) {
                dividerValue = 3;
            }
            return dividerValue;
        }
    
        var container = $('#gallery');
        var dividerValue = calculateDivider();
        var containerWidth = $(container).width() - 20;
        var columnWidth = containerWidth / dividerValue;
        $(container).isotope({
            resizable: true,
            masonry: {
                columnWidth: columnWidth
            }
        });
    
        $(window).smartresize(function() {
            var dividerValue = calculateDivider();
            var containerWidth = $(container).width() - 20;
            var columnWidth = containerWidth / dividerValue;
            $(container).isotope({
                masonry: { 
                    columnWidth: columnWidth 
                }
            });
        });
    
        var $optionSets = $('#options .gallery-option-set'),
        $optionLinks = $optionSets.find('a');
    
        $optionLinks.click( function(){
            var $this = $(this);
            if ($this.hasClass('active')) {
                return false;
            }
            var $optionSet = $this.parents('.gallery-option-set');
            $optionSet.find('.active').removeClass('active');
            $this.addClass('active');
    
            var options = {};
            var key = $optionSet.attr('data-option-key');
            var value = $this.attr('data-option-value');
                value = value === 'false' ? false : value;
                options[ key ] = value;
            $(container).isotope( options );
            return false;
        });
    });
});



/* -------------------------------
   40.0 CONTROLLER - Gallery V2
------------------------------- */
omsApp.controller('galleryV2Controller', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
	    $('.superbox').SuperBox();
	});
});



/* -------------------------------
   41.0 CONTROLLER - Page with Footer
------------------------------- */
omsApp.controller('pageWithFooterController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageFooter = true;
});



/* -------------------------------
   42.0 CONTROLLER - Page without Sidebar
------------------------------- */
omsApp.controller('pageWithoutSidebarController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageWithoutSidebar = true;
});



/* -------------------------------
   43.0 CONTROLLER - Page with Right Sidebar
------------------------------- */
omsApp.controller('pageWithRightSidebarController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageRightSidebar = true;
});



/* -------------------------------
   44.0 CONTROLLER - Page with Minified Sidebar
------------------------------- */
omsApp.controller('pageWithMinifiedSidebarController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageSidebarMinified = true;
});



/* -------------------------------
   45.0 CONTROLLER - Page with Two Sidebar
------------------------------- */
omsApp.controller('pageWithTwoSidebarController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageTwoSidebar = true;
});



/* -------------------------------
   46.0 CONTROLLER - Full Height Content
------------------------------- */
omsApp.controller('pageFullHeightContentController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageContentFullHeight = true;
    $rootScope.setting.layout.pageContentFullWidth = true;
});



/* -------------------------------
   47.0 CONTROLLER - Page with Wide Sidebar
------------------------------- */
omsApp.controller('pageWithWideSidebarController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageWideSidebar = true;
});



/* -------------------------------
   48.0 CONTROLLER - Page with Light Sidebar
------------------------------- */
omsApp.controller('pageWithLightSidebarController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageLightSidebar = true;
});


/* -------------------------------
   49.0 CONTROLLER - Page with Mega Menu
------------------------------- */
omsApp.controller('pageWithMegaMenuController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageMegaMenu = true;
});



/* -------------------------------
   50.0 CONTROLLER - Page with Top Menu
------------------------------- */
omsApp.controller('pageWithTopMenuController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageTopMenu = true;
    $rootScope.setting.layout.pageWithoutSidebar = true;
});



/* -------------------------------
   51.0 CONTROLLER - Page with Boxed Layout
------------------------------- */
omsApp.controller('pageWithBoxedLayoutController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageBoxedLayout = true;
});



/* -------------------------------
   52.0 CONTROLLER - Page with Mixed Menu
------------------------------- */
omsApp.controller('pageWithMixedMenuController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageTopMenu = true;
});



/* -------------------------------
   53.0 CONTROLLER - Page Boxed Layout with Mixed Menu
------------------------------- */
omsApp.controller('pageBoxedLayoutWithMixedMenuController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageBoxedLayout = true;
    $rootScope.setting.layout.pageTopMenu = true;
});



/* -------------------------------
   54.0 CONTROLLER - Page with Transparent Sidebar
------------------------------- */
omsApp.controller('pageWithTransparentSidebarController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageSidebarTransparent = true;
});



/* -------------------------------
   55.0 CONTROLLER - Timeline
------------------------------- */
omsApp.controller('extraTimelineController', function($scope, $rootScope, $state) {
    angular.element(document).ready(function () {
        var mapDefault;
    
        function initialize() {
            var mapOptions = {
                zoom: 6,
                center: new google.maps.LatLng(-33.397, 145.644),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
            };
            mapDefault = new google.maps.Map(document.getElementById('google-map'), mapOptions);
        }
    
        $(window).unbind('googleMapLoaded');
        $(window).bind('googleMapLoaded', initialize);
        $.getScript("http://maps.google.com/maps/api/js?sensor=false&callback=handleGoogleMapLoaded");
    
        $(window).resize(function() {
            google.maps.event.trigger(mapDefault, "resize");
        });
    });
});



/* -------------------------------
   56.0 CONTROLLER - Coming Soon
------------------------------- */
omsApp.controller('comingSoonController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageWithoutHeader = true;
    $rootScope.setting.layout.pageBgWhite = true;
    $rootScope.setting.layout.paceTop = true;
    
    angular.element(document).ready(function () {
        var newYear = new Date();
        newYear = new Date(newYear.getFullYear() + 1, 1 - 1, 1);
        $('#timer').countdown({until: newYear});
    });
});



/* -------------------------------
   57.0 CONTROLLER - 404 Error
------------------------------- */
omsApp.controller('errorController', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageWithoutHeader = true;
    $rootScope.setting.layout.paceTop = true;
});



/* -------------------------------
   58.0 CONTROLLER - Login V1
------------------------------- */
// omsApp.controller('loginV1Controller', function($scope, $rootScope, $state) {
//     $rootScope.setting.layout.pageWithoutHeader = true;
//     $rootScope.setting.layout.paceTop = true;
    
//     $scope.submitForm = function(form) {
//         $state.go('app.dashboard.v2');
//     };
// });



/* -------------------------------
   59.0 CONTROLLER - Login V2
------------------------------- */
// omsApp.controller('loginV2Controller', function($scope, $rootScope, $state) {
//     $rootScope.setting.layout.pageWithoutHeader = true;
//     $rootScope.setting.layout.paceTop = true;
    
//     $scope.submitForm = function(form) {
//         $state.go('app.dashboard.v2');
//     };
    
//     angular.element(document).ready(function () {
//         $('[data-click="change-bg"]').click(function() {
//             var targetImage = '[data-id="login-cover-image"]';
//             var targetImageSrc = $(this).find('img').attr('src');
//             var targetImageHtml = '<img src="'+ targetImageSrc +'" data-id="login-cover-image" />';
        
//             $('.login-cover-image').prepend(targetImageHtml);
//             $(targetImage).not('[src="'+ targetImageSrc +'"]').fadeOut('slow', function() {
//                 $(this).remove();
//             });
//             $('[data-click="change-bg"]').closest('li').removeClass('active');
//             $(this).closest('li').addClass('active');	
//         });
//     });
// });



/* -------------------------------
   60.0 CONTROLLER - Login V3
------------------------------- */
// omsApp.controller('loginV3Controller', function($scope, $rootScope, $state) {
//     $rootScope.setting.layout.pageWithoutHeader = true;
//     $rootScope.setting.layout.paceTop = true;
//     $rootScope.setting.layout.pageBgWhite = true;
    
//     $scope.submitForm = function(form) {
//         $state.go('app.dashboard.v2');
//     };
// });



/* -------------------------------
   61.0 CONTROLLER - Register V3
------------------------------- */
omsApp.controller('registerV3Controller', function($scope, $rootScope, $state) {
    $rootScope.setting.layout.pageWithoutHeader = true;
    $rootScope.setting.layout.paceTop = true;
    $rootScope.setting.layout.pageBgWhite = true;
    
    $scope.submitForm = function(form) {
        $state.go('app.dashboard.v2');
    };
});
/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 2.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v2.0/admin/angularjs/
*/

/* Prevent Global Link Click
------------------------------------------------ */

omsApp.directive('a', function() {
    return {
        restrict: 'E',
        link: function(scope, elem, attrs) {
            if (attrs.ngClick || attrs.href === '' || attrs.href === '#') {
                elem.on('click', function(e) {
                    e.preventDefault();
                });
            }
        }
    };
});

/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 2.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v2.0/admin/angularjs/
    ----------------------------
        APPS CONTENT TABLE
    ----------------------------
    
    <!-- ======== GLOBAL SCRIPT SETTING ======== -->
    01. Handle Scrollbar
    
    02. Handle Sidebar - Menu
    03. Handle Sidebar - Mobile View Toggle
    04. Handle Sidebar - Minify / Expand
    05. Handle Page Load - Fade in
    06. Handle Panel - Remove / Reload / Collapse / Expand
    07. Handle Panel - Draggable
    08. Handle Tooltip & Popover Activation
    09. Handle Scroll to Top Button Activation
    
    <!-- ======== Added in V1.2 ======== -->
    10. Handle Theme & Page Structure Configuration
    11. Handle Theme Panel Expand
    12. Handle After Page Load Add Class Function - added in V1.2
    
    <!-- ======== Added in V1.5 ======== -->
    13. Handle Save Panel Position Function - added in V1.5
    14. Handle Draggable Panel Local Storage Function - added in V1.5
    15. Handle Reset Local Storage - added in V1.5
    
    <!-- ======== Added in V1.6 ======== -->
    16. Handle IE Full Height Page Compatibility - added in V1.6
    17. Handle Unlimited Nav Tabs - added in V1.6
    
    <!-- ======== Added in V1.7 ======== -->
    18. Handle Mobile Sidebar Scrolling Feature - added in V1.7
    
    <!-- ======== Added in V1.9 ======== -->
    19. Handle Top Menu - Unlimited Top Menu Render - added in V1.9
    20. Handle Top Menu - Sub Menu Toggle - added in V1.9
    21. Handle Top Menu - Mobile Sub Menu Toggle - added in V1.9
    22. Handle Top Menu - Mobile Top Menu Toggle - added in V1.9
    23. Handle Clear Sidebar Selection & Hide Mobile Menu - added in V1.9
	
    <!-- ======== APPLICATION SETTING ======== -->
    Application Controller
*/



/* 01. Handle Scrollbar
------------------------------------------------ */
var handleSlimScroll = function() {
    "use strict";
    $('[data-scrollbar=true]').each( function() {
        generateSlimScroll($(this));
    });
};
var generateSlimScroll = function(element) {
    if ($(element).attr('data-init')) {
        return;
    }
    var dataHeight = $(element).attr('data-height');
        dataHeight = (!dataHeight) ? $(element).height() : dataHeight;
    
    var scrollBarOption = {
        height: dataHeight, 
        alwaysVisible: true
    };
    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $(element).css('height', dataHeight);
        $(element).css('overflow-x','scroll');
    } else {
        $(element).slimScroll(scrollBarOption);
    }
    $(element).attr('data-init', true);
};


/* 02. Handle Sidebar - Menu
------------------------------------------------ */
var handleSidebarMenu = function() {
    "use strict";
    $('.sidebar .nav > .has-sub > a').click(function() {
        var target = $(this).next('.sub-menu');
        var otherMenu = '.sidebar .nav > li.has-sub > .sub-menu';
    
        if ($('.page-sidebar-minified').length === 0) {
            $(otherMenu).not(target).slideUp(250, function() {
                $(this).closest('li').removeClass('expand');
            });
            $(target).slideToggle(250, function() {
                var targetLi = $(this).closest('li');
                if ($(targetLi).hasClass('expand')) {
                    $(targetLi).removeClass('expand');
                } else {
                    $(targetLi).addClass('expand');
                }
            });
        }
    });
    $('.sidebar .nav > .has-sub .sub-menu li.has-sub > a').click(function() {
        if ($('.page-sidebar-minified').length === 0) {
            var target = $(this).next('.sub-menu');
            $(target).slideToggle(250);
        }
    });
};


/* 03. Handle Sidebar - Mobile View Toggle
------------------------------------------------ */
var handleMobileSidebarToggle = function() {
    var sidebarProgress = false;
    $('.sidebar').bind('click touchstart', function(e) {
        if ($(e.target).closest('.sidebar').length !== 0) {
            sidebarProgress = true;
        } else {
            sidebarProgress = false;
            e.stopPropagation();
        }
    });
    
    $(document).bind('click touchstart', function(e) {
        if ($(e.target).closest('.sidebar').length === 0) {
            sidebarProgress = false;
        }
        if (!e.isPropagationStopped() && sidebarProgress !== true) {
            if ($('#page-container').hasClass('page-sidebar-toggled')) {
                sidebarProgress = true;
                $('#page-container').removeClass('page-sidebar-toggled');
            }
            if ($(window).width() <= 767) {
                if ($('#page-container').hasClass('page-right-sidebar-toggled')) {
                    sidebarProgress = true;
                    $('#page-container').removeClass('page-right-sidebar-toggled');
                }
            }
        }
    });
    
    $('[data-click=right-sidebar-toggled]').click(function(e) {
        e.stopPropagation();
        var targetContainer = '#page-container';
        var targetClass = 'page-right-sidebar-collapsed';
            targetClass = ($(window).width() < 979) ? 'page-right-sidebar-toggled' : targetClass;
        if ($(targetContainer).hasClass(targetClass)) {
            $(targetContainer).removeClass(targetClass);
        } else if (sidebarProgress !== true) {
            $(targetContainer).addClass(targetClass);
        } else {
            sidebarProgress = false;
        }
        if ($(window).width() < 480) {
            $('#page-container').removeClass('page-sidebar-toggled');
        }
        $(window).trigger('resize');
    });
    
    $('[data-click=sidebar-toggled]').click(function(e) {
        e.stopPropagation();
        var sidebarClass = 'page-sidebar-toggled';
        var targetContainer = '#page-container';

        if ($(targetContainer).hasClass(sidebarClass)) {
            $(targetContainer).removeClass(sidebarClass);
        } else if (sidebarProgress !== true) {
            $(targetContainer).addClass(sidebarClass);
        } else {
            sidebarProgress = false;
        }
        if ($(window).width() < 480) {
            $('#page-container').removeClass('page-right-sidebar-toggled');
        }
    });
};


/* 04. Handle Sidebar - Minify / Expand
------------------------------------------------ */
var handleSidebarMinify = function() {
    $('[data-click=sidebar-minify]').click(function(e) {
        e.preventDefault();
        var sidebarClass = 'page-sidebar-minified';
        var targetContainer = '#page-container';
        $('#sidebar [data-scrollbar="true"]').css('margin-top','0');
        $('#sidebar [data-scrollbar="true"]').removeAttr('data-init');
        $('#sidebar [data-scrollbar=true]').stop();
        if ($(targetContainer).hasClass(sidebarClass)) {
            $(targetContainer).removeClass(sidebarClass);
            if ($(targetContainer).hasClass('page-sidebar-fixed')) {
                if ($('#sidebar .slimScrollDiv').length !== 0) {
                    $('#sidebar [data-scrollbar="true"]').slimScroll({destroy: true});
                    $('#sidebar [data-scrollbar="true"]').removeAttr('style');
                }
                generateSlimScroll($('#sidebar [data-scrollbar="true"]'));
                $('#sidebar [data-scrollbar=true]').trigger('mouseover');
            } else if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                if ($('#sidebar .slimScrollDiv').length !== 0) {
                    $('#sidebar [data-scrollbar="true"]').slimScroll({destroy: true});
                    $('#sidebar [data-scrollbar="true"]').removeAttr('style');
                }
                generateSlimScroll($('#sidebar [data-scrollbar="true"]'));
            }
        } else {
            $(targetContainer).addClass(sidebarClass);
    
            if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                if ($(targetContainer).hasClass('page-sidebar-fixed')) {
                    $('#sidebar [data-scrollbar="true"]').slimScroll({destroy: true});
                    $('#sidebar [data-scrollbar="true"]').removeAttr('style');
                }
                $('#sidebar [data-scrollbar=true]').trigger('mouseover');
            } else {
                $('#sidebar [data-scrollbar="true"]').css('margin-top','0');
                $('#sidebar [data-scrollbar="true"]').css('overflow', 'visible');
            }
        }
        $(window).trigger('resize');
    });
};


/* 05. Handle Page Load - Fade in
------------------------------------------------ */
var handlePageContentView = function() {
    "use strict";
    $.when($('#page-loader').addClass('hide')).done(function() {
        $('#page-container').addClass('in');
    });
};


/* 06. Handle Panel - Remove / Reload / Collapse / Expand
------------------------------------------------ */
var panelActionRunning = false;
var handlePanelAction = function() {
    "use strict";
    
    if (panelActionRunning) {
        return false;
    }
    panelActionRunning = true;
    
    // remove
    $(document).on('hover', '[data-click=panel-remove]', function(e) {
        $(this).tooltip({
            title: 'Remove',
            placement: 'bottom',
            trigger: 'hover',
            container: 'body'
        });
        $(this).tooltip('show');
    });
    $(document).on('click', '[data-click=panel-remove]', function(e) {
        e.preventDefault();
        $(this).tooltip('destroy');
        $(this).closest('.panel').remove();
    });
    
    // collapse
    $(document).on('hover', '[data-click=panel-collapse]', function(e) {
        $(this).tooltip({
            title: 'Collapse / Expand',
            placement: 'bottom',
            trigger: 'hover',
            container: 'body'
        });
        $(this).tooltip('show');
    });
    $(document).on('click', '[data-click=panel-collapse]', function(e) {
        e.preventDefault();
        $(this).closest('.panel').find('.panel-body').slideToggle();
    });
    
    // reload
    $(document).on('hover', '[data-click=panel-reload]', function(e) {
        $(this).tooltip({
            title: 'Reload',
            placement: 'bottom',
            trigger: 'hover',
            container: 'body'
        });
        $(this).tooltip('show');
    });
    $(document).on('click', '[data-click=panel-reload]', function(e) {
        e.preventDefault();
        var target = $(this).closest('.panel');
        if (!$(target).hasClass('panel-loading')) {
            var targetBody = $(target).find('.panel-body');
            var spinnerHtml = '<div class="panel-loader"><span class="spinner-small"></span></div>';
            $(target).addClass('panel-loading');
            $(targetBody).prepend(spinnerHtml);
            setTimeout(function() {
                $(target).removeClass('panel-loading');
                $(target).find('.panel-loader').remove();
            }, 2000);
        }
    });
    
    // expand
    $(document).on('hover', '[data-click=panel-expand]', function(e) {
        $(this).tooltip({
            title: 'Expand / Compress',
            placement: 'bottom',
            trigger: 'hover',
            container: 'body'
        });
        $(this).tooltip('show');
    });
    $(document).on('click', '[data-click=panel-expand]', function(e) {
        e.preventDefault();
        var target = $(this).closest('.panel');
        var targetBody = $(target).find('.panel-body');
        var targetTop = 40;
        if ($(targetBody).length !== 0) {
            var targetOffsetTop = $(target).offset().top;
            var targetBodyOffsetTop = $(targetBody).offset().top;
            targetTop = targetBodyOffsetTop - targetOffsetTop;
        }
        
        if ($('body').hasClass('panel-expand') && $(target).hasClass('panel-expand')) {
            $('body, .panel').removeClass('panel-expand');
            $('.panel').removeAttr('style');
            $(targetBody).removeAttr('style');
        } else {
            $('body').addClass('panel-expand');
            $(this).closest('.panel').addClass('panel-expand');
            
            if ($(targetBody).length !== 0 && targetTop != 40) {
                var finalHeight = 40;
                $(target).find(' > *').each(function() {
                    var targetClass = $(this).attr('class');
                    
                    if (targetClass != 'panel-heading' && targetClass != 'panel-body') {
                        finalHeight += $(this).height() + 30;
                    }
                });
                if (finalHeight != 40) {
                    $(targetBody).css('top', finalHeight + 'px');
                }
            }
        }
        $(window).trigger('resize');
    });
};


/* 07. Handle Panel - Draggable
------------------------------------------------ */
var handleDraggablePanel = function() {
    "use strict";
    var target = $('.panel').parent('[class*=col]');
    var targetHandle = '.panel-heading';
    var connectedTarget = '.row > [class*=col]';
    
    $(target).sortable({
        handle: targetHandle,
        connectWith: connectedTarget,
        stop: function(event, ui) {
            ui.item.find('.panel-title').append('<i class="fa fa-refresh fa-spin m-l-5" data-id="title-spinner"></i>');
            handleSavePanelPosition(ui.item);
        }
    });
};


/* 08. Handle Tooltip & Popover Activation
------------------------------------------------ */
var handelTooltipPopoverActivation = function() {
    "use strict";
    $('[data-toggle=tooltip]').tooltip();
    $('[data-toggle=popover]').popover();
};


/* 09. Handle Scroll to Top Button Activation
------------------------------------------------ */
var handleScrollToTopButton = function() {
    "use strict";
    $(document).scroll( function() {
        var totalScroll = $(document).scrollTop();

        if (totalScroll >= 200) {
            $('[data-click=scroll-top]').addClass('in');
        } else {
            $('[data-click=scroll-top]').removeClass('in');
        }
    });

    $('[data-click=scroll-top]').click(function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $("body").offset().top
        }, 500);
    });
};


/* 10. Handle Theme & Page Structure Configuration - added in V1.2
------------------------------------------------ */
var handleThemePageStructureControl = function() {
    // COOKIE - Theme File Setting
    if ($.cookie && $.cookie('theme')) {
        if ($('.theme-list').length !== 0) {
            $('.theme-list [data-theme]').closest('li').removeClass('active');
            $('.theme-list [data-theme="'+ $.cookie('theme') +'"]').closest('li').addClass('active');
        }
        var cssFileSrc = 'assets/css/theme/' + $.cookie('theme') + '.css';
        $('#theme').attr('href', cssFileSrc);
    }
    
    // COOKIE - Sidebar Styling Setting
    if ($.cookie && $.cookie('sidebar-styling')) {
        if ($('.sidebar').length !== 0 && $.cookie('sidebar-styling') == 'grid') {
            $('.sidebar').addClass('sidebar-grid');
            $('[name=sidebar-styling] option[value="2"]').prop('selected', true);
        }
    }
    
    // COOKIE - Header Setting
    if ($.cookie && $.cookie('header-styling')) {
        if ($('.header').length !== 0 && $.cookie('header-styling') == 'navbar-inverse') {
            $('.header').addClass('navbar-inverse');
            $('[name=header-styling] option[value="2"]').prop('selected', true);
        }
    }
    
    // COOKIE - Gradient Setting
    if ($.cookie && $.cookie('content-gradient')) {
        if ($('#page-container').length !== 0 && $.cookie('content-gradient') == 'enabled') {
            $('#page-container').addClass('gradient-enabled');
            $('[name=content-gradient] option[value="2"]').prop('selected', true);
        }
    }
    
    // COOKIE - Content Styling Setting
    if ($.cookie && $.cookie('content-styling')) {
        if ($('body').length !== 0 && $.cookie('content-styling') == 'black') {
            $('body').addClass('flat-black');
            $('[name=content-styling] option[value="2"]').prop('selected', true);
        }
    }
    
    // THEME - theme selection
    $('.theme-list [data-theme]').click(function() {
        var cssFileSrc = 'assets/css/theme/' + $(this).attr('data-theme') + '.css';
        $('#theme').attr('href', cssFileSrc);
        $('.theme-list [data-theme]').not(this).closest('li').removeClass('active');
        $(this).closest('li').addClass('active');
        $.cookie('theme', $(this).attr('data-theme'));
    });
    
    // HEADER - inverse or default
    $('.theme-panel [name=header-styling]').on('change', function() {
        var targetClassAdd = ($(this).val() == 1) ? 'navbar-default' : 'navbar-inverse';
        var targetClassRemove = ($(this).val() == 1) ? 'navbar-inverse' : 'navbar-default';
        $('#header').removeClass(targetClassRemove).addClass(targetClassAdd);
        $.cookie('header-styling',targetClassAdd);
    });
    
    // SIDEBAR - grid or default
    $('.theme-panel [name=sidebar-styling]').on('change', function() {
        if ($(this).val() == 2) {
            $('#sidebar').addClass('sidebar-grid');
            $.cookie('sidebar-styling', 'grid');
        } else {
            $('#sidebar').removeClass('sidebar-grid');
            $.cookie('sidebar-styling', 'default');
        }
    });
    
    // CONTENT - gradient enabled or disabled
    $('.theme-panel [name=content-gradient]').on('change', function() {
        if ($(this).val() == 2) {
            $('#page-container').addClass('gradient-enabled');
            $.cookie('content-gradient', 'enabled');
        } else {
            $('#page-container').removeClass('gradient-enabled');
            $.cookie('content-gradient', 'disabled');
        }
    });
    
    // CONTENT - default or black
    $(document).on('change', '.theme-panel [name=content-styling]', function() {
        if ($(this).val() == 2) {
            $('body').addClass('flat-black');
            $.cookie('content-styling', 'black');
        } else {
            $('body').removeClass('flat-black');
            $.cookie('content-styling', 'default');
        }
    });
    
    // SIDEBAR - fixed or default
    $(document).on('change', '.theme-panel [name=sidebar-fixed]', function() {
        if ($(this).val() == 1) {
            if ($('.theme-panel [name=header-fixed]').val() == 2) {
                alert('Default Header with Fixed Sidebar option is not supported. Proceed with Fixed Header with Fixed Sidebar.');
                $('.theme-panel [name=header-fixed] option[value="1"]').prop('selected', true);
                $('#header').addClass('navbar-fixed-top');
                $('#page-container').addClass('page-header-fixed');
            }
            $('#page-container').addClass('page-sidebar-fixed');
            if (!$('#page-container').hasClass('page-sidebar-minified')) {
                generateSlimScroll($('.sidebar [data-scrollbar="true"]'));
            }
        } else {
            $('#page-container').removeClass('page-sidebar-fixed');
            if ($('.sidebar .slimScrollDiv').length !== 0) {
                if ($(window).width() <= 979) {
                    $('.sidebar').each(function() {
                        if (!($('#page-container').hasClass('page-with-two-sidebar') && $(this).hasClass('sidebar-right'))) {
                            $(this).find('.slimScrollBar').remove();
                            $(this).find('.slimScrollRail').remove();
                            $(this).find('[data-scrollbar="true"]').removeAttr('style');
                            var targetElement = $(this).find('[data-scrollbar="true"]').parent();
                            var targetHtml = $(targetElement).html();
                            $(targetElement).replaceWith(targetHtml);
                        }
                    });
                } else if ($(window).width() > 979) {
                    $('.sidebar [data-scrollbar="true"]').slimScroll({destroy: true});
                    $('.sidebar [data-scrollbar="true"]').removeAttr('style');
                }
            }
            if ($('#page-container .sidebar-bg').length === 0) {
                $('#page-container').append('<div class="sidebar-bg"></div>');
            }
        }
    });
    
    // HEADER - fixed or default
    $(document).on('change', '.theme-panel [name=header-fixed]', function() {
        if ($(this).val() == 1) {
            $('#header').addClass('navbar-fixed-top');
            $('#page-container').addClass('page-header-fixed');
            $.cookie('header-fixed', true);
        } else {
            if ($('.theme-panel [name=sidebar-fixed]').val() == 1) {
                alert('Default Header with Fixed Sidebar option is not supported. Proceed with Default Header with Default Sidebar.');
                $('.theme-panel [name=sidebar-fixed] option[value="2"]').prop('selected', true);
                $('#page-container').removeClass('page-sidebar-fixed');
                if ($('#page-container .sidebar-bg').length === 0) {
                    $('#page-container').append('<div class="sidebar-bg"></div>');
                }
            }
            $('#header').removeClass('navbar-fixed-top');
            $('#page-container').removeClass('page-header-fixed');
            $.cookie('header-fixed', false);
        }
    });
};


/* 11. Handle Theme Panel Expand - added in V1.2
------------------------------------------------ */
var handleThemePanelExpand = function() {
    $(document).on('click', '[data-click="theme-panel-expand"]', function() {
        var targetContainer = '.theme-panel';
        var targetClass = 'active';
        if ($(targetContainer).hasClass(targetClass)) {
            $(targetContainer).removeClass(targetClass);
        } else {
            $(targetContainer).addClass(targetClass);
        }
    });
};


/* 12. Handle After Page Load Add Class Function - added in V1.2
------------------------------------------------ */
var handleAfterPageLoadAddClass = function() {
    if ($('[data-pageload-addclass]').length !== 0) {
        $(window).load(function() {
            $('[data-pageload-addclass]').each(function() {
                var targetClass = $(this).attr('data-pageload-addclass');
                $(this).addClass(targetClass);
            });
        });
    }
};


/* 13. Handle Save Panel Position Function - added in V1.5
------------------------------------------------ */
var handleSavePanelPosition = function(element) {
    "use strict";
    if ($('.ui-sortable').length !== 0) {
        var newValue = [];
        var index = 0;
        $.when($('.ui-sortable').each(function() {
            var panelSortableElement = $(this).find('[data-sortable-id]');
            if (panelSortableElement.length !== 0) {
                var columnValue = [];
                $(panelSortableElement).each(function() {
                    var targetSortId = $(this).attr('data-sortable-id');
                    columnValue.push({id: targetSortId});
                });
                newValue.push(columnValue);
            } else {
                newValue.push([]);
            }
            index++;
        })).done(function() {
            var targetPage = window.location.href;
                targetPage = targetPage.split('?');
                targetPage = targetPage[0];
            localStorage.setItem(targetPage, JSON.stringify(newValue));
            $(element).find('[data-id="title-spinner"]').delay(500).fadeOut(500, function() {
                $(this).remove();
            });
        });
    }
};


/* 14. Handle Draggable Panel Local Storage Function - added in V1.5
------------------------------------------------ */
var handleLocalStorage = function() {
    "use strict";
    if (typeof(Storage) !== 'undefined' && typeof(localStorage) !== 'undefined') {
        var targetPage = window.location.href;
            targetPage = targetPage.split('?');
            targetPage = targetPage[0];
        var panelPositionData = localStorage.getItem(targetPage);
        
        if (panelPositionData) {
            panelPositionData = JSON.parse(panelPositionData);
            var i = 0;
            $('.panel').parent('[class*="col-"]').each(function() {
                var storageData = panelPositionData[i]; 
                var targetColumn = $(this);
                if (storageData) {
                    $.each(storageData, function(index, data) {
                        var targetId = $('[data-sortable-id="'+ data.id +'"]').not('[data-init="true"]');
                        if ($(targetId).length !== 0) {
                            var targetHtml = $(targetId).clone();
                            $(targetId).remove();
                            $(targetColumn).append(targetHtml);
                            $('[data-sortable-id="'+ data.id +'"]').attr('data-init','true');
                        }
                    });
                }
                i++;
            });
        }
    } else {
        alert('Your browser is not supported with the local storage'); 
    }
};


/* 15. Handle Reset Local Storage - added in V1.5
------------------------------------------------ */
var handleResetLocalStorage = function() {
    "use strict";
    $(document).on('click', '[data-click=reset-local-storage]', function(e) {
        e.preventDefault();
        
        var targetModalHtml = ''+
        '<div class="modal fade" data-modal-id="reset-local-storage-confirmation">'+
        '    <div class="modal-dialog">'+
        '        <div class="modal-content">'+
        '            <div class="modal-header">'+
        '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
        '                <h4 class="modal-title"><i class="fa fa-refresh m-r-5"></i> Reset Local Storage Confirmation</h4>'+
        '            </div>'+
        '            <div class="modal-body">'+
        '                <div class="alert alert-info m-b-0">Would you like to RESET all your saved widgets and clear Local Storage?</div>'+
        '            </div>'+
        '            <div class="modal-footer">'+
        '                <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal"><i class="fa fa-close"></i> No</a>'+
        '                <a href="javascript:;" class="btn btn-sm btn-inverse" data-click="confirm-reset-local-storage"><i class="fa fa-check"></i> Yes</a>'+
        '            </div>'+
        '        </div>'+
        '    </div>'+
        '</div>';
        
        $('body').append(targetModalHtml);
        $('[data-modal-id="reset-local-storage-confirmation"]').modal('show');
    });
    $(document).on('hidden.bs.modal', '[data-modal-id="reset-local-storage-confirmation"]', function(e) {
        $('[data-modal-id="reset-local-storage-confirmation"]').remove();
    });
    $(document).on('click', '[data-click=confirm-reset-local-storage]', function(e) {
        e.preventDefault();
        var localStorageName = window.location.href;
            localStorageName = localStorageName.split('?');
            localStorageName = localStorageName[0];
        localStorage.removeItem(localStorageName);
        
        location.reload();
    });
};


/* 16. Handle IE Full Height Page Compatibility - added in V1.6
------------------------------------------------ */
var handleIEFullHeightContent = function() {
    var userAgent = window.navigator.userAgent;
    var msie = userAgent.indexOf("MSIE ");

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
        $('.vertical-box-row [data-scrollbar="true"][data-height="100%"]').each(function() {
            var targetRow = $(this).closest('.vertical-box-row');
            var targetHeight = $(targetRow).height();
            $(targetRow).find('.vertical-box-cell').height(targetHeight);
        });
    }
};


/* 17. Handle Unlimited Nav Tabs - added in V1.6
------------------------------------------------ */
var handleUnlimitedTabsRender = function() {
    
    // function handle tab overflow scroll width 
    function handleTabOverflowScrollWidth(obj, animationSpeed) {
        var marginLeft = parseInt($(obj).css('margin-left'));  
        var viewWidth = $(obj).width();
        var prevWidth = $(obj).find('li.active').width();
        var speed = (animationSpeed > -1) ? animationSpeed : 150;
        var fullWidth = 0;

        $(obj).find('li.active').prevAll().each(function() {
            prevWidth += $(this).width();
        });

        $(obj).find('li').each(function() {
            fullWidth += $(this).width();
        });

        if (prevWidth >= viewWidth) {
            var finalScrollWidth = prevWidth - viewWidth;
            if (fullWidth != prevWidth) {
                finalScrollWidth += 40;
            }
            $(obj).find('.nav.nav-tabs').animate({ marginLeft: '-' + finalScrollWidth + 'px'}, speed);
        }

        if (prevWidth != fullWidth && fullWidth >= viewWidth) {
            $(obj).addClass('overflow-right');
        } else {
            $(obj).removeClass('overflow-right');
        }

        if (prevWidth >= viewWidth && fullWidth >= viewWidth) {
            $(obj).addClass('overflow-left');
        } else {
            $(obj).removeClass('overflow-left');
        }
    }
    
    // function handle tab button action - next / prev
    function handleTabButtonAction(element, direction) {
        var obj = $(element).closest('.tab-overflow');
        var marginLeft = parseInt($(obj).find('.nav.nav-tabs').css('margin-left'));  
        var containerWidth = $(obj).width();
        var totalWidth = 0;
        var finalScrollWidth = 0;

        $(obj).find('li').each(function() {
            if (!$(this).hasClass('next-button') && !$(this).hasClass('prev-button')) {
                totalWidth += $(this).width();
            }
        });
    
        switch (direction) {
            case 'next':
                var widthLeft = totalWidth + marginLeft - containerWidth;
                if (widthLeft <= containerWidth) {
                    finalScrollWidth = widthLeft - marginLeft;
                    setTimeout(function() {
                        $(obj).removeClass('overflow-right');
                    }, 150);
                } else {
                    finalScrollWidth = containerWidth - marginLeft - 80;
                }

                if (finalScrollWidth != 0) {
                    $(obj).find('.nav.nav-tabs').animate({ marginLeft: '-' + finalScrollWidth + 'px'}, 150, function() {
                        $(obj).addClass('overflow-left');
                    });
                }
                break;
            case 'prev':
                var widthLeft = -marginLeft;
            
                if (widthLeft <= containerWidth) {
                    $(obj).removeClass('overflow-left');
                    finalScrollWidth = 0;
                } else {
                    finalScrollWidth = widthLeft - containerWidth + 80;
                }
                $(obj).find('.nav.nav-tabs').animate({ marginLeft: '-' + finalScrollWidth + 'px'}, 150, function() {
                    $(obj).addClass('overflow-right');
                });
                break;
        }
    }

    // handle page load active tab focus
    function handlePageLoadTabFocus() {
        $('.tab-overflow').each(function() {
            var targetWidth = $(this).width();
            var targetInnerWidth = 0;
            var targetTab = $(this);
            var scrollWidth = targetWidth;

            $(targetTab).find('li').each(function() {
                var targetLi = $(this);
                targetInnerWidth += $(targetLi).width();
    
                if ($(targetLi).hasClass('active') && targetInnerWidth > targetWidth) {
                    scrollWidth -= targetInnerWidth;
                }
            });

            handleTabOverflowScrollWidth(this, 0);
        });
    }
    
    // handle tab next button click action
    $('[data-click="next-tab"]').click(function(e) {
        e.preventDefault();
        handleTabButtonAction(this,'next');
    });
    
    // handle tab prev button click action
    $('[data-click="prev-tab"]').click(function(e) {
        e.preventDefault();
        handleTabButtonAction(this,'prev');

    });
    
    // handle unlimited tabs responsive setting
    $(window).resize(function() {
        $('.tab-overflow .nav.nav-tabs').removeAttr('style');
        handlePageLoadTabFocus();
    });
    
    handlePageLoadTabFocus();
};


/* 18. Handle Mobile Sidebar Scrolling Feature - added in V1.7
------------------------------------------------ */
var handleMobileSidebar = function() {
    "use strict";
    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        if ($('#page-container').hasClass('page-sidebar-minified')) {
            $('#sidebar [data-scrollbar="true"]').css('overflow', 'visible');
            $('.page-sidebar-minified #sidebar [data-scrollbar="true"]').slimScroll({destroy: true});
            $('.page-sidebar-minified #sidebar [data-scrollbar="true"]').removeAttr('style');
            $('.page-sidebar-minified #sidebar [data-scrollbar=true]').trigger('mouseover');
        }
    }

    var oriTouch = 0;
    $('.page-sidebar-minified .sidebar [data-scrollbar=true] a').bind('touchstart', function(e) {
        var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
        var touchVertical = touch.pageY;
        oriTouch = touchVertical - parseInt($(this).closest('[data-scrollbar=true]').css('margin-top'));
    });

    $('.page-sidebar-minified .sidebar [data-scrollbar=true] a').bind('touchmove',function(e){
        e.preventDefault();
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
            var touchVertical = touch.pageY;
            var elementTop = touchVertical - oriTouch;
            
            $(this).closest('[data-scrollbar=true]').css('margin-top', elementTop + 'px');
        }
    });

    $('.page-sidebar-minified .sidebar [data-scrollbar=true] a').bind('touchend', function(e) {
        var targetScrollBar = $(this).closest('[data-scrollbar=true]');
        var windowHeight = $(window).height();
        var sidebarTopPosition = parseInt($('#sidebar').css('padding-top'));
        var sidebarContainerHeight = $('#sidebar').height();
        oriTouch = $(targetScrollBar).css('margin-top');

        var sidebarHeight = sidebarTopPosition;
        $('.sidebar').not('.sidebar-right').find('.nav').each(function() {
            sidebarHeight += $(this).height();
        });
        var finalHeight = -parseInt(oriTouch) + $('.sidebar').height();
        if (finalHeight >= sidebarHeight && windowHeight <= sidebarHeight && sidebarContainerHeight <= sidebarHeight) {
            var finalMargin = windowHeight - sidebarHeight - 20;
            $(targetScrollBar).animate({marginTop: finalMargin + 'px'});
        } else if (parseInt(oriTouch) >= 0 || sidebarContainerHeight >= sidebarHeight) {
            $(targetScrollBar).animate({marginTop: '0px'});
        } else {
            finalMargin = oriTouch;
            $(targetScrollBar).animate({marginTop: finalMargin + 'px'});
        }
    });
};


/* 19. Handle Top Menu - Unlimited Top Menu Render - added in V1.9
------------------------------------------------ */
var handleUnlimitedTopMenuRender = function() {
    "use strict";
    // function handle menu button action - next / prev
    function handleMenuButtonAction(element, direction) {
        var obj = $(element).closest('.nav');
        var marginLeft = parseInt($(obj).css('margin-left'));  
        var containerWidth = $('.top-menu').width() - 88;
        var totalWidth = 0;
        var finalScrollWidth = 0;

        $(obj).find('li').each(function() {
            if (!$(this).hasClass('menu-control')) {
                totalWidth += $(this).width();
            }
        });
        
        switch (direction) {
            case 'next':
                var widthLeft = totalWidth + marginLeft - containerWidth;
                if (widthLeft <= containerWidth) {
                    finalScrollWidth = widthLeft - marginLeft + 128;
                    setTimeout(function() {
                        $(obj).find('.menu-control.menu-control-right').removeClass('show');
                    }, 150);
                } else {
                    finalScrollWidth = containerWidth - marginLeft - 128;
                }

                if (finalScrollWidth != 0) {
                    $(obj).animate({ marginLeft: '-' + finalScrollWidth + 'px'}, 150, function() {
                        $(obj).find('.menu-control.menu-control-left').addClass('show');
                    });
                }
                break;
            case 'prev':
                var widthLeft = -marginLeft;
    
                if (widthLeft <= containerWidth) {
                    $(obj).find('.menu-control.menu-control-left').removeClass('show');
                    finalScrollWidth = 0;
                } else {
                    finalScrollWidth = widthLeft - containerWidth + 88;
                }
                $(obj).animate({ marginLeft: '-' + finalScrollWidth + 'px'}, 150, function() {
                    $(obj).find('.menu-control.menu-control-right').addClass('show');
                });
                break;
        }
    }

    // handle page load active menu focus
    function handlePageLoadMenuFocus() {
        var targetMenu = $('.top-menu .nav');
        var targetList = $('.top-menu .nav > li');
        var targetActiveList = $('.top-menu .nav > li.active');
        var targetContainer = $('.top-menu');
        
        var marginLeft = parseInt($(targetMenu).css('margin-left'));  
        var viewWidth = $(targetContainer).width() - 128;
        var prevWidth = $('.top-menu .nav > li.active').width();
        var speed = 0;
        var fullWidth = 0;
        
        $(targetActiveList).prevAll().each(function() {
            prevWidth += $(this).width();
        });

        $(targetList).each(function() {
            if (!$(this).hasClass('menu-control')) {
                fullWidth += $(this).width();
            }
        });

        if (prevWidth >= viewWidth) {
            var finalScrollWidth = prevWidth - viewWidth + 128;
            $(targetMenu).animate({ marginLeft: '-' + finalScrollWidth + 'px'}, speed);
        }
        
        if (prevWidth != fullWidth && fullWidth >= viewWidth) {
            $(targetMenu).find('.menu-control.menu-control-right').addClass('show');
        } else {
            $(targetMenu).find('.menu-control.menu-control-right').removeClass('show');
        }

        if (prevWidth >= viewWidth && fullWidth >= viewWidth) {
            $(targetMenu).find('.menu-control.menu-control-left').addClass('show');
        } else {
            $(targetMenu).find('.menu-control.menu-control-left').removeClass('show');
        }
    }

    // handle menu next button click action
    $('[data-click="next-menu"]').click(function(e) {
        e.preventDefault();
        handleMenuButtonAction(this,'next');
    });

    // handle menu prev button click action
    $('[data-click="prev-menu"]').click(function(e) {
        e.preventDefault();
        handleMenuButtonAction(this,'prev');

    });

    // handle unlimited menu responsive setting
    $(window).resize(function() {
        $('.top-menu .nav').removeAttr('style');
        handlePageLoadMenuFocus();
    });

    handlePageLoadMenuFocus();
};


/* 20. Handle Top Menu - Sub Menu Toggle - added in V1.9
------------------------------------------------ */
var handleTopMenuSubMenu = function() {
    "use strict";
    $('.top-menu .sub-menu .has-sub > a').click(function() {
        var target = $(this).closest('li').find('.sub-menu').first();
        var otherMenu = $(this).closest('ul').find('.sub-menu').not(target);
        $(otherMenu).not(target).slideUp(250, function() {
            $(this).closest('li').removeClass('expand');
        });
        $(target).slideToggle(250, function() {
            var targetLi = $(this).closest('li');
            if ($(targetLi).hasClass('expand')) {
                $(targetLi).removeClass('expand');
            } else {
                $(targetLi).addClass('expand');
            }
        });
    });
};


/* 21. Handle Top Menu - Mobile Sub Menu Toggle - added in V1.9
------------------------------------------------ */
var handleMobileTopMenuSubMenu = function() {
    "use strict";
    $('.top-menu .nav > li.has-sub > a').click(function() {
        if ($(window).width() <= 767) {
            var target = $(this).closest('li').find('.sub-menu').first();
            var otherMenu = $(this).closest('ul').find('.sub-menu').not(target);
            $(otherMenu).not(target).slideUp(250, function() {
                $(this).closest('li').removeClass('expand');
            });
            $(target).slideToggle(250, function() {
                var targetLi = $(this).closest('li');
                if ($(targetLi).hasClass('expand')) {
                    $(targetLi).removeClass('expand');
                } else {
                    $(targetLi).addClass('expand');
                }
            });
        }
    });
};


/* 22. Handle Top Menu - Mobile Top Menu Toggle - added in V1.9
------------------------------------------------ */
var handleTopMenuMobileToggle = function() {
    "use strict";
    $('[data-click="top-menu-toggled"]').click(function() {
        $('.top-menu').slideToggle(250);
    });
};


/* 23. Handle Clear Sidebar Selection & Hide Mobile Menu - added in V1.9
------------------------------------------------ */
var handleClearSidebarSelection = function() {
    $('.sidebar .nav > li, .sidebar .nav .sub-menu').removeClass('expand').removeAttr('style');
};
var handleClearSidebarMobileSelection = function() {
    $('#page-container').removeClass('page-sidebar-toggled');
};


/* Application Controller
------------------------------------------------ */
var App = function () {
	"use strict";
	
	return {
		init: function () {
		    this.initLocalStorage();
		    this.initSidebar();
		    this.initTopMenu();
		    this.initPageLoad();
		    this.initComponent();
		    this.initThemePanel();
		},
		initSidebar: function() {
			handleSidebarMenu();
			handleMobileSidebarToggle();
			handleSidebarMinify();
			handleMobileSidebar();
		},
		initSidebarSelection: function() {
		    handleClearSidebarSelection();
		},
		initSidebarMobileSelection: function() {
		    handleClearSidebarMobileSelection();
		},
		initTopMenu: function() {
			handleUnlimitedTopMenuRender();
			handleTopMenuSubMenu();
			handleMobileTopMenuSubMenu();
			handleTopMenuMobileToggle();
		},
		initPageLoad: function() {
			handlePageContentView();
		},
		initComponent: function() {
			handleDraggablePanel();
			handleIEFullHeightContent();
			handleSlimScroll();
			handleUnlimitedTabsRender();
			handlePanelAction();
			handelTooltipPopoverActivation();
			handleScrollToTopButton();
			handleAfterPageLoadAddClass();
		},
		initLocalStorage: function() {
		    handleLocalStorage();
		},
		initThemePanel: function() {
			handleThemePageStructureControl();
			handleThemePanelExpand();
		    handleResetLocalStorage();
		},
		scrollTop: function() {
            $('html, body').animate({
                scrollTop: $('body').offset().top
            }, 0);
		}
  };
}();

//# sourceMappingURL=template.js.map
