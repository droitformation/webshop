<template>

  <div class="wrapper relative">

      <div class="filter-wrap" id="filter-wrap">
        <div class="card card-full">
          <div class="card-body card-body-modele">
              <p class="mb-1"><input type="text" v-model="search" placeholder="Recherche" class="form-control"/></p>
              <div>
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
              <div :class="'question-text question-type-' + avi.type" v-html="avi.question_simple"></div>
            </drag>
          </div>
        </div>
      </div>

    <div id="sondage_dragdrop">

        <div class="card card-full">
          <div class="card-body">
            <h3><i class="fa fa-edit"></i> &nbsp;{{ modele.title }}</h3>
            <p><i class="fa fa-edit"></i> &nbsp;{{ modele.description }}</p>
          </div>
        </div>

        <div class="card card-full">
          <div class="card-body">

            <drop-list
                :items="items"
                class="list list_main"
                @insert="onInsert"
                @reorder="$event.apply(items)"
            >
              <template v-slot:item="{item}">
                <drag class="item-choosen" :key="item.id">
                  <div>
                    <div :class="'question-type-' + item.type"><p class="question-p">{{ item.question_simple }}</p></div>
                    <ul class="question-ul" v-if="item.choices_list">
                      <li v-for="choice in item.choices_list">
                        <label :class="item.type + '-inline'">
                           <input :type="item.type" disabled>  {{ choice }}
                        </label>
                      </li>
                    </ul>
                    <p class="question-textarea" v-if="item.type == 'text'"><textarea class="form-control" disabled></textarea></p>
                  </div>
                </drag>
              </template>
              <template v-slot:feedback="{data}">
                <div class="item feedback" :key="data.id">{{ data.type_name }}</div>
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
    props: ['avis','modele'],
    mounted() {
      console.log('Component mounted.');

      let bar    = 230;
      let width  = $('body').innerWidth();
      let height = $('body').innerHeight();

      width = width - bar;

      let $wrapper = $('#sondage_dragdrop');
      let $sidebar = $('#filter-wrap');

      let max = height * 0.35;

      let s_width = (width - 10) * 0.35;
      let w_width = width * 0.62;

      $sidebar.css('width',s_width);
      $wrapper.css('width',w_width);
      $wrapper.css('margin-left',s_width + 5);
    },
    components: {
      Drag,
      DropList
    },
    data() {
      return {
         items : [],
         choosen: [],
         questions: this.avis,
         search: '',
         type: ''
      }
    },
    computed: {
      filteredList() {
        return Object.values(this.questions).filter(question => {

            return question.question_simple.toLowerCase().includes(this.search.toLowerCase())
        });
      }
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
          this.items.splice(event.index, 0, event.data);
      },
      clear(){
        this.questions = this.avis;
      }
    }
  }
</script>

<style scoped>
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
}

.question-ul li{
  list-style:none;
  margin-bottom: 5px;
  padding-left: 5px;
}

.question-textarea{
  margin-top: 15px;
}

.mb-1{margin-bottom: 15px}
.pb-1{padding-bottom: 10px}

.question-type-chapitre{
  font-size: 16px;
  text-transform: uppercase;
  font-weight: bold;
}
</style>