<div class="overflow-x-auto rounded-xl border border-slate-800">
    <table class="w-full text-left border-collapse">
        <thead class="border-b border-slate-800">
            <tr>
                {{ $header }}
            </tr>
        </thead>
        <tbody>
            @if($rows->count() > 0)
                {{ $rows }}
            @else
                <tr>
                    <td colspan="100%" class="px-4 py-12 text-center">
                        {{ $empty }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>