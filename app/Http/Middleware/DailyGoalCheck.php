<?php

namespace App\Http\Middleware;

use App\Models\User\UserGoal;
use Closure;
use Illuminate\Http\Request;

class DailyGoalCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(UserGoal::shouldBeReset()) UserGoal::reset();
        return $next($request);
    }
}
