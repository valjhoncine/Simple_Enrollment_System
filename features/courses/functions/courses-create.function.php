<?php

require FEATURES_DIRECTORY . '/courses/CourseService.php';

const COURSES_VALIDATION_ERRORS = "COURSES_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(COURSES_VALIDATION_ERRORS);

$courseService = new CourseService($connection);

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] === 'course') {
    $request = $_POST;

    $course_code = trim($request["course_code"]);
    $course_title = trim($request["course_title"]);

    if ($course_code == "") {
        $errors["course_code"][] = "Course code is required.";
    }
    if ($course_title == "") {
        $errors["course_title"][] = "Course title is required.";
    }

    $result = $courseService->getCourseByCode($course_code);
    if ($result) {
        $errors["course_code"][] = "Course code not available, please enter a different course code.";
    }

    if (!empty($errors)) {
        $_SESSION[COURSES_VALIDATION_ERRORS] = $errors;
        $_SESSION[OLD_FORM_VAL] = [
            "course_code" => $course_code,
            "course_title" => $course_title,
        ];
        navigateTo($routes, "courses-create");
    }

    $result = $courseService->saveCourse($course_code, $course_title);
    if ($result) {
        $_SESSION[INSERT_SUCCESS] = "New course created successfully.";
        navigateTo($routes, "courses-create");
    } else {
        $_SESSION[INSERT_FAILED] = "An error occurred failed to create course, please try again.";
        navigateTo($routes, "courses-create");
    }
}
