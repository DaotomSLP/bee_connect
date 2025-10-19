<?php
/**
 * mPDF Configuration File.
 * This file registers custom fonts (NotoSansLao) for proper Complex Script support.
 * The font name 'noto' defined here must match 'defaultFont' in the Controller.
 */
return [
    'mode'                     => '',
    'format'                   => 'B3',
    'default_font_size'        => '12',
    'default_font'             => 'noto', // กำหนดฟอนต์หลักเป็น 'noto'
    'margin_left'              => 10,
    'margin_right'             => 10,
    'margin_top'               => 10,
    'margin_bottom'            => 10,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'L', // กำหนดเป็น Landscape ตามที่คุณต้องการ

    // *** แก้ไข: เปลี่ยนพาธฟอนต์ให้ชี้ไปที่ resource_path('fonts') ตามที่ผู้ใช้แจ้ง ***
    'font_path' => base_path('resources/fonts/'),

    // *** ส่วนที่สำคัญที่สุดสำหรับภาษาลาว (Complex Scripts) ***
    'fontdata' => [
        'noto' => [ // คีย์นี้ต้องตรงกับ 'default_font' และ CSS font-family
            'R' => 'NotoSansLao-Regular.ttf', // ชื่อไฟล์ฟอนต์ปกติ
            'B' => 'NotoSansLao-Bold.ttf',    // ชื่อไฟล์ฟอนต์ตัวหนา
            'useOTL' => 0xFF, // เปิดใช้งาน OpenType Layout (สำคัญสำหรับการแสดงผลภาษาลาว)
        ]
    ],
    // ***************************************************************

    'auto_language_tools'      => true,
    'auto_script_to_lang'      => true,
    'auto_arabic'              => true,
    'auto_vietnamese'          => true,
    'auto_chinese_japanese'    => true,
    'auto_outdent_list'        => false,
    'table_lineheight_correction' => 1.2,
    'temporary_dir'            => sys_get_temp_dir(),
];
