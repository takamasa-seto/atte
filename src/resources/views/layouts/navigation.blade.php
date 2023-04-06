<nav x-data="{ open: false }" class="bg-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="font-bold text-2xl text-black">
                        Atte
                    </a>
                </div>
            </div>

            <!-- Settings Links -->
            <div class="flex items-center">
                <ul class="flex">
                    <li class="mr-8 last:mr-0"><a href="{{ url('/') }}">ホーム</a></li>
                    <li class="mr-8 last:mr-0"><a href="{{ url('/attendance') }}">日付一覧</a></li>
                    <li class="mr-8 last:mr-0">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                ログアウト
                            </a>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>

</nav>
