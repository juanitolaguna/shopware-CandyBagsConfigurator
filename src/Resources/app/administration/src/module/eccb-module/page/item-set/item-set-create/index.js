import {Component, Context} from 'src/core/shopware';

Component.extend('eccb-item-set-create', 'eccb-item-set-detail', {
    methods: {
        createdComponent() {
            if (Shopware.Context.api.languageId !== Shopware.Context.api.systemLanguageId) {
                Shopware.State.commit('context/setApiLanguageId', Shopware.Context.api.languageId)
            }

            if (!this.stepSet) {
                if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
                    Shopware.State.commit('context/resetLanguageToDefault');
                }
            }

            this.getItemSet();
        },

        getItemSet() {
            this.itemSet = this.itemSetRepository.create(Context.api);
            this.isLoading = false;
            setTimeout(() => {
                document.getElementById('sw-field--itemSet-internalName').focus();
            }, 100)
        },

        async onSave() {
            if (!this.validate()) return;
            this.isLoading = true;
            try {
                await this.itemSetRepository.save(this.itemSet, Context.api)

                this.createNotificationSuccess({
                    title: this.$tc('eccb.save-success.title'),
                    message: this.$tc('eccb.save-success.text')
                });

                this.$router.push({
                    name: 'eccb.plugin.item-set.detail',
                    params: {id: this.itemSet.id},
                    query: {treeNodeId: this.$route.query.treeNodeId}
                });
            } catch (error) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: this.createErrorMessage(error)
                });
            }

        },


    }
});