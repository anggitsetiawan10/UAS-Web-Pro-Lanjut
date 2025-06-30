<div>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Data Penilaian</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Penilaian</a></li>
                    <li class="breadcrumb-item active">Data Penilaian</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div class="card-body pt-3">
                <h5 class="card-title">Form Penilaian</h5>

                {{-- Alert --}}
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Form --}}
                <form wire:submit.prevent="{{ $editMode ? 'update' : 'save' }}" class="row g-3">

                    <!-- <div class="col-md-6">
                        <label class="form-label">Kode Penilaian</label>
                        <input type="text" class="form-control" wire:model="kode_penilaian" readonly>
                    </div> -->
    
                    <div class="col-md-6">
                        <label class="form-label">Penerima</label>
                        <select wire:model="kode_penerima" class="form-select @error('kode_penerima') is-invalid @enderror">
                            <option value="">Pilih Penerima</option>
                            @foreach($data_penerima as $item)
                                <option value="{{ $item->kode_penerima }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        @error('kode_penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Skor Rumah</label>
                        <input type="number" wire:model="skor_rumah" class="form-control @error('skor_rumah') is-invalid @enderror">
                        @error('skor_rumah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Skor Kendaraan</label>
                        <input type="number" wire:model="skor_kendaraan" class="form-control @error('skor_kendaraan') is-invalid @enderror">
                        @error('skor_kendaraan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Skor Pendapatan</label>
                        <input type="number" wire:model="skor_pendapatan" class="form-control @error('skor_pendapatan') is-invalid @enderror">
                        @error('skor_pendapatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Skor Tanggungan</label>
                        <input type="number" wire:model="skor_tanggungan" class="form-control @error('skor_tanggungan') is-invalid @enderror">
                        @error('skor_tanggungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Total Skor</label>
                        <input type="number" wire:model="skor_total" class="form-control @error('skor_total') is-invalid @enderror" readonly>
                        @error('skor_total') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status Kelayakan</label>
                        <select wire:model="kode_status" class="form-select @error('kode_status') is-invalid @enderror">
                            <option value="">Pilih Status</option>
                            @foreach($data_status as $status)
                                <option value="{{ $status->id_status }}">{{ $status->nama_status }}</option>
                            @endforeach
                        </select>
                        @error('kode_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Penilaian</label>
                        <input type="date" wire:model="tanggal_penilaian" class="form-control @error('tanggal_penilaian') is-invalid @enderror">
                        @error('tanggal_penilaian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea wire:model="catatan" rows="2" class="form-control"></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            {{ $editMode ? 'Update' : 'Simpan' }}
                        </button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                    </div>
                </form>

                <hr>

                {{-- Search --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input wire:model.defer="searchInput" type="text" class="form-control" placeholder="Cari Nama Penerima">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button wire:click="doSearch" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button wire:click="resetSearch" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                    </div>
                </div>
                <!-- Tombol redreh perhitungan dan export -->
                <div class="d-flex gap-2 mb-3">
                    <a href="{{ route('export.pdf') }}" target="_blank" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </a>

                    <button
                        wire:click="refreshSAW"
                        onclick="return confirm('Apakah Anda yakin ingin memperbarui seluruh perhitungan SAW?')"
                        class="btn btn-outline-success">
                        🔄 Perbarui Perhitungan SAW
                    </button>
                </div>

                {{-- Table --}}
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Penerima</th>
                            <th>Total Skor</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                            <tr>
                                <td>{{ $data->firstItem() + $index }}</td>
                                <td>{{ $item->kode_penerima }}</td>
                                <td>{{ $item->penerima->nama ?? '-' }}</td>
                                <td>{{ $item->skor_total }}</td>
                                <td>{{ $item->status->nama_status ?? '-' }}</td>
                                <td>{{ $item->tanggal_penilaian }}</td>
                                <td>
                                    <button wire:click="edit('{{ $item->kode_penerima }}')" class="btn btn-warning btn-sm">
                                        Edit
                                    </button>
                                    <button wire:click="delete('{{ $item->kode_penerima }}')" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                {{ $data->links() }}

            </div>
        </div>

    </main>
</div>
