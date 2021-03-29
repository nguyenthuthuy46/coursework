<?php
include "../../config.php";
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

    <!-- PLACE CODE INSIDE THIS AREA -->
    <section class="admin-content">
        <div class="container m-t-30">
            <div class="row">
                <div class="col-12 m-b-20">
                    <h3> <i class="fe fe-zap"></i>Statistic System</h3>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card m-b-30">
                        <div class="card-body">
                            <div class="pb-2">
                                <div class="avatar avatar-lg">
                                    <div class="avatar-title bg-soft-primary rounded-circle">
                                        <i class="fe fe-user"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-muted text-overline m-0">Amount of User</p>
                                <h3 class="fw-400">400 Users</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card m-b-30">
                        <div class="card-body">
                            <div class="pb-2">
                                <div class="avatar avatar-lg">
                                    <div class="avatar-title bg-soft-primary rounded-circle">
                                        <i class="icon-placeholder fe fe-folder"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-muted text-overline m-0">Amount of contribution</p>
                                <h3 class="fw-400">40 Contributions</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card m-b-30">
                        <div class="card-body">
                            <div class="pb-2">
                                <div class="avatar avatar-lg">
                                    <div class="avatar-title bg-soft-primary rounded-circle">
                                        <i class="fe fe-file-text"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-muted text-overline m-0">Amount of article</p>
                                <h3 class="fw-400">80 Articles</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card m-b-30">
                        <div class="card-body">
                            <div class="pb-2">
                                <div class="avatar avatar-lg">
                                    <div class="avatar-title bg-soft-primary rounded-circle">
                                        <i class="fe fe-file"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-muted text-overline m-0">Amount of Approved Article</p>
                                <h3 class="fw-400">30 Articles</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 m-b-20">
                    <h3> <i class="fe fe-activity"></i> Chart</h3>
                </div>
                <div class="col-lg-6">

                    <div class="card m-b-30">
                        <div class="card-header">
                            <h3>Chart 1</h3>
                        </div>
                        <div class="card-body">
<!--                            Chart in here-->
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">

                    <div class="card m-b-30">
                        <div class="card-header">
                            <h3>Chart 2</h3>
                        </div>
                        <div class="card-body">
                            <!--                            Chart in here-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-b-30">
                        <div class="card-header">
                            <h3>Chart 3</h3>
                        </div>
                        <div class="card-body">
<!--                            Code in here-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
</body>