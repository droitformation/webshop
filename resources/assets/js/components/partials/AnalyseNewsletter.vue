<template>
    <div>
        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
            <tr align="center">
                <td class="resetMarge">
                    <!-- Bloc content-->

                    <table v-for="analyse in analyses" border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="tableReset contentForm">
                        <tr>
                            <td valign="top" align="center" width="375" class="resetMarge contentForm">
                                <h3 style="text-align: left;font-family: sans-serif;">Commentaire de l'arrêt {{ arret.reference }}</h3>
                                <!-- Authors -->

                                <table v-for="author in analyse.auteurs" border="0" width="375" align="left" cellpadding="0" cellspacing="0" class="resetTable">
                                    <tr>
                                        <td valign="top" width="60" class="resetMarge">
                                            <img style="width: 60px;" width="60" border="0" :alt="author.name" :src="author.author_photo">
                                        </td>
                                        <td valign="top" width="10" class="resetMarge"></td>
                                        <td valign="top" width="305" class="resetMarge">
                                            <h3>{{ author.name }}</h3>
                                            <p  v-html="author.occupation"></p>
                                        </td>
                                    </tr>
                                    <tr bgcolor="ffffff"><td colspan="3" height="15" class=""></td></tr>
                                </table>

                                <!-- End Authors -->
                                <p class="abstract">{{ analyse.abstract }}</p>
                                <p><a href="#">Télécharger en pdf</a></p>

                            </td>
                            <td width="25" class="resetMarge"></td><!-- space -->
                            <td align="center" valign="top" width="160" class="resetMarge">
                                <a target="_blank" href="#">
                                    <img border="0" style="max-width: 130px;" alt="Analyses" :src="analyse.path">
                                </a>
                            </td>
                        </tr>
                        <tr bgcolor="ffffff"><td colspan="3" height="25" class=""></td></tr>
                    </table>

                </td>
            </tr>
        </table>

    </div>
</template>
<style>
</style>
<script>

    export default{
        props: ['arret','analyses'],
        data(){
            return{
            }
        },
        mounted: function ()  {
            this.initialize();
        },
        methods: {
            initialize : function(){

            },
        }
    }
</script>
