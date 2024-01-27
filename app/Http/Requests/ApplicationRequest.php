<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'save' => '',
            'initiator' => 'nullable',
            'purchase_basis' => 'nullable',
            'specification' => 'nullable',
            'delivery_date' => 'nullable',
            'name' => 'nullable',
            'basis' => 'nullable',
            'separate_requirements' => 'nullable',
            'other_requirements' => 'nullable',
            'expire_warranty_date' => 'nullable',
            'planned_price' => 'nullable',
            'info_business_plan' => 'nullable',
            'equal_planned_price' => 'nullable',
            'branch_initiator_id' => 'nullable',
            'branch_id' => 'nullable',
            'show_leader' => 'nullable',
            'subject' => 'nullable',
            'type_of_purchase_id' => 'nullable',
            'info_purchase_plan' => 'nullable',
            'comment' => 'nullable',
            'incoterms' => 'nullable',
            'signers' => 'nullable',
            'performer_user_id' => 'nullable',
            'branch_customer_id' => 'nullable',
            'currency' => 'nullable',
            'lot_number' => 'nullable',
            'contract_number' => 'nullable',
            'contract_date' => 'nullable',
            'protocol_date' => 'nullable',
            'contract_info' => 'nullable',
            'with_nds' => 'nullable|digits:1',
            'country_produced_id' => 'nullable',
            'contract_price' => 'nullable',
            'supplier_name' => 'nullable',
            'supplier_inn' => 'nullable',
            'status' => 'nullable',
            'report_if_cancelled' => 'nullable',
            'performer_status' => 'nullable',
            'is_more_than_limit' => 'nullable',
            'draft' => 'nullable',
            'resource_id' => 'nullable',
            'table' => 'nullable',
            'performer_leader_comment' => 'nullable',
            'performer_role_id' => 'nullable',
            'performer_leader_user_id' => 'nullable',
            'performer_comment' => 'nullable',
            'branch_leader_comment' => 'nullable',
            'number' => 'nullable',
            'branch_leader_user_id' => 'nullable',
            'date' => 'nullable',
            'product_info' => 'nullable',
            'protocol_number' => 'nullable',
        ];
    }
}
