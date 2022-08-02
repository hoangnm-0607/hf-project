<?php

namespace App;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class AppBundle extends AbstractPimcoreBundle
{
    public function getJsPaths(): array
    {
        return [
            '/bundles/app/admin-static/js/app.plugin.courseEventGenerator.js',
            '/bundles/app/admin-static/js/app.plugin.archiveButton.js',
            '/bundles/app/admin-static/js/app.plugin.saveButtonModifier.js',
            '/bundles/app/admin-static/js/app.startup.js',
            '/bundles/app/admin-static/js/app.plugin.pimcore.helpers.js',
            '/bundles/app/admin-static/js/app.plugin.calcGeoDataButton.js',
            '/bundles/app/admin-static/js/app.plugin.companyInviteUserButton.js',
            '/bundles/app/admin-static/js/app.plugin.companyDocumentsCreateButtons.js',
        ];
    }

    public function getCssPaths(): array
    {
        return [
            '/bundles/app/admin-static/css/admin-style.css'
        ];
    }
}
