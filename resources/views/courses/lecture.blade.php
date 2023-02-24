<x-app-layout>
    <lecture
        :user-configuration="{{$userConfiguration}}"
        :lecture-prop='@json($lecture)'
        :course='@json($course)'
    ></lecture>
</x-app-layout>
