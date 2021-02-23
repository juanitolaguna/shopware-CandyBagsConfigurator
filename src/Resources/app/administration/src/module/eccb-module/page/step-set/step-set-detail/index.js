import {Component, Mixin, Context} from 'src/core/shopware';
import Criteria from 'src/core/data-new/criteria.data';

import template from './step-set-detail.twig';
import './step-set-detail.scss'

Component.register('eccb-step-set-detail', {
    template,

    mixins: [
        Mixin.getByName('notification')
    ],

    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        ESCAPE: 'onClickCancel'
    },

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
            inlineEdit: false,
            currentInlineEditId: null
        }
    },

    created() {
        this.createdComponent()
    },

    mounted() {
        this.$refs.sidebar.openContent();
    },

    computed: {
        treeNodeColumns() {
            return [
                {
                    property: 'stepDescription',
                    label: 'Step Description',
                    inlineEdit: 'string',
                    routerLink: 'eccb.plugin.tree-node.detail',
                    primary: true
                },

                {
                    property: 'item.position',
                    label: 'Position',
                    inlineEdit: 'number',
                },

                {
                    property: 'item.active',
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

        itemRepository() {
            return this.repositoryFactory.create('eccb_item');
        },

        /** ToDo: not used? */
        treeNodeCriteria() {
            const criteria = new Criteria();
            criteria.addAssociation('item');
            criteria.addFilter(Criteria.equals('stepSetId', this.$route.params.id));
            return criteria;
        }
    },

    methods: {
        async createdComponent() {
            this.isLoading = true;

            await Promise.all([
                this.getStepSet(),
                this.getSteps()
            ]);

            this.isLoading = false;
        },


        async getStepSet() {
            const criteria = new Criteria();
            criteria.addAssociation('media');
            this.stepSet = await this.stepSetRepository.get(this.$route.params.id, Context.api, criteria)



        },

        async getSteps() {
                const criteria = new Criteria();
                criteria.addFilter(Criteria.equals('stepSetId', this.$route.params.id));
                criteria.addFilter(Criteria.equals('parentId', null));
                criteria.addFilter(Criteria.equals('treeNodeItemSetId', null));

                criteria.addAssociation('item');
                criteria.addSorting(Criteria.sort('item.position', 'desc'));
                return this.treeNodeRepository.search(criteria, Context.api).then((result) => {
                    this.steps = result;
                    return Promise.resolve();
                })
        },


        onMediaSelect() {
            this.onSave();
        },

        async onSave() {
            this.isLoading = true;
            try {
                await this.stepSetRepository.save(this.stepSet, Context.api);
                await this.createdComponent();
            } catch (error) {
                this.createNotificationError({
                    title: this.$tc('eccb.step-set.detail.error'),
                    message: error
                });
            }
        },

        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            this.createdComponent();
        },

        onClickCancel() {
            this.$router.push({name: 'eccb.plugin.index'});
        },

        async onInlineEditTreeNodes(item) {
            await Promise.all([
                this.treeNodeRepository.save(item, Context.api)
            ]).then(() => {
                this.inlineEdit = false;
                this.getSteps();
                this.createNotificationSuccess({
                    title: this.$tc('eccb.step-set.save-success.title'),
                    message: this.$tc('eccb.step-set.save-success.text')
                });
            }).catch((error) => {
                this.createNotificationError({
                    title: this.$tc('eccb.step-set.error'),
                    message: error
                });
            })
        },

        deleteTreeNode(item) {
            this.steps.remove(item.id);
            this.treeNodeRepository.delete(item.id, Context.api).then(() => {
                this.createNotificationSuccess({
                    title: this.$tc('eccb.step-set.save-success.title'),
                    message: this.$tc('eccb.step-set.save-success.text')
                });
            }).catch((exception) => {
                this.createNotificationError({
                    title: this.$tc('eccb.step-set.error'),
                    message: exception
                });
                this.createdComponent();
            })
        },

        editTreeNode(item) {
            this.$router.push({name: 'eccb.plugin.tree-node.detail', params: {id: item.id}})
        },

        setInlineEdit(payload, id) {
            this.inlineEdit = true;
            this.currentInlineEditId = id;
        },

        getInlineEdit(item) {
            return this.inlineEdit && this.currentInlineEditId === item['id'];
        }

    },


});
