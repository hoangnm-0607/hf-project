pimcore.registerNS("pimcore.plugin.startup");
pimcore.plugin.startup = Class.create(pimcore.plugin.admin, {

    getClassName: function () {
        return "pimcore.plugin.startup";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },
    pimcoreReady: function () {

        // Set QuickSearch minchars to 3
        Ext.getCmp('quickSearchCombo').minChars = 3;

    }

});
let startup = new pimcore.plugin.startup();
