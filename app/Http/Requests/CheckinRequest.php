<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckinRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'details'       => 'nullable',
            'attachments'   => 'nullable',
            'draft'         => 'nullable|boolean',
            'attachments.*' => 'mimes:' . env('ATTACHMENT_EXTS', 'jpg,png,pdf,docx,xlsx,zip'),

            'date'         => 'required|date',
            'contact_id'   => 'required|exists:contacts,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'reference'    => 'nullable|max:50|unique:checkins,reference,' . optional($this->route('checkin'))->id,

            'items'                   => 'required|array|min:1',
            'items.*.id'              => 'nullable',
            'items.*.checkin_item_id' => 'nullable',
            'items.*.has_serials'     => 'nullable',
            'items.*.has_variants'    => 'nullable',
            'items.*.quantity'        => 'required|numeric',
            'items.*.item_id'         => 'required|exists:items,id',
            'items.*.unit_id'         => 'nullable|exists:units,id',
            'items.*.selected'        => 'nullable|array|required_if:items.*.has_variants,1',
            'items.*.weight'          => 'nullable|numeric|required_if:items.*.track_weight,1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->setImplicitAttributesFormatter(function ($attribute) {
            $attributes = explode('.', $attribute);
            if ($attributes[0] == 'items') {
                if ($attributes[2]) {
                    return __('Item') . ' ' . ((int) $attributes[1] + 1) . ' ' . __($attributes[2]) . ' ' . (isset($attributes[3]) ? ((int) $attributes[3] + 1) : '');
                }

                return __('Item') . ' ' . ((int) $attributes[1] + 1);
            } elseif ($attributes[0] == 'attachments') {
                return 'attachments ' . ((int) $attributes[1] + 1);
            }

            return $attributes;
        });
    }
}
