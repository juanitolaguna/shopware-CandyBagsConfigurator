import {Component, Mixin, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './step-set-detail.twig';
import './step-set-detail.scss'

Component.register('eccb-step-set-detail', {
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
            stepSet: null,
            steps: null,
            isLoading: true,
            mediaFolderName: 'Candy Bags',
            currentLanguageId: Context.api.languageId,
        }
    },

    created() {
        this.createdComponent()
    },

    mounted() {
        this.$refs.stepSetSidebar.openContent();
    },

    computed: {
        treeNodeColumns() {
            return [
                {
                    property: 'name',
                    label: 'Name',
                    inlineEdit: 'string',
                    routerLink: 'eccb.tree-node.detail',
                    primary: true
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
                }
            ]
        },

        stepSetRepository() {
            return this.repositoryFactory.create('eccb_step_set');
        },

        treeNodeRepository() {
            return this.repositoryFactory.create('eccb_tree_node');
        },

        treeNodeCriteria() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('stepSetId', this.$route.params.id))
            return criteria;
        }
    },

    methods: {
        async createdComponent() {
            this.isLoading = true;

            await Promise.all([
                this.getStepSet(),
                 this.getSteps()
            ])

            this.isLoading = false

            return Promise.resolve();

        },


        async getStepSet() {
            const criteria = new Criteria();
            criteria.addAssociation('media');
            criteria.addAssociation('steps');


            return this.stepSetRepository.get(this.$route.params.id, Context.api, criteria)
                .then((result) => {
                    this.stepSet = result;
                    return Promise.resolve();
                });
        },

        async getSteps() {
            this.treeNodeRepository.search(this.treeNodeCriteria, Context.api)
                .then((result) => {
                    this.steps = result
                    return Promise.resolve();
                }).catch((e) => {
                console.log(e);
            })
        },


        onMediaSelect() {
            this.onSave();
        },

        async onSave() {
            this.isLoading = true;

            this.stepSetRepository
                .save(this.stepSet, Context.api)
                .then(() => {
                    this.createdComponent();
                }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('eccb.step-set.detail.error'),
                    message: exception
                });
            })
        },


        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.createdComponent();
        },

        onClickCancel() {
            this.$router.push({name: 'eccb.plugin.index'});
        }
    },


});
