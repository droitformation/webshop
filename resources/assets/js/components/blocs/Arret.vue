<template>
    <div>
        <analyse-newsletter
                v-if="newsletter.display == 'top'"
                :title="newsletter.comment_title"
                :arret="arret"
                :analyses="arret.analyses"></analyse-newsletter>

        <!-- Bloc content-->
        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
            <tr>
                <td valign="top" width="375" class="resetMarge contentForm">
                    <h3 style="text-align: left; font-family: sans-serif;">{{ arret.dumois ? 'Arrêt du mois : ' : '' }}{{ arret.title }}</h3>
                    <p class="abstract">{{ arret.abstract }}</p>
                    <div v-html="arret.content" class="content"></div>
                    <p><a target="_blank" :class="arret.class" :href="arret.link">{{ arret.message }}</a></p>
                </td>
                <!-- Bloc image droite-->
                <td width="25" class="resetMarge"></td><!-- space -->
                <td valign="top" align="center" width="160" class="resetMarge">
                    <div v-for="image in arret.images">
                        <a target="_blank" :href="image.link">
                            <img width="130" border="0" :alt="image.title" :src="image.image">
                        </a>
                        <p v-if="!newsletter.hide_title" style="text-align:center !important;margin: 0px 0px 5px; padding: 0px;">{{ image.title }}</p>
                    </div>
                </td>
            </tr>
        </table>
        <!-- Bloc content-->

        <analyse-newsletter
                v-if="newsletter.display == 'bottom'"
                :title="newsletter.comment_title"
                :arret="arret"
                :analyses="arret.analyses"></analyse-newsletter>
    </div>
</template>
<style>

</style>
<script>
    import AnalyseNewsletter from '../partials/AnalyseNewsletter.vue';

    export default{
        props: ['arret','newsletter'],
        components:{'analyse-newsletter' : AnalyseNewsletter, },
        data(){
            return{}
        }
    }
</script>
