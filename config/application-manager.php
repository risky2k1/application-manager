<?php

// config for Risky2k1/ApplicationManager
return [
    'application' => [
        'default' => 'leave_application',
        'type' => [
            'request_application' => [
                'asset_transfer', // Bàn giao tài sản
                'payment', // Thanh toán
                'purchase', // Mua hàng
                'advance', //Tạm ứng
            ],
            'leave_application' => [
                'sick_leave',   //nghỉ ốm
                'maternity_leave', //Nghỉ thai sản
                'unpaid_leave', //Nghỉ không lương
                'annual_leave', //Nghỉ phép năm
                'other_leave', //Nghỉ khác
                'child_sick_leave', //Nghỉ con ốm
                'recovery_leave_after_illness', //nghỉ dưỡng sức sau ốm đau
                'conference_and_training_leave',   //nghỉ hội nghị, học tập
                'recovery_leave_after_maternity', // nghỉ dưỡng sức sau thai sản
                'recovery_leave_after_treatment_or_injury', //nghỉ dưỡng sức sau điều trị thương tật, tai nạn
                'compensatory_leave', //nghỉ bù
                'accident_leave', //nghỉ tai nạn
                'bereavement_leave', //nghỉ hiếu hỷ
                'customer_meeting', //gặp khách hàng
            ],
        ],
        'shift' => [
            'morning', //ca sáng
            'afternoon' // ca chiều
        ]
    ],


    'prefix' => 'applications',


    'middleware' => ['web', 'auth', '2fa', 'auth.active', 'check.permission', 'application.type'],


];
