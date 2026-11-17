@foreach (['success', 'status'] as $messageKey)
    @if (session($messageKey))<div class="alert alert-success" role="status">{{ session($messageKey) }}</div>@endif
@endforeach
@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <p class="message-title">Revisá los siguientes datos:</p>
        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif