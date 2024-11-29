<div>
    <div class="navbar bg-base-100 px-4 shadow-md">
        <div class="flex-1">
            <label for="my-drawer" class="drawer-button cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </label>

            <img src="{{ asset('images/logo.png') }}" class="w-52 hidden md:flex">
        </div>
        <div class="flex-none space-x-3">
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <div class="indicator">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="badge badge-ghost badge-sm indicator-item text-xs">0</span>
                    </div>
                </div>
                <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-[1] mt-3 w-52 shadow">
                    <div class="card-body">
                    </div>
                </div>
            </div>
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="flex items-center space-x-2 bg-gray-100 pr-4 rounded-full">
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                        </div>
                    </div>
                    <p class="text-sm">{{ Auth::user()->name }}</p>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    <li><a href="{{ route('profile.show') }}">Profile</a></li>


                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <li><a href="{{ route('logout') }}" @click.prevent="$root.submit();">Logout</a></li>
                    </form>
                </ul>
            </div>
        </div>
    </div>

    <div class="drawer z-10">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-side">
            <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu bg-white text-base-content min-h-full w-80 px-4">
                <img src="{{ asset('images/logo.png') }}" class="w-52 mb-4">

                <div class="flex justify-center mb-5">
                    <div class="bg-[#20bcce] text-white uppercase py-1 px-4 rounded-xl text-[12px] ">
                        {{ Auth::user()->role }}
                    </div>
                </div>

                @foreach ($menus as $menu)
                    <li class="mb-1 text-sm">
                        <a href="{{ url($menu['url']) }}"
                            class="{{ request()->is(trim($menu['url'], '/')) ? 'bg-[#81c98c] text-white' : '' }}"><i
                                class="{{ $menu['icon'] }} mr-1 text-xs"></i>
                            {{ $menu['menu'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
