<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- ================= LEFT ================= --}}
            <div class="flex items-center gap-8">

                {{-- LOGO --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <x-application-logo class="h-9 w-auto text-gray-800" />
                </a>

                {{-- DESKTOP MENU --}}
                <div class="hidden sm:flex items-center gap-6">
                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link
                        :href="route('assets.view.index')"
                        :active="request()->is('assets-view*')">
                        Assets
                    </x-nav-link>
                </div>
            </div>

            {{-- ================= RIGHT ================= --}}
            <div class="hidden sm:flex items-center gap-4">

                {{-- ================= ADMIN MENU ================= --}}
                @auth
                @if(auth()->user()->isAdmin())
                    <x-dropdown align="right" width="64">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                                System Admin
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">

                            {{-- APPROVAL --}}
                            <div class="px-3 py-1 text-xs text-gray-400 uppercase">
                                Approval
                            </div>

                            @if(Route::has('approvals.index'))
                                <x-dropdown-link href="{{ route('approvals.index') }}">
                                    Asset & Group Approval
                                </x-dropdown-link>
                            @endif

                            @if(Route::has('approval.changes'))
                                <x-dropdown-link href="{{ route('approval.changes') }}">
                                    Asset & Group Approval Edit
                                </x-dropdown-link>
                            @endif

                            <hr class="my-1">

                            {{-- MASTER DATA --}}
                            <div class="px-3 py-1 text-xs text-gray-400 uppercase">
                                Master Data
                            </div>

                            @if(Route::has('categories.index'))
                                <x-dropdown-link href="{{ route('categories.index') }}">Categories</x-dropdown-link>
                            @endif
                            @if(Route::has('locations.index'))
                                <x-dropdown-link href="{{ route('locations.index') }}">Locations</x-dropdown-link>
                            @endif
                            @if(Route::has('departments.index'))
                                <x-dropdown-link href="{{ route('departments.index') }}">Departments</x-dropdown-link>
                            @endif

                            <hr class="my-1">

                            {{-- REPORT & LOG --}}
                            <div class="px-3 py-1 text-xs text-gray-400 uppercase">
                                Report & Log
                            </div>

                            @if(Route::has('export.asset.view'))
                                <x-dropdown-link href="{{ route('export.asset.view') }}">Export Assets</x-dropdown-link>
                            @endif
                            @if(Route::has('export.activity.view'))
                                <x-dropdown-link href="{{ route('export.activity.view') }}">Export Activity Log</x-dropdown-link>
                            @endif
                            @if(Route::has('activity.recent'))
                                <x-dropdown-link href="{{ route('activity.recent') }}">Activity Log</x-dropdown-link>
                            @endif

                            <hr class="my-1">

{{-- USER --}}
<div class="px-3 py-1 text-xs text-gray-400 uppercase">
    User Management
</div>

<x-dropdown-link href="{{ route('users.view.index') }}">
    Users
</x-dropdown-link>



                        </x-slot>
                    </x-dropdown>
                @endif
                @endauth

                {{-- ================= USER MENU ================= --}}
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                            {{ Auth::user()->name }}
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
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
                @endauth
            </div>

            {{-- MOBILE TOGGLE --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                        class="p-2 rounded-md text-gray-500 hover:bg-gray-100">
                    ☰
                </button>
            </div>
        </div>
    </div>
</nav>
