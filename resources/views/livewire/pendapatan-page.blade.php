<div>
    <main id="main" class="main">

        {{-- Title dan Breadcrumb --}}
        <div class="pagetitle">
            <h1>Data Pendapatan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Penerima Bantuan</a></li>
                    <li class="breadcrumb-item active">Pendapatan</li>
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

        {{-- Form Input Pendapatan --}}
        <section class="section">
            <div class="card">
                <div class="card-body pt-3">
                    <h5 class="card-title">Form Pendapatan</h5>

                    <form wire:submit.prevent="simpan" class="row g-3">
                        {{-- Input Kode --}}
                        <div class="col-md-6">
                            <label class="form-label">Kode Pendapatan</label>
                            <input type="text" wire:model="kode_pendapatan" class="form-control" readonly>
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

                        {{-- Select Profesi --}}
                        <div class="col-md-6">
                            <label class="form-label">Profesi</label>
                            <select wire:model="kode_profesi" class="form-select">
                                <option value="">-- Pilih Profesi --</option>
                                @foreach($data_profesi as $item)
                                    <option value="{{ $item->id_profesi }}">
                                        {{ $item->nama_profesi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Pendapatan --}}
                        <div class="col-md-6">
                            <label class="form-label">Pendapatan Bulanan (Rp)</label>
                            <input type="number" wire:model="pendapatan_bulanan" class="form-control">
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
                    <h5 class="card-title">Daftar Pendapatan</h5>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Penerima</th>
                                <th>Profesi</th>
                                <th>Pendapatan (Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data_pendapatan as $index => $item)
                                <tr>
                                    <td>{{ $data_pendapatan->firstItem() + $index }}</td>
                                    <td>{{ $item->kode_pendapatan }}</td>
                                    <td>{{ $item->penerima->nama ?? '-' }}</td>
                                    <td>{{ $item->profesi->nama_profesi ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->pendapatan_bulanan, 0, ',', '.') }}</td>
                                    <td>
                                        <button wire:click="edit('{{ $item->kode_pendapatan }}')" class="btn btn-warning btn-sm">Edit</button>
                                        <button wire:click="delete('{{ $item->kode_pendapatan }}')" class="btn btn-danger btn-sm"
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

                    {{-- Pagination --}}
                    {{ $data_pendapatan->links() }}
                </div>
            </div>
        </section>
    </main>
</div>
