(function (dsgw) {
    dsgw.libs = dsgw.libs || {}
    dsgw.init = dsgw.init || {}

    /* Internal functions */
    function getWidgetBaseUrl() {
        var scripts = document.getElementsByTagName('script');
        for (var script of scripts) {
            const parts = script.src.split('/');
            const name = parts[parts.length - 1];
            if (name === 'widgetloader.js') {
                const url = new URL(script.src);
                const protocol = url.hostname.includes('localhost') ? 'http:' : url.protocol;
                return `${protocol}//${url.hostname}${url.port ? ':' + url.port : ''}`;
            }
        }

        return 'https://sportsdatalive.com';
    }
    function serializeOptions(obj) {
        var str = [];
        for (var p in obj)
            if (obj.hasOwnProperty(p)) {
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            }
        return str.join("&");
    }

    function handleWidgetWidth() {
        document.querySelectorAll('.dsgw-widget').forEach(el => {
            new ResizeObserver(entries => {
                entries.forEach(entry => {
                    const target = entry.target;
                    const width = target.offsetWidth;

                    target.classList.toggle('dsgw-lg', width < 992);
                    target.classList.toggle('dsgw-mb', width < 768);
                    target.classList.toggle('dsgw-mb-400', width < 401);

                    if (width >= 992) {
                        target.classList.remove('dsgw-mb');
                    }
                    if (width >= 768) {
                        target.classList.remove('dsgw-mb-400');
                    }
                });
            }).observe(el);
        });
    }

    /* Library functions */
    dsgw.libs.getWidgetUrl = function (options) {
        const baseUrl = getWidgetBaseUrl();
        return `${baseUrl}/widgets/widget.php?` + serializeOptions(options);
    }

    dsgw.libs.getRequestUrl = function (uri) {
        const baseUrl = getWidgetBaseUrl();
        return `${baseUrl}/${uri}`;
    }

    dsgw.libs.loadSlimScroll = function (container, options = null, reload = false) {
        options = options || {
            allowPageScroll: true,
            axis: 'x',
            height: '100%',
            width: '100%',
            alwaysVisible: true
        }

        if ($(container).length) {
            if (reload) {
                $(container).each(function () {
                    $(this).slimScroll({destroy: true});
                });
            }
            $(container).each(function () {
                $(this).slimScroll(options);
            });
        }
    }

    /* Widget functions to initialize */
    dsgw.init.loadBoxscoreFunctions = function () {
        dsgw.libs.loadSlimScroll('.dsgw-n-boxscore .dsgw-c-c2');
        for (const el of document.querySelectorAll('.dsgw-n-boxscore .dsgw-c-toggle > div')) {
            el.addEventListener('click', function () {
                for (const el2 of this.parentNode.querySelectorAll('div')) {
                    el2.classList.remove('dsgw-c-active');
                }
                for (const el2 of this.parentNode.parentNode.querySelectorAll('.dsgw-n-boxscore .dsgw-c-stats')) {
                    el2.style.display = 'none';
                }

                this.parentNode.parentNode.querySelectorAll('.dsgw-n-boxscore .dsgw-c-stats[data-side="' + this.dataset.side + '"]')[0].style.display = 'grid';
                this.classList.add('dsgw-c-active');
            })
        }
    }
    dsgw.init.loadPrevMatchFunctions = function () {
        for (const el of document.querySelectorAll('.dsgw-n-previous-games .dsgw-c-teams > div')) {
            el.addEventListener('click', function () {
                for (const el2 of this.parentNode.querySelectorAll('div')) {
                    el2.classList.remove('dsgw-c-active');
                }
                for (const el2 of this.parentNode.parentNode.querySelectorAll('.dsgw-n-previous-games .dsgw-c-matches')) {
                    el2.style.display = 'none';
                }

                this.parentNode.parentNode.querySelectorAll('.dsgw-n-previous-games .dsgw-c-matches[data-side="' + this.dataset.side + '"]')[0].style.display = 'grid';
                this.classList.add('dsgw-c-active');
            })
        }
    }
    dsgw.init.loadMatchStandingsFunctions = function () {
        for (const el of document.querySelectorAll('.dsgw-n-match-standings .dsgw-c-nav > div')) {
            el.addEventListener('click', function () {
                for (const el2 of this.parentNode.querySelectorAll('div')) {
                    el2.classList.remove('dsgw-c-selected');
                }
                for (const el2 of this.parentNode.parentNode.querySelectorAll('.dsgw-n-match-standings .dsgw-c-results > div')) {
                    el2.style.display = 'none';
                }

                this.parentNode.parentNode.querySelectorAll('.dsgw-n-match-standings .dsgw-c-results > div[data-id="' + this.dataset.id + '"]')[0].style.display = 'grid';
                this.classList.add('dsgw-c-selected');
            })
        }
    }
    dsgw.init.loadGameStatFunctions = function () {
        for (const el of document.querySelectorAll('.dsgw-n-game-stats .dsgw-c-toggle-all')) {
            el.addEventListener('click', function () {
                let targetElement = el.parentNode.querySelectorAll('.dsgw-c-all-stats')[0];

                if (targetElement.classList.contains('dsgw-c-active')) {
                    targetElement.classList.remove('dsgw-c-active');
                    targetElement.style.height = 0;
                    el.querySelectorAll('span')[0].innerText = 'Show All';
                    el.querySelectorAll('img')[0].style.transform = 'rotate(0)';
                } else {
                    targetElement.classList.add('dsgw-c-active');
                    targetElement.style.height = targetElement.scrollHeight + 'px';
                    el.querySelectorAll('span')[0].innerText = 'Hide All';
                    el.querySelectorAll('img')[0].style.transform = 'rotate(180deg)';
                }
            })
        }
    }
    dsgw.init.initRosterFunctions = function () {
        dsgw.libs.loadSlimScroll('.dsgw-n-team-roster .dsgw-c-col2');
        $('.dsgw-n-team-roster').on('click', '.dsgw-c-select-label', function () {
            $('.dsgw-n-team-roster .dsgw-c-options').slideToggle();
        });

        $('.dsgw-n-team-roster').on('click', '.dsgw-c-options > div', function () {
            $('.dsgw-n-team-roster .dsgw-c-category').removeClass('dsgw-c-active');
            $('#' + $(this).data('id')).addClass('dsgw-c-active');
            $('.dsgw-n-team-roster .dsgw-c-select-label').text($(this).text());
            $('.dsgw-n-team-roster .dsgw-c-options').slideUp();
        });
    }
    dsgw.init.initTopPlayerFunctions = function () {
        if ($('.dsgw-n-top-players').length) {
            $('.dsgw-n-top-players').on('click', '.dsgw-c-select-label', function () {
                $('.dsgw-n-top-players .dsgw-c-options').slideToggle();
            });

            $('.dsgw-n-top-players').on('click', '.dsgw-c-options > div', function () {
                $('.dsgw-n-top-players .dsgw-c-ranking-type').removeClass('dsgw-c-active');
                $('#' + $(this).data('id')).addClass('dsgw-c-active');
                $('.dsgw-n-top-players .dsgw-c-select-label span').text($(this).text());
                $('.dsgw-n-top-players .dsgw-c-options').slideUp();
            });
        } else {
            $('.dsg-widget[data-widget="top_players"]').prev('.sport-subheader').remove();
        }
    }
    dsgw.init.initScoringSummaryFunctions = function () {
        if ($('.dsgw-n-scoring-summary .dsgw-c-periods').length) {
            $('.dsgw-n-scoring-summary').on('click', '.dsgw-c-periods > div', function () {
                $('.dsgw-n-scoring-summary .dsgw-c-periods > div').removeClass('dsgw-c-active');
                $(this).addClass('dsgw-c-active');

                $('.dsgw-n-scoring-summary .dsgw-c-period').removeClass('dsgw-c-active');
                $('.dsgw-n-scoring-summary .dsgw-c-period[data-id="' + $(this).data('id') + '"]').addClass('dsgw-c-active');
            });
        }
    }
    dsgw.init.initPlayerCareerStatFunctions = function () {
        dsgw.libs.loadSlimScroll('.dsgw-n-player-career-stats .dsgw-c-col2');
    }
    dsgw.init.initPlayerMatchesFunctions = function () {
        dsgw.libs.loadSlimScroll('.dsgw-n-player-recent-matches .dsgw-c-col2');
    }
    dsgw.init.initTeamStatsFunctions = function () {
        dsgw.libs.loadSlimScroll('.dsgw-n-team-stats .dsgw-c-col2');
    }
    dsgw.init.initScoreboardFunctions = function () {
        function liveScoreboardAction() {
            if (document.querySelectorAll('.dsgw-n-scoreboard .dsgw-c-periods').length > 0 && document.querySelectorAll('.dsgw-n-scoreboard .dsgw-c-scores').length > 0) {
                setInterval(() => {
                    if (document.querySelectorAll('.dsgw-n-scoreboard .dsgw-c-scores')[0].style.transform == 'translateX(-100%)') {
                        document.querySelectorAll('.dsgw-n-scoreboard .dsgw-c-scores')[0].style.transform = 'translateX(0)';
                        document.querySelectorAll('.dsgw-n-scoreboard .dsgw-c-periods')[0].style.transform = 'translateX(0)';
                    } else {
                        document.querySelectorAll('.dsgw-n-scoreboard .dsgw-c-scores')[0].style.transform = 'translateX(-100%)';
                        document.querySelectorAll('.dsgw-n-scoreboard .dsgw-c-periods')[0].style.transform = 'translateX(-100%)';
                    }
                }, 5000)
            }
        }

        liveScoreboardAction();
        dsgw.libs.loadSlimScroll('.dsgw-n-scoreboard .dsgw-c-periods');
    }

    async function initWidgetLoader() {
        function loadLibrary(library) {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                if (library === 'jquery' && window.jQuery) {
                    resolve();
                    return;
                }
                const baseUrl = getWidgetBaseUrl();
                script.src = library === 'jquery'
                    ? `${baseUrl}/widgets/assets/js/jquery.min.js`
                    : `${baseUrl}/widgets/assets/js/${library}.js`;
                script.async = true;
                script.defer = true;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }
        function loadResource(type, resource) {
            const baseUrl = getWidgetBaseUrl();
            if (type === 'css') {
                const link = document.createElement('link');
                link.href = `${baseUrl}/widgets/assets/css/${resource}.css`;
                link.type = 'text/css';
                link.rel = 'stylesheet';
                link.media = 'screen,print';

                document.head.appendChild(link);
            }
        }

        async function loadWidgetDependencies(widgetName) {
            const jsLibraries = {
                standings: ['jquery', 'slimscroll', 'standings'],
                player_recent_matches: ['jquery', 'slimscroll'],
                player_career_stats: ['jquery', 'slimscroll'],
                team_stats: ['jquery', 'slimscroll'],
                team_roster: ['jquery', 'slimscroll'],
                boxscore: ['jquery', 'slimscroll'],
                scoreboard: ['jquery', 'slimscroll'],
                top_players: ['jquery'],
                scoring_summary: ['jquery'],
                schedule: ['jquery', 'jquery-ui.min', 'flickity', 'schedule'],
                golf_tournament: ['jquery', 'flickity', 'golf-schedule', 'simplebar.min'],
                race_calendar: ['jquery', 'race-calendar'],
                sessions: ['jquery', 'sessions']
            };

            const cssResources = {
                schedule: ['flickity.min'],
                golf_tournament: ['flickity.min', 'simplebar']
            };

            const libraries = jsLibraries[widgetName];
            const resources = cssResources[widgetName];

            if (libraries) {
                for (const lib of libraries) {
                    await loadLibrary(lib);
                }
            }

            if (resources) {
                for (const res of resources) {
                    loadResource('css', res);
                }
            }
        }

        function loadWidgetFunctions(widgetName) {
            switch (widgetName) {
                case 'standings':
                    dsgw.init.initStandingsFunctions();
                    break;
                case 'schedule':
                    dsgw.init.initScheduleFunctions();
                    break;
                case 'scoreboard':
                    dsgw.init.initScoreboardFunctions();
                    break;
                case 'boxscore':
                    dsgw.init.loadBoxscoreFunctions(); // Naming consistency
                    break;
                case 'previous_games':
                    dsgw.init.loadPrevMatchFunctions(); // Naming consistency
                    break;
                case 'match_standings':
                    dsgw.init.loadMatchStandingsFunctions(); // Naming consistency
                    break;
                case 'game_stats':
                    dsgw.init.loadGameStatFunctions(); // Naming consistency
                    break;
                case 'player_recent_matches':
                    dsgw.init.initPlayerMatchesFunctions();
                    break;
                case 'player_career_stats':
                    dsgw.init.initPlayerCareerStatFunctions();
                    break;
                case 'team_stats':
                    dsgw.init.initTeamStatsFunctions();
                    break;
                case 'team_roster':
                    dsgw.init.initRosterFunctions();
                    break;
                case 'top_players':
                    dsgw.init.initTopPlayerFunctions();
                    break;
                case 'scoring_summary':
                    dsgw.init.initScoringSummaryFunctions();
                    break;
                case 'golf_tournament':
                    dsgw.init.initGolfScheduleFunctions(); // Fix double dot
                    break;
                case 'race_calendar':
                    dsgw.init.initRaceCalendarFunctions();
                    break;
                case 'sessions':
                    dsgw.init.initSessionsFunctions();
                    break;
                default:
                    // Handle unknown widget names (optional)
                    // console.warn(`Unknown widget name: ${widgetName}`);
            }
        }

        function loadWidget(container, url) {
            var request = new XMLHttpRequest();

            try {
                request.onreadystatechange = function () {
                    if (request.readyState == 4) {
                        container.innerHTML = request.responseText;
                        handleWidgetWidth();
                        loadWidgetFunctions(container.dataset.widget);
                    }
                };
                request.open("GET", url, true);
                request.send();
            } catch (e) {

            }
        }

        loadResource('css', 'widget');
        for (const el of document.querySelectorAll('.dsg-widget')) {
            await loadWidgetDependencies(el.dataset.widget);
            loadWidget(el, dsgw.libs.getWidgetUrl(el.dataset));
        }
    }

    window.addEventListener('load', initWidgetLoader);

})(window.dsgw = window.dsgw || {});
