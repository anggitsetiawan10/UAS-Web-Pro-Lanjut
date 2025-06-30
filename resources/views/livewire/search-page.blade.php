<div>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Hasil Pencarian</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    <li class="breadcrumb-item active">Search</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Pencarian</h5>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <input wire:model="query" type="text" class="form-control"
                                placeholder="Masukkan nama, kode, NIK atau kontak">
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

                    @if ($query)
                        <h6>Hasil untuk: <strong>"{{ $query }}"</strong></h6>

                        @if (count($results))
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mt-3">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>NIK</th>
                                            <th>Kontak</th>
                                            <th>Alamat</th>
                                            <th>Skor Total</th>
                                            <th>Status Kelayakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->kode_penerima }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->nik }}</td>
                                                <td>{{ $item->kontak }}</td>
                                                <td>{{ $item->alamat }}</td>
                                                <td>
                                                    {{ $item->penilaian->skor_total ?? '-' }}
                                                </td>
                                                <td>
                                                    @if($item->penilaian)
                                                        @if($item->penilaian->kode_status == 1)
                                                            <span class="badge bg-danger">Tidak Layak</span>
                                                        @elseif($item->penilaian->kode_status == 2)
                                                            <span class="badge bg-success">Layak</span>
                                                        @else
                                                            <span class="badge bg-secondary">-</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-danger mt-3">❌ Tidak ada hasil untuk "<strong>{{ $query }}</strong>"</p>
                        @endif
                    @endif

                </div>
            </div>
        </section>
    </main>
</div>
