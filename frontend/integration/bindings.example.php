<?php
// Copy to config/sikuliner-ui.php inside the real Laravel project and fill from route:list.
// No routes, models, Gates, or migrations are registered by this file.
return [
    'routes' => array_fill_keys([
        'home', 'catalog', 'about', 'food.show', 'login', 'login.store', 'register',
        'register.store', 'logout', 'admin.home', 'regions.index', 'regions.create',
        'regions.edit', 'regions.store', 'regions.update', 'regions.destroy',
        'foods.index', 'foods.create', 'foods.edit', 'foods.store', 'foods.update', 'foods.destroy',
    ], null),
    'adminAbility' => null, // Existing backend Gate/ability; null denies admin rendering.
    'parameterMap' => [], // e.g. 'regions.edit' => ['id' => 'kabupaten'], after verification.
    'fields' => [
        'regions' => ['name' => 'name', 'description' => 'description', 'photo' => 'photo'],
        'foods' => ['name' => 'name', 'region_id' => 'region_id', 'category' => 'category', 'description' => 'description', 'photo' => 'photo'],
    ], // Presentation defaults. Replace with the ACTUAL request field names.
    'queries' => ['q' => 'q', 'region' => 'region', 'page' => 'page'],
    'updateMethod' => 'PUT',
    'regionPhotoEnabled' => false,
    'uploadMaxBytes' => null, // Set only after checking backend validation (report: 2 MB).
    'uploadAccept' => null, // e.g. ['image/jpeg', 'image/png']; match backend MIME rules.
];
