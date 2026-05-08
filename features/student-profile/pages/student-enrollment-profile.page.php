<?php
require FEATURES_DIRECTORY . '/enrollment/EnrollmentService.php';
$pageTitle = "Enrollment Profile";

$enrollmentService = new EnrollmentService($connection);

$enrollment = $enrollmentService->getEnrollmenyByUserId(Auth::id());

if (!$enrollment || ($enrollment->status === 0 || $enrollment->status === -1)) {
    include FEATURES_DIRECTORY . '/student-profile/pages/application/enrollment-application.php';
} else {
    require FEATURES_DIRECTORY . '/enrollment/ClassProgramService.php';
    require FEATURES_DIRECTORY . '/users/UserService.php';

    $classProgramService = new ClassProgramService($connection);
    $userService = new UserService($connection);
    $subjects = $classProgramService->getStudentClassProgram(Auth::id());

    $user = $userService->getUserByEmail(Auth::email());
?>
    <?php
    $pageTitle = "Profile";
    ob_start();
    ?>
    <div class="container-xl px-4 mt-4">
        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="border rounded-4 p-3 h-100 bg-light">
                            <small class="text-muted d-block mb-1">
                                Full Name
                            </small>

                            <h4 class="fw-semibold mb-0">
                                <?= Auth::name() ?>
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded-4 p-3 h-100 bg-light">
                            <small class="text-muted d-block mb-1">
                                Student Number
                            </small>

                            <h4 class="fw-semibold mb-0">
                                <?= $user->profile->student_number ?>
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded-4 p-3 h-100 bg-light">
                            <small class="text-muted d-block mb-1">
                                Address
                            </small>

                            <h5 class="fw-normal mb-0">
                                <?= $user->profile->address ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded-4 p-3 h-100 bg-light">
                            <small class="text-muted d-block mb-1">
                                Birthdate
                            </small>

                            <h5 class="fw-normal mb-0">
                                <?= CommonHelper::getDateStringFormat($user->profile->date_of_birth) ?>
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Title</th>
                                <th>Day</th>
                                <th>Time</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if ($subjects) {
                                foreach ($subjects as $subject) {
                            ?>
                                    <tr>
                                        <td><?= $subject->subject_code ?></td>
                                        <td><?= $subject->subject_title ?></td>
                                        <td><?= $subject->day ?></td>
                                        <td><?= $subject->time ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
<?php
    $content = ob_get_clean();
    include INCLUDES_DIRECTORY . '/layouts/protected_layout.php';
}
?>
