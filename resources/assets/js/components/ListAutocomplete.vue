<template>
    <div>
        <div class="autocomplete-input">
            <p class="control has-icon has-icon-right">
                <input class="input is-large"
                       v-model="keyword"
                       @input="onInput($event.target.value)"
                       @keyup.esc="isOpen = false"
                       @blur="isOpen = false"
                       @keydown.down="moveDown"
                       @keydown.up="moveUp"
                       @keydown.enter="select"
                       placeholder="Search...">
                <i class="icon fa fa-angle-down"></i>
            </p>
            <ul class="options-list" v-show="isOpen">
                <li v-for="option in options" :class="{'highlighted': index === highlightedPosition }"
                    v-on:mouseenter="highlightedPosition = index"  v-on:mousedown="select"
                >
                    <strong>{{ option.label }}</strong><br>
                    {{ option.desc }}
                </li>
            </ul>
        </div>
    </div>
</template>
<style>
</style>

<script>
    export default{
        data(){
            return{
                options: [],
                isOpen: false,
                highlightedPosition: 0,
                keyword: ''
            }
        },
        computed: {
            fOptions () {

                //const re = new RegExp(this.keyword, 'i')
                //return this.options.filter(o => o.title.match(re))
            }
        },
        methods: {
            onInput (value) {
                this.isOpen = !!value
                this.highlightedPosition = 0,
                this.search();
            },
            moveDown () {
                if (!this.isOpen) {
                    return
                }
                this.highlightedPosition = (this.highlightedPosition + 1) % this.fOptions.length;
            },
            moveUp () {
              if (!this.isOpen) {
                return
              }
              this.highlightedPosition = this.highlightedPosition - 1 < 0  ? this.fOptions.length - 1 : this.highlightedPosition - 1;
            },
            select () {
                const selectedOption = this.options[this.highlightedPosition]
                this.keyword = selectedOption.title
                this.isOpen = false
                this.$emit('select', selectedOption)
            },
            updateOptions(options){
                this.options = options;
            },
            search: function() {
                  //this.loading = true;
                  this.$http.post('vue/recherche', { term:this.keyword }).then((response) => {
                      //this.updateArrets(response.body.options);
                      console.log(response);
                        // self.loading = false;
                  }, (response) => { }).bind(this);
            },
        }
    }
</script>
