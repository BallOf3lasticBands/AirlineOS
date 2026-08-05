<?php
require_once __DIR__ . '/../includes/config.php';
$require_login = true; // protect boarding pass pages for logged-in users
require_once __DIR__ . '/../includes/auth.php';

$config_result = mysqli_query($db_connect, 'SELECT * FROM config LIMIT 1');
$config_data = mysqli_fetch_assoc($config_result);
mysqli_free_result($config_result);

$boarding_id = isset($_GET['bp']) ? (int) $_GET['bp'] : 0;
$query = "SELECT * FROM boardingpass WHERE id = {$boarding_id}";
$result = mysqli_query($db_connect, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($db_connect));
}

$boardingpass_data = mysqli_fetch_assoc($result);
$flight_data = null;
$flight_result = false;

if ($boardingpass_data) {
    $flightnr = (int)$boardingpass_data['flightnr'];
    $flight_query = "SELECT * FROM flight WHERE id = {$flightnr}";
    $flight_result = mysqli_query($db_connect, $flight_query);

    if (!$flight_result) {
        die('Flight query failed: ' . mysqli_error($db_connect));
    }

    $flight_data = mysqli_fetch_assoc($flight_result);
}

mysqli_free_result($result);
if ($flight_result) {
    mysqli_free_result($flight_result);
}

$page_title = 'Boarding Pass';
$extra_head = '<style>' .
'    .cardWrap { width: 100%; margin: 3em auto; color: #fff; font-family: sans-serif; }' .
'    .card { background: linear-gradient(to bottom, #e84c3d 0%, #e84c3d 20%, #ecedef 20%, #ecedef 100%); height: 20em; float: left; position: relative; padding: 1em; margin-top: 100px; }' .
'    .cardLeft { border-top-left-radius: 8px; border-bottom-left-radius: 8px; width: 70%; }' .
'    .cardRight { width: 30%; border-left: 0.18em dashed #fff; border-top-right-radius: 8px; border-bottom-right-radius: 8px; }' .
'    .cardRight:before, .cardRight:after { content: ""; position: absolute; display: block; width: 0.9em; height: 0.9em; background: #fff; border-radius: 50%; left: -0.5em; }' .
'    .cardRight:before { top: -0.4em; }' .
'    .cardRight:after { bottom: -0.4em; }' .
'    h1 { font-size: 1.1em; margin-top: 0; }' .
'    h1 span { font-weight: normal; }' .
'    .title, .name, .seat, .time { text-transform: uppercase; font-weight: normal; }' .
'    .title h2, .name h2, .seat h2, .time h2 { font-size: 0.9em; color: #525252; margin: 0; }' .
'    .title span, .name span, .seat span, .time span { font-size: 0.7em; color: #a2aeae; }' .
'    .title { margin: 2em 0 0 0; }' .
'    .name, .seat { margin: 0.7em 0 0 0; }' .
'    .time { margin: 0.7em 0 0 1em; }' .
'    .seat, .time { float: left; }' .
'    .eye { position: relative; width: 2em; height: 1.5em; background: #fff; margin: 0 auto; border-radius: 1em/0.6em; z-index: 1; }' .
'    .eye:before, .eye:after { content: ""; display: block; position: absolute; border-radius: 50%; }' .
'    .eye:before { width: 1em; height: 1em; background: #e84c3d; z-index: 2; left: 8px; top: 4px; }' .
'    .eye:after { width: 0.5em; height: 0.5em; background: #fff; z-index: 3; left: 12px; top: 8px; }' .
'    .number { text-align: center; text-transform: uppercase; }' .
'    .number h3 { color: #e84c3d; margin: 0.9em 0 0 0; font-size: 2.5em; }' .
'    .number span { display: block; color: #a2aeae; }' .
'    .barcode { height: 2em; width: 0; margin: 1.2em 0 0 0.8em; box-shadow: 1px 0 0 1px #343434, 5px 0 0 1px #343434, 10px 0 0 1px #343434, 11px 0 0 1px #343434, 15px 0 0 1px #343434, 18px 0 0 1px #343434, 22px 0 0 1px #343434, 23px 0 0 1px #343434, 26px 0 0 1px #343434, 30px 0 0 1px #343434, 35px 0 0 1px #343434, 37px 0 0 1px #343434, 41px 0 0 1px #343434, 44px 0 0 1px #343434, 47px 0 0 1px #343434, 51px 0 0 1px #343434, 56px 0 0 1px #343434, 59px 0 0 1px #343434, 64px 0 0 1px #343434, 68px 0 0 1px #343434, 72px 0 0 1px #343434, 74px 0 0 1px #343434, 77px 0 0 1px #343434, 81px 0 0 1px #343434; }' .
'</style>';

require_once __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <?php if ($boardingpass_data): ?>
            <div class="cardWrap">
                <div class="card cardLeft">
                    <h1>Startup <span>Cinema</span></h1>
                    <div class="title">
                        <h2><?= htmlspecialchars($config_data['title'] ?? 'AirlineOS') ?></h2>
                        <span>id</span>
                    </div>
                    <div class="time">
                        <h2><?= htmlspecialchars($flight_data['flight_id'] ?? 'N/A') ?></h2>
                        <span>flightnr</span>
                    </div>
                    <div class="time">
                        <h2><?= htmlspecialchars($boardingpass_data['seat'] ?? 'N/A') ?></h2>
                        <span>seat</span>
                    </div>
                    <div class="name">
                        <h2><?= htmlspecialchars($boardingpass_data['passenger_first'] ?? '') ?> <?= htmlspecialchars($boardingpass_data['passenger_last'] ?? '') ?></h2>
                        <span>Boarding pass</span>
                    </div>
                    <div class="seat">
                        <h2><?= htmlspecialchars($flight_data['flight_id'] ?? 'N/A') ?></h2>
                        <span>Flight</span>
                    </div>
                    <div class="seat">
                        <h2><?= htmlspecialchars($boardingpass_data['dep_gate'] ?? 'N/A') ?></h2>
                        <span>gate</span>
                    </div>
                    <div class="time">
                        <h2><?= htmlspecialchars($flight_data['dep_date'] ?? 'N/A') ?></h2>
                        <span>date</span>
                    </div>
                    <div class="time">
                        <h2><?= htmlspecialchars($flight_data['dep_time'] ?? 'N/A') ?></h2>
                        <span>time</span>
                    </div>
                    <div class="time">
                        <h2><?= htmlspecialchars($flight_data['departure_airport'] ?? 'N/A') ?></h2>
                        <span>from</span>
                    </div>
                    <div class="time">
                        <h2><?= htmlspecialchars($flight_data['arrival_airport'] ?? 'N/A') ?></h2>
                        <span>to</span>
                    </div>
                </div>
                <div class="card cardRight">
                    <div class="eye"></div>
                    <div class="number">
                        <h3><?= htmlspecialchars($boardingpass_data['passenger_first'] ?? '') ?> <?= htmlspecialchars($boardingpass_data['passenger_last'] ?? '') ?></h3>
                        <span>passenger</span>
                    </div>
                    <div class="number">
                        <h3><?= htmlspecialchars($boardingpass_data['seat'] ?? 'N/A') ?></h3>
                        <span>seat</span>
                    </div>
                    <div class="number">
                        <h3><?= htmlspecialchars($boardingpass_data['bags'] ?? 'N/A') ?></h3>
                        <span>bags</span>
                    </div>
                    <div class="barcode"></div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">No boarding pass found for that ID.</div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
