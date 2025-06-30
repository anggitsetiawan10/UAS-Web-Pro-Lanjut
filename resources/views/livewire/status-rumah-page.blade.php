<div>
    <main id="main" class="main">

        {{-- Judul Halaman --}}
        <div class="pagetitle">
            <h1>Data Status Rumah</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a wire:navigate href="#">Penerima Bantuan</a></li>
                    <li class="breadcrumb-item active">Status Rumah</li>
                </ol>
            </nav>
        </div>

        {{-- Notifikasi --}}
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <section class="section">

            {{-- Form Input --}}
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Status Rumah</h5>

                    <form wire:submit.prevent="simpan" class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Kode Rumah</label>
                            <input type="text" wire:model="kode_kepemilikan_rumah" class="form-control" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kode Penerima</label>
                            <select wire:model="kode_penerima" class="form-select @error('kode_penerima') is-invalid @enderror">
                                <option value="">-- Pilih Penerima --</option>
                                @foreach($data_penerima as $item)
                                    <option value="{{ $item->kode_penerima }}">{{ $item->kode_penerima }} - {{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('kode_penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status Rumah</label>
                            <select wire:model="id_status_rumah" class="form-select @error('id_status_rumah') is-invalid @enderror">
                                <option value="">-- Pilih Status --</option>
                                @foreach($status_rumah as $item)
                                    <option value="{{ $item->id_status }}">{{ $item->status_rumah }}</option>
                                @endforeach
                            </select>
                            @error('id_status_rumah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Luas Rumah (m²)</label>
                            <input type="number" wire:model="luas_rumah" class="form-control @error('luas_rumah') is-invalid @enderror">
                            @error('luas_rumah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kondisi Rumah</label>
                            <select wire:model="id_kondisi" class="form-select @error('id_kondisi') is-invalid @enderror">
                                <option value="">-- Pilih Kondisi --</option>
                                @foreach($kondisi_rumah as $item)
                                    <option value="{{ $item->id_kondisi }}">{{ $item->kondisi_rumah }}</option>
                                @endforeach
                            </select>
                            @error('id_kondisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Rumah</label>
                            <select wire:model="id_jenis" class="form-select @error('id_jenis') is-invalid @enderror">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenis_rumah as $item)
                                    <option value="{{ $item->id_jenis }}">{{ $item->jenis_rumah }}</option>
                                @endforeach
                            </select>
                            @error('id_jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jumlah Penghuni</label>
                            <input type="number" wire:model="jumlah_penghuni" class="form-control @error('jumlah_penghuni') is-invalid @enderror">
                            @error('jumlah_penghuni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" wire:click="resetInput" class="btn btn-secondary">Reset</button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Daftar Status Rumah</h5>

                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Rumah</th>
                                <th>Penerima</th>
                                <th>Status</th>
                                <th>Luas</th>
                                <th>Kondisi</th>
                                <th>Jenis</th>
                                <th>Penghuni</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_rumah as $index => $item)
                                <tr>
                                    <td>{{ $data_rumah->firstItem() + $index }}</td>
                                    <td>{{ $item->kode_kepemilikan_rumah }}</td>
                                    <td>{{ $item->penerima->nama ?? '-' }}</td>
                                    <td>{{ $item->status->status_rumah ?? '-' }}</td>
                                    <td>{{ $item->luas_rumah }} m²</td>
                                    <td>{{ $item->kondisi->kondisi_rumah ?? '-' }}</td>
                                    <td>{{ $item->jenis->jenis_rumah ?? '-' }}</td>
                                    <td>{{ $item->jumlah_penghuni }}</td>
                                    <td>
                                        <button wire:click="edit('{{ $item->kode_kepemilikan_rumah }}')" class="btn btn-warning btn-sm">
                                            Edit
                                        </button>
                                        <button wire:click="delete('{{ $item->kode_kepemilikan_rumah }}')" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                     {{ $data_rumah->links() }}
                </div>
            </div>

        </section>
    </main>
</div>
