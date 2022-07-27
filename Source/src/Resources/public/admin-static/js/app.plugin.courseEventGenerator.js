pimcore.registerNS("pimcore.plugin.courseEventGenerator");
pimcore.plugin.courseEventGenerator = Class.create(pimcore.plugin.admin, {

    generateCourseEvents: function(objectId) {

        this.objectId = objectId;
        let element = pimcore.globalmanager.get("object_" + objectId);
        let task = 'version';
        if(element.data.general.o_published) {
            task = 'publish';
        }

        element.save(task);

        Ext.Ajax.request({
            url: "/generate-course-events/",
            method: 'PUT',
            params: {
                object_id: this.objectId
            },
            success: function () {
                this.reloadUpdatedObject();
            }.bind(this)
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
let courseEventGenerator = new pimcore.plugin.courseEventGenerator();
