<?php
require_once "functions.php";
require_once "connection.php";
$dbh = new PDO($dsn, $username, $dbpassword, $options);
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$day = filter_input(INPUT_GET, 'day', FILTER_SANITIZE_NUMBER_INT);
if (empty($id)){
    $id = default_by_date($day, $dbh);
    if (empty($id)) {
        $id = 1;
    }
}
$video = get_video_infos($id, $dbh);
if (empty($day)) {
    $day = $video['day'];
}
$url = getURL($video);
add_view($id, $dbh);
$vids = videos_by_date($day, $dbh);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $video["title"]; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="stylesheet" href="assets/css/tuktuk.css">
        <link rel="stylesheet" href="assets/css/tuktuk.icons.css">
        <link rel="stylesheet" href="assets/css/tuktuk.grid.css">
        <link href="assets/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link href="assets/css/jquery.bxslider.css" rel="stylesheet" />
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <header class="bck margin-bottom">
            <div class="row">
                <div class="column_6">
                    <a href="https://afrinic.net">
                        <img src="assets/img/logo.png" class="logo on-left">
                    </a>
                    <h3 class="text bold">AFRINIC 22 - VOD</h3>
                </div>
            </div>
        </header>
        <?php if (empty($video)) { ?>
            <div class='row'>
                <div class='column_12 text center alert'>Video not found. Please come back later and try again!</div>
            </div>
        <?php } else { ?>
            <section class="main">
                <div class="row margin-bottom">
                    <div class="column_6 padding-top padding-bottom">
                        <div class="video">
                            <iframe src="<?php echo $url; ?>" frameborder="0"></iframe>
                        </div>
                    </div>
                    <div class="column_6 padding-top padding-bottom">
                        <table class="padding-top">
                            <tbody>
                                <tr>
                                    <td class="highlight"><span class="icon bookmark-empty"></span> Title</td>
                                    <td class="right"><?php echo $video["title"]; ?></td>
                                </tr>
                                <tr>
                                    <?php if (!empty($video["speaker"])) { ?>
                                        <td class="highlight"><span class="icon user"></span> Speaker</td>
                                        <td class="right">  <?php echo $video["speaker"]; ?></td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td class="highlight"><span class="icon bar-chart"></span> Views</td>
                                    <td class="right"><?php echo $video["views"]; ?><br/></td>
                                </tr>
                                <tr>
                                    <td class="highlight"><span class="icon calendar"></span> Date</td>
                                    <td class="right"> <?php echo date("d F Y", strtotime("2015-06-0" . $video['day'])); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if (!empty($video["presentation"])) { ?>
                            <div class="text center">
                                <a href="<?php echo $video["presentation"]; ?>" class="button large"><span class="icon download"></span>  Download presentation</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
            <section class="slider-header">
                <div class="row text center">
                    <div class="column_12">
                        <nav data-tuktuk="menu" class="text bold">
                            <?php
                            for ($d = 1; $d <= 5; $d++) {
                                $date = "2015-06-0$d";
                                $theDate = date("M jS", strtotime($date));
                                if ($d == $day) {
                                    echo "<a href='index.php?day=$d' class='active'>$theDate<span class='tick'></span></a>";
                                } else {
                                    echo "<a href='index.php?day=$d'>$theDate</a>";
                                }
                            }
                            ?>
                        </nav>
                    </div>
                </div>

            </section>
            <?php if (count($vids) > 0) { ?>
                <div class="slider">
                    <?php foreach ($vids as $vid) { ?>
                        <div class="slide">
                            <a href="index.php?id=<?php echo $vid["id"]; ?>">
                                <img class="thumbnail" title="<?php echo $vid['title']; ?>" src="<?php echo $vid["thumbnail"]; ?>" >
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class='row'><div class='column_12 text center alert'>Video list is empty. Please come back later and try again!</div></div>
            <?php } ?>
            <script src="assets/js/jquery.min.js"></script>
            <script src="assets/js/jquery.bxslider.min.js"></script>
            <script src="assets/js/tuktuk.js"></script>
            <script>
                $(document).ready(function () {
                    $('.slider').bxSlider({
                        slideWidth: 320,
                        minSlides: 3,
                        captions: true,
                        maxSlides: 5,
                        slideMargin: 20
                    });
                });
            </script>  <?php } ?>
    </body>
</html>
