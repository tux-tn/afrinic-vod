<?php

function add_video($url, $title, $speaker, $thumbnail, $presentation, $day, $starttime, $endtime, $dbh) {
    $query = "INSERT INTO `video`(`url`,`title`,`speaker`,`thumbnail`,`presentation`,`day`,`starttime`,`endtime`) VALUES(:url, :title, :speaker, :thumbnail, :presentation, :day, :starttime, :endtime)";
    $st = $dbh->prepare($query);
    $st->bindValue('url', $url, PDO::PARAM_STR);
    $st->bindValue('title', $title, PDO::PARAM_STR);
    $st->bindValue('speaker', $speaker, PDO::PARAM_STR);
    $st->bindValue('thumbnail', $thumbnail, PDO::PARAM_STR);
    $st->bindValue('presentation', $presentation, PDO::PARAM_STR);
    $st->bindValue('day', $day, PDO::PARAM_STR);
    $st->bindValue('starttime', $starttime, PDO::PARAM_STR);
    $st->bindValue('endtime', $endtime, PDO::PARAM_STR);
    $st->execute();
    if ($st->errorCode() != "0000") {
        throw new Exception($st->errorInfo()[2]);
    }
}

function change_info($id, $url, $title, $speaker, $thumbnail, $presentation, $day, $starttime, $endtime, $dbh) {
    if (!empty($url)) {
        $query = "UPDATE `video` SET `url` = :url WHERE `id` = :id";
        $st = $dbh->prepare($query);
        $st->bindValue('url', $url, PDO::PARAM_STR);
        $st->bindValue('id', $id, PDO::PARAM_INT);
        $st->execute();
    }
    if (!empty($title)) {
        $query = "UPDATE `video` SET `title` = :title WHERE `id` = :id";
        $st = $dbh->prepare($query);
        $st->bindValue('title', $title, PDO::PARAM_STR);
        $st->bindValue('id', $id, PDO::PARAM_INT);
        $st->execute();
    }
    if (!empty($speaker)) {
        $query = "UPDATE `video` SET `speaker` = :speaker WHERE `id` = :id";
        $st = $dbh->prepare($query);
        $st->bindValue('speaker', $speaker, PDO::PARAM_STR);
        $st->bindValue('id', $id, PDO::PARAM_INT);
        $st->execute();
    }
    if (!empty($thumbnail)) {
        $query = "UPDATE `video` SET `thumbnail` = :thumbnail WHERE `id` = :id";
        $st = $dbh->prepare($query);
        $st->bindValue('thumbnail', $thumbnail, PDO::PARAM_STR);
        $st->bindValue('id', $id, PDO::PARAM_INT);
        $st->execute();
    }
    if (!empty($presentation)) {
        $query = "UPDATE `video` SET `presentation` = :presentation WHERE `id` = :id";
        $st = $dbh->prepare($query);
        $st->bindValue('presentation', $presentation, PDO::PARAM_STR);
        $st->bindValue('id', $id, PDO::PARAM_INT);
        $st->execute();
    }
    if (!empty($day)) {
        $query = "UPDATE `video` SET `day` = :day WHERE `id` = :id";
        $st = $dbh->prepare($query);
        $st->bindValue('day', $day, PDO::PARAM_INT);
        $st->bindValue('id', $id, PDO::PARAM_INT);
        $st->execute();
    }
    if (!empty($starttime)) {
        $query = "UPDATE `video` SET `starttime` = :starttime WHERE `id` = :id";
        $st = $dbh->prepare($query);
        $st->bindValue('starttime', $starttime, PDO::PARAM_INT);
        $st->bindValue('id', $id, PDO::PARAM_INT);
        $st->execute();
    }
    if (!empty($endtime)) {
        $query = "UPDATE `video` SET `endtime` = :endtime WHERE `id` = :id";
        $st = $dbh->prepare($query);
        $st->bindValue('endtime', $endtime, PDO::PARAM_INT);
        $st->bindValue('id', $id, PDO::PARAM_INT);
        $st->execute();
    }
}

function get_video_infos($id, $dbh) {
    $query = "SELECT * FROM `video` WHERE `id` = :id";
    $st = $dbh->prepare($query);
    $st->bindValue(':id', $id, PDO::PARAM_INT);
    $st->execute();
    $result = $st->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function videos_by_date($day, $dbh) {
    $query = "SELECT * FROM `video` WHERE `day` = :day";
    $st = $dbh->prepare($query);
    $st->bindValue(':day', $day, PDO::PARAM_STR);
    $st->execute();
    $result = $st->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function default_by_date($day, $dbh) {
    $query = "SELECT * FROM `video` WHERE `day` = :day ORDER BY `id` ASC";
    $st = $dbh->prepare($query);
    $st->bindValue(':day', $day, PDO::PARAM_STR);
    $st->execute();
    $result = $st->fetch(PDO::FETCH_ASSOC);
    return $result["id"];
}

function getURL($video) {
    $params = $video['url'] . "/?autoplay=1&showinfo=0";
    if (!empty($video["starttime"])) {
        $params .= "&start=" . $video["starttime"];
    }
    if (!empty($video["endtime"])) {
        $params .= "&end=" . $video["endtime"];
    }
    return "https://www.youtube.com/embed/" . $params;
}

function add_view($id, $dbh) {
    $query = "UPDATE `video` SET `views` = `views` + 1 WHERE `id` = :id";
    $st = $dbh->prepare($query);
    $st->bindValue(':id', $id, PDO::PARAM_INT);
    $st->execute();
}

function image_upload($index) {
    if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) {
        throw new Exception("Error sending file");
    }
    if ($_FILES[$index]['size'] > 4194304) {
        throw new Exception("File size is more than 4MB");
    }
    $ext = pathinfo($_FILES[$index]['name'], PATHINFO_EXTENSION);
    if (!in_array($ext, array('png', 'gif', 'jpg', 'jpeg'))) {
        throw new Exception("File is not a valid picture");
    }
    $destination = '/assets/img/' . uniqid("img_") . '.' . $ext;
    if (move_uploaded_file($_FILES[$index]['tmp_name'], filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING) . $destination)) {
        return $destination;
    } else {
        throw new Exception("Operation failed");
    }
}

function file_upload($index) {
    if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) {
        throw new Exception("Error sending file");
    }
    if ($_FILES[$index]['size'] > 16777216) {
        throw new Exception("File size is more than 16MB");
    }
    $ext = pathinfo($_FILES[$index]['name'], PATHINFO_EXTENSION);
    $destination = '/attachements/' . uniqid("prez_") . '.' . $ext;
    if (move_uploaded_file($_FILES[$index]['tmp_name'], filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING) . $destination)) {
        return $destination;
    } else {
        throw new Exception("Operation failed");
    }
}
