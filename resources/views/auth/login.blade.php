<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <!-- Tombol Register -->
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                    class="ms-3 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 active:bg-gray-300 transition ease-in-out duration-150">
                    {{ __('Register') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Tombol Install PWA -->
    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 flex flex-col items-center hidden" id="install-pwa-container">
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 text-center">Pasang aplikasi MBG FC di perangkat Anda untuk akses lebih cepat dan mudah!</p>
        <button id="btn-install-pwa" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download / Install App
        </button>
    </div>

    <!-- Script PWA Install Prompt -->
    <script>
        let deferredPrompt;
        const installContainer = document.getElementById('install-pwa-container');
        const installButton = document.getElementById('btn-install-pwa');

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the mini-infobar from appearing on mobile
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI notify the user they can install the PWA
            installContainer.classList.remove('hidden');
        });

        installButton.addEventListener('click', async () => {
            if (deferredPrompt !== null) {
                // Show the install prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('User accepted the install prompt');
                    installContainer.classList.add('hidden');
                } else {
                    console.log('User dismissed the install prompt');
                }
                // We've used the prompt, and can't use it again, throw it away
                deferredPrompt = null;
            }
        });

        window.addEventListener('appinstalled', () => {
            // Hide the app-provided install promotion
            installContainer.classList.add('hidden');
            // Clear the deferredPrompt so it can be garbage collected
            deferredPrompt = null;
            console.log('PWA was installed');
        });
    </script>
</x-guest-layout>