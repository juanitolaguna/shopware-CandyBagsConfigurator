import {Component, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

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
                    label: 'Name',
                    inlineEdit: 'string',
                    routerLink: 'eccb.plugin.detail',
                    primary: true
                },

                {
                    property: 'description',
                    label: 'Description',
                    inlineEdit: 'string',
                },

                {
                    property: 'position',
                    label: 'Position',
                    inlineEdit: 'number',
                },

                {
                    property: 'active',
                    label: 'Active',
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