<main class="mt-0 transition-all duration-200 ease-in-out">
    <section>
        <div class="relative flex items-center justify-center min-h-screen p-0 overflow-hidden bg-center bg-cover">
            <div class="container z-1">
                <div class="flex flex-wrap justify-center -mx-3">
                    <div class="flex flex-col w-full max-w-full px-3 mx-auto shrink-0 md:w-6/12 lg:w-5/12 xl:w-4/12">
                        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-lg dark:bg-gray-950 rounded-2xl bg-clip-border">
                            <div class="p-6 pb-0 mb-0 text-center">
                                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="h-20 mx-auto mb-4">
                                <h4 class="font-bold">Sign In</h4>
                                <p class="mb-0">Enter your email and password to sign in</p>

                                @if ($isError)
                                    <div class="bg-red-400 rounded-md p-3 mt-4">
                                        <p class="mb-0 text-neutral-800">Email atau password salah!</p>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-auto p-6">
                                <form wire:submit.prevent='handleLogin'>
                                    <div class="mb-4">
                                        <input type="email" placeholder="Email" wire:model="email"
                                            class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
                                        @error('email')
                                            <div class="text-red-500 text-sm">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <input type="password" placeholder="Password" wire:model="password"
                                            class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
                                        @error('password')
                                            <div class="text-red-500 text-sm">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="text-center">
                                        <x-ui.button type="submit" title="Sign In" color="primary" wireLoading
                                            formAction="handleLogin" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>