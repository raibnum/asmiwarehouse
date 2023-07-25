@if (session('alert-message'))
<div class="alert alert-{{ session('alert-status') ?? 'primary' }}" role="alert">
  {{ session('alert-message') ?? '' }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif

@if (session()->has('flash_notification.message'))
<div class="alert alert-{{ session()->get('flash_notification.level') }}" role="alert">
  {{ session()->get('flash_notification.message') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif