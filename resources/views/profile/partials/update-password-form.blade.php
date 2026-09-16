<div>
    <h2 class="font-display text-lg uppercase tracking-wider text-white">Update Password</h2>
    <p class="mt-1 text-xs text-[#8fa89c]">Ensure your account is using a long, random password to stay secure.</p>
</div>

<form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
    @csrf
    @method('put')

    <div>
        <label for="current_password" class="block text-xs font-semibold text-[#8fa89c] font-display uppercase tracking-wider mb-1.5">Current Password</label>
        <input
            id="current_password"
            name="current_password"
            type="password"
            autocomplete="current-password"
            class="block w-full rounded border bg-[#133323] border-[#1a4030] text-white text-sm px-4 py-2.5 placeholder-[#8fa89c]/50 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none transition-colors duration-200"
        />
        @error('current_password', 'updatePassword')
            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <label for="password" class="block text-xs font-semibold text-[#8fa89c] font-display uppercase tracking-wider mb-1.5">New Password</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="block w-full rounded border bg-[#133323] border-[#1a4030] text-white text-sm px-4 py-2.5 placeholder-[#8fa89c]/50 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none transition-colors duration-200"
            />
            @error('password', 'updatePassword')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-[#8fa89c] font-display uppercase tracking-wider mb-1.5">Confirm Password</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="block w-full rounded border bg-[#133323] border-[#1a4030] text-white text-sm px-4 py-2.5 placeholder-[#8fa89c]/50 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none transition-colors duration-200"
            />
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded bg-[#10b981] text-[#0a1f14] font-display uppercase text-sm font-semibold hover:bg-[#10b981]/90 transition-colors duration-200">
            Save
        </button>
    </div>
</form>
