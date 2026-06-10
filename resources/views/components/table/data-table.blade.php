<div class="overflow-x-auto rounded-xl border border-slate-800">
    <table class="w-full text-left border-collapse">
        <thead class="border-b border-slate-800">
            <tr>
                {{ $header }}
            </tr>
        </thead>
        <tbody>
            @if(isset($rows))
                {{ $rows }}
            @else
                {{ $slot }}
            @endif
        </tbody>
    </table>
</div>