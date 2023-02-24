<!-- Daily Goal Series -->
<div class="dropdown static md:relative">

    <div class="mr-2 sm:mr-4 no-select text-lg relative hidden md:flex">
        <svg style="width: 18px; margin-top: -1px" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24">
            <path fill="{{$userData->userGoal->goal_reached == true ? '#3D96F2' : '#9CA3AF'}}" d="M8.46777,8.39453l-.00225.00183-.00214.00208ZM18.42188,8.208a1.237,1.237,0,0,0-.23-.17481.99959.99959,0,0,0-1.39941.41114,5.78155,5.78155,0,0,1-1.398,1.77734,8.6636,8.6636,0,0,0,.1333-1.50977,8.71407,8.71407,0,0,0-4.40039-7.582,1.00009,1.00009,0,0,0-1.49121.80567A7.017,7.017,0,0,1,7.165,6.87793l-.23047.1875a8.51269,8.51269,0,0,0-1.9873,1.8623A8.98348,8.98348,0,0,0,8.60254,22.83594.99942.99942,0,0,0,9.98,21.91016a1.04987,1.04987,0,0,0-.0498-.3125,6.977,6.977,0,0,1-.18995-2.58106,9.004,9.004,0,0,0,4.3125,4.0166.997.997,0,0,0,.71534.03809A8.99474,8.99474,0,0,0,18.42188,8.208ZM14.51709,21.03906a6.964,6.964,0,0,1-3.57666-4.40234,8.90781,8.90781,0,0,1-.17969-.96387,1.00025,1.00025,0,0,0-.79931-.84473A.982.982,0,0,0,9.77,14.80957a.99955.99955,0,0,0-.8667.501,8.9586,8.9586,0,0,0-1.20557,4.71777,6.98547,6.98547,0,0,1-1.17529-9.86816,6.55463,6.55463,0,0,1,1.562-1.458.74507.74507,0,0,0,.07422-.05469s.29669-.24548.30683-.2511a8.96766,8.96766,0,0,0,2.89874-4.63269,6.73625,6.73625,0,0,1,1.38623,8.08789,1.00024,1.00024,0,0,0,1.18359,1.418,7.85568,7.85568,0,0,0,3.86231-2.6875,7.00072,7.00072,0,0,1-3.2793,10.457Z"/>
        </svg>
        <span class="{{$userData->userGoal->goal_reached == true ? 'text-primary' : 'text-gray-500'}} font-semibold">{{$userData->userGoal->goal_reach_count}}</span>
    </div>

    <div class="hidden" id="userxp" userxp="{{$userData->userGoal->user_xp}}"></div>

    <!-- For large screens -->
    <div class="invisible hidden md:block">
        <div class="dropdown-menu absolute invisible z-10 goal-series-dropdown">
            <div class="bg-white mt-3 rounded border-2 border-solid border-gray-200" style="width: 340px;">

                <div class="border-2 border-solid rounded border-gray-200 absolute bg-white tr-left" style="width: 20px; height: 20px; margin: -11px auto 0 -10px; transform: rotate(45deg);"></div>
                <div class="absolute bg-white tr-left" style="width: 30px; height: 13px; margin-left: -15px"></div>

                <div class="p-3 mt-1">
                    <div class="grid grid-cols-12">
                        <div class="col-span-4 text-center">
                            <div class="mt-4" style="position: relative; display: inline-block; width: 85%">

                                <div class="relative p-0 bg-transparent w-full">
                                    <svg viewBox="0 0 36 36">
                                        <path
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                            fill="none"
                                            stroke="#E5E7EB"
                                            stroke-width="2"
                                            stroke-dasharray="100, 100"
                                        />
                                        <path
                                            class="dailyGoalPath"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                            fill="none"
                                            stroke="#3D96F2"
                                            stroke-width="2"
                                            stroke-dasharray="{{$userData->userGoal->percentage}}, 100"
                                        />
                                    </svg>
                                    <div class="absolute"
                                         style="top: 50%;left: 50%;transform: translateX(-50%) translateY(-50%);">
                                        <p class="text-4xl font-semibold {{$userData->userGoal->goal_reached == true ? 'text-primary' : 'text-gray-400'}} no-select">
                                            {{$userData->userGoal->goal_reach_count}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-span-8">
                            <h2 class="subheader text-gray-700">Daily goal series</h2>
                            <p class="text-gray-700 my-1">
                                Level:
                                <a href="{{route('settings.daily-goal')}}" class="cursor-pointer font-semibold level text-primary">
                                    {{$userData->userConfiguration->dailyGoal->level}}
                                </a>
                            </p>
                            <p class="text-gray-700 my-1">
                                Earn {{$userData->userConfiguration->dailyGoal->experience}} XP each day to build up your daily goal series!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
