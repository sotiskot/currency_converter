<template>
    <div class="text-center">
        <form @submit.prevent="exchange">
            <input type="text" v-model="input">
            <select v-model="selectedFrom" @change="matchCurrency">
                <option disabled value="">Select Currency</option>
                <option v-for="item in JSON.parse(currencies)" v-bind:value="item.code">{{ item.name }}</option>
            </select>
            <br>
            <input type="text" v-model="output" disabled>
            <select v-model="selectedTo" ref="test" disabled>
                <option disabled value="">Select Currency</option>
                <option v-for="item in available" v-bind:value="item.code">{{ item.name }}</option>
            </select>
            <br>
            <button>exchange</button>
        </form>
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
                selectedFrom: '',
                selectedTo: '',
                available: []
            }
        },

        methods: {
            exchange: function () {
                var rates = JSON.parse(this.$props.rates);
                var rate = '';
                var temp;
                
                const key = this.selectedFrom + this.selectedTo;
                const rkey = this.selectedTo + this.selectedFrom;
                
                if( rates.filter(r => r.currencies == key).length > 0)
                {
                    rate = parseFloat(rates.find(r => r.currencies == key).rate);
                }else
                {
                    rate = 1/parseFloat(rates.find(r => r.currencies == rkey).rate) * this.input;
                }

                this.output = rate * this.input;
            },
            
            matchCurrency: function (){
                var rates = JSON.parse(this.$props.rates);
                var currencies = JSON.parse(this.$props.currencies);
                const key = this.selectedFrom;
                var available = rates.filter(r => r.currencies.includes(key));
                available.forEach(element => {
                    var currency = currencies.filter(r => r.code == element.currencies.replace(key, ""));
                    if(currency.length>0)
                        this.available.push(currency[0]);
                });
                
                this.$refs.test.disabled = false;
            }
        },
        mounted() {
            console.log(window)
        }
    }
</script>