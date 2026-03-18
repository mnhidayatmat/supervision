<x-layouts.app title="Storage Settings">
    <x-slot:header>Settings</x-slot:header>

    <div class="max-w-2xl" x-data="storageSettings('{{ $currentDisk }}')">
        {{-- Page header --}}
        <div class="mb-6">
            <h2 class="text-base font-semibold text-primary">Storage Configuration</h2>
            <p class="text-xs text-secondary mt-0.5">Configure where uploaded files and documents are stored</p>
        </div>

        {{-- Disk selector --}}
        <x-card title="Storage Driver" class="mb-4">
            <div class="grid grid-cols-3 gap-3">
                @foreach(['local' => 'Local Disk', 'do_spaces' => 'DO Spaces', 'google_drive' => 'Google Drive'] as $disk => $label)
                    <label
                        class="relative flex flex-col items-center gap-2 p-4 border rounded-lg cursor-pointer transition-all"
                        :class="activeDisk === '{{ $disk }}' ? 'border-accent bg-amber-50' : 'border-border hover:border-gray-300 bg-white'"
                    >
                        <input type="radio" name="disk_selector" value="{{ $disk }}" x-model="activeDisk" class="sr-only">
                        @if($disk === 'local')
                            <svg class="w-6 h-6" :class="activeDisk === '{{ $disk }}' ? 'text-accent' : 'text-secondary'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                        @elseif($disk === 'do_spaces')
                            <svg class="w-6 h-6" :class="activeDisk === '{{ $disk }}' ? 'text-accent' : 'text-secondary'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                        @else
                            <svg class="w-6 h-6" :class="activeDisk === '{{ $disk }}' ? 'text-accent' : 'text-secondary'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        @endif
                        <span class="text-xs font-medium" :class="activeDisk === '{{ $disk }}' ? 'text-accent' : 'text-secondary'">
                            {{ $label }}
                        </span>
                        <div x-show="activeDisk === '{{ $disk }}'" class="absolute top-2 right-2">
                            <svg class="w-3.5 h-3.5 text-accent" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                    </label>
                @endforeach
            </div>
        </x-card>

        {{-- Config forms --}}
        <form method="POST" action="{{ route('admin.settings.storage.update') }}">
            @csrf
            <input type="hidden" name="storage_disk" :value="activeDisk">

            {{-- Local disk config --}}
            <div x-show="activeDisk === 'local'" x-cloak>
                <x-card title="Local Disk Settings" class="mb-4">
                    <p class="text-xs text-secondary">Files are stored on the server's local filesystem. No additional configuration needed.</p>
                </x-card>
            </div>

            {{-- DO Spaces config --}}
            <div x-show="activeDisk === 'do_spaces'" x-cloak>
                <x-card title="DigitalOcean Spaces Settings" class="mb-4">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-input
                                label="Access Key ID"
                                name="do_spaces_key"
                                :value="old('do_spaces_key', $settings['do_spaces_key'] ?? '')"
                                placeholder="Your DO Spaces access key"
                            />
                            <div>
                                <label for="do_spaces_secret" class="block text-sm font-medium text-primary mb-1">Secret Access Key</label>
                                <input
                                    type="password"
                                    name="do_spaces_secret"
                                    id="do_spaces_secret"
                                    value="{{ old('do_spaces_secret', !empty($settings['do_spaces_secret']) ? '••••••••' : '') }}"
                                    placeholder="Your DO Spaces secret"
                                    class="w-full rounded-lg border border-border bg-white px-3 py-2 text-sm text-primary placeholder-secondary/50 focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-colors"
                                >
                            </div>
                            <x-input
                                label="Bucket / Space Name"
                                name="do_spaces_bucket"
                                :value="old('do_spaces_bucket', $settings['do_spaces_bucket'] ?? '')"
                                placeholder="my-research-files"
                            />
                            <div>
                                <label for="do_spaces_region" class="block text-sm font-medium text-primary mb-1">Region</label>
                                <select name="do_spaces_region" id="do_spaces_region" class="w-full rounded-lg border border-border bg-white px-3 py-2 text-sm text-primary focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-colors">
                                    @foreach(['nyc3' => 'New York 3', 'sfo3' => 'San Francisco 3', 'ams3' => 'Amsterdam 3', 'sgp1' => 'Singapore 1', 'fra1' => 'Frankfurt 1', 'blr1' => 'Bangalore 1', 'syd1' => 'Sydney 1'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('do_spaces_region', $settings['do_spaces_region'] ?? 'sgp1') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <x-input
                                    label="CDN Endpoint (Optional)"
                                    name="do_spaces_endpoint"
                                    :value="old('do_spaces_endpoint', $settings['do_spaces_endpoint'] ?? '')"
                                    placeholder="https://myspace.sgp1.cdn.digitaloceanspaces.com"
                                />
                                <p class="text-xs text-secondary mt-1">Leave blank to use the default Spaces endpoint</p>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            {{-- Google Drive config --}}
            <div x-show="activeDisk === 'google_drive'" x-cloak>
                <x-card title="Google Drive Settings" class="mb-4">
                    <div class="space-y-4">
                        <x-input
                            label="Client ID"
                            name="google_drive_client_id"
                            :value="old('google_drive_client_id', $settings['google_drive_client_id'] ?? '')"
                            placeholder="Your Google OAuth client ID"
                        />
                        <div>
                            <label for="google_drive_client_secret" class="block text-sm font-medium text-primary mb-1">Client Secret</label>
                            <input
                                type="password"
                                name="google_drive_client_secret"
                                id="google_drive_client_secret"
                                value="{{ old('google_drive_client_secret', !empty($settings['google_drive_client_secret']) ? '••••••••' : '') }}"
                                placeholder="Your Google OAuth client secret"
                                class="w-full rounded-lg border border-border bg-white px-3 py-2 text-sm text-primary placeholder-secondary/50 focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-colors"
                            >
                        </div>
                        <x-input
                            label="Refresh Token"
                            name="google_drive_refresh_token"
                            :value="old('google_drive_refresh_token', $settings['google_drive_refresh_token'] ?? '')"
                            placeholder="OAuth refresh token"
                        />
                        <x-input
                            label="Folder ID (Optional)"
                            name="google_drive_folder_id"
                            :value="old('google_drive_folder_id', $settings['google_drive_folder_id'] ?? '')"
                            placeholder="Root folder ID for uploads"
                        />
                        <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg">
                            <p class="text-xs text-blue-700">
                                To get a refresh token, authorize your app via the
                                <a href="https://developers.google.com/oauthplayground" target="_blank" class="underline">Google OAuth Playground</a>
                                using the Drive API scope.
                            </p>
                        </div>
                    </div>
                </x-card>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between">
                {{-- Test connection button --}}
                <div x-data="testConnection()" x-show="activeDisk !== 'local'">
                    <button
                        type="button"
                        @click="test()"
                        :disabled="testing"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-secondary border border-border rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg x-show="!testing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <svg x-show="testing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span x-text="testing ? 'Testing...' : 'Test Connection'"></span>
                    </button>
                    <p x-show="result" x-cloak class="mt-2 text-xs" :class="success ? 'text-green-600' : 'text-red-600'" x-text="result"></p>
                </div>
                <div x-show="activeDisk === 'local'"></div>

                <div class="flex items-center gap-3">
                    <x-button type="submit" variant="primary">Save Settings</x-button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function storageSettings(currentDisk) {
            return {
                activeDisk: currentDisk || 'local',
            }
        }

        function testConnection() {
            return {
                testing: false,
                result: '',
                success: false,
                async test() {
                    this.testing = true;
                    this.result = '';
                    try {
                        const res = await axios.post('{{ route('admin.settings.storage.test') }}', {
                            disk: document.querySelector('input[name="storage_disk"]').value
                        });
                        this.success = true;
                        this.result = res.data.message || 'Connection successful';
                    } catch (e) {
                        this.success = false;
                        this.result = e.response?.data?.message || 'Connection failed. Check your credentials.';
                    } finally {
                        this.testing = false;
                    }
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
