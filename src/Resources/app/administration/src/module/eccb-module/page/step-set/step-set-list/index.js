import {Component, Context} from 'src/core/shopware';
const {Criteria} = Shopware.Data;

import template from './eccb-step-set-list.twig'

Component.register('eccb-step-set-list', {
    template,

    inject: ['repositoryFactory'],

    shortcuts: {
        'SYSTEMKEY+N': 'addSequence'
    },


    data() {
        return {
            stepSets: null,
            isLoading: true,
            currentLanguageId: Shopware.Context.api.languageId
        }
    },

    created() {
        this.componentCreated();
    },


    computed: {
        stepSetRepository() {
            return this.repositoryFactory.create('eccb_step_set');
        },

        stepSetCriteria() {
            return new Criteria();
        },

        columns() {
            return [
                {
                    property: 'name',
                    dataIndex: 'name',
                    label: 'eccb.column.name',
                    inlineEdit: 'string',
                    routerLink: 'eccb.plugin.detail',
                    primary: true
                },

                {
                    property: 'position',
                    label: 'eccb.column.position',
                    inlineEdit: 'number',
                },

                {
                    property: 'active',
                    label: 'eccb.column.active',
                    inlineEdit: 'boolean',
                },
            ]
        }
    },

    methods: {
        componentCreated() {
            this.getSetSteps();
        },

        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.componentCreated();
        },


        getSetSteps() {
            this.isLoading = true;
            return this.stepSetRepository
                .search(this.stepSetCriteria, Context.api)
                .then((result) => {
                    this.stepSets = result;
                    this.isLoading = false;
                })
        },

        addSequence() {
            this.$router.push({name: 'eccb.plugin.create'});
        }
    }


});