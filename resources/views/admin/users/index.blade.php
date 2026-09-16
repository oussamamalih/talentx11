<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-display text-3xl text-white uppercase tracking-wide">
                    {{ __('User Management') }}
                </h2>
                <p class="text-xs text-[#8fa89c] mt-0.5">
                    {{ __('Platform user directory, authorization roles, and account controls') }}
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-[#133323] border border-[#1a4030] text-xs font-display text-white uppercase tracking-wider rounded hover:bg-[#1a4030] transition">
                &larr; {{ __('Back to Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Search & Filters -->
            <div class="bg-[#0d2919] rounded border border-[#1a4030] p-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <div class="sm:col-span-6">
                        <label for="search" class="block text-xs font-bold text-white uppercase tracking-wider mb-1.5">{{ __('Search User') }}</label>
                        <input type="text" name="search" id="search" value="{{ $filters['search'] }}" placeholder="Search by name or email..." class="w-full rounded border-[#1a4030] bg-[#0a1f14] text-white placeholder:text-[#8fa89c] shadow-sm focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] text-sm">
                    </div>

                    <div class="sm:col-span-4">
                        <label for="role" class="block text-xs font-bold text-white uppercase tracking-wider mb-1.5">{{ __('Filter by Role') }}</label>
                        <select name="role" id="role" class="w-full rounded border-[#1a4030] bg-[#0a1f14] text-white shadow-sm focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] text-sm">
                            <option value="">{{ __('All Roles') }}</option>
                            @foreach ($roles as $roleKey => $roleLabel)
                                <option value="{{ $roleKey }}" @selected($filters['role'] === $roleKey)>{{ $roleLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex items-end space-x-2">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-[#10b981] hover:bg-[#0d9488] text-white text-xs font-display uppercase tracking-wider rounded transition">
                            {{ __('Filter') }}
                        </button>
                        @if ($filters['search'] || $filters['role'])
                            <a href="{{ route('admin.users.index') }}" class="inline-flex justify-center items-center px-3 py-2.5 bg-[#133323] border border-[#1a4030] hover:bg-[#1a4030] text-white rounded text-xs font-display uppercase tracking-wider transition">
                                {{ __('Reset') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-[#0d2919] rounded border border-[#1a4030] overflow-hidden">
                <div class="p-6 border-b border-[#1a4030] flex items-center justify-between">
                    <h3 class="font-display text-xl text-white uppercase tracking-wide">
                        {{ __('Registered Users') }} <span class="text-xs font-normal text-[#8fa89c] ms-1">({{ $users->total() }})</span>
                    </h3>
                </div>

                @if ($users->isEmpty())
                    <div class="p-12 text-center">
                        <div class="mx-auto w-12 h-12 bg-[#133323] rounded-full flex items-center justify-center text-[#a3e635] mb-3 border border-[#1a4030]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h4 class="font-display text-xl text-white uppercase tracking-wide">{{ __('No users found') }}</h4>
                        <p class="text-xs text-[#8fa89c] mt-1">{{ __('Try adjusting your search criteria or role filters.') }}</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#1a4030] text-left text-sm">
                            <thead class="bg-[#133323] text-[11px] uppercase font-bold text-[#8fa89c] tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5">{{ __('User') }}</th>
                                    <th class="px-6 py-3.5">{{ __('Role') }}</th>
                                    <th class="px-6 py-3.5">{{ __('Profile Status') }}</th>
                                    <th class="px-6 py-3.5">{{ __('Joined') }}</th>
                                    <th class="px-6 py-3.5 text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#1a4030] bg-[#0d2919]">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-[#133323]/70 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-white">{{ $user->name }}</div>
                                            <div class="text-xs text-[#8fa89c]">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($user->isAdmin())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-950 text-purple-400">
                                                    {{ __('Admin') }}
                                                </span>
                                            @elseif ($user->isScout())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#133323] text-[#a3e635]">
                                                    {{ __('Scout') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#133323] text-[#10b981] border border-[#10b981]/40">
                                                    {{ __('Player') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($user->isPlayer())
                                                @if ($user->playerProfile)
                                                    <span class="text-xs font-bold text-[#10b981] bg-[#133323] px-2 py-0.5 rounded border border-[#10b981]/40">
                                                        {{ $user->playerProfile->position }} &bull; {{ $user->playerProfile->location }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-[#8fa89c] italic">{{ __('Profile not created') }}</span>
                                                @endif
                                            @elseif ($user->isScout())
                                                @if ($user->scoutProfile)
                                                    <span class="text-xs font-bold text-[#a3e635] bg-[#133323] px-2 py-0.5 rounded border border-[#1a4030]">
                                                        {{ $user->scoutProfile->organization }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-[#8fa89c] italic">{{ __('Profile not created') }}</span>
                                                @endif
                                            @else
                                                <span class="text-xs text-purple-400 font-semibold">{{ __('System Administrator') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-xs text-[#8fa89c]">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-2.5 py-1 text-xs font-bold text-white bg-[#133323] hover:bg-[#1a4030] rounded border border-[#1a4030] transition">
                                                {{ __('View') }}
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-2.5 py-1 text-xs font-bold text-white bg-[#10b981] hover:bg-[#0d9488] rounded transition">
                                                {{ __('Edit') }}
                                            </a>
                                            @if (Auth::id() !== $user->id)
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 text-xs font-bold text-red-400 bg-red-950 hover:bg-red-900 rounded border border-red-500/40 transition">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 border-t border-[#1a4030]">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>