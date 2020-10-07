<template>
    <div class="container text-center">
        <table class="table">
            <tr class="bg-primary text-white">
                <th>From</th>
                <th>To</th>
                <th>Rate</th>
                <td></td>
            </tr>
            <tr v-for="item in newRates" v-bind:id="item.id">
                <td>{{ matchCurrency(item.currencies.substring(0,3)) }}</td>
                <td>{{ matchCurrency(item.currencies.substring(3,6)) }}</td>
                <td>{{ item.rate }}</td>
                <td hidden>{{ item.currencies }}</td>
                <td><button class="btn btn-danger" @click="delRates($event, item)">X</button></td>
            </tr>
            <tr>
                <td>
                    <select name="" id="from" v-model="from" class="custom-select">
                        <option v-for="item in JSON.parse(currencies)" v-bind:value="item.code">{{ item.name }}</option>
                    </select>
                </td>
                <td>
                    <select name="" id="to" v-model="to" class="custom-select">
                        <option v-for="item in JSON.parse(currencies)" v-bind:value="item.code">{{ item.name }}</option>
                    </select>
                </td>
                <td>
                    <input type="text" id="rate" class="form-control" v-model="rate">
                </td>
                <td>
                    <button class="btn btn-primary" @click="addNewRate">+</button>
                </td>
            </tr>
        </table>
        <h1 class="bg-warning" :style="style">{{ message }}</h1>
    </div>
</template>

<script>
    export default {
        props: {
            currencies: String,
            rates: String
        },

        data: function () {
            return{
                from: '',
                to: '',
                rate: '',
                message: '',
                style: {
                    color: ''
                }
            }
        },

        computed:{
            // Add prop to a computed vue data in order to be able to alter it
            newRates: function(){
                var rates = [];
                JSON.parse(this.$props.rates).forEach(element => {
                    rates.push(element)
                });
                return rates;
            }
        },

        methods:{
            // Return the name of the currency based on it's code
            matchCurrency: function (e) {
                var currencies = JSON.parse(this.currencies);
                var test = currencies.filter(r => r.code == e);
                return test[0].name;
            },


            /**
             *  Create an axios/ajax request to the server to add a new Rate
             *  Based on the response create an appropriate message to the user  
             */ 

            addNewRate: function (){
                axios.post('/add', {
                    from: this.from,
                    to: this.to,
                    rate: this.rate
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
                        this.newRates.push( { 'currencies': this.from + this.to, 'rate': this.rate });
                    }
                    
                })
            },

            delRates: function (event, target){ 
                document.getElementById(target.id).innerHTML = ''; //temp
                axios.post('/delete', {
                    currencies: target
                }).then(response => {
                    this.message = response.data.message
                    this.style.color = 'green'
                })
            }
        },

        mounted() {
            console.log(window)
        }
    }
</script>