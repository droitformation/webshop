<template>
    <div id="adobe-dc-view" style="width: 100%; height: 600px;"></div>
</template>

<script>
    export default {
        props: ['book','path'],
        mounted() {
            console.log('Component mounted.');
            this.init();
        }
    }
</script>
