<?php

return [
    'default' => [                                              // this is the config for the default view
        'iconCls' => 'pimcore_icon_perspective',
        'elementTree' => [
            [
                'type' => 'objects',
                'position' => 'right',
                'expanded' => false,
                'hidden' => false,
                'sort' => 0
            ],
            [
                'type' => 'assets',
                'position' => 'right',
                'expanded' => false,
                'hidden' => false,
                'sort' => 1
            ],
            [
                'type' => 'documents',
                'position' => 'right',
                'expanded' => false,
                'hidden' => false,
                'sort' => 1
            ],
            [
                'type' => 'customview',
                'id' => 'partnerView',
                'position' => 'left',
                'expanded' => true,
                'hidden' => false,
                'showroot' => true,
                'sort' => -1,
            ],
            [
                'type' => 'customview',
                'id' => 'companyView',
                'position' => 'left',
                'expanded' => false,
                'hidden' => false,
                'showroot' => true,
                'sort' => -1,
            ],
            [
                'type' => 'customview',
                'id' => 'userView',
                'position' => 'left',
                'expanded' => true,
                'hidden' => false,
                'showroot' => true,
                'sort' => 2,
            ],
            [
                'type' => 'customview',
                'id' => 'endUsersView',
                'position' => 'left',
                'expanded' => true,
                'hidden' => false,
                'showroot' => true,
                'sort' => 2,
            ],
            [
                'type' => 'customview',
                'id' => 'onlineProducts',
                'position' => 'right',
                'expanded' => true,
                'hidden' => false,
                'showroot' => true,
                'sort' => 1,
            ],
            [
                'type' => 'customview',
                'id' => 'voucherPools',
                'position' => 'right',
                'expanded' => true,
                'hidden' => false,
                'showroot' => false,
                'sort' => 3,
            ],
        ],
        'toolbar' => [
            'search' => [
                'items' => [
                    'assets' => true,
                    'quickSearch' => true,
                    'documents' => false
                ]
            ]
        ],
        'dashboards' => [                                  // this is the standard setting for the welcome screen
            'predefined' => [
                'welcome' => [                             // internal key of the dashboard
                    'positions' => [
                        [                                  // left column
                            [
                                'id' => 1,
                                'type' => 'pimcore.layout.portlets.modificationStatistic',
                                'config' => null                // additional config
                            ],
                            [
                                'id' => 2,
                                'type' => 'pimcore.layout.portlets.modifiedAssets',
                                'config' => null
                            ]
                        ],
                        [
                            [
                                'id' => 3,
                                'type' => 'pimcore.layout.portlets.modifiedObjects',
                                'config' => null
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
