pimcore.registerNS("pimcore.plugin.saveButtonModifier");
pimcore.plugin.saveButtonModifier = Class.create(pimcore.plugin.admin, {

    getClassName: function () {
        return "pimcore.plugin.saveButtonModifier";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    postSaveObject: function (object) {
        if(!object.autoSavingInterval && object.data.general.o_className === 'SingleEvent') {
            saveButtonModifier.closeThisAndReloadNextActiveTab();
        }
        if(!object.autoSavingInterval && object.data.general.o_className === 'PartnerProfile') {
            let tabpanel = Ext.getCmp("pimcore_panel_tabs");
            let activeTab = tabpanel.getActiveTab();
            activeTab.initialConfig.object.reload();
        }
    },
    closeThisAndReloadNextActiveTab: function() {
        let tabpanel = Ext.getCmp("pimcore_panel_tabs");
        let activeTab = tabpanel.getActiveTab();

        activeTab.initialConfig.object.close();

        let nextActiveTab = tabpanel.getActiveTab();
        if (nextActiveTab) {
            nextActiveTab.initialConfig.object.reload();
        }
    }

});
let saveButtonModifier = new pimcore.plugin.saveButtonModifier();
