<?php
require FEATURES_DIRECTORY . '/enrollment/EnrollmentService.php';
$pageTitle = "Enrollment Profile";

$enrollmentService = new EnrollmentService($connection);

$enrollment = $enrollmentService->getEnrollmenyByUserId(Auth::id());

if (!$enrollment || $enrollment->status === 0) {
    include FEATURES_DIRECTORY . '/student-profile/pages/application/enrollment-application.php';
} else {
    $class_program = $enrollmentService->getEnrollmentClassProgramByUserId(Auth::id());
    if (!$class_program) {
        // proceed 
    } else {
        navigateTo($routes, 'dashboard');
    }
}
