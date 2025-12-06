<nav class="-mx-3 flex flex-1 justify-end">
    @auth
        <a href="{{ url('/dashboard') }}"
            class="rounded-lg px-4 py-2 text-slate-700 font-medium ring-1 ring-transparent transition hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-500">
            Dashboard
        </a>
    @else
        <a href="{{ route('login') }}"
            class="rounded-lg px-4 py-2 text-slate-700 font-medium ring-1 ring-transparent transition hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-500">
            Log in
        </a>

        @if (Route::has('register'))
            <a href="{{ route('register') }}"
                class="rounded-lg px-4 py-2 text-slate-700 font-medium ring-1 ring-transparent transition hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-500">
                Register
            </a>
        @endif
    @endauth
</nav>