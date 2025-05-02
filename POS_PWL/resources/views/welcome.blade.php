@extends('layouts.template')

@push('css')
<style>
  /* ===== Card & Statistic Boxes ===== */
  .stat-box {
    border-radius: 0.75rem;
    position: relative;
    overflow: hidden;
    color: #fff;
    transition: transform .3s ease, box-shadow .3s ease;
  }
  .stat-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 1rem 2rem rgba(0,0,0,.15);
  }
  .stat-box .inner {
    padding: 1.2rem 1.4rem;
  }
  .stat-box .inner h3 {
    font-size: 2.1rem;
    font-weight: 600;
    margin: 0;
  }
  .stat-box .icon {
    position: absolute;
    right: 1rem;
    top: -0.6rem;
    font-size: 4.5rem;
    opacity: 0.15;
  }

  /* gradients */
  .gradient-info      {background: linear-gradient(45deg,#36d1dc,#5b86e5);} /* biru aqua */
  .gradient-success   {background: linear-gradient(45deg,#16a085,#1abc9c);} /* hijau */
  .gradient-warning   {background: linear-gradient(45deg,#ffb347,#ffcc33);} /* kuning */
  .gradient-danger    {background: linear-gradient(45deg,#ff416c,#ff4b2b);} /* merah */
  .gradient-primary   {background: linear-gradient(45deg,#007bff,#00c6ff);} /* biru */
  .gradient-secondary {background: linear-gradient(45deg,#6a11cb,#2575fc);} /* ungu-biru */

  /* small‑box footer link */
  .stat-box .small-box-footer{
    display:flex;
    align-items:center;
    justify-content:space-between;
    width:100%;
    padding:.55rem 1.4rem;
    font-weight:600;
    font-size:.875rem;
    color:#fff;
    text-decoration:none;
    backdrop-filter: blur(4px);
    background: rgba(255,255,255,.12);
    transition:background .2s ease;
  }
  .stat-box .small-box-footer:hover{
    background: rgba(255,255,255,.22);
  }
  .stat-box .small-box-footer i{
    transition:transform .2s ease;
  }
  .stat-box .small-box-footer:hover i{
    transform:translateX(4px);
  }

  /* ===== Cards & Charts ===== */
  .card {
    border-radius: 1rem;
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.05);
  }
</style>
@endpush

@section('content')
  {{-- Greeting Card --}}
  <div class="card shadow-sm mb-4 border-0 bg-light bg-gradient">
    <div class="card-body d-flex align-items-center">
      <div class="flex-grow-1">
        <h3 class="mb-0 fw-bold text-primary">Halo, apa kabar! 👋</h3>
        <p class="mb-0 text-muted">Selamat datang kembali, semoga harimu menyenangkan ✨</p>
      </div>
      <i class="fas fa-chart-line fa-3x text-primary opacity-25"></i>
    </div>
  </div>

  {{-- Statistic Boxes --}}
  <div class="row g-3">
    <div class="col-md-6 col-xl-3">
      <div class="stat-box gradient-info">
        <div class="inner">
          <h3>{{ $userCount }}</h3>
          <p>Data User</p>
        </div>
        <div class="icon"><i class="fas fa-users"></i></div>
        <a href="{{ url('user') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="stat-box gradient-success">
        <div class="inner">
          <h3>{{ $levelCount }}</h3>
          <p>Level User</p>
        </div>
        <div class="icon"><i class="fas fa-layer-group"></i></div>
        <a href="{{ url('level') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="stat-box gradient-warning">
        <div class="inner">
          <h3>{{ $kategoriCount }}</h3>
          <p>Kategori Barang</p>
        </div>
        <div class="icon"><i class="fas fa-bookmark"></i></div>
        <a href="{{ url('kategori') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="stat-box gradient-danger">
        <div class="inner">
          <h3>{{ $barangCount }}</h3>
          <p>Data Barang</p>
        </div>
        <div class="icon"><i class="fas fa-boxes"></i></div>
        <a href="{{ url('barang') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div>

  <div class="row g-3 mt-1">
    <div class="col-md-6">
      <div class="stat-box gradient-primary">
        <div class="inner">
          <h3>{{ $stokCount }}</h3>
          <p>Total Stok</p>
        </div>
        <div class="icon"><i class="fas fa-cubes"></i></div>
        <a href="{{ url('stok') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
    <div class="col-md-6">
      <div class="stat-box gradient-secondary">
        <div class="inner">
          <h3>{{ $penjualanCount }}</h3>
          <p>Transaksi Penjualan</p>
        </div>
        <div class="icon"><i class="fas fa-cash-register"></i></div>
        <a href="{{ url('penjualan') }}" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div>

  {{-- Charts --}}
  <div class="row g-3 mt-2">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0"><h5 class="mb-0 fw-bold">Penjualan 7 Hari Terakhir</h5></div>
        <div class="card-body"><canvas id="salesChart" height="200"></canvas></div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-0"><h5 class="mb-0 fw-bold">Distribusi Kategori</h5></div>
        <div class="card-body"><canvas id="kategoriChart" height="220"></canvas></div>
      </div>
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0"><h5 class="mb-0 fw-bold">Stok per Barang</h5></div>
        <div class="card-body"><canvas id="stokChart" height="200"></canvas></div>
      </div>
    </div>
  </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const labels          = @json($labels);
  const dataSales       = @json($dataSales);
  const kategoriLabels  = @json($kategoriLabels);
  const kategoriCounts  = @json($kategoriCounts);
  const barangLabels    = @json($barangLabels);
  const barangStok      = @json($barangStok);

  // Line chart with gradient fill
  const ctxLine = document.getElementById('salesChart').getContext('2d');
  const gradient = ctxLine.createLinearGradient(0,0,0,200);
  gradient.addColorStop(0,'rgba(75,192,192,.35)');
  gradient.addColorStop(1,'rgba(75,192,192,0)');
  new Chart(ctxLine, {
    type:'line',
    data:{
      labels:labels,
      datasets:[{
        label:'Jumlah Penjualan',
        data:dataSales,
        fill:true,
        backgroundColor:gradient,
        borderColor:'rgba(75,192,192,1)',
        tension:.4,
        pointRadius:3,
      }]
    },
    options:{
      responsive:true,
      plugins:{legend:{display:false}},
      scales:{y:{beginAtZero:true, ticks:{precision:0}}}
    }
  });

  // Pie chart kategori
  new Chart(document.getElementById('kategoriChart'), {
    type:'doughnut',
    data:{labels:kategoriLabels,datasets:[{data:kategoriCounts,backgroundColor:['#ff6384','#36a2eb','#ffce56','#4bc0c0','#9966ff']}]},
    options:{responsive:true,plugins:{legend:{position:'bottom'}}}
  });

  // Bar chart stok
  new Chart(document.getElementById('stokChart'), {
    type:'bar',
    data:{labels:barangLabels,datasets:[{label:'Jumlah Stok',data:barangStok,backgroundColor:'#6a11cb'}]},
    options:{
      responsive:true,
      plugins:{legend:{display:false}},
      scales:{y:{beginAtZero:true}}
    }
  });
</script>
@endpush
