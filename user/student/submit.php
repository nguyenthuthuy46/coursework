<?php
session_start();
include "../../connect_db.php";

$userId = $_SESSION["current_user"]["u_id"];


$idTopic = $_GET["idt"];

$topic = $conn->query("SELECT * from topic where id = '$idTopic' ");
$topicSubmit = mysqli_fetch_assoc($topic);
$userInfor = $conn->query("SELECT faculty.*,user.* from faculty INNER JOIN user ON faculty.f_id = user.faculty_id where u_id = '$userId' ");
$userInforFaculty = mysqli_fetch_assoc($userInfor);

// while ($rowInfor = mysqli_fetch_array($userInfor)) {
//     $userInforFaculty[] = $rowInfor;
// }

//$file = $conn->query("SELECT * from file_submit_to_topic where file_userId_uploaded = '$userId' AND `file_topic_uploaded` = '$idTopic' AND `id` = (SELECT MAX(id) from `file_submit_to_topic` where `file_userId_uploaded` = '$userId' ) ");
$file = $conn->query("SELECT * from file_submit_to_topic where file_userId_uploaded = '$userId' AND `file_topic_uploaded` = '$idTopic' ORDER BY id DESC LIMIT 1 ");
if ($file == true) {
    $fileSubmit = mysqli_fetch_assoc($file);
}


$selected_date = ($topicSubmit["topic_deadline"]);
// echo $selected_date, "a ";
$duration = 14;
$duration_type = 'day';
$deadline = date('Y/m/d H:i:s', strtotime($selected_date . ' +' . $duration . ' ' . $duration_type));
// echo $deadline;




$current = new DateTime("now", new DateTimeZone('Asia/Ho_Chi_Minh'));
$timeSubmitFile = $current->format('Y/m/d H:i:s');
$diff = abs(strtotime($deadline) - strtotime($timeSubmitFile));
$years = floor($diff / (365 * 60 * 60 * 24));
$months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
$days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
$hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
$minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
$seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));

if ($years != 0) {
    $checkTimeSubmit = $years . " years, " . $months . " months, " . $days . " days, " . $hours . " hours, " . $minutes . " minutes, " . $seconds . " seconds";
} else if ($months != 0) {
    $checkTimeSubmit = $months . " months, " . $days . " days, " . $hours . " hours, " . $minutes . " minutes, " . $seconds . " seconds";
} else {
    $checkTimeSubmit =  $days . " days, " . $hours . " hours, " . $minutes . " minutes, " . $seconds . " seconds";
}



?>




<?php
if (isset($_POST['uploadFile'])) {

    $count = 0;
    $res = mysqli_query($conn, "SELECT * from file_submit_to_topic where file_submit_to_topic.file_userId_uploaded = '$userId' AND file_submit_to_topic.file_topic_uploaded = '$idTopic'") or die(mysqli_error($link));
    $count = mysqli_num_rows($res);
    var_dump($count);
    if ($count > 0) {
        $delete_query = $conn->query("DELETE FROM file_submit_to_topic where file_submit_to_topic.file_userId_uploaded = '$userId' AND file_submit_to_topic.file_topic_uploaded = '$idTopic' ");
        var_dump($delete_query);
    }


    $upload_query = $conn->query("INSERT INTO `file_submit_to_topic` (`id`, `file_name`, `file_authod`, `file_status`, `file_date_uploaded`,  `file_topic_uploaded`, `file_userId_uploaded`) VALUES (NULL, '$_POST[nameArticle]', '$_POST[nameAuthor]', '1', '" . $timeSubmitFile . "', '$idTopic', '$userId')");
    $tm = md5(time());
    $uploadPath = "./file_library/";

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';

    $fileNames = array_filter($_FILES['inputFileArticle']['name']);

    if (!empty($fileNames)) {
        foreach ($_FILES['inputFileArticle']['name'] as $key => $val) {
            $fileName =  $tm . basename($_FILES['inputFileArticle']['name'][$key]);
            $targetFilePath = $uploadPath . $fileName;


            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {

                if (move_uploaded_file($_FILES["inputFileArticle"]["tmp_name"][$key], $targetFilePath)) {


                    $insertId = $conn->insert_id;
                    $insertValuesSQL .= "(NULL, ' $insertId', '" . $fileName . "', '" . time() . "')";
                    if ($key != count($_FILES["inputFileArticle"]["tmp_name"]) - 1) {
                        $insertValuesSQL .=  ",";
                    }
                } else {
                    $errorUpload .= $_FILES['inputFileArticle']['name'][$key] . ' | ';
                }
            } else {
                $errorUploadType .= $_FILES['inputFileArticle']['name'][$key] . ' | ';
            }
        }

        if (!empty($insertValuesSQL)) {

            $insertFileSubmit = $conn->query("INSERT INTO `file_content` (`file_content_id`, `file_submit_id`, `file_content_name`, `file_content_update_name`) VALUES $insertValuesSQL");
            if ($insertFileSubmit) {
                $errorUpload = !empty($errorUpload) ? 'Upload Error: ' . trim($errorUpload, ' | ') : '';
                $errorUploadType = !empty($errorUploadType) ? 'File Type Error: ' . trim($errorUploadType, ' | ') : '';
                $errorMsg = !empty($errorUpload) ? '<br/>' . $errorUpload . '<br/>' . $errorUploadType : '<br/>' . $errorUploadType;
                $statusMsg = "Files are uploaded successfully." . $errorMsg;
            } else {
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $statusMsg = 'Please select a file to upload.';
    }

    // Display status message 
    echo $statusMsg;



    if ($upload_query == true) {

?>
        <script>
            window.location.replace("./submit.php?idt=<?= $idTopic ?>")
        </script>
<?php

    }
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta content="" name="author" />
<meta content="atlas is Bootstrap 4 based admin panel.It comes with 100's widgets,charts and icons" name="description" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:title" content="atlas is Bootstrap 4 based admin panel.It comes with 100's widgets,charts and icons" />
<meta property="og:description" content="atlas is Bootstrap 4 based admin panel.It comes with 100's widgets,charts and icons" />
<meta property="og:image" content="https://cdn.dribbble.com/users/180706/screenshots/5424805/the_sceens_-_mobile_perspective_mockup_3_-_by_tranmautritam.jpg" />
<meta property="og:site_name" content="atlas " />
<title>Home Page</title>
<link rel="icon" type="image/x-icon" href="assets/img/logo.png" />
<link rel="icon" href="assets/img/logo.png" type="image/png" sizes="16x16">
<link rel='stylesheet' href='https://d33wubrfki0l68.cloudfront.net/css/478ccdc1892151837f9e7163badb055b8a1833a5/light/assets/vendor/pace/pace.css' />
<script src='https://d33wubrfki0l68.cloudfront.net/js/3d1965f9e8e63c62b671967aafcad6603deec90c/light/assets/vendor/pace/pace.min.js'></script>
<!--vendors-->
<link rel='stylesheet' type='text/css' href='https://d33wubrfki0l68.cloudfront.net/bundles/291bbeead57f19651f311362abe809b67adc3fb5.css' />
<link rel='stylesheet' href='https://d33wubrfki0l68.cloudfront.net/bundles/fc681442cee6ccf717f33ccc57ebf17a4e0792e1.css' />


<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,600" rel="stylesheet">
<!--Material Icons-->
<link rel='stylesheet' type='text/css' href='https://d33wubrfki0l68.cloudfront.net/css/548117a22d5d22545a0ab2dddf8940a2e32c04ed/default/assets/fonts/materialdesignicons/materialdesignicons.min.css' />
<!--Material Icons-->
<link rel='stylesheet' type='text/css' href='https://d33wubrfki0l68.cloudfront.net/css/0940f25997c8e50e65e95510b30245d116f639f0/light/assets/fonts/feather/feather-icons.css' />
<!--Bootstrap + atmos Admin CSS-->
<link rel='stylesheet' type='text/css' href='https://d33wubrfki0l68.cloudfront.net/css/16e33a95bb46f814f87079394f72ef62972bd197/light/assets/css/atmos.min.css' />
<!-- Additional library for page -->
<?php include '../partials/aside.php' ?>


<body class="sidebar-pinned ">
    <?php include '../partials/aside.php' ?>
    <main class="admin-main">
        <!-- Header -->
        <?php include '../partials/header.php' ?>
        <!-- Session -->

        <section class="admin-content">
            <div class="container m-t-30">
                <div class="card m-b-30">
                    <div class="card-header">
                        <br>
                        <br>
                        <h5>
                            This is topic <span style="color:red"> <?= $topicSubmit['topic_name'] ?></span>
                        </h5>
                        <p class="m-b-0 text-muted">
                        </p>
                    </div>
                    <?php
                    if (strtotime($timeSubmitFile) > strtotime($deadline)) { ?>
                        <h1 style="color:red">Qu?? h???n n???p b??i</h1>
                    <?php
                    } else {
                    ?>
                        <div class="card-body p-b-0">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td>Submission</td>
                                        <td><?php

                                            if ($fileSubmit == NULL) {
                                            ?>
                                                <span>Not Submitted</span>
                                            <?php
                                            } else {
                                            ?>
                                                <span>Submitted for grading</span>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Grading status</td>
                                        <td><?php

                                            if (($fileSubmit == NULL)) {
                                            ?>
                                                <span>Not Graded</span>
                                            <?php
                                            } else if (($fileSubmit["file_status"]) == "1") {
                                            ?>
                                                <span>Processing</span>
                                            <?php

                                            } else if (($fileSubmit["file_status"]) == "2") {
                                            ?>
                                                <span style="color:blue; font-size:16px">Approved</span>
                                            <?php

                                            } else if (($fileSubmit["file_status"]) == "3") {
                                            ?>
                                                <span style="color:red; font-size:16px">Rejected</span>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Deadline date</td>
                                        <td>
                                            <?php echo (!empty($topicSubmit["topic_deadline"]) ?  $deadline : "Not submited");
                                            ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Time remaining</td>
                                        <td> <?php echo (!empty($fileSubmit["file_date_uploaded"]) ? $checkTimeSubmit : "Not submited"); ?>
                                        </td>
                                    </tr>

                                    <!-- <tr>
                                    <td>Time submit</td>

                                    <td>
                                        <?= (!empty($fileSubmit["file_date_uploaded"]) ? date("d/m/Y H:i:s", $fileSubmit["file_date_uploaded"]) : "Not submited") ?>
                                    </td>
                                </tr> -->
                                    <tr>
                                        <td>Last modified</td>

                                        <td>
                                            <?php
                                            if (isset($fileSubmit["file_date_uploaded"]) && strtotime($fileSubmit["file_date_uploaded"]) != '-62169987208') {
                                            ?>
                                                <span><?= $fileSubmit["file_date_uploaded"] ?></span>
                                            <?php
                                            } else {
                                            ?>
                                                <span>Not update</span>
                                            <?php
                                            }


                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>File submited name</td>
                                        <td> <?php echo (!empty($fileSubmit["file_name"]) ? $fileSubmit["file_name"] : "Not submited"); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> Comment of report</td>
                                        <td>
                                            <?php
                                            $fileInfor = $conn->query("SELECT file_comment.*, user.*,file_submit_to_topic.* FROM (( user INNER JOIN file_comment ON file_comment.file_comment_user = user.u_id) INNER JOIN file_submit_to_topic ON file_submit_to_topic.id = file_comment.file_submited_id) WHERE `file_userId_uploaded` = '$userId' AND `file_topic_uploaded` = '$idTopic'");

                                            $commentContentFile = array();
                                            while ($rowCmt =  mysqli_fetch_array($fileInfor)) {
                                                $commentContentFile[] = $rowCmt;
                                            }
                                            foreach ($commentContentFile as $rowComment) {
                                            ?>
                                                <p>User cmt <?= $rowComment['role'], " ", $rowComment['fullname'] ?></p>

                                                <p><?= $rowComment['file_comment_content'] ?></p>
                                                <hr>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Submission comments</td>
                                        <td>
                                            <div class="input-group m-b-10">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">With textarea</span>
                                                </div>
                                                <textarea class="form-control" aria-label="With textarea" spellcheck="false"></textarea>
                                                <grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: 0px; left: 0px; pointer-events: none; z-index: 3;" class="cGcvT"></grammarly-extension>
                                            </div>
                                            <div class="button-comment float-right">
                                                <button class="btn btn-warning">Comments</button>
                                                <button class="btn btn-danger">Cancle</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- <?php
                                            if ($datediff < 0) {
                                            ?>
                                    <tr>
                                        <td></td>
                                        <td style="text-align: right">
                                            <div style="color: red">
                                                B???n ???? h???t h???n n???p b??i.
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                            }
                                ?> -->
                                </tbody>
                            </table>
                        </div>
                        <div class=" card-footer button-submit text-center">
                            <button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target=".modal-submit-artical">Add Submission
                            </button>
                            <!-- <?php
                                    if ($datediff > 0) {
                                    ?>
                            <button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target=".modal-submit-artical">Add Submission
                            </button>
                        <?php
                                    } else {
                        ?>
                            <button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target=".modal-submit-artical" disabled>Add Submission
                            </button>
                        <?php
                                    }
                        ?> -->
                        </div>

                        <!--                    <form method="post">-->
                        <!--                        <input type="submit" name="uploadFile" class="btn btn-primary" value="uploadFile" id="uploadFile" />-->
                        <!--                    </form>-->

                        <!-- Modal -->

                        <div class="modal fade modal-submit-artical" tabindex="-1" role="dialog" aria-labelledby="submissionModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="submissionModal">Upload Article</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="card-header">
                                                <p class="m-b-0 text-muted">
                                                    Students are need to provide complete information prior to submitting an
                                                    article.
                                                </p>
                                            </div>
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="inputName">Name of article</label>
                                                        <input type="text" class="form-control" id="inputName" name="nameArticle" placeholder="Name of article">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="inputAuthor">Author</label>
                                                        <input type="text" class="form-control" id="inputAuthor" name="nameAuthor" value="<?= $userInforFaculty["fullname"] ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div>
                                                        <p class=" font-secondary">File Uploads</p>
                                                        <div class="form-group">
                                                            <div>
                                                                <p class=" font-secondary">File Uploads</p>
                                                                <div class="input-group mb-3">
                                                                    <div class="custom-file" onload="GetFileInfo ()">
                                                                        <input type="file" class="custom-file-input" id="inputFile" name="inputFileArticle[]" multiple onchange="GetFileInfo ()">
                                                                        <label class="custom-file-label" for="inputFile">Choose file</label>
                                                                    </div>
                                                                </div>
                                                                <div id="info" style="margin-top:10px"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="gridCheck" name="agree">
                                                                <label class="form-check-label" for="gridCheck">
                                                                    I agree to the Terms and Conditions
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group  float-right">
                                                            <input type="submit" name="uploadFile" class="btn btn-primary" value="uploadFile" id="uploadFile"></input>
                                                            <!--                                    <button type="submit" value="Upload" name="uploadFile" class="btn btn-primary" value="uploadFile" id="uploadFile" onclick="onUploadI()">Submit</button>--> <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                Close
                                                            </button>
                                                        </div>
                                            </form>
                                        </div>
                                </div>
                            </div>
                        </div>
                    <?php

                    }
                    ?>

                </div>
        </section>
    </main>
    </section>

    <head>
        <script type="text/javascript">
            var mess = "";
            var count = 0;

            function GetFileInfo() {
                var fileInput = document.getElementById("inputFile");
                var message = "";
                if ('files' in fileInput) {
                    if (fileInput.files.length == 0) {
                        message = "Please browse for one or more files.";
                    } else {
                        for (var i = 0; i < fileInput.files.length; i++) {
                            count = count + 1;
                            message += "<br /><b>File" + count + "</b><br />";
                            var file = fileInput.files[i];
                            if ('name' in file) {
                                message += "Name of file: " + file.name + "<br />";
                            } else {
                                message += "Name of file: " + file.fileName + "<br />";
                            }
                            if ('size' in file) {
                                message += "Size of file: " + file.size + " bytes <br />";
                            } else {
                                message += "Size of file: " + file.fileSize + " bytes <br />";
                            }
                            if ('mediaType' in file) {
                                message += "Type: " + file.mediaType + "<br />";
                            }
                        }
                    }
                } else {
                    if (fileInput.value == "") {
                        message += "Please browse for one or more files!";
                        message += "<br />Use the Control or Shift key for multiple selection.";
                    } else {
                        message += "Your browser doesn't support the files property!";
                        message += "<br />The path of the selected file: " + fileInput.value;
                    }
                }
                mess = mess + message;
                var info = document.getElementById("info");
                info.innerHTML = mess;

            }
        </script>
    </head>

    <script src='https://d33wubrfki0l68.cloudfront.net/bundles/85bd871e04eb889b6141c1aba0fedfa1a2215991.js'></script>
    <!--page specific scripts for demo-->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-66116118-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-66116118-3');
    </script>

    <!--Additional Page includes-->
    <script src='https://d33wubrfki0l68.cloudfront.net/js/c36248babf70a3c7ad1dcd98d4250fa60842eea9/light/assets/vendor/apexchart/apexcharts.min.js'></script>
    <!--chart data for current dashboard-->
    <script src='https://d33wubrfki0l68.cloudfront.net/js/d678dabfdc5c3131d492af7ef517fbe46fbbd8e4/light/assets/js/dashboard-01.js'></script>


</body>

</html>