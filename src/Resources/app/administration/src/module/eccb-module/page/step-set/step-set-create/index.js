import {Component, Context} from 'src/core/shopware';

Component.extend('eccb-step-set-create','eccb-step-set-detail', {

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

            this.getStepSet();
        },

        getStepSet() {
            this.stepSet = this.stepSetRepository.create(Context.api);
            this.isLoading = false;
        },

        onClickCancel() {
            this.$router.push({name: 'eccb.plugin.index'});
        },

        onSave() {
            this.stepSetRepository
                .save(this.stepSet, Context.api)
                .then(() => {
                    this.isLoading = false
                    this.createNotificationSuccess({
                        title: this.$tc('eccb.step-set.save-success.title'),
                        message: this.$tc('eccb.step-set.save-success.text')
                    });
                    this.$router.push({ name: 'eccb.plugin.detail', params: {id: this.stepSet.id} });
                }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.step-set.error'),
                    message: exception
                });
            })
        }
    }
});