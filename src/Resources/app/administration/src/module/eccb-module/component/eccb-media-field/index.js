const { Component, Context, Utils } = Shopware;
const { Criteria } = Shopware.Data;

import template from './eccb-media-field.twig';

Component.extend('eccb-media-field', 'sw-media-field', {
    template,
    props: {
        mediaFolderName: {
            type: String,
            required: false,
        }
    },

    // set Media Folder ID and pass it to the temlate
    data() {
        return {
            mediaFolderId: ''
        }
    },

    methods: {
        createdComponent() {
            this.$super('createdComponent');
            this.getDefaultFolderByName()
        },

        getDefaultFolderByName() {
            let folderRepository = this.repositoryFactory.create('media_folder');
            const mediaFolderCriteria = new Criteria();
            const folderName = this.mediaFolderName ? this.mediaFolderName : 'Product Media'

            mediaFolderCriteria.addFilter(Criteria.equals('name', folderName));

            folderRepository.search(mediaFolderCriteria, Context.api).then((entity) => {
                this.mediaFolderId = entity[0].id;
            })
        }
    }

});