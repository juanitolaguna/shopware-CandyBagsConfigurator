import {Component, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

Component.extend('eccb-step-create','eccb-step-detail', {

    methods: {
        createdComponent() {
            if (Shopware.Context.api.languageId !== Shopware.Context.api.systemLanguageId) {
                Shopware.State.commit('context/setApiLanguageId', Shopware.Context.api.languageId)
            }

            if (!this.configuratorStep) {
                if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
                    Shopware.State.commit('context/resetLanguageToDefault');
                }
            }

            this.getConfigurationStep();
        },

        getConfigurationStep() {
            this.configuratorStep = this.configuratorStepRepository.create(Context.api);
            this.isLoading = false;
        },

        onClickSave() {
            this.configuratorStepRepository
                .save(this.configuratorStep, Context.api)
                .then(() => {
                    this.isLoading = false
                    this.$router.push({ name: 'eccb.module.detail', params: {id: this.configuratorStep.id} });

                    this.createNotificationSuccess({
                        title: this.$tc('eccb.step-detail.notification.save-success.title'),
                        message: this.$tc('eccb.step-detail.notification.save-success.text')
                    });
                }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.step-detail.notification.error'),
                    message: exception
                });
            })
        }


    }
});