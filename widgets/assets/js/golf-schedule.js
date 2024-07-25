(function (dsgw, $) {

    dsgw.libs = dsgw.libs || {}
    dsgw.init = dsgw.init || {}

    const changeRoundNavigation = (widgetWrapper) => {
        $('.dsgw-n-schedule').find('.dsgw-c-rounds').flickity('selectCell', widgetWrapper.find('.dsgw-c-nav-round').index(widgetWrapper.find('.dsgw-c-nav-round[data-id="' + $('.dsgw-n-schedule .dsgw-c-rounds-wrapper').data('round') + '"]')))
    }

    const toggleDetailPlayer = () => {
        $('.dsgw-golf-tournaments-widget').on('click', '.dsgw-leaderboard__body-player', function () {
            const detailClass = $(this).data('detail')
            $(this).toggleClass('dsgw-g-player-active')
            $(`.${detailClass}`).toggle()
        })
    }

    const loadTournamentDetail = (schedule_id, tournament_id, tournament_key) => {
        const wid = $('.dsgw-golf-tournaments-widget').data('id')
        const id = $('[data-widget="golf_tournament"]').data('id')
        const url = dsgw.libs.getWidgetUrl({
            widget: 'golf_tournament',
            id: id,
            wid: wid,
            tournament_id: tournament_id,
            schedule_id: schedule_id
        })
        const container = $(`#dsgw-tournament__${tournament_key}`)

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
        });

        $(document).ajaxComplete(function (event, xhr, settings) {
            if (settings.url === url) {
                container.html(xhr.responseText)
            }
        });
    }

    const loadScheduleWidgetBlock = (id) => {
        $('.dsgw-tournament__item--wrap').removeClass('dsgw-current-schedule')
        $(`.dsgw-tournament__item-${id}`).addClass('dsgw-current-schedule')
    }

    /**
     * Load slider
     * @param container
     */
    const loadFlickity = (container = '.dsgw-golf-schedule-rounds') => {
        $(container).flickity({
            cellAlign: 'left',
            contain: true,
            pageDots: false,
            prevNextButtons: false,
        });
        setTimeout(function () {
            $(container).flickity('resize');
        }, 2000);
    }


    const selectItem = (id) => {
        $('.dsgw-simplebar-item').removeClass('is-selected');
        const $selectedItem = $(`.dsgw-simplebar-item[data-id="${id}"]`);
        $selectedItem.addClass('is-selected');
        loadScheduleWidgetBlock(id);
        centeredSimplebar($selectedItem);
    };

    const centeredSimplebar = ($item) => {
        const itemPosition = $item.position().left;
        const itemWidth = $item.outerWidth(true);
        const $simplebar = $('.simplebar-content');
        const containerWidth = $simplebar.width();
        const scrollPosition = itemPosition + itemWidth / 2 - containerWidth / 2;
        const scrollContainer = document.querySelector('.simplebar-content');
        scrollContainer.scrollBy({
            left: scrollPosition,
            behavior: 'smooth'
        });
    }

    const initSimplebar = () => {
        const id = $('.dsgw-simplebar-container').data('round');
        $(`.dsgw-simplebar-item[data-id="${id}"]`).addClass('is-selected');
        setTimeout(() => {
            selectItem(id);
        }, 200);
    }

    if (typeof handleWidgetWidth !== 'function') {
        function handleWidgetWidth() {
            document.querySelectorAll('.dsgw-widget').forEach(el => {
                el.classList.toggle('dsgw-mb', el.offsetWidth < 768);
            });
        }

        window.addEventListener('load', handleWidgetWidth);
        window.addEventListener('resize', handleWidgetWidth);
    }

    dsgw.init.initGolfScheduleFunctions = () => {
        const widgetWrapper = $('.dsgw-golf-tournaments-widget');
        const scheduleSlider = widgetWrapper.find('.dsgw-c-rounds')
        const tournamentItemClass = '.dsgw-tournament__item'

        loadFlickity();
        changeRoundNavigation(widgetWrapper);
        toggleDetailPlayer()
        initSimplebar();

        widgetWrapper.on('click', '.dsgw-simplebar-item', function () {
            const id = $(this).data('id');
            selectItem(id);
        });

        widgetWrapper.on('click', '.dsgw-simplebar-prev', function () {
            const $currentItem = $('.dsgw-simplebar-item.is-selected');
            const $prevItem = $currentItem.prev('.dsgw-simplebar-item');
            if ($prevItem.length) {
                const prevId = $prevItem.data('id');
                selectItem(prevId);
            }
        });

        widgetWrapper.on('click', '.dsgw-simplebar-next', function () {
            const $currentItem = $('.dsgw-simplebar-item.is-selected');
            const $nextItem = $currentItem.next('.dsgw-simplebar-item');
            if ($nextItem.length) {
                const nextId = $nextItem.data('id');
                selectItem(nextId);
            }
        });

        widgetWrapper.on('click', '.dsgw-c-nav-round', function () {
            scheduleSlider.flickity('selectCell', $(this).index())
            const id = $(this).data('id')
            const activeItemClass = 'dsgw-current-schedule';
            $('.dsgw-tournament__item--wrap').removeClass(activeItemClass)
            widgetWrapper.find(`.dsgw-tournament__item-${id}`).addClass(activeItemClass)
        });

        widgetWrapper.on('click', '.dsgw-c-rounds-left', function () {
            scheduleSlider.flickity('previous');
            const id = scheduleSlider.find('.is-selected').data('id')
            loadScheduleWidgetBlock(id)
        });

        widgetWrapper.on('click', '.dsgw-c-rounds-right', function () {
            scheduleSlider.flickity('next');
            const id = scheduleSlider.find('.is-selected').data('id')
            loadScheduleWidgetBlock(id)
        });

        widgetWrapper.on('click', `${tournamentItemClass}`, function () {
            const currentTour = $(this)
            const activeClass = 'dsgw-show'
            const itemWrappClass = '.dsgw-tournament__item--wrap'
            const tournamentKey = currentTour.data('key')
            const scheduleId = currentTour.data('schedule')
            const tournamentId = currentTour.data('tournament')
            const itemWrapp = currentTour.closest(itemWrappClass)

            const resetActive = () => {
                $(tournamentItemClass).removeClass(activeClass)
                $(itemWrappClass).removeClass(activeClass)
                $('.dsgw-g-player ').hide()
                $('.dsgw-leaderboard__body-player').removeClass('dsgw-g-player-active')
            }

            const setActiveCurrentTour = () => {
                currentTour.addClass(activeClass)
                itemWrapp.addClass(activeClass)
            }

            const loadNewData = () => {
                loadTournamentDetail(scheduleId, tournamentId, tournamentKey)
                itemWrapp.addClass('dsgw-data-loaded')
            }

            if (currentTour.hasClass(activeClass)) {
                return resetActive()
            }

            resetActive()
            setActiveCurrentTour()

            if (!itemWrapp.hasClass('dsgw-data-loaded')) {
                return loadNewData()
            }
        })
    }

})(window.dsgw = window.dsgw || {}, jQuery)