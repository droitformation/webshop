<template>

  <div class="wrapper relative">
    <div class="filter-wrap" id="filter-wrap">
      <h4>Questions</h4>
      <div class="card card-full">
        <div class="card-body card-body-modele">
          <p class="mb-1"><input type="text" v-model="search" placeholder="Recherche" class="form-control"/></p>
          <div class="filter-types">
            <label class="checkbox-inline"><input v-model="type" type="radio" name="type" id="chapitre" value="chapitre"> Chapitre</label>
            <label class="checkbox-inline"><input v-model="type" type="radio" name="type" id="text" value="text"> Texte</label>
            <label class="checkbox-inline"><input v-model="type" type="radio" name="type" id="checkbox" value="checkbox"> Case à cocher</label>
            <label class="checkbox-inline"><input v-model="type" type="radio" name="type" id="radio" value="radio"> Option à choix</label>
            <label class="checkbox-inline"><input v-model="type" type="radio" id="clear" name="type" value=""> Tout</label>
          </div>
        </div>
      </div>
      <div class="model-avis">
        <div class="list">
          <drag v-for="avi in filteredList" :data="avi" class="item" :key="avi.id">
            <span>{{ avi.type_name }}</span>
            <div :class="'question-text question-type-' + avi.type" v-html="avi.question_simple + ' | ' + avi.id"></div>
          </drag>
        </div>
      </div>
    </div>

    <div id="sondage_dragdrop">
      <h4>Contenu</h4>

      <slot name="update"></slot>

      <div class="card card-full">
        <div class="card-body">

          <p class="empty" v-if="items.length == 0">Placer des questions ici.</p>

          <drop-list
              :items="items"
              class="list list_main"
              @insert="onInsert"
              @reorder="$event.apply(items)"
          >
            <template v-slot:item="{item}">
              <drag class="item-choosen" :key="item.id+'_'+item.rang">
                <div class="list-item-choosen">
                  <div :class="'question-type-' + item.type"><p class="question-p">{{ item.question_simple }} | {{ item.id }}</p></div>
                  <ul class="question-ul" v-if="item.choices_list">
                    <li v-for="choice in item.choices_list">
                      <label :class="item.type + '-inline'">
                        <input :type="item.type" disabled>  {{ choice }}
                      </label>
                    </li>
                  </ul>
                  <p class="question-textarea" v-if="item.type == 'text'"><textarea class="form-control" disabled></textarea></p>
                  <button @click="remove(item.id)" class="btn btn-danger btn-xs">x</button>
                </div>
              </drag>
            </template>
            <template v-slot:feedback="{data}">
              <div class="item feedback" :key="data.id+'_'+data.rang">{{ data.type_name }}</div>
            </template>
          </drop-list>

        </div>
      </div>
    </div>

  </div>

</template>

<script>

import { Drag, DropList } from "vue-easy-dnd";

export default {
  props: ['avis','current'],
  mounted() {
    console.log('Component mounted.');

    let $wrapper = $('#sondage_dragdrop');
    let $sidebar = $('#filter-wrap');

    $(window).scroll(function(){
      var top = $(window).scrollTop();

      if (($wrapper.offset().top) < top) {
         $sidebar.addClass("sticky");
      } else {
         $sidebar.removeClass("sticky");
      }
    });

    let bar    = 230;
    let width  = $('body').innerWidth();
    let height = $('body').innerHeight();

    width = width - bar;

    let max = height * 0.35;

    let s_width = (width - 10) * 0.35;
    let w_width = width * 0.62;

    if(width < 1680){
      $sidebar.css('width',s_width - 20);
      $wrapper.css('width',w_width - 15);
      $wrapper.css('margin-left',s_width + 10);
    }
    else{
      $sidebar.css('width',s_width);
      $wrapper.css('width',w_width);
      $wrapper.css('margin-left',s_width + 5);
    }

  },
  components: {
    Drag,
    DropList
  },
  data() {
    return {
      items : this.current ?? [],
      questions: this.avis,
      search: '',
      type: '',
    }
  },
  computed: {
    filteredList() {
      return Object.values(this.questions).filter(question => {
        return question.question_simple.toLowerCase().includes(this.search.toLowerCase())
      });
    },
    choosen() {
      return Object.values(this.items).map(function (item, index){
        return {
          id: item.id,
          rang: index
        }
      });
    },
  },
  watch: {
    type: function (val) {
      this.clear();
      if(this.type){
        this.questions = Object.values(this.questions).filter(question => {
          console.log('question '+ question.type);
          console.log('type '+ val);
          return question.type == val;
        });
      }
    }
  },
  methods: {
    onInsert(event) {
       event.data.rang = event.index;
       this.items.splice(event.index, 0, event.data);

       this.$emit('update-list', this.choosen);
    },
    remove(id) {
       let index = this.items.map(item => item.id).indexOf(id);
       console.log('index '+ index);
       this.items.splice(index, 1);

       this.$emit('update-list', this.choosen);
    },
    clear(){
       this.questions = this.avis;
    },

  }
}
</script>

<style scoped>

.sticky {
  position:fixed;
  top:85px;
}

.inline{
  display: inline;
}
.filter-types{
  display: flex;
  height: 55px;
  flex-direction: row;
  flex-wrap: wrap;
  align-items: center;
  align-content: center;
  justify-content: center;
}
.filter-types label{
  height: 30px;
  line-height: 20px;
  padding: 5px;
}
#sondage_dragdrop{
  overflow: scroll;
}
.relative{
  position: relative;
}
.filter-wrap{
  position: fixed;
  max-width: 495px;
}
.d-block{
  display: block;
}
.card{
  margin-bottom: 14px;
}
.hidden{
  display: none;
}
.card-body-modele{
  padding: 5px;
}
.radio-inline,
.checkbox-inline {
  padding-left: 10px;
  font-size: 13px;
}
.model-avis{
  overflow: scroll;
  padding:0 5px 5px 5px;
  background: #fff;
  max-height: 50vh;
  border: 1px solid rgba(0,0,0,.125);
}
.drop-in {
  box-shadow: 0 0 10px rgba(0, 0, 255, 0.3);
}

.wrapper .list {
  margin-top: 0;
}
.wrapper .list.list_main {
  min-height: 50px;
  width: 100%;
  border: 1px solid #eee;
  padding: 15px;
  background: #fbfbfb;
}

.wrapper .list .item {
  padding: 15px  10px;
  margin: 10px 0;
  display: flex;
  flex-direction: column;
  align-items: start;
  position: relative;
  background-color: #f7f8fa;
  border: 1px solid #e0e5ee;
}

.wrapper .list_main .item-choosen{
  padding:  10px;
  margin-bottom: 5px;
  position: relative;
}

.wrapper .list_main .item-choosen button{
  position: absolute;
  top: 8px;
  right:8px;
  display: none;
}

.wrapper .list_main .item-choosen:hover{
  background: #fff;
  box-shadow: 0 0 5px rgba(0, 0, 155, 0.3);
}

.wrapper .list_main .item-choosen:hover button{
  display: block;
}

.wrapper .list .item span{
  display: block;
  position: absolute;
  top: 8px;
  right: 8px;
  font-size: 11px;
  color: #d4a569;
}

.wrapper .list .item .question-text{
  padding: 5px;
  width: 80%;
}

.wrapper .list .item.feedback {
  background-color: #ffdcdc;
  border: 2px dashed black;
}

.question-p{
  margin-bottom: 6px;
  font-weight: 600;
}

.question-ul{
  margin-top: 15px;
  margin-left: 10px;
  padding-left: 0;
  display: flex;
  flex-direction: row;
  justify-content: start;
}

.question-ul li{
  list-style: none;
  margin-bottom: 5px;
  padding-left: 5px;
  display: block;
  padding-right: 51px;
}

.question-textarea{
  margin-top: 15px;
}

.mb-1{margin-bottom: 10px}
.pb-1{padding-bottom: 10px}

.question-type-chapitre{
  font-size: 16px;
  text-transform: uppercase;
  font-weight: bold;
}
.dl-simple{
  margin-bottom: 0;
}
.empty{
  padding: 5px;
  text-align: center;
}
</style>