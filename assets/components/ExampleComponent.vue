<template>
    <div class="text-center">
        <form @submit.prevent="test">
            <p>{{ rates }}</p>
            <input type="text" v-model="input"><select v-model="selectedFrom"><option v-for="(item, currency) in currencies" v-bind:value="currency">{{ item }}</option></select><br>
            <input type="text" v-model="output" disabled><select v-model="selectedTo"><option v-for="(item, currency) in currencies" v-bind:value="currency">{{ item }}</option></select><br>
            <button>exchange</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            rates: String
        },

        data: function() {
            return {
                output: '0',
                input: '',
                selectedFrom: '',
                selectedTo: '',
                currencies: {
                    eur: 'Euro',
                    usd: 'Us dollar',
                    sfr: 'Swiss Franc',
                    brp: 'British Pound',
                    cad: 'Canadian dollar',
                    jpy: 'Japanese Yen'
                    },
                
            }
        },

        methods: {
            test: function () {
                var rates = JSON.stringify(this.$props.rates);
                var rate = 0;
                console.log(rates[0]);
                if( rate = parseFloat(rates[String(this.selectedFrom + this.selectedTo)]))
                {
                    this.output = rate * this.input;
                }else if( rate = parseFloat(rates[String(this.selectedTo + this.selectedFrom)]))
                {
                    this.output = 1/rate * this.input;
                    console.log(rate);
                }
                //this.output = parseFloat(this.rates[String(this.selectedFrom + this.selectedTo)]) * this.input;
            }
        },
        mounted() {
            console.log(window)
        }
    }
</script>