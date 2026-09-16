<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-display text-3xl text-white uppercase tracking-wide">
                    {{ __('Edit User:') }} {{ $user->name }}
                </h2>
                <p class="text-xs text-[#8fa89c] mt-0.5">
                    {{ __('Modify account credentials and system role assignment') }}
                </p>
            </div>
            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-4 py-2 bg-[#133323] border border-[#1a4030] text-xs font-display text-white uppercase tracking-wider rounded hover:bg-[#1a4030] transition">
                &larr; {{ __('Cancel & Return') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#0d2919] rounded border border-[#1a4030] p-6 sm:p-8">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <!-- Role -->
                    <div>
                        <x-input-label for="role" :value="__('User Role')" />
                        <select id="role" name="role" class="mt-1 block w-full rounded border-[#1a4030] bg-[#0a1f14] text-white shadow-sm focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] text-sm">
                            @foreach ($roles as $roleKey => $roleLabel)
                                <option value="{{ $roleKey }}" @selected(old('role', $user->role) === $roleKey)>
                                    {{ $roleLabel }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role')" />
                        <p class="text-xs text-[#8fa89c] mt-1.5">
                            {{ __('Changing a user role alters their permissions and accessible interfaces across TalentX11.') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-[#1a4030]">
                        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-[#8fa89c] hover:text-white">
                            {{ __('Cancel') }}
                        </a>
                        <x-primary-button>
                            {{ __('Update User') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>