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