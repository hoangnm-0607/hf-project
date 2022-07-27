pimcore.registerNS("pimcore.plugin.companyInviteUserButton");
pimcore.plugin.companyInviteUserButton = Class.create(pimcore.plugin.admin, {

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);

        Ext.define("Company.view.invite.modal", {
            extend: "Ext.window.Window",
            width:400,
            renderTo: Ext.getBody(),
            modal: true,
            items: [
                {
                    xtype: 'form',
                    bodyPadding: 5,
                    flex: 1,
                    title: 'Invite Customer Admin',
                    url: '/company-invite-user/',
                    items: [
                        {
                            xtype: 'textfield',
                            name: 'firstname',
                            fieldLabel: 'Firstname',
                            allowBlank: true,
                            width: 350
                        },
                        {
                            xtype: 'textfield',
                            name: 'lastname',
                            fieldLabel: 'Lastname',
                            allowBlank: true,
                            width: 350
                        },
                        {
                            xtype: 'textfield',
                            name: 'email',
                            fieldLabel: 'Email',
                            allowBlank: false,
                            vtype: 'email',
                            width: 350
                        },
                        {
                            xtype: 'textfield',
                            name: 'position',
                            fieldLabel: 'Position',
                            allowBlank: true,
                            width: 350
                        },
                        {
                            xtype: 'hidden',
                            id: 'company_id',
                            name: 'company_id'
                        }
                    ],
                    buttons: [{
                        text: 'Submit',
                        handler: function(button, e) {
                            var form = this.up('form').getForm();
                            if (form.isValid()) {
                                form.submit({
                                    success: function(form, action) {
                                        Ext.Msg.alert('Success', t('admin.button.company_user_invite'));
                                        button.up('window').close();
                                        calcGeoDataButton.reloadUpdatedObject();
                                    },
                                    failure: function(form, action) {
                                        Ext.Msg.alert('Failed', action.result ? action.result.message : 'No response');
                                    }
                                });
                            }
                        }
                    }]
                }
            ]
        });
    },

    showCompanyInviteUserModal: function (objectId) {
        let modal = Ext.create('Company.view.invite.modal');
        Ext.getCmp('company_id').setValue(objectId);

        modal.show()
    },

    reloadUpdatedObject: function () {
        let tabpanel = Ext.getCmp("pimcore_panel_tabs");
        let activeTab = tabpanel.getActiveTab();

        if (activeTab) {
            if (activeTab.initialConfig.object) {
                activeTab.initialConfig.object.reload();
            }
        }
    },
});

let companyInviteUserButton = new pimcore.plugin.companyInviteUserButton();
