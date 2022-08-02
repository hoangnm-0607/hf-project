pimcore.registerNS("pimcore.plugin.companyDocumentsCreateButtons");
pimcore.plugin.companyDocumentsCreateButtons = Class.create(pimcore.plugin.admin, {

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    createTemplatesFolder: function (objectId, templateType) {
        Ext.Ajax.request({
            url: '/admin/company/create_document_folders',
            method: 'POST',
            params: {
                'companyId': objectId,
                'templateType': templateType,
            },
            success: function (resp) {
                let result = Ext.decode(resp.responseText);
                if (result.success === true) {
                    pimcore.treenodelocator.showInTree(result.folderId, 'document');
                }
            }
        });
    },

});

let companyDocumentsCreateButtons = new pimcore.plugin.companyDocumentsCreateButtons();
