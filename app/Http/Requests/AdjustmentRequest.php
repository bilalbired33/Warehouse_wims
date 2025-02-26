<?php

namespace App\Http\Requests;

use App\Helpers\CheckOverSelling;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AdjustmentRequest extends FormRequest
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
            'warehouse_id' => 'required|exists:warehouses,id',
            'type'         => 'required|in:Damage,Addition,Subtraction',
            'reference'    => 'nullable|max:50|unique:adjustments,reference,' . optional($this->route('adjustment'))->id,

            'items'                      => 'required|array|min:1',
            'items.*.id'                 => 'nullable',
            'items.*.adjustment_item_id' => 'nullable',
            'items.*.has_serials'        => 'nullable',
            'items.*.has_variants'       => 'nullable',
            'items.*.quantity'           => 'required|numeric',
            'items.*.old_quantity'       => 'nullable|numeric',
            'items.*.item_id'            => 'required|exists:items,id',
            'items.*.unit_id'            => 'nullable|exists:units,id',
            'items.*.selected'           => 'nullable|array|required_if:items.*.has_variants,1',
            'items.*.weight'             => 'nullable|numeric|required_if:items.*.track_weight,1',
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

    protected function passedValidation()
    {
        if (! $this->input('draft') && $this->input('type') != 'Addition' && ! get_settings('over_selling')) {
            $error = (new CheckOverSelling())->check($this->input('items'), $this->input('warehouse_id'));
            if ($error) {
                throw ValidationException::withMessages($error);
            }
        }
    }
}
