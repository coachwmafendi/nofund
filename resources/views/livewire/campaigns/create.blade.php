<div>
    <x-page-header
        title="Create Campaign"
        description="Start a new fundraising campaign."
    >
        <x-slot:actions>
            <x-buttons.ghost onclick="history.back()">Cancel</x-buttons.ghost>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <form wire:submit="create" class="space-y-6">
            {{-- Title --}}
            <x-form.field>
                <x-form.label for="title" required>Title</x-form.label>
                <x-form.input
                    id="title"
                    type="text"
                    wire:model="title"
                    placeholder="e.g., Wakaf Pembinaan Masjid Al-Ikhlas"
                    autocomplete="off"
                />
                <x-form.error name="title" />
            </x-form.field>

            {{-- Slug --}}
            <x-form.field>
                <x-form.label for="slug" required>Slug</x-form.label>
                <x-form.input
                    id="slug"
                    type="text"
                    wire:model="slug"
                    placeholder="e.g., wakaf-pembinaan-masjid-al-ikhlas"
                    autocomplete="off"
                />
                <p class="mt-1 text-xs text-slate-500">Leave empty to auto-generate from title. Must be unique.</p>
                <x-form.error name="slug" />
            </x-form.field>

            {{-- Description --}}
            <x-form.field>
                <x-form.label for="description">Description</x-form.label>
                <x-form.textarea
                    id="description"
                    wire:model="description"
                    rows="4"
                    placeholder="Describe your campaign, its goals, and impact..."
                />
                <x-form.error name="description" />
            </x-form.field>

            {{-- Target Amount --}}
            <x-form.field>
                <x-form.label for="targetAmount" required>Target Amount (RM)</x-form.label>
                <x-form.input
                    id="targetAmount"
                    type="number"
                    wire:model="targetAmount"
                    min="1"
                    step="0.01"
                    placeholder="10000.00"
                />
                <x-form.error name="targetAmount" />
            </x-form.field>

            {{-- Category --}}
            <x-form.field>
                <x-form.label for="category" required>Category</x-form.label>
                <x-form.select id="category" wire:model="category">
                    <option value="">Select a category</option>
                    <option value="Wakaf">Wakaf</option>
                    <option value="Zakat">Zakat</option>
                    <option value="Sedekah">Sedekah</option>
                    <option value="Dana Pendidikan">Dana Pendidikan</option>
                    <option value="Bantuan Kecemasan">Bantuan Kecemasan</option>
                    <option value="Pembangunan">Pembangunan</option>
                    <option value="General Donation">General Donation</option>
                </x-form.select>
                <x-form.error name="category" />
            </x-form.field>

            {{-- Dates Row --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <x-form.field>
                    <x-form.label for="startDate">Start Date</x-form.label>
                    <x-form.input
                        id="startDate"
                        type="date"
                        wire:model="startDate"
                    />
                    <x-form.error name="startDate" />
                </x-form.field>

                <x-form.field>
                    <x-form.label for="endDate">End Date</x-form.label>
                    <x-form.input
                        id="endDate"
                        type="date"
                        wire:model="endDate"
                    />
                    <x-form.error name="endDate" />
                </x-form.field>
            </div>

            {{-- Visibility --}}
            <x-form.field>
                <x-form.label for="visibility" required>Visibility</x-form.label>
                <x-form.select id="visibility" wire:model="visibility">
                    <option value="public">Public — Visible to everyone</option>
                    <option value="unlisted">Unlisted — Accessible via direct link only</option>
                    <option value="private">Private — Only visible to organization members</option>
                </x-form.select>
                <x-form.error name="visibility" />
            </x-form.field>

            {{-- Submit --}}
            <div class="flex items-center justify-end pt-4 border-t border-slate-800">
                <x-buttons.primary type="submit">
                    Create Campaign
                </x-buttons.primary>
            </div>
        </form>
    </x-card>
</div>