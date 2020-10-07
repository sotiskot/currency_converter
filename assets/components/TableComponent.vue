<template>
    <div class="text-center">
        <table>
            <tr>
                <th>From</th>
                <th>To</th>
                <th>Rate</th>
            </tr>
            <tr v-for="item in newRates">
                <td>{{ matchCurrency(item.currencies.substring(0,3)) }}</td>
                <td>{{ matchCurrency(item.currencies.substring(3,6)) }}</td>
                <td>{{ item.rate }}</td>
                <td hidden>{{ item.currencies }}</td>
                <td><button @click="delRates($event, item)">X</button></td>
            </tr>
        </table>
        <form @submit.prevent="addNewRate">
            <label for="from">From: </label>
            <select name="" id="from" v-model="from">
                <option v-for="item in JSON.parse(currencies)" v-bind:value="item.code">{{ item.name }}</option>
            </select>
            <label for="to">To: </label>
            <select name="" id="to" v-model="to">
                <option v-for="item in JSON.parse(currencies)" v-bind:value="item.code">{{ item.name }}</option>
            </select>
            <label for="rate">Rate: </label><input type="text" id="rate" v-model="rate"><br>
            <button>+</button><span>{{ message }}</span>
        </form>
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
                message: ''
            }
        },

        computed:{
            newRates: function(){
                var rates = [];
                JSON.parse(this.$props.rates).forEach(element => {
                    rates.push(element)
                });
                return rates;
            }
        },

        methods:{
            matchCurrency: function (e) {
                var currencies = JSON.parse(this.currencies);
                var test = currencies.filter(r => r.code == e);
                return test[0].name;
            },

            addNewRate: function (){
                axios.post('/add', {
                    from: this.from,
                    to: this.to,
                    rate: this.rate
                }).then(response => {
                    if(response.data.failed == true)
                    {
                        this.message = response.data.message;
                    }else{
                        this.message = response.data.message;
                        this.newRates.push( { 'currencies': this.from + this.to, 'rate': this.rate });
                    }
                    
                })
            },

            delRates: function (event, target){
                axios.post('/delete', {
                    currencies: target
                }).then(response => {
                    //
                });
            }
        },

        mounted() {
            console.log(window)
        }
    }
</script>