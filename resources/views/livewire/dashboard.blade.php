<div>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a wire:navigate href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
             <a href="{{ route('export.pdf') }}" target="_blank" class="btn btn-danger mb-3">
                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
            <!-- Statistik -->
            <div class="row">

                <div class="col-xxl-4 col-xl-12">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Penerima</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $total }}</h6>
                                    <span class="text-muted small pt-2 ps-1">Total Data</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Laki-laki</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center  bg-info">
                                    <i class="bi bi-gender-male"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $totalLaki }}</h6>
                                    <span class="text-muted small pt-2 ps-1">Total Laki-laki</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Perempuan</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-gender-female"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $totalPerempuan }}</h6>
                                    <span class="text-muted small pt-2 ps-1">Total Perempuan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Langkah Penginputan dan Data -->
            <div class="row">

                <!-- Langkah -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Langkah Penginputan</h5>
                            <div class="activity">
                                @php
                                    $steps = [
                                        ['label' => 'Step 1', 'text' => 'Data Calon Penerima', 'color' => 'primary'],
                                        ['label' => 'Step 2', 'text' => 'Data Kendaraan', 'color' => 'success'],
                                        ['label' => 'Step 3', 'text' => 'Data Rumah', 'color' => 'warning'],
                                        ['label' => 'Step 4', 'text' => 'Data Pendapatan', 'color' => 'info'],
                                        ['label' => 'Step 5', 'text' => 'Data Tanggungan', 'color' => 'danger'],
                                    ];
                                @endphp
                                @foreach ($steps as $step)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $step['label'] }}</div>
                                    <i class="bi bi-circle-fill activity-badge text-{{ $step['color'] }} align-self-start"></i>
                                    <div class="activity-content">
                                        Menginput <a href="#" class="fw-bold text-dark">{{ $step['text'] }}</a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bobot Kriteria SAW -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Bobot Kriteria SAW</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kriteria</th>
                                            <th>Bobot (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Kondisi Rumah</td>
                                            <td>30%</td>
                                        </tr>
                                        <tr>
                                            <td>Kendaraan</td>
                                            <td>20%</td>
                                        </tr>
                                        <tr>
                                            <td>Pendapatan</td>
                                            <td>30%</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggungan</td>
                                            <td>20%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ol-xxl-4 col-xl-12">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Penilaian</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary">
                                    <i class="bi bi-bar-chart text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $totalStatus }}</h6>
                                    <span class="text-muted small pt-2 ps-1">Total Status Kelayakan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visualisasi Kelayakan -->
                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Layak</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info">
                                    <i class="bi bi-check-circle text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $layak }}</h6>
                                    <span class="text-muted small pt-2 ps-1">Jumlah Status Layak</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Tidak Layak</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger">
                                    <i class="bi bi-x-circle text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $tidakLayak }}</h6>
                                    <span class="text-muted small pt-2 ps-1">Jumlah Status Tidak Layak</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Penilaian -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Penilaian Kelayakan</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Penerima</th>
                                        <th>Kode Penerima</th>
                                        <th>Skor Rumah</th>
                                        <th>Skor Kendaraan</th>
                                        <th>Skor Pendapatan</th>
                                        <th>Skor Tanggungan</th>
                                        <th>Skor Total</th>
                                        <th>Status Kelayakan</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penilaian as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->penerima->nama ?? '-' }}</td>
                                        <td>{{ $item->kode_penerima }}</td>
                                        <td>{{ $item->skor_rumah }}</td>
                                        <td>{{ $item->skor_kendaraan }}</td>
                                        <td>{{ $item->skor_pendapatan }}</td>
                                        <td>{{ $item->skor_tanggungan }}</td>
                                        <td><strong>{{ number_format($item->skor_total, 2) }}</strong></td>
                                        <td>
                                            @if($item->kode_status == 1)
                                                <span class="badge bg-danger">Tidak Layak</span>
                                            @else
                                                <span class="badge bg-success">Layak</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->catatan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </section>
    </main>
</div>
