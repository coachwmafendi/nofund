<div>
    <x-page-header title="Settings" description="Manage your organization preferences." />

    <!-- Settings Tabs -->
    <div class="flex items-center gap-1 border-b border-slate-800 pb-px mb-6">
        <a href="{{ route('settings.general') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => true,
            'border-transparent text-slate-400 hover:text-slate-200' => false,
        ])>
            General
        </a>
        <a href="{{ route('settings.members') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => false,
            'border-transparent text-slate-400 hover:text-slate-200' => true,
        ])>
            Members
        </a>
        <a href="{{ route('settings.billing') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => false,
            'border-transparent text-slate-400 hover:text-slate-200' => true,
        ])>
            Billing
        </a>
        <a href="{{ route('settings.receipts') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => false,
            'border-transparent text-slate-400 hover:text-slate-200' => true,
        ])>
            Receipts
        </a>
        <a href="{{ route('settings.security') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => false,
            'border-transparent text-slate-400 hover:text-slate-200' => true,
        ])>
            Security
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 text-sm text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save">
        <x-card>
            <x-slot:title>General Information</x-slot:title>
            <x-slot:description>Update your organization details and preferences.</x-slot:description>

            <div class="grid gap-5 md:grid-cols-2">
                <x-form.field>
                    <x-form.label for="name">Organization Name</x-form.label>
                    <x-form.input wire:model="name" id="name" name="name" placeholder="e.g. Masjid Jamek" />
                    <x-form.error name="name" />
                </x-form.field>

                <x-form.field>
                    <x-form.label for="slug">Slug</x-form.label>
                    <x-form.input wire:model="slug" id="slug" name="slug" placeholder="e.g. masjid-jamek" />
                    <x-form.error name="slug" />
                </x-form.field>

                <x-form.field>
                    <x-form.label for="type">Type</x-form.label>
                    <x-form.select wire:model="type" id="type" name="type">
                        @foreach(\App\Enums\OrganizationType::cases() as $type)
                            <option value="{{ $type->value }}">{{ ucfirst($type->value) }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.error name="type" />
                </x-form.field>

                <x-form.field>
                    <x-form.label for="contactEmail">Contact Email</x-form.label>
                    <x-form.input wire:model="contactEmail" id="contactEmail" name="contactEmail" type="email" placeholder="contact@organization.com" />
                    <x-form.error name="contactEmail" />
                </x-form.field>

                <x-form.field>
                    <x-form.label for="contactPhone">Contact Phone</x-form.label>
                    <x-form.input wire:model="contactPhone" id="contactPhone" name="contactPhone" placeholder="+60 12-345 6789" />
                    <x-form.error name="contactPhone" />
                </x-form.field>

                <x-form.field>
                    <x-form.label for="timezone">Timezone</x-form.label>
                    <x-form.select wire:model="timezone" id="timezone" name="timezone">
                        @foreach($timezones as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.error name="timezone" />
                </x-form.field>

                <x-form.field>
                    <x-form.label for="currency">Currency</x-form.label>
                    <x-form.select wire:model="currency" id="currency" name="currency">
                        @foreach($currencies as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.error name="currency" />
                </x-form.field>
            </div>

            <x-form.field class="mt-5">
                <x-form.label for="description">Description</x-form.label>
                <x-form.textarea wire:model="description" id="description" name="description" placeholder="Brief description of your organization..." rows="3" />
                <x-form.error name="description" />
            </x-form.field>

            <x-slot:footer>
                <x-buttons.primary type="submit">Save Changes</x-buttons.primary>
            </x-slot:footer>
        </x-card>
    </form>
</div>