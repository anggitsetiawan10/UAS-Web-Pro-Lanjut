<div>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Data Kendaraan Penerima</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a wire:navigate href="#">Penerima Bantuan</a></li>
                    <li class="breadcrumb-item active">Kendaraan</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div class="card-body pt-3">
                <h5 class="card-title">Form Data Kendaraan</h5>

                {{-- Alert --}}
                @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                {{-- Form --}}
                <form wire:submit.prevent="simpan" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Kendaraan</label>
                        <input type="text" class="form-control" wire:model="kode_kendaraan" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kode Penerima</label>
                        <select wire:model="kode_penerima" class="form-select @error('kode_penerima') is-invalid @enderror">
                            <option value="">-- Pilih Penerima --</option>
                            @foreach($data_penerima as $item)
                                <option value="{{ $item->kode_penerima }}">
                                    {{ $item->kode_penerima }} - {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kode_penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jenis Kendaraan</label>
                        <select wire:model="id_jenis_kendaraan" class="form-select @error('id_jenis_kendaraan') is-invalid @enderror">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($jenis_kendaraan as $item)
                                <option value="{{ $item->id_jenis_kendaraan }}">{{ $item->jenis }}</option>
                            @endforeach
                        </select>
                        @error('id_jenis_kendaraan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jumlah</label>
                        <input wire:model="jumlah" type="number" class="form-control @error('jumlah') is-invalid @enderror">
                        @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" wire:click="resetInput" class="btn btn-secondary">Reset</button>
                    </div>
                </form>

                <hr>

                {{-- Table --}}
                <table class="table table-bordered table-striped mt-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Penerima</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_kendaraan as $index => $item)
                        <tr>
                            <td>{{ $data_kendaraan->firstItem() + $index }}</td>
                            <td>{{ $item->kode_kendaraan }}</td>
                            <td>{{ $item->penerima->nama ?? '-' }}</td>
                            <td>{{ $item->jenisKendaraan->jenis ?? '-' }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>
                                <button wire:click="edit('{{ $item->kode_kendaraan }}')" class="btn btn-warning btn-sm">Edit</button>
                                <button wire:click="delete('{{ $item->kode_kendaraan }}')" class="btn btn-danger btn-sm"
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
                {{ $data_kendaraan->links() }}
            </div>
        </div>

    </main>
</div>
