<template>
    <div>

        <!-- Bloc content-->
        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
            <tr v-if="model">
                <td valign="top" width="375" class="resetMarge contentForm">
                    <h3>{{ model.title }}</h3>
                    <p class="abstract">{{ model.abstract }}</p>
                    <div v-html="model.content" class="content"></div>
                    <p><a target="_blank" :style="style" :class="model.class" :href="model.link">{{ model.message }}</a></p>
                </td>
                <!-- Bloc image droite-->
                <td width="25" class="resetMarge"></td><!-- space -->
                <td valign="top" align="center" width="160" class="resetMarge">
                    <div v-for="image in model.images">
                        <a target="_blank" :href="image.link">
                            <img width="130" border="0" :alt="image.title" :src="image.image">
                        </a>
                    </div>
                    <img v-if="type == 7" :src="model.path" class="img-responsive">
                </td>
            </tr>
        </table>
        <!-- Bloc content-->

    </div>
</template>
<style>

</style>
<script>
    export default{
        props: ['model','type','color'],
        data(){
            return{}
        },
        computed: {
            style(){
                return this.model.style + 'background:' + this.color;
            }
        },
        methods: {}
    }
</script>
