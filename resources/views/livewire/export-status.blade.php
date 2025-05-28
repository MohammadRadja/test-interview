<div>
    @if ($exports->whereIn('status', ['pending', 'processing'])->count() > 0)
        <div class="alert alert-info">Export sedang diproses...</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID Export</th>
                <th>User</th>
                <th>File</th>
                <th>Status</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($exports as $export)
                <tr>
                    <td>{{ $export->id }}</td>
                    <td>{{ $export->user->name ?? 'User tidak ditemukan' }}</td>
                    <td>
                        @if ($export->file_path && $export->status === 'done')
                            <a href="{{ asset('storage/' . $export->file_path) }}" target="_blank">Download</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @php
                            $badgeClass = match ($export->status) {
                                'pending' => 'secondary',
                                'processing' => 'info',
                                'done' => 'success',
                                'failed' => 'danger',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($export->status) }}</span>
                    </td>
                    <td>{{ $export->created_at->format('d M Y H:i:s') }}</td>
                    <td>{{ $export->updated_at && $export->status === 'done' ? $export->updated_at->format('d M Y H:i:s') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada riwayat export.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        setInterval(() => {
            Livewire.dispatch('refreshExportStatus');
        }, 5000);
    </script>
</div>
