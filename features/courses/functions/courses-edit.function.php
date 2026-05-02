<?php
require FEATURES_DIRECTORY . '/courses/CourseService.php';

const COURSES_VALIDATION_ERRORS = "COURSES_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(COURSES_VALIDATION_ERRORS);

$courseService = new CourseService($connection);

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] == 'course-update') {
    $request = $_POST;

    $course = $courseService->getCourseById(isset($_POST['id']) ? $_POST['id'] : 0);
    if (!$course) {
        apiResponse(false, null, [REQUEST_RESOURCE_NOT_FOUND => "Course not found."]);
    }

    $course_code = trim($request["course_code"]);
    $course_title = trim($request["course_title"]);

    if ($course_code == "") {
        $errors["course_code"][] = "Course code is required.";
    }
    if ($course_title == "") {
        $errors["course_title"][] = "Course title is required.";
    }

    if (!empty($errors)) {
        apiResponse(false, null, $errors);
    }
    $course->code = $course_code;
    $course->name = $course_title;
    try {
        $result = $courseService->updateCourse($course);
        if ($result) {
            apiResponse(true, $course);
        } else {
            throw new Exception(INSERT_FAILED);
        }
    } catch (Exception $ex) {
        if (str_contains($ex, "Duplicate entry")) {
            $errors["course_code"][] = "Course code not available.";
            apiResponse(false, null, $errors);
        }
        apiResponse(false, null, [INSERT_FAILED => "Cannot process request, an unexpected error occurred." . $ex]);
    }
}

$selectedCourse = null;
if ($_SERVER['REQUEST_METHOD'] === HTTP_GET) {
    if (!isset($_GET["id"]) || $_GET['id'] <= 1) {
        $_SESSION[REQUEST_RESOURCE_NOT_FOUND] = "Course not found.";
        navigateTo($routes, "courses");
    }
    $request = $_GET;

    $result = $courseService->getCourseById($request["id"]);
    if (!$result) {
        $_SESSION[REQUEST_RESOURCE_NOT_FOUND] = "Course not found.";
        navigateTo($routes, "courses");
    }
    $selectedCourse = $result;
}
