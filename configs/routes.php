<?php

define("ROUTE_PUBLIC", "PUBLIC");
define("ROUTE_PROTECTED", "PRIVATE");

class Route
{
    private $url;
    private $path;
    private $middleware;
    private $meta;

    public function __construct($url, $path, $middleware, $meta = "")
    {
        $this->url = $url;
        $this->path = $path;
        $this->middleware = $middleware;
        $this->meta = $meta;
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
                ROUTE_PROTECTED
            ),
            'dashboard' => new Route(
                $baseUrl .  '/dashboard',
                $featuresDirectory . '/dashboard/dashboard.page.php',
                ROUTE_PROTECTED,
                "dashboard"
            ),
            'users' => new Route(
                $baseUrl .  '/users',
                $featuresDirectory . '/users/pages/users.page.php',
                ROUTE_PROTECTED,
                "users"
            ),
            'users-create' => new Route(
                $baseUrl .  '/users/create',
                $featuresDirectory . '/users/pages/users-create.page.php',
                ROUTE_PROTECTED,
                "users"
            ),
            'courses' => new Route(
                $baseUrl .  '/courses',
                $featuresDirectory . '/courses/pages/courses.page.php',
                ROUTE_PROTECTED,
                "courses"
            ),
            'courses-create' => new Route(
                $baseUrl .  '/courses/create',
                $featuresDirectory . '/courses/pages/courses-create.page.php',
                ROUTE_PROTECTED,
                "courses"
            ),
            'courses-edit' => new Route(
                $baseUrl .  '/courses/edit',
                $featuresDirectory . '/courses/pages/courses-edit.page.php',
                ROUTE_PROTECTED,
                "courses"
            ),
            'subjects' => new Route(
                $baseUrl .  '/subjects',
                $featuresDirectory . '/subjects/pages/subjects.page.php',
                ROUTE_PROTECTED,
                "subjects"
            ),
            'subjects-create' => new Route(
                $baseUrl .  '/subjects/create',
                $featuresDirectory . '/subjects/pages/subjects-create.page.php',
                ROUTE_PROTECTED,
                "subjects"
            ),
            'subjects-edit' => new Route(
                $baseUrl .  '/subjects/edit',
                $featuresDirectory . '/subjects/pages/subjects-edit.page.php',
                ROUTE_PROTECTED,
                "subjects"
            ),
            'schedules' => new Route(
                $baseUrl .  '/schedules',
                $featuresDirectory . '/schedules/pages/schedules.page.php',
                ROUTE_PROTECTED,
                "subject Schedules"
            ),
            'schedules-create' => new Route(
                $baseUrl .  '/schedules/create',
                $featuresDirectory . '/schedules/pages/schedules-create.page.php',
                ROUTE_PROTECTED,
                "subject Schedules"
            ),
            'schedules-edit' => new Route(
                $baseUrl .  '/schedules/edit',
                $featuresDirectory . '/schedules/pages/schedules-edit.page.php',
                ROUTE_PROTECTED,
                "subject Schedules"
            ),
        ];
    }
}
define('BASE_URL', env()["BASE_URL"]);
