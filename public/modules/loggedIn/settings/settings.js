(function ()
{
    'use strict';

    angular
        .module('app.settings', [])
        .config(config)
        .controller('SettingController', SettingController);

    /** @ngInject */
    function config($stateProvider)
    {
        // State
         $stateProvider
            .state('app.settings', {
                url: '/settings',
                data: {'pageTitle': 'Settings'},
                views   : {
                    'pageContent@app': {
                        templateUrl: 'modules/loggedIn/settings/settings.php',
                        controller: 'SettingController as vm'
                    }
                }
            });
    }

    function SettingController($http, $compile, $scope, $window) {
        // Data
        var vm = this;
        vm.option = {};
        vm.email = {};

        vm.filter = {};
        vm.filterEmail = {};
        vm.menuItems = [];
        vm.item = {};

        vm.modules = [];

        vm.itemTypes = {
            0: 'No link',
            1: 'External link',
            2: 'Module page link'
        };

        vm.faIcons = [{name: 'fa-500px', code: '\uf26e'},
{name: 'fa-adjust', code: '\uf042'},
{name: 'fa-adn', code: '\uf170'},
{name: 'fa-align-center', code: '\uf037'},
{name: 'fa-align-justify', code: '\uf039'},
{name: 'fa-align-left', code: '\uf036'},
{name: 'fa-align-right', code: '\uf038'},
{name: 'fa-amazon', code: '\uf270'},
{name: 'fa-ambulance', code: '\uf0f9'},
{name: 'fa-american-sign-language-interpreting', code: '\uf2a3'},
{name: 'fa-anchor', code: '\uf13d'},
{name: 'fa-android', code: '\uf17b'},
{name: 'fa-angellist', code: '\uf209'},
{name: 'fa-angle-double-down', code: '\uf103'},
{name: 'fa-angle-double-left', code: '\uf100'},
{name: 'fa-angle-double-right', code: '\uf101'},
{name: 'fa-angle-double-up', code: '\uf102'},
{name: 'fa-angle-down', code: '\uf107'},
{name: 'fa-angle-left', code: '\uf104'},
{name: 'fa-angle-right', code: '\uf105'},
{name: 'fa-angle-up', code: '\uf106'},
{name: 'fa-apple', code: '\uf179'},
{name: 'fa-archive', code: '\uf187'},
{name: 'fa-area-chart', code: '\uf1fe'},
{name: 'fa-arrow-circle-down', code: '\uf0ab'},
{name: 'fa-arrow-circle-left', code: '\uf0a8'},
{name: 'fa-arrow-circle-o-down', code: '\uf01a'},
{name: 'fa-arrow-circle-o-left', code: '\uf190'},
{name: 'fa-arrow-circle-o-right', code: '\uf18e'},
{name: 'fa-arrow-circle-o-up', code: '\uf01b'},
{name: 'fa-arrow-circle-right', code: '\uf0a9'},
{name: 'fa-arrow-circle-up', code: '\uf0aa'},
{name: 'fa-arrow-down', code: '\uf063'},
{name: 'fa-arrow-left', code: '\uf060'},
{name: 'fa-arrow-right', code: '\uf061'},
{name: 'fa-arrow-up', code: '\uf062'},
{name: 'fa-arrows', code: '\uf047'},
{name: 'fa-arrows-alt', code: '\uf0b2'},
{name: 'fa-arrows-h', code: '\uf07e'},
{name: 'fa-arrows-v', code: '\uf07d'},
{name: 'fa-asl-interpreting', code: '\uf2a3'},
{name: 'fa-assistive-listening-systems', code: '\uf2a2'},
{name: 'fa-asterisk', code: '\uf069'},
{name: 'fa-at', code: '\uf1fa'},
{name: 'fa-audio-description', code: '\uf29e'},
{name: 'fa-automobile', code: '\uf1b9'},
{name: 'fa-backward', code: '\uf04a'},
{name: 'fa-balance-scale', code: '\uf24e'},
{name: 'fa-ban', code: '\uf05e'},
{name: 'fa-bank', code: '\uf19c'},
{name: 'fa-bar-chart', code: '\uf080'},
{name: 'fa-bar-chart-o', code: '\uf080'},
{name: 'fa-barcode', code: '\uf02a'},
{name: 'fa-bars', code: '\uf0c9'},
{name: 'fa-battery-0', code: '\uf244'},
{name: 'fa-battery-1', code: '\uf243'},
{name: 'fa-battery-2', code: '\uf242'},
{name: 'fa-battery-3', code: '\uf241'},
{name: 'fa-battery-4', code: '\uf240'},
{name: 'fa-battery-empty', code: '\uf244'},
{name: 'fa-battery-full', code: '\uf240'},
{name: 'fa-battery-half', code: '\uf242'},
{name: 'fa-battery-quarter', code: '\uf243'},
{name: 'fa-battery-three-quarters', code: '\uf241'},
{name: 'fa-bed', code: '\uf236'},
{name: 'fa-beer', code: '\uf0fc'},
{name: 'fa-behance', code: '\uf1b4'},
{name: 'fa-behance-square', code: '\uf1b5'},
{name: 'fa-bell', code: '\uf0f3'},
{name: 'fa-bell-o', code: '\uf0a2'},
{name: 'fa-bell-slash', code: '\uf1f6'},
{name: 'fa-bell-slash-o', code: '\uf1f7'},
{name: 'fa-bicycle', code: '\uf206'},
{name: 'fa-binoculars', code: '\uf1e5'},
{name: 'fa-birthday-cake', code: '\uf1fd'},
{name: 'fa-bitbucket', code: '\uf171'},
{name: 'fa-bitbucket-square', code: '\uf172'},
{name: 'fa-bitcoin', code: '\uf15a'},
{name: 'fa-black-tie', code: '\uf27e'},
{name: 'fa-blind', code: '\uf29d'},
{name: 'fa-bluetooth', code: '\uf293'},
{name: 'fa-bluetooth-b', code: '\uf294'},
{name: 'fa-bold', code: '\uf032'},
{name: 'fa-bolt', code: '\uf0e7'},
{name: 'fa-bomb', code: '\uf1e2'},
{name: 'fa-book', code: '\uf02d'},
{name: 'fa-bookmark', code: '\uf02e'},
{name: 'fa-bookmark-o', code: '\uf097'},
{name: 'fa-braille', code: '\uf2a1'},
{name: 'fa-briefcase', code: '\uf0b1'},
{name: 'fa-btc', code: '\uf15a'},
{name: 'fa-bug', code: '\uf188'},
{name: 'fa-building', code: '\uf1ad'},
{name: 'fa-building-o', code: '\uf0f7'},
{name: 'fa-bullhorn', code: '\uf0a1'},
{name: 'fa-bullseye', code: '\uf140'},
{name: 'fa-bus', code: '\uf207'},
{name: 'fa-buysellads', code: '\uf20d'},
{name: 'fa-cab', code: '\uf1ba'},
{name: 'fa-calculator', code: '\uf1ec'},
{name: 'fa-calendar', code: '\uf073'},
{name: 'fa-calendar-check-o', code: '\uf274'},
{name: 'fa-calendar-minus-o', code: '\uf272'},
{name: 'fa-calendar-o', code: '\uf133'},
{name: 'fa-calendar-plus-o', code: '\uf271'},
{name: 'fa-calendar-times-o', code: '\uf273'},
{name: 'fa-camera', code: '\uf030'},
{name: 'fa-camera-retro', code: '\uf083'},
{name: 'fa-car', code: '\uf1b9'},
{name: 'fa-caret-down', code: '\uf0d7'},
{name: 'fa-caret-left', code: '\uf0d9'},
{name: 'fa-caret-right', code: '\uf0da'},
{name: 'fa-caret-square-o-down', code: '\uf150'},
{name: 'fa-caret-square-o-left', code: '\uf191'},
{name: 'fa-caret-square-o-right', code: '\uf152'},
{name: 'fa-caret-square-o-up', code: '\uf151'},
{name: 'fa-caret-up', code: '\uf0d8'},
{name: 'fa-cart-arrow-down', code: '\uf218'},
{name: 'fa-cart-plus', code: '\uf217'},
{name: 'fa-cc', code: '\uf20a'},
{name: 'fa-cc-amex', code: '\uf1f3'},
{name: 'fa-cc-diners-club', code: '\uf24c'},
{name: 'fa-cc-discover', code: '\uf1f2'},
{name: 'fa-cc-jcb', code: '\uf24b'},
{name: 'fa-cc-mastercard', code: '\uf1f1'},
{name: 'fa-cc-paypal', code: '\uf1f4'},
{name: 'fa-cc-stripe', code: '\uf1f5'},
{name: 'fa-cc-visa', code: '\uf1f0'},
{name: 'fa-certificate', code: '\uf0a3'},
{name: 'fa-chain', code: '\uf0c1'},
{name: 'fa-chain-broken', code: '\uf127'},
{name: 'fa-check', code: '\uf00c'},
{name: 'fa-check-circle', code: '\uf058'},
{name: 'fa-check-circle-o', code: '\uf05d'},
{name: 'fa-check-square', code: '\uf14a'},
{name: 'fa-check-square-o', code: '\uf046'},
{name: 'fa-chevron-circle-down', code: '\uf13a'},
{name: 'fa-chevron-circle-left', code: '\uf137'},
{name: 'fa-chevron-circle-right', code: '\uf138'},
{name: 'fa-chevron-circle-up', code: '\uf139'},
{name: 'fa-chevron-down', code: '\uf078'},
{name: 'fa-chevron-left', code: '\uf053'},
{name: 'fa-chevron-right', code: '\uf054'},
{name: 'fa-chevron-up', code: '\uf077'},
{name: 'fa-child', code: '\uf1ae'},
{name: 'fa-chrome', code: '\uf268'},
{name: 'fa-circle', code: '\uf111'},
{name: 'fa-circle-o', code: '\uf10c'},
{name: 'fa-circle-o-notch', code: '\uf1ce'},
{name: 'fa-circle-thin', code: '\uf1db'},
{name: 'fa-clipboard', code: '\uf0ea'},
{name: 'fa-clock-o', code: '\uf017'},
{name: 'fa-clone', code: '\uf24d'},
{name: 'fa-close', code: '\uf00d'},
{name: 'fa-cloud', code: '\uf0c2'},
{name: 'fa-cloud-download', code: '\uf0ed'},
{name: 'fa-cloud-upload', code: '\uf0ee'},
{name: 'fa-cny', code: '\uf157'},
{name: 'fa-code', code: '\uf121'},
{name: 'fa-code-fork', code: '\uf126'},
{name: 'fa-codepen', code: '\uf1cb'},
{name: 'fa-codiepie', code: '\uf284'},
{name: 'fa-coffee', code: '\uf0f4'},
{name: 'fa-cog', code: '\uf013'},
{name: 'fa-cogs', code: '\uf085'},
{name: 'fa-columns', code: '\uf0db'},
{name: 'fa-comment', code: '\uf075'},
{name: 'fa-comment-o', code: '\uf0e5'},
{name: 'fa-commenting', code: '\uf27a'},
{name: 'fa-commenting-o', code: '\uf27b'},
{name: 'fa-comments', code: '\uf086'},
{name: 'fa-comments-o', code: '\uf0e6'},
{name: 'fa-compass', code: '\uf14e'},
{name: 'fa-compress', code: '\uf066'},
{name: 'fa-connectdevelop', code: '\uf20e'},
{name: 'fa-contao', code: '\uf26d'},
{name: 'fa-copy', code: '\uf0c5'},
{name: 'fa-copyright', code: '\uf1f9'},
{name: 'fa-creative-commons', code: '\uf25e'},
{name: 'fa-credit-card', code: '\uf09d'},
{name: 'fa-credit-card-alt', code: '\uf283'},
{name: 'fa-crop', code: '\uf125'},
{name: 'fa-crosshairs', code: '\uf05b'},
{name: 'fa-css3', code: '\uf13c'},
{name: 'fa-cube', code: '\uf1b2'},
{name: 'fa-cubes', code: '\uf1b3'},
{name: 'fa-cut', code: '\uf0c4'},
{name: 'fa-cutlery', code: '\uf0f5'},
{name: 'fa-dashboard', code: '\uf0e4'},
{name: 'fa-dashcube', code: '\uf210'},
{name: 'fa-database', code: '\uf1c0'},
{name: 'fa-deaf', code: '\uf2a4'},
{name: 'fa-deafness', code: '\uf2a4'},
{name: 'fa-dedent', code: '\uf03b'},
{name: 'fa-delicious', code: '\uf1a5'},
{name: 'fa-desktop', code: '\uf108'},
{name: 'fa-deviantart', code: '\uf1bd'},
{name: 'fa-diamond', code: '\uf219'},
{name: 'fa-digg', code: '\uf1a6'},
{name: 'fa-dollar', code: '\uf155'},
{name: 'fa-dot-circle-o', code: '\uf192'},
{name: 'fa-download', code: '\uf019'},
{name: 'fa-dribbble', code: '\uf17d'},
{name: 'fa-dropbox', code: '\uf16b'},
{name: 'fa-drupal', code: '\uf1a9'},
{name: 'fa-edge', code: '\uf282'},
{name: 'fa-edit', code: '\uf044'},
{name: 'fa-eject', code: '\uf052'},
{name: 'fa-ellipsis-h', code: '\uf141'},
{name: 'fa-ellipsis-v', code: '\uf142'},
{name: 'fa-empire', code: '\uf1d1'},
{name: 'fa-envelope', code: '\uf0e0'},
{name: 'fa-envelope-o', code: '\uf003'},
{name: 'fa-envelope-square', code: '\uf199'},
{name: 'fa-envira', code: '\uf299'},
{name: 'fa-eraser', code: '\uf12d'},
{name: 'fa-eur', code: '\uf153'},
{name: 'fa-euro', code: '\uf153'},
{name: 'fa-exchange', code: '\uf0ec'},
{name: 'fa-exclamation', code: '\uf12a'},
{name: 'fa-exclamation-circle', code: '\uf06a'},
{name: 'fa-exclamation-triangle', code: '\uf071'},
{name: 'fa-expand', code: '\uf065'},
{name: 'fa-expeditedssl', code: '\uf23e'},
{name: 'fa-external-link', code: '\uf08e'},
{name: 'fa-external-link-square', code: '\uf14c'},
{name: 'fa-eye', code: '\uf06e'},
{name: 'fa-eye-slash', code: '\uf070'},
{name: 'fa-eyedropper', code: '\uf1fb'},
{name: 'facebook?', code: '\uf09a'},
{name: 'facebook-f?', code: '\uf09a'},
{name: 'facebook-official?', code: '\uf230'},
{name: 'facebook-square?', code: '\uf082'},
{name: 'fast-backward?', code: '\uf049'},
{name: 'fast-forward?', code: '\uf050'},
{name: 'fax?', code: '\uf1ac'},
{name: 'fa-feed', code: '\uf09e'},
{name: 'fa-female', code: '\uf182'},
{name: 'fa-fighter-jet', code: '\uf0fb'},
{name: 'fa-file', code: '\uf15b'},
{name: 'fa-file-archive-o', code: '\uf1c6'},
{name: 'fa-file-audio-o', code: '\uf1c7'},
{name: 'fa-file-code-o', code: '\uf1c9'},
{name: 'fa-file-excel-o', code: '\uf1c3'},
{name: 'fa-file-image-o', code: '\uf1c5'},
{name: 'fa-file-movie-o', code: '\uf1c8'},
{name: 'fa-file-o', code: '\uf016'},
{name: 'fa-file-pdf-o', code: '\uf1c1'},
{name: 'fa-file-photo-o', code: '\uf1c5'},
{name: 'fa-file-picture-o', code: '\uf1c5'},
{name: 'fa-file-powerpoint-o', code: '\uf1c4'},
{name: 'fa-file-sound-o', code: '\uf1c7'},
{name: 'fa-file-text', code: '\uf15c'},
{name: 'fa-file-text-o', code: '\uf0f6'},
{name: 'fa-file-video-o', code: '\uf1c8'},
{name: 'fa-file-word-o', code: '\uf1c2'},
{name: 'fa-file-zip-o', code: '\uf1c6'},
{name: 'fa-files-o', code: '\uf0c5'},
{name: 'fa-film', code: '\uf008'},
{name: 'fa-filter', code: '\uf0b0'},
{name: 'fa-fire', code: '\uf06d'},
{name: 'fa-fire-extinguisher', code: '\uf134'},
{name: 'fa-firefox', code: '\uf269'},
{name: 'fa-flag', code: '\uf024'},
{name: 'fa-flag-checkered', code: '\uf11e'},
{name: 'fa-flag-o', code: '\uf11d'},
{name: 'fa-flash', code: '\uf0e7'},
{name: 'fa-flask', code: '\uf0c3'},
{name: 'fa-flickr', code: '\uf16e'},
{name: 'fa-floppy-o', code: '\uf0c7'},
{name: 'fa-folder', code: '\uf07b'},
{name: 'fa-folder-o', code: '\uf114'},
{name: 'fa-folder-open', code: '\uf07c'},
{name: 'fa-folder-open-o', code: '\uf115'},
{name: 'fa-font', code: '\uf031'},
{name: 'fa-fonticons', code: '\uf280'},
{name: 'fa-fort-awesome', code: '\uf286'},
{name: 'fa-forumbee', code: '\uf211'},
{name: 'fa-forward', code: '\uf04e'},
{name: 'fa-foursquare', code: '\uf180'},
{name: 'fa-frown-o', code: '\uf119'},
{name: 'fa-futbol-o', code: '\uf1e3'},
{name: 'fa-gamepad', code: '\uf11b'},
{name: 'fa-gavel', code: '\uf0e3'},
{name: 'fa-gbp', code: '\uf154'},
{name: 'fa-ge', code: '\uf1d1'},
{name: 'fa-gear', code: '\uf013'},
{name: 'fa-gears', code: '\uf085'},
{name: 'fa-genderless', code: '\uf22d'},
{name: 'fa-get-pocket', code: '\uf265'},
{name: 'fa-gg', code: '\uf260'},
{name: 'fa-gg-circle', code: '\uf261'},
{name: 'fa-gift', code: '\uf06b'},
{name: 'fa-git', code: '\uf1d3'},
{name: 'fa-git-square', code: '\uf1d2'},
{name: 'fa-github', code: '\uf09b'},
{name: 'fa-github-alt', code: '\uf113'},
{name: 'fa-github-square', code: '\uf092'},
{name: 'fa-gitlab', code: '\uf296'},
{name: 'fa-gittip', code: '\uf184'},
{name: 'fa-glass', code: '\uf000'},
{name: 'fa-glide', code: '\uf2a5'},
{name: 'fa-glide-g', code: '\uf2a6'},
{name: 'fa-globe', code: '\uf0ac'},
{name: 'fa-google', code: '\uf1a0'},
{name: 'fa-google-plus', code: '\uf0d5'},
{name: 'fa-google-plus-square', code: '\uf0d4'},
{name: 'fa-google-wallet', code: '\uf1ee'},
{name: 'fa-graduation-cap', code: '\uf19d'},
{name: 'fa-gratipay', code: '\uf184'},
{name: 'fa-group', code: '\uf0c0'},
{name: 'fa-h-square', code: '\uf0fd'},
{name: 'fa-hacker-news', code: '\uf1d4'},
{name: 'fa-hand-grab-o', code: '\uf255'},
{name: 'fa-hand-lizard-o', code: '\uf258'},
{name: 'fa-hand-o-down', code: '\uf0a7'},
{name: 'fa-hand-o-left', code: '\uf0a5'},
{name: 'fa-hand-o-right', code: '\uf0a4'},
{name: 'fa-hand-o-up', code: '\uf0a6'},
{name: 'fa-hand-paper-o', code: '\uf256'},
{name: 'fa-hand-peace-o', code: '\uf25b'},
{name: 'fa-hand-pointer-o', code: '\uf25a'},
{name: 'fa-hand-rock-o', code: '\uf255'},
{name: 'fa-hand-scissors-o', code: '\uf257'},
{name: 'fa-hand-spock-o', code: '\uf259'},
{name: 'fa-hand-stop-o', code: '\uf256'},
{name: 'fa-hard-of-hearing', code: '\uf2a4'},
{name: 'fa-hashtag', code: '\uf292'},
{name: 'fa-hdd-o', code: '\uf0a0'},
{name: 'fa-header', code: '\uf1dc'},
{name: 'fa-headphones', code: '\uf025'},
{name: 'fa-heart', code: '\uf004'},
{name: 'fa-heart-o', code: '\uf08a'},
{name: 'fa-heartbeat', code: '\uf21e'},
{name: 'fa-history', code: '\uf1da'},
{name: 'fa-home', code: '\uf015'},
{name: 'fa-hospital-o', code: '\uf0f8'},
{name: 'fa-hotel', code: '\uf236'},
{name: 'fa-hourglass', code: '\uf254'},
{name: 'fa-hourglass-1', code: '\uf251'},
{name: 'fa-hourglass-2', code: '\uf252'},
{name: 'fa-hourglass-3', code: '\uf253'},
{name: 'fa-hourglass-end', code: '\uf253'},
{name: 'fa-hourglass-half', code: '\uf252'},
{name: 'fa-hourglass-o', code: '\uf250'},
{name: 'fa-hourglass-start', code: '\uf251'},
{name: 'fa-houzz', code: '\uf27c'},
{name: 'fa-html5', code: '\uf13b'},
{name: 'fa-i-cursor', code: '\uf246'},
{name: 'fa-ils', code: '\uf20b'},
{name: 'fa-image', code: '\uf03e'},
{name: 'fa-inbox', code: '\uf01c'},
{name: 'fa-indent', code: '\uf03c'},
{name: 'fa-industry', code: '\uf275'},
{name: 'fa-info', code: '\uf129'},
{name: 'fa-info-circle', code: '\uf05a'},
{name: 'fa-inr', code: '\uf156'},
{name: 'fa-instagram', code: '\uf16d'},
{name: 'fa-institution', code: '\uf19c'},
{name: 'fa-internet-explorer', code: '\uf26b'},
{name: 'fa-intersex', code: '\uf224'},
{name: 'fa-ioxhost', code: '\uf208'},
{name: 'fa-italic', code: '\uf033'},
{name: 'fa-joomla', code: '\uf1aa'},
{name: 'fa-jpy', code: '\uf157'},
{name: 'fa-jsfiddle', code: '\uf1cc'},
{name: 'fa-key', code: '\uf084'},
{name: 'fa-keyboard-o', code: '\uf11c'},
{name: 'fa-krw', code: '\uf159'},
{name: 'fa-language', code: '\uf1ab'},
{name: 'fa-laptop', code: '\uf109'},
{name: 'fa-lastfm', code: '\uf202'},
{name: 'fa-lastfm-square', code: '\uf203'},
{name: 'fa-leaf', code: '\uf06c'},
{name: 'fa-leanpub', code: '\uf212'},
{name: 'fa-legal', code: '\uf0e3'},
{name: 'fa-lemon-o', code: '\uf094'},
{name: 'fa-level-down', code: '\uf149'},
{name: 'fa-level-up', code: '\uf148'},
{name: 'fa-life-bouy', code: '\uf1cd'},
{name: 'fa-life-buoy', code: '\uf1cd'},
{name: 'fa-life-ring', code: '\uf1cd'},
{name: 'fa-life-saver', code: '\uf1cd'},
{name: 'fa-lightbulb-o', code: '\uf0eb'},
{name: 'fa-line-chart', code: '\uf201'},
{name: 'fa-link', code: '\uf0c1'},
{name: 'fa-linkedin', code: '\uf0e1'},
{name: 'fa-linkedin-square', code: '\uf08c'},
{name: 'fa-linux', code: '\uf17c'},
{name: 'fa-list', code: '\uf03a'},
{name: 'fa-list-alt', code: '\uf022'},
{name: 'fa-list-ol', code: '\uf0cb'},
{name: 'fa-list-ul', code: '\uf0ca'},
{name: 'fa-location-arrow', code: '\uf124'},
{name: 'fa-lock', code: '\uf023'},
{name: 'fa-long-arrow-down', code: '\uf175'},
{name: 'fa-long-arrow-left', code: '\uf177'},
{name: 'fa-long-arrow-right', code: '\uf178'},
{name: 'fa-long-arrow-up', code: '\uf176'},
{name: 'fa-low-vision', code: '\uf2a8'},
{name: 'fa-magic', code: '\uf0d0'},
{name: 'fa-magnet', code: '\uf076'},
{name: 'fa-mail-forward', code: '\uf064'},
{name: 'fa-mail-reply', code: '\uf112'},
{name: 'fa-mail-reply-all', code: '\uf122'},
{name: 'fa-male', code: '\uf183'},
{name: 'fa-map', code: '\uf279'},
{name: 'fa-map-marker', code: '\uf041'},
{name: 'fa-map-o', code: '\uf278'},
{name: 'fa-map-pin', code: '\uf276'},
{name: 'fa-map-signs', code: '\uf277'},
{name: 'fa-mars', code: '\uf222'},
{name: 'fa-mars-double', code: '\uf227'},
{name: 'fa-mars-stroke', code: '\uf229'},
{name: 'fa-mars-stroke-h', code: '\uf22b'},
{name: 'fa-mars-stroke-v', code: '\uf22a'},
{name: 'fa-maxcdn', code: '\uf136'},
{name: 'fa-meanpath', code: '\uf20c'},
{name: 'fa-medium', code: '\uf23a'},
{name: 'fa-medkit', code: '\uf0fa'},
{name: 'fa-meh-o', code: '\uf11a'},
{name: 'fa-mercury', code: '\uf223'},
{name: 'fa-microphone', code: '\uf130'},
{name: 'fa-microphone-slash', code: '\uf131'},
{name: 'fa-minus', code: '\uf068'},
{name: 'fa-minus-circle', code: '\uf056'},
{name: 'fa-minus-square', code: '\uf146'},
{name: 'fa-minus-square-o', code: '\uf147'},
{name: 'fa-mixcloud', code: '\uf289'},
{name: 'fa-mobile', code: '\uf10b'},
{name: 'fa-mobile-phone', code: '\uf10b'},
{name: 'fa-modx', code: '\uf285'},
{name: 'fa-money', code: '\uf0d6'},
{name: 'fa-moon-o', code: '\uf186'},
{name: 'fa-mortar-board', code: '\uf19d'},
{name: 'fa-motorcycle', code: '\uf21c'},
{name: 'fa-mouse-pointer', code: '\uf245'},
{name: 'fa-music', code: '\uf001'},
{name: 'fa-navicon', code: '\uf0c9'},
{name: 'fa-neuter', code: '\uf22c'},
{name: 'fa-newspaper-o', code: '\uf1ea'},
{name: 'fa-object-group', code: '\uf247'},
{name: 'fa-object-ungroup', code: '\uf248'},
{name: 'fa-odnoklassniki', code: '\uf263'},
{name: 'fa-odnoklassniki-square', code: '\uf264'},
{name: 'fa-opencart', code: '\uf23d'},
{name: 'fa-openid', code: '\uf19b'},
{name: 'fa-opera', code: '\uf26a'},
{name: 'fa-optin-monster', code: '\uf23c'},
{name: 'fa-outdent', code: '\uf03b'},
{name: 'fa-pagelines', code: '\uf18c'},
{name: 'fa-paint-brush', code: '\uf1fc'},
{name: 'fa-paper-plane', code: '\uf1d8'},
{name: 'fa-paper-plane-o', code: '\uf1d9'},
{name: 'fa-paperclip', code: '\uf0c6'},
{name: 'fa-paragraph', code: '\uf1dd'},
{name: 'fa-paste', code: '\uf0ea'},
{name: 'fa-pause', code: '\uf04c'},
{name: 'fa-pause-circle', code: '\uf28b'},
{name: 'fa-pause-circle-o', code: '\uf28c'},
{name: 'fa-paw', code: '\uf1b0'},
{name: 'fa-paypal', code: '\uf1ed'},
{name: 'fa-pencil', code: '\uf040'},
{name: 'fa-pencil-square', code: '\uf14b'},
{name: 'fa-pencil-square-o', code: '\uf044'},
{name: 'fa-percent', code: '\uf295'},
{name: 'fa-phone', code: '\uf095'},
{name: 'fa-phone-square', code: '\uf098'},
{name: 'fa-photo', code: '\uf03e'},
{name: 'fa-picture-o', code: '\uf03e'},
{name: 'fa-pie-chart', code: '\uf200'},
{name: 'fa-pied-piper', code: '\uf1a7'},
{name: 'fa-pied-piper-alt', code: '\uf1a8'},
{name: 'fa-pinterest', code: '\uf0d2'},
{name: 'fa-pinterest-p', code: '\uf231'},
{name: 'fa-pinterest-square', code: '\uf0d3'},
{name: 'fa-plane', code: '\uf072'},
{name: 'fa-play', code: '\uf04b'},
{name: 'fa-play-circle', code: '\uf144'},
{name: 'fa-play-circle-o', code: '\uf01d'},
{name: 'fa-plug', code: '\uf1e6'},
{name: 'fa-plus', code: '\uf067'},
{name: 'fa-plus-circle', code: '\uf055'},
{name: 'fa-plus-square', code: '\uf0fe'},
{name: 'fa-plus-square-o', code: '\uf196'},
{name: 'fa-power-off', code: '\uf011'},
{name: 'fa-print', code: '\uf02f'},
{name: 'fa-product-hunt', code: '\uf288'},
{name: 'fa-puzzle-piece', code: '\uf12e'},
{name: 'fa-qq', code: '\uf1d6'},
{name: 'fa-qrcode', code: '\uf029'},
{name: 'fa-question', code: '\uf128'},
{name: 'fa-question-circle', code: '\uf059'},
{name: 'fa-question-circle-o', code: '\uf29c'},
{name: 'fa-quote-left', code: '\uf10d'},
{name: 'fa-quote-right', code: '\uf10e'},
{name: 'fa-ra', code: '\uf1d0'},
{name: 'fa-random', code: '\uf074'},
{name: 'fa-rebel', code: '\uf1d0'},
{name: 'fa-recycle', code: '\uf1b8'},
{name: 'fa-reddit', code: '\uf1a1'},
{name: 'fa-reddit-alien', code: '\uf281'},
{name: 'fa-reddit-square', code: '\uf1a2'},
{name: 'fa-refresh', code: '\uf021'},
{name: 'fa-registered', code: '\uf25d'},
{name: 'fa-remove', code: '\uf00d'},
{name: 'fa-renren', code: '\uf18b'},
{name: 'fa-reorder', code: '\uf0c9'},
{name: 'fa-repeat', code: '\uf01e'},
{name: 'fa-reply', code: '\uf112'},
{name: 'fa-reply-all', code: '\uf122'},
{name: 'fa-retweet', code: '\uf079'},
{name: 'fa-rmb', code: '\uf157'},
{name: 'fa-road', code: '\uf018'},
{name: 'fa-rocket', code: '\uf135'},
{name: 'fa-rotate-left', code: '\uf0e2'},
{name: 'fa-rotate-right', code: '\uf01e'},
{name: 'fa-rouble', code: '\uf158'},
{name: 'fa-rss', code: '\uf09e'},
{name: 'fa-rss-square', code: '\uf143'},
{name: 'fa-rub', code: '\uf158'},
{name: 'fa-ruble', code: '\uf158'},
{name: 'fa-rupee', code: '\uf156'},
{name: 'fari?', code: '\uf267'},
{name: 'fa-save', code: '\uf0c7'},
{name: 'fa-scissors', code: '\uf0c4'},
{name: 'fa-scribd', code: '\uf28a'},
{name: 'fa-search', code: '\uf002'},
{name: 'fa-search-minus', code: '\uf010'},
{name: 'fa-search-plus', code: '\uf00e'},
{name: 'fa-sellsy', code: '\uf213'},
{name: 'fa-send', code: '\uf1d8'},
{name: 'fa-send-o', code: '\uf1d9'},
{name: 'fa-server', code: '\uf233'},
{name: 'fa-share', code: '\uf064'},
{name: 'fa-share-alt', code: '\uf1e0'},
{name: 'fa-share-alt-square', code: '\uf1e1'},
{name: 'fa-share-square', code: '\uf14d'},
{name: 'fa-share-square-o', code: '\uf045'},
{name: 'fa-shekel', code: '\uf20b'},
{name: 'fa-sheqel', code: '\uf20b'},
{name: 'fa-shield', code: '\uf132'},
{name: 'fa-ship', code: '\uf21a'},
{name: 'fa-shirtsinbulk', code: '\uf214'},
{name: 'fa-shopping-bag', code: '\uf290'},
{name: 'fa-shopping-basket', code: '\uf291'},
{name: 'fa-shopping-cart', code: '\uf07a'},
{name: 'fa-sign-in', code: '\uf090'},
{name: 'fa-sign-language', code: '\uf2a7'},
{name: 'fa-sign-out', code: '\uf08b'},
{name: 'fa-signal', code: '\uf012'},
{name: 'fa-signing', code: '\uf2a7'},
{name: 'fa-simplybuilt', code: '\uf215'},
{name: 'fa-sitemap', code: '\uf0e8'},
{name: 'fa-skyatlas', code: '\uf216'},
{name: 'fa-skype', code: '\uf17e'},
{name: 'fa-slack', code: '\uf198'},
{name: 'fa-sliders', code: '\uf1de'},
{name: 'fa-slideshare', code: '\uf1e7'},
{name: 'fa-smile-o', code: '\uf118'},
{name: 'fa-snapchat', code: '\uf2ab'},
{name: 'fa-snapchat-ghost', code: '\uf2ac'},
{name: 'fa-snapchat-square', code: '\uf2ad'},
{name: 'fa-soccer-ball-o', code: '\uf1e3'},
{name: 'fa-sort', code: '\uf0dc'},
{name: 'fa-sort-alpha-asc', code: '\uf15d'},
{name: 'fa-sort-alpha-desc', code: '\uf15e'},
{name: 'fa-sort-amount-asc', code: '\uf160'},
{name: 'fa-sort-amount-desc', code: '\uf161'},
{name: 'fa-sort-asc', code: '\uf0de'},
{name: 'fa-sort-desc', code: '\uf0dd'},
{name: 'fa-sort-down', code: '\uf0dd'},
{name: 'fa-sort-numeric-asc', code: '\uf162'},
{name: 'fa-sort-numeric-desc', code: '\uf163'},
{name: 'fa-sort-up', code: '\uf0de'},
{name: 'fa-soundcloud', code: '\uf1be'},
{name: 'fa-space-shuttle', code: '\uf197'},
{name: 'fa-spinner', code: '\uf110'},
{name: 'fa-spoon', code: '\uf1b1'},
{name: 'fa-spotify', code: '\uf1bc'},
{name: 'fa-square', code: '\uf0c8'},
{name: 'fa-square-o', code: '\uf096'},
{name: 'fa-stack-exchange', code: '\uf18d'},
{name: 'fa-stack-overflow', code: '\uf16c'},
{name: 'fa-star', code: '\uf005'},
{name: 'fa-star-half', code: '\uf089'},
{name: 'fa-star-half-empty', code: '\uf123'},
{name: 'fa-star-half-full', code: '\uf123'},
{name: 'fa-star-half-o', code: '\uf123'},
{name: 'fa-star-o', code: '\uf006'},
{name: 'fa-steam', code: '\uf1b6'},
{name: 'fa-steam-square', code: '\uf1b7'},
{name: 'fa-step-backward', code: '\uf048'},
{name: 'fa-step-forward', code: '\uf051'},
{name: 'fa-stethoscope', code: '\uf0f1'},
{name: 'fa-sticky-note', code: '\uf249'},
{name: 'fa-sticky-note-o', code: '\uf24a'},
{name: 'fa-stop', code: '\uf04d'},
{name: 'fa-stop-circle', code: '\uf28d'},
{name: 'fa-stop-circle-o', code: '\uf28e'},
{name: 'fa-street-view', code: '\uf21d'},
{name: 'fa-strikethrough', code: '\uf0cc'},
{name: 'fa-stumbleupon', code: '\uf1a4'},
{name: 'fa-stumbleupon-circle', code: '\uf1a3'},
{name: 'fa-subscript', code: '\uf12c'},
{name: 'fa-subway', code: '\uf239'},
{name: 'fa-suitcase', code: '\uf0f2'},
{name: 'fa-sun-o', code: '\uf185'},
{name: 'fa-superscript', code: '\uf12b'},
{name: 'fa-support', code: '\uf1cd'},
{name: 'fa-table', code: '\uf0ce'},
{name: 'fa-tablet', code: '\uf10a'},
{name: 'fa-tachometer', code: '\uf0e4'},
{name: 'fa-tag', code: '\uf02b'},
{name: 'fa-tags', code: '\uf02c'},
{name: 'fa-tasks', code: '\uf0ae'},
{name: 'fa-taxi', code: '\uf1ba'},
{name: 'fa-television', code: '\uf26c'},
{name: 'fa-tencent-weibo', code: '\uf1d5'},
{name: 'fa-terminal', code: '\uf120'},
{name: 'fa-text-height', code: '\uf034'},
{name: 'fa-text-width', code: '\uf035'},
{name: 'fa-th', code: '\uf00a'},
{name: 'fa-th-large', code: '\uf009'},
{name: 'fa-th-list', code: '\uf00b'},
{name: 'fa-thumb-tack', code: '\uf08d'},
{name: 'fa-thumbs-down', code: '\uf165'},
{name: 'fa-thumbs-o-down', code: '\uf088'},
{name: 'fa-thumbs-o-up', code: '\uf087'},
{name: 'fa-thumbs-up', code: '\uf164'},
{name: 'fa-ticket', code: '\uf145'},
{name: 'fa-times', code: '\uf00d'},
{name: 'fa-times-circle', code: '\uf057'},
{name: 'fa-times-circle-o', code: '\uf05c'},
{name: 'fa-tint', code: '\uf043'},
{name: 'fa-toggle-down', code: '\uf150'},
{name: 'fa-toggle-left', code: '\uf191'},
{name: 'fa-toggle-off', code: '\uf204'},
{name: 'fa-toggle-on', code: '\uf205'},
{name: 'fa-toggle-right', code: '\uf152'},
{name: 'fa-toggle-up', code: '\uf151'},
{name: 'fa-trademark', code: '\uf25c'},
{name: 'fa-train', code: '\uf238'},
{name: 'fa-transgender', code: '\uf224'},
{name: 'fa-transgender-alt', code: '\uf225'},
{name: 'fa-trash', code: '\uf1f8'},
{name: 'fa-trash-o', code: '\uf014'},
{name: 'fa-tree', code: '\uf1bb'},
{name: 'fa-trello', code: '\uf181'},
{name: 'fa-tripadvisor', code: '\uf262'},
{name: 'fa-trophy', code: '\uf091'},
{name: 'fa-truck', code: '\uf0d1'},
{name: 'fa-try', code: '\uf195'},
{name: 'fa-tty', code: '\uf1e4'},
{name: 'fa-tumblr', code: '\uf173'},
{name: 'fa-tumblr-square', code: '\uf174'},
{name: 'fa-turkish-lira', code: '\uf195'},
{name: 'fa-tv', code: '\uf26c'},
{name: 'fa-twitch', code: '\uf1e8'},
{name: 'fa-twitter', code: '\uf099'},
{name: 'fa-twitter-square', code: '\uf081'},
{name: 'fa-umbrella', code: '\uf0e9'},
{name: 'fa-underline', code: '\uf0cd'},
{name: 'fa-undo', code: '\uf0e2'},
{name: 'fa-universal-access', code: '\uf29a'},
{name: 'fa-university', code: '\uf19c'},
{name: 'fa-unlink', code: '\uf127'},
{name: 'fa-unlock', code: '\uf09c'},
{name: 'fa-unlock-alt', code: '\uf13e'},
{name: 'fa-unsorted', code: '\uf0dc'},
{name: 'fa-upload', code: '\uf093'},
{name: 'fa-usb', code: '\uf287'},
{name: 'fa-usd', code: '\uf155'},
{name: 'fa-user', code: '\uf007'},
{name: 'fa-user-md', code: '\uf0f0'},
{name: 'fa-user-plus', code: '\uf234'},
{name: 'fa-user-secret', code: '\uf21b'},
{name: 'fa-user-times', code: '\uf235'},
{name: 'fa-users', code: '\uf0c0'},
{name: 'fa-venus', code: '\uf221'},
{name: 'fa-venus-double', code: '\uf226'},
{name: 'fa-venus-mars', code: '\uf228'},
{name: 'fa-viacoin', code: '\uf237'},
{name: 'fa-viadeo', code: '\uf2a9'},
{name: 'fa-viadeo-square', code: '\uf2aa'},
{name: 'fa-video-camera', code: '\uf03d'},
{name: 'fa-vimeo', code: '\uf27d'},
{name: 'fa-vimeo-square', code: '\uf194'},
{name: 'fa-vine', code: '\uf1ca'},
{name: 'fa-vk', code: '\uf189'},
{name: 'fa-volume-control-phone', code: '\uf2a0'},
{name: 'fa-volume-down', code: '\uf027'},
{name: 'fa-volume-off', code: '\uf026'},
{name: 'fa-volume-up', code: '\uf028'},
{name: 'fa-warning', code: '\uf071'},
{name: 'fa-wechat', code: '\uf1d7'},
{name: 'fa-weibo', code: '\uf18a'},
{name: 'fa-weixin', code: '\uf1d7'},
{name: 'fa-whatsapp', code: '\uf232'},
{name: 'fa-wheelchair', code: '\uf193'},
{name: 'fa-wheelchair-alt', code: '\uf29b'},
{name: 'fa-wifi', code: '\uf1eb'},
{name: 'fa-wikipedia-w', code: '\uf266'},
{name: 'fa-windows', code: '\uf17a'},
{name: 'fa-won', code: '\uf159'},
{name: 'fa-wordpress', code: '\uf19a'},
{name: 'fa-wpbeginner', code: '\uf297'},
{name: 'fa-wpforms', code: '\uf298'},
{name: 'fa-wrench', code: '\uf0ad'},
{name: 'fa-xing', code: '\uf168'},
{name: 'fa-xing-square', code: '\uf169'},
{name: 'fa-y-combinator', code: '\uf23b'},
{name: 'fa-y-combinator-square', code: '\uf1d4'},
{name: 'fa-yahoo', code: '\uf19e'},
{name: 'fa-yc', code: '\uf23b'},
{name: 'fa-yc-square', code: '\uf1d4'},
{name: 'fa-yelp', code: '\uf1e9'},
{name: 'fa-yen', code: '\uf157'},
{name: 'fa-youtube', code: '\uf167'},
]

        var settings_grid_container = "#settingGrid";
        var settings_grid_pager = "#settingPager";

        var emails_grid_container = "#emailGrid";
        var emails_grid_pager = "#emailPager";

        var emailsGridLoaded = false;

        // Methods

        vm.loadSettingsGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1,
                not_editable: 0
            };

            params.url = "/api/getOptions";
            params.datatype = "json";
            params.mtype = 'GET';
            params.styleUI = 'Bootstrap';
            params.autowidth = true;
            params.height = 'auto';
            params.rownumbers = true;
            params.shrinkToFit = true;
            params.colNames = [
                'Actions',
                'Name',
                'Value',
                'Description'
            ];
            params.colModel = [
                {
                    name: 'actions',
                    index: 'actions',
                    hidden: moduleAccess.settings == 0,
                    sortable: false,
                    width: 25
                }, {
                    name: 'name',
                    index: 'name',
                    width: 100
                }, {
                    name: 'value',
                    index: 'value',
                    sortable: false,
                    width: 50
                }, {
                    name: 'description',
                    index: 'description',
                    sortable: false,
                    width: 200
                }
            ];
            params.rowNum = 1000;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = settings_grid_pager;
            params.sortname = 'name';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Settings";

            params.gridComplete = function() {
                $compile($('.clickMeSett'))($scope);
            }

            jQuery(settings_grid_container).jqGrid(params);

            jQuery(settings_grid_container).jqGrid('navGrid', settings_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.searchOptionsGrid = function() {
            var object = {};
            object = vm.filter;

            object.is_grid = 1;
            object.not_editable = 0;
            jQuery(settings_grid_container).jqGrid('setPostData', object);
            jQuery(settings_grid_container).trigger("reloadGrid");
        }

        vm.clearSearch = function() {
            vm.filter = {};
            vm.searchOptionsGrid();
        }

        vm.closeAndReset = function() {
            $('#optionModal').modal('hide');
            vm.option = {};
        }

        vm.saveOption = function() {
            $http({
                method: "POST",
                url: '/api/saveOption',
                data: vm.option,
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Option saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndReset();
                    vm.searchOptionsGrid();
                } else {
                    $.gritter.add({
                        title: 'Error!',
                        text: response.data.message,
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                }
            });
        }

        vm.editOption = function(id) {
            $http({
                method: 'GET',
                url: "/api/getOption",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    vm.option = response.data.option;
                    vm.openModal();
                } else  {
                    $.gritter.add({
                        title: 'Error!',
                        text: response.data.message,
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                }
            })
        }

        vm.openModal = function() {
            $('#optionModal').modal('show');
        }

        // Emails..
        vm.loadEmailsGrid = function() {
            var params = {};
            params.postData = {
                is_grid: 1,
                not_editable: 0
            };

            params.url = "/api/getEmailTemplates";
            params.datatype = "json";
            params.mtype = 'GET';
            params.styleUI = 'Bootstrap';
            params.autowidth = true;
            params.height = 'auto';
            params.rownumbers = true;
            params.shrinkToFit = true;
            params.colNames = [
                'Actions',
                'Name',
                'Email title',
                'Description'
            ];
            params.colModel = [
                {
                    name: 'actions',
                    index: 'actions',
                    sortable: false,
                    width: 25
                }, {
                    name: 'name',
                    index: 'name',
                    width: 100
                }, {
                    name: 'title',
                    index: 'title',
                    width: 100
                }, {
                    name: 'description',
                    index: 'description',
                    sortable: false,
                    width: 200
                }
            ];
            params.rowNum = 1000;
            params.rowList = [10, 25, 50, 75, 100, 150];
            params.pager = emails_grid_pager;
            params.sortname = 'name';
            params.sortorder = 'ASC';
            params.viewrecords = true;
            params.caption = "Email templates";

            params.gridComplete = function() {
                $compile($('.clickMeTpl'))($scope);
            }

            jQuery(emails_grid_container).jqGrid(params);

            jQuery(emails_grid_container).jqGrid('navGrid', emails_grid_pager, {
                refresh: true,
                edit: false,
                add: false,
                del: false,
                search: false
            });
        }

        vm.closeAndResetEmail = function() {
            $('#emailModal').modal('hide');
            tinymce.get('emailContent').remove();
            vm.option = {};
        }

        vm.searchEmailsGrid = function() {
            var object = {};
            object = vm.filterEmail;

            object.is_grid = 1;
            jQuery(emails_grid_container).jqGrid('setPostData', object);
            jQuery(emails_grid_container).trigger("reloadGrid");
        }

        vm.clearEmailSearch = function() {
            vm.filterEmail = {};
            vm.searchEmailsGrid();
        }

        vm.saveEmail = function() {
            vm.email.content = tinymce.get("emailContent").getContent();
            $http({
                method: "POST",
                url: '/api/saveEmailTemplate',
                data: vm.email,
            })
            .then(function successCallback(response) {
                if(response.data.success == '1') {
                    $.gritter.add({
                        title: 'Success!',
                        text: 'Email saved successfully!',
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                    vm.closeAndResetEmail();
                    vm.searchEmailsGrid();
                } else {
                    $.gritter.add({
                        title: 'Error!',
                        text: response.data.message,
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                }
            });
        }

        vm.editEmail = function(id) {
            $http({
                method: 'GET',
                url: "/api/getEmailTemplate",
                params: {
                    id: id
                }
            }).then(function successCallback(response) {
                if(response.data.success == '1') {
                    vm.email = response.data.email;
                    $('#emailContent').val(response.data.email.content);
                    vm.openEmailModal();
                } else  {
                    $.gritter.add({
                        title: 'Error!',
                        text: response.data.message,
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                }
            })
        }

        vm.openEmailModal = function() {
            tinymce.init({
                selector: '#emailContent',
                menubar: false
            });
            $('#emailModal').modal('show');
        }

        vm.viewEmail = function(templateCode) {
            $window.open('/previewEmail/'+templateCode, '_blank');
        }

        vm.addMenuItem = function() {
            vm.item.id = vm.menuItems.length;
            vm.menuItems.push({
                name: vm.item.name,
                type: vm.item.type,
                internal_id: vm.item.id,
                link: vm.item.link,
                icon: vm.item.icon,
                page: vm.item.page,
                deleted: 0
            });
            vm.item = {};
        }

        vm.deleteItem = function(id) {
            if(!confirm("Are you sure you want to delete this menu item?")) {
                return;
            }
            vm.menuItems[id].deleted = 1;
        }

        vm.editItem = function(id) {
            vm.item = vm.menuItems[id];
            console.log(vm.item);
        }

        vm.saveMenuItem = function() {
            vm.menuItems[vm.item.internal_id] = vm.item;
            vm.item = {};
        }

        vm.getModulePages = function() {
            $http({
                method: 'GET',
                url: "/api/getModulePages",
                params: {
                    active: 1,
                    sord: 'module_name',
                    with_hidden: 1,
                    rows: 10000
                }
            }).then(function successCallback(response) {
                $.each(response.data.rows, function(key, val) {
                    vm.modules[val.id] = val.cell[1];
                });
            })
        }

        vm.getMenu = function() {
            $http({
                method: 'GET',
                url: "/api/getMenu",
            }).then(function successCallback(response) {
                vm.menuItems = [];
                // vm.menuItems = response.data;

                // console.log(vm.menuItems);
                $.each(response.data, function(key, val) {
                    val.internal_id = vm.menuItems.length;
                    vm.menuItems.push(val);
                    if(val.children) {
                        $.each(val.children, function(key2, val2) {
                            val2.internal_id = vm.menuItems.length;
                            val2.is_child = true;
                            vm.menuItems.push(val2);
                        });
                    }
                });
                $('#nestable').nestable({
                    maxDepth: 2
                });

            });
        }

        vm.saveMenu = function() {
            $http({
                method: 'POST',
                url: "/api/saveMenu",
                data: {
                    items: $('.dd').nestable('serialize')
                }
            }).then(function successCallback(response) {
                if(response.data.success == 1) {
                    $.gritter.add({
                        title: 'Success!',
                        text: "Menu saved successfully!",
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                } else {
                    $.gritter.add({
                        title: 'Error!',
                        text: response.data.message,
                        sticky: true,
                        time: '',
                        class_name: 'my-sticky-class'
                    });
                }
            })
        }

        ///////
        vm.loadSettingsGrid();
        vm.getModulePages();
        vm.getMenu();

        $('#emailTab').click(function() {
            if(!emailsGridLoaded) {
                setTimeout(vm.loadEmailsGrid, 200); // Fix, otherwise grid won't take the the full width of the container..
                emailsGridLoaded = true;
            } else {
                vm.searchEmailsGrid();
            }
        });

    }

})();