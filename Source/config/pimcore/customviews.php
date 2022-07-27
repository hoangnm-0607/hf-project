<?php

return [
    'views' => [
        [
            'treetype' => 'asset',                                              // asset view
            'name' => 'Voucher Uploads',
            'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/upload.svg',
            'id' => 'voucherPools',
            'rootfolder' => '/Voucher Uploads',
            'showroot' => true,
            'position' => 'right',
            'sort' => '-15',                                                    // show in on the top
            'expanded' => false,
        ],
        [
            'treetype' => 'object',                                              // asset view
            'name' => 'Online+ Products',
            'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/deployment.svg',
            'id' => 'onlineProducts',
            'rootfolder' => '/Online+/Products',
            'classes' => null,
            'showroot' => false,
            'position' => 'right',
            'sort' => '-15',                                                    // show in on the top
            'expanded' => false,
        ],
        [
            'treetype' => 'object',                                              // asset view
            'name' => 'Partner',
            'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/wysiwyg.svg',
            'id' => 'partnerView',
            'rootfolder' => '/Partner',
            'classes' => null,
            'showroot' => false,
            'position' => 'right',
            'sort' => '-10',                                                    // show in on the top
            'expanded' => false,
        ],
        [
            'treetype' => 'object',                                              // asset view
            'name' => 'Company',
            'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/wysiwyg.svg',
            'id' => 'companyView',
            'rootfolder' => '/Company',
            'classes' => null,
            'showroot' => false,
            'position' => 'right',
            'sort' => '-9',                                                    // show in on the top
            'expanded' => false,
        ],
        [
            'treetype' => 'object',                                              // asset view
            'name' => 'User',
            'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/person.svg',
            'id' => 'userView',
            'rootfolder' => '/User',
            'classes' => null,
            'showroot' => true,
            'position' => 'right',
            'sort' => '-15',                                                    // show in on the top
            'expanded' => false,
        ],
        [
            'treetype' => 'object',                                              // asset view
            'name' => 'End Users',
            'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/person.svg',
            'id' => 'endUsersView',
            'rootfolder' => '/End Users',
            'classes' => null,
            'showroot' => true,
            'position' => 'right',
            'sort' => '-15',                                                    // show in on the top
            'expanded' => false,
        ],
        [
            'treetype' => 'object',                                              // asset view
            'name' => 'Course Categories',
            'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/fieldset.svg',
            'id' => 'courseCategoryView',
            'rootfolder' => '/Course Categories',
            'classes' => [
                'CourseCategory' => 1
            ],
            'showroot' => false,
            'position' => 'right',
            'sort' => '-15',                                                    // show in on the top
            'expanded' => false,
        ],
        [
            'treetype' => 'object',                                              // asset view
            'name' => 'Partner Categories',
            'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/menu.svg',
            'id' => 'partnerCategoryView',
            'rootfolder' => '/Partner Categories',
            'classes' => [
                'PartnerCategory' => 1
            ],
            'showroot' => false,
            'position' => 'right',
            'sort' => '-15',                                                    // show in on the top
            'expanded' => false,
        ],
        [
            'treetype' => 'object',                                              // asset view
            'name' => 'Service Packages',
            'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/package.svg',
            'id' => 'servicesPackageView',
            'rootfolder' => '/Service Packages',
            'classes' => [
                'ServicePackage' => 1
            ],
            'showroot' => false,
            'position' => 'right',
            'sort' => '-15',                                                    // show in on the top
            'expanded' => false,
        ]
    ]
];
