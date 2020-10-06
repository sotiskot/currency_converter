<template>
    <div class="text-center">
        <input @keypress="isNumber($event)" v-model="input" :style="hidden" >
        <button @click="show">{{ buttonText }}</button>
    </div>
</template>

<script>
    export default {
        props:{
            test: String
        },

        data: function (){
                return{
                    buttonText: 'Edit Rate',
                    hidden: 'display:none;',
                    input: ''
                }
        },

        methods:{
            show: function () {
                this.hidden = 'display:inline-block;';
                this.buttonText = 'Submit new Rate'
            },

            edit: function () {

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
                    if(charCode == 46 && this.input.includes('.'))
                    {
                        evt.preventDefault();;
                    }
                    return true;
                }
            }
        },

        mounted() {
            console.log(window)
        }
    }
</script>