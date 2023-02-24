<x-app-layout>
    <div class="py-7 sm:py-16 max-w-5xl mx-auto border-gray-200 rounded-sm">
        <div class="grid grid-cols-12 px-3">
            <div
                class="md:col-span-3 sm:col-span-4 col-span-12 sm:mb-0 mb-0 sm:mb-2 order-1 h-36 w-36 lg:h-40 lg:w-40 mx-auto relative">
                @if (Auth::id() === $profile->id)
                    <div @click="$refs.uploader.click()"
                         class="absolute cursor-pointer border-2 border-solid border-white right-1 top-2 bg-primary rounded-full w-8 h-8 flex flex-wrap content-center justify-center">
                        <uil-pen class="text-xl inline text-white" style="margin-top: -2px"></uil-pen>
                        <image-upload
                            ref="uploader"
                            :send-Request="true"
                            @success="(url) => $refs.profileImage.src = url"
                            @showfiletoolarge="$refs.fileTooLargeError.style.display = 'block'"
                            @hidefiletoolarge="$refs.fileTooLargeError.style.display = 'none'"
                        ></image-upload>
                    </div>
                @endif
                <img
                    ref="profileImage"
                    class="h-full w-full m-auto object-cover rounded-full"
                    style="border: 1px solid #E0E0E0"
                    src="{{$profile->profile_picture_url != null ? $profile->profile_picture_url : 'https://labchess.s3.eu-central-1.amazonaws.com/LabChessImages/LabChessProfile.svg'}}"
                />
                <p
                    style="display: none"
                    ref="fileTooLargeError"
                    class="text-danger text-center">
                    The file is too large.
                </p>
            </div>

            <div class="md:col-span-6 sm:col-span-4 col-span-12 order-2 sm:text-left text-center">

                <!-- Username and Name -->
                <h1 class="header text-gray-800 mt-2 sm:mt-0">{{ $profile->username }}</h1>

                @if(Auth::user()->id === $profile->id && $profile->email_verified_at === null)
                    <email-verification></email-verification>
                @endif

                <p class="text-primary-dark text-sm">{{ $profile->level }}</p>

                <p class="text-gray-700">{{ $profile->full_name }}</p>

                @if($profile->subscribed || $profile->email === "labchess97@gmail.com")
                    <div class="flex align-center justify-center sm:justify-start mt-1 text-gray-700">
                        <img class="w-4 ml-0.5 mr-2" src="{{\Illuminate\Support\Facades\URL::asset('images/icons/diamond.png')}}">
                        Premium
                    </div>
                @endif

                <!-- Joined -->
                <uil-clock class="text-md sm:text-xl inline text-gray-700" style="margin-top: -4px"></uil-clock>
                <p class="inline-block mt-1 text-gray-700">
                    Joined {{\Carbon\Carbon::parse($profile->created_at)->format('F Y')}}</p>

                <!-- Following -->
                <div class="clear-both">
                    <uil-users-alt class="text-md sm:text-xl inline text-gray-700" style="margin-top: -4px"></uil-users-alt>
                    <p class="inline-block mt-1 text-gray-700">
                        {{$followCount['following']}} Following / <span
                            ref="followersCount">{{$followCount['followers']}}</span> Followers
                    </p>
                </div>


                <div class="flex sm:block justify-center mt-1.5 sm:mt-0">

                    <!-- Goal Reach -->
                    @if(!empty($profile->userGoal->goal_reach_count))
                        <div class="clear-both flex justify-center sm:justify-start mt-0 sm:mt-1 mx-2 sm:mx-0">
                            <svg style="width: 18px; margin-top: -1px" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24"><path fill="#3D96F2" d="M8.46777,8.39453l-.00225.00183-.00214.00208ZM18.42188,8.208a1.237,1.237,0,0,0-.23-.17481.99959.99959,0,0,0-1.39941.41114,5.78155,5.78155,0,0,1-1.398,1.77734,8.6636,8.6636,0,0,0,.1333-1.50977,8.71407,8.71407,0,0,0-4.40039-7.582,1.00009,1.00009,0,0,0-1.49121.80567A7.017,7.017,0,0,1,7.165,6.87793l-.23047.1875a8.51269,8.51269,0,0,0-1.9873,1.8623A8.98348,8.98348,0,0,0,8.60254,22.83594.99942.99942,0,0,0,9.98,21.91016a1.04987,1.04987,0,0,0-.0498-.3125,6.977,6.977,0,0,1-.18995-2.58106,9.004,9.004,0,0,0,4.3125,4.0166.997.997,0,0,0,.71534.03809A8.99474,8.99474,0,0,0,18.42188,8.208ZM14.51709,21.03906a6.964,6.964,0,0,1-3.57666-4.40234,8.90781,8.90781,0,0,1-.17969-.96387,1.00025,1.00025,0,0,0-.79931-.84473A.982.982,0,0,0,9.77,14.80957a.99955.99955,0,0,0-.8667.501,8.9586,8.9586,0,0,0-1.20557,4.71777,6.98547,6.98547,0,0,1-1.17529-9.86816,6.55463,6.55463,0,0,1,1.562-1.458.74507.74507,0,0,0,.07422-.05469s.29669-.24548.30683-.2511a8.96766,8.96766,0,0,0,2.89874-4.63269,6.73625,6.73625,0,0,1,1.38623,8.08789,1.00024,1.00024,0,0,0,1.18359,1.418,7.85568,7.85568,0,0,0,3.86231-2.6875,7.00072,7.00072,0,0,1-3.2793,10.457Z"/></svg>
                            <p class="ml-1" style="color: rgb(55 65 81)">{{$profile->userGoal->goal_reach_count}}</p>
                        </div>
                    @endif

                   <!-- Star & Experience -->
                    <div class="flex align-center center clear-both mx-2 sm:mx-0 mt-0 sm:mt-1">
                        <svg style="width: 20px; margin-top: -4px; margin-left: -2px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#FDE047" d="M17.56248,21.55957a1.00275,1.00275,0,0,1-.46531-.11475L12,18.76514,6.90283,21.44482a1.00019,1.00019,0,0,1-1.45117-1.0542l.97363-5.67578-4.12353-4.019a1.00033,1.00033,0,0,1,.5542-1.706l5.69873-.82813L11.103,2.99805a1.04173,1.04173,0,0,1,1.79394,0l2.54834,5.16357,5.69873.82813a1.00033,1.00033,0,0,1,.5542,1.706l-4.12353,4.019.97363,5.67578a1,1,0,0,1-.98586,1.169Z"/></svg>
                        <p class="ml-1 text-gray-700">{{$profile->experience}} xp</p>
                    </div>

                </div>

                <p class="mt-1 mb-1 text-gray-700">{{ $profile->biography }}</p>
            </div>

            <div class="md:col-span-3 sm:col-span-4 col-span-12 order-3 text-center sm:text-right mr-3">
                @if (Auth::user()->id === $profile->id)
                    <a href="{{ route('settings.profile') }}" class="ml-3 text-sm button sub-button inline-block">
                        <uil-pen class="inline" style="margin-top: -4px"></uil-pen>
                        Edit Profile
                    </a>
                @else
                    <follow-unfollow-buttons
                        :user-Follows="{!! json_encode($userFollows) !!}"
                        :profile="{{$profile}}"
                        @follow="onFollow"
                        @unfollow="onUnfollow"
                    ></follow-unfollow-buttons>
                @endif
            </div>

        </div>

        <div class="w-full border-t border-gray-200 my-8 hidden sm:block"></div>

        <div class="w-full text-center" id="profile-spinner">
            <div class="lds-dual-ring-p mt-10"></div>
        </div>

        <div class="grid grid-cols-3 gap-3 mt-3 px-6">
            <div class="lg:col-span-2 col-span-3">

                <courses
                    class="w-full mt-5"
                    :courses='@json($courses)'
                    :profile='@json($profile)'
                ></courses>

                <trainer
                    class="w-full mt-5"
                    :trainers='@json($trainer)'
                    :profile='@json($profile)'
                    @stopspin="stopSpinner"
                ></trainer>

            </div>
            <div class="lg:col-span-1 col-span-3 mt-3">
                <friends class="w-full" ref="friends" :profile='@json($profile)'></friends>
                <activity class="w-full mt-10" :xp-Activity='@json($xpActivity)'></activity>
            </div>
        </div>

        @if($profile->social_media_links != null)
            <div class="w-full border-t border-gray-200 mt-10 mb-6"></div>
        @else
            <div class="my-6"></div>
        @endif

        <social-media-links class="px-3" :social-Media-Links-Prop='@json($profile->social_media_links)'></social-media-links>

    </div>

</x-app-layout>
