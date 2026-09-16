@props([
    'duration' => 4000,
])

@php
    $toasts = [];

    $statusKeyMap = [
        'profile-updated' => ['type' => 'success', 'message' => 'Profile updated successfully.'],
        'password-updated' => ['type' => 'success', 'message' => 'Password updated successfully.'],
        'verification-link-sent' => ['type' => 'success', 'message' => 'Verification link sent.'],
    ];

    foreach (['success', 'error', 'warning', 'info'] as $flashKey) {
        $message = session($flashKey);

        if ($message !== null && $message !== '') {
            $toasts[] = ['type' => $flashKey, 'message' => (string) $message];
        }
    }

    $status = session('status');

    if ($status !== null && $status !== '') {
        if (isset($statusKeyMap[$status])) {
            $toasts[] = $statusKeyMap[$status];
        } else {
            $toasts[] = ['type' => 'success', 'message' => (string) $status];
        }
    }
@endphp

<div
    x-data="toastStack({{ Js::from($toasts) }}, {{ (int) $duration }})"
    x-cloak
    class="atlas-toast-stack"
    role="region"
    aria-label="Notifications"
    aria-atomic="false"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            class="atlas-toast"
            :class="`atlas-toast--${toast.type}`"
            :role="toast.type === 'error' ? 'alert' : 'status'"
            :aria-live="toast.type === 'error' ? 'assertive' : 'polite'"
            :aria-label="toast.type === 'error' ? 'Error' : null"
            x-show="toast.visible"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
        >
            <span class="atlas-toast-icon" aria-hidden="true">
                <svg x-show="toast.type === 'success'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg x-show="toast.type === 'error'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg x-show="toast.type === 'warning'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <svg x-show="toast.type === 'info'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
            </span>

            <div class="atlas-toast-body">
                <p class="atlas-toast-message" x-text="toast.message"></p>
            </div>

            <button
                type="button"
                class="atlas-toast-close"
                @click="dismiss(toast)"
                aria-label="{{ __('Close notification') }}"
                title="{{ __('Close notification') }}"
            >
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>