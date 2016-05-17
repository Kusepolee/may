<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 简体中文
    |--------------------------------------------------------------------------
    |
    */

    'accepted'             => ':attribute 不被认可',
    'active_url'           => ':attribute 错误链接',
    'after'                => ':attribute 必须在 :date 之后',
    'alpha'                => ':attribute 只能由字母组成',
    'alpha_dash'           => ':attribute 只能由字母, 数字, 下划线组成',
    'alpha_num'            => ':attribute 只能由字母, 数字组成',
    'array'                => ':attribute 只能是数组.',
    'before'               => ':attribute 必须在 :date 之前',
    'between'              => [
        'numeric' => ':attribute 数值必须在 :min 和 :max 之间',
        'file'    => ':attribute 文件大小必须在 :min 和 :max KB 之间.',
        'string'  => ':attribute 字符数必须在 :min 和 :max 之间',
        'array'   => ':attribute 数组项必须在 :min 和 :max 之间',
    ],
    'boolean'              => ':attribute 只能是 true 或 false',
    'confirmed'            => ':attribute 两次输入不一致',
    'date'                 => ':attribute 无效日期.',
    'date_format'          => ':attribute 无效日期格式 :format.',
    'different'            => ':attribute 和 :other 不能相同',
    'digits'               => ':attribute must be :digits digits.',
    'digits_between'       => ':attribute must be between :min and :max digits.',
    'email'                => ':attribute 邮件错误.',
    'exists'               => ':attribute 不可用',
    'filled'               => ':attribute 必填',
    'image'                => ':attribute 必须是图形',
    'in'                   => 'selected :attribute is invalid.',
    'integer'              => ':attribute 必须是整数类型',
    'ip'                   => ':attribute 必须是IP地址',
    'json'                 => ':attribute 必须是可用的 JSON 格式.',
    'max'                  => [
        'numeric' => ':attribute 必须小于 :max.',
        'file'    => ':attribute 必须小于 :max kb.',
        'string'  => ':attribute 必须小于 :max 字符',
        'array'   => ':attribute 项必须小于 :max 项',
    ],
    'mimes'                => ':attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => ':attribute 必须大于 :min.',
        'file'    => ':attribute 不少于 :min kb.',
        'string'  => ':attribute 不少于 :min 个字符.',
        'array'   => ':attribute 必须大于 :min 项.',
    ],
    'not_in'               => '选择项 :attribute 不可用.',
    'numeric'              => ':attribute 必须是数字.',
    'regex'                => ':attribute 格式错误',
    'required'             => ':attribute 必填',
    'required_if'          => ':attribute 字段必填当 :other 值为 :value.',
    'required_with'        => ':attribute 必填, 当 :values 出现.',
    'required_with_all'    => ':attribute 必填, 当 :values 出现.',
    'required_without'     => ':attribute 必填, 当 :values 没出现.',
    'required_without_all' => ':attribute 必填, 当其中一个 :values 出现.',
    'same'                 => ':attribute and :other must match.',
    'size'                 => [
        'numeric' => ':attribute 必须是 :size.',
        'file'    => ':attribute 必须是 :size kb.',
        'string'  => ':attribute 必须是 :size 字符.',
        'array'   => ':attribute must contain :size items.',
    ],
    'string'               => ':attribute 必须是字符',
    'timezone'             => ':attribute 必须是效时区',
    'unique'               => ':attribute 已被占用',
    'url'                  => ':attribute 格式错误.',

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
