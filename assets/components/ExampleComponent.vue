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
                rates: {
                    'eurusd': '1.3764',
                    'eursf': '1.2079',
                    'eurbp': '0.8731',
                    'usdjpy': '76.7200',
                    'sfusd': '1.1379',
                    'bpcad': '1.5648'
                }
            }
        },
        methods: {
            test: function () {
                console.log(this.selectedFrom + this.selectedTo);
                this.output = parseFloat(this.rates[String(this.selectedFrom + this.selectedTo)]) * this.input;
            }
        },
        mounted() {
            console.log(window)
        }
    }
</script>