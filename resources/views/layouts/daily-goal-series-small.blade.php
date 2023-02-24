<!-- Daily Goal Series Small Screen -->
<div class="dropdown hidden static md:relative border-b" :class="{'block': dailyGoalOpened, 'hidden': ! dailyGoalOpened}">
    <div
        class="md:hidden md:invisible grid grid-cols-12 bg-white w-full rounded border-t border-solid border-gray-200 px-2 pb-3">
        <div class="col-span-4 text-center">
            <div class="mt-5" style="position: relative; display: inline-block; width: 85%; max-width: 120px !important;">
                <div class="relative p-0 bg-transparent w-full">
                    <svg viewBox="0 0 36 36">
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
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
                    <div class="absolute" style="top: 50%;left: 50%;transform: translateX(-50%) translateY(-50%);">
                        <p class="text-4xl font-semibold no-select {{$userData->userGoal->goal_reached == true ? 'text-primary' : 'text-gray-400'}}">{{$userData->userGoal->goal_reach_count}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-8 mt-5">
            <h2 class="subheader text-gray-700">Daily goal series</h2>
            <p class="text-gray-700 my-1">
                Level:
                <a href="{{route('settings.daily-goal')}}" class="cursor-pointer font-semibold level cursor-pointer text-primary">
                    {{$userData->userConfiguration->dailyGoal->level}}
                </a>
            </p>
            <p class="text-gray-700 my-1">
                Earn {{$userData->userConfiguration->dailyGoal->experience}} XP each day to build up your daily goal series!
            </p>
        </div>
    </div>
</div>
