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


class ApplicationStatusEnum
{
    public const New = 'new';
    public const In_Process = 'in_process';
    public const Extended = 'extended';
    public const Overdue = 'overdue';
    public const Accepted = 'Принята';
    public const Refused = 'refused';
    public const Agreed = 'agreed';
    public const Rejected = 'rejected';
    public const Distributed = 'distributed';
    public const Canceled = 'canceled';
    public const Partially_Completed = 'partially_completed';
    public const Completed_Full = 'completed_full';
    public const Management_Canceled  = 'management_canceled';
    public const Uztelecom_Canceled = 'uztelecom_canceled';
    public const Application_Uztelecom = 'application_uztelecom';
    public const Order_Delivered = 'order_delivered';
    public const Order_Arrived = 'order_arrived';
    public const Contract_Concluded = 'contract_concluded';
    public const Draft = 'draft';





}
