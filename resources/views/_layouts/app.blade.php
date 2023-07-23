<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  @include('_layouts.head')
</head>

@php
use Illuminate\Support\Facades\Auth;
$USERLOGIN = Auth::user();
@endphp

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
  <div id="loading"
    style="position: fixed; top: 0; bottom: 0; left: 0; right: 0; z-index: 1059; background-color: #00000080; display: none;">
    <img src="{{ asset('images/loader.gif') }}" alt="loader" class="d-block m-auto"
      style="position: relative; top: 50%; transform: translateY(-50%);">
  </div>

  <div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="{{ asset('images/logo-asmi.png') }}" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    @include('_layouts.header')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('_layouts.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <!-- .content-wrapper > section.content-header -->
    <!-- .content-wrapper > section.content -->
    @yield('content')
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    @include('_layouts.footer')
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  @include('_layouts.scripts')
  <script>
    moment.locale('id');
  </script>
  @yield('script')
  <script>
    $(document).ready(function () {
      $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('.select2').select2({ width: '100%' });
      });
      
      $('table').on('draw.dt', function () {
        $('[data-toggle="tooltip"]').tooltip();
      });

      /* set waktu app di header */
      setCurrentTime();
      setInterval(setCurrentTime, 1000);

      /* ajax csrf token */
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    });

    function setCurrentTime() {
      const arrHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'];
      const arrBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

      const date = new Date();
      const hari = arrHari[date.getDay()];
      const tanggal = date.getDate();
      const bulan = arrBulan[date.getMonth()];
      const tahun = date.getFullYear();
      const jam = date.getHours() < 10 ? '0' + date.getHours() : date.getHours();
      const menit = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
      const detik = date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds();

      const time = `${hari}, ${tanggal} ${bulan} ${tahun} ${jam}:${menit}:${detik}`;
      $('#current-time').html(time);
    }
  </script>
</body>

</html>