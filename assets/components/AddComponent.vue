<template>
    <div class="container text-center mt-5">
        <table class="table">
            <tr class="bg-primary text-white">
                <th>Coin</th>
                <th>Code</th>
                <td></td>
            </tr>
            <tr v-for="coin in newCurrencies" v-bind:id="coin.id">
                <td>{{ coin.name }}</td>
                <td>{{ coin.code }}</td>
                <td><button class="btn btn-danger" @click="delCoin(coin)">X</button></td>
            </tr>
            <tr>
                <td>
                    <input class="form-control text-center" type="text" placeholder="Name" v-model="name">
                </td>
                <td>
                    <input class="form-control text-center" type="text" placeholder="Code" v-model="code">
                </td>
                <td>
                    <button class="btn btn-primary" @click="addCoin">+</button>
                </td>
            </tr>
        </table>
        <h5 class="bg-warning" :style="style">{{ message }}</h5>
    </div>
</template>

<script>
    export default {
        props:{
            currencies: String
        },

        data: function () {
            return{
                name: '',
                code: '',
                message: '',
                style: {
                    color: ''
                }
            }
        },

        computed:{
            newCurrencies: function (){
                var currencies = [];
                JSON.parse(this.$props.currencies).forEach(element => {
                    currencies.push(element)
                });
                return currencies;
            }
        },

        methods: {
            addCoin: function() {
                this.message = '';
                axios.post('/add_coin', {
                    name: this.name,
                    code: this.code
                }).then(response => {
                    if(response.data.failed == true)
                    {
                        this.style.color = 'red'
                        this.message = response.data.message;
                    }else{
                        this.style.color = 'green'
                        this.message = response.data.message;

                        /**
                         *  If response wasn't a fail then add new property to the
                         *  array responsible for the table data for the DOM to show
                         */ 
                        this.newCurrencies.push( { 'id': this.newCurrencies.length, 'name': this.name, 'code': this.code });
                    }
                    this.message = response.data.message
                })
            },

            delCoin: function (target){ 
                this.message = '';
                axios.post('/delete_coin', {
                    coin: target.code
                }).then(response => {
                    this.message = response.data.message
                    this.style.color = 'green'
                    document.getElementById(target.id).innerHTML = ''; //temp
                })
            }
        },

        mounted() {
            console.log(window)
        }
    }
</script>