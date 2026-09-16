<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-display text-3xl sm:text-4xl uppercase tracking-wide">
                    {{ __('Player Profile') }}
                </h2>
                <p class="text-xs text-[#8fa89c] mt-1 tracking-wide">
                    {{ __('Official Moroccan Football Scouting Dossier') }}
                </p>
            </div>

            @if (Auth::id() === $profile->user_id)
                <a href="{{ route('player.profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#0a1f14] text-xs font-display font-semibold uppercase tracking-[0.08em] rounded transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    {{ __('Edit My Profile') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (Auth::user()?->isScout())
                @php
                    $existingInterest = \App\Models\ScoutingInterest::where('scout_id', Auth::id())
                        ->where('player_profile_id', $profile->id)
                        ->first();
                @endphp

                @if ($existingInterest)
                    <div class="relative overflow-hidden bg-[#0d2919] border border-[#1a4030] border-l-4 border-l-[#10b981] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded">
                        <div class="atlas-plus-pattern"></div>
                        <div class="relative flex items-center space-x-3">
                            <span class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-[#10b981] text-[#0a1f14] font-display font-black text-sm">
                                &#10003;
                            </span>
                            <div>
                                <h4 class="font-display text-lg uppercase tracking-wide">{{ __('Scouting Interest Expressed') }}</h4>
                                <p class="text-xs text-[#8fa89c]">
                                    {{ __('You expressed interest on') }} {{ $existingInterest->created_at->format('M d, Y') }} &mdash;
                                    <span class="font-display uppercase tracking-wider
                                        @if ($existingInterest->status === 'pending') text-[#f59e0b]
                                        @elseif ($existingInterest->status === 'contacted') text-[#10b981]
                                        @else text-[#8fa89c] @endif">
                                        {{ __($existingInterest->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('scouting.interests.show', $existingInterest) }}" class="relative inline-flex items-center text-xs font-bold text-[#10b981] hover:text-[#34d399] underline underline-offset-2">
                            {{ __('View Interest Details') }} &rarr;
                        </a>
                    </div>
                @else
                    <div x-data="{ open: false }" class="relative overflow-hidden bg-[#0d2919] border border-[#1a4030] border-l-4 border-l-[#10b981] p-5 rounded">
                        <div class="atlas-plus-pattern"></div>
                        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h4 class="font-display text-lg uppercase tracking-wide text-[#10b981]">{{ __('Interested in Scouting this Talent?') }}</h4>
                                <p class="text-xs text-[#8fa89c] mt-0.5">
                                    {{ __('Express your official scouting interest to start a dialogue and track this player.') }}
                                </p>
                            </div>
                            <button @click="open = !open" type="button" class="inline-flex items-center justify-center px-4 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#0a1f14] text-xs font-display font-semibold uppercase tracking-[0.08em] rounded transition">
                                {{ __('Express Interest') }}
                            </button>
                        </div>

                        <form x-show="open" x-cloak method="POST" action="{{ route('scouting.interests.store', $profile) }}" class="relative mt-4 pt-4 border-t border-[#1a4030] space-y-3">
                            @csrf
                            <div>
                                <x-input-label for="message" :value="__('Message or Trial Invitation (Optional)')" />
                                <textarea id="message" name="message" rows="3" class="mt-1 block w-full bg-[#133323] border border-[#1a4030] text-white focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] rounded text-sm placeholder:text-[#8fa89c]/60" placeholder="Introduce yourself, club affiliation, or describe trial opportunities..."></textarea>
                            </div>
                            <div class="flex justify-end items-center gap-3">
                                <button @click="open = false" type="button" class="px-3 py-1.5 text-xs font-display font-semibold text-[#8fa89c] hover:text-white uppercase tracking-[0.08em]">
                                    {{ __('Cancel') }}
                                </button>
                                <x-primary-button>
                                    {{ __('Send Scouting Interest') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($profile->isFavoritedBy(Auth::user()))
                    <div class="relative overflow-hidden bg-[#0d2919] border border-[#1a4030] border-l-4 border-l-[#f59e0b] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded">
                        <div class="atlas-plus-pattern"></div>
                        <div class="relative flex items-center space-x-3">
                            <span class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-[#f59e0b]/15 text-[#f59e0b] font-display font-black text-sm">&#9733;</span>
                            <div>
                                <h4 class="font-display text-lg uppercase tracking-wide">{{ __('Saved to Shortlist') }}</h4>
                                <p class="text-xs text-[#8fa89c]">{{ __('This talent is bookmarked in your saved talents.') }}</p>
                            </div>
                        </div>
                        <form action="{{ route('favorites.destroy', $profile) }}" method="POST" class="relative">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-[#133323] hover:bg-[#1a4030] text-[#10b981] text-xs font-display font-semibold uppercase tracking-[0.08em] rounded transition border border-[#10b981]/40">
                                {{ __('Remove from Shortlist') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="relative overflow-hidden bg-[#0d2919] border border-[#1a4030] border-l-4 border-l-[#f59e0b] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded">
                        <div class="atlas-plus-pattern"></div>
                        <div class="relative flex items-center space-x-3">
                            <span class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-[#f59e0b]/15 text-[#f59e0b] font-display font-black text-sm">&#9733;</span>
                            <div>
                                <h4 class="font-display text-lg uppercase tracking-wide">{{ __('Shortlist This Talent') }}</h4>
                                <p class="text-xs text-[#8fa89c]">{{ __('Bookmark this player to revisit them later.') }}</p>
                            </div>
                        </div>
                        <form action="{{ route('favorites.store', $profile) }}" method="POST" class="relative">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-[#f59e0b] hover:bg-[#fbbf24] text-[#0a1f14] text-xs font-display font-semibold uppercase tracking-[0.08em] rounded transition">
                                {{ __('Add to Shortlist') }}
                            </button>
                        </form>
                    </div>
                @endif
            @endif

            <!-- Main Dossier Header Card -->
            <div class="atlas-card relative overflow-hidden p-6">
                <div class="atlas-plus-pattern"></div>
                <div class="atlas-bg-text top-1/2 -translate-y-1/2 right-4 hidden lg:block">{{ substr(strtoupper($profile->user->name), 0, 1) }}</div>
                <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                    <div class="flex items-center space-x-5">
                        <div class="h-20 w-20 rounded bg-[#133323] border-2 border-[#10b981] text-white flex items-center justify-center font-display text-5xl shrink-0">
                            {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h1 class="font-display text-3xl uppercase tracking-wide">{{ $profile->user->name }}</h1>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-display uppercase tracking-wider bg-[rgba(16,185,129,0.1)] text-[#10b981] border border-[rgba(16,185,129,0.25)]">
                                    {{ $profile->position }}
                                </span>
                            </div>
                            <div class="mt-2 flex items-center text-xs text-[#8fa89c] gap-4 flex-wrap">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 me-1 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $profile->location }}
                                </span>
                                @if ($profile->age)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 me-1 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $profile->age }} {{ __('years old') }}
                                    </span>
                                @endif
                                @if ($profile->current_club)
                                    <span class="flex items-center font-bold text-[#10b981]">
                                        <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $profile->current_club }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Attributes Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="atlas-stat">
                    <span class="text-[11px] font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Position') }}</span>
                    <p class="atlas-stat-val font-display text-2xl mt-1">{{ $profile->position }}</p>
                </div>

                <div class="atlas-stat" style="border-left-color: var(--tx-brand-strong) !important;">
                    <span class="text-[11px] font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Preferred Foot') }}</span>
                    <p class="atlas-stat-val font-display text-2xl text-[#a3e635] mt-1">{{ $profile->preferred_foot ?? '&#8212;' }}</p>
                </div>

                <div class="atlas-stat" style="border-left-color: #f59e0b !important;">
                    <span class="text-[11px] font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Height') }}</span>
                    <p class="atlas-stat-val font-display text-2xl text-[#f59e0b] mt-1">{{ $profile->height ? $profile->height . ' cm' : '&#8212;' }}</p>
                </div>

                <div class="atlas-stat" style="border-left-color: #ef4444 !important;">
                    <span class="text-[11px] font-display uppercase tracking-[0.12em] text-[#8fa89c]">{{ __('Weight') }}</span>
                    <p class="atlas-stat-val font-display text-2xl text-[#ef4444] mt-1">{{ $profile->weight ? $profile->weight . ' kg' : '&#8212;' }}</p>
                </div>
            </div>

            <!-- Details Card -->
            <div class="atlas-card relative overflow-hidden p-6 space-y-6">
                <div class="atlas-plus-pattern"></div>
                <!-- Football Details & Personal Info -->
                <div class="relative border-b border-[#1a4030] pb-6">
                    <h3 class="font-display text-xl uppercase tracking-wide mb-4">{{ __('Football Profile Details') }}</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-xs">
                        <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                            <dt class="text-[#8fa89c] font-display uppercase tracking-[0.12em] text-[10px]">{{ __('Date of Birth') }}</dt>
                            <dd class="mt-1 text-sm font-bold text-white">
                                {{ optional($profile->date_of_birth)->format('F j, Y') }}
                                @if ($profile->age)
                                    <span class="text-[#8fa89c] font-medium text-xs">({{ $profile->age }} {{ __('years old') }})</span>
                                @endif
                            </dd>
                        </div>

                        <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                            <dt class="text-[#8fa89c] font-display uppercase tracking-[0.12em] text-[10px]">{{ __('Location') }}</dt>
                            <dd class="mt-1 text-sm font-bold text-white">{{ $profile->location }}</dd>
                        </div>

                        <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                            <dt class="text-[#8fa89c] font-display uppercase tracking-[0.12em] text-[10px]">{{ __('Current Club / Academy') }}</dt>
                            <dd class="mt-1 text-sm font-bold text-[#10b981]">{{ $profile->current_club ?? __('Free Agent / Unaffiliated') }}</dd>
                        </div>

                        <div class="bg-[#133323] p-3 rounded border border-[#1a4030]">
                            <dt class="text-[#8fa89c] font-display uppercase tracking-[0.12em] text-[10px]">{{ __('Contact Phone') }}</dt>
                            <dd class="mt-1 text-sm font-bold text-white">{{ $profile->phone ?? __('Not specified') }}</dd>
                        </div>

                        <div class="bg-[#133323] p-3 rounded border border-[#1a4030] sm:col-span-2">
                            <dt class="text-[#8fa89c] font-display uppercase tracking-[0.12em] text-[10px]">{{ __('Email') }}</dt>
                            <dd class="mt-1 text-sm font-bold text-white">{{ $profile->user->email }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Football Experience -->
                @if ($profile->football_experience)
                    <div class="relative border-b border-[#1a4030] pb-6">
                        <h3 class="font-display text-xl uppercase tracking-wide mb-2">{{ __('Football Experience') }}</h3>
                        <div class="p-4 bg-[#133323] rounded border border-[#1a4030] text-sm text-white whitespace-pre-line leading-relaxed">
                            {{ $profile->football_experience }}
                        </div>
                    </div>
                @endif

                <!-- Bio -->
                @if ($profile->bio)
                    <div class="relative">
                        <h3 class="font-display text-xl uppercase tracking-wide mb-2">{{ __('About Player') }}</h3>
                        <div class="p-4 bg-[#133323] rounded border border-[#1a4030] text-sm text-white whitespace-pre-line leading-relaxed">
                            {{ $profile->bio }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>