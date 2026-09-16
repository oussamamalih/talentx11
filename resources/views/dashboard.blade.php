<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-display text-3xl sm:text-4xl uppercase tracking-wide">
                    @if ($user->isPlayer())
                        {{ __('Player Dashboard') }}
                    @elseif ($user->isScout())
                        {{ __('Scout Dashboard') }}
                    @else
                        {{ __('Administrator Overview') }}
                    @endif
                </h2>
                <p class="text-xs text-[#8fa89c] mt-1 tracking-wide">
                    {{ __('TalentX11 Football Scouting Management Portal') }}
                </p>
            </div>

            <div class="flex items-center space-x-3">
                @if ($user->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-[#133323] border border-[#1a4030] hover:border-[#10b981] text-[#a3e635] text-xs font-display font-semibold uppercase tracking-[0.08em] rounded transition">
                        <svg class="w-4 h-4 me-1.5 text-[#a3e635]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ __('Full Admin Dashboard') }}
                    </a>
                @elseif ($user->isScout())
                    <a href="{{ route('favorites.index') }}" class="inline-flex items-center px-3.5 py-2 bg-[#133323] border border-[#1a4030] hover:border-[#10b981] text-white text-xs font-display font-semibold uppercase tracking-[0.08em] rounded transition">
                        <svg class="w-4 h-4 me-1.5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                        {{ __('Saved Talents') }} ({{ $favoritesCount ?? 0 }})
                    </a>
                    <a href="{{ route('scout.search') }}" class="inline-flex items-center px-4 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#0a1f14] text-xs font-display font-semibold uppercase tracking-[0.08em] rounded transition">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        {{ __('Search Players') }}
                    </a>
                @else
                    @if ($playerProfile)
                        <a href="{{ route('player.profile.index') }}" class="inline-flex items-center px-4 py-2 bg-[#133323] border border-[#1a4030] hover:border-[#10b981] text-white text-xs font-display font-semibold uppercase tracking-[0.08em] rounded transition">
                            {{ __('My Football Profile') }}
                        </a>
                    @else
                        <a href="{{ route('player.profile.create') }}" class="inline-flex items-center px-4 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#0a1f14] text-xs font-display font-semibold uppercase tracking-[0.08em] rounded transition">
                            {{ __('Create Profile') }}
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Welcome Header Card -->
            <div class="atlas-card relative overflow-hidden p-6">
                <div class="atlas-plus-pattern"></div>
                <div class="atlas-bg-text top-1/2 -translate-y-1/2 right-6 hidden lg:block">Atlas</div>
                <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="font-display text-xs uppercase tracking-[0.2em] text-[#10b981]">{{ __('Welcome back') }}</p>
                        <h3 class="font-display text-3xl uppercase tracking-wide">
                            {{ $user->name }}!
                        </h3>
                        <p class="text-sm text-[#8fa89c] mt-1">
                            @if ($user->isPlayer())
                                {{ __('Track scout inquiries, manage your football profile, and monitor club interest in your talent.') }}
                            @elseif ($user->isScout())
                                {{ __('Discover Moroccan football talent, connect with promising players, and manage your scouting shortlist.') }}
                            @else
                                {{ __('TalentX11 Administrator Portal: Overview of users, scouting activity, and platform metrics.') }}
                            @endif
                        </p>
                    </div>
                    <div>
                        @if ($user->isPlayer())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-display uppercase tracking-wider bg-[rgba(16,185,129,0.1)] text-[#10b981] border border-[rgba(16,185,129,0.25)]">
                                <span class="w-2 h-2 rounded-full bg-[#10b981] me-1.5"></span>
                                {{ __('Player Account') }}
                            </span>
                        @elseif ($user->isScout())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-display uppercase tracking-wider bg-[rgba(163,230,53,0.1)] text-[#a3e635] border border-[rgba(163,230,53,0.25)]">
                                <span class="w-2 h-2 rounded-full bg-[#a3e635] me-1.5"></span>
                                {{ __('Verified Scout') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-display uppercase tracking-wider bg-[#133323] text-white border border-[#1a4030]">
                                {{ __('Administrator') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- PLAYER DASHBOARD CONTENT -->
            @if ($user->isPlayer())
                @if (!$playerProfile)
                    <!-- Incomplete Profile Banner -->
                    <div class="relative overflow-hidden bg-[#0d2919] border border-[#1a4030] border-l-4 border-l-[#10b981] p-6 rounded">
                        <div class="atlas-plus-pattern"></div>
                        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h4 class="font-display text-xl uppercase tracking-wide">{{ __('Complete Your Football Profile') }}</h4>
                                <p class="text-sm text-[#8fa89c] mt-1">
                                    {{ __('Your football profile is currently empty. Add your position, birth date, club, and experience so scouts can discover your talent.') }}
                                </p>
                            </div>
                            <a href="{{ route('player.profile.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-[#10b981] hover:bg-[#34d399] text-[#0a1f14] font-display font-semibold text-sm uppercase tracking-[0.08em] rounded transition whitespace-nowrap">
                                {{ __('Create Football Profile') }} &rarr;
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Player Metrics Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="atlas-stat">
                            <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Total Scouting Interests') }}</p>
                            <p class="atlas-stat-val font-display text-5xl mt-2">{{ $totalInterestsCount }}</p>
                            <a href="{{ route('scouting.interests.index') }}" class="text-xs text-[#10b981] hover:text-[#34d399] font-bold mt-3 inline-block">
                                {{ __('View inquiries &rarr;') }}
                            </a>
                        </div>

                        <div class="atlas-stat" style="border-left-color: #f59e0b !important;">
                            <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Pending Inquiries') }}</p>
                            <p class="atlas-stat-val font-display text-5xl text-[#f59e0b] mt-2">{{ $pendingInterestsCount }}</p>
                            <p class="text-xs text-[#8fa89c] mt-3">{{ __('Awaiting scout review / trials') }}</p>
                        </div>

                        <div class="atlas-stat">
                            <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Unread Notifications') }}</p>
                            <p class="atlas-stat-val font-display text-5xl mt-2">{{ $unreadNotificationsCount }}</p>
                            <a href="{{ route('notifications.index') }}" class="text-xs text-[#10b981] hover:text-[#34d399] font-bold mt-3 inline-block">
                                {{ __('Check notifications &rarr;') }}
                            </a>
                        </div>
                    </div>

                    <!-- Player Profile Snapshot Card -->
                    <div class="atlas-card p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-[#1a4030] mb-4">
                            <h3 class="font-display text-lg uppercase tracking-wide">{{ __('My Football Profile Overview') }}</h3>
                            <div class="space-x-2">
                                <a href="{{ route('player.profile.edit') }}" class="text-xs font-bold text-[#10b981] hover:text-[#34d399]">
                                    {{ __('Edit Profile') }}
                                </a>
                                <span class="text-[#1a4030]">&bull;</span>
                                <a href="{{ route('player.profile.index') }}" class="text-xs font-semibold text-[#8fa89c] hover:text-white">
                                    {{ __('Full View &rarr;') }}
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                            <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                                <span class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Position') }}</span>
                                <p class="font-display text-lg uppercase text-[#10b981] mt-0.5">{{ $playerProfile->position }}</p>
                            </div>
                            <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                                <span class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Location') }}</span>
                                <p class="font-bold text-white mt-0.5">{{ $playerProfile->location }}</p>
                            </div>
                            <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                                <span class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Age') }}</span>
                                <p class="font-bold text-white mt-0.5">{{ $playerProfile->age }} {{ __('years old') }}</p>
                            </div>
                            <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                                <span class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Current Club') }}</span>
                                <p class="font-bold text-white mt-0.5">{{ $playerProfile->current_club ?? __('Free Agent') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Scouting Interests for Player -->
                    <div class="atlas-card p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-[#1a4030] mb-4">
                            <h3 class="font-display text-lg uppercase tracking-wide">{{ __('Recent Scouting Interest Received') }}</h3>
                            <a href="{{ route('scouting.interests.index') }}" class="text-xs font-bold text-[#10b981] hover:text-[#34d399]">
                                {{ __('All Inquiries &rarr;') }}
                            </a>
                        </div>

                        @if ($recentInterests->isEmpty())
                            <div class="py-8 text-center">
                                <p class="text-sm text-[#8fa89c]">{{ __('No scouting inquiries received yet.') }}</p>
                                <p class="text-xs text-[#8fa89c] mt-1">{{ __('Make sure your profile details are accurate to maximize your discovery chances.') }}</p>
                            </div>
                        @else
                            <div class="divide-y divide-[#1a4030]">
                                @foreach ($recentInterests as $interest)
                                    <div class="py-3.5 flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-bold text-white">{{ $interest->scout->name }}</p>
                                            <p class="text-xs text-[#8fa89c]">
                                                {{ $interest->scout->scoutProfile?->organization ?? __('Independent Scout') }} &bull; {{ $interest->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-display uppercase tracking-wider
                                                @if ($interest->status === 'pending') bg-[rgba(245,158,11,0.1)] text-[#f59e0b] border border-[rgba(245,158,11,0.25)]
                                                @elseif ($interest->status === 'contacted') bg-[rgba(16,185,129,0.1)] text-[#10b981] border border-[rgba(16,185,129,0.25)]
                                                @else bg-[#133323] text-[#8fa89c] border border-[#1a4030] @endif">
                                                {{ $interest->status }}
                                            </span>
                                            <a href="{{ route('scouting.interests.show', $interest) }}" class="text-xs font-bold text-[#8fa89c] hover:text-[#10b981]">
                                                {{ __('Details') }} &rarr;
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

            <!-- SCOUT DASHBOARD CONTENT -->
            @elseif ($user->isScout())
                @if (!$scoutProfile)
                    <!-- Incomplete Scout Profile Banner -->
                    <div class="relative overflow-hidden bg-[#0d2919] border border-[#1a4030] border-l-4 border-l-amber-500 p-6 rounded">
                        <div class="atlas-plus-pattern"></div>
                        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h4 class="font-display text-xl uppercase tracking-wide">{{ __('Complete Your Scout Profile') }}</h4>
                                <p class="text-sm text-[#8fa89c] mt-1">
                                    {{ __('Add your club, academy, or agency affiliation to build trust with players and access talent scout tools.') }}
                                </p>
                            </div>
                            <a href="{{ route('scout.profile.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-[#10b981] hover:bg-[#34d399] text-[#0a1f14] font-display font-semibold text-sm uppercase tracking-[0.08em] rounded transition whitespace-nowrap">
                                {{ __('Create Scout Profile') }} &rarr;
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Scout Metrics Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="atlas-stat">
                            <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Talents Tracked') }}</p>
                            <p class="atlas-stat-val font-display text-5xl mt-2">{{ $totalInterestsCount }}</p>
                            <a href="{{ route('scouting.interests.index') }}" class="text-xs text-[#10b981] hover:text-[#34d399] font-bold mt-3 inline-block">
                                {{ __('View tracked players &rarr;') }}
                            </a>
                        </div>

                        <div class="atlas-stat" style="border-left-color: #f59e0b !important;">
                            <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Saved Talents') }}</p>
                            <p class="atlas-stat-val font-display text-5xl text-[#f59e0b] mt-2">{{ $favoritesCount ?? 0 }}</p>
                            <a href="{{ route('favorites.index') }}" class="text-xs text-[#10b981] hover:text-[#34d399] font-bold mt-3 inline-block">
                                {{ __('View shortlist &rarr;') }}
                            </a>
                        </div>

                        <div class="atlas-stat" style="border-left-color: #f59e0b !important;">
                            <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Pending Contacts') }}</p>
                            <p class="atlas-stat-val font-display text-5xl text-[#f59e0b] mt-2">{{ $pendingInterestsCount }}</p>
                            <p class="text-xs text-[#8fa89c] mt-3">{{ __('Awaiting player reply') }}</p>
                        </div>

                        <div class="atlas-stat">
                            <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Contacted Talents') }}</p>
                            <p class="atlas-stat-val font-display text-5xl text-[#10b981] mt-2">{{ $contactedInterestsCount }}</p>
                            <p class="text-xs text-[#8fa89c] mt-3">{{ __('Official trials / discussions') }}</p>
                        </div>
                    </div>

                    <!-- Scout Profile Snapshot Card -->
                    <div class="atlas-card p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-[#1a4030] mb-4">
                            <h3 class="font-display text-lg uppercase tracking-wide">{{ __('My Scout Affiliation') }}</h3>
                            <div class="space-x-2">
                                <a href="{{ route('scout.profile.edit') }}" class="text-xs font-bold text-[#10b981] hover:text-[#34d399]">
                                    {{ __('Edit Profile') }}
                                </a>
                                <span class="text-[#1a4030]">&bull;</span>
                                <a href="{{ route('scout.profile.index') }}" class="text-xs font-semibold text-[#8fa89c] hover:text-white">
                                    {{ __('Full View &rarr;') }}
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                            <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                                <span class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Organization') }}</span>
                                <p class="font-display text-lg uppercase text-[#a3e635] mt-0.5">{{ $scoutProfile->organization }}</p>
                            </div>
                            <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                                <span class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Role Title') }}</span>
                                <p class="font-bold text-white mt-0.5">{{ $scoutProfile->role_title ?? __('Scout') }}</p>
                            </div>
                            <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                                <span class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Base Location') }}</span>
                                <p class="font-bold text-white mt-0.5">{{ $scoutProfile->location }}</p>
                            </div>
                            <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                                <span class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Experience') }}</span>
                                <p class="font-bold text-white mt-0.5">{{ $scoutProfile->experience_years ? $scoutProfile->experience_years . ' yrs' : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Search Callout Banner -->
                    <div class="atlas-card relative overflow-hidden p-6">
                        <div class="atlas-plus-pattern"></div>
                        <div class="atlas-bg-text top-1/2 -translate-y-1/2 right-6 hidden lg:block">Talent Finder</div>
                        <div class="relative flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div>
                                <h4 class="font-display text-2xl uppercase tracking-wide">{{ __('Discover Football Talents Across Morocco') }}</h4>
                                <p class="text-xs text-[#8fa89c] mt-1">
                                    {{ __('Filter by position, city, age, and preferred foot to find your next academy star or first-team recruit.') }}
                                </p>
                            </div>
                            <a href="{{ route('scout.search') }}" class="inline-flex items-center px-5 py-2.5 bg-[#10b981] hover:bg-[#34d399] text-[#0a1f14] font-display font-semibold text-sm uppercase tracking-[0.08em] rounded transition whitespace-nowrap">
                                {{ __('Search Players Now') }} &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Recent Scouting Activities for Scout -->
                    <div class="atlas-card p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-[#1a4030] mb-4">
                            <h3 class="font-display text-lg uppercase tracking-wide">{{ __('Recent Talents Expressed Interest In') }}</h3>
                            <a href="{{ route('scouting.interests.index') }}" class="text-xs font-bold text-[#10b981] hover:text-[#34d399]">
                                {{ __('All Tracked Talents &rarr;') }}
                            </a>
                        </div>

                        @if ($recentInterests->isEmpty())
                            <div class="py-8 text-center">
                                <p class="text-sm text-[#8fa89c]">{{ __('You have not expressed interest in any players yet.') }}</p>
                                <div class="mt-3">
                                    <a href="{{ route('scout.search') }}" class="text-xs font-bold text-[#10b981] hover:text-[#34d399]">
                                        {{ __('Browse player profiles to get started &rarr;') }}
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="divide-y divide-[#1a4030]">
                                @foreach ($recentInterests as $interest)
                                    <div class="py-3.5 flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-bold text-white">{{ $interest->playerProfile->user->name }}</p>
                                            <p class="text-xs text-[#8fa89c]">
                                                {{ $interest->playerProfile->position }} &bull; {{ $interest->playerProfile->location }} &bull; {{ $interest->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-display uppercase tracking-wider
                                                @if ($interest->status === 'pending') bg-[rgba(245,158,11,0.1)] text-[#f59e0b] border border-[rgba(245,158,11,0.25)]
                                                @elseif ($interest->status === 'contacted') bg-[rgba(16,185,129,0.1)] text-[#10b981] border border-[rgba(16,185,129,0.25)]
                                                @else bg-[#133323] text-[#8fa89c] border border-[#1a4030] @endif">
                                                {{ $interest->status }}
                                            </span>
                                            <a href="{{ route('scouting.interests.show', $interest) }}" class="text-xs font-bold text-[#8fa89c] hover:text-[#10b981]">
                                                {{ __('Details') }} &rarr;
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

            <!-- ADMIN DASHBOARD CONTENT -->
            @elseif ($user->isAdmin())
                <!-- Admin Quick Access Card -->
                <div class="atlas-card relative overflow-hidden p-6">
                    <div class="atlas-plus-pattern"></div>
                    <div class="atlas-bg-text top-1/2 -translate-y-1/2 right-6 hidden lg:block">Control</div>
                    <div class="relative flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="font-display text-2xl uppercase tracking-wide">{{ __('Administrator Control Center') }}</h4>
                            <p class="text-xs text-[#8fa89c] mt-1">
                                {{ __('Manage platform users, monitor registrations, oversee scouting inquiries, and configure system rules.') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#0a1f14] font-display font-semibold text-sm uppercase tracking-[0.08em] rounded transition">
                                {{ __('Admin Dashboard') }}
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-[#133323] border border-[#1a4030] hover:border-[#10b981] text-white font-display font-semibold text-sm uppercase tracking-[0.08em] rounded transition">
                                {{ __('Manage Users') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Admin Platform Metrics -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="atlas-stat">
                        <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Total Registered Users') }}</p>
                        <p class="atlas-stat-val font-display text-5xl mt-2">{{ $adminStats['total_users'] }}</p>
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-[#10b981] hover:text-[#34d399] font-bold mt-3 inline-block">
                            {{ __('View all users &rarr;') }}
                        </a>
                    </div>

                    <div class="atlas-stat" style="border-left-color: var(--tx-brand-strong) !important;">
                        <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Total Players') }}</p>
                        <p class="atlas-stat-val font-display text-5xl text-[#10b981] mt-2">{{ $adminStats['total_players'] }}</p>
                        <p class="text-xs text-[#8fa89c] mt-3">{{ $adminStats['total_player_profiles'] }} {{ __('profiles active') }}</p>
                    </div>

                    <div class="atlas-stat" style="border-left-color: #f59e0b !important;">
                        <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Total Scouts') }}</p>
                        <p class="atlas-stat-val font-display text-5xl text-[#f59e0b] mt-2">{{ $adminStats['total_scouts'] }}</p>
                        <p class="text-xs text-[#8fa89c] mt-3">{{ $adminStats['total_scout_profiles'] }} {{ __('verified scouts') }}</p>
                    </div>

                    <div class="atlas-stat">
                        <p class="text-xs font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Scouting Interests') }}</p>
                        <p class="atlas-stat-val font-display text-5xl mt-2">{{ $adminStats['total_scouting_interests'] }}</p>
                        <a href="{{ route('scouting.interests.index') }}" class="text-xs text-[#10b981] hover:text-[#34d399] font-bold mt-3 inline-block">
                            {{ __('View activity log &rarr;') }}
                        </a>
                    </div>
                </div>

                <!-- Recent Platform Interests for Admin -->
                <div class="atlas-card p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-[#1a4030] mb-4">
                        <h3 class="font-display text-lg uppercase tracking-wide">{{ __('Latest Platform Scouting Inquiries') }}</h3>
                        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-[#10b981] hover:text-[#34d399]">
                            {{ __('Full Admin Dashboard &rarr;') }}
                        </a>
                    </div>

                    @if ($recentInterests->isEmpty())
                        <p class="text-sm text-[#8fa89c] py-4">{{ __('No scouting interests logged yet.') }}</p>
                    @else
                        <div class="divide-y divide-[#1a4030]">
                            @foreach ($recentInterests as $interest)
                                <div class="py-3.5 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-white">
                                            <span class="text-[#10b981]">{{ $interest->scout->name }}</span>
                                            <span class="text-[#8fa89c]">&rarr;</span>
                                            <span>{{ $interest->playerProfile->user->name }}</span>
                                        </p>
                                        <p class="text-xs text-[#8fa89c]">
                                            {{ $interest->scout->scoutProfile?->organization ?? __('Independent Scout') }} &bull; {{ $interest->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-display uppercase tracking-wider
                                            @if ($interest->status === 'pending') bg-[rgba(245,158,11,0.1)] text-[#f59e0b] border border-[rgba(245,158,11,0.25)]
                                            @elseif ($interest->status === 'contacted') bg-[rgba(16,185,129,0.1)] text-[#10b981] border border-[rgba(16,185,129,0.25)]
                                            @else bg-[#133323] text-[#8fa89c] border border-[#1a4030] @endif">
                                            {{ $interest->status }}
                                        </span>
                                        <a href="{{ route('scouting.interests.show', $interest) }}" class="text-xs font-bold text-[#8fa89c] hover:text-[#10b981]">
                                            {{ __('Details') }} &rarr;
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Recent Platform Registrations for Admin -->
                <div class="atlas-card p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-[#1a4030] mb-4">
                        <h3 class="font-display text-lg uppercase tracking-wide">{{ __('Recently Registered Users') }}</h3>
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-[#10b981] hover:text-[#34d399]">
                            {{ __('All Users &rarr;') }}
                        </a>
                    </div>

                    <div class="divide-y divide-[#1a4030]">
                        @forelse ($recentUsers ?? collect() as $recentUser)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded bg-[#133323] border border-[#1a4030] text-[#a3e635] flex items-center justify-center font-display font-semibold text-sm shrink-0">
                                        {{ strtoupper(substr($recentUser->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $recentUser->name }}</p>
                                        <p class="text-xs text-[#8fa89c]">{{ $recentUser->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-display uppercase tracking-wider
                                        @if ($recentUser->role === 'admin') bg-[rgba(163,230,53,0.1)] text-[#a3e635] border border-[rgba(163,230,53,0.25)]
                                        @elseif ($recentUser->role === 'scout') bg-[rgba(245,158,11,0.1)] text-[#f59e0b] border border-[rgba(245,158,11,0.25)]
                                        @else bg-[rgba(16,185,129,0.1)] text-[#10b981] border border-[rgba(16,185,129,0.25)] @endif">
                                        {{ $recentUser->role }}
                                    </span>
                                    <a href="{{ route('admin.users.show', $recentUser) }}" class="text-xs font-bold text-[#8fa89c] hover:text-[#10b981]">
                                        &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-[#8fa89c] py-4">{{ __('No users found.') }}</p>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>