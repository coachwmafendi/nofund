<div x-data="{ show: false, message: '', type: 'success' }"
     x-on:toast.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 4000)"
     x-show="show"
     x-transition
     class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-lg px-4 py-3 shadow-lg"
     :class="{
         'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400': type === 'success',
         'bg-red-500/10 border border-red-500/20 text-red-400': type === 'error',
         'bg-amber-500/10 border border-amber-500/20 text-amber-400': type === 'warning',
         'bg-sky-500/10 border border-sky-500/20 text-sky-400': type === 'info',
     }"
     style="display: none;"
>
    <x-heroicon-o-check-circle x-show="type === 'success'" class="w-5 h-5" />
    <x-heroicon-o-x-circle x-show="type === 'error'" class="w-5 h-5" />
    <x-heroicon-o-exclamation-triangle x-show="type === 'warning'" class="w-5 h-5" />
    <x-heroicon-o-information-circle x-show="type === 'info'" class="w-5 h-5" />
    <span class="text-sm font-medium" x-text="message"></span>
    <button @click="show = false" class="ml-2 text-slate-400 hover:text-slate-200">
        <x-heroicon-o-x-mark class="w-4 h-4" />
    </button>
</div>