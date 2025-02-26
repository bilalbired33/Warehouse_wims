<?php

// Get Settings
if (! function_exists('get_settings')) {
    function get_settings($keys = null)
    {
        if (! empty($keys)) {
            $single = ! is_array($keys) || count($keys) == 1;

            if ($single) {
                $key = is_array($keys) ? $keys[0] : $keys;

                return optional(App\Models\Setting::where('tec_key', $key)->first())->tec_value;
            }

            return App\Models\Setting::whereIn('tec_key', $keys)->pluck('tec_value', 'tec_key');
        }

        return App\Models\Setting::all()->pluck('tec_value', 'tec_key')->merge(['baseUrl' => url('/')]);
    }
}

// Get account id
if (! function_exists('getAccountId')) {
    function getAccountId($account_id = null)
    {
        return session('account_id', $account_id ?? optional(auth()->user())->account_id);
    }
}

// Log Activity
if (! function_exists('log_activity')) {
    function log_activity($activity, $properties = null, $model = null, $name = null)
    {
        return activity($name)->performedOn($model)->withProperties($properties)->log($activity);
    }
}

// Format Decimal
if (! function_exists('formatDecimal')) {
    function formatDecimal($number, $decimals = 4, $ds = '.', $ts = '')
    {
        return number_format($number, $decimals, $ds, $ts);
    }
}

// Format Number
if (! function_exists('formatNumber')) {
    function formatNumber($number, $decimals = 2, $ds = '.', $ts = ',')
    {
        return number_format($number, $decimals, $ds, $ts);
    }
}

// check if demo enabled
if (! function_exists('demo')) {
    function demo()
    {
        return env('DEMO', false) || env('WINDOWS', false);
    }
}

// check if desktop version enabled
if (! function_exists('desktopMachine')) {
    function desktopMachine()
    {
        return env('DESKTOPMACHINE', false);
    }
}

// check if demo is not enabled
if (! function_exists('notDemo')) {
    function notDemo()
    {
        return ! demo();
    }
}

// Json translation with choice replace
if (! function_exists('__choice')) {
    function __choice($key, array $replace = [], $number = null)
    {
        return trans_choice($key, $number, $replace);
    }
}

// Get UUID v1
if (! function_exists('uuid1')) {
    function uuid1()
    {
        $nodeProvider = new Ramsey\Uuid\Provider\Node\RandomNodeProvider();

        return Ramsey\Uuid\Uuid::uuid1($nodeProvider->getNode());
    }
}

// Get UUID v4
if (! function_exists('uuid4')) {
    function uuid4()
    {
        return Ramsey\Uuid\Uuid::uuid4();
    }
}

// Get ULID
if (! function_exists('ulid')) {
    function ulid()
    {
        return (string) Ulid\Ulid::generate(true);
    }
}

// Get get next id
if (! function_exists('get_next_id')) {
    function get_next_id($model)
    {
        return collect(Illuminate\Support\Facades\DB::select("show table status like '{$model->getTable()}'"))->first()->Auto_increment;
    }
}

// Get reference
if (! function_exists('get_reference')) {
    function get_reference($model)
    {
        $format = get_settings('reference');

        return match ($format) {
            'ai'     => get_next_id($model),
            'ulid'   => ulid(),
            'uuid'   => uuid4(),
            'uniqid' => uniqid(),
            default  => ulid(),
        };
    }
}

// Convert unit quantity to base quantity
if (! function_exists('convert_to_base_quantity')) {
    function convert_to_base_quantity($quantity, $unit)
    {
        $base_quantity = $quantity;
        if ($unit && $unit->operator) {
            switch ($unit->operator) {
                case '*':
                    $base_quantity = $quantity * $unit->operation_value;
                    break;
                case '/':
                    $base_quantity = $quantity / $unit->operation_value;
                    break;
                case '+':
                    $base_quantity = $quantity + $unit->operation_value;
                    break;
                case '-':
                    $base_quantity = $quantity - $unit->operation_value;
                    break;
                default:
                    $base_quantity = $quantity;
            }
        }

        return $base_quantity;
    }
}
