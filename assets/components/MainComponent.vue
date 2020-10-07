<template>
    <div class="container text-center">
        <form @submit.prevent="exchange">
            <div class="form-row">
                <div class="form-col col-6">
                    <select class="custom-select" v-model="selectedFrom" @change="matchCurrency" >
                        <option disabled value="">Select Currency</option>
                        <!-- select option using data from database  currency name and currency code as value -->
                        <option v-for="item in JSON.parse(currencies)" v-bind:value="item.code">{{ item.name }}</option>
                    </select>
                </div>
                <div class="form-col col-6">
                    <input class="form-control" type="text" v-model="input" @keypress="isNumber($event, input)">
                </div>      
            </div>
            <div class="form-row">
                <div class="form-col col-6">
                    <select class="custom-select" v-model="selectedTo" ref="test" disabled @change="allowExchange">
                        <option disabled value="">Select Currency</option>
                        <!-- select option using available exchange rates  -->
                        <option v-for="item in available" v-bind:value="item.code">{{ item.name }}</option>
                    </select>
                </div>
                <div class="form-col col-6">
                    <input class="form-control" type="text" v-model="output" disabled>
                                <!-- Call edit component and pass rates -->
                    <button class="btn btn-success form-control" ref="exchange" @click="allowEdit" disabled>exchange</button>
                </div>
            </div>


        </form>
        <input v-model="editInput" @keypress="isNumber($event, editInput)" :style="editHidden" >
        <button class="btn btn-primary m-1" ref="edit" @click="show" disabled>{{ buttonText }}</button>
        <p style="color:yellow">{{ editText }}</p>
    </div>
</template>

<script>
    export default {
        props: {
            currencies: String,
            rates: String
        },

        data: function() {
            return {
                output: '0',
                input: '',
                editInput: '',
                selectedFrom: '',
                selectedTo: '',
                available: [],
                dot: false,
                buttonText: 'Edit Rate',
                editHidden: 'display:none;',
                reverse: true,
                editText: ''
            }
        },

        methods: {

            /**
             * Manages the exchange rate between the 2 select fields.
             */
            exchange: function () {
                var rates = JSON.parse(this.$props.rates);
                var rate = '';
                var temp;
                
                // create a value combination of the 2 selects ex. eurusd
                const key = this.selectedFrom + this.selectedTo;
                // create a value combination of the reverse of the 2 selects ex usdeur -> reverse = eurusd
                const rkey = this.selectedTo + this.selectedFrom;
                

                // checks if the key combination is known by the data sent by database 
                if( rates.filter(r => r.currencies == key).length > 0)
                {
                    rate = parseFloat(rates.find(r => r.currencies == key).rate);
                    this.reverse = true;

                }else // if not then reverse combination is used in order to use the known rate exchange
                {
                    rate = 1/parseFloat(rates.find(r => r.currencies == rkey).rate) * this.input;
                    this.reverse = false;
                }

                this.output = rate * this.input;
            },
            
            matchCurrency: function (){

                /**
                 * get props and format them into json
                 */
                var rates = JSON.parse(this.$props.rates);
                var currencies = JSON.parse(this.$props.currencies);

                // use the 1st select as key
                const key = this.selectedFrom;

                // based on key find known any exchange rate that uses that code - prone to bugs if large_scale
                var available = rates.filter(r => r.currencies.includes(key));

                available.forEach(element => {
                    /**
                     * run through the available exchange rates extract the second code
                     * and push that into the select array for 2nd select
                     */  
                    var currency = currencies.filter(r => r.code == element.currencies.replace(key, ""));
                    if(currency.length>0)
                        this.available.push(currency[0]);
                });
                
                this.$refs.test.disabled = false;
            },

            isNumber: function(evt, input) {
                /**
                 * run this event every time the user tries to input any character
                 * extract the key code of that character
                 */
                evt = (evt) ? evt: window.event;
                var charCode = (evt.which) ? evt.which : evt.KeyCode;

                // if keycode is not backspace number or . prevent input
                if((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46)
                {
                    evt.preventDefault();;
                }else
                {
                    // if keycode is dot check the whole input and allow only 1 to exist at a time
                    if(charCode == 46 && input.includes('.'))
                    {
                        evt.preventDefault();;
                    }
                    return true;
                }
            },

            show: function () {
                var key = '';
                if( this.editHidden == 'display:none;' )
                {
                    this.editHidden = 'display:inline-block;';
                    this.buttonText = 'Submit new Rate'
                }else{
                    (this.reverse) ? key = this.selectedFrom + this.selectedTo : key = this.selectedTo + this.selectedFrom;
                    axios.post('/edit', {
                        currencies: key,
                        rate: this.editInput
                    }).then((response)=>{
                        this.editText = response.data.message
                    });
                }
                    
            },

            allowExchange: function () {
                this.$refs.exchange.disabled = false;
            },

            allowEdit: function () {
                this.$refs.edit.disabled = false;
            }
        },

        mounted() {
            console.log(window)
        }
    }
</script>