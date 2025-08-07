<?php

namespace App\Models;

class Status
{
    public $shortStatus;
    public $statusDetails;

    // Constructor to initialize the properties
    public function __construct($shortStatus, $statusDetails)
    {
        $this->shortStatus = $shortStatus;
        $this->statusDetails = $statusDetails;
    }

    // Static method to get short and detailed status
    public static function getStatus($status)
    {
        // Define status details and short status mappings
        $statusDetails = [
            'pending' => 'Consignment is not delivered or cancelled yet.',
            'delivered_approval_pending' => 'Consignment is delivered but waiting for admin approval.',
            'partial_delivered_approval_pending' => 'Consignment is delivered partially and waiting for admin approval.',
            'cancelled_approval_pending' => 'Consignment is cancelled and waiting for admin approval.',
            'unknown_approval_pending' => 'Unknown Pending status. Need contact with the support team.',
            'delivered' => 'Consignment is delivered and balance added.',
            'partial_delivered' => 'Consignment is partially delivered and balance added.',
            'cancelled' => 'Consignment is cancelled and balance updated.',
            'hold' => 'Consignment is held.',
            'in_review' => 'Order is placed and waiting to be reviewed.',
            'unknown' => 'Unknown status. Need contact with the support team.'
        ];

        $shortStatus = [
            'pending' => 'PENDING',
            'delivered_approval_pending' => 'DELIVERY_APPROVAL_PENDING',
            'partial_delivered_approval_pending' => 'PARTIAL_DELIVERY_APPROVAL_PENDING',
            'cancelled_approval_pending' => 'CANCELLED_APPROVAL_PENDING',
            'unknown_approval_pending' => 'UNKNOWN_APPROVAL_PENDING',
            'delivered' => 'DELIVERED',
            'partial_delivered' => 'PARTIAL_DELIVERED',
            'cancelled' => 'CANCELLED',
            'hold' => 'HOLD',
            'in_review' => 'IN_REVIEW',
            'unknown' => 'UNKNOWN'
        ];

        // Check if the status exists in the mappings
        if (array_key_exists($status, $statusDetails)) {
            return new Status($shortStatus[$status], $statusDetails[$status]);
        } else {
            return new Status('UNKNOWN', 'Unknown status. Need contact with the support team.');
        }
    }
}
