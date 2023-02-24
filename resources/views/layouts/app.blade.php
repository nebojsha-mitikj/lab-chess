<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- Google tag (gtag.js) --}}
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-238865369-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-238865369-1');
        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>
            @if(strpos(Route::current()->getName(), 'courses') !== false)
                Labchess | Courses
            @elseif(strpos(Route::current()->getName(), 'trainer') !== false)
                Labchess | Trainer
            @elseif(strpos(Route::current()->getName(), 'settings') !== false)
                Labchess | Settings
            @elseif(strpos(Route::current()->getName(), 'leaderboard') !== false)
                Labchess | Leaderboard
            @elseif(strpos(Route::current()->getName(), 'profile') !== false)
                Labchess | Profile
            @else
                Labchess | Learn Chess Online
            @endif
        </title>
        <link rel="preload" href="{{asset('fonts/XRXV3I6Li01BKofINeaB.woff2')}}" as="font" type="font/woff2" crossorigin/>
        <link rel="icon" href="{{ asset('images/labchess learn chess online.svg') }}"/>
        <style>::-moz-selection{color:inherit;background:#a9d4ff}::selection{color:inherit;background:#a9d4ff}@font-face{font-family:Nunito;font-style:normal;font-weight:400;font-display:swap;src:url({{asset('fonts/XRXV3I6Li01BKofIOOaBXso.woff2')}}) format('woff2');unicode-range:U+0460-052F,U+1C80-1C88,U+20B4,U+2DE0-2DFF,U+A640-A69F,U+FE2E-FE2F}@font-face{font-family:Nunito;font-style:normal;font-weight:400;font-display:swap;src:url(https://fonts.gstatic.com/s/nunito/v22/XRXV3I6Li01BKofIMeaBXso.woff2) format('woff2');unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:Nunito;font-style:normal;font-weight:400;font-display:swap;src:url(https://fonts.gstatic.com/s/nunito/v22/XRXV3I6Li01BKofIOuaBXso.woff2) format('woff2');unicode-range:U+0102-0103,U+0110-0111,U+0128-0129,U+0168-0169,U+01A0-01A1,U+01AF-01B0,U+1EA0-1EF9,U+20AB}@font-face{font-family:Nunito;font-style:normal;font-weight:400;font-display:swap;src:url(https://fonts.gstatic.com/s/nunito/v22/XRXV3I6Li01BKofIO-aBXso.woff2) format('woff2');unicode-range:U+0100-024F,U+0259,U+1E00-1EFF,U+2020,U+20A0-20AB,U+20AD-20CF,U+2113,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:Nunito;font-style:normal;font-weight:400;font-display:swap;src:url({{asset('fonts/XRXV3I6Li01BKofINeaB.woff2')}}) format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}@font-face{font-family:Nunito;font-style:normal;font-weight:600;font-display:swap;src:url({{asset('fonts/XRXV3I6Li01BKofIOOaBXso.woff2')}}) format('woff2');unicode-range:U+0460-052F,U+1C80-1C88,U+20B4,U+2DE0-2DFF,U+A640-A69F,U+FE2E-FE2F}@font-face{font-family:Nunito;font-style:normal;font-weight:600;font-display:swap;src:url(https://fonts.gstatic.com/s/nunito/v22/XRXV3I6Li01BKofIMeaBXso.woff2) format('woff2');unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:Nunito;font-style:normal;font-weight:600;font-display:swap;src:url(https://fonts.gstatic.com/s/nunito/v22/XRXV3I6Li01BKofIOuaBXso.woff2) format('woff2');unicode-range:U+0102-0103,U+0110-0111,U+0128-0129,U+0168-0169,U+01A0-01A1,U+01AF-01B0,U+1EA0-1EF9,U+20AB}@font-face{font-family:Nunito;font-style:normal;font-weight:600;font-display:swap;src:url(https://fonts.gstatic.com/s/nunito/v22/XRXV3I6Li01BKofIO-aBXso.woff2) format('woff2');unicode-range:U+0100-024F,U+0259,U+1E00-1EFF,U+2020,U+20A0-20AB,U+20AD-20CF,U+2113,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:Nunito;font-style:normal;font-weight:600;font-display:swap;src:url({{asset('fonts/XRXV3I6Li01BKofINeaB.woff2')}}) format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}@font-face{font-family:Nunito;font-style:normal;font-weight:700;font-display:swap;src:url({{asset('fonts/XRXV3I6Li01BKofIOOaBXso.woff2')}}) format('woff2');unicode-range:U+0460-052F,U+1C80-1C88,U+20B4,U+2DE0-2DFF,U+A640-A69F,U+FE2E-FE2F}@font-face{font-family:Nunito;font-style:normal;font-weight:700;font-display:swap;src:url(https://fonts.gstatic.com/s/nunito/v22/XRXV3I6Li01BKofIMeaBXso.woff2) format('woff2');unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:Nunito;font-style:normal;font-weight:700;font-display:swap;src:url(https://fonts.gstatic.com/s/nunito/v22/XRXV3I6Li01BKofIOuaBXso.woff2) format('woff2');unicode-range:U+0102-0103,U+0110-0111,U+0128-0129,U+0168-0169,U+01A0-01A1,U+01AF-01B0,U+1EA0-1EF9,U+20AB}@font-face{font-family:Nunito;font-style:normal;font-weight:700;font-display:swap;src:url(https://fonts.gstatic.com/s/nunito/v22/XRXV3I6Li01BKofIO-aBXso.woff2) format('woff2');unicode-range:U+0100-024F,U+0259,U+1E00-1EFF,U+2020,U+20A0-20AB,U+20AD-20CF,U+2113,U+2C60-2C7F,U+A720-A7FF}@font-face{font-family:Nunito;font-style:normal;font-weight:700;font-display:swap;src:url({{asset('fonts/XRXV3I6Li01BKofINeaB.woff2')}}) format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}</style>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @if(Route::is('settings.display'))
            <link rel="stylesheet" href="{{ asset('css/pieces/knight.css') }}">
        @endif
        @if(Route::is('premium'))
            @paddleJS
        @endif
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @if(!Route::is('courses.lecture.index') && !Route::is('trainer.position.index'))
                @include('layouts.navigation')
            @endif
            <div id="app">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        window.user = {!! Auth::user(); !!};
        window.subscribed = {{ json_encode(Auth::user()->subscribed(env("PADDLE_SUBSCRIPTION"))) }}

        window.addEventListener('scroll', throttle(callback, 25));
        let mNav = document.querySelector('#m-nav');
        function throttle(fn, wait) {
            var time = Date.now();
            return function() {
                if ((time + wait - Date.now()) < 0) {
                    fn();
                    time = Date.now();
                }
            }
        }
        function callback() {
            if(mNav != null){
                if(document.documentElement.scrollTop > 80) {
                    if (!mNav.classList.contains('border-b')) {
                        mNav.classList.add('border-b');
                    }
                }else{
                    mNav.classList.remove('border-b');
                }
            }
        }
    </script>
</html>
