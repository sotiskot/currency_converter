<template>
    <div class="container text-center mt-5">
        <table class="table">
            <tr class="bg-primary text-white">
                <th>Coin</th>
                <th>Code</th>
                <td></td>
            </tr>
            <tr v-for="currency in newCurrencies" v-bind:id="currency.id">
                <td>{{ currency.name }}</td>
                <td>{{ currency.code }}</td>
                <td><button class="btn btn-danger" @click="delCurrency(currency)">X</button></td>
            </tr>
            <tr>
                <td>
                    <input class="form-control text-center" type="text" placeholder="Name" v-model="name">
                </td>
                <td>
                    <input class="form-control text-center" type="text" placeholder="Code" v-model="code">
                </td>
                <td>
                    <button class="btn btn-primary" @click="addCurrency">+</button>
                </td>
            </tr>
        </table>
        <h5 :style="style">{{ message }}</h5>
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
                    color: '',
                    backgroundColor: ''
                }
            }
        },

        computed:{
            newCurrencies: function (){
                /**
                 *  Create a new array for props currencies
                 *  in order to be able to Update in the DOM
                 */  
                var currencies = [];
                JSON.parse(this.$props.currencies).forEach(element => {
                    currencies.push(element)
                });
                return currencies;
            }
        },

        methods: {
            addCurrency: function() {
                this.message = '';

                // make an ajax post request with currency name and code
                axios.post('/add_currency', {
                    name: this.name,
                    code: this.code
                }).then(response => {
                    if(response.data.failed == true)
                    {
                        /**  
                        *   if response indicates failure show message to user and change color
                        *   for it's container
                        */
                        this.style.color = "red";
                        this.style.backgroundColor = "yellow";
                        this.message = response.data.message;
                    }else{
                        /**
                         *  If response wasn't a fail then add new property to the
                         *  array responsible for the table data for the DOM to show,
                         *  show success message to the user and change to success colors
                         *  for the message
                         */ 
                        this.ne
                        this.style.color = "white";
                        this.style.backgroundColor = "green";
                        this.message = response.data.message;

                        this.newCurrencies.push( { 'id': this.newCurrencies.length+2, 'name': this.name, 'code': this.code });
                    }
                    this.message = response.data.message
                })
            },

            delCurrency: function (target){ 
                //  reset message
                this.message = '';

                //  axios request with currency code
                axios.post('/delete_currency', {
                    currency: target.code
                }).then(response => {
                    /**
                     *  on response show message
                     *  "Remove" array entry for DOM not to show
                     *  change message color palette
                     */ 

                    this.message = response.data.message
                    this.style.color = 'white'
                    this.style.backgroundColor = "green";
                    document.getElementById(target.id).innerHTML = '';
                })
            }
        },

        mounted() {
            console.log(window)
        }
    }
</script>