<template>
    <div>
        <fieldset class="field_clone_order" id="" v-for="(row,index) in choosen">
            <div class="row">
                <div class="col-lg-6 col-md-5 col-xs-12">
                    <label style="display: block;">Produit</label>
                    <select :name="'order[products]['+ index +']'" v-model="row.products" :data-index="index" class="chosen-select form-control" data-placeholder="produits">
                        <option value="">Choix</option>
                        <option v-for="product in products" v-bind:value="product.id">{{ product.title }}</option>
                    </select>
                </div>
                <div class="col-lg-1 col-md-2 col-xs-12">
                    <label>Quantité</label>
                    <input class="form-control" @change="check(index)" @blur="hide(index)" required type="number" v-model="row.qty" value="" :name="'order[qty][' + index + ']'">
                    <span :id="'error_' + index" style="display:none; font-size: 10px; color: red;">Plus assez de stock</span>
                </div>
                <div class="col-lg-1 col-md-2 col-xs-12">
                    <label>Rabais</label>
                    <div class="input-group">
                        <input class="form-control" value="" type="text" v-model="row.rabais" :name="'order[rabais][' + index + ']'">
                        <span class="input-group-addon">%</span>
                    </div><!-- /input-group -->
                </div>
                <div class="col-lg-1 col-md-2 col-xs-12">
                    <label>Prix spécial</label>
                    <div class="input-group">
                        <input class="form-control" value="" type="text" v-model="row.price" :name="'order[price][' + index + ']'">
                        <span class="input-group-addon">CHF</span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-1 col-xs-12">
                    <label></label>
                    <div class="checkbox">
                        <label><input type="checkbox" :name="'order[gratuit][' + index + ']'" v-model="row.gratuit" value="1"> Livre gratuit</label>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-xs-12 text-right">
                    <label>&nbsp;</label><p><a href="#" class="btn btn-danger btn-sm remove_order">x</a></p>
                </div>
            </div>
        </fieldset>

        <button @click="add"></button>
        <p><button class="btn btn-sm btn-default" type="button" @click="add"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un produit</button></p>
    </div>
</template>

<script>
    export default {
        props: ['products','old'],
        data () {
            return {
                choosen: [{
                    products:null,
                    qty :null,
                    rabais :null,
                    price :null,
                    gratuit :null,
                }],
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.init();
        },
        methods: {
            init(){
                this.$nextTick(function(){
                    var self = this;
                    $('.chosen-select').chosen({
                        templateSelection: function (data, container) {
                            // Add custom attributes to the <option> tag for the selected option
                            $(data.element).attr('data-index', data.index);
                            return data.text;
                        }
                    });

                    $('.chosen-select').on('change', function (e) {

                        let data = $(this).val();
                        let index = $(this).attr('data-index');
                        // hide error
                        $('#error_' + index).hide();

                        // put selected product in choosen
                        self.choosen[index].products = data;
                        let qty = self.choosen[index].qty;

                        // check
                        if(qty > 0){
                            self.check(index);
                        }

                        console.log(self.choosen[index].products);
                    });
                });
            },
            add() {
                this.choosen.push({
                    products:null,
                    qty:null,
                    rabais:null,
                    price:null,
                    gratuit:null
                });
                this.init();
            },
            remove() {
                this.choosen.splice(-1);
            },
            hide(index){
                $('#error_' + index).hide();
            },
            check : function(index){

                let id = this.choosen[index].products
                let qty = this.choosen[index].qty

                var self = this;

                axios.post('/admin/stock/qty', { id: id, qty : qty }).then(function (response) {

                    if(response.data.result === 0){
                        $('#error_' + index).show();
                    }
                    else{
                        $('#error_' + index).hide();
                    }

                }).catch(function (error) { console.log(error);});
            }
        }
    }
</script>
