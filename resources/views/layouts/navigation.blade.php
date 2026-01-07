<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT --}}
            <div class="flex items-center">

                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="h-9 w-auto text-gray-800" />
                </a>

                {{-- Main Navigation --}}
                <div class="hidden sm:flex sm:space-x-6 sm:ml-10">

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link href="/assets-view" :active="request()->is('assets-view*')">
                        Assets
                    </x-nav-link>

                    <x-nav-link href="/packages-view" :active="request()->is('packages-view*')">
                        Packages
                    </x-nav-link>

                    <x-nav-link href="/activity/asset/1">
                        Activity
                    </x-nav-link>

                </div>
            </div>

            {{-- RIGHT --}}
            <div class="hidden sm:flex sm:items-center sm:space-x-4">

                {{-- ADMIN MENU --}}
                @if(auth()->user()->isAdmin())
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                                System Admin
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">

                            <x-dropdown-link href="{{ route('categories.index') }}">
                                Categories
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('locations.index') }}">
                                Locations
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('departments.index') }}">
                                Departments
                            </x-dropdown-link>

                            <hr class="my-1">

                            <x-dropdown-link href="{{ route('approval.assets') }}">
                                Asset Approval
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('approval.packages') }}">
                                Package Approval
                            </x-dropdown-link>

                            <hr class="my-1">

                            <x-dropdown-link href="{{ route('export.view.assets') }}">
                                Export Assets
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('export.view.activity') }}">
                                Export Activity Log
                            </x-dropdown-link>

                        </x-slot>
                    </x-dropdown>
                @endif

                {{-- USER MENU --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                            {{ Auth::user()->name }}
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

            </div>

            {{-- MOBILE BUTTON --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 text-gray-500 hover:bg-gray-100 rounded">
                    ☰
                </button>
            </div>
        </div>
    </div>
</nav>
