import {Component, Context} from 'src/core/shopware';

import template from '../item-set-item-detail/item-set-item-detail.twig';

Component.extend('eccb-item-set-item-create', 'eccb-item-set-item-detail', {
    template,

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

            this.getItem();
        },

        getItem() {
            this.item = this.itemRepository.create(Context.api);
            this.item.itemSetId = this.$route.query.itemSetId;
            this.item.type = 'card'
            setTimeout(() => {
                document.getElementById('sw-field--itemCard-name').focus();
            }, 100)

            this.itemCard = this.itemCardRepository.create(Context.api);
            this.isLoading = false;
        },

        async onSave() {
            if (!this.validate()) return;
            this.isLoading = true;
            try {

                if (this.item.type === 'card') {
                    this.item.itemCardId = this.itemCard.id;
                    await this.itemCardRepository.save(this.itemCard, Context.api)
                }

                await this.itemRepository.save(this.item, Context.api)

                this.createNotificationSuccess({
                    title: this.$tc('eccb.save-success.title'),
                    message: this.$tc('eccb.save-success.text')
                });

                this.$router.push({
                    name: 'eccb.plugin.item-set.item.detail',
                    params: {id: this.item.id}
                });

            } catch (error) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.error'),
                    message: error
                });
            }

        },


    }

});