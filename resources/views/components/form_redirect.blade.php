<form action="{{ route('redirect') }}" method="get">
  @csrf
  <input type="hidden" name="target" value="{{ $target }}">
  <input type="hidden" name="alert-status" value="success">
  <input type="hidden" name="alert-message" value="Aksi berhasil dilakukan">
</form>