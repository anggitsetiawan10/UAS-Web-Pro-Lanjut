<div>
    <main id="main" class="main">
         <div class="pagetitle">
            <h1>Data Penerima Bantuan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a wire:navigate href="#">Penerima Bantuan</a></li>
                    <li class="breadcrumb-item active">Data Penerima</li>
                </ol>
            </nav>
        </div>
        <div class="card">
            <div class="card-body pt-3">
                <h5 class="card-title">Form Data Penerima Bantuan</h5>

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
                    <div class="col-md-6">
                        <label class="form-label">Kode Penerima</label>
                        <input type="text" class="form-control" wire:model="kode_penerima" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIK</label>
                        <input wire:model="nik" type="text" class="form-control @error('nik') is-invalid @enderror">
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input wire:model="nama" type="text" class="form-control @error('nama') is-invalid @enderror">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir</label>
                        <input wire:model="tanggal_lahir" type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror">
                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select wire:model="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                            <option value="">Pilih</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kontak</label>
                        <input wire:model="kontak" type="text" class="form-control @error('kontak') is-invalid @enderror">
                        @error('kontak') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea wire:model="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2"></textarea>
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Survei</label>
                        <input wire:model="tanggal_survei" type="date" class="form-control @error('tanggal_survei') is-invalid @enderror">
                        @error('tanggal_survei') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Simpan' }}</button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                    </div>
                </form>

                <hr>

                {{-- Search --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input wire:model.defer="searchInput" type="text" class="form-control" placeholder="Cari Nama atau NIK">
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


                {{-- Table --}}
                <table class="table table-bordered table-striped mt-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Kontak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                        <tr>
                            <td>{{ $data->firstItem() + $index }}</td>
                            <td>{{ $item->kode_penerima }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->nik }}</td>
                            <td>{{ $item->kontak }}</td>
                            <td>
                                <button wire:click="edit('{{ $item->kode_penerima }}')" class="btn btn-warning btn-sm">Edit</button>
                                <button wire:click="delete('{{ $item->kode_penerima }}')" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin hapus data ini?')">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Data tidak ada</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
               {{ $data->links() }}
            </div>
        </div>
    </main>
</div>
