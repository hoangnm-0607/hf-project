pimcore.registerNS("pimcore.plugin.archiveButton");
pimcore.plugin.archiveButton = Class.create(pimcore.plugin.admin, {

    getClassName: function () {
        return "pimcore.plugin.archiveButton";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    postOpenObject: function (object) {
        if (['Course', 'SingleEvent'].indexOf(object.data.general.o_className) >= 0
            && this.checkShowArchiveButton(object)) {
            object.toolbar.add({
                text: t('admin.button.archive'),
                iconCls: 'appPluginArchiveButton',
                scale: 'medium',
                handler: function () {
                    this.checkArchivable(object);
                }.bind(this, object)
            });
            pimcore.layout.refresh();
        }
    },
    checkShowArchiveButton: function (object) {
        let showArchiveButton = Ext.Ajax.request({
            url: '/object-show-archive-button/',
            async: false,
            method: "GET",
            params: {
                "id": object.id
            }
        });
        return JSON.parse(showArchiveButton.responseText);
    },
    checkArchivable: function (object) {
        let isHierarchieClean = Ext.Ajax.request({
            url: '/object-do-archive/',
            async: false,
            method: "GET",
            params: {
                "id": object.id
            }
        });

        let result = JSON.parse(isHierarchieClean.responseText);

        Ext.Msg.alert(t('admin.button.archive'), t(result.message), function(btn) {
            if (result.canArchive === true && btn === 'ok') {
                archiveButton.reloadActiveTab();
                pimcore.elementservice.refreshRootNodeAllTrees("object");
            }
        });
    },
    reloadActiveTab: function() {
        let tabpanel = Ext.getCmp("pimcore_panel_tabs");
        let activeTab = tabpanel.getActiveTab();

        if (activeTab) {
            activeTab.initialConfig.object.reload();
        }
    }
});

let archiveButton = new pimcore.plugin.archiveButton();
