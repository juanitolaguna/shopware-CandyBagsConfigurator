import {Component, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

Component.extend('eccb-candybag-create','eccb-candybag-detail', {

    methods: {
        createdComponent() {
            if (Shopware.Context.api.languageId !== Shopware.Context.api.systemLanguageId) {
                Shopware.State.commit('context/setApiLanguageId', Shopware.Context.api.languageId)
            }

            if (!this.candyBag) {
                if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
                    Shopware.State.commit('context/resetLanguageToDefault');
                }
            }


            this.getCandyBag();
        },

        getCandyBag() {
            this.candyBag = this.candyBagRepository.create(Context.api);
            this.isLoading = false;
        },

        onClickSave() {
            this.candyBagRepository
                .save(this.candyBag, Context.api)
                .then(() => {
                    this.isLoading = false
                    this.$router.push({ name: 'eccb.module.detail', params: {id: this.candyBag.id} });

                    this.createNotificationSuccess({
                        title: this.$tc('eccb.candybag-detail.notification.save-success.title'),
                        message: this.$tc('eccb.candybag-detail.notification.save-success.text')
                    });
                }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.candybag-detail.notification.error'),
                    message: exception
                });
            })
        }


    }
});