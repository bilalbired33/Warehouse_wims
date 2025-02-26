<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Mail') }} {{ __('Checkout') }} {{ $checkout->id }}</title>
</head>

<body style="font-family:Arial,sans-serif;color:#444;padding:1rem;margin:0 auto;max-width:700px;">
    @if ($mail)
        {{ __('Hello') }} {{ $checkout->contact->name }},
        <p style="margin:1rem 0">{{ __('Please review the checkout details below') }}</p>
    @endif
    <div style=" border:1px solid #E5E7EB;border-radius:5px;padding:1rem;line-height:1.4;margin:1rem
        auto;background:white">
        <div style="display:flex;align-items:flex-start;justify-content:space-between">
            @if ($checkout->warehouse->logo)
                <img src="{{ $checkout->warehouse->logo }}" class="mb-4 sm:mb-0" style="max-height: 90px" />
            @else
                <h1 style="margin:0 1rem 0 0">{{ $checkout->warehouse->name }}</h1>
            @endif
            <div style="text-align:right">
                <div style="font-weight:bold">{{ $checkout->warehouse->name }} ({{ $checkout->warehouse->code }})
                </div>
                <div>{{ $checkout->warehouse->address }}</div>
                <div>{{ $checkout->warehouse->phone }}</div>
                <div>{{ $checkout->warehouse->email }}</div>
            </div>
        </div>
        <div style="border-bottom:1px solid #E5E7EB;margin:1rem -1rem"></div>
        @if ($checkout->draft)
            <div
                style="border-bottom:1px solid #E5E7EB;margin:-1rem;background:#FDE68A;color:#D97706;padding:0.5rem 1rem">
                {{ __('This record is still a draft.') }}</div>
        @endif
        <div style="width:100%;padding-top:1rem">
            <h1 style="text-align:center;text-transform:uppercase;margin:1rem auto">{{ __('Checkout') }}</h1>
        </div>
        <div style="display:flex;align-items:flex-start;justify-content:space-between;">
            <div style="width:50%;padding:1rem 0">
                <div style="font-weight:bold">&nbsp;</div>
                <div>{{ __('Date') }}: {{ $checkout->date }}</div>
                <div>{{ __('Reference') }}: {{ $checkout->reference }}</div>
                <div>{{ __('Created at') }}: {{ $checkout->created_at }}</div>
            </div>
            <div style="width:50%;padding:1rem 0">
                <div style="font-weight:bold">{{ __('For') }}:</div>
                <div>{{ $checkout->contact->name }}</div>
                <div>{{ $checkout->contact->phone }}</div>
                <div>{{ $checkout->contact->email }}</div>
            </div>
        </div>
        <div style="margin:1rem -1rem;border-top:1px solid #E5E7EB;border-bottom:1px solid #E5E7EB;">
            <table style=" width:100%;min-width: 500px;border-collapse:collapse">
                <tr style="background-color:#f5f5f9;">
                    <th style="padding:0.5rem 1rem;text-align:left;width:50%">{{ __('Item') }}</th>
                    <th style="padding:0.5rem 1rem;text-align:right;width:25%"><span>{{ __('Weight') }}</span>
                    </th>
                    <th style="padding:0.5rem 1rem;text-align:right;width:25%">{{ __('Quantity') }}</th>
                </tr>

                @foreach ($checkout->items as $item)
                    @if ($item->variations->isNotEmpty())
                        <tbody class="avoid bg-gray-50 border-b">
                            <tr>
                                <td style="padding:0.5rem 1rem;border-top:1px solid #E5E7EB;font-weight:bold">
                                    {{ $item->item->name }}
                                    ({{ $item->item->code }})</td>
                                @if ($settings->track_weight)
                                    <td style="padding:0.5rem 1rem;border-top:1px solid #E5E7EB;text-align:right">
                                    </td>
                                @endif
                                <td style="padding:0.5rem 1rem;border-top:1px solid #E5E7EB;text-align:right"></td>
                            </tr>
                            @foreach ($item->variations as $variation)
                                <tr>
                                    <td style="padding:0 1rem 0.5rem 1rem;">
                                        @foreach ($variation->meta as $variant => $value)
                                            {{ $variant }}:
                                            <strong>{{ $value }}</strong>{{ $loop->last ? '' : ', ' }}
                                        @endforeach
                                    </td>
                                    @if ($settings->track_weight)
                                        <td style="padding:0 1rem 0.5rem 1rem;text-align:right">
                                            @if ($item->item->track_weight)
                                                {{ formatNumber($variation->pivot->weight, $settings->fraction) }}
                                                {{ $settings->weight_unit }}
                                            @endif
                                        </td>
                                    @endif
                                    <td style="padding:0 1rem 0.5rem 1rem;text-align:right">
                                        {{ formatNumber($variation->pivot->quantity, $settings->fraction) }}
                                        {{ $item->unit ? $item->unit->code : '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    @else
                        <tr class="avoid">
                            <td style="padding:0.5rem 1rem;border-top:1px solid #E5E7EB">{{ $item->item->name }}
                                ({{ $item->item->code }})</td>
                            @if ($settings->track_weight)
                                <td style="padding:0.5rem 1rem;border-top:1px solid #E5E7EB;text-align:right">
                                    @if ($item->item->track_weight)
                                        {{ formatNumber($item->weight, $settings->fraction) }}
                                        {{ $settings->weight_unit }}
                                    @endif
                                </td>
                            @endif
                            <td style="padding:0.5rem 1rem;border-top:1px solid #E5E7EB;text-align:right">
                                {{ formatNumber($item->quantity, $settings->fraction) }}
                                {{ $item->unit ? $item->unit->code : '' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
        <div>{{ $checkout->details }}</div>
    </div>
    <div style="font-size:0.8rem;text-align:center;color:#666">
        {{ __('This is computer generated document, no signature required.') }}
    </div>
</body>

</html>
