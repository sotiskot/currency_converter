<template>
    <div class="container text-center mt-5">
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
                <td><input class="form-control text-center" type="text" v-bind:id="'text'+item.id" v-model="item.rate" @blur="edit($event, item)" @keyup.enter="$event.target.blur()" disabled></td>
                <td hidden>{{ item.currencies }}</td>
                <td>
                    <button class="btn btn-success" @click="enable(item)">edit</button>
                    <button class="btn btn-danger" @click="delRates(item)">X</button>
                </td>
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
        <h5 :style="style">{{ message }}</h5>
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
                    color: '',
                    backgroundColor: ''
                }
            }
        },

        computed:{
            // Add prop to a computed vue data in order to be able to alter it in DOM
            newRates: function(){
                var rates = [];
                JSON.parse(this.$props.rates).forEach(element => {
                    rates.push(element)
                });
                return rates;
            }
        },

        methods:{
            enable: function(item){
                //  clicking edit enables input for rate and focuses in it
                document.getElementById('text'+item.id).disabled = false;
                document.getElementById('text'+item.id).focus();
            },

            // Return the name of the currency based on it's code
            matchCurrency: function (e) {
                var currencies = JSON.parse(this.currencies);
                var currency = currencies.filter(r => r.code == e);
                return currency[0].name;
            },


            /**
             *  Create an axios/ajax request to the server to add a new Rate
             *  Based on the response create an appropriate message to the user  
             */ 

            addNewRate: function (){
                 //  Ajax post request with Currency from -> to and their rate
                this.message = '';
                axios.post('/add_rate', {
                    from: this.from,
                    to: this.to,
                    rate: this.rate
                }).then(response => {
                    //  check if request failed, if it did show message and alter colors for it
                    if(response.data.failed == true)
                    {
                        this.style.color = 'red'
                        this.style.backgroundColor = "yellow"
                        this.message = response.data.message;
                    }else{
                        this.style.color = 'white'
                        this.style.backgroundColor = 'green';
                        this.message = response.data.message;

                        /**
                         *  If response wasn't a fail then add new property to the
                         *  array responsible for the table data for the DOM to show
                         */ 
                        this.newRates.push( { 'id': this.newRates.length+2, 'currencies': this.from + this.to, 'rate': this.rate });
                    }
                    
                })
            },

            delRates: function (target){ 
                this.message = '';
                // Ajax post request for deletion with currencies code
                axios.post('/delete_rate', {
                    currencies: target
                }).then(response => {
                    //  on response show deleted message and alter colors of it
                    this.message = response.data.message
                    this.style.color = 'white'
                    this.style.colo = 'green'
                    // Make the array element like that in Order for DOM not to show it
                    document.getElementById(target.id).innerHTML = ''
                })
            },
            
            edit: function (event, item){
                // Ajax post request for edit with currencies code and new rate value
                axios.post('/edit', {
                    currencies: item.currencies,
                    rate: event.target.value
                }).then(response => {
                    // If response indicates no failure go ahead and disable input alter colors for message
                    if(response.data.fail == false)
                    {
                        event.target.disabled = true;
                        this.style.color = 'white';
                        this.style.backgroundColor = 'green';
                    }else{
                        this.style.color = 'red';
                        this.style.backgroundColor = 'yellow';
                    }
                    this.message = response.data.message
                });
            }
        },

        mounted() {
            console.log(window)
        }
    }
</script>