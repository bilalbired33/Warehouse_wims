<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Mail') }} {{ __('Low Stock') }}</title>
</head>

<body style="font-family:Arial,sans-serif;color:#444;padding:1rem;margin:0 auto;max-width:700px;">
    {{ __('Hello') }}{{ $user ? ' ' . $user->name : '' }},
    <p style="margin:1rem 0">{{ __('Some warehouse items are low in stock.') }}
        {{ __('Please click the link below to review') }}
    </p>
    <div style=" border:1px solid #E5E7EB;border-radius:5px;padding:1rem;line-height:1.4;margin:1rem
        auto;background:white">
        <section style="margin:0 auto;">
            @if ($single)
                <a href="{{ route('alerts.list', $data->id) }}"
                    style="display:block;padding:1rem;text-decoration:none;color:#444;position:relative">
                    <div class="flex items-start justify-between">
                        <h2 style="margin:0 0 0.5rem 0;font-size:1.2rem;font-weight:bold">
                            {{ __($data->name) }} ({{ $data->code }})
                        </h2>
                        <span
                            style="position:absolute;top:1rem;right:1rem;display:flex;align-items:center;font-size:0.8rem;margin0.25rem;{{ ($data->alert / ($data->stock_count ?: 1)) * 100 > 20 ? 'color:red;' : 'color:orange;' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                style="width:1rem;height:1rem;margin-right:0.25rem;fill: currentColor;">
                                <path fill-rule="evenodd"
                                    d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>{{ ($data->alert / ($data->stock_count ?: 1)) * 100 }}%</span>
                        </span>
                    </div>
                    <p style="margin:0">
                        {{ __choice('x items are low in stock.', ['x' => $data->alert]) }}</p>
                </a>
            @else
                <div style="display:grid;grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem;">
                    @foreach ($data as $warehouse)
                        <a href="{{ route('alerts.list', $warehouse->id) }}"
                            style="border:1px solid #E5E7EB;padding:1rem;border-radius:0.25rem;text-decoration:none;color:#444;position:relative">
                            <div class="flex items-start justify-between">
                                <h2 style="margin:0 0 0.5rem 0;font-size:1.2rem;font-weight:bold">
                                    {{ __($warehouse->name) }} ({{ $warehouse->code }})
                                </h2>
                                <span
                                    style="position:absolute;top:1rem;right:1rem;display:flex;align-items:center;font-size:0.8rem;margin0.25rem;{{ ($warehouse->alert / ($warehouse->stock_count ?: 1)) * 100 > 20 ? 'color:red;' : 'color:orange;' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        style="width:1rem;height:1rem;margin-right:0.25rem;fill: currentColor;">
                                        <path fill-rule="evenodd"
                                            d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ ($warehouse->alert / ($warehouse->stock_count ?: 1)) * 100 }}%</span>
                                </span>
                            </div>
                            <p style="margin:0">
                                {{ __choice('x items are low in stock.', ['x' => $warehouse->alert]) }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</body>

</html>
