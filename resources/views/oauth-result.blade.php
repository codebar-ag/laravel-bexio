<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Bexio OAuth2 Result' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>

    <div class="bexio-oauth-card">
        <h2 class="bexio-oauth-title">{{ $title }}</h2>
        <p class="bexio-oauth-message">{{ $message }}</p>
        @if (isset($actions) && is_array($actions))
            <div class="bexio-oauth-actions">
                @foreach ($actions as $btn)
                    <a href="{{ $btn['url'] }}" class="bexio-oauth-btn {{ $btn['class'] ?? '' }}">{{ $btn['label'] }}</a>
                @endforeach
            </div>
        @elseif(isset($action))
            <a href="{{ $action['url'] }}" class="bexio-oauth-btn">{{ $action['label'] }}</a>
        @endif
    </div>
</body>

</html>
