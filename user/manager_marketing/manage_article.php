<?php
include "../../config.php";
$idTopic = $_GET['idt'];
$userFacultyId = $_SESSION["current_user"]["faculty_id"];
$userId = $_SESSION["current_user"]["u_id"];

// Perform query

/** @var TYPE_NAME $conn */
// $result = mysqli_query($conn, "SELECT * FROM file_submit_to_system WHERE file_faculty_id = $file_faculty_id");
// $file_submit_to_system = mysqli_fetch_assoc($result);
// $faculty = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM faculty WHERE f_id = $file_faculty_id"), MYSQLI_ASSOC);

$studentSb = array();
//$res = $conn->query("SELECT files.*, u.*,f.* FROM file_submit_to_topic as files INNER JOIN user as u ON files.file_userId_uploaded = u.u_id INNER JOIN faculty.f_id = user.faculty_id WHERE u.role = 'student' AND files.file_topic_uploaded = '$idTopic' ORDER BY id DESC LIMIT 1");
$result = $conn->query("SELECT file_submit_to_topic.*, user.*,faculty.* FROM file_submit_to_topic INNER JOIN user ON file_submit_to_topic.file_userId_uploaded = user.u_id INNER JOIN faculty ON faculty.f_id = user.faculty_id WHERE user.role = 'student' AND user.faculty_id = '$userFacultyId' AND file_submit_to_topic.file_topic_uploaded = '$idTopic'  ");


while ($rowSt = mysqli_fetch_array($result)) {
    $studentSb[] = $rowSt;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../partials/html_header.php"; ?>
</head>
<body class="sidebar-pinned ">
<?php include "../partials/aside.php"; ?>
<main class="admin-main">
    <?php include "../partials/header.php"; ?>

    <section class="manage-articles">
        <div class="container m-t-30">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h4>Manage Articles</h4>
                                </h4>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                        <input type="text" class="form-control" placeholder="Search">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group ">
                                                <select class="form-control " data-select2-id="1" tabindex="-1"
                                                        aria-hidden="true">
                                                    <option selected="" data-select2-id="3">Select status</option>
                                                    <option data-select2-id="16">Approved</option>
                                                    <option data-select2-id="17">Reject</option>
                                                    <option data-select2-id="16">Processing</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group ">
                                                <select class="form-control " data-select2-id="1" tabindex="-1"
                                                        aria-hidden="true">
                                                    <option selected="" data-select2-id="3">Select topic</option>
                                                    <option data-select2-id="16">Cup Cake</option>
                                                    <option data-select2-id="17">Donut</option>
                                                    <option data-select2-id="18">Eclair</option>
                                                    <option data-select2-id="19">Froyo</option>
                                                </select>
                                            </div>
                                        </div>
                                </div>
                            </div>

                        </div>
                        <div class="table-responsive p-t-10">
                            <table class="table table-bordered">
                                <thead class="thead" style="background-color: #F4F7FC; text-align: center;">
                                <tr style="color: black !important">
                                    <th style="color: black !important" scope="col">NO.</th>
                                    <th style="color: black !important" scope="col">AVATAR</th>
                                    <th style="color: black !important" scope="col">STUDENT OWNER</th>
                                    <th style="color: black !important" scope="col">EMAIL</th>
                                    <th style="color: black !important" scope="col">STATUS</th>
                                    <th style="color: black !important" scope="col">SUBMIT TIME</th>
                                    <th style="color: black !important" scope="col">ACTION</th>
                                </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                <?php
                                $stt = 1;
                                foreach ($studentSb as $stReport) {
                                    $a = $stReport['file_name'];
                                    $userId = $stReport['u_id'];
                                    $idFile = $stReport['id'];
                                    //                                    $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM user WHERE user.u_id = $userId"),MYSQLI_ASSOC );
                                    //                                    $value_file = mysqli_fetch_array($value, MYSQLI_ASSOC);
                                    //


                                    ?>
                                    <tr>
                                        <th scope="row"><?= $stt++ ?></th>
                                        <td><img class="avatar avatar-lg" style="border-radius: 50%" src="../../assets/img/users/user-1.jpg"></td>
                                        <td style="padding: 2.5%;"><?= $stReport["username"] ?></td>
                                        <td style="padding: 2.5%;"><?= $stReport["email"] ?></td>
                                        <td style="padding: 1.5%;">
                                            <?php

                                            if (($stReport["file_status"]) == "1") {
                                                ?>
                                                <span class="badge badge-secondary">Note Grade</span>
                                                </button>
                                                <?php

                                            } else if (($stReport["file_status"]) == "2") {
                                                ?>
                                                <span class="badge badge-success">Approved</span>
                                                <?php

                                            } else if (($stReport["file_status"]) == "3") {
                                                ?>
                                                <span class="badge badge-danger">Rejected</span>
                                                <?php
                                            }
                                            ?>

                                        </td>
                                        <td style="padding: 2.5%;"><?= $stReport["file_date_uploaded"] ?></td>

                                        <td style="padding: 1.5%; color:red"><a type="button" class="btn btn-primary" href="view_article.php?idfile=<?= $idFile ?>&idst=<?= $userId ?>">Select </a>
                                        </td>
                                    </tr>
                                    <?php
                                }

                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</main>
<link href="path/to/select2.min.css" rel="stylesheet" /> <br>
<script src="path/to/select2.min.js"></script>
<?php include "../partials/js_libs.php"; ?>
</body>
</html>