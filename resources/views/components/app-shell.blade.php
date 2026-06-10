<div class="min-h-screen bg-slate-950 text-slate-50 flex">
    <x-sidebar />
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
        <x-topbar />
        <main class="flex-1 p-6 md:p-8 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
    <x-toast />
</div>