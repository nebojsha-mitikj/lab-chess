@php
    $userData = \App\Helpers\DailyGoalSeries::read();
    $user = \Illuminate\Support\Facades\Auth::user();
    $subscriptionStatus = $user->subscriptionStatus();
@endphp

<div class="w-full h-16"></div>

<nav x-data="{ open: false, dailyGoalOpened: false }" class="bg-white top-0 z-50 border-gray-200 fixed w-full" id="m-nav">
    <!-- Primary Navigation Menu - Large Screens -->
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">

                <!-- Logo -->
                <a href="/" class="py-1 flex justify-center align-center cursor-pointer no-select">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="isolation:isolate; margin-top: 1px" viewBox="0 0 800 800" width="18pt" height="18pt"><defs><clipPath id="_clipPath_H2tOL8BEhXhy6DwPmqaxqpb7G4LLHwyx"><rect width="800" height="800"/></clipPath></defs><g clip-path="url(#_clipPath_H2tOL8BEhXhy6DwPmqaxqpb7G4LLHwyx)"><path d=" M 689.483 5.25 L 597.375 5.25 C 582.841 5.25 571.058 17.033 571.058 31.567 L 571.058 110.517 L 492.108 110.517 L 492.108 31.567 C 492.108 17.033 480.327 5.25 465.792 5.25 L 334.208 5.25 C 319.673 5.25 307.892 17.033 307.892 31.567 L 307.892 110.517 L 229.106 110.517 L 229.106 31.567 C 229.106 17.033 217.323 5.25 202.789 5.25 L 110.517 5.25 C 95.983 5.25 84.2 17.033 84.2 31.567 L 84.2 321.05 L 189.467 373.683 C 189.467 453.177 186.933 529.938 167.74 636.85 L 632.26 636.85 C 613.067 529.938 610.533 452.172 610.533 373.683 L 715.8 321.05 L 715.8 31.567 C 715.8 17.033 704.017 5.25 689.483 5.25 Z  M 452.633 478.95 L 347.367 478.95 L 347.367 373.683 C 347.367 344.614 370.932 321.05 400 321.05 C 429.068 321.05 452.633 344.614 452.633 373.683 L 452.633 478.95 Z  M 689.483 689.483 L 110.517 689.483 C 95.983 689.483 84.2 701.266 84.2 715.8 L 84.2 768.433 C 84.2 782.967 95.983 794.75 110.517 794.75 L 689.483 794.75 C 704.017 794.75 715.8 782.967 715.8 768.433 L 715.8 715.8 C 715.8 701.266 704.017 689.483 689.483 689.483 Z " fill="rgb(61,150,242)"/></g></svg>
                    <p class="text-3xl text-gray-700 ml-0.5 mt-1 font-semibold ml-0.5">labchess</p>
                </a>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 md:-my-px md:ml-10 md:flex">

                    <a href="{{route('courses')}}" class="flex items-center px-1 cursor-pointer pt-1 font-medium leading-5 transition duration-75 ease-in-out {{ request()->routeIs('courses') ? 'focus:outline-none border-primary-light text-primary font-semibold' : 'focus:outline-none border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
{{--                        <svg style="width: 18px; margin-right: 4px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('courses') ? '#3D96F2' : '#9CA3AF' }}" d="M21.17,2.06A13.1,13.1,0,0,0,19,1.87a12.94,12.94,0,0,0-7,2.05,12.94,12.94,0,0,0-7-2,13.1,13.1,0,0,0-2.17.19,1,1,0,0,0-.83,1v12a1,1,0,0,0,1.17,1,10.9,10.9,0,0,1,8.25,1.91l.12.07.11,0a.91.91,0,0,0,.7,0l.11,0,.12-.07A10.9,10.9,0,0,1,20.83,16a1,1,0,0,0,1.17-1v-12A1,1,0,0,0,21.17,2.06ZM11,15.35a12.87,12.87,0,0,0-6-1.48c-.33,0-.66,0-1,0v-10a8.69,8.69,0,0,1,1,0,10.86,10.86,0,0,1,6,1.8Zm9-1.44c-.34,0-.67,0-1,0a12.87,12.87,0,0,0-6,1.48V5.67a10.86,10.86,0,0,1,6-1.8,8.69,8.69,0,0,1,1,0Zm1.17,4.15A13.1,13.1,0,0,0,19,17.87a12.94,12.94,0,0,0-7,2.05,12.94,12.94,0,0,0-7-2.05,13.1,13.1,0,0,0-2.17.19A1,1,0,0,0,2,19.21,1,1,0,0,0,3.17,20a10.9,10.9,0,0,1,8.25,1.91,1,1,0,0,0,1.16,0A10.9,10.9,0,0,1,20.83,20,1,1,0,0,0,22,19.21,1,1,0,0,0,21.17,18.06Z"/></svg>--}}
                        {{ __('Courses') }}
                    </a>

                    <a href="{{route('trainer')}}" class="flex items-center px-1 cursor-pointer pt-1 font-medium leading-5 transition duration-75 ease-in-out {{ request()->routeIs('trainer') || request()->routeIs('trainer.variant.index') ? 'focus:outline-none border-primary-light text-primary font-semibold' : 'focus:outline-none border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
{{--                        <svg style="width: 19px; margin-right: 4px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('trainer') || request()->routeIs('trainer.variant.index') ? '#3D96F2' : '#9CA3AF' }}" d="M21,11H19.93A8,8,0,0,0,13,4.07V3a1,1,0,0,0-2,0V4.07A8,8,0,0,0,4.07,11H3a1,1,0,0,0,0,2H4.07A8,8,0,0,0,11,19.93V21a1,1,0,0,0,2,0V19.93A8,8,0,0,0,19.93,13H21a1,1,0,0,0,0-2Zm-4,2h.91A6,6,0,0,1,13,17.91V17a1,1,0,0,0-2,0v.91A6,6,0,0,1,6.09,13H7a1,1,0,0,0,0-2H6.09A6,6,0,0,1,11,6.09V7a1,1,0,0,0,2,0V6.09A6,6,0,0,1,17.91,11H17a1,1,0,0,0,0,2Zm-5-2a1,1,0,1,0,1,1A1,1,0,0,0,12,11Z"/></svg>--}}
                        {{ __('Trainer') }}
                    </a>

                    <a href="{{route('leaderboard')}}" class="flex items-center px-1 cursor-pointer pt-1 font-medium leading-5 transition duration-75 ease-in-out {{ request()->routeIs('leaderboard') ? 'focus:outline-none border-primary-light text-primary font-semibold' : 'focus:outline-none border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
{{--                        <svg style="width: 19px; margin-right: 4px" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('leaderboard') ? '#3D96F2' : '#9CA3AF' }}" d="M20.87,17.25l-2.71-4.68A6.9,6.9,0,0,0,19,9.25a7,7,0,0,0-14,0,6.9,6.9,0,0,0,.84,3.32L3.13,17.25A1,1,0,0,0,4,18.75l2.87,0,1.46,2.46a1,1,0,0,0,.18.22,1,1,0,0,0,.69.28h.14a1,1,0,0,0,.73-.49L12,17.9l1.93,3.35a1,1,0,0,0,.73.48h.14a1,1,0,0,0,.7-.28.87.87,0,0,0,.17-.21l1.46-2.46,2.87,0a1,1,0,0,0,.87-.5A1,1,0,0,0,20.87,17.25ZM9.19,18.78,8.3,17.29a1,1,0,0,0-.85-.49l-1.73,0,1.43-2.48a7,7,0,0,0,3.57,1.84ZM12,14.25a5,5,0,1,1,5-5A5,5,0,0,1,12,14.25Zm4.55,2.55a1,1,0,0,0-.85.49l-.89,1.49-1.52-2.65a7.06,7.06,0,0,0,3.56-1.84l1.43,2.48Z"/></svg>--}}
                        {{ __('Leaderboard') }}
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden md:flex md:items-center md:ml-6">

                @include('layouts.daily-goal-series')

                <div class="dropdown static md:relative">
                    <div class="ml-0.5 no-select text-gray-500 flex relative">
                        <div>{{ Auth::user()->username }}</div>
                        <div class="ml-0.5 mt-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <div class="block invisible hidden md:block">
                        <div class="absolute  dropdown-menu invisible" style="left: -50px">
                            <div class="bg-white mt-3 rounded border-2 border-solid border-gray-200" style="width: 200px;">
                                <div class="border-2 border-solid rounded border-gray-200 absolute bg-white" style="width: 20px; height: 20px; left: 50%; margin: -11px auto 0 -10px; transform: rotate(45deg);"></div>

                                @if($subscriptionStatus !== 'paid')
                                    <a href="{{route('premium')}}"
                                       class="w-full block px-4 py-2 text-gray-600 hover:bg-gray-100 bg-white relative profileLink">
                                        <div class="flex align-center">
                                            <div>
                                                <img class="w-4 mr-2" src="{{\Illuminate\Support\Facades\URL::asset('images/icons/diamond.png')}}">
                                            </div>
                                            @if($subscriptionStatus === 'free')
                                                {{ __('Free Trial') }}
                                            @else
                                                {{ __('Premium') }}
                                            @endif
                                        </div>
                                    </a>
                                @endif

                                <a href="{{route('profile',['username' => Auth::user()->username])}}"
                                   class="w-full block px-4 py-2 text-gray-600 hover:bg-gray-100 bg-white relative profileLink">
                                    {{ __('My Profile') }}
                                </a>

                                <a href="{{route('settings.account')}}"
                                   class="w-full block px-4 py-2 text-gray-600 hover:bg-gray-100">
                                    {{ __('Settings') }}
                                </a>

                                <a href="{{route('support.contact')}}"
                                   class="w-full block px-4 py-2 text-gray-600 hover:bg-gray-100">
                                    {{ __('Help Me') }}
                                </a>

                                @if(Auth::user()->role === 'admin')
                                <a href="{{route('analytics.analytics')}}"
                                   class="w-full block px-4 py-2 text-gray-600 hover:bg-gray-100">
                                    {{ __('Analytics') }}
                                </a>
                                @endif

                                <a href="{{route('subscription')}}"
                                   class="w-full block px-4 py-2 text-gray-600 hover:bg-gray-100">
                                    {{ __('Subscription') }}
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="w-full block px-4 py-2 text-gray-600 hover:bg-gray-100" href="{{route('logout')}}" onclick="event.preventDefault();this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center md:hidden">

                <button @click="dailyGoalOpened = !dailyGoalOpened; open = false" class="mr-2 no-select text-lg relative flex align-center">
                    <svg style="width: 18px; margin-top: -1px" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24">
                        <path fill="{{$userData->userGoal->goal_reached == true ? '#3D96F2' : '#9CA3AF'}}" d="M8.46777,8.39453l-.00225.00183-.00214.00208ZM18.42188,8.208a1.237,1.237,0,0,0-.23-.17481.99959.99959,0,0,0-1.39941.41114,5.78155,5.78155,0,0,1-1.398,1.77734,8.6636,8.6636,0,0,0,.1333-1.50977,8.71407,8.71407,0,0,0-4.40039-7.582,1.00009,1.00009,0,0,0-1.49121.80567A7.017,7.017,0,0,1,7.165,6.87793l-.23047.1875a8.51269,8.51269,0,0,0-1.9873,1.8623A8.98348,8.98348,0,0,0,8.60254,22.83594.99942.99942,0,0,0,9.98,21.91016a1.04987,1.04987,0,0,0-.0498-.3125,6.977,6.977,0,0,1-.18995-2.58106,9.004,9.004,0,0,0,4.3125,4.0166.997.997,0,0,0,.71534.03809A8.99474,8.99474,0,0,0,18.42188,8.208ZM14.51709,21.03906a6.964,6.964,0,0,1-3.57666-4.40234,8.90781,8.90781,0,0,1-.17969-.96387,1.00025,1.00025,0,0,0-.79931-.84473A.982.982,0,0,0,9.77,14.80957a.99955.99955,0,0,0-.8667.501,8.9586,8.9586,0,0,0-1.20557,4.71777,6.98547,6.98547,0,0,1-1.17529-9.86816,6.55463,6.55463,0,0,1,1.562-1.458.74507.74507,0,0,0,.07422-.05469s.29669-.24548.30683-.2511a8.96766,8.96766,0,0,0,2.89874-4.63269,6.73625,6.73625,0,0,1,1.38623,8.08789,1.00024,1.00024,0,0,0,1.18359,1.418,7.85568,7.85568,0,0,0,3.86231-2.6875,7.00072,7.00072,0,0,1-3.2793,10.457Z"/>
                    </svg>
                    <span class="{{$userData->userGoal->goal_reached == true ? 'text-primary' : 'text-gray-500'}} font-semibold">{{$userData->userGoal->goal_reach_count}}</span>
                </button>

                <button @click="open = ! open; dailyGoalOpened = false" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @include('layouts.daily-goal-series-small')

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1 border-b shadow-sm">

            @if($subscriptionStatus !== 'paid')
                <x-responsive-nav-link class="flex items-center" :href="route('premium')" :active="request()->routeIs('premium')">
                    <img class="w-4 mr-2" src="{{\Illuminate\Support\Facades\URL::asset('images/icons/diamond.png')}}">
                    @if($subscriptionStatus === 'free')
                        {{ __('Free Trial') }}
                    @else
                        {{ __('Premium') }}
                    @endif
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link class="flex items-center" :href="route('courses')" :active="request()->routeIs('courses')">
                <svg style="width: 19px; margin-right: 3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('courses') ? '#3D96F2' : '#9CA3AF' }}" d="M21.17,2.06A13.1,13.1,0,0,0,19,1.87a12.94,12.94,0,0,0-7,2.05,12.94,12.94,0,0,0-7-2,13.1,13.1,0,0,0-2.17.19,1,1,0,0,0-.83,1v12a1,1,0,0,0,1.17,1,10.9,10.9,0,0,1,8.25,1.91l.12.07.11,0a.91.91,0,0,0,.7,0l.11,0,.12-.07A10.9,10.9,0,0,1,20.83,16a1,1,0,0,0,1.17-1v-12A1,1,0,0,0,21.17,2.06ZM11,15.35a12.87,12.87,0,0,0-6-1.48c-.33,0-.66,0-1,0v-10a8.69,8.69,0,0,1,1,0,10.86,10.86,0,0,1,6,1.8Zm9-1.44c-.34,0-.67,0-1,0a12.87,12.87,0,0,0-6,1.48V5.67a10.86,10.86,0,0,1,6-1.8,8.69,8.69,0,0,1,1,0Zm1.17,4.15A13.1,13.1,0,0,0,19,17.87a12.94,12.94,0,0,0-7,2.05,12.94,12.94,0,0,0-7-2.05,13.1,13.1,0,0,0-2.17.19A1,1,0,0,0,2,19.21,1,1,0,0,0,3.17,20a10.9,10.9,0,0,1,8.25,1.91,1,1,0,0,0,1.16,0A10.9,10.9,0,0,1,20.83,20,1,1,0,0,0,22,19.21,1,1,0,0,0,21.17,18.06Z"/></svg>
                {{ __('Courses') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link class="flex items-center" :href="route('trainer')" :active="request()->routeIs('trainer') || request()->routeIs('trainer.variant.index')">
                <svg style="width: 19px; margin-right: 3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('trainer') || request()->routeIs('trainer.variant.index') ? '#3D96F2' : '#9CA3AF' }}" d="M21,11H19.93A8,8,0,0,0,13,4.07V3a1,1,0,0,0-2,0V4.07A8,8,0,0,0,4.07,11H3a1,1,0,0,0,0,2H4.07A8,8,0,0,0,11,19.93V21a1,1,0,0,0,2,0V19.93A8,8,0,0,0,19.93,13H21a1,1,0,0,0,0-2Zm-4,2h.91A6,6,0,0,1,13,17.91V17a1,1,0,0,0-2,0v.91A6,6,0,0,1,6.09,13H7a1,1,0,0,0,0-2H6.09A6,6,0,0,1,11,6.09V7a1,1,0,0,0,2,0V6.09A6,6,0,0,1,17.91,11H17a1,1,0,0,0,0,2Zm-5-2a1,1,0,1,0,1,1A1,1,0,0,0,12,11Z"/></svg>
                {{ __('Trainer') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link class="flex items-center" :href="route('leaderboard')" :active="request()->routeIs('leaderboard')">
                <svg style="width: 19px; margin-right: 3px" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('leaderboard') ? '#3D96F2' : '#9CA3AF' }}" d="M20.87,17.25l-2.71-4.68A6.9,6.9,0,0,0,19,9.25a7,7,0,0,0-14,0,6.9,6.9,0,0,0,.84,3.32L3.13,17.25A1,1,0,0,0,4,18.75l2.87,0,1.46,2.46a1,1,0,0,0,.18.22,1,1,0,0,0,.69.28h.14a1,1,0,0,0,.73-.49L12,17.9l1.93,3.35a1,1,0,0,0,.73.48h.14a1,1,0,0,0,.7-.28.87.87,0,0,0,.17-.21l1.46-2.46,2.87,0a1,1,0,0,0,.87-.5A1,1,0,0,0,20.87,17.25ZM9.19,18.78,8.3,17.29a1,1,0,0,0-.85-.49l-1.73,0,1.43-2.48a7,7,0,0,0,3.57,1.84ZM12,14.25a5,5,0,1,1,5-5A5,5,0,0,1,12,14.25Zm4.55,2.55a1,1,0,0,0-.85.49l-.89,1.49-1.52-2.65a7.06,7.06,0,0,0,3.56-1.84l1.43,2.48Z"/></svg>
                {{ __('Leaderboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('profile',['username' => Auth::user()->username])" :active="request()->routeIs('profile')" class="profileLink flex items-center">
                <svg style="width: 19px; margin-right: 3px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('profile') ? '#3D96F2' : '#9CA3AF' }}" d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"/></svg>
                {{ __('My Profile') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link class="flex items-center" :href="route('settings.account')" :active="request()->routeIs('settings.account')">
                <svg style="width: 19px; margin-right: 3px" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('settings.account') ? '#3D96F2' : '#9CA3AF' }}" d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z"/></svg>
                {{ __('Settings') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link class="flex items-center" :href="route('support.contact')" :active="request()->routeIs('support.contact')">
                <svg style="width: 19px; margin-right: 3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('support.contact') ? '#3D96F2' : '#9CA3AF' }}" d="M12,9a1,1,0,1,0,1,1A1,1,0,0,0,12,9Zm7-7H5A3,3,0,0,0,2,5V15a3,3,0,0,0,3,3H16.59l3.7,3.71A1,1,0,0,0,21,22a.84.84,0,0,0,.38-.08A1,1,0,0,0,22,21V5A3,3,0,0,0,19,2Zm1,16.59-2.29-2.3A1,1,0,0,0,17,16H5a1,1,0,0,1-1-1V5A1,1,0,0,1,5,4H19a1,1,0,0,1,1,1ZM8,9a1,1,0,1,0,1,1A1,1,0,0,0,8,9Zm8,0a1,1,0,1,0,1,1A1,1,0,0,0,16,9Z"/></svg>
                {{ __('Help Me') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
            <x-responsive-nav-link class="flex items-center" :href="route('analytics.analytics')" :active="request()->routeIs('analytics.analytics')"><svg style="width: 19px; margin-right: 3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('analytics.analytics') ? '#3D96F2' : '#9CA3AF' }}" d="M21.92,6.62a1,1,0,0,0-.54-.54A1,1,0,0,0,21,6H16a1,1,0,0,0,0,2h2.59L13,13.59l-3.29-3.3a1,1,0,0,0-1.42,0l-6,6a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L9,12.41l3.29,3.3a1,1,0,0,0,1.42,0L20,9.41V12a1,1,0,0,0,2,0V7A1,1,0,0,0,21.92,6.62Z"/></svg>
                {{ __('Analytics') }}
            </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link class="flex items-center" :href="route('subscription')" :active="request()->routeIs('subscription')">
                <svg style="width: 19px; margin-right: 3px; margin-top: -3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="{{ request()->routeIs('support.contact') ? '#3D96F2' : '#9CA3AF' }}" d="M21,10a.99974.99974,0,0,0,1-1V6a.9989.9989,0,0,0-.68359-.94824l-9-3a1.002,1.002,0,0,0-.63282,0l-9,3A.9989.9989,0,0,0,2,6V9a.99974.99974,0,0,0,1,1H4v7.18427A2.99507,2.99507,0,0,0,2,20v2a.99974.99974,0,0,0,1,1H21a.99974.99974,0,0,0,1-1V20a2.99507,2.99507,0,0,0-2-2.81573V10ZM20,21H4V20a1.001,1.001,0,0,1,1-1H19a1.001,1.001,0,0,1,1,1ZM6,17V10H8v7Zm4,0V10h4v7Zm6,0V10h2v7ZM4,8V6.7207l8-2.667,8,2.667V8Z"/></svg>
                {{ __('Subscription') }}
            </x-responsive-nav-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" class="flex items-center"
                                       onclick="event.preventDefault();
                                        this.closest('form').submit();">
                    <svg style="width: 19px; margin-right: 3px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#9CA3AF" d="M12.59,13l-2.3,2.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0l4-4a1,1,0,0,0,.21-.33,1,1,0,0,0,0-.76,1,1,0,0,0-.21-.33l-4-4a1,1,0,1,0-1.42,1.42L12.59,11H3a1,1,0,0,0,0,2ZM12,2A10,10,0,0,0,3,7.55a1,1,0,0,0,1.8.9A8,8,0,1,1,12,20a7.93,7.93,0,0,1-7.16-4.45,1,1,0,0,0-1.8.9A10,10,0,1,0,12,2Z"/></svg>
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
