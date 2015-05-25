<?php
require_once "functions.php";
require_once "connection.php";
$dbh = new PDO($dsn, $username, $dbpassword, $options);
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$day = filter_input(INPUT_GET, 'day', FILTER_SANITIZE_NUMBER_INT);
if (empty($id)) {
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
        <link rel="stylesheet" href="/assets/css/tuktuk.css">
        <link rel="stylesheet" href="/assets/css/tuktuk.icons.css">
        <link rel="stylesheet" href="/assets/css/tuktuk.grid.css">
        <link href="/assets/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link href="/assets/css/jquery.bxslider.css" rel="stylesheet" />
        <link rel="stylesheet" href="/assets/css/style.css">
    </head>
    <body>
        <header class="bck">
            <div class="row">
                <div class="column_12">
                    <a href="http://internetsummitafrica.org/">
                        <h3 class="text bold">AIS'15 / AFRINIC-22 VOD</h3>
                    </a>
                </div>
            </div>
        </header>
        <?php if (empty($video)) { ?>
            <div class='row'>
                <div class='column_12 text center alert'>Video not found. Please come back later and try again!</div>
            </div>
        <?php } else { ?>
            <section class="main">
                <div class="row">
                    <div class="column_6 padding-top padding-bottom">
                        <div class="video">
                            <iframe src="<?php echo $url; ?>" frameborder="0"></iframe>
                        </div>
                    </div>
                    <div class="column_6 padding-top">
                        <table class="padding-top">
                            <tbody>
                                <tr>
                                    <td><span class="icon bookmark-empty"></span> Title</td>
                                    <td class="right"><?php echo $video["title"]; ?></td>
                                </tr>
                                <tr>
                                    <?php if (!empty($video["speaker"])) { ?>
                                        <td><span class="icon user"></span> Speaker</td>
                                        <td class="right">  <?php echo $video["speaker"]; ?></td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td><span class="icon bar-chart"></span> Views</td>
                                    <td class="right"><?php echo $video["views"]; ?><br/></td>
                                </tr>
                                <tr>
                                    <td><span class="icon calendar"></span> Date</td>
                                    <td class="right"> <?php echo date("d F Y", strtotime("2015-06-0" . $video['day'])); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if (!empty($video["presentation"])) { ?>
                            <div class="text center">
                                <a href="<?php echo $video["presentation"]; ?>" class="button"><span class="icon download"></span>  Download presentation</a>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="text center column_6 padding-top">
                                <ul class="social-buttons">
                                    <li><a class="resp-sharing-button__link" href="https://facebook.com/sharer/sharer.php?url=<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" target="_blank"><div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--small">
                                                <svg class="resp-sharing-button__icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1em" height="1em" viewBox="0 0 32 32">
                                                <path fill="#FFF" d="M26.667 0h-21.334c-2.945 0-5.333 2.388-5.333 5.334v21.332c0 2.946 2.387 5.334 5.333 5.334h10.667v-14h-4v-4h4v-3c0-2.761 2.239-5 5-5h5v4h-5c-0.552 0-1 0.448-1 1v3h5.5l-1 4h-4.5v14h6.667c2.945 0 5.333-2.388 5.333-5.334v-21.332c0-2.946-2.387-5.334-5.333-5.334z"></path>
                                                </svg>
                                            </div>
                                        </a></li>
                                    <li><a class="resp-sharing-button__link" href="https://twitter.com/intent/tweet/?text=<?php echo $video["title"]; ?>&url=<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" target="_blank"><div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--small">
                                                <svg class="resp-sharing-button__icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1em" height="1em" viewBox="0 0 32 32">
                                                <path fill="#FFF" d="M26.667 0h-21.333c-2.934 0-5.334 2.4-5.334 5.334v21.332c0 2.936 2.4 5.334 5.334 5.334h21.333c2.934 0 5.333-2.398 5.333-5.334v-21.332c0-2.934-2.399-5.334-5.333-5.334zM23.952 11.921c0.008 0.176 0.012 0.353 0.012 0.531 0 5.422-4.127 11.675-11.675 11.675-2.317 0-4.474-0.679-6.29-1.844 0.321 0.038 0.648 0.058 0.979 0.058 1.922 0 3.692-0.656 5.096-1.757-1.796-0.033-3.311-1.219-3.833-2.849 0.251 0.048 0.508 0.074 0.772 0.074 0.374 0 0.737-0.050 1.081-0.144-1.877-0.377-3.291-2.035-3.291-4.023 0-0.017 0-0.034 0-0.052 0.553 0.307 1.186 0.492 1.858 0.513-1.101-0.736-1.825-1.992-1.825-3.415 0-0.752 0.202-1.457 0.556-2.063 2.024 2.482 5.047 4.116 8.457 4.287-0.070-0.3-0.106-0.614-0.106-0.935 0-2.266 1.837-4.103 4.103-4.103 1.18 0 2.247 0.498 2.995 1.296 0.935-0.184 1.813-0.525 2.606-0.996-0.306 0.958-0.957 1.762-1.804 2.27 0.83-0.099 1.621-0.32 2.357-0.646-0.55 0.823-1.245 1.545-2.047 2.124z"></path>
                                                </svg>
                                            </div>
                                        </a></li>
                                    <li><a class="resp-sharing-button__link" href="https://plus.google.com/share?url=<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" target="_blank"><div class="resp-sharing-button resp-sharing-button--google resp-sharing-button--small">
                                                <svg class="resp-sharing-button__icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1em" height="1em" viewBox="0 0 32 32">
                                                <path fill="#FFF" d="M0.025 27.177c-0.008-0.079-0.014-0.158-0.018-0.238 0.004 0.080 0.011 0.159 0.018 0.238zM7.372 17.661c2.875 0.086 4.804-2.897 4.308-6.662s-3.231-6.787-6.106-6.873c-2.876-0.085-4.804 2.796-4.308 6.562 0.496 3.765 3.23 6.887 6.106 6.973zM32 8v-2.666c0-2.934-2.399-5.334-5.333-5.334h-21.333c-2.884 0-5.25 2.32-5.33 5.185 1.824-1.606 4.354-2.947 6.965-2.947 2.791 0 11.164 0 11.164 0l-2.498 2.113h-3.54c2.348 0.9 3.599 3.629 3.599 6.429 0 2.351-1.307 4.374-3.153 5.812-1.801 1.403-2.143 1.991-2.143 3.184 0 1.018 1.93 2.75 2.938 3.462 2.949 2.079 3.904 4.010 3.904 7.233 0 0.513-0.064 1.026-0.19 1.53h9.617c2.934 0 5.333-2.398 5.333-5.334v-16.666h-6v6h-2v-6h-6v-2h6v-6h2v6h6zM5.809 23.936c0.675 0 1.294-0.018 1.936-0.018-0.848-0.823-1.52-1.831-1.52-3.074 0-0.738 0.236-1.448 0.567-2.079-0.337 0.024-0.681 0.031-1.035 0.031-2.324 0-4.297-0.752-5.756-1.995v2.101l0 6.304c1.67-0.793 3.653-1.269 5.809-1.269zM0.107 27.727c-0.035-0.171-0.061-0.344-0.079-0.52 0.018 0.176 0.045 0.349 0.079 0.52zM14.233 29.776c-0.471-1.838-2.139-2.749-4.465-4.361-0.846-0.273-1.778-0.434-2.778-0.444-2.801-0.030-5.41 1.092-6.882 2.762 0.498 2.428 2.657 4.267 5.226 4.267h8.951c0.057-0.348 0.084-0.707 0.084-1.076 0-0.392-0.048-0.775-0.137-1.148z"></path>
                                                </svg>
                                            </div>
                                        </a></li>
                                </ul>
                            </div>
                        </div>
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
                            <a href="/<?php echo $vid["id"]; ?>" <?php if ($vid['id'] == $id) echo "class='active'"; ?>>
                                <img class="thumbnail" title="<?php echo $vid['title']; ?>" src="<?php echo $vid["thumbnail"]; ?>" >
                                <div class="overlay"></div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class='row'><div class='column_12 text center alert'>Video list is empty. Please come back later and try again!</div></div>
            <?php } ?>
            <script src="/assets/js/jquery.min.js"></script>
            <script src="/assets/js/jquery.bxslider.min.js"></script>
            <script src="/assets/js/tuktuk.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('.slider').bxSlider({
                        slideWidth: 320,
                        minSlides: 3,
                        captions: true,
                        maxSlides: 5,
                        slideMargin: 20
                    });
                    $("html, body").animate({ scrollTop: $('#title1').offset().top }, 1000);
                 });
            </script>  <?php } ?>
    </body>
</html>
