<x-app-layout>
    <div class="py-7 sm:py-16 px-3 max-w-7xl mx-auto">
        <!-- Back to courses -->
        <a class="text-blue-500 mb-1" href="{{route('courses')}}">
            <uil-angle-left class="text-2xl mt-1 inline float-left"></uil-angle-left>
            <p class="mt-1 inline-block">Back to courses</p>
        </a>

        <!-- Lecture Count -->
        <div class="mb-1 float-right text-primary">
            <p class="text-sm text-gray-600">
                <span class="font-bold">{{count($course->lectures)}}</span> Lectures
            </p>
        </div>

        <!-- Name and description -->
        <div class="mt-6 grid grid-cols-12 mx-auto max-w-5xl">
            <div class="col-span-12 sm:col-span-4 md:col-span-3 lg:col-span-2 text-center flex align-center">
                <circular-progress-bar :percent="{{100* count($userLecture) / count($course->lectures)}}"
                                       class="mx-auto bg-transparent p-0 text-primary w-28">
                    <uil-bolt class="text-6xl"></uil-bolt>
                </circular-progress-bar>
            </div>
            <div
                class="col-span-12 sm:col-span-8 md:col-span-9 lg:col-span-10 justify-center sm:justify-start text-center sm:text-left flex align-center">
                <div>
                    <h1 class="header">{{ $course->name }}</h1>
                    <p class="text-lg text-gray-600">{{ $course->description }}</p>
                </div>
            </div>
        </div>

        <hr class="mt-6 mb-6">

        <!-- Lectures -->
        <lectures :course='@json($course)' :user-Lectures='@json($userLecture)'></lectures>
    </div>
</x-app-layout>
