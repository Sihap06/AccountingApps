<!-- sidenav  -->
<aside
    class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand xl:ml-6 z-90 rounded-2xl xl:left-0 md:translate-x-0"
    aria-expanded="false">

    <div class="h-auto">
        <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden"
            sidenav-close></i>
        {{-- <a class="block text-center px-8 py-6 m-0 text-xl font-semibold whitespace-nowrap dark:text-white text-slate-700"
            href="#" target="_blank">
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">Accounting Apps</span>
        </a> --}}
        <div class="relative px-8 py-6 m-0 flex flex-col min-w-0 break-words bg-transparent border-0 shadow-none rounded-2xl bg-clip-border"
            sidenav-card="">
            <img class="w-full mx-auto" src="{{ asset('assets/img/logo.png') }}" alt="logo">
        </div>
    </div>

    <hr
        class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

    <div class="items-center block w-auto max-h-screen overflow-auto grow basis-full">
        <ul class="flex flex-col pl-0 mb-0">
            <li class="mt-0.5 w-full">
                <a class="py-2.7 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 transition-colors {{ request()->routeIs('dashboard.index') ? 'bg-blue-500/13 text-slate-700 font-semibold' : '' }}"
                    href="{{ route('dashboard.index') }}">
                    <div
                        class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-blue-500 ni ni-tv-2"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Dashboard</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <a class=" dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 rounded-lg transition-colors {{ request()->routeIs('dashboard.inventory') ? 'bg-blue-500/13 text-slate-700 font-semibold' : '' }}"
                    href="{{ route('dashboard.inventory') }}">
                    <div
                        class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-orange-500 ni ni-calendar-grid-58"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Inventory</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <a class=" dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 rounded-lg transition-colors {{ request()->routeIs('dashboard.point-of-sales') ? 'bg-blue-500/13 text-slate-700 font-semibold' : '' }}"
                    href="{{ route('dashboard.point-of-sales') }}">
                    <div
                        class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center fill-current stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-emerald-500 ni ni-credit-card"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">POS</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <a class="rounded-lg dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('dashboard.reporting') ? 'bg-blue-500/13 text-slate-700 font-semibold' : '' }}"
                    href="{{ route('dashboard.reporting') }}">
                    <div
                        class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-cyan-500 ni ni-app"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Reporting</span>
                </a>
            </li>
            @if (auth()->user()->role !== 'sysadmin')
                <li class="mt-0.5 w-full">
                    <a class=" dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('dashboard.teknisi.index') ? 'bg-blue-500/13 text-slate-700 font-semibold' : '' }}"
                        href="{{ route('dashboard.teknisi.index') }}">
                        <div
                            class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="relative top-0 text-sm leading-normal text-yellow-500 ni ni-planet"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Technician</span>
                    </a>
                </li>
            @endif
            <li class="mt-0.5 w-full">
                <a class=" dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap px-4 transition-colors {{ request()->routeIs('dashboard.customers.index') ? 'bg-blue-500/13 text-slate-700 font-semibold' : '' }}"
                    href="{{ route('dashboard.customers.index') }}">
                    <div
                        class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-neutral-800 ni ni-user-run"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Customers</span>
                </a>
            </li>


        </ul>
    </div>

    <div class="absolute bottom-0 pb-4 px-8 py-6 w-full">

        <form action="{{ url('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="relative mt-20 inline-block w-full py-2 text-xs font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-slate-800 border-0 rounded-lg shadow-md select-none bg-150 bg-x-25 hover:shadow-xs hover:-translate-y-px"
                target="_blank">Logout</button>
        </form>
    </div>

</aside>

<!-- end sidenav -->
