<?php

return [
	'mode'                 => '',
	'format'               => 'A7',
	'default_font_size'    => '12',
	'default_font'         => 'sans-serif',
	'margin_left'          => 10,
	'margin_right'         => 10,
	'margin_top'           => 10,
	'margin_bottom'        => 10,
	'margin_header'        => 0,
	'margin_footer'        => 0,
	'orientation'          => 'P',
	'title'                => 'PDF',
	'author'               => '',
	'watermark'            => '',
	'show_watermark'       => false,
	'watermark_font'       => 'sans-serif',
	'display_mode'         => 'fullpage',
	'watermark_text_alpha' => 0.1,
	'custom_font_dir'      => '',
	'custom_font_data' 	   => [],
	'auto_language_detection'  => false,
	'temp_dir'               => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
	'pdfa' 			=> false,
        'pdfaauto' 		=> false,
];


?>