<div style="width: 100%; padding: 20px; background-color: #e6fcff; color: #000;">

  <h1 style="text-align: center; margin-top: 0; margin-bottom: 20px; font-weight: 800; font-size: 28px; line-height: 100%;">ASMI Warehouse
  </h1>

  <div style="background-color: #fff; width: 75%; text-align: center; margin: 0 auto; padding: 20px 12px;">
    <h4 class="font-weigh-bold my-3" style="font-size: 16px; font-weight: 800; margin: 12px 0; color:#000;">
      Seseorang meminta untuk melakukan reset password untuk akun ini.
    </h4>

    <p style="color: #000;">Untuk dapat melakukan reset password, klik tombol di bawah ini.</p>

    <a href="{{ route('resetPassword.index', $token) }}?email={{ $email }}"
      style="padding: 10px 10px; border-radius: 5px; text-decoration: none; color: #fff; background-color: #d81b60; margin-bottom: 12px; font-weight: 600;"
      target="_blank">
      Reset Password
    </a>

    <p style="margin-top: 12px; color: #000;">
      Jika tombol tidak berfungsi, tekan link ini di bawah, atau <i>copy</i> lalu
      <i>paste</i> link ini di
      halaman browser Anda
    </p>

    <p style="margin-top: 12px;"><i>{{ route('resetPassword.index', $token) }}</i></p>

    <p style="margin-top: 12px; color: #000;">Link akan expired dalam 30 menit.</p>

    <p style="font-weight: 800; margin: 12px 0; color: #000;">
      Salam, <br>
      ASMI Warehouse
    </p>

    <i style="color: #000; font-size: 11px;">*Email dikirim melalui aplikasi, diharap tidak membalas pesan ini.</i>
  </div>
</div>