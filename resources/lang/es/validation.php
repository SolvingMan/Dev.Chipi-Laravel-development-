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

    'accepted'             => 'El atributo: debe ser aceptado.',
    'active_url'           => 'El atributo: no es una URL válida.',
    'after'                => 'El atributo: debe ser una fecha posterior a: date.',
    'after_or_equal'       => 'El atributo: debe ser una fecha posterior o igual a: date.',
    'alpha'                => 'El atributo: sólo puede contener letras.',
    'alpha_dash'           => 'El atributo: sólo puede contener letras, números y guiones.',
    'alpha_num'            => 'El atributo: sólo puede contener letras y números.',
    'array'                => 'El atributo: debe ser un array.',
    'before'               => 'El atributo: debe ser una fecha anterior a: date.',
    'before_or_equal'      => 'El atributo: debe ser una fecha anterior o igual a: date.',
    'between'              => [
        'numeric' => 'El atributo: debe estar entre: min y: máx.',
        'file'    => 'El atributo: debe estar entre: min y: kilobytes máximos.',
        'string'  => 'El atributo: debe estar entre: min y: caracteres máx.',
        'array'   => 'El atributo: debe tener entre: min y: elementos max.',
    ],
    'boolean'              => 'El campo de atributo: debe ser verdadero o falso.',
    'confirmed'            => 'La confirmación de atributo: no coincide.',
    'date'                 => 'El atributo: no es una fecha válida.',
    'date_format'          => 'El atributo: no coincide con el formato: format.',
    'different'            => 'El: atributo y: otros deben ser diferentes.',
    'digits'               => 'El atributo: debe ser: digits digits.',
    'digits_between'       => 'El atributo: debe estar entre: min y: dígitos máximos.',
    'dimensions'           => 'El atributo: tiene dimensiones de imagen no válidas.',
    'distinct'             => 'El campo de atributo tiene un valor duplicado.',
    'email'                => 'El atributo: debe ser una dirección de correo electrónico válida.',
    'exists'               => 'El atributo seleccionado: no es válido.',
    'file'                 => 'El atributo: debe ser un archivo.',
    'filled'               => 'El campo atributo: debe tener un valor.',
    'image'                => 'El atributo: debe ser una imagen.',
    'in'                   => 'El atributo seleccionado: no es válido.',
    'in_array'             => 'El campo atributo: no existe en: other.',
    'integer'              => 'El atributo: debe ser un entero.',
    'ip'                   => 'El atributo: debe ser una dirección IP válida.',
    'json'                 => 'El atributo: debe ser una cadena JSON válida.',

    'max'                  => [
        'numeric' => 'El atributo: no puede ser mayor que: max.',
        'file'    => 'El atributo: no puede ser mayor que: max kilobytes.',
        'string'  => 'El atributo: no puede ser mayor que: caracteres máx.',
        'array'   => 'El atributo: no puede tener más de: elementos max.',
    ],
    'mimes'                => 'El atributo: debe ser un archivo de tipo:: values.',
    'mimetypes'            => 'El atributo: debe ser un archivo de tipo:: values.',
    'min'                  => [
        'numeric' => 'El atributo: debe ser como mínimo: min.',
        'file'    => 'El atributo: debe ser como mínimo: min kilobytes.',
        'string'  => 'El atributo: debe tener al menos: caracteres min.',
        'array'   => 'El atributo: debe tener al menos: elementos min.',
    ],
    'not_in'               => 'El atributo seleccionado: no es válido.',
    'numeric'              => 'El atributo: debe ser un número.',
    'present'              => 'El campo atributo: debe estar presente.',
    'regex'                => 'El formato de atributo no es válido.',
    'required'             => 'El campo de atributo es obligatorio.',
    'required_if'          => 'El campo de atributo: se requiere cuando: other is: value.',
    'required_unless'      => 'El campo de atributo: se requiere a menos que: other is in: values.',
    'required_with'        => 'El campo de atributo: se requiere cuando: values are present.',
    'required_with_all'    => 'El campo de atributo: se requiere cuando: no hay valores.',
    'required_without'     => 'El campo de atributo: se requiere cuando: no hay valores.',
    'required_without_all' => 'El campo de atributo: se requiere cuando ninguno de: los valores están presentes.',
    'same'                 => 'El atributo: y otro debe coincidir.',
    'size'                 => [
        'numeric' => 'El atributo: debe ser: size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'El atributo: debe ser: size characters.',
        'array'   => 'El atributo: debe contener: elementos de tamaño.',
    ],
    'string'               => 'El atributo: debe ser una cadena.',
    'timezone'             => 'El atributo: debe ser una zona válida.',
    'unique'               => 'El atributo: ya se ha tomado.',
    'uploaded'             => 'El atributo: falló al cargar.',
    'url'                  => 'El formato de atributo no es válido.',

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
            'rule-name' => 'mensaje personalizado',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
