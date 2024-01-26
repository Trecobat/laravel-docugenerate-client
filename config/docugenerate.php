<?php

return [
    'docugenerate_key' => env( "DOCUGENERATE_KEY","" ),
    'activated'        => true, // active/inactive all logging
    'middleware'       => ['web', 'auth'],
    'route_path'       => 'admin/docugenerate',
    'admin_panel_path' => 'admin/docugenerate',
];
