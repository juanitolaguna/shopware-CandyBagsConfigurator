import {Component, Mixin, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './eccb-step-detail.twig';
import './eccb-step-detail.scss';

Component.register('eccb-step-detail', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],

    inject: ['repositoryFactory'],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            configuratorStep: null,
            isLoading: true,
            currentLanguageId: Context.api.languageId
        }
    },

    created() {
        this.createdComponent()
    },

    mounted() {
        this.$refs.candyBagSidebar.openContent();
    },

    computed: {
        configuratorStepRepository() {
            return this.repositoryFactory.create('eccb_configurator_step');
        }
    },

    methods: {
        createdComponent() {
            this.getConfiguratorStep();
        },

        getConfiguratorStep() {
            const criteria = new Criteria();
            criteria.addAssociation('media')

            return this.configuratorStepRepository.get(this.$route.params.id, Context.api, criteria)
                .then((result) => {
                    this.configuratorStep = result;
                    this.isLoading = false
                })
        },

        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.componentCreated();
        },

        onCancel() {
            this.$router.push({name: 'eccb.module.index'});
        },

        onMediaSelect() {
            this.onClickSave();
        },

        onClickSave() {
            this.isLoading = true;

            this.configuratorStepRepository
                .save(this.configuratorStep, Context.api)
                .then(() => {
                    this.getConfiguratorStep();
                    this.isLoading = false
                }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.step-detail.notification.error'),
                    message: exception
                });
            })
        }
    }


})