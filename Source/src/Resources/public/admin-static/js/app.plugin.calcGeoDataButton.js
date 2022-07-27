pimcore.registerNS("pimcore.plugin.calcGeoDataButton");
pimcore.plugin.calcGeoDataButton = Class.create(pimcore.plugin.admin, {

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    recalculateGeoData: function (objectId) {
        Ext.Ajax.request({
            url: '/admin/recalculate-geodata/',
            method: 'GET',
            params: {
              'objectId': objectId
            },
            success: function (resp) {
                let result = Ext.decode(resp.responseText);
                if (result.success === true) {
                    Ext.Msg.alert('GeoData', t('admin.button.geoData'), function(btn) {
                        if (btn === 'ok') {
                            calcGeoDataButton.reloadUpdatedObject();
                        }
                    });
                }
            }
        });
    },

    reloadUpdatedObject: function () {
        let tabpanel = Ext.getCmp("pimcore_panel_tabs");
        let activeTab = tabpanel.getActiveTab();

        if (activeTab) {
            if (activeTab.initialConfig.object) {
                activeTab.initialConfig.object.reload();
            }
        }
    }


});

let calcGeoDataButton = new pimcore.plugin.calcGeoDataButton();
