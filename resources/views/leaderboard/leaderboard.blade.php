<x-app-layout>
    <div class="py-7 sm:py-16 px-3 max-w-7xl mx-auto">
        <leaderboard :boards='@json($boards)' :user-levels='@json($userLevels)' :user-rank='@json($userRank)'></leaderboard>
    </div>
</x-app-layout>
