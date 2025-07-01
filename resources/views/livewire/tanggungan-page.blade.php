<div>
    <main id="main" class="main">

        {{-- Title dan Breadcrumb --}}
        <div class="pagetitle">
            <h1>Data Tanggungan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Penerima Bantuan</a></li>
                    <li class="breadcrumb-item active">Tanggungan</li>
                </ol>
            </nav>
        </div>

        {{-- Notifikasi --}}
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Form Input Tanggungan --}}
        <section class="section">
            <div class="card">
                <div class="card-body pt-3">
                    <h5 class="card-title">Form Tanggungan</h5>

                    <form wire:submit.prevent="simpan" class="row g-3">
                        {{-- Input Kode --}}
                        <div class="col-md-6">
                            <label class="form-label">Kode Tanggungan</label>
                            <input type="text" class="form-control" wire:model="kode_tanggungan" readonly>
                        </div>

                        {{-- Select Penerima --}}
                        <div class="col-md-6">
                            <label class="form-label">Penerima</label>
                            <select wire:model="kode_penerima" class="form-select">
                                <option value="">-- Pilih Penerima --</option>
                                @foreach($data_penerima as $item)
                                    <option value="{{ $item->kode_penerima }}">
                                        {{ $item->kode_penerima }} - {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Jumlah Anak --}}
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Anak</label>
                            <input type="number" class="form-control" wire:model="jumlah_anak">
                        </div>

                        {{-- Input Anak Sekolah --}}
                        <div class="col-md-4">
                            <label class="form-label">Anak Sekolah</label>
                            <input type="number" class="form-control" wire:model="anak_sekolah">
                        </div>

                        {{-- Input Anak Belum Sekolah --}}
                        <div class="col-md-4">
                            <label class="form-label">Anak Belum Sekolah</label>
                            <input type="number" class="form-control" wire:model="anak_belum_sekolah">
                        </div>

                        {{-- Input Tanggungan Lain --}}
                        <div class="col-md-6">
                            <label class="form-label">Tanggungan Lain</label>
                            <input type="number" class="form-control" wire:model="jumlah_tanggungan_lain">
                        </div>

                        {{-- Tombol --}}
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" wire:click="resetInput" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table List --}}
            <div class="card">
                <div class="card-body pt-3">
                    <h5 class="card-title">Daftar Tanggungan</h5>

                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Penerima</th>
                                <th>Jumlah Anak</th>
                                <th>Sekolah</th>
                                <th>Belum Sekolah</th>
                                <th>Tanggungan Lain</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data_tanggungan as $index => $item)
                                <tr>
                                    <td>{{ $data_tanggungan->firstItem() + $index }}</td>
                                    <td>{{ $item->kode_tanggungan }}</td>
                                    <td>{{ $item->penerima->nama ?? '-' }}</td>
                                    <td>{{ $item->jumlah_anak }}</td>
                                    <td>{{ $item->anak_sekolah }}</td>
                                    <td>{{ $item->anak_belum_sekolah }}</td>
                                    <td>{{ $item->jumlah_tanggungan_lain }}</td>
                                    <td>
                                        <button wire:click="edit('{{ $item->kode_tanggungan }}')" class="btn btn-warning btn-sm">Edit</button>
                                        <button wire:click="delete('{{ $item->kode_tanggungan }}')" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin hapus data ini?')">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    {{ $data_tanggungan->links() }}
                </div>
            </div>
        </section>
    </main>
</div>
