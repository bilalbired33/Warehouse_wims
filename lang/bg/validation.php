<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'        => ':Attribute трябва да бъде приет.',
    'active_url'      => ':Attribute не е валиден URL адрес.',
    'after'           => ':Attribute трябва да бъде дата след :date.',
    'after_or_equal'  => ':Attribute трябва да бъде дата след или равна на :date.',
    'alpha'           => ':Attribute може да съдържа само букви.',
    'alpha_dash'      => ':Attribute може да съдържа само букви, цифри, тирета и долни черти.',
    'alpha_num'       => ':Attribute може да съдържа само букви и цифри.',
    'array'           => ':Attribute трябва да бъде масив.',
    'before'          => ':Attribute трябва да бъде дата преди :date.',
    'before_or_equal' => ':Attribute трябва да бъде дата преди или равна на :date.',
    'between'         => [
        'numeric' => ':Attribute трябва да бъде между :min и :max.',
        'file'    => ':Attribute трябва да бъде между :min и :max килобайта.',
        'string'  => ':Attribute трябва да бъде между :min и :max символа.',
        'array'   => ':Attribute трябва да има между :min и :max елемента.',
    ],
    'boolean'        => ':Attribute полето трябва да бъде true или false.',
    'confirmed'      => ':Attribute потвърждението не съвпада.',
    'date'           => ':Attribute не е валидна дата.',
    'date_equals'    => ':Attribute трябва да бъде дата, равна на :date.',
    'date_format'    => ':Attribute не съвпада с формата :format.',
    'different'      => ':Attribute и :other трябва да бъдат различни.',
    'digits'         => ':Attribute трябва да бъде :digits цифри.',
    'digits_between' => ':Attribute трябва да бъде между :min и :max цифри.',
    'dimensions'     => ':Attribute има невалидни размери на изображението.',
    'distinct'       => ':Attribute полето има дублираща се стойност.',
    'email'          => ':Attribute трябва да бъде валиден имейл адрес.',
    'ends_with'      => ':Attribute трябва да завършва с едно от следните: :values.',
    'exists'         => 'Избраният :attribute е невалиден.',
    'file'           => ':Attribute трябва да бъде файл.',
    'filled'         => ':Attribute полето трябва да има стойност.',
    'gt'             => [
        'numeric' => ':Attribute трябва да бъде по-голямо от :value.',
        'file'    => ':Attribute трябва да бъде по-голямо от :value килобайта.',
        'string'  => ':Attribute трябва да бъде по-голямо от :value символа.',
        'array'   => ':Attribute трябва да има повече от :value елемента.',
    ],
    'gte' => [
        'numeric' => ':Attribute трябва да бъде по-голямо или равно на :value.',
        'file'    => ':Attribute трябва да бъде по-голямо или равно на :value килобайта.',
        'string'  => ':Attribute трябва да бъде по-голямо или равно на :value символа.',
        'array'   => ':Attribute трябва да има :value елемента или повече.',
    ],
    'image'    => ':Attribute трябва да бъде изображение.',
    'in'       => 'Избраният :attribute е невалиден.',
    'in_array' => ':Attribute полето не съществува в :other.',
    'integer'  => ':Attribute трябва да бъде цяло число.',
    'ip'       => ':Attribute трябва да бъде валиден IP адрес.',
    'ipv4'     => ':Attribute трябва да бъде валиден IPv4 адрес.',
    'ipv6'     => ':Attribute трябва да бъде валиден IPv6 адрес.',
    'json'     => ':Attribute трябва да бъде валиден JSON низ.',
    'lt'       => [
        'numeric' => ':Attribute трябва да бъде по-малко от :value.',
        'file'    => ':Attribute трябва да бъде по-малко от :value килобайта.',
        'string'  => ':Attribute трябва да бъде по-малко от :value символа.',
        'array'   => ':Attribute трябва да има по-малко от :value елемента.',
    ],
    'lte' => [
        'numeric' => ':Attribute трябва да бъде по-малко или равно на :value.',
        'file'    => ':Attribute трябва да бъде по-малко или равно на :value килобайта.',
        'string'  => ':Attribute трябва да бъде по-малко или равно на :value символа.',
        'array'   => ':Attribute не трябва да има повече от :value елемента.',
    ],
    'max' => [
        'numeric' => ':Attribute не може да бъде по-голямо от :max.',
        'file'    => ':Attribute не може да бъде по-голямо от :max килобайта.',
        'string'  => ':Attribute не може да бъде по-голямо от :max символа.',
        'array'   => ':Attribute не може да има повече от :max елемента.',
    ],
    'mimes'     => ':Attribute трябва да бъде файл от тип: :values.',
    'mimetypes' => ':Attribute трябва да бъде файл от тип: :values.',
    'min'       => [
        'numeric' => ':Attribute трябва да бъде поне :min.',
        'file'    => ':Attribute трябва да бъде поне :min килобайта.',
        'string'  => ':Attribute трябва да бъде поне :min символа.',
        'array'   => ':Attribute трябва да има поне :min елемента.',
    ],
    'multiple_of'          => ':Attribute трябва да бъде кратно на :value.',
    'not_in'               => 'Избраният :attribute е невалиден.',
    'not_regex'            => ':Attribute форматът е невалиден.',
    'numeric'              => ':Attribute трябва да бъде число.',
    'password'             => 'Невалидна парола.',
    'present'              => ':Attribute полето трябва да присъства.',
    'regex'                => ':Attribute форматът е невалиден.',
    'required'             => ':Attribute полето е задължително.',
    'required_if'          => ':Attribute полето е задължително, когато :other е :value.',
    'required_unless'      => ':Attribute полето е задължително, освен ако :other е в :values.',
    'required_with'        => ':Attribute полето е задължително, когато :values е присъствено.',
    'required_with_all'    => ':Attribute полето е задължително, когато :values са присъствени.',
    'required_without'     => ':Attribute полето е задължително, когато :values не е присъствено.',
    'required_without_all' => ':Attribute полето е задължително, когато нито един от :values не е присъствен.',
    'same'                 => ':Attribute и :other трябва да съвпадат.',
    'size'                 => [
        'numeric' => ':Attribute трябва да бъде :size.',
        'file'    => ':Attribute трябва да бъде :size килобайта.',
        'string'  => ':Attribute трябва да бъде :size символа.',
        'array'   => ':Attribute трябва да има :size елемента.',
    ],
    'starts_with' => ':Attribute трябва да започва с едно от следните: :values.',
    'string'      => ':Attribute трябва да бъде низ.',
    'timezone'    => ':Attribute трябва да бъде валидна зона.',
    'unique'      => ':Attribute вече е заето.',
    'uploaded'    => ':Attribute не успя да качи.',
    'url'         => ':Attribute форматът е невалиден.',
    'uuid'        => ':Attribute трябва да бъде валиден UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];
