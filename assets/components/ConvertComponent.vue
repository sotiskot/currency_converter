<template>
    <div class="container text-center">
        <div class="col-6 m-auto">
            <input class="form-control text-center" type="text" placeholder="ammount" @keypress="isNumber($event)" v-model="input">
            <select class="custom-select" v-for="(item, index) in select" @change="addSelect($event, index)"  v-bind:id="index" >
                <option disabled selected>Select Currency</option>
                <option v-bind:value="coin.code" v-for="coin in JSON.parse(select[index])">{{ coin.name }}</option>
            </select>
            <input class="form-control text-center" type="text" placeholder="result" v-model="result" disabled>
            <button class="btn btn-success form-control" ref="convert" @click="convert" disabled>convert</button>
            <button class="btn btn-primary form-control" @click="reset">reset</button>
        </div>
    </diV>
</template>

<script>
    export default {
        props: {
            currencies: String,
            rates: String
        },

        data: function (){
            return{
                result: '',
                select: [this.$props.currencies],
                selected: [''],
                input: ''
            }
        },

        methods:{
            //  On reset button press make sure select fields are reset in the DOM
            reset: function () {
                this.select = [this.$props.currencies];
                this.selected = [''];
                this.$refs.convert.disabled = true;
                this.input = '';
                this.result = '';
                document.getElementById(0).disabled = false;
            },

            addSelect: function (event, index){
                //  if the next index matches the select array length go ahead and add a new select
                if(index+1 == this.select.length)
                { 
                    var rates = JSON.parse(this.$props.rates);
                    var currencies = JSON.parse(this.$props.currencies);
                    const key = event.target.value;
                    
                    //  using the select value as key find all rates that much selected currency
                    var available = rates.filter(r => r.currencies.includes(key));
                    var tempAvailable = [];
                    available.forEach(element => {
                        /**
                         * run through the available exchange rates extract the second code
                         * and push that into the select array for 2nd select
                         */  
                        var currency = currencies.filter(r => r.code == element.currencies.replace(key, ""));
                        if(currency.length>0)
                            tempAvailable.push(currency[0]);
                    });
                    this.select.push(JSON.stringify(tempAvailable));
                    this.selected.push('');
                }
                //  check if selected length is 3 or more and enable convert button if less disable it(on reset)
                (this.selected.length >= 3 ) ? this.$refs.convert.disabled = false : this.$refs.convert.disabled = true;
                event.target.disabled = true;
                this.selected[index] = event.target.value;
            },

            
            
            isNumber: function(evt) {
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
                    if(charCode == 46 && event.target.value.includes('.'))
                    {
                        evt.preventDefault();;
                    }
                    return true;
                }
            },

            convert: function () {
                /**
                 *  Find all selected currency codes and create an array with all relations
                 *  ex ['usd', 'eur', 'cad'] => ['usd->eur', 'eur->cad']
                 *  Find rates with those relations and convert from input to each relation in order
                 *  ex input=1, 1 * usdeur.rate * eurcad.rate
                 */
                var rates = JSON.parse(this.$props.rates);
                console.log(this.selected);
                var key = [];
                var tempResult = this.input;
                
                // create array of rate currencies
                this.selected.forEach((element, index) => {
                    if(element != '' && this.selected[index+1] != ''){
                        key.push(this.selected[index] + this.selected[index+1]);
                    }
                });

                /**
                 * rates only has 1 value for each relation ex eur->usd = eurusd, usd->eur = 1/eurusd,
                 * Check if the relation is in order or reverse order and apply the proper conversio.
                 */
                key.forEach(element => {
                    if( rates.filter(r => r.currencies == element).length > 0)
                    {
                        tempResult = parseFloat(rates.find(r => r.currencies == element).rate) * tempResult;

                    }else // if not then reverse combination is used in order to use the known rate exchange
                    {
                        tempResult = 1/parseFloat(rates.find(r => r.currencies == element.substr(3,6)+element.substr(0,3)).rate) * tempResult;
                    }
                });
                
                this.result = tempResult;
            }
        },

        mounted() {
            console.log(window)
        }
    }
</script>