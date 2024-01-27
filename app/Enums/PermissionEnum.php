<?php

/**
 *
 *
 * Author:  Asror Zakirov
 * https://www.linkedin.com/in/asror-zakirov
 * https://github.com/asror-z
 *
 */

namespace App\Enums;


use phpDocumentor\Descriptor\ConstantDescriptor;

class PermissionEnum
{
    public const Warehouse = 'Warehouse';
    public const Purchasing_Management_Center = 'Purchasing_Management_Center';
    public const Company_Leader = 'Company_Leader';
    public const Branch_Leader = 'Branch_Leader';
    public const Add_Company_Signer = 'Add_Company_Signer';
    public const Add_Branch_Signer = 'Add_Branch_Signer';
    public const Company_Signer = 'Company_Signer';
    public const Branch_Signer = 'Branch_Signer';
    public const Company_Performer = 'Company_Performer';
    public const Branch_Performer = 'Branch_Performer';
    public const Number_Change = 'Number_Change';
    public const Plan_Budget = 'Plan_Budget';
    public const Plan_Business = 'Plan_Business';
    public const Select_Branch = 'select_branch';
    public const Edit_Users = 'edit_users';

}
