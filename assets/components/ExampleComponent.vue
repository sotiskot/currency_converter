<template>
    <div>
        <form @submit.prevent="test">
            <p>{{ output }}</p>
            <p>{{ rates }}</p>
            <input type="number" v-model="input"><select v-model="selectedFrom"><option v-for="(item, currency) in currencies" v-bind:value="currency">{{ item }}</option></select><br>
            <input type="number"><select v-model="selectedTo"><option v-for="(item, currency) in currencies" v-bind:value="currency">{{ item }}</option></select><br>
            <button>exchange</button>
        </form>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                output: '0',
                input: '1',
                selectedFrom: '',
                selectedTo: '',
                currencies: {
                    euro: 'Euro',
                    usd: 'Us dollar',
                    sf: 'Swiss Franc',
                    bp: 'British Pound',
                    cad: 'Canadian dollar',
                    jpy: 'Japanese Yen'
                    },
                rates: {
                    'eurousd': '1.3764',
                    'eurosf': '1.2079',
                    'eurobp': '0.8731',
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