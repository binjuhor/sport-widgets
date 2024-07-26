<?php
$competitionId = $_GET['id'];
if (empty($competitionId)) exit();

$isViewDetailTournament = isset($_GET['schedule_id']) && isset($_GET['tournament_id']);

if($isViewDetailTournament) {
    $jsonFile = './api/golf_tournament/tournaments/tournament_'.$_GET['schedule_id'].'_'.intval($_GET['tournament_id']).'.json';
} else {
    $jsonFile = './api/golf_tournament/schedule_'.$competitionId.'.json';
}

$data = json_decode(file_get_contents($jsonFile) ,true);
$schedules = isset($data['schedule']) ? $data['schedule'] : [];
$seasonInfo = isset($data['season_info']) ? $data['season_info'] : [];
$tournaments = isset($data['tournaments']) ? $data['tournaments'] : [];
$currentScheduleId = isset($_POST['schedule_id']) ? $_POST['schedule_id'] : $seasonInfo['current_shedule'];
$currentTournamentId = $seasonInfo['current_tournament_id'];

function formatDateLabel($label) {
    $monthAbbreviations = [
        '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
        '04' => 'Apr', '05' => 'May', '06' => 'Jun',
        '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
        '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'
    ];
    $date = explode('-', $label);
    $numericMonth = $date[1];

    $abbreviatedMonth = isset($monthAbbreviations[$numericMonth]) ? $monthAbbreviations[$numericMonth] : '';

    return '<span><strong>'.$abbreviatedMonth.'</strong></span>&nbsp;<p>'.$date[0].'</p>';
}

function getScoreColor($score, $par) {
    $difference = $score - $par;

    switch ($difference) {
        case -2:
        case -3:
        case -4:
            return 'dsgw-eagle';
        case -1:
            return 'dsgw-bridie';
        case 1:
            return 'dsgw-bogey';
        case 2:
        case 3:
        case 4:
            return 'dsgw-double-bogey';
        default:
            return '';
    }
}

function generateTournament($tournament, $tournamentKey){
?>
    <?php if (isset($tournament['top_players'])) : ?>
        <div class="dsgw-c-group dsgw-c-full-width dsgw-element_<?php echo $tournamentKey ?> dsgw-g-top-players dsgw-element">
            <h2>TOP PLAYERS</h2>
            <div class="dsgw-c-top-player ">
                <div class="dsgw-player-group">
                    <?php foreach($tournament['top_players'] as $player) :?>
                        <div class="dsgw-player-detail">
                            <span class="dsgw-mc-b dsgw-mc-center dsgw-player-title"><?php echo $player['title']; ?></span>
                            <div class="dsgw-player-item">
                                <div class="dsgw-c-flex-col dsgw-text-center dsgw-top-players__flag">
                                    <span class="dsgw-mc-b dsgw-top-players__position"><?php echo $player['position']; ?></span>
                                    <img src="<?php echo $player['flag']?>" class="dsgw-c-flag" alt="">
                                </div>
                                <div class="dsgw-c-t-r2">
                                    <img src="<?php echo $player['avatar']?>" class="dsgw-c-avatar" alt="">
                                </div>
                                <div class="dsgw-c-flex-col">
                                    <span class="dsgw-mc-name"><?php echo $player['name']; ?></span>
                                    <span class="dsgw-mc-p6"><?php echo $player['value'] ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php  if(isset($tournament['leaderboard']) && count($tournament['leaderboard'])): ?>
        <div class="dsgw-c-group dsgw-element_<?php echo $tournamentKey ?> dsgw-leaderboard dsgw-element">
            <h2>LEADERBOARD</h2>
            <div class="dsgw-c-table dsgw-g-responsive-table">
                <div class="dsgw-c-table-c2" >
                    <div class="dsgw-c-table-h dsgw-text-center dsgw-leaderboard__body-player">
                        <div class="dsgw-mc-b">#</div>
                        <div class="dsgw-mc-b">Player</div>
                        <div class="dsgw-mc-b">Score</div>
                        <div class="dsgw-mc-b">THRU</div>
                        <div class="dsgw-mc-b">Round</div>
                        <div class="dsgw-mc-b">R1</div>
                        <div class="dsgw-mc-b">R2</div>
                        <div class="dsgw-mc-b">R3</div>
                        <div class="dsgw-mc-b">R4</div>
                        <div class="dsgw-mc-b">Strokes</div>
                        <div class="dsgw-mc-b">FedEx PTS</div>
                    </div>
                    <?php
                    foreach ($tournament['leaderboard'] as $playerKey => $player) :
                        $playerClass = 'dsgw-pplayer_'.md5($player['player_id'].$tournament['schedule_id'].rand(0, 1000));
                        ?>
                        <div class="dsgw-c-table-r dsgw-text-center dsgw-leaderboard__body-player" data-detail="<?php echo $playerClass?>">
                            <div class="dsgw-c-t-r1 dsgw-g-rank"><?php echo $playerKey + 1; ?></div>
                            <div class="dsgw-c-t-r2 dsgw-min-w-fill-available dsgw-g-player-name">
                                <img src="<?php echo $player['flag']?>" class="dsgw-c-flag" alt="">
                                <a href="#"><?php echo $player['name']?></a>
                            </div>
                            <div class="dsgw-mc-p2"><?php echo $player['score']?></div>
                            <div class="dsgw-mc-p2"><?php echo $player['thru']?></div>
                            <div class="dsgw-mc-p1"><?php echo $player['round']?></div>
                            <div class="dsgw-mc-"><?php echo $player['r1']?></div>
                            <div class="dsgw-mc-"><?php echo $player['r2']?></div>
                            <div class="dsgw-mc-"><?php echo $player['r3']?></div>
                            <div class="dsgw-mc-"><?php echo $player['r4']?></div>
                            <div class="dsgw-mc-"><?php echo $player['strokes']?></div>
                            <div class="dsgw-mc-b"><?php echo $player['fedEx_pts']?></div>
                            <div class="dsgw-arrow-container">
                                <div class="dsgw-arrow-blue"></div>
                            </div>
                        </div>
                        <?php if(isset($player['details']) && count($player['details'])):?>
                        <div class="dsgw-g-player <?php echo $playerClass?>" style="display: none;">
                            <?php
                            $parList = $player['details']['par'];
                            foreach ($player['details'] as $title => $data):?>
                                <?php if(strtolower($title) !== 'tot'):
                                    ?>
                                    <div class="dsgw-c-table-r dsgw-text-center <?php echo $title == 'hole' || $title == 'par' ? 'dsgw-c-table-detail' : '' ?>">
                                        <div class="dsgw-mc-b dsgw-g-l-heading"><?php echo ucfirst($title)?></div>
                                        <?php
                                        foreach ($data as $key => $datum):
                                            $end =  $key+1 === count($data);
                                            ?>
                                            <div class="dsgw-mc-">
                                                <span class="<?php echo strtolower($title) !== 'hole' ? getScoreColor($datum, $parList[$key]) : '';?>">
                                                    <?php echo $datum?>
                                                </span>
                                            </div>

                                            <?php if(strtolower($title) === 'hole' && $end):?>
                                            <div class="dsgw-mc-b">Tot</div>
                                            <?php endif?>

                                            <?php if(isset($player['details']['tot'][$title])):?>
                                            <?php if($player['details']['tot'][$title] && $end):?>

                                                <div class="dsgw-mc- <?php echo strtolower($title) === 'par' ? 'dsgw-mc-b' : 'dsgw-mc-name';?>">
                                                    <?php echo $player['details']['tot'][$title]?>
                                                </div>

                                            <?php endif?>
                                        <?php endif?>
                                        <?php endforeach;?>
                                    </div>
                                <?php endif;?>
                            <?php endforeach;?>

                            <div class="dsgw-c-table-r dsgw-c-table-score">
                                <div class="left-item left-item-circle item-yellow">Eagle or Better</div>
                                <div class="left-item left-item-circle item-red">Bridie</div>
                                <div class="left-item item-blue">Doube Bogey +</div>
                                <div class="left-item item-black">Bogey</div>
                            </div>
                        </div>
                    <?php endif;?>
                    <?php  endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif;?>
<?php
}

if($isViewDetailTournament && count($data)) {
    generateTournament($data,  md5($_POST['tournament_id']));
    exit();
}
?>
<div class="dsgw-widget dsgw-n-schedule dsgw-golf-tournaments-widget" data-id="<?php echo $competitionId?>">
	<div class="dsgw-c-content">
        <div class="dsgw-simplebar-container" data-round="<?php echo $currentScheduleId?>">
            <div class="dsgw-simplebar-wrapper">
                <div class="dsgw-simplebar-arrow dsgw-simplebar-prev">
                    <svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 448 512">
                        <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
                    </svg>
                </div>
                <div data-simplebar class="dsgw-simplebar-horizontal">
                    <?php foreach ($schedules as $schedule) { ?>
                        <div class="dsgw-simplebar-item" data-id="<?php echo $schedule['id']; ?>">
                            <?= formatDateLabel($schedule['label']); ?>
                        </div>
                    <?php }; ?>
                </div>
                <div class="dsgw-simplebar-arrow dsgw-simplebar-next">
                    <svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 448 512">
                        <path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="dsgw-g-results dsgw-g-tournaments">
            <?php
            foreach ($tournaments as $key => $tournament):
                $isActivated = $isViewDetailTournament ?: isset($tournament['activate']) && $tournament['activate'];
                $isHaveLeaderBoard =  isset($tournament['leaderboard']) && count($tournament['leaderboard']);
                $tournamentKey = md5($tournament['tournament_id'].$key);
                $isCurrentTournament = $tournament['tournament_id'] === $currentTournamentId;
                $isCurrentSchedule = $tournament['schedule_id'] === $currentScheduleId;
                $isLoadedData =  isset($tournament['top_players']) || $isHaveLeaderBoard;
            ?>
            <div class="dsgw-tournament__item--wrap dsgw-tournament__item-<?php echo $tournament['schedule_id']?><?php echo $isCurrentSchedule ? ' dsgw-current-schedule' : ''?><?php echo $isActivated  ? ' dsgw-show' : ''?><?php echo $isLoadedData  ? ' dsgw-data-loaded' : ''?>">
                <div
                    class="dsgw-tournament__item <?php echo $isCurrentTournament ? ' dsgw-current' : ''?><?php echo $isActivated?' dsgw-show':''?>"
                    data-key="<?php echo $tournamentKey?>"
                    data-schedule="<?php echo $tournament['schedule_id']?>"
                    data-tournament="<?php echo $tournament['tournament_id']?>"
                >
                    <div class="dsgw-c-flex-row dsgw-gap-20 dsgw-c-competition">
                        <div class="dsgw-c-tournament">
                            <img class="dsgw-c-tournament-logo" src="<?php echo $tournament['logo']?>" alt="<?php echo $tournament['name']?>">
                        </div>
                        <div class="dsgw-c-flex-col">
                            <span class="dsgw-c-competition__date"><?php echo $tournament['date']?></span>
                            <span class="dsgw-c-competition__name"><?php echo $tournament['name']?></span>
                            <span class="dsgw-text-grey"><?php echo $tournament['location']?></span>
                        </div>
                    </div>

                    <div class="dsgw-detail-data">
                        <div class="dsgw-detail-data__item dsgw-c-prize">
                            <div class="dsgw-c-prize-item">
                                <small class="dsgw-text-grey">FedEx Cup</small>
                                <span class="dsgw-text-left dsgw-mc-b"><?php echo $tournament['fedex_cup']?> pts</span>
                            </div>
                            <div class="dsgw-c-prize-item">
                                <small class="dsgw-text-grey">Purse</small>
                                <span class="dsgw-mc-name"><?php echo $tournament['purse']?></span>
                            </div>
                        </div>

                        <div class="dsgw-detail-data__item dsgw-c-previous-winner">
                            <?php $winner = $tournament['previous_winner'];?>
                            <div class="dsgw-c-previous-winner-item">
                                <small class="dsgw-text-grey">Previous Winner</small>
                                <div class="dsgw-c-flex-row dsgw-align-center">
                                    <span><img class="dsgw-c-flag" src="<?php echo $winner['flag']?>" alt=""/> </span>
                                    <span class="dsgw-mc-b"><?php echo $winner['name']?></span>
                                </div>
                            </div>
                            <div class="dsgw-c-previous-winner-item sm:dsgw-align-center sm:dsgw-justify-center">
                                <div><?php echo $winner['earnings']?></div>
                            </div>
                        </div>
                    </div>

                    <div class="dsgw-arrow-container "><span class="dsgw-arrow"></span></div>
                </div>

                <div id="dsgw-tournament__<?php echo $tournamentKey ?>" class="dsgw-g-results">
                    <?php generateTournament($tournament, $tournamentKey); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
	</div>
</div>
<style>
    dsgw-golf-tournaments-widget.dsgw-widget * {
        font-family: "Montserrat", serif;
        box-sizing: border-box;
    }

    .dsgw-golf-tournaments-widget.dsgw-widget a {
        color: inherit;
        text-decoration: none;
    }

    .dsgw-golf-tournaments-widget.dsgw-widget {
        background-color: white;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-nav {
        width: 100%;
        height: 50px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 50px;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-rounds-wrapper {
        height: 100%;
        display: grid;
        align-items: center;
        grid-template-columns: 40px 1fr 40px;
        border-top: 1px solid #EEE;
        border-bottom: 1px solid #EEE;
        width: 100%;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-nav-round {
        height: 100%;
        white-space: nowrap;
        padding: 0 25px;
        border-bottom: 2px solid transparent;
        display: flex;
        align-items: center;
        cursor: pointer;
        color: #737373;
        font-size: 16px;
        position: relative;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-nav-round.is-selected {
        border-image: linear-gradient(90deg, #0070F0 -12.5%, #01F4F5 111.11%) 1;
        color: black;
        font-weight: bold;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-nav-round.is-selected > span {
        font-weight: bold;
        background: linear-gradient(90deg, #0070F0 -12.5%, #01F4F5 111.11%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-rounds-left,
    .dsgw-golf-tournaments-widget .dsgw-c-rounds-right {
        font-size: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-rounds-right {
        transform: rotate(180deg);
    }

    .dsgw-golf-tournaments-widget .dsgw-c-results > div.dsgw-c-active {
        display: block;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-group h2 {
        color: #BFBFBF;
        font-size: 25px;
        font-weight: 300;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-table {
        display: flex;
        grid-template-columns: 250px 1fr;
        background: #FFF;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-c-table {
        grid-template-columns: 170px 1fr;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-table-h {
        font-weight: bold;
        font-size: 13px;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-table-r {
        font-size: 14px;
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-c-table-h {
        font-size: 11px;
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-c-table-r {
        font-size: 13px;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-table-h,
    .dsgw-golf-tournaments-widget .dsgw-c-table-r {
        height: 50px;
        align-items: center;
        border-bottom: 1px solid #eee;
    }

    .dsgw-golf-tournaments-widget .dsgw-leaderboard {
        width: 100%;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-table-c1 .dsgw-c-table-h,
    .dsgw-golf-tournaments-widget .dsgw-c-table-c1 .dsgw-c-table-r {
        display: grid;
        grid-template-columns: 40px 1fr;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-table-c2 {
        background: #fff;
        min-width: 1000px;
        width: 100%;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-table-c2 .dsgw-c-table-h,
    .dsgw-golf-tournaments-widget .dsgw-c-table-c2 .dsgw-c-table-r {
        grid-auto-columns: minmax(50px, 1fr);
        display: grid;
        grid-auto-flow: column;
        overflow: hidden;
        padding-right: 20px;
    }

    .dsgw-golf-tournaments-widget .dsgw-leaderboard__body-player {
        grid-template-columns: 60px 205px 1fr;
        width: 100%;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-table-score {
        display: flex !important;
        justify-content: center;
        gap: 30px;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-t-r1 {
        text-align: center;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-table-r .dsgw-c-t-r1 {
        font-size: 20px;
        background: -webkit-linear-gradient(#0070F0, #01F4F5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-table-detail {
        background-color: #f5f2f2;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-t-r2 {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dsgw-golf-tournaments-widget .dsgw-mc-b {
        font-weight: bold;
        font-family: Arial, sans-serif;
    }

    .dsgw-golf-tournaments-widget .dsgw-mc-name {
        font-size: 15px;
        font-weight: bold;
        font-family: Arial, sans-serif;
        background: -webkit-linear-gradient(#0070F0, #01F4F5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .dsgw-golf-tournaments-widget .dsgw-mc-p6 {
        font-size: 30px;
    }

    .dsgw-golf-tournaments-widget .dsgw-mc-center {
        text-align: center;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-results > div.dsgw-c-active {
        display: block;
    }

    .dsgw-golf-tournaments-widget .left-item {
        position: relative;
        padding-left: 20px;
    }

    .dsgw-golf-tournaments-widget .left-item::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 15px;
        height: 15px;
    }

    .dsgw-golf-tournaments-widget .left-item-circle::before {
        border-radius: 50%;
    }

    .dsgw-golf-tournaments-widget .item-red::before {
        background-color: #f2382e;
    }

    .dsgw-golf-tournaments-widget .item-yellow::before {
        background-color: #f7ce16;
    }

    .dsgw-golf-tournaments-widget .item-blue::before {
        background-color: #3dcaf5;
    }

    .dsgw-golf-tournaments-widget .item-black::before {
        background-color: #041217;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-top-player {
        position: relative;
        display: grid;
    }

    .dsgw-golf-tournaments-widget .dsgw-player-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }

    .dsgw-golf-tournaments-widget .dsgw-player-group .dsgw-player-detail {
        box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.1);
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        gap: 15px;
        flex: 1;
    }

    .dsgw-golf-tournaments-widget .dsgw-player-group .dsgw-player-item {
        gap: 20px;
        display: grid;
        grid-template-columns: 30px 50px 120px;
        align-items: center;
        justify-content: center;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-flex-col {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-flex-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dsgw-golf-tournaments-widget .dsgw-player-title {
        font-size: 12px;
    }

    .dsgw-golf-tournaments-widget .dsgw-top-players__position {
        font-size: 12px;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-full-width {
        width: 100%;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-flag {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        padding: 5px;
        background-color: white;
        box-shadow: 0 0 0 1px rgba(128, 128, 128, 0.3);
        object-fit: scale-down;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-tournament-logo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        padding: 5px;
        background-color: white;
        box-shadow: 0 0 0 1px rgba(128, 128, 128, 0.3);
        object-fit: scale-down;
    }

    .dsgw-g-tournaments .dsgw-tournament__item--wrap {
        display: none;
    }

    .dsgw-g-tournaments .dsgw-tournament__item--wrap.dsgw-current-schedule {
        display: block;
        width: 100%;
        margin-bottom: 2.5rem;
    }

    .dsgw-g-tournaments .dsgw-tournament__item {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        position: relative;
        border: 1px solid #eee;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .dsgw-g-tournaments .dsgw-tournament__item .dsgw-detail-data {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        padding: 0;
        border-left: 1px solid #eee;
        border-top: 1px solid #eee;
        margin-top: -1px;
    }

    .dsgw-g-tournaments .dsgw-tournament__item .dsgw-detail-data .dsgw-detail-data__item {
        padding: 1.5rem;
        justify-content: center;
        gap: 10px;
        display: flex;
        flex-direction: column;
    }

    .dsgw-g-tournaments .dsgw-tournament__item .dsgw-detail-data .dsgw-c-prize {
        border-right: 1px solid #eee;
    }

    .dsgw-tournament__item:not(.dsgw-current) .dsgw-c-competition::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: -webkit-linear-gradient(#0070F0, #01F4F5);
    }

    .dsgw-tournament__item > div {
        padding: 1.5rem;
    }

    .dsgw-tournament__item .dsgw-c-competition .dsgw-c-competition__date {
        font-family: Arial, sans-serif;
        font-weight: 600;
        font-size: 15px;
    }

    .dsgw-tournament__item .dsgw-c-competition .dsgw-c-competition__name {
        font-family: Arial, sans-serif;
        font-weight: bold;
        font-size: 30px;
    }

    .dsgw-leaderboard__body-player {
        position: relative;
    }

    .dsgw-golf-tournaments-widget .dsgw-leaderboard__body-player > div:first-child {
        text-align: left;
        padding-left: 20px;
    }

    .dsgw-current {
        outline: 2px solid red;
    }

    .dsgw-current::before {
        content: "";
        background: red;
        font-weight: bold;
        position: absolute;
        width: 6px;
        height: 6px;
        border-radius: 50px;
        top: 15px;
        left: 15px;
    }

    .dsgw-bogey,
    .dsgw-double-bogey,
    .dsgw-eagle,
    .dsgw-bridie {
        color: #fff;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 24px;
        height: 24px;
    }

    .dsgw-eagle,
    .dsgw-bridie {
        border-radius: 50%;
    }

    .dsgw-eagle {
        background: #f7ce16;
        color: #041217;
    }

    .dsgw-bridie {
        background: #f2382e;
    }

    .dsgw-double-bogey {
        background: #3dcaf5;
    }

    .dsgw-bogey {
        background: #041217;
    }

    .dsgw-tournament__item .dsgw-c-previous-winner-item {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-prize-item {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dsgw-golf-tournaments-widget .dsgw-arrow-container {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        border: none !important;
    }

    .dsgw-golf-tournaments-widget .dsgw-arrow::before,
    .dsgw-golf-tournaments-widget .dsgw-arrow-blue::before {
        content: "";
        display: inline-block;
        width: 5px;
        height: 5px;
        border: solid grey;
        border-width: 0 2px 2px 0;
        transform: rotate(-45deg);
    }

    .dsgw-golf-tournaments-widget .dsgw-arrow-blue {
        padding: 20px;
    }

    .dsgw-golf-tournaments-widget .dsgw-arrow-blue::before {
        border: solid #0070F0;
        border-width: 0 2px 2px 0;
    }

    .dsgw-tournament__item--wrap.dsgw-show .dsgw-tournament__item .dsgw-arrow::before,
    .dsgw-leaderboard__body-player.dsgw-g-player-active .dsgw-arrow-blue::before {
        transform: rotate(45deg);
    }

    .dsgw-element {
        display: none;
        margin-top: 3.13rem;
    }

    .dsgw-show .dsgw-element {
        display: block;
    }

    .dsgw-golf-tournaments-widget .dsgw-leaderboard {
        margin-bottom: 20px;
    }

    .dsgw-golf-tournaments-widget .dsgw-c-nav-round::after {
        content: "";
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 0;
        width: 1px;
        height: 15px;
        background-color: #ccc;
    }

    .dsgw-g-rank,
    .dsgw-g-l-heading {
        width: 50px;
        padding-left: 20px;
        box-sizing: border-box;
        text-align: left;
    }

    .dsgw-g-player-name {
        white-space: nowrap;
    }

    .dsgw-golf-tournaments-widget .dsgw-simplebar-container .simplebar-content {
        display: flex;
        flex-direction: row;
        width: 100%;
        overflow-x: hidden;
    }

    .dsgw-golf-tournaments-widget .dsgw-simplebar-container {
        width: 100%;
        height: 50px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 50px;
    }

    .dsgw-golf-tournaments-widget .dsgw-simplebar-container .dsgw-simplebar-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 15px;
        cursor: pointer;
        z-index: 10;
    }

    .dsgw-golf-tournaments-widget .dsgw-simplebar-container .dsgw-simplebar-wrapper {
        display: grid;
        align-items: center;
        grid-template-columns: 40px 1fr 30px;
        border-top: 1px solid #EEE;
        border-bottom: 1px solid #EEE;
        width: 100%;
        max-width: 100%;
        overflow: auto;
    }

    .dsgw-golf-tournaments-widget .dsgw-simplebar-horizontal {
        display: flex;
    }

    .dsgw-golf-tournaments-widget .dsgw-simplebar-item {
        flex: 0 0 auto;
        max-width: 200px;
        padding: 0 2.75rem;
        display: flex;
        align-items: center;
        position: relative;
        height: 100%;
        white-space: nowrap;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        color: #737373;
        font-size: 16px;
    }

    .dsgw-golf-tournaments-widget .dsgw-simplebar-item::after {
        content: "";
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 0;
        width: 1px;
        height: 15px;
        background-color: #ccc;
    }

    .dsgw-golf-tournaments-widget .dsgw-simplebar-item.is-selected {
        border-image: linear-gradient(90deg, #0070F0 -12.5%, #01F4F5 111.11%) 1;
        color: black;
        font-weight: bold;
    }

    .dsgw-golf-tournaments-widget .dsgw-simplebar-item.is-selected > span {
        font-weight: bold;
        background: linear-gradient(90deg, #0070F0 -12.5%, #01F4F5 111.11%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .dsgw-g-results {
        width: 100%;
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-detail-data {
        grid-template-columns: 1fr;
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-detail-data .dsgw-detail-data__item {
        flex-direction: row;
        align-items: flex-end;
        justify-content: space-between;
        padding-block: 10px;
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-detail-data .dsgw-detail-data__item.dsgw-c-previous-winner {
        padding-bottom: 40px;
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-detail-data .dsgw-c-prize-item,
    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-detail-data .dsgw-c-previous-winner-item {
        flex: 1;
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-tournament__item .dsgw-arrow-container {
        top: initial;
        bottom: 0;
        left: 50%;
        right: initial;
        transform: translateX(-50%);
        padding: 10px;
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-tournament__item .dsgw-arrow-container .dsgw-arrow::before {
        transform: rotate(45deg);
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-tournament__item--wrap.dsgw-show .dsgw-arrow-container .dsgw-arrow::before {
        transform: rotate(-135deg);
    }

    .dsgw-golf-tournaments-widget.dsgw-mb .dsgw-player-group .dsgw-player-item {
        justify-content: start;
    }
</style>
