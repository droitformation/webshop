<template>
    <div>
        <vue-tags-input
                v-model="tag"
                :tags="tags"
                @tags-changed="newTags => tags = newTags"
        />
    </div>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';

    export default {
        props: ['tags'],
        components: {
            VueTagsInput,
        },
        data() {
            return {
                tag: '',
                tags: [],
                autocompleteItems: [{
                    text: 'Spain',
                }, {
                    text: 'France',
                }, {
                    text: 'USA',
                }, {
                    text: 'Germany',
                }, {
                    text: 'China',
                }],
            };
        },
    };
</script>