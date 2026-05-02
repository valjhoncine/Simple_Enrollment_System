<?php
require FEATURES_DIRECTORY . '/courses/CourseService.php';

if ($_SERVER['REQUEST_METHOD'] === HTTP_GET && isset($_GET['action']) && $_GET['action'] === 'courses') {

    $courseService = new CourseService($connection);
    $courses = $courseService->getCourses();

    apiResponse(true, $courses);
}
