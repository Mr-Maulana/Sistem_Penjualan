<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

if (! defined('SIGINT')) {
    define('SIGHUP', 1);
    define('SIGINT', 2);
    define('SIGQUIT', 3);
    define('SIGILL', 4);
    define('SIGTRAP', 5);
    define('SIGABRT', 6);
    define('SIGBUS', 7);
    define('SIGFPE', 8);
    define('SIGKILL', 9);
    define('SIGUSR1', 10);
    define('SIGSEGV', 11);
    define('SIGUSR2', 12);
    define('SIGPIPE', 13);
    define('SIGALRM', 14);
    define('SIGTERM', 15);
    define('SIGCHLD', 17);
    define('SIGCONT', 18);
    define('SIGSTOP', 19);
    define('SIGTSTP', 20);
    define('SIGUSR1_CUSTOM', 30);
}

if (! function_exists('mb_split')) {
    function mb_split(string $pattern, string $string, int $limit = -1): array|false {
        return @preg_split('{'.$pattern.'}u', $string, $limit);
    }
}

if (! function_exists('posix_kill')) {
    function posix_kill(int $processId, int $signal): bool {
        if ($processId <= 0) {
            return false;
        }
        if ($signal === 0) {
            $output = @shell_exec("tasklist /FI \"PID eq {$processId}\" /NH 2>NUL");
            return $output && str_contains($output, (string)$processId);
        }
        @shell_exec("taskkill /F /PID {$processId} 2>NUL");
        return true;
    }
}

if (! function_exists('posix_getpid')) {
    function posix_getpid(): int|false {
        return getmypid();
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
