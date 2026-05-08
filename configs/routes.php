<?php

define("ROUTE_PUBLIC", "PUBLIC");
define("ROUTE_PROTECTED", "PRIVATE");

class Route
{
    private $url;
    private $path;
    private $middleware;
    private $meta;
    private $guard;

    public function __construct($url, $path, $middleware, $meta = "", $guard = [])
    {
        $this->url = $url;
        $this->path = $path;
        $this->middleware = $middleware;
        $this->meta = $meta;
        $this->guard = $guard;
    }

    public function url()
    {
        return $this->url;
    }
    public function path()
    {
        return $this->path;
    }
    public function middleware()
    {
        return $this->middleware;
    }
    public function meta()
    {
        return $this->meta;
    }
    public function guard()
    {
        return $this->guard;
    }
}

class Routes
{
    public static function get($baseUrl, $featuresDirectory)
    {
        return [
            'login' => new Route(
                $baseUrl .  '/',
                $featuresDirectory . '/authenticate/login.page.php',
                ROUTE_PUBLIC
            ),
            'register' => new Route(
                $baseUrl .  '/register',
                $featuresDirectory . '/authenticate/register.page.php',
                ROUTE_PUBLIC
            ),
            'logout' => new Route(
                $baseUrl .  '/logout',
                $featuresDirectory . '/authenticate/logout.function.php',
                ROUTE_PROTECTED,
                "logout",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK,
                    USER_FACULTY,
                    USER_STUDENT
                ]
            ),
            'dashboard' => new Route(
                $baseUrl .  '/dashboard',
                $featuresDirectory . '/dashboard/dashboard.page.php',
                ROUTE_PROTECTED,
                "dashboard",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK,
                    USER_FACULTY,
                    USER_STUDENT
                ]
            ),
            'users' => new Route(
                $baseUrl .  '/users',
                $featuresDirectory . '/users/pages/users.page.php',
                ROUTE_PROTECTED,
                "users",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK
                ]
            ),
            'users-create' => new Route(
                $baseUrl .  '/users/create',
                $featuresDirectory . '/users/pages/users-create.page.php',
                ROUTE_PROTECTED,
                "users",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK
                ]
            ),
            'courses' => new Route(
                $baseUrl .  '/courses',
                $featuresDirectory . '/courses/pages/courses.page.php',
                ROUTE_PROTECTED,
                "courses",
                [
                    USER_ADMINISTRATOR
                ]
            ),
            'courses-create' => new Route(
                $baseUrl .  '/courses/create',
                $featuresDirectory . '/courses/pages/courses-create.page.php',
                ROUTE_PROTECTED,
                "courses",
                [
                    USER_ADMINISTRATOR
                ]
            ),
            'courses-edit' => new Route(
                $baseUrl .  '/courses/edit',
                $featuresDirectory . '/courses/pages/courses-edit.page.php',
                ROUTE_PROTECTED,
                "courses",
                [
                    USER_ADMINISTRATOR
                ]
            ),
            'subjects' => new Route(
                $baseUrl .  '/subjects',
                $featuresDirectory . '/subjects/pages/subjects.page.php',
                ROUTE_PROTECTED,
                "subjects",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK
                ]
            ),
            'subjects-create' => new Route(
                $baseUrl .  '/subjects/create',
                $featuresDirectory . '/subjects/pages/subjects-create.page.php',
                ROUTE_PROTECTED,
                "subjects",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK
                ]
            ),
            'subjects-edit' => new Route(
                $baseUrl .  '/subjects/edit',
                $featuresDirectory . '/subjects/pages/subjects-edit.page.php',
                ROUTE_PROTECTED,
                "subjects",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK
                ]
            ),
            'schedules' => new Route(
                $baseUrl .  '/schedules',
                $featuresDirectory . '/schedules/pages/schedules.page.php',
                ROUTE_PROTECTED,
                "subject Schedules",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK
                ]
            ),
            'schedules-create' => new Route(
                $baseUrl .  '/schedules/create',
                $featuresDirectory . '/schedules/pages/schedules-create.page.php',
                ROUTE_PROTECTED,
                "subject Schedules",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK
                ]
            ),
            'schedules-edit' => new Route(
                $baseUrl .  '/schedules/edit',
                $featuresDirectory . '/schedules/pages/schedules-edit.page.php',
                ROUTE_PROTECTED,
                "subject Schedules",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK
                ]
            ),
            'student-enrollment-profile' => new Route(
                $baseUrl .  '/student-enrollment-profile',
                $featuresDirectory . '/student-profile/pages/student-enrollment-profile.page.php',
                ROUTE_PROTECTED,
                "Enrollment Profile",
                [
                    USER_STUDENT
                ]
            ),
            'enrollment' => new Route(
                $baseUrl .  '/enrollment',
                $featuresDirectory . '/enrollment/pages/enrollment.page.php',
                ROUTE_PROTECTED,
                "Enrollment Applications",
                [
                    USER_FACULTY
                ]
            ),
            'account' => new Route(
                $baseUrl .  '/account',
                $featuresDirectory . '/account/pages/account.page.php',
                ROUTE_PROTECTED,
                "account",
                [
                    USER_ADMINISTRATOR,
                    USER_CLERK,
                    USER_FACULTY,
                    USER_STUDENT
                ]
            ),
        ];
    }
}
define('BASE_URL', env()["BASE_URL"]);
