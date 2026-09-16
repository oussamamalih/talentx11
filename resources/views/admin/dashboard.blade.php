<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-display text-3xl text-white uppercase tracking-wide">
                    {{ __('Admin Dashboard') }}
                </h2>
                <p class="text-xs text-[#8fa89c] mt-0.5">
                    {{ __('TalentX11 Executive Overview & Platform Operations') }}
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-[#10b981] hover:bg-[#0d9488] text-white text-xs font-display uppercase tracking-wider rounded transition">
                    {{ __('Manage Users') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Platform Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users -->
                <div class="atlas-stat bg-[#0d2919] rounded p-6 border border-[#1a4030] border-l-4 border-l-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold text-[#8fa89c] uppercase tracking-wider">{{ __('Total Users') }}</p>
                            <p class="font-display text-4xl text-white mt-2">{{ $stats['total_users'] }}</p>
                            <p class="text-xs text-[#8fa89c] mt-1">{{ $stats['total_admins'] }} {{ __('administrators') }}</p>
                        </div>
                        <div class="p-3 bg-[#133323] text-white rounded">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Players -->
                <div class="atlas-stat bg-[#0d2919] rounded p-6 border border-[#1a4030] border-l-4 border-l-[#10b981]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold text-[#8fa89c] uppercase tracking-wider">{{ __('Players') }}</p>
                            <p class="font-display text-4xl text-[#10b981] mt-2">{{ $stats['total_players'] }}</p>
                            <p class="text-xs text-[#8fa89c] mt-1">{{ $stats['total_player_profiles'] }} {{ __('profiles created') }}</p>
                        </div>
                        <div class="p-3 bg-[#133323] text-[#10b981] rounded">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Scouts -->
                <div class="atlas-stat bg-[#0d2919] rounded p-6 border border-[#1a4030] border-l-4 border-l-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold text-[#8fa89c] uppercase tracking-wider">{{ __('Scouts') }}</p>
                            <p class="font-display text-4xl text-amber-500 mt-2">{{ $stats['total_scouts'] }}</p>
                            <p class="text-xs text-[#8fa89c] mt-1">{{ $stats['total_scout_profiles'] }} {{ __('scout profiles') }}</p>
                        </div>
                        <div class="p-3 bg-[#133323] text-amber-500 rounded">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Scouting Interests -->
                <div class="atlas-stat bg-[#0d2919] rounded p-6 border border-[#1a4030] border-l-4 border-l-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold text-[#8fa89c] uppercase tracking-wider">{{ __('Scouting Interests') }}</p>
                            <p class="font-display text-4xl text-white mt-2">{{ $stats['total_scouting_interests'] }}</p>
                            <p class="text-xs text-[#8fa89c] mt-1">{{ $stats['pending_interests'] }} {{ __('pending') }} &bull; {{ $stats['contacted_interests'] }} {{ __('contacted') }}</p>
                        </div>
                        <div class="p-3 bg-[#133323] text-white rounded">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two-Column Tables Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Users -->
                <div class="bg-[#0d2919] rounded border border-[#1a4030] p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-[#1a4030] mb-4">
                        <h3 class="font-display text-xl text-white uppercase tracking-wide">{{ __('Recently Registered Users') }}</h3>
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-[#10b981] hover:text-[#a3e635]">
                            {{ __('All Users &rarr;') }}
                        </a>
                    </div>

                    <div class="divide-y divide-[#1a4030]">
                        @forelse ($recentUsers as $user)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded bg-[#133323] text-[#a3e635] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-[#8fa89c]">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        @if ($user->role === 'admin') bg-purple-950 text-purple-400 border border-purple-500/40
                                        @elseif ($user->role === 'scout') bg-[#133323] text-[#a3e635] border border-[#a3e635]/40
                                        @else bg-[#133323] text-[#10b981] border border-[#10b981]/40 @endif">
                                        {{ $user->role }}
                                    </span>
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-xs font-bold text-white hover:text-[#10b981]">
                                        &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-[#8fa89c] py-4">{{ __('No users found.') }}</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Scouting Inquiries -->
                <div class="bg-[#0d2919] rounded border border-[#1a4030] p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-[#1a4030] mb-4">
                        <h3 class="font-display text-xl text-white uppercase tracking-wide">{{ __('Recent Scouting Activity') }}</h3>
                        <a href="{{ route('scouting.interests.index') }}" class="text-xs font-bold text-[#10b981] hover:text-[#a3e635]">
                            {{ __('All Inquiries &rarr;') }}
                        </a>
                    </div>

                    <div class="divide-y divide-[#1a4030]">
                        @forelse ($recentInterests as $interest)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-bold text-white">
                                        <span class="text-[#10b981]">{{ $interest->scout->name }}</span>
                                        <span class="text-[#1a4030]">&rarr;</span>
                                        <span>{{ $interest->playerProfile->user->name }}</span>
                                    </p>
                                    <p class="text-xs text-[#8fa89c]">
                                        {{ $interest->scout->scoutProfile?->organization ?? __('Independent Scout') }} &bull; {{ $interest->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        @if ($interest->status === 'pending') bg-amber-950 text-amber-400 border border-amber-500/40
                                        @elseif ($interest->status === 'contacted') bg-[#133323] text-[#10b981] border border-[#10b981]/40
                                        @else bg-[#133323] text-[#8fa89c] @endif">
                                        {{ $interest->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-[#8fa89c] py-4">{{ __('No scouting activity logged.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>