@if (session('alert-message'))
<div class="alert alert-{{ session('alert-status') ?? 'primary' }}" role="alert">
  {{ session('alert-message') ?? '' }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif