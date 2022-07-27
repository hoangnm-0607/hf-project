// overrides the original method, adds the ability to display a returned error message on failure
// used to display an error message, when uploading assets without dedicated asset folder/missing CASPublicId
pimcore.helpers.assetSingleUploadDialog = function (parent, parentType, success, failure, context) {

    let params = {};
    params['parent' + ucfirst(parentType)] = parent;

    let url = Routing.generate('pimcore_admin_asset_addassetcompatibility', params);
    if (context) {
        url += "&context=" + Ext.encode(context);
    }

    pimcore.helpers.uploadDialog(url, 'Filedata', success, function(evt) {
        let res = evt.result;
        pimcore.helpers.showNotification(t("error"), res.message ? res.message : t("error"), "error", evt["message"]);
    });
};
