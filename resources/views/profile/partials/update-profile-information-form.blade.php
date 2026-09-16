<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<div>
    <h2 class="font-display text-lg uppercase tracking-wider text-white">Profile Information</h2>
    <p class="mt-1 text-xs text-[#8fa89c]">Update your account's profile information and email address.</p>
</div>

<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
    @csrf
    @method('patch')

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-xs font-semibold text-[#8fa89c] font-display uppercase tracking-wider mb-1.5">Name</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
                class="block w-full rounded border bg-[#133323] border-[#1a4030] text-white text-sm px-4 py-2.5 placeholder-[#8fa89c]/50 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none transition-colors duration-200"
            />
            @error('name')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-[#8fa89c] font-display uppercase tracking-wider mb-1.5">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
                class="block w-full rounded border bg-[#133323] border-[#1a4030] text-white text-sm px-4 py-2.5 placeholder-[#8fa89c]/50 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none transition-colors duration-200"
            />
            @error('email')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="rounded border bg-amber-900/20 border-amber-800/40 p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="text-sm text-amber-300 font-medium">
                        Your email address is unverified.
                    </p>
                    <button
                        form="send-verification"
                        class="mt-1.5 text-xs font-semibold text-amber-400 underline hover:text-amber-300 transition-colors duration-200 font-display uppercase"
                    >
                        Click here to re-send the verification email.
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-center gap-4">
        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded bg-[#10b981] text-[#0a1f14] font-display uppercase text-sm font-semibold hover:bg-[#10b981]/90 transition-colors duration-200">
            Save
        </button>
    </div>
</form>
